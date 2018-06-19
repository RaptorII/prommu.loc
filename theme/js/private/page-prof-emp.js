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
	$('.upp__rating-block p').click(function(){
		$(this).fadeOut();
		var html = "<form data-header='Как считается рейтинг' class='text-left'>"
		+ "<ul><li>1. Оценки за Вакансию</li>"
		+ "<li>2. Отзывы (свежие - важнее)</li>"
		+ "<li>3. Время на сайте от момента регистрации</li>"
		+ "<li>4. Активность размещения Вакансий</li>"
		+ "<li>5. Подтверждение данных</li>"
		+ "<li>- наличие фото (логотипа компании или ИП)</li>"
		+ "<li>- наличие сайта</li>"
		+ "<li>- подтверждение регистрационных данных компании (ИНН / Св-во регистрации и тд)</li>"
		+ "<li>- электронная почта</li>"
		+ "<li>- мобильный телефон</li>"
		+ "<li>- офисный (городской) телефон</li></ul>"
		+ "</form>";
		ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
	});
});