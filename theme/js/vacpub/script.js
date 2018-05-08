$(function(){
  var 
    dateBeg = {'year': 0, 'month': 0, 'day': 0},
    dateEnd = {'year': 0, 'month': 0, 'day': 0},
    titleLen = 70,//  Заголовок не более 70и символов
    ageLen = 2,//  Возраст = 2 цифры
    ageMinLimit = 14, // Возраст от 14 лет
    curDate = new Date(),
    arSelect = ['expirience','paylims'];

  //  события поля заголовок
  $('#va-vac-title').on('keyup', function(){
    var val = $(this).val();
    if(val.length>titleLen) 
      $(this).val(val.substr(0,titleLen));
  });
  //  события полей select
  $(document).on('click', function(e){
    for(var i=0; i<arSelect.length; i++){
      if(e.target.id == 'av-'+arSelect[i]+'-veil')
        $('#av-'+arSelect[i]+'-list').fadeIn();
      else if($(e.target).is('#av-'+arSelect[i]+'-list i') || !$(e.target).closest('#av-'+arSelect[i]+'-list').length)
        $('#av-'+arSelect[i]+'-list').fadeOut();
    }
    // манипулируем календарями
    if(e.target.id=='av-cibdate-veil'){
      $(e.target).siblings('.fav__calendar').fadeIn();
      $('#av-ciedate').siblings('.fav__calendar').fadeOut();
    }
    else if(!$(e.target).closest('.fav__calendar').length)
      $('#av-cibdate').siblings('.fav__calendar').fadeOut();

    if(e.target.id=='av-ciedate-veil'){
      $(e.target).siblings('.fav__calendar').fadeIn();
      $('#av-cibdate').siblings('.fav__calendar').fadeOut();
    }
    else if(!$(e.target).closest('.fav__calendar').length)
      $('#av-ciedate').siblings('.fav__calendar').fadeOut();
  });
  // изменение типа опыта
  $('#av-expirience-list input').on('change',function(){ 
    $('#av-expirience').val($(this).data('name'))
      .siblings('ul').fadeOut();
    checkField($('#av-expirience'));
  });
  // изменение срока оплаты
  $('#av-paylims-list').on('change', 'input', function(){
    $('#av-paylims').val($(this).data('name'));
    $('#av-paylims-list').fadeOut();
    if($(this).val()=='164'){
      $('#av-custom-paylims').val($(this).data('name'));
    }
    checkField($('#av-paylims'));
  });
  //  добавление нового срока оплаты
  $('#add-new-term').click(function(){
    var newPay = $('#inp-new-term').val(),
        rand = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
    if(newPay!==''){
      html = '<li>' + 
          '<input type="radio" name="user-attribs[paylims]" value="164" id="paylims-' + rand + '" data-name="' + newPay + '" checked>' + 
          '<label for="paylims-' + rand + '"><table><td><p>' + newPay + '</p><td><b></b></table></label>' + 
        '</li>';
      $('.fav__exp-new').before(html)
        .children('#inp-new-term').val('');
      $('#av-paylims').val(newPay)
        .siblings('ul').fadeOut();
      $('#av-custom-paylims').val(newPay);
    }
  });
  $('#inp-new-term').on('input',function(){
    $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1).toLowerCase());
  });
  //  событие полей возраста
  $('#av-age-from, #av-age-to').on('keyup', function(){
    var val = $(this).val().replace(/\D+/g,'');
    if(val.length>ageLen) 
      val = val.substr(0,ageLen); // двузначного возраста достаточно
    $(this).val(val);
    checkField(this);
  });
  // событие ввода оплаты
  $('.fav__input-salary').on('input', function(){
    $(this).val($(this).val().replace(/\D+/g,''));
  });
  //
  //    Проверка полей
  //
  $('.fav__required').bind('keyup change', function(){ checkField(this) });
  //
  $('.fav__submit').click(function(e){
    e.preventDefault();
    var arElems = $('.fav__label-textarea .nicEdit-main'),
        errors = false;
    $('#av-requirements').val($(arElems[0]).html());
    $('#av-duties').val($(arElems[1]).html());
    $('#av-conditions').val($(arElems[2]).html());
    $('#av-pdate').val($('#av-ciedate').val());

    $.each($('.fav__required'), function(){ 
      if(!checkField(this))
        errors = true; 
    });

    if(!$('#av-posts-select [name="posts[]"]').length){
      addErr('.fav__select-posts');
      errors = true; 
    }

    var arErrors = $('.error');
    if(arErrors.length>0)
      $('html, body').animate({ scrollTop: $(arErrors[0]).offset().top-20 }, 1000);

    if(!errors)
      $("#F1vacancy").submit();
  });
  //
  //  editor
  //
  var requirements = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline', 'ol', 'ul'] });
  requirements.addInstance('av-requirements');
  requirements.setPanel('av-requirements-panel');

  var duties = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline', 'ol', 'ul'] });
  duties.addInstance('av-duties');
  duties.setPanel('av-duties-panel');

  var conditions = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline', 'ol', 'ul'] });
  conditions.addInstance('av-conditions');
  conditions.setPanel('av-conditions-panel');
  $('.nicEdit-main:first').addClass('fav__required');
  $('.nicEdit-main.fav__required').on('keyup', function(){ checkField(this) });
  //
  //
  //
  Calendar("calendar-begin", curDate.getFullYear(), curDate.getMonth());
  Calendar("calendar-end", curDate.getFullYear(), curDate.getMonth());
  //  выбор даты
  $(document).on('click', '.fav__calendar-table .day', function(){ checkDate(this) });
  //  переключаем месяцы
  $('.month-left').click(function(e){
    var idTable = $(this).closest('table').prop('id'),
      monthName = document.querySelector('#'+idTable+' .month-name');
    Calendar(idTable, monthName.dataset.year, parseFloat(monthName.dataset.month)-1);
  });
  $('.month-right').click(function(e){ 
    var idTable = $(this).closest('table').prop('id'),
      monthName = document.querySelector('#'+idTable+' .month-name');
    Calendar(idTable, monthName.dataset.year, parseFloat(monthName.dataset.month)+1);
  });
  //    calendar
  function Calendar(id, year, month){
    var Dlast = new Date(year,month+1,0).getDate(),
      D = new Date(year,month,Dlast),
      DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
      DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
      calendar = '<tr>',
      month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
      date = new Date();
    if(DNfirst != 0){
      for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
    }else{
      for(var  i = 0; i < 6; i++) calendar += '<td>';
    }
    for(var  i = 1; i <= Dlast; i++){
      newDate = new Date(D.getFullYear(),D.getMonth(),i);
      if(i==date.getDate() && D.getFullYear()==date.getFullYear() && D.getMonth()==date.getMonth()){ // today
        calendar += '<td class="day today">' + i;
      }
      else if(Math.ceil((newDate - date)/(1000 * 60 * 60 * 24))<=0){ // переводим милисекунды в дни
        calendar += '<td class="day past">' + i;
      }
      else{
        calendar += '<td class="day">' + i;
      }
      if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
        calendar += '<tr>';
      }
    }
    for(var i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
    document.querySelector('#'+id+' tbody').innerHTML = calendar;
    monthName = document.querySelector('#'+id+' .month-name');
    monthName.innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
    monthName.dataset.month = D.getMonth();
    monthName.dataset.year = D.getFullYear();
    if(document.querySelectorAll('#'+id+' tbody tr').length < 6){ // всегда 6 строк
      document.querySelector('#'+id+' tbody').innerHTML += '<tr><td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td>&nbsp;';
    }
  }
  //
  function checkDate(elem){
    var idTable = $(elem).closest('table').prop('id'),
      date = document.querySelector('#'+idTable+' .month-name'),
      newDate = new Date(date.dataset.year, date.dataset.month, Number($(elem).text()));
    dateBeg['year']!=0 ? begDate = new Date(dateBeg['year'],dateBeg['month'],dateBeg['day']) : begDate = 0;
    dateEnd['year']!=0 ? endDate = new Date(dateEnd['year'],dateEnd['month'],dateEnd['day']) : endDate = 0;

    if(idTable=="calendar-begin"){ // дата начала
      if(diffDate(newDate,curDate) >= 0){ // не прошедшая ли дата
        if(endDate){  // дата окончания уже есть
          diffDate(endDate,newDate) >= 0 // а вдруг позже даты окончания
          ? setDate(dateBeg, newDate, elem, 'begin')
          : $('#av-begin-err').show();
        }
        else{
          setDate(dateBeg, newDate, elem, 'begin');
          Calendar("calendar-end", parseFloat(newDate.getFullYear()), parseFloat(newDate.getMonth()));
        }
      }
      else{
        $('#av-begin-err').show();
      }
      if(endDate && diffDate(endDate,begDate) > 0)
        $('#av-end-err').hide();
      checkField('#av-cibdate');
    }
    if(idTable=="calendar-end"){  // дата окончания
      if(diffDate(newDate,curDate) >= 0){ // не прошедшая ли дата
        if(begDate){  // дата начала уже есть
          diffDate(newDate,begDate) >= 0 // а вдруг раньше даты начала
          ? setDate(dateEnd, newDate, elem, 'end')
          : $('#av-end-err').show();
        }
        else{
          setDate(dateEnd, newDate, elem, 'end');
          Calendar("calendar-begin", parseFloat(newDate.getFullYear()), parseFloat(newDate.getMonth()));
        }
      }
      else{
        $('#av-end-err').show();
      }
      if(begDate && diffDate(begDate,curDate) >= 0)
        $('#av-begin-err').hide();
      checkField('#av-ciedate');
    }
  }
  //
  function diffDate(date1, date2){
    miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
    return Math.ceil((date1 - date2) / miliToDay);
  }
  //
  function setDate(obj, date, e, type){
    obj['year'] = Number(date.getFullYear());
    obj['month'] = Number(date.getMonth());
    obj['day'] = Number(date.getDate());
    str = ('0' + obj['day']).slice(-2) + '.' + ('0' + (obj['month'] + 1)).slice(-2) + '.' + obj['year'];
    var calendar = $(e).closest('.fav__calendar');
    $(calendar).siblings('input').val(str);
    $(calendar).fadeOut();
    $('#av-'+type+'-err').hide();
    arDays = $('#calendar-'+type+' .day');
    $.each(arDays, function(){ $(this).removeClass('select') });
    $(e).addClass('select');
  }
  //
  function checkField(e){
    var val = $(e).val(),
      id = $(e).prop('id'),
      res = false;
    if(id=='av-sex-man' || id=='av-sex-woman'){
      if(!$('#av-sex-man').is(':checked') && !$('#av-sex-woman').is(':checked')){       
        res = addErr('[for=av-sex-man]');
        addErr('[for=av-sex-woman]');    
      }
      else{
        res = remErr('[for=av-sex-man]');
        remErr('[for=av-sex-woman]');
      }
    }
    else if(id=='av-age-from' || id=='av-age-to'){
      var $from = $('#av-age-from'), 
          $to = $('#av-age-to'),
          $parent = $from.closest('.fav__label');
          ageFrom = $from.val(), 
          ageTo = $to.val();

      if(ageFrom==''){
        res = addErr($from);
      }
      else if(ageFrom<ageMinLimit){
        res = addErr($from);
        addErr($parent);
      }
      else if(ageTo==''){
        res = remErr($from);
        remErr($to);
        remErr($parent);
      }
      else if(ageFrom>ageTo){
          res = addErr($from);
          addErr($to);
          addErr($parent);
      }
      else{
        res = remErr($from);
        remErr($to);
        remErr($parent);
      }
    }
    else if($(e).hasClass('fav__input-salary')){
      var arSalary = $('.fav__input-salary'), cntEmp = 0;
      $.each(arSalary, function(){ if($(this).val()==''){ cntEmp++ }});
      cntEmp==4 // 4 поля ввода
      ? $.each(arSalary, function(){ res=addErr(this) })
      : $.each(arSalary, function(){ res=remErr(this) });
    }
    else if(id=='av-city-name'){
      var arOptions = $(e).find('option:selected');
      res = (
        arOptions.length>0 
        ? remErr('.fav__label .select2-container') 
        : addErr('.fav__label .select2-container') 
      );
    }
    else if($(e).hasClass('nicEdit-main')){
      res = ($(e).text().length>0 ? remErr(e) : addErr(e));
    }
    else{
      res = ((val=='' || val==null) ? addErr(e) : remErr(e));     
    }
    return res;
  }
  function addErr(e){ 
    $(e).addClass('error');
    return false;
  }
  function remErr(e){ 
    $(e).removeClass('error');
    return true;
  }
  /*
  *
  */  // новый ввод вакансий
  $('#av-posts-select').click(function(e){ 
    if(!$(e.target).is('i')){
      $('#av-posts-list').fadeIn();
      $('#av-posts-list input').focus();   
    }
  });
  $('#av-posts-list input').bind('input focus blur', function(e){
    var arResult = [],
        content = '',
        $pList = $('#av-posts-list'),
        placeholder = $('#av-posts-select').siblings('span'),
        val = $(this).val().charAt(0).toUpperCase() + $(this).val().slice(1).toLowerCase(),
        piece = $(this).val().toLowerCase(),
        showList = true;

    arSelectId = getSelectedPosts();
    $(this).val(val);

    if(arSelectId.length) $(placeholder).hide(); 
    if(e.type!=='blur'){
      if(val===''){
        $.each(arPosts, function(){ // список вакансий если ничего не введено
          if($.inArray(this.id, arSelectId)<0)
            content += '<li data-id="' + this.id + '">' + this.name + '</li>';
        });
      }
      else{
        $.each(arPosts, function(){ // список вакансий если что-то введено
          word = this.name.toLowerCase();
          if(word===piece){ // если введена именно вакансия полностью
            addSelectedPosts(this.id, this.name);
            showList = false;
          }
          else if(word.indexOf(piece)>=0 && $.inArray(this.id, arSelectId)<0)
            arResult.push( {'id':this.id, 'name':this.name} );
        });
        arResult.length>0
        ? $.each(arResult, function(){ content += '<li data-id="' + this.id + '">' + this.name + '</li>' })         
        : content = '<li class="emp">Список пуст</li>';
      }
      arList = $pList.find('li');
      for(var i=0; i<arList.length; i++){
        if(arList[i].dataset.id!='0')
          $(arList[i]).remove();
      }
      $pList.append(content);
      if(showList){
        $pList.fadeIn();
      }
      else{
        $pList.fadeOut();
        $(this).val('');
      }
    }
    else{ // если потерян фокус раньше времени
      $(this).val('');
      len = getSelectedPosts().length;
      len ? $(placeholder).hide() : $(placeholder).show();
      len ? remErr('.fav__select-posts') : addErr('.fav__select-posts');
    }
  });
  $(document).on('click', function(e){  // Закрываем список
    var sList = '#av-posts-select',
        pList = '#av-posts-list';

    if($(e.target).is(pList+' li') && !$(e.target).hasClass('emp')){ // если кликнули по списку && если это не "Список пуст" && 
      $(e.target).remove();
      $(sList).siblings('span').hide();
      addSelectedPosts($(e.target).data('id'), $(e.target).text());
      $(pList).fadeOut();
    }
    if($(e.target).is(sList+' i')){ // удаление выбраной должности из списка
      $(e.target).closest('li').remove();
      len = getSelectedPosts().length;
      len ? $('#av-posts-select').siblings('span').hide() : $('#av-posts-select').siblings('span').show();
      len ? remErr('.fav__select-posts') : addErr('.fav__select-posts');
    }
    if(!$(e.target).is(sList) && !$(e.target).closest(sList).length && !$(e.target).is(pList+' input'))
      $(pList).fadeOut();
  });
  /*
  *
  */ // ввод нового города
  selectCities({
    'main' : '#multyselect-cities', 
    'arCity' : arSelectCity,
    'span' : 'Город *',
    'inputName' : 'idcity[]'
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
    if(typeof obj.arCity!=='undefined'){
      content = '<li data-id="' + obj.arCity.id + '">' + 
                  obj.arCity.name + '<i></i><input type="hidden" name="' + obj.inputName + '" value="' + obj.arCity.id + '">' + 
                '</li>';
      $select.prepend(content);
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
  //  добавляем выбранную должность
  function addSelectedPosts(id,name){ 
    html = '<li data-id="' + id + '">' + name + '<i></i><input type="hidden" name="posts[]" value="' + id + '"/></li>';
    $('#av-posts-select').append(html);
    remErr('.fav__select-posts'); 
  }
  //  считаем выбранные должности
  function getSelectedPosts(){
    var arId = [];
    $.each($('#av-posts-select li'), function(){ arId.push(String($(this).data('id'))) });
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
});