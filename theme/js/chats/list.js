jQuery(function($){
	$('.vacancy__item-tab').click(function(){
		var main = this.parentElement,
				content = this.nextElementSibling;

		$(main).toggleClass('enable');
		$(main).hasClass('enable') ? $(content).fadeIn() : $(content).fadeOut();
	});

    //fixed menu in personal account
    var posAccMenu = $('.personal-acc__menu').offset().top - 100;
    $(window).on('resize scroll',scrollAccMenu);
    scrollAccMenu();
    function scrollAccMenu()
    {
        (
            $(document).scrollTop() > posAccMenu
            &&
            $(window).width() < 768
        )
            ? $('.personal-acc__menu').addClass('fixed')
            : $('.personal-acc__menu').removeClass('fixed');
    }
    //
    $('.ppp__self-employed-question').on('click',function(){
        $.fancybox.open({
            src: '#self_employed_message',
            type: 'inline',
            touch: false
        });
    });
});