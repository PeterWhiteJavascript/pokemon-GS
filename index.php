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
        <div id="background-title"></div>
        <div id="directory-container">
            <div class="directory-heading flex-centered"><div>Pokemon GS Challenges</div></div>
            <div class="directory-item normal-text flex-centered"><div class="clickable"><a href="bingo.php"><div>Bingo</div></a></div></div>
        </div>
        <div id="audio-player" class="normal-text clickable">Play Music</div>
        <audio id="audio-element">
            <source src="audio/title-screen.mp3" type="audio/mpeg">
          Your browser does not support the audio element.
        </audio>
    </body>
</html>
