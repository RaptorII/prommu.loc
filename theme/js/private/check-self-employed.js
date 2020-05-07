'use strict'
jQuery(function($){
  var INNLenth = 12;
  $('.check-inn__block').on('input','input',function(){
    var v = this.value.replace (/\D/, '').substr(0,INNLenth);
    $(this).val(v);
  });
  $('.check-inn__block').on('blur','input',function(){
    if(this.value.length<3)
      this.value = '';
  });
  $('.check-inn__block .prmu-btn_small').click(function(){
    if($(".check-inn__block input").length==5)
      return;
    $('.check-inn__block').append('<div class="check-inn__block-input"><input type="text" name="inn[]">');
  });
  //
  $('#check_inn').on('click',function(){
    var arInput = $(".check-inn__block input"),
      arValid = [];

    $.each(arInput, function(){
      if(this.value.length!=INNLenth)
      {
        $(this).addClass('error');
      }
      else
      {
        $(this).removeClass('error');
        arValid.push(this);
      }
    });

    if(arValid.length)
    {
      var cnt = 0;
      ajaxRequest(arValid, cnt);
      $('#check_inn').hide();
    }
  });

  var ajaxRequest = function(arValid, cnt)
  {
    let self = arValid[cnt],
      parent = $(self).closest('.check-inn__block-input');

    $(parent).addClass('load');
    $.ajax({
      type: 'POST',
      url: '/ajax/Self_employed',
      data: 'inn=' + $(self).val(),
      success: function(r)
      {
        r = JSON.parse(r);
        let message = '';

        if(r.error==true)
        {
          $(parent).removeClass('load');
          message = 'Непредвиденная ошибка. Пожалуйста обратитесь к администратору';
        }
        else
        {
          $(parent).removeClass('load');
          message = r.response.message;
        }
        $(parent).find('span').remove();
        $(parent).append('<span>' + message + '</span>');
        if($(arValid[cnt+1]).is('*'))
        {
          ajaxRequest(arValid, cnt+1);
        }
        else
        {
          $('#check_inn').show();
        }
      }
    });
  }
});