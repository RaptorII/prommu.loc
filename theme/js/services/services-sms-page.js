(function($){
  //  Проверка валидности СМС символов
  $.fn.smsArea = function(options){
    var
    e = this,
    cutStrLength = 0,
    s = $.extend({
        cut: true,
        maxSmsNum: 1,
        counters: { character: $('#mess-mlength') },
        lengths: {
          ascii: [600], // -12 символов на '-PROMMU.COM-'
          unicode: [256] // -12 символов на '-PROMMU.COM-'
          /*ascii: [160, 306, 459, 619],unicode: [70, 134, 201, 268]*/
        }
    }, options);
    e.keyup(function(){
      var
      smsType,
      smsLength = 0,
      smsCount = -1,
      charsLeft = 0,
      text = e.val(),
      isUnicode = false;

      for(var charPos = 0; charPos < text.length; charPos++){
        switch(text[charPos]){
            case "\n": 
            case "[":
            case "]":
            case "\\":
            case "^":
            case "{":
            case "}":
            case "|":
            case "€":
                smsLength += 2;
            break;
            default:
                smsLength += 1;
        }
        //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
        if(text.charCodeAt(charPos) > 127 && text[charPos] != "€")
        isUnicode = true;
      }
      if(isUnicode)
        smsType = s.lengths.unicode;
      else
        smsType = s.lengths.ascii;
      for(var sCount = 0; sCount < s.maxSmsNum; sCount++){
          cutStrLength = smsType[sCount];
          if(smsLength <= smsType[sCount]){
              smsCount = sCount + 1;
              charsLeft = smsType[sCount] - smsLength;
              break
          }
      }
      if(s.cut) e.val(text.substring(0, cutStrLength));
      smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

      s.counters.character.html(charsLeft);
    }).keyup()
  }     
}(jQuery));
$(function(){
  /*
  *   PAGE 1
  */
  var arInputs = $('.smss-vacancies__item-input'),
      $button = $('#sms-vac-btn'),
      $vacancy = $('#vacancy'),
      strParent = '.smss-vacancies__item';
  //  select vacancies
  arInputs.change(function(){
    var $this = $(this);

    if($this.is(':checked')){
      $.each(arInputs, function(){
        if($(this).is($this)){
          $(this).parent(strParent).addClass('active');
          $vacancy.val($(this).val());
          $button.fadeIn();         
        }
        else{
          $(this).prop('checked', false);
          $(this).parent(strParent).removeClass('active');            
        }
      });
    }
    else{
      $this.parent(strParent).removeClass('active');
      $vacancy.val('');
      $button.fadeOut();
    }  
  });
  /*
  *   PAGE 2
  */
  var $form = $('#F1Filter'),
      $content = $('#content'),
      $load = $('.smss__veil'),
      $cntW = $('.smss-workers__form-workers'),
      arSelectIdies = [];

  $('.templatingSelect2').select2(); // мультиселект для города
  $('#ank-srch-cities').change(function(){ getVacanciesAjax() });  
  var selectTimer = setInterval(function(){ checkSelect() }, 100);// показываем города, когда загрузилась либа
  // вкладки фильтра
  $('.smss__filter-name').click(function(){
    var $it = $(this);
    if($it.hasClass('opened')){
      $it.siblings('.smss__filter-content').slideUp(200);
      setTimeout(function(){
        $it.removeClass('opened');
        $it.siblings('.smss__filter-content').removeClass('opened');
      },200);
    }
    else{
      $it.addClass('opened');
      $it.siblings('.smss__filter-content').slideDown(500);
      $it.siblings('.smss__filter-content').addClass('opened');
    }
  });
  // подгрузка данных для пола, и дополнительно
  $('.filter-sex input, .filter-additional input').change(function(){ 
    setTimeout(function(){ getVacanciesAjax() }, 300); 
  });
  // подгрузка данных при перелистывании
  $('#content').on('click', '.paging-wrapp a', function(e){ getVacanciesAjax(e) });
  //  выбор работников
  $content.on('change', '.promo_inp', function(){
    var id = Number($(this).val());
    if($(this).is(':checked')){
      // записуем выбраный ID
      if($.inArray(id, arSelectIdies)<0){ arSelectIdies.push(id) };
      if(arSelectIdies.length == arIdies.length) 
        $('#mess-all').prop('checked',true);  
    }
    else{
      // убираем ID
      if($.inArray(id, arSelectIdies)>=0){ arSelectIdies.splice(arSelectIdies.indexOf(id),1) }
      $('#mess-all').prop('checked',false);
    }
    $('#mess-workers').val(arSelectIdies);
    $('#mess-wcount').html(arSelectIdies.length);
    $('#mess-wcount-inp').val(arSelectIdies.length);
    error = false;
    if(!checkText()) error = true;
    if(!checkCount(true)) error = true;
    error ? $('#mess-form-btn').addClass('off') : $('#mess-form-btn').removeClass('off');
  });
  //  выбрать всех
  $('#mess-all').change(function(){
    if($(this).is(':checked')){
      arSelectIdies = arIdies.slice();
      $.each($content.find('.promo_inp'), function(){ $(this).prop('checked',true) });
    }
    else{
      arSelectIdies = [];
      $.each($content.find('.promo_inp'), function(){ $(this).prop('checked',false) });
    }
    $('#mess-workers').val(arSelectIdies);     
    $('#mess-wcount').html(arSelectIdies.length);
    $('#mess-wcount-inp').val(arSelectIdies.length);
    error = false;
    if(!checkText()) error = true;
    if(!checkCount(true)) error = true;
    error ? $('#mess-form-btn').addClass('off') : $('#mess-form-btn').removeClass('off');
  });
  // проверка смс сообщений
  $('#mess-text').smsArea();
  // отправка формы
  $('#mess-form').submit(function(){
    if(!checkCount(true)) return false; 
    if(!checkText(true)) return false;
    // передаем фильтр
    if($('#mess-all').is(':checked')){
      var filter = $('#F1Filter').serialize();
      $('#mess-filter').val(filter);
    }
  });
  // событие проверки заполненности текстового поля
  $('#mess-text').keyup(function(){ 
    error = false;
    if(!checkText(true)) error = true;
    if(!checkCount()) error = true;
    error ? $('#mess-form-btn').addClass('off') : $('#mess-form-btn').removeClass('off');
  });
  //  выделение ошибки
  $('#mess-all').change(function(){
    $('#mess-wcount-inp').val()==0 ? $cntW.addClass('error') : $cntW.removeClass('error');
  });
  $content.on('change', '.promo_inp', function(){
    $('#mess-wcount-inp').val()==0 ? $cntW.addClass('error') : $cntW.removeClass('error');
  });
  //
  //
  // функция проверки заполненности текстового поля
  function checkText(err=false){
    var $this = $('#mess-text');
    if($this.val()==''){
      if(err) $this.addClass('error');
      return false;
    }
    else{
      if(err) $this.removeClass('error');
      return true;
    }
  }
  // функция проверки выбранных пользователей
  function checkCount(err=false){
    if($('#mess-wcount-inp').val()==0){
      if(err) $cntW.addClass('error');
      return false;
    }
    else{
      if(err) $cntW.removeClass('error');
      return true;
    }
  }
  //  
  function getVacanciesAjax(e=false){
    var params = $form.serialize();

    if(e){  // прокрутка страниц
      e.preventDefault();
      params = e.target.href.slice(e.target.href.indexOf(AJAX_GET_PROMO) + 30);// вырезаем GET
    }
    $load.show(); // процесс загрузки

    $.ajax({
      type: 'GET',
      url: AJAX_GET_PROMO,
      data: params,
      success: function(res){
        $content.html(res);
        if(e){   // постраничное обновление
          $('html, body').animate({ scrollTop: $content.offset().top - 100 }, 700);//прокручиваем к заголовку
          $.each($content.find('.promo_inp'), function(){ 
            var id = Number($(this).val());
            if($.inArray(id,arSelectIdies)>=0 || $('#mess-all').is(':checked'))
              $(this).prop('checked',true);
          });
        }
        else{
          arSelectIdies = [];
          $('#mess-workers').val(arSelectIdies);     
          $('#mess-wcount').html(0);
          $('#mess-wcount-inp').val(0);
          $('#mess-all').prop('checked',false);
        }
        $load.hide();
      }
    });
  }
  // показываем города, когда загрузилась либа
  function checkSelect(){ 
    if($('*').is('.filter-cities .select2')){
      $('.filter-cities .smss__filter-content').addClass('active');
      clearInterval(selectTimer); 
    }
  }
})