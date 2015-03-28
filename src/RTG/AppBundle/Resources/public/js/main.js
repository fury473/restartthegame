function setScrollBtnOffset() {
    var container = $('body > .container');
    var container_width = container.width() + 30;
    var offset = container.offset().left + container_width + 15;
    return $('.scrollup').css('left', offset + 'px');
}

$(function () {
    $('#carousel-example-generic').carousel();
    $("[data-toggle='switch']").bootstrapSwitch();
    $("[data-toggle='tab']").click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })

    setScrollBtnOffset();

    $(window).resize(function () {
        setScrollBtnOffset();
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 400);
        return false;
    });
});