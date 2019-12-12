jQuery(function($){
	$('.upp__img-block, .upp__logo-more').magnificPopup({
		delegate: '.profile__logo-full',
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

	$('.upp-logo-more__link').click(function(){
		var arPhotos = $('.upp__img-block-more.off');
		$.each(arPhotos, function(){ $(this).removeClass('off'); });
		$(this).hide();
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
		if($(e.target).closest('.upp__rating-block').length || $(e.target).is('.upp__rating-block')){
			$('.upp__rating-block p').fadeIn(300);
			clearTimeout(timerHint);
		};
	})
	.mouseout(function(e){	// подсказка для подтверждения телефона
		if(!$(e.target).closest('.upp__rating-block').length && !$(e.target).is('.upp__rating-block')){
			clearTimeout(timerHint);
			timerHint = setTimeout(function(){ $('.upp__rating-block p').fadeOut(300) },500);
		}
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