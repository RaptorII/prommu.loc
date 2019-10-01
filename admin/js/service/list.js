'use strict'
jQuery(function($){
  // открытие заявки
  $(document).on(
    'dblclick',
    '.custom-table tbody td',
    function(e){
      var id = $(this).siblings('td').eq(0).text(),
        url = window.location.href + '/' + id;

      if(!$(this).hasClass('empty'))
        $(location).attr('href',url);
    });
  // смена статуса у карт
  $(document).on('click','.select_update li',function(){
    var self = this;

    if(!MainAdmin.bAjaxTimer)
    {
      MainAdmin.bAjaxTimer = true;
      $.ajax({
        type: 'GET',
        url: '/admin/service/update',
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