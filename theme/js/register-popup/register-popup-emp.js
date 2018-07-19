$(function(){
  var arInputs = $('.required-inp'),
      oldPhone = $('#phone-code').val(),
      cityTimer = false, // таймер обращения к серверу для поиска городов
      bShowCityList = true, // флаг отображения списка городов
      keyCode = 0;

  $('body').append('<div class="bg_veil"></div>');

  $(document).keydown(function(e){ keyCode = e.keyCode }); // код нажатой клавиши

    var timerCity = setInterval(checkCity,100),
      $code = $('[name="__phone_prefix"]'),
      oldPhoneCode = $code.val();

    function checkCity(){
      if(oldPhoneCode!=$code.val()){
        oldPhoneCode = $code.val();
        clearInterval(timerCity);
        var $input = $('#city-input'),
          val = $input.val().charAt(0).toUpperCase() + $input.val().slice(1).toLowerCase(),
          piece = $input.val().toLowerCase();

        for (var i=0; i<arCountries.length; i++)
          if(oldPhoneCode==arCountries[i].phone){
            country = arCountries[i].id_co;
            break;
          }

        $.ajax({
          url: MainConfig.AJAX_GET_VE_GET_CITIES,
          data: 'idco=' + country + '&query=' + val,
          dataType: 'json',
          success: function(res){
            var errCity = true;
            $.each(res.suggestions, function(){ // список городов если что-то введено
              if(this.value.toLowerCase()===piece && this.data!=='man'){ // если введен именно город полностью
                remEr('#city-input');
                $input.val(val);
                errCity = false;
              }
            });
            if(errCity){
              addEr('#city-input');
              $input.val('');
            }
            timerCity = setInterval(checkCity,100);
          }
        });
      }
    }
  
  $('#phone-code').on('blur',function(){
    var len = $(this).val().replace(/\D+/g,'').length,
        code = $('[name="__phone_prefix"]').val(),
        phoneLen = 10; 

        if(code.length==3 && len<9){ // UKR
          addEr($(this).closest('div'));
          $(this).val('');
        }
        else if(code.length==1 && len<10){ // RF
          addEr($(this).closest('div'));
          $(this).val('');
        }
        else{
          remEr($(this).closest('div')); 
        }
  });

  //  таймер проверки загрузки фото
  setInterval(function (e){
    if($('#HiLogo').val() != ''){
      $('#company-img').attr('src','/images/company/tmp/'+$('#HiLogo').val()+'400.jpg');
      remEr('.rp-content1__logo-img');
      error = false;
      $.each(arInputs, function(){ if(!checkFieldEasy(this)) error = true; });
      (!error && !$('.error').length) ? remEr('#company-btn','off') : addEr('#company-btn','off');
    }
  }, 1000);
  //
  //  события
  //
  //  проверка полей
  $('.required-inp').change(function(){ 
    checkField(this);
    error = false;
    $.each(arInputs, function(){ if(!checkFieldEasy(this)) error = true; });
    (!error && !$('.error').length) ? remEr('#company-btn','off') : addEr('#company-btnn','off');
  });
  // проверка перед отправкой формы
  $('#popup-form').submit(function(){
    $.each(arInputs, function(){ checkField(this) });
    if($('.error').length>0){
      $('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
      addEr('#company-btn','off');
      return false;
    }
    else{
      remEr('#company-btn','off');
    } 
  });
  //    push
  $('#push-props').click(function(){ pushProps() });
  $('#push-checkbox').change(function(){ if(!$(this).prop('checked')) pushProps() });
  $('body').on('change', '#all', function(){
    $(this).prop('checked') ? $('.pp-form__all-props').fadeOut() : $('.pp-form__all-props').fadeIn();
  });
  $('.bg_veil').click(function(){ sendPushData() });
  $('.push-popup__form').submit(function(){ return sendPushData() });
  $('#push-checkbox').change(function(){ $(this).prop('checked') ? $('#push-props').hide() : $('#push-props').show() });
  //    города
  //  поиск городов по вводу
  $('#city-input').bind('input focus', function(e){
    var $input = $(this),
        sec = e.type==='focus' ? 1 : 1000;

    bShowCityList = true;
    clearTimeout(cityTimer);
    cityTimer = setTimeout(function(){
      setFirstUpper('#city-input'); //  город с большой буквы
      var val = $input.val(),
          piece = $input.val().toLowerCase(),
          main = $input.closest('span'),
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
              arCities.push(this.value);
            });// список городов если ничего не введено
            arCities.length>0  
            ? $.each(arCities, function(){ content += '<li>'+this+'</li>' })         
            : content = '<li class="emp">Список пуст</li>';
            $('#city-list').empty().append(content);
            if(bShowCityList)
              $('#city-list').show();
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
              word = this.value.toLowerCase();
              if(word===piece && this.data!=='man'){ // если введен именно город полностью
                remEr('#city-input');
                arCities.push(this.value);
              }
              else if(word.indexOf(piece)>=0 && this.data!=='man')
                arCities.push(this.value);
            });
            arCities.length>0  
            ? $.each(arCities, function(){ content += '<li>'+this+'</li>' })         
            : content = '<li class="emp">Список пуст</li>';
            $('#city-list').empty().append(content);
            if(bShowCityList)
              $('#city-list').show();
            $(main).removeClass('load');
          }
        });
      } 
    },sec);   
  });
  //  выбор города из списка
  $(document).on('click', '#city-list li', function(){
    if(!$(this).hasClass('emp')){
      $('#city-input').val($(this).text());
      bShowCityList = false;
      $('#city-list').hide();
      remEr('#city-input');
    }
    else{
      addEr('#city-input');
    }
  });
  //  закрываем список городов
  $(document).click(function(e){
    if(!$('#city-input').is(e.target) && !$(e.target).closest('#city-list').length){
      setFirstUpper('#city-input'); //  город с большой буквы

      var $input = $('#city-input'),
          val = $input.val(),
          piece = $input.val().toLowerCase();
    
      $.ajax({
        url: MainConfig.AJAX_GET_VE_GET_CITIES,
        data: 'idco=' + country + '&query=' + val,
        dataType: 'json',
        success: function(res){
          var errCity = true;
          $.each(res.suggestions, function(){ // список городов если что-то введено
            if(this.value.toLowerCase()===piece && this.data!=='man'){ // если введен именно город полностью
              remEr('#city-input');
              $input.val(val);
              setFirstUpper('#city-input');
              errCity = false;
            }
          });
          if(errCity){
            addEr('#city-input');
            $input.val('');
          }
        }
      });
      bShowCityList = false;
      $('#city-list').hide();
    }
  });
  //
  //    функции
  //
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
  //  проверка полей
  function checkField(e){
    var val = $(e).val(), erBlock = e;
    if($(e).is('#HiLogo')){ erBlock='.rp-content1__logo-img' }

    (val=='' || val==null) ? addEr(erBlock) : remEr(erBlock);
  }
  function checkFieldEasy(e){
    if($(e).val()=='' || $(e).val()==null) return false;
    else return true;
  }
  //      правильный ввод названия города
  function setFirstUpper(e) {
    var split = $(e).val().split(' ');

    for(var i=0, len=split.length; i<len; i++)
        split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    split = $(e).val().split('-');
    for(var i=0, len=split.length; i<len; i++)
        split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  }
}); 