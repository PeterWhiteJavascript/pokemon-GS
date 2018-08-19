<?php ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pokemon GS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/shared.js"></script>
        <style>
            body{
                background-color:#588c7e;
            }
            #save{
                top:10px;
                left:50%;
                position:absolute;
                border-radius:5px;
                padding:10px;
                cursor:pointer;
                user-select:none;
                transform:translate( -50%, 0);
                width:25%;
                text-align:center;
            }
            #main{
                left:50%;
                top:10%;
                width:100%;
                height:auto;
                position:absolute;
                transform:translate( -50%, 0);
                display:flex;
            }
            #main-cont{
                width:100%;
                height:auto;
                border:1px solid black;
                padding:5px;
                background-color:#ffcc5c;
                display:flex;
                flex-wrap:wrap;
            }
            .itm{
                width:31.5%;
                border-top:4px solid black;
                border-right:2px solid black;
                border-left:2px solid black;
                display:flex;
                flex-wrap:wrap;
                padding:5px;
            }
            .itm > input, .itm > div:not(.regions-checkboxes):not(.pokemon-sprite), .itm > select{
                height:20px;
            }
            .pokemon-sprite{
                margin-left: 10px;
            }
            .pokemon-text{
                width:20%;
                padding-left:5px;
            }
            .pokemon-input{
                width:35%;
            }
            .headings-text{
                width:100%;
                text-align:center;
                background: linear-gradient(#f2e394, #f2ae72);
            }
            .regions-checkboxes{
                width:100%;
                display:flex;
                flex-wrap:wrap;
                padding-top:2px;
                margin-top:2px;
                border-top: 1px dashed black;
            }
            .regions-checkboxes > div:not(.region-data){
                width:50%;
            }
            .region-data{
                width:100%;
                height:auto;
                display:flex;
                flex-wrap:wrap;
            }
            .time-text{
                width:21.5%;
                padding-left:5px;
            }
            .region-data > div:not(.pokemon-text):not(.time-text):not(.headings-text):not(.regions-checkboxes){
                width:30%;
                padding-left:5px;
            }
            .region-data:not(:empty){
                border-bottom: 1px dashed black;
                padding-bottom:2px;
            }
            .region-data:empty{
                height:0px;
            }
            select{
                width:25%;
            }
            input{
                width:8%;
            }
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
              -webkit-appearance: none;
              margin: 0;
            }
            input[type="number"] {
              -moz-appearance: textfield;
            }
            
        </style>
    </head>
    <body>
        <div id="save" class="normal-text clickable">Save</div>
        <div id='main'>
            <div id='main-cont'></div>
        </div>
        
        <script>
            $(function(){
                var tileX = 56;
                var tileY = 56;
                var numberInRow = 20;
                function toggleShowRegionData(elm){
                    var idx = $(elm).parent().children("input").index(elm);
                    //First time
                    if($(elm).parent().children(".region-data:eq("+idx+")").is(':empty')){
                        $(elm).parent().children(".region-data:eq("+idx+")").append(getEmptyRegionData());
                    }
                    var checked = $(elm).prop("checked");
                    if(!checked){
                        $(elm).parent().children(".region-data:eq("+idx+")").hide();
                    } else {
                        $(elm).parent().children(".region-data:eq("+idx+")").show();
                    }
                }
                function getEmptyRegionData(){
                    return "<div class='time-text'>Morn</div>\n\
                            <input type='number' class='Morn'>\n\
                            <div class='time-text'>Day</div>\n\
                            <input type='number' class='Day'>\n\
                            <div class='time-text'>Nite</div>\n\
                            <input type='number' class='Nite'>\n\
                            <div>Evolve Only</div>\n\
                            <input type='number' min='0' max='1'>\n\
                            <div>First Lv.</div>\n\
                            <input type='number' min='2' max='100'>\n\
                            <div>Evo. Diff</div>\n\
                            <input type='number' min='1' max='3'>\n\
                            <div>Special Enc.</div>\n\
                            <select><option>None</option><option>Old Rod</option><option>Good Rod</option><option>Super Rod</option><option>Headbutt</option><option>Bug Catching Contest</option><option>Event</option><option>Gift</option><option>Rock Smash</option><option>Ruins Puzzle</option><option>Trade</option><option>Hatch Egg</option></select>";
                }
                function createItem(pokemon){
                    var itm = $(
                        "<div class='itm'>\n\
                            <div>ID</div>\n\
                            <input type='number'>\n\
                            <div class='pokemon-text'>Pokemon</div>\n\
                            <input class='pokemon-input'>\n\
                            <div class='pokemon-sprite'></div>\n\
                            <div class='pokemon-text'>Tag</div>\n\
                            <select><option>Normal</option><option>Version Exclusive</option><option>Starter</option><option>Legendary Dog</option><option>Legendary Bird</option></select>\n\
                            <div class='regions-checkboxes'>\n\
                                <div>Before Azaelea</div><input type='checkbox'>\n\
                                <div class='region-data'></div>\n\
                                <div>Before Olivine</div><input type='checkbox'>\n\
                                <div class='region-data'></div>\n\
                                <div>Before Ice Path</div><input type='checkbox'>\n\
                                <div class='region-data'></div>\n\
                                <div>Before Elite 4</div><input type='checkbox'>\n\
                                <div class='region-data'></div>\n\
                                <div>Kanto</div><input type='checkbox'>\n\
                                <div class='region-data'></div>\n\
                            </div>\n\
                        </div>");
                    $("#main-cont").prepend(itm);
                    if(pokemon){
                        itm.children("input:eq(0)").val(pokemon.id);
                        itm.children("input:eq(1)").val(pokemon.name);
                        var xPos = pokemon.id % numberInRow * tileX - tileX;
                        var yPos = ~~((pokemon.id - 1) / numberInRow) * tileY;
                        itm.children(".pokemon-sprite").css("background-position", -xPos+"px "+-yPos+"px");
                        itm.children("select:eq(0)").val(pokemon.tag);
                        var regions = pokemon.region;
                        for(var i = 0; i < regions.length; i++){
                            var region = regions[i];
                            if(Object.keys(region).length){
                                itm.children(".regions-checkboxes").children(".region-data:eq("+i+")").append(getEmptyRegionData());
                                itm.children(".regions-checkboxes").children("input:eq("+i+")").prop("checked", true);
                                var regionData = itm.children(".regions-checkboxes").children(".region-data:eq("+i+")");
                                regionData.children("input:eq(0)").val(region.Morn);
                                regionData.children("input:eq(1)").val(region.Day);
                                regionData.children("input:eq(2)").val(region.Nite);
                                regionData.children("input:eq(3)").val(region.evoOnly);
                                regionData.children("input:eq(4)").val(region.firstLv);
                                regionData.children("input:eq(5)").val(region.evoDiff);
                                regionData.children("select:eq(0)").val(region.specEnc || "None");
                            }
                        }
                    } else {
                        $(itm).children("input").on("change", function(){
                            var allFilled = true;
                            $(this).parent().children("input:not(:checkbox)").each(function(){
                                if($(this).val().length === 0) allFilled = false; 
                            });
                            if(allFilled){ 
                                createItem();
                                $(this).children("input").off("change");
                            };
                        });
                    }
                    itm.children(".regions-checkboxes").children("input").on("change", function(){
                        toggleShowRegionData(this);
                    });
                }
                $.getJSON("data/pokemon.json", function( data ) {
                    for(var i = 0; i < data.pokemon.length; i++){
                        createItem(data.pokemon[i]);
                    }
                    createItem();
                });
                $("#save").on("click",function(){
                    var data = {
                        pokemon:[]
                    };
                    function setData(){
                        $($("#main-cont").children(".itm").get().reverse()).each(function(){
                            var id = parseInt($(this).children("input:eq(0)").val());
                            var name = $(this).children("input:eq(1)").val();
                            var tag = $(this).children("select:eq(0)").val();
                            var regionsCheckboxes = $(this).children(".regions-checkboxes");
                            var checkBoxes = regionsCheckboxes.children("input");
                            var regionData = [
                                {},{},{},{},{}
                            ];
                            checkBoxes.each(function(i){
                                if($(this).prop("checked")){
                                    var rData = $(this).parent().children(".region-data:eq("+i+")");
                                    var morn = parseInt(rData.children("input:eq(0)").val()) || 0;
                                    var day = parseInt(rData.children("input:eq(1)").val()) || 0;
                                    var nite = parseInt(rData.children("input:eq(2)").val()) || 0;
                                    var evoOnly = parseInt(rData.children("input:eq(3)").val()) || 0;
                                    var firstLv = parseInt(rData.children("input:eq(4)").val());
                                    var evoDiff = parseInt(rData.children("input:eq(5)").val()) || 0;
                                    var specEnc = rData.children("select:eq(0)").val();
                                    if(specEnc === "None") specEnc = false;
                                    regionData[i] = {Morn: morn, Day: day, Nite: nite, evoOnly: evoOnly, firstLv: firstLv, evoDiff: evoDiff, specEnc: specEnc};
                                }
                            });
                            
                            if(id >= 0 && name.length){
                                data.pokemon.push({id: id, name: name, tag:tag, region: regionData});
                            }
                        });
                    }
                    setData();
                    data.pokemon.sort(function(a, b){
                        return b.id - a.id; 
                    });
                    $.ajax({
                        type:'POST',
                        url:'save-data.php',
                        data:{filename: "data/pokemon.json", data: JSON.stringify(data)},
                        dataType:'json'
                    })
                    .done(function(data){console.log(data)})
                    .fail(function(data){console.log(data)});
                });
            });
        </script>
    </body>
</html>



