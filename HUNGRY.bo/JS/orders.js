$(window).bind("resize", function () {
    if ($(this).width() < 981) {
        $("td.order-state").attr("hidden", "true");
        $("th.order-prod").attr("hidden", "true");
    } else {
        $("td.order-state").removeAttr("hidden");
        $("th.order-prod").removeAttr("hidden");
    }
}).trigger('resize');
