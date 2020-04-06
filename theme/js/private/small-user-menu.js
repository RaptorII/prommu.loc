'use_strict'

$(function() {
    //fixed menu in personal account

    let heightSmlMenu = $('.personal-acc__menu').height();
    let heightTopWrap = $('#DiTopMenuWrapp').height();

    $(window).on('resize scroll',scrollAccMenu);
    scrollAccMenu();
    function scrollAccMenu() {

        if	(
            $(document).scrollTop() > heightTopWrap
            &&
            $(window).width() < 768
        )
        {
            $('.personal-acc__menu').addClass('fixed');
            $('body').css('margin-top', heightSmlMenu + 'px');

        } else {
                $('.personal-acc__menu').removeClass('fixed');
                $('body').css('margin-top', 0 + 'px');
        }

    }

});
