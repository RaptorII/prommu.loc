$(function(){
	$('.app-projects__list').on('click','.second_response',function() {
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
          if(t.error!=1)
          {
						$(self).fadeOut();
						$(status).html('Статус : <b>Ожидание ответа</b>');
          }
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
	});
  // согласие или отказ
  $('.change_status').click(function() {
    var self = this,
    		main = $(self).closest('.app-projects__item-replace'),
    		blockAccept = $(main).siblings('.status_accept-content'),
    		blockReject = $(main).siblings('.status_reject-content'),
        message = 'Произошла ошибка<br>Пожалуйста обновите страницу и попробуйте еще раз';

    $.post(
      MainConfig.AJAX_POST_SETRESPONSESTATUS,
      { idres:self.dataset.id, s:self.dataset.status }, 
      function(t) {
        t = JSON.parse(t);
        if(typeof t.error !=undefined)
        {
        	if(t.error==0)
        	{
	          if($(self).hasClass('status_reject'))
	          {
	            $(main).html($(blockReject).html());
	            message = 'Вы успешно отклонили приглашение на вакансию';
	          }
	          if($(self).hasClass('status_accept'))
	          {
	            $(main).html($(blockAccept).html());
	            message = 'Вы успешно подтвердили участие в вакансии';
	          }
        	}
        }
        $('body').append('<div class="prmu__popup"><p>'+message+'</p></div>'),
        $.fancybox.open({
          src: "body>div.prmu__popup",
          type: 'inline',
          touch: false,
          afterClose: function(){ $('body>div.prmu__popup').remove() }
        })
        console.log(t);
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