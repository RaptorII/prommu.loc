$(function(){
  /*
  *   PAGE 1
  */
  var arInputs = $('.service-vac__item-input');
  //  select vacancies
  arInputs.change(function(){
    var $this = $(this);

    if($this.is(':checked')){
      $.each(arInputs, function(){
        if($(this).is($this)){
          $(this).parent('.service-vac__item').addClass('active');
          $('#vacancy').val($(this).val());
          $('#vac-btn').fadeIn();         
        }
        else{
          $(this).prop('checked', false);
          $(this).parent('.service-vac__item').removeClass('active');            
        }
      });
    }
    else{
      $this.parent('.service-vac__item').removeClass('active');
      $('#vacancy').val('');
      $('#vac-btn').fadeOut();
    }  
  });
  $('#vac-btn').click(function(e){
    e.preventDefault();
    if(MainScript.isButtonLoading(this))
    {
      return false;
    }
    else
    {
      MainScript.buttonLoading(this,true);
      $(this.parentNode).submit();
    }
  });
  /*
  *   PAGE 2
  */
  var $form = $('#promo-filter'),
      $content = $('#promo-content'),
      $load = $('.filter__veil'),
      $cntW = $('.workers-form__cnt'),
      arSelectIdies = [],
      arSalaryInp = $('.filter-salary .psa__input');

  // salary
  arSalaryInp.focus(function() {
    for (var t = 1, e = 0; e < arSalaryInp.length; e++)
      $(this).is(arSalaryInp[e]) && (t = 5 < e ? 4 : e < 4 ? 1 < e ? 2 : 1 : 3);
    for (e = 0; e < arSalaryInp.length; e++)
    (1 == t && 0 != e && 1 != e || 2 == t && 2 != e && 3 != e || 3 == t && 4 != e && 5 != e || 4 == t && 6 != e && 7 != e) && $(arSalaryInp[e]).val("");
    $("#psa-salary-type").val(t);
  });
  // просмотреть все вакансии
  $('.more-posts').click(function(){ 
    $(this.parentNode).css({height:'initial'});
    $(this).remove();
  });
  // устанавливаем выбрть все/снять все для вакансий
  $('.filter-posts input').change(function(){
    var arCheckbox = $('.filter-posts input');
    if($(this).is(arCheckbox[0])){
      for (var i=1; i<arCheckbox.length; i++)
        $(this).is(':checked') 
        ? $(arCheckbox[i]).prop('checked',true) 
        : $(arCheckbox[i]).prop('checked',false);     
    }
    setTimeout(function(){ getVacanciesAjax() }, 300); 
  });
  // прячем фильтр для моб. разрешения
  $(window).on('load resize',function(){
    if($(window).width() < '751')
      $('.filter__vis').hasClass('active') ? $form.show() : $form.hide(); 
    else
      $form.show();
  });
  $('.filter__vis').click(function(){
    $(this).hasClass('active') ? $form.fadeOut() : $form.fadeIn();
    $(this).toggleClass('active');
  });
  // ввод города
  if(typeof arSelectCity!=='undefined')
    selectCities({'main':'#multyselect-cities', 'arCity':arSelectCity, 'inputName':'cities[]'});
  // вкладки фильтра
  $('.filter__item-name').click(function(){
    var $it = $(this);
    if($it.hasClass('opened')){
      $it.siblings('.filter__item-content').slideUp(200);
      setTimeout(function(){
        $it.removeClass('opened');
        $it.siblings('.filter__item-content').removeClass('opened');
      },200);
    }
    else{
      $it.addClass('opened');
      $it.siblings('.filter__item-content').slideDown(500);
      $it.siblings('.filter__item-content').addClass('opened');
    }
  });
  // подгрузка данных для пола, и дополнительно
  $('.filter-sex input, .filter-additional input').change(function(){ 
    setTimeout(function(){ getVacanciesAjax() }, 300); 
  });
  // подгрузка данных при перелистывании
  $('#promo-content').on('click', '.paging-wrapp a', function(e){ getVacanciesAjax(e) });
  // подгрузка данных для текстовых полей 
  $('#promo-filter .prmu-btn').click(function(){ getVacanciesAjax() });
  //  выбор работников
  $content.on('change', '.promo_inp', function(){
    var id = Number($(this).val());
    if($(this).is(':checked')){
      // записуем выбраный ID
      if($.inArray(id, arSelectIdies)<0){ arSelectIdies.push(id) };
      if(arSelectIdies.length == arIdies.length) 
        $('#all-workers').prop('checked',true);  
    }
    else{
      // убираем ID
      if($.inArray(id, arSelectIdies)>=0){ arSelectIdies.splice(arSelectIdies.indexOf(id),1) }
      $('#all-workers').prop('checked',false);
    }
    $('#mess-workers').val(arSelectIdies);
    $('#mess-wcount').html(arSelectIdies.length);
    $('#mess-wcount-inp').val(arSelectIdies.length);
    error = false;
    if(!checkCount(true)) error = true;
    error ? $('#workers-btn').addClass('off') : $('#workers-btn').removeClass('off');
  });
  //  выбрать всех
  $('#all-workers').change(function(){
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
    if(!checkCount(true)) error = true;
    error ? $('#workers-btn').addClass('off') : $('#workers-btn').removeClass('off');
  });
  // отправка формы
  $('#workers-form').submit(function(){
    if(!checkCount(true)) return false; 
    // передаем фильтр
    if($('#all-workers').is(':checked')){
      var filter = $('#promo-filter').serialize();
      $('#mess-filter').val(filter);
    }
  });
  //  выделение ошибки
  $('#all-workers').change(function(){
    $('#mess-wcount-inp').val()==0 ? $cntW.addClass('error') : $cntW.removeClass('error');
  });
  $content.on('change', '.promo_inp', function(){
    $('#mess-wcount-inp').val()==0 ? $cntW.addClass('error') : $cntW.removeClass('error');
  });
  //
  //
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
            if($.inArray(id,arSelectIdies)>=0 || $('#all-workers').is(':checked'))
              $(this).prop('checked',true);
          });
        }
        else{
          arSelectIdies = [];
          $('#mess-workers').val(arSelectIdies);     
          $('#mess-wcount').html(0);
          $('#mess-wcount-inp').val(0);
          $('#all-workers').prop('checked',false);
        }
        $load.hide();
      }
    });
  }
  //
  function selectCities(obj){
    var $main = $(obj.main).append('<ul class="cities-select"><li data-id="0"><input type="text" name="c"></li></ul><ul class="cities-list"></ul><b></b>'), // родитель
        $select = $main.find('ul').eq(0), // список ввода
        $input = $select.find('input'), // ввод города
        $list = $main.find('ul').eq(1), // список выбора
        $load = $main.find('b'), // тег загрузки
        bShowCityList = true, // флаг отображения списка городов
        cityTimer = false; // таймер обращения к серверу для поиска городов
       
    // добавляем уже выбранный город
    if(typeof obj.arCity!=='undefined'){
      var content = '';
      for (var i=0; n=obj.arCity.length, i<n; i++){
        content += '<li data-id="' + obj.arCity[i]['id'] + '">' + 
          obj.arCity[i]['name'] + '<i></i><input type="hidden" name="' + obj.inputName + '" value="' + obj.arCity[i]['id'] + '">' + 
          '</li>';
      }
      $select.prepend(content);
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
                    bShowCityList = false;
                    getVacanciesAjax();
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
        }
      },sec);
    });
    // Закрываем список
    $(document).on('click', function(e){
      if($(e.target).is('li') && $(e.target).closest($list).length && !$(e.target).hasClass('emp')){ // если кликнули по списку && если это не "Список пуст" && 
        $(e.target).remove();
        html =  '<li data-id="' + $(e.target).data('id') + '">' + $(e.target).text() + 
                  '<i></i><input type="hidden" name="' + obj.inputName + '" value="' + $(e.target).data('id') + '"/>' +
                '</li>';
        $select.find('[data-id="0"]').before(html);
        $list.fadeOut();
        getVacanciesAjax();
      }
      if($(e.target).is('i') && $(e.target).closest($select).length){ // удаление выбраного города из списка
        $(e.target).closest('li').remove();
        l = getSelectedCities($select).length;
        getVacanciesAjax();
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
  // делаем каждое слово в городе с большой
  function setFirstUpper(e){
    var split = $(e).val().split(' ');

    for(var i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    var split = $(e).val().split('-');
    for(var i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  }
})