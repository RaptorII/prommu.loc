$(function(){
	setInterval(function (e){ if($("#HiLogo").val()!= ''){ location.reload(true) } }, 1000);
	//
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
});