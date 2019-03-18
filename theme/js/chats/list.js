jQuery(function($){
	$('.vacancy__item-tab').click(function(){
		var main = this.parentElement,
				content = this.nextElementSibling;

		$(main).toggleClass('enable');
		$(main).hasClass('enable') ? $(content).fadeIn() : $(content).fadeOut();
	});
});