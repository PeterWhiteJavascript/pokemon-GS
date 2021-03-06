<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pokemon GS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/shared.js"></script>
    </head>
    <body>
        <div id="background-bingo"></div>
        <div id="audio-player" class="normal-text clickable">Play Music</div>
        <div id="create-new-bingo-card" class="normal-text clickable">Create New Card</div>
        <div id="filters">
            <div id="regions" class="filter-container">
                <div class="big-text">Regions</div>
                <div>Start - Azaelea</div><input type='checkbox' checked="true">
                <div>Azaelea - Olivine</div><input type='checkbox'>
                <div>Olivine - Ice Path</div><input type='checkbox'>
                <div>Ice Path - Elite 4</div><input type='checkbox'>
                <div>After Elite 4</div><input type='checkbox'>
                <div class="pre-select">R Spread</div><select><option selected>Evenly</option><option>Random</option></select>
            </div>
            <div id="difficulty" class="filter-container">
                <div class="big-text">Difficulty</div>
                <div>Easy</div><input type='checkbox' checked="true">
                <div>Medium</div><input type='checkbox' checked="true">
                <div>Difficult</div><input type='checkbox' checked="true">
                <div>Y tho?</div><input type='checkbox'>
                <div class="pre-select">D Spread</div><select><option>Evenly</option><option>Random</option><option selected>Favour Easy</option><option>Favour Medium</option><option>Favour Hard</option></select>
            </div>
            <div id="evolve" class="filter-container">
                <div class="big-text">Evolution</div>
                <div class="pre-select">Frequency</div><select><option>None</option><option selected>Some</option><option>Average</option><option>Many</option><option>All</option></select>
            </div>
            <div id="time" class="filter-container">
                <div class="big-text">Time</div>
                <div>Morning</div><input type='checkbox' checked="true">
                <div>Day</div><input type='checkbox' checked="true">
                <div>Night</div><input type='checkbox' checked="true">
            </div>
            <div id="misc" class="filter-container">
                <div class="big-text">Misc.</div>
                <div>Event Pokemon</div><input type='checkbox'>
                <div>Starter Pokemon</div><input type='checkbox'>
                <div>Legendary Dogs</div><input type='checkbox' checked="true">
                <div>Legendary Birds</div><input type='checkbox' checked="true">
                <div class="pre-select">Exclusives</div><select><option>None</option><option>Include</option><option selected>Split</option></select>
                <div class="pre-select">Grid Size</div><select><option>3x3</option><option selected>5x5</option><option>7x7</option></select>
                <div class="pre-select">Number of Cards</div><select><option>1</option><option selected>2</option><option>3</option><option>4</option><option>5</option></select>
            </div>
        </div>
        <div id="main-container"></div>
        <audio id="audio-element">
            <source src="audio/bingo.mp3" type="audio/mpeg">
          Your browser does not support the audio element.
        </audio>
        <script>
            $.getJSON( "data/pokemon.json", function( data ) {
                //Returns the pokemon's pokedex number, which corresponds to the sprite sheet position.
                function generatePokemon(allPokemon, howManyInEachRegion, regions, difficultySpread, difficulty, evoSpread, numOfPokemon){
                    var pokemon = [];
                    var numOfEvos = ~~(numOfPokemon * (evoSpread / 100));
                    var numSinceLastAdded = 0;
                    var duplicationAllowedAfter = allPokemon.length * 2;
                    var duplicatedTimes = 0;
                    while(pokemon.length < numOfPokemon){
                        numSinceLastAdded ++;
                        var poke = allPokemon[0];
                        for(var i = 0; i < regions.length; i++){
                            var region = regions[i];
                            var numberLeftInRegion = howManyInEachRegion[i];
                            if(numberLeftInRegion === 0) continue;
                            var regionData = poke.region[region];
                            if(!(regionData instanceof Array) && ((numOfEvos === 0 && !regionData.evoOnly) || (numOfEvos > 0 && regionData.evoOnly))){
                                var lowestDifficulty = Math.min(regionData.Morn || 100, regionData.Day || 100, regionData.Nite || 100);
                                //If there is no way to find it in the wild
                                if(lowestDifficulty === 100){
                                    if(poke.tag === "Starter" || regionData.specEnc === "Gift" || regionData.specEnc === "Event" || regionData.specEnc === "Trade") lowestDifficulty = 1;
                                    if(regionData.specEnc === "Hatch Egg") lowestDifficulty = 2;
                                    if(poke.tag === "Legendary Dog" || poke.tag === "Legendary Bird") lowestDifficulty = 3;
                                    if(regionData.evoOnly) lowestDifficulty =  regionData.evoDiff;
                                    
                                }
                                
                                var difIdx = difficulty.indexOf(lowestDifficulty);
                                if(difIdx >= 0 && difficultySpread[difIdx] > 0){
                                    //Make sure that the pokemon does not exist already in the array
                                    if(poke.added < duplicatedTimes){
                                        if(regionData.evoOnly){
                                            numOfEvos--;
                                        }
                                        numberLeftInRegion--;
                                        difficultySpread[difIdx]--;
                                        pokemon.push(poke);
                                        numSinceLastAdded = 0;
                                        poke.added = true;
                                        shuffleArray(allPokemon);
                                    }
                                }
                            }
                        }
                        duplicationAllowedAfter --;
                        if(duplicationAllowedAfter <= 0) {
                            duplicationAllowedAfter = allPokemon.length * 2;
                            duplicatedTimes ++;
                        }
                        allPokemon.push(allPokemon.shift());
                        if(numSinceLastAdded === allPokemon.length * 10) {
                            alert("Your criteria is yieilding a 0 result. Change it and try again.");
                            return;
                        }
                    }
                    return shuffleArray(pokemon);
                }
                function getOtherVersionExclusive(pokemon){
                    function findPokemon(name){
                        return data.pokemon.find(function(poke){return poke.name === name;});
                    }
                    switch(pokemon.name){
                        case "Caterpie":
                            return [pokemon, findPokemon("Weedle")];
                        case "Metapod":
                            return [pokemon, findPokemon("Kakuna")];
                        case "Butterfree":
                            return [pokemon, findPokemon("Beedrill")];
                        case "Weedle":
                            return [pokemon, findPokemon("Caterpie")];
                        case "Kakuna":
                            return [pokemon, findPokemon("Metapod")];
                        case "Beedrill":
                            return [pokemon, findPokemon("Butterfree")];
                        case "Ekans":
                            return [pokemon, findPokemon("Sandshrew")];
                        case "Arbok":
                            return [pokemon, findPokemon("Sandslash")];
                        case "Sandshrew":
                            return [findPokemon("Ekans"), pokemon];
                        case "Sandslash":
                            return [findPokemon("Arbok"), pokemon];
                        case "Meowth":
                            return [pokemon, findPokemon("Mankey")];
                        case "Persian":
                            return [pokemon, findPokemon("Primeape")];
                        case "Mankey":
                            return [findPokemon("Meowth"), pokemon];
                        case "Primeape":
                            return [findPokemon("Persian"), pokemon];
                        case "Ledyba":
                            return [pokemon, findPokemon("Spinarak")];
                        case "Ledian":
                            return [pokemon, findPokemon("Ariados")];
                        case "Spinarak":
                            return [findPokemon("Ledyba"), pokemon];
                        case "Ariados":
                            return [findPokemon("Ledian"), pokemon];
                        case "Growlithe":
                            return [findPokemon("Vulpix"), pokemon];
                        case "Vulpix":
                            return [findPokemon("Growlithe"), pokemon];
                        case "Delibird":
                            return [pokemon, findPokemon("Mantine")];
                        case "Mantine":
                            return [findPokemon("Delibird"), pokemon];
                        case "Lugia":
                            return [pokemon, findPokemon("Ho-Oh")];
                        case "Ho-Oh":
                            return [findPokemon("Lugia"), pokemon];
                        case "Gligar":
                            return [pokemon, findPokemon("Skarmory")];
                        case "Skarmory":
                            return [findPokemon("Gligar"), pokemon];
                        case "Teddiursa":
                            return [pokemon, findPokemon("Phanpy")];
                        case "Ursaring":
                            return [pokemon, findPokemon("Donphan")];
                        case "Phanpy":
                            return [findPokemon("Teddiursa"), pokemon];
                        case "Donphan":
                            return [findPokemon("Ursaring"), pokemon];
                    }   
                }
                function allSelectedIdxs(inputs){
                    return $.map(inputs.filter(function(){return $(this).prop("checked");}), function(elm){return $(elm).parent().children("input").index(elm);});
                }
                function spreadEvenly(arr, targetSum){
                    var newArr = [];
                    var share = Math.floor(targetSum / arr.length);
                    for(var i = 0; i < arr.length; i++){
                        if(i === 0) newArr.push(share + (targetSum % arr.length));
                        else newArr.push(share);
                    };
                    return newArr;
                }
                function spreadRandomly(arr, targetSum){
                    var newArr = Array.from(Array(arr.length), () => 0);
                    for(var i = 0; i < targetSum; i++){
                        newArr[Math.floor(Math.random() * arr.length)] ++;
                    }
                    return newArr;
                }
                function spreadWithFavour(arr, targetSum, favouredIdx){
                    if(!arr.includes(favouredIdx + 1)) return spreadEvenly(arr, targetSum);
                    var share = Math.floor(targetSum / arr.length) / 2;
                    var newArr = Array.from(Array(arr.length), () => share);
                    newArr[favouredIdx] *= arr.length;
                    newArr[favouredIdx] += (targetSum % share) + share;
                    return newArr;
                }
                //https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array/25984542
                function shuffleArray(array) {
                    for (let i = array.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        [array[i], array[j]] = [array[j], array[i]]; // eslint-disable-line no-param-reassign
                    }
                    return array;
                }
                //Gets all of the potential pokemon
                function filterPokemon(pokemon, regions, difficulty, evolution, time, special){
                    
                    var filtered = [];
                    function addPokemon(poke){
                        poke.added = 0;
                        filtered.push(poke);
                    }
                    for(var i = 0; i < pokemon.length; i++){
                        var meetsCriteria = true;
                        var mon = pokemon[i];
                        if(!special.starter && mon.tag === "Starter"){
                            meetsCriteria = false;
                        } else {
                            addPokemon(pokemon[i]);
                        }
                        if(!special.dogs && mon.tag === "Legendary Dog") meetsCriteria = false;
                        if(!special.birds && mon.tag === "Legendary Bird") meetsCriteria = false;
                        if(special.exclusives === "None" && mon.tag === "Version Exclusive") meetsCriteria = false;
                        if(!meetsCriteria) continue;
                        var foundWithinRegions = false;
                        var foundWithinTimeSet = false;
                        for(var j = regions.length -1 ; j >= 0 ; j--){
                            var regionData = mon.region[regions[j]];
                            if(!(regionData instanceof Array)){
                                foundWithinRegions = true;
                                if((evolution === "None" && regionData.evoOnly) || (evolution === "All" && !regionData.evoOnly)) continue;
                                if(!special.event && regionData.specEnc === "Event") continue;
                                var canBeFoundBasedOnTimeDifficulty = false;
                                for(var k = 0; k < time.length; k++){
                                    var difficultyLevel = regionData[time[k]];
                                    if(difficulty.includes(difficultyLevel) && difficultyLevel > 0) canBeFoundBasedOnTimeDifficulty = true;
                                }
                                if(canBeFoundBasedOnTimeDifficulty) foundWithinTimeSet = true;
                            }
                        }
                        if(!foundWithinRegions || !foundWithinTimeSet) meetsCriteria = false;
                        if(meetsCriteria) addPokemon(pokemon[i]);
                    }
                    return shuffleArray(filtered);
                    
                }
                function getDifficulty(diffSpread, difficulty, numPokemon){
                    var levelsOfDifficulty = [];
                    switch(diffSpread){
                        case "Evenly":
                            levelsOfDifficulty = spreadEvenly(difficulty, numPokemon);
                            break;
                        case "Random":
                            levelsOfDifficulty = spreadRandomly(difficulty, numPokemon);
                            break;
                        case "Favour Easy":
                            levelsOfDifficulty = spreadWithFavour(difficulty, numPokemon, 0);
                            break;
                        case "Favour Medium":
                            levelsOfDifficulty = spreadWithFavour(difficulty, numPokemon, 1);
                            break;
                        case "Favour Hard":
                            levelsOfDifficulty = spreadWithFavour(difficulty, numPokemon, 2);
                            break;
                    }
                    return levelsOfDifficulty;
                }
                function generateBingoCard(){
                    $("#main-container").empty();
                    var regions = allSelectedIdxs($("#regions").children("input"));
                    if(!regions.length) regions = [0];
                    var regionSpread = $("#regions").children("select:eq(0)").val();
                    var difficulty = allSelectedIdxs($("#difficulty").children("input")).map( function(value) { return value + 1; } );
                    if(!difficulty.length) difficulty = [1];
                    var diffSpread = $("#difficulty").children("select:eq(0)").val();
                    var evolution = $("#evolve").children("select:eq(0)").val();
                    var evoSpread = evolution === "Some" ? 15 : evolution === "Average" ? 30 : evolution === "Many" ? 50 : evolution === "All" ? 100 : 0;
                    var timeArr = ["Morn", "Day", "Nite"];
                    var time = allSelectedIdxs($("#time").children("input")).map(function(t){return timeArr[t];});
                    if(!time.length) time = [timeArr[1]];
                    var eventPokemon = $("#misc").children("input:eq(0)").prop("checked");
                    var starterPokemon = $("#misc").children("input:eq(1)").prop("checked");
                    var legendaryDogs = $("#misc").children("input:eq(2)").prop("checked");
                    var legendaryBirds = $("#misc").children("input:eq(3)").prop("checked");
                    var exclusives = $("#misc").children("select:eq(0)").val();
                    var gridSize = $("#misc").children("select:eq(1)").val();
                    var numOfCards = parseInt($("#misc").children("select:eq(2)").val());
                    
                    var x = parseInt(gridSize[0]);
                    var y = parseInt(gridSize[2]);
                    var numPokemon = x * y;
                    
                    var tileX = 56;
                    var tileY = 56;
                    //Number in row of spritesheet
                    var numberInRow = 20;
                    
                    for(var num = 0; num < numOfCards; num++){
                        var bingoCard = $("<div class='bingo-card'></div>");
                        var pokemonInEachRegion = regionSpread === "Evenly" ? pokemonInEachRegion = spreadEvenly(regions, numPokemon) : pokemonInEachRegion = spreadRandomly(regions, numPokemon);
                        var levelsOfDifficulty = getDifficulty(diffSpread, difficulty, numPokemon);
                        var filteredPokemon = filterPokemon(data.pokemon, regions, difficulty, evolution, time, {event: eventPokemon, dogs: legendaryDogs, birds:legendaryBirds, starter: starterPokemon, exclusives: exclusives});
                        var pokemonToDisplay = generatePokemon(filteredPokemon, pokemonInEachRegion, regions, levelsOfDifficulty, difficulty, evoSpread, numPokemon);
                        if(!pokemonToDisplay) return;
                        for(var i = 0;i < y; i++){
                            for(var j = 0; j < x; j++){
                                var bingoItem = $("<div class='bingo-item flex-vertical-aligned'></div>");
                                var pokemon = pokemonToDisplay[i + j * x];

                                if((pokemon.tag === "Version Exclusive" || pokemon.tag === "Legendary Bird") && exclusives === "Split"){
                                    var splits = getOtherVersionExclusive(pokemon);
                                    pokemon = splits[0];
                                    var id = pokemon.id;
                                    var xPos = id % numberInRow * tileX - tileX;
                                    var yPos = ~~((id - 1) / numberInRow) * tileY;
                                    var splitPokemon = splits[1];
                                    var splitid = splitPokemon.id;
                                    var splitxPos = splitid % numberInRow * tileX - tileX;
                                    var splityPos = ~~((splitid - 1) / numberInRow) * tileY;
                                    bingoItem.append("<div class='pokemon-id small-text'>"+"#"+id+" / #"+splitid+"</div>");
                                    bingoItem.append("<div class='pokemon-sprite-placeholder'></div><div class='pokemon-sprite-split'><div class='pokemon-sprite flipped-image' style='background-position:"+-xPos+"px "+-yPos+"px; position:absolute; right:20px;'></div>"+"<div class='pokemon-sprite' style='background-position:"+-splitxPos+"px "+-splityPos+"px; position:absolute; left:20px;'></div></div>")
                                    bingoItem.append("<div class='pokemon-name small-text'>"+pokemon.name.substring(0, 5)+"/"+splitPokemon.name.substring(0, 5)+"</div>");
                                    bingoCard.append(bingoItem);
                                } else {
                                    var id = pokemon.id;
                                    var xPos = id % numberInRow * tileX - tileX;
                                    var yPos = ~~((id - 1) / numberInRow) * tileY;
                                    bingoItem.append("<div class='pokemon-id small-text'>"+"#"+id+"</div>");
                                    bingoItem.append("<div class='pokemon-sprite' style='background-position:"+-xPos+"px "+-yPos+"px;'></div>");
                                    bingoItem.append("<div class='pokemon-name small-text'>"+pokemon.name+"</div>");
                                    bingoCard.append(bingoItem);
                                }
                            }
                            if(numPokemon === 9){
                                $(bingoCard).css("width", "300px");
                                $(bingoCard).css("height", "300px");
                            } else if(numPokemon === 25){

                            } else {
                                $(bingoCard).css("width", "700px");
                                $(bingoCard).css("height", "700px");
                            }
                        }
                        $("#main-container").append(bingoCard);
                    }
                }
                generateBingoCard();
                $("#create-new-bingo-card").on("click",function(){
                    generateBingoCard();
                });
            });
        </script>
    </body>
</html>
