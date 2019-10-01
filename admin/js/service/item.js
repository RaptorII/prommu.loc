'use strict'
jQuery(function($){
  // Просмотреть весь список соискателей
  $('.applicants_td .btn').click(function(){
    $('.applicants_td').addClass('active');
    $(this).hide();
  });
  // запуск услуг (premium, email, sms)
  $('#start_service').click(function(){
    $('#start_service-input').attr('checked',true);
    $('#service_form').submit();
  });
  // слайдер картинок
  if(typeof $.magnificPopup === 'object')
  {
    $('.service_images').magnificPopup({
      delegate: '.service_images-link',
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
  }
  // текстовый редактор
  if(typeof nicEditor === 'function')
  {
    var myNicEditor = new nicEditor(
      { maxHeight: 600, buttonList: ['bold', 'italic', 'underline'] }
    );
    myNicEditor.addInstance('comment');
    myNicEditor.setPanel('comment-panel');
  }
});