jQuery(function($){
	if($(".complete-popup").is('*')){
		var form = $(".complete-popup").clone().removeClass('tmpl');
		ModalWindow.open({ content: form, action: { active: 0 }, additionalStyle:'dark-ver' });
	}
});