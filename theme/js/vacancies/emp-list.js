$(function(){
	$('.evl__service-btn').click(function(){
		var form = $(this).siblings('.evl__service-popup').clone();
		$(form).removeClass('tmpl');

		ModalWindow.open({ content: form, action: { active: 0 }, additionalStyle:'dark-ver' });
	});
});