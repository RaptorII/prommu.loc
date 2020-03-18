$(function(){
  // повторная отправка
  $('.app-projects__item-replace').on('click','.second_response',function() {
    var self = this,
        arBlocks = $('.app_project__body-status .app_project__body-flex');
        
    $.get(
      MainConfig.AJAX_POST_SETVACATIONRESPONSE, 
      {id:this.dataset.id, sresponse:this.dataset.sresponse}, 
      function(t) {
        t = JSON.parse(t);
        if(typeof t.message !=undefined)
        {
          if(t.error!=1)
          {
            $(arBlocks[1]).fadeOut();
            $(arBlocks[0]).html('<b>Ожидание ответа</b>');
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
        main = $('.app-projects__item-replace'),
        blockAccept = $('.status_accept-content'),
        blockReject = $('.status_reject-content'),
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
    });
  });

});