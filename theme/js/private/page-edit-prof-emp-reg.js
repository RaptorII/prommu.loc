jQuery(function($){
  var phoneLen = 10, // нормальное кол-во цифр в телефоне
    epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
    oldEmail = $('.epe__input-mail').val(),
    emailTimer = null,
    oldPhone = $('#phone-code').val(),
    oldFlag = '',
    confirmEmail = $('#conf-email').hasClass('complete') ? true : false;
  confirmPhone = $('#conf-phone').hasClass('complete') ? true : false;
  //
  //
  $(document).on('click', function(e){
    var it = e.target;
    // select
    var listT = '#epe-list-type',
      listM = '#epe-list-mess';

    if(it.id=='epe-veil-type') $(listT).fadeIn();
    else if($(it).is('#epe-list-type i') || !$(it).closest(listT).length) $(listT).fadeOut();
    if(it.id=='epe-veil-mess') $(listM).fadeIn();
    else if($(it).is('#epe-list-mess i') || !$(it).closest(listM).length) $(listM).fadeOut();
  });
  // события выбора мессенджера
  $('#epe-list-mess input').on('change', function(){
    var arInputs = $('#epe-list-mess input'),
      arMess = [];
    showHint = false;
    $.each(arInputs, function(){
      mess = $(this).data('mess');
      if($(this).is(':checked')){
        arMess.push($(this).siblings('label').text());
        $('.epe__mess-'+mess).removeClass('off');
        showHint = true;
      }
      else{
        $('.epe__mess-'+mess).addClass('off');
        $('.epe__mess-'+mess+' input').val('');
      }
    });
    $('#epe-str-mess').val(arMess);
    showHint ? $('.epe__mess-hint').removeClass('off') : $('.epe__mess-hint').addClass('off');
  });
  //	изменяем тип работодателя
  $('#epe-list-type input').on('change', function(){
    var arInputs = $('#epe-list-type input');
    $.each(arInputs, function(){
      if($(this).is(':checked'))
        $('#epe-str-type').text($(this).siblings('label').text());
    });
    $('#epe-list-type').fadeOut();
  });
  //
  //		Ввод телефона
  //
  $(document).on('click',function(e){ checkPhone(e) });
  $('#phone-code').on('input',function(e){ checkPhone(e) });
  //
  $('.epe__btn').click(function(e){
    var self = this,
      nemail = $('.epe__input-mail').val(),
      bAvatar = $('#login-img').hasClass('active-logo'),
      errors = false;

    e.preventDefault();

    if(MainScript.isButtonLoading(self))
      return false;
    else
      MainScript.buttonLoading(self,true);


    checkPhone({'type':false,'target':false});

    if(epattern.test(nemail) && nemail!=oldEmail){
      clearTimeout(emailTimer);
      emailTimer = setTimeout(function(){
        $.ajax({
          type: 'POST',
          url: '/ajax/emailVerification',
          data: 'nemail='+nemail+'&oemail='+oldEmail,
          dataType: 'json',
          success: function(res){
            res
              ? $('.epe__email').addClass('erroremail error')
              : $('.epe__email').removeClass('erroremail error');

            if(!checkField($('.epe__input-name'))) errors = true;
            // роверка наличия аватара
            if(!bAvatar)
            {
              $('.avatar__logo-main').addClass('input__error');
            }
            var arErrors = $('.error');
            if(arErrors.length>0)
            {
              var scrollItem = (!bAvatar ? $('.input__error')[0] : $('.error')[0]);
              $('html, body').animate({ scrollTop: $(scrollItem).offset().top-20 }, 1000);
              MainScript.buttonLoading(self,false);
            }
            if(!errors && !arErrors.length && bAvatar){
              $('#F1compprof').submit();
            }
          }
        });
      }, 500);
    }
    else{
      $.each($('.epe__required'), function(){
        if(!checkField(this)) errors = true;
      });
      // роверка наличия аватара
      if(!bAvatar)
      {
        $('.avatar__logo-main').addClass('input__error');
      }
      var arErrors = $('.error');
      if(arErrors.length>0)
      {
        var scrollItem = (!bAvatar ? $('.input__error')[0] : $('.error')[0]);
        MainScript.buttonLoading(self,false);
        $.fancybox.open({
          src: '.prmu__popup',
          type: 'inline',
          touch: false,
          afterClose: function(){
            $('html, body').animate({ scrollTop: $(scrollItem).offset().top-20 }, 1000);
          }
        });
      }
      if(!errors && !arErrors.length && bAvatar){
        $('#F1compprof').submit();
      }
    }
    //console.log($('#F1compprof').serializeArray());
  });
  //    Проверка полей
  $('.epe__required').on('input', function(){ checkField(this) });
  $('.epe__required').on('change', function(){ checkField(this) });
  //
  //	Functions
  //
  // получаем номер
  function getNum(value){ return value.replace(/\D+/g,'') }
  // additional functions
  function addErr(e){
    $(e).addClass('error');
    return false;
  }
  function remErr(e){
    $(e).removeClass('error');
    return true;
  }
  // check fields
  function checkField(e){
    var val = $(e).val(),
      label = $(e).closest('.epe__label'),
      res = false;

    if($(e).hasClass('epe__input-mail')){
      res = epattern.test(val) ? remErr(label) : addErr(label);
      $('.epe__email').removeClass('erroremail');
      if(res && val!=oldEmail){
        clearTimeout(emailTimer);
        emailTimer = setTimeout(function(){
          $.ajax({
            type: 'POST',
            url: '/ajax/emailVerification',
            data: 'nemail='+val+'&oemail='+oldEmail,
            dataType: 'json',
            success: function(res){
              if(res){
                $('.epe__email').addClass('erroremail error');
              }
              else{
                $('.epe__email').removeClass('erroremail error');
                $('#conf-email').removeClass('complete')
                  .html('<p>Почта не подтверждена. <em>Подтвердить</em></p>');
                confirmEmail = false;
              }
            }
          });
        }, 500);
      }
    }
    else{
      res = ((val=='' || val==null) ? addErr(label) : remErr(label));
    }
    return res;
  };
  // проверка номера
  function checkPhone(e){
    var $inp = $('#phone-code'),
      len = getNum($inp.val()).length,
      code = $('[name="__phone_prefix"]').val().length;

    if(e.type=='click' && !$(e.target).is('.country-phone') && !$(e.target).closest('.country-phone').length){
      if((code==3 && len<9) || (code==1 && len<10)){ // UKR || RF
        addErr($inp.closest('.epe__label'));
        $inp.val('');
      }
      else{
        remErr($inp.closest('.epe__label'));
        if($inp.val()!==oldPhone){
          $('#conf-phone').removeClass('complete')
            .html('<p>Телефон не подтвержден. <em>Подтвердить</em></p>');
          confirmPhone = false;
        }
      }
    }
    else{
      if((code==3 && len<9) || (code==1 && len<10) || len==0){
        addErr($inp.closest('.epe__label'));
      }
      else{
        remErr($inp.closest('.epe__label'));
        if($inp.val()!==oldPhone){
          $('#conf-phone').removeClass('complete')
            .html('<p>Телефон не подтвержден. <em>Подтвердить</em></p>');
          confirmPhone = false;
        }
      }
    }
  }
  //
  getFlagTimer = setInterval(function(){ // ищем флаг страны
    if($('.country-phone-selected>img').is('*')){
      oldFlag = $('.country-phone-selected>img').attr('class');
      clearInterval(getFlagTimer);
    }
  },500);
  //
  //      ГОРОДА
  //
  selectCities({
    'main' : '#multyselect-cities',
    'arCity' : arSelectCity,
    'span' : 'Город *',
    'inputName' : 'cities[]'
  });
  //
  function selectCities(obj){
    var $main = $(obj.main).append('<span></span><ul class="cities-select"><li data-id="0"><input type="text" name="c"></li></ul><ul class="cities-list"></ul><b></b>'), // родитель
      $span = $main.find('span').text(obj.span), // placeholder
      $select = $main.find('ul').eq(0), // список ввода
      $input = $select.find('input'), // ввод города
      $list = $main.find('ul').eq(1), // список выбора
      $load = $main.find('b'), // тег загрузки
      bShowCityList = true, // флаг отображения списка городов
      cityTimer = false; // таймер обращения к серверу для поиска городов

    // добавляем уже выбранный город
    if(typeof obj.arCity!=='undefined')
    {
      $.each(obj.arCity, function(){
        content = '<li data-id="' + this.id + '">' +
          this.name + '<i></i><input type="hidden" name="' + obj.inputName + '" value="' + this.id + '">' +
          '</li>';
        $select.prepend(content);
      });
      $span.hide();
    }
    // при клике по блоку фокусируем на поле ввода
    $select.click(function(e){ if(!$(e.target).is('i')) $input.focus() });
    $input.click(function(e){ if(!$(e.target).is('i')) $input.focus() })
    // обработка событий поля ввода
    $input.bind('input focus blur', function(e){
      setFirstUpper($input);

      var val = $input.val(),
        sec = e.type==='focus' ? 1 : 1000;

      $input.val(val).css({width:(val.length * 10 + 5)+'px'});// делаем ширину поля по содержимому, чтобы не занимало много места
      bShowCityList = true;
      clearTimeout(cityTimer);
      cityTimer = setTimeout(function(){
        setFirstUpper($input);

        var arResult = [],
          content = '',
          val = $input.val(),
          piece = $input.val().toLowerCase();

        arSelectId = getSelectedCities($select);// находим выбранные города
        if(arSelectId.length) $span.hide(); // показываем или прячем placeholder
        else val==='' ? $span.show() : $span.hide();

        if(e.type!=='blur'){ // если мы не потеряли фокус
          if(val===''){ // если ничего не введено
            $load.show(); // показываем загрузку
            $.ajax({
              url: MainConfig.AJAX_GET_VE_GET_CITIES,
              data: 'idco=' + obj.arCity.id_co + '&query=' + val,
              dataType: 'json',
              success: function(res){
                $.each(res.suggestions, function(){ // список городов если ничего не введено
                  if($.inArray(this.data, arSelectId)<0)
                    content += '<li data-id="' + this.data + '">' + this.value + '</li>';
                });
                if(bShowCityList)
                  $list.empty().append(content).fadeIn();
                else{
                  $list.empty().append(content).fadeOut();
                  $input.val('');
                }
                $load.hide();
              }
            });
          }
          else{
            $load.show();
            $.ajax({
              url: MainConfig.AJAX_GET_VE_GET_CITIES,
              data: 'idco=' + obj.arCity.id_co + '&query=' + val,
              dataType: 'json',
              success: function(res){
                $.each(res.suggestions, function(){ // список городов если что-то введено
                  word = this.value.toLowerCase();
                  if(word===piece && $.inArray(this.data, arSelectId)<0 && this.data!=='man'){ // если введен именно город полностью
                    html =  '<li data-id="' + this.data + '">' + this.value +
                      '<i></i><input type="hidden" name="' + obj.inputName + '" value="' + this.data + '"/>' +
                      '</li>';
                    $select.find('[data-id="0"]').before(html);
                    remErr($main);
                    bShowCityList = false;
                  }
                  else if(word.indexOf(piece)>=0 && $.inArray(this.data, arSelectId)<0 && this.data!=='man')
                    arResult.push( {'id':this.data, 'name':this.value} );
                });
                arResult.length>0
                  ? $.each(arResult, function(){ content += '<li data-id="' + this.id + '">' + this.name + '</li>' })
                  : content = '<li class="emp">Список пуст</li>';
                if(bShowCityList)
                  $list.empty().append(content).fadeIn();
                else{
                  $list.empty().append(content).fadeOut();
                  $input.val('');
                }
                $load.hide();
              }
            });
          }
        }
        else{ // если потерян фокус раньше времени
          $input.val('');
          if(getSelectedCities($select).length){
            $span.hide();
            remErr($main);
          }
          else{
            $span.show();
            addErr($main);
          }
        }
      },sec);
    });
    // Закрываем список
    $(document).on('click', function(e){
      if($(e.target).is('li') && $(e.target).closest($list).length && !$(e.target).hasClass('emp')){ // если кликнули по списку && если это не "Список пуст" &&
        $(e.target).remove();
        $span.hide();
        html =  '<li data-id="' + $(e.target).data('id') + '">' + $(e.target).text() +
          '<i></i><input type="hidden" name="' + obj.inputName + '" value="' + $(e.target).data('id') + '"/>' +
          '</li>';
        $select.find('[data-id="0"]').before(html);
        remErr($main);
        $list.fadeOut();
      }
      if($(e.target).is('i') && $(e.target).closest($select).length){ // удаление выбраного города из списка
        $(e.target).closest('li').remove();
        l = getSelectedCities($select).length;
        l ? $span.hide() : $span.show();
        l ? remErr($main) : addErr($main);
      }
      if(!$(e.target).is($select) && !$(e.target).closest($select).length){ // закрытие списка
        bShowCityList = false;
        $list.fadeOut();
      }
    });
  }
  function getSelectedCities(ul){
    var arId = [],
      arSelected = $(ul).find('li');
    $.each(arSelected, function(){
      if($(this).data('id')!=0)
        arId.push(String($(this).data('id')));
    });
    return arId;
  }
  //      правильный ввод названия города
  setFirstUpper = function (e) {
    let split = $(e).val().split(' ');

    for(let i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    split = $(e).val().split('-');
    for(let i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  },
  //
  $('[name="user-attribs[stationaryphone]"]').on('input',function(){
    this.value = this.value.replace(/\D+/g,'');
  })
  //
  // начальное выделение полей
  //
  $.each($('.epe__required'), function(){ checkField(this) });
});