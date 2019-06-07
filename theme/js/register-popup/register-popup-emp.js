'use strict'
var RegisterPopupEmp = (function () {

  RegisterPopupEmp.prototype.result = [];
  RegisterPopupEmp.prototype.cityTimer = false;

  function RegisterPopupEmp() { this.init() }

  RegisterPopupEmp.prototype.init = function () {
    let self = this;

    // устанавливаем начальные параметры
    $.each($('#popup-form').serializeArray(),function(){
      self.result[this.name] = this.value;
    });
    //
    //
    // обработчик всех кликов
    $(document).click(function(e){
      self.closeCityList(e.target);
    });
    //
    //
    // обработчик выбора телефона
    $('#phone-code').on('blur',function(e){
      setTimeout(function(){
        let val = $(e.target).val(),
            len = val.replace(/\D+/g,'').length,
            code = $('[name="__phone_prefix"]').val(),
            main = $(e.target).closest('div');

            if(code.length==3 && len<9)
            { // UKR
              $(main).addClass('error');
              $(e.target).val('');
            }
            else if(code.length==1 && len<10)
            { // RF
              $(main).addClass('error');
              $(e.target).val('');
            }
            else if(self.result.phone!=val){
              self.setAjax({phone:code+val});
              self.result.phone = val;
              $(main).removeClass('error');
            }
      },100);
    });
    //
    //
    // обработчик выбора города
    $('#city_input').on('blur',function(e){
      setTimeout(function(){
        let val = $('#city_hidden').val();
        if(!val.length)
          $(e.target).addClass('error');
        else if(self.result.city!=val)
        {
          self.setAjax({city:val});
          self.result.city = val;
          $(e.target).removeClass('error');
        }
      },100);
    });
    //
    $('#city_input').on('input focus', function(e){
      self.selectCity(e.type, e.target);
    });
    // обработчик ввода контактного лица
    $('#contact_field').on('blur',function(e){
      setTimeout(function(){
        let val = e.target.value;
        if(!val.length)
          $(e.target).addClass('error');
        else if(self.result.contact!=val)
        {
          self.setAjax({contact:val});
          self.result.contact = val;
          $(e.target).removeClass('error');
        }
      },100);
    });
    // обработчик выбора типа компании
    $('#type_select').on('change',function(e){
      let option = $(e.target).find(':checked')[0],
          val = option.value;

      setTimeout(function(){
          self.setAjax({companyType:val});
          self.result.companyType = val;
      },100);
    });
    //
    //
    // проверка перед отправкой формы
    $('#form_btn, .rp-header__close-btn').click(function(e)
    {
      let bError = false;

      e.preventDefault();

      if(MainScript.isButtonLoading(this))
        return false; 
      else if(this.id==='form_btn')
        MainScript.buttonLoading(this,true);
      // проверка телефона
      let val = $('#phone-code').val(),
          main = $('#phone-code').closest('div');

      if(val.replace(/\D+/g,'').length<10)
      { // RF
        $(main).addClass('error');
        $(e.target).val('');
      }

      if($('.country-phone').hasClass('error'))
      {
        bError = true;
      }
      if(!$('#city_hidden').val().length)
      {
        $('#city_input').addClass('error');
        bError = true;
      }
      if(!$('#contact_field').val().length)
      {
        $('#contact_field').addClass('error');
        bError = true;
      }
      //
      if($('.error').length>0 || bError)
      {
        $.fancybox.open({
          src: '.prmu__popup',
          type: 'inline',
          touch: false,
          afterClose: function(){
            $('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
          }
        });

        if(this.id==='form_btn')
          MainScript.buttonLoading(this,false);
      }
      else
      {
        $('#popup-form').submit();
      }  
    }); 
  }
  //
  RegisterPopupEmp.prototype.setAjax = function (data)
  {
    $('.register-popup__veil').show();
    $.ajax({
      type: 'POST',
      url: '/ajax/profile',
      data: {data: JSON.stringify(data)},
      dataType: 'json',
      complete: function(){ $('.register-popup__veil').hide() }
    });
  }
  //
  RegisterPopupEmp.prototype.selectCity = function (event, item)
  {
    let self = this,
        sec = event==='focus' ? 1 : 1000;

    clearTimeout(self.cityTimer);
    self.cityTimer = setTimeout(function(){
      self.setFirstUpper('#city_input');
      var val = $(item).val(),
          piece = $(item).val().toLowerCase(),
          main = $(item).closest('span'),
          content = '',
          arCities = [];

      if(val===''){ // если ничего не введено
        $(main).addClass('load'); // показываем загрузку
        $.ajax({
          url: MainConfig.AJAX_GET_VE_GET_CITIES,
          data: 'idco=' + country + '&query=' + val,
          dataType: 'json',
          success: function(res){
            $.each(res.suggestions, function(){ 
              arCities.push(this);
            });// список городов если ничего не введено
            arCities.length>0  
            ? $.each(arCities, function(){ content += '<li data-id='+this.data+'>'+this.value+'</li>' })         
            : content = '<li class="emp">Список пуст</li>';
            $('#city_list').empty().append(content);
            $('#city_list').show();
            $(main).removeClass('load');
          }
        });
      }
      else{
        $(main).addClass('load');
        $.ajax({
          url: MainConfig.AJAX_GET_VE_GET_CITIES,
          data: 'idco=' + country + '&query=' + val,
          dataType: 'json',
          success: function(res){
            $.each(res.suggestions, function(){ // список городов если что-то введено
              let word = this.value.toLowerCase();
              if(word===piece && this.data!=='man'){ // если введен именно город полностью
                $('#city_input').removeClass('error');
                arCities.push(this);
              }
              else if(word.indexOf(piece)>=0 && this.data!=='man')
                arCities.push(this);
            });
            arCities.length>0  
            ? $.each(arCities, function(){ content += '<li data-id='+this.data+'>'+this.value+'</li>' })         
            : content = '<li class="emp">Список пуст</li>';
            $('#city_list').empty().append(content);
            $('#city_list').show();
            $(main).removeClass('load');
          }
        });
      }
    },sec);
  }
  // выбор города из списка
  RegisterPopupEmp.prototype.closeCityList = function (item)
  {
    if($(item).closest('#city_list').length)
    {
      if(!$(item).hasClass('emp'))
      {
        $('#city_input').val($(item).text())
          .removeClass('error');
        $('#city_hidden').val(item.dataset.id)
          .data('name',$(item).text());
        $('#city_list').hide();
      }
      else
      {
        $('#city_input').addClass('error');
      }
    }
    else
    {
      $('#city_input').val($('#city_hidden').data('name'))
        .removeClass('error');
      $('#city_list').hide();
    }
  }
  //      правильный ввод названия города
  RegisterPopupEmp.prototype.setFirstUpper = function (e)
  {
    var split = $(e).val().split(' ');

    for(var i=0, len=split.length; i<len; i++)
        split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    split = $(e).val().split('-');
    for(var i=0, len=split.length; i<len; i++)
        split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  }
  //
  return RegisterPopupEmp;
}());
/*
*
*/
$(function(){
  var oldPhone = $('#phone-code').val(),
      keyCode = 0,
      arInputs = $('.required-inp'),
      $code = $('[name="__phone_prefix"]'),
      oldPhoneCode = $code.val();

  new RegisterPopupEmp();

  $('body').append('<div class="bg_veil"></div>');
  $(document).keydown(function(e){ keyCode = e.keyCode }); // код нажатой клавиши
  //  окно пуш настроек
  $('#push-props').click(function(){ pushProps() });
  $('#push-checkbox').change(function(){ if(!$(this).prop('checked')) pushProps() });
  $('body').on('change', '#all', function(){
    $(this).prop('checked') ? $('.pp-form__all-props').fadeOut() : $('.pp-form__all-props').fadeIn();
  });
  //  выбор всех пуш настроек
  $('#push-checkbox').change(function(){ $(this).prop('checked') ? $('#push-props').hide() : $('#push-props').show() });
  //  отправка пуш настроек на основную форму
  $('.bg_veil').click(function(){ sendPushData() });
  $('.push-popup__form').submit(function(){ return sendPushData() });
  /*
  *     Финкции
  */
  //  отображение пуш настроек
  function pushProps(){
    $('.push-popup__form').fadeIn();
    $('.bg_veil').fadeIn();
    $('html, body').animate({scrollTop: 0},500);    
  }
  //    send push props
  function sendPushData(){
    $('.push-popup__form').fadeOut();
    $('.bg_veil').fadeOut();
    var arPushInputs = [
      'all', 
      'rate', 
      'invite',
      'mess',
      'workday'
    ];
    $.each(arPushInputs, function(){
      $sourse = $('#'+this);
      $receiver = $('.register-popup-form [name='+this+']');
      $sourse.is(':checked') ? $receiver.val(2) : $receiver.val(0);
    });
    return false;
  }
  //  визуализация ошибок
  function addEr(e, style='error'){ $(e).addClass(style) }
  function remEr(e, style='error'){ $(e).removeClass(style) }
}); 