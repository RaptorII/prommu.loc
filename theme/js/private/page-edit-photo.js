$(function(){
	$(".photos__item-delete").click(function(e){ 
		if(!confirm("Вы хотите удалить фото?"))
			e.preventDefault(); 
	});
	//
	$('.photo-list').magnificPopup({
		delegate: '.photos__item-link',
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