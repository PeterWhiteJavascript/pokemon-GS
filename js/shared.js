$(function(){
    function makeMusicWork(){
        var audio = $("#audio-element");
        var player = $("#audio-player");
        player.click(function(){
            if (audio.get(0).paused === false) {
                player.text("Play Music");
                audio.trigger("pause");
            } else {
                player.text("Pause Music");
                audio.trigger("play");
            }
        });
    }
    makeMusicWork();
});
