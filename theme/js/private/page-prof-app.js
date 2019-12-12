jQuery(function($){
	$('.ppp__logo').magnificPopup({
		delegate: '.ppp__logo-full',
		type: 'image',
		gallery: {
			enabled: true,
			preload: [0, 2],
			navigateByImgClick: true,
			arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
			tPrev: '',
			tNext: '',
			tCounter: '<span class="mfp-counter">%curr% / %total%</span>'
		}
	});
	if($(".Info").is('*')){
		var form = $(".Info").clone();
		$(form).toggleClass('tmpl');
		ModalWindow.open({ content: form, action: { active: 0 }, additionalStyle:'dark-ver' });
	}
	//
	//		Подсказка по рейтингу
	//
	var timerHint;
	$(document).mousemove(function(e){	// подсказка для подтверждения почты
		if($(e.target).closest('.ppp__rating-block').length || $(e.target).is('.ppp__rating-block')){
			$('.ppp__rating-block p').fadeIn(300);
			clearTimeout(timerHint);
		};
	})
	.mouseout(function(e){	// подсказка для подтверждения телефона
		if(!$(e.target).closest('.ppp__rating-block').length && !$(e.target).is('.ppp__rating-block')){
			clearTimeout(timerHint);
			timerHint = setTimeout(function(){ $('.ppp__rating-block p').fadeOut(300) },500);
		}
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
	//
	$('.question_popup').on('click',function(){
		$.fancybox.open({
			src: '.popup__msg',
			type: 'inline',
			touch: false
		});
	});
});