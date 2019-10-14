'use strict'
$(function(){
  // слайдер для фоточек
  $(function(){
    $('.photo-list').magnificPopup({
      delegate: '.photos__item-link',
      type: 'image',
      gallery: {
        enabled: true,
        preload: [0, 2],
        navigateByImgClick: true,
        arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
        tPrev: '',
        tNext: '',
        tCounter: '<span class="mfp-counter">%curr% / %total%</span>'
      }
    });
  });
  // открытие заявки фидбека
  $(document).on(
    'dblclick',
    '.custom-table tbody td',
    function(e){
      var item = $(this).siblings('td').eq(0).find('a'),
        url = $(item).attr('href');

      console.log(url);

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
        url: '/admin/feedback',
        data: {'data':JSON.stringify(self.dataset)},
        dataType: 'json',
        success: function (result)
        {
          confirm(result.message);
          MainAdmin.bAjaxTimer = false;
          $.fn.yiiGridView.update("feedback_list");
        }
      });
    }
  });
  // переключение по табам
  $('#tablist a').on('click',function(){
    var link = this.href,
      start = link.indexOf('#') + 1,
      anchor = link.substr(start, link.length - start),
      newLink = location.protocol + '//' + location.host + location.pathname + '?anchor=' + anchor;

    window.history.pushState('object or string', 'page name', newLink);
  });
});