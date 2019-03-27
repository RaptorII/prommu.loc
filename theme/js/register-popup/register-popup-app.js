'use strict'
var RegisterPopupApp = (function () {

  RegisterPopupApp.prototype.result = [];
  RegisterPopupApp.prototype.cityTimer = false;

  function RegisterPopupApp() { this.init() }

  RegisterPopupApp.prototype.init = function () {
    let self = this;

    // устанавливаем начальные параметры
    $.each($('#popup-form').serializeArray(),function(){
      self.result[this.name] = this.value;
    });
    //
    //
    // инициализация календаря
    $("#datepicker").datepicker({
      maxDate: '-14y',
      changeYear: true,
      yearRange: "1950:2005",
      beforeShow: function(){
        $('#ui-datepicker-div').addClass('custom-calendar');
      }
    });
    // проверка корректности даты
    if($('#datepicker').is('*')){
      $('#datepicker').change(function(){ self.checkDate() });
    }
    //
    //
    // обработчик всех кликов
    $(document).click(function(e){
      self.selectPosition(e.type, e.target);
      self.closeCityList(e.target);
    });
    //
    //
    // обработчик выбора должностей
    $('#post_input').on('focus input',function(e){ self.selectPosition(e.type, this); });
    $('#post_input').on('blur',function(e){
      setTimeout(function(){
        let val = $('#post_hidden').val();
        if(!val.length)
          $(e.target).addClass('error');
        else if(self.result.position!=val)
        {
          self.setAjax({position:val});
          self.result.position = val;
          $(e.target).removeClass('error');
        }
      },100);
    });
    //
    //
    // обработчик выбора даты
    $('#datepicker').on('blur',function(e){
      setTimeout(function(){
        let val = $(e.target).val();
        if(!val.length)
          $(e.target).addClass('error');
        else if(self.result.birthday!=val)
        {
          self.setAjax({birthday:val});
          self.result.birthday = val;
          $(e.target).removeClass('error');
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
    // проверка перед отправкой формы
    $('#form_btn, .rp-header__close-btn').click(function(e)
    {
      let bError = false;

      e.preventDefault();

      if(MainScript.isButtonLoading(this))
        return false; 
      else if(this.id==='form_btn')
        MainScript.buttonLoading(this,true);

      if(!$('#datepicker').val().length)
      {
        $('#datepicker').addClass('error');
        bError = true;
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
      if(!$('#post_hidden').val().length)
      {
        $('#post_input').addClass('error');
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
  RegisterPopupApp.prototype.selectPosition = function (event, item)
  {
    let arPosts = $('#post_list li');
        
    if((event=='click' || event=='focus') && $(item).attr('id')=='post_input') // работаем с селектом
    {
      $('#post_list').show();
      return;
    }
    else if($(item).closest('#post_list').length) // кликнули по элементам списка
    {
      if(item.dataset.id>0)
      {
        $('#post_hidden').val(item.dataset.id);
        $('#post_input').val($(item).text()).removeClass('error');
        $('#post_list').hide();
        return;
      }
    }
    else if(event!='input') // убрать список
    {
      let hInput = $('#post_hidden').val();
      if(hInput.length)
      {
        for (let i=1, n=arPosts.length; i<n; i++)
          if(hInput==arPosts[i].dataset.id)
            $('#post_input').val($(arPosts[i]).text()).removeClass('error');
      }
      $('#post_list').hide();
    }
    //
    if((event=='click' || event=='focus') && $(item).attr('id')=='post_input') // события клика и фокуса
    {
      $('#post_list').show();
    }
    if(event=='input') // события ввода
    {
      let bList = false,
          v = $('#post_input').val().toLowerCase();

      for (let i=1, n=arPosts.length; i<n; i++)
      {
        if(v.length)
        {
          let postName = $(arPosts[i]).text().toLowerCase();
          if(postName.indexOf(v)>=0) // показываем совпадающие элементы
          {
            $(arPosts[i]).show();
            bList = true;
          }
          else
          {
            $(arPosts[i]).hide();
          }          
        }
        else
        {
          $(arPosts[i]).show();
          bList = true;         
        }
      }
      !bList ? $(arPosts[0]).show() : $(arPosts[0]).hide();
    }
  }
  //
  RegisterPopupApp.prototype.setAjax = function (data)
  {
    $('.register-popup__veil').show();
    $.ajax({
      type: 'POST',
      url: '/ajax/profile',
      data: {data: JSON.stringify(data)},
      dataType: 'json',
      success: function(r){ console.log(r) },
      complete: function(){ $('.register-popup__veil').hide() }
    });
  }
  // проверка корректности даты
  RegisterPopupApp.prototype.checkDate = function ()
  {
    let item = document.getElementById('datepicker');

    if($(item).val().length)
    {
      let objDate = $(item).datepicker('getDate'),
          d = String(objDate.getDate()),
          m = String(objDate.getMonth()+1);

      d = d.length<2 ? ('0'+d) : d;
      m = m.length<2 ? ('0'+m) : m;

      item.value = d + '.' + m + '.' + objDate.getFullYear();      
    }
  }
  //
  RegisterPopupApp.prototype.closeCityList = function (item)
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
  //
  RegisterPopupApp.prototype.selectCity = function (event, item)
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
  //      правильный ввод названия города
  RegisterPopupApp.prototype.setFirstUpper = function (e)
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
  return RegisterPopupApp;
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

  new RegisterPopupApp();

  $('body').append('<div class="bg_veil"></div>'); // фон для окна пуш настроек
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
  //  таймер проверки загрузки фото
  setInterval(function (e){
    if($('#HiLogo').val() != ''){
      $('#applicant-img').attr('src','/images/applic/'+$('#HiLogo').val()+'400.jpg');
      remEr('.rp-content1__logo-img');
      let error = false;
      $.each(arInputs, function(){ if(!checkFieldEasy(this)) error = true; });
      //(!error && !$('.error').length) ? remEr('#applicant-btn','off') : addEr('#applicant-btn','off');
    }
  }, 1000);
  /*
  *     Финкции
  */
  //  отображение пуш настроек
  function pushProps(){
    $('.push-popup__form').fadeIn();
    $('.bg_veil').fadeIn();
    $('html, body').animate({scrollTop: 0},500);   
  }
  //  отправка пуш настроек
  function sendPushData(){
    $('.push-popup__form').fadeOut();
    $('.bg_veil').fadeOut();
    var arInputs = [
      'all', 
      'rate', 
      'respond',
      'mess',
      'workday',
    ];
    $.each(arInputs, function(){
      $sourse = $('#'+this);
      $receiver = $('#popup-form [name='+this+']');
      $sourse.is(':checked') ? $receiver.val(2) : $receiver.val(0);
    });
    return false;
  } 
  //  визуализация ошибок
  function addEr(e, style='error'){ $(e).addClass(style) }
  function remEr(e, style='error'){ $(e).removeClass(style) }

  function checkFieldEasy(e){
    if($(e).val()=='' || $(e).val()==null) return false;
    else return true;
  }
});