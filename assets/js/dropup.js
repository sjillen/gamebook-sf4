$(document).ready(function () {
    $(".header-bar").click(function () {
        $('li > ul').not($(this).children("ul").toggle()).hide();
    });
    $(".header-bar").hover(function() {
        $('li > ul').not($(this).children("ul").toggle()).hide();
    })
});