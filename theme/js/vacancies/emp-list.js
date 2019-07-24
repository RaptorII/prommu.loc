$(function(){
	$('.evl__service-btn').click(function(){
		var form = $(this).siblings('.evl__service-popup').clone();
		$(form).removeClass('tmpl');

		ModalWindow.open({ content: form, action: { active: 0 }, additionalStyle:'dark-ver' });
	});

	//
	$('.send_to_archive').click(function(){
		var linkApproved = $(this).data('approved'),
				linkReviews = $(this).data('reviews');

		console.log(this);

    $('body').append('<div class="prmu__popup"><p>Для того что бы отправить вакансию в архив, необходимо <a href="' +
      linkReviews + '">оценить</a> работающий с Вами персонал. Если он с Вами не работал - необходимо перейти по <a href="' +
      linkApproved + '">ссылке</a> и отменить утверждение на вакансии</p></div>');

		$.fancybox.open({
			src: "body>div.prmu__popup",
			type: 'inline',
			touch: false,
			afterClose: function(){ $('body>div.prmu__popup').remove() }
		});
	});

	//fixed menu in personal account
	var posAccMenu = $('.personal-acc__menu').offset().top - 100;
	$(window).on('resize scroll',scrollAccMenu);
	scrollAccMenu();
	function scrollAccMenu() {
		(
			$(document).scrollTop() > posAccMenu
			&&
			$(window).width() < 768
		)
			? $('.personal-acc__menu').addClass('fixed')
			: $('.personal-acc__menu').removeClass('fixed');
	}

});