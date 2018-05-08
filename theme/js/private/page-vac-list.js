$(function(){
	if(typeof flashMes !== "undefined" && flashMes.length>0){
		var message = '<div>' + flashMes + '</div>';
		ModalWindow.open({ content: message, action: { active: 0 }, additionalStyle:'dark-ver' });
	}
	$('.evl__service-btn').click(function(){
		var form = $(this).siblings('.evl__service-popup').clone();
		$(form).removeClass('tmpl');

		ModalWindow.open({ content: form, action: { active: 0 }, additionalStyle:'dark-ver' });
	});
});