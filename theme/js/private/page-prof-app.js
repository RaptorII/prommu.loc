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
});