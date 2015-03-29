$(document).ready(function () {
    $("select").selecter();
    $('a.navbar-text').mouseover(function () {
        $(this).tooltip('show');
    });
    $('a.navbar-text').mouseout(function () {
        $(this).tooltip('hide');
    });
});

