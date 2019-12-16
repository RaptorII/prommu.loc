

$(window).scroll(function () {
    /**
     * parallax
     */ /*
    $('.parallax').each(function () {
        if ($(this).offset().top < ($(window).scrollTop() + 500)) { //pixels start
            // Get ammount of pixels the image is above the top of the window
            var difference = $(window).scrollTop() - $(this).offset().top + 500;
            // Top value of image is set to half the amount scrolled
            // (this gives the illusion of the image scrolling slower than the rest of the page)
            var half = 20 - (difference / 4.5) + 'px';

            $(this).find('.static__img-d').css('top', half);
        } else {
            $(this).find('.static__img-d').css('top', '0');// if image is below the top of the window set top to 0
        }
    });*/
});


//
$(function(){

    function showMessage(string) {
        $('body').append('<div class="prmu__popup"><p>' + string + '</p></div>'),
            $.fancybox.open({
                src: "body>div.prmu__popup",
                type: 'inline',
                touch: false,
                afterClose: function(){ $('body>div.prmu__popup').remove() }
            })
    }

    //
    $('.employer_public_anc').click(function(){
        showMessage('Нам очень жаль :( но Вы зарегистрированы как работодатель и не можете публиковать анкеты');
    });
    //
    $('.geo_message').click(function(){
        showMessage('Извините, данная услуга еще в разработке. Как только она активизируется - мы обязательно оповестим Вас');
    });
    //
    $('.applicant_service').click(function(){
        showMessage('Нам очень жаль :( но Вы зарегистрированы как работодатель и не можете воспользоваться данной услугой');
    });
});

