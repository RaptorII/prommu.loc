jQuery(function($){
	if($(".complete-popup").is('*')){
		var form = $(".complete-popup").clone().removeClass('tmpl');
		ModalWindow.open({ content: form, action: { active: 0 }, additionalStyle:'dark-ver' });
	}

	$('.vacancy__item-tab').click(function(){
		var main = this.parentElement,
				content = this.nextElementSibling;

		$(main).toggleClass('enable');
		$(main).hasClass('enable') ? $(content).fadeIn() : $(content).fadeOut();
	});
});