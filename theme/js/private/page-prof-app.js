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
	//
	$('.ppp__rating-block p').click(function(){
		$(this).fadeOut();
		var html = "<form data-header='Как считается рейтинг' class='text-left'>"
		+ "<ul><li>1. Оценки за Вакансию</li>"
		+ "<li>2. Отзывы (более свежие повышают рейтинг)</li>"
		+ "<li>3. Время на сайте от момента регистрации</li>"
		+ "<li>4. Количество отработанных Вакансий</li>"
		+ "<li>5. Заполненность анкеты</li>"
		+ "<li>6. Подтверждение данных</li>"
		+ "<li>- наличие фото (одно и больше)</li>"
		+ "<li>- больше одного фото</li>"
		+ "<li>- электронная почта</li>"
		+ "<li>- мобильный телефон - номер</li></ul>"
		+ "</form>";
		ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
	});
});