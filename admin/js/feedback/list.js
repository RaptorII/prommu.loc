'use strict'
jQuery(function($){
  // открытие заявки
  $(document).on(
    'dblclick',
    '.custom-table tbody td',
    function(e){
      var item = $(this).siblings('td').eq(0).find('a'),
        url = $(item).attr('href');

      if(!$(this).hasClass('empty'))
        $(location).attr('href',url);
    });
  // смена статуса
  $(document).on('click','.select_update li',function(){
    var self = this;

    if(!MainAdmin.bAjaxTimer)
    {
      MainAdmin.bAjaxTimer = true;
      $.ajax({
        type: 'POST',
        data: {'data':JSON.stringify(self.dataset)},
        dataType: 'json',
        success: function (result)
        {
          confirm(result.message);
          MainAdmin.bAjaxTimer = false;
          $.fn.yiiGridView.update("custom_list");
        }
      });
    }
  });
});