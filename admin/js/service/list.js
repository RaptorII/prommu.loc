'use strict'
jQuery(function($){
  // открытие заявки
  $(document).on(
    'dblclick',
    '.custom-table tbody td',
    function(e){
      var id = $(this).siblings('td').eq(0).text(),
          tr = $(this).closest('tr'),
          url = window.location.href + '/' + id;

      console.log(window.location);

      if(window.location.href.indexOf('service_cloud')>=0)
      {
        url = '/admin/service/service_cloud/';
        if($(tr).hasClass('vacancy'))
        {
          url+='vacancy?id='+id;
        }
        else if($(tr).hasClass('email'))
        {
          url+='email?id='+id;
        }
        else if($(tr).hasClass('sms'))
        {
          url+='sms?id='+id;
        }
        else if($(tr).hasClass('push'))
        {
          url+='push?id='+id;
        }
        else if($(tr).hasClass('upvacancy'))
        {
          url+='upvacancy?id='+id;
        }
        else if($(tr).hasClass('personal-invitation'))
        {
          url+='personal-invitation?id='+id;
        }
        else if($(tr).hasClass('creation-vacancy'))
        {
          url+='creation_vacancy?id='+id;
        }
        else if($(tr).hasClass('repost'))
        {
          url+='repost?id='+id;
        }
        else if($(tr).hasClass('api'))
        {
          url+='api?id='+id;
        }

        $(location).attr('href',url);
      }
      else
      {
        if(!$(this).hasClass('empty'))
          $(location).attr('href',url);
      }
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