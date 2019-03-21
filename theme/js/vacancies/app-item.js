$(function(){
  var arBlocks = $('.app_project__body-status .app_project__body-flex');
  // повторная отправка
  $('.second_response').click(function() {
    var self = this;
        
    $.get(
      MainConfig.AJAX_POST_SETVACATIONRESPONSE, 
      {id:this.dataset.id, sresponse:this.dataset.sresponse}, 
      function(t) {
        t = JSON.parse(t);
        if(typeof t.message !=undefined)
        {
          $(arBlocks[1]).fadeOut();
          $(arBlocks[0]).html('<b>Ожидение ответа</b>');
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
        message = 'Произошла ошибка<br>Пожалуйста обновите страницу и попробуйте еще раз';

    $.post(
      MainConfig.AJAX_POST_SETRESPONSESTATUS,
      { idres:self.dataset.id, s:self.dataset.status }, 
      function(t) {
        t = JSON.parse(t);
        if(typeof t.error !=undefined)
        {
          $(arBlocks[1]).fadeOut();
          if(t.error==0 && self.dataset.status==3)
          {
            $(arBlocks[0]).html('<b>Приглашение отклонено</b>');
            message = 'Вы успешно отклонили приглашение на вакансию';
          }
          if(t.error==0 && self.dataset.status==5)
          {
            $(arBlocks[0]).html('<b>Приглашение принято</b>');
            message = 'Вы успешно подтвердили участие в вакансии';
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

  
});