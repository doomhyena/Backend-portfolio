const path = "/Hotel/common/findroom.php";

$(function () {
    $("#rooms").load(path + "?keresett=");

    $("#search-box").on("keyup", function () {
        const ertek = $(this).val();
        $("#rooms").load(path + "?keresett=" + encodeURIComponent(ertek));
    });
});