$(function(){
	$('.second_response').click(function() {
		var self = this,
				main = $(self).closest('.app-projects__item-right'),
				status = $(main).find('.app-projects__right-bl:eq(1)');

		$.get(
			MainConfig.AJAX_POST_SETVACATIONRESPONSE, 
			{id:this.dataset.id, sresponse:this.dataset.sresponse}, 
			function(t) {
				t = JSON.parse(t);
				if(typeof t.message !=undefined)
				{
					$(self).fadeOut();
					$(status).html('Статус : <b>Ожидение ответа</b>');
					$('body').append('<div class="prmu__popup"><p>'+t.message+'</p></div>'),
					$.fancybox.open({
						src: "body>div.prmu__popup",
						type: 'inline',
						touch: false,
						afterClose: function(){ $('body>div.prmu__popup').remove() }
					})
				}
			}
		)
	})
});