$(function() {
    var widget_hello_word = "HELLO WORLD";
    var widget_hello_word_hover = "HELLO WORLD HOVER";

    $('.widget-hello-world .tit').mouseenter(function() {
        $(this).text(widget_hello_word_hover)
    }).mouseleave(function() {
        $(this).text(widget_hello_word)
    })
})