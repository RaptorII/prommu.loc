$(function(){
		var $button = $('.api-service__btn');

		$('#display, #update, #synch').change(function(){
			$(this).is(':checked') ? $button.fadeIn() : $button.fadeOut()
		});

		$('.api__exp-link').click(function(){
			var block = $(this).siblings('.api__exp-col');
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(block).fadeOut();
			}
			else{
				$(this).addClass('active');
				$(block).fadeIn();
			}
		});
		//
		//
		//
		if($('*').is('[name="city"]')){
			var arInputs = $('.api-srvc__chbox input');

			arInputs.change(function(e){
				var flag = false;
				$.each(arInputs,function(){
					if($(this).is(':checked')) flag = true;
				});
				flag ? $button.fadeIn() : $button.fadeOut();
			});
		}
});