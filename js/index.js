$(document).ready(function () {
    $("select").selecter();
    $('a.navbar-text').mouseover(function () {
        $(this).tooltip('show');
    });
    $('a.navbar-text').mouseout(function () {
        $(this).tooltip('hide');
    });
    $("#submit_carve").click(function (e) {
        $("#loading").show();
    });
    $("#submit_carve").click(function (e) {
        e.preventDefault();
        $t_width = parseInt($("#width").val());
        $t_height = parseInt($("#height").val());
        $o_width = parseInt($("#original_width").text());
        $o_height = parseInt($("#original_height").text());
        $algo = parseInt($("#algo").val());
        while (($o_width != $t_width || $o_height != $t_height)) {

            if ($o_width > $t_width) {
                $o_width = Math.max($o_width - 50, $t_width);
            } else if ($o_width < $t_width) {
                $o_width = Math.min($o_width + 50, $t_width);
            }

            if ($o_height > $t_height) {
                $o_height = Math.max($o_height - 50, $t_height);
            } else if ($o_height < $t_height) {
                $o_height = Math.min($o_height + 50, $t_height);
            }

            $.ajax({
                url: 'update.php', // Un script PHP que l'on va créer juste après
                type: 'POST',
                async: false,
                cache: false,
                data: 'width=' + $o_width + '&height=' + $o_height + '&algo=' + $algo,
                dataType: 'html',
                success: function (data) {
                    $("#main").html(data);
                    $("#original_width").text($o_width);
                    $("#original_height").text($o_height);
                }
            });
        }
        $("#loading").hide();
    });
});

