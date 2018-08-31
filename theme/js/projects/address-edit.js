'use strict'
var AddressEdit = (function () {
	AddressEdit.prototype.arIdCities = [];

	function AddressEdit() { this.init() }

	AddressEdit.prototype.init = function () {
		let self = this,
			arCalendars = document.querySelectorAll('.calendar');

    $('#save-index').click( function(){ self.saveProgram(this) });
    $('#index').on('click', '.add-loc-btn', function(){ self.addLocation(this) });
    $('#index').on('click', '.add-period-btn', function() { self.addPeriod(this) });
    $('#index').on(
      'click',
      '.city-del,.loc-del,.period-del',
      function(){ self.removeElement(this) }
    );
    // работа с городами
    $('#index').on('input', '.city-inp', function() { self.inputCity(this) });
    $('#index').on('focus', '.city-inp', function() { self.focusCity(this) });
    $('#add-city-btn').click(function(){ self.addCity() });
    // работаем с метро
    $('#index').on('input','.metro-inp',function(){ self.inputMetros(this) });
    $('#index').on('focus','.metro-inp',function(){ self.focusMetro(this) });
    // работа с датами
    for (let i=0; i<arCalendars.length; i++)
      self.buildCalendar(arCalendars[i]);
    $('#index').on('click', '.period-item span', function() { self.showCalendar(this) });
    $('#index').on('click', '.mleft', function(){ self.changeMonth(this,-1) });
    $('#index').on('click', '.mright', function(){ self.changeMonth(this,1) });
    $('#index').on('click', '.calendar .day', function(e){ self.checkDate(e.target) });
    // работа с временем
    $('.time-inp').mask('99:99');
    $('#index').on('blur', '.time-inp', function() { self.checkTime(this) });
    // обрабатываем клики
    $(document).on('click', function(e) {
      self.checkCity(e.target);
      self.checkMetro(e.target);
      self.closureCalendar(e.target);
    });

    self.scrollToBlock();
	}
	//
	//      ГОРОДА
	//
	//      добавление города
  AddressEdit.prototype.addCity = function () {
    let self = this,
      arCities = $('#index .city-item'),
      content = $('#city-content').html(),
      empty = self.checkFields(),
      arIdies = self.getNewId(),
      arTime;

    if (!empty) {
      $(arCities[arCities.length-1]).after(content);
      content = $('#index .city-item:eq(-1)');
      $(content).append($('#loc-content').html());
      content = $(content).find('.loc-item:eq(-1)')[0];
      content.dataset.id = arIdies.location;
      $(content).append($('#period-content').html());
      content = $(content).find('.period-item')[0];
      content.dataset.id = arIdies.period;
      arTime = $(content).find('.time-inp');
      $(arTime).mask('99:99');
    }
    else
      MainProject.showPopup('notif', 'add-city');
  }
  //      ввод города
  AddressEdit.prototype.inputCity = function (e) {
    let self = this,
      val = $(e).val();

    clearTimeout(MainProject.bAjaxTimer);
    self.setFirstUpper(e);

    MainProject.bAjaxTimer = setTimeout(function(){ self.getAjaxCities(val, e) },1000);
  }
  //      фокус поля города
  AddressEdit.prototype.focusCity = function (e) {
    let self = this,
      val = $(e).val();
    $(e).val('').val(val);
    self.setFirstUpper(e);
    self.getAjaxCities(val, e);
  };
  //      запрос списка городов
  AddressEdit.prototype.getAjaxCities = function (val, e) {
    let self = this,
      $e = $(e),
      list = $e.siblings('.select-list')[0],
      main = $e.closest('.city-field')[0],
      mainCity = $e.closest('.city-item')[0],
      idcity = Number(mainCity.dataset.city),
      piece = val.toLowerCase(),
      content = '',
      params = 'query=' + val + '&idco=' + MainProject.idCo;

    self.getSelectedCities();
    $(main).addClass('load'); // загрузка началась

    $.ajax({
      type: 'POST',
      url: MainConfig.AJAX_GET_VE_GET_CITIES,
      data: params,
      dataType: 'json',
      success: function(r) {
        for (let i in r.suggestions) {
          let item = r.suggestions[i],
            id = +item.data;

          if(isNaN(item.data))
            break;

          if(
            ( $.inArray(id, self.arIdCities)<0 || id==idcity )
             &&
            item.value.toLowerCase().indexOf(piece) >= 0
          ){ // собираем список
            content += '<li data-id="'
              + item.data + '" data-metro="' + item.ismetro
              + '">' + item.value + '</li>';
          }
        }
        content
        ? $(list).html(content).fadeIn()
        : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
        $(main).removeClass('load'); // загрузка завершена
      }
    });
  }
  //      фокус инпута и выбор города
  AddressEdit.prototype.checkCity = function (e) {
    let self = this,
      $e = $(e),
      data = e.dataset,
      arCities = $('#index .city-field');

   	if( !$e.closest('.city-field').length && !$e.is('.city-field') ) {
      for(let i=0; i<arCities.length; i++){ // закрываем списки без фокуса
        let cSelect = $(arCities[i]).find('.city-select'),
          cInput = $(arCities[i]).find('.city-inp'),
          cList = $(arCities[i]).find('.select-list'),
          v = $(cSelect).text();

        cSelect.text()==='' ? cSelect.hide() : cSelect.show();
        cInput.val(v).hide();
        cList.fadeOut();
      }
    }
    else{ // клик по объектам списка
      if( $e.is('li') && !$e.hasClass('emp') ) { // выбираем из списка
        let main = $e.closest('.city-item')[0],
          select = $(main).find('.city-select'),
          inpText = $(main).find('.city-inp'),
          list = $(main).find('.select-list'),
          input = $(inpText).siblings('[type="hidden"]');

        if(main.dataset.city!=='' && main.dataset.city===data.id) {
          let v = select.text();
          inpText.val(v).hide();
          select.show();
        }
        else { // ввод нового города
          let v = $(e).text();

          main.dataset.city = data.id;
          input.val(data.id);
          inpText.val(v).hide();
          select.html(v+'<b></b>').show();

          if(data.metro==='1' && !$(main).find('.metro-item').length) {
            let mContent = $('#metro-content').html(),
              arRows = $(main).find('.loc-item .project__index-row');

            for (var i = 0, n = arRows.length; i < n; i++) {
              $(arRows[i]).prepend(mContent);
              /*let loc = $(arRows[i]).closest('.loc-item')[0],
                  inp = $(arRows[i]).find('input')[0],
                  name = '[' + data.id + '][' + loc.dataset.id + ']';
              $(inp).attr('name','metro' + name);*/
            }
          }
          else if($(main).find('.metro-item').length) {
            $(main).find('.metro-item').remove();
          }
          //
          let arLocs = $(main).find('.loc-item'),
            arPers = $(main).find('.period-item'),
            name = '';

          for (let i = 0, n = arLocs.length; i < n; i++) {
            let idL = arLocs[i].dataset.id,
              arLocInp = $(arLocs[i]).find('.loc-field input');

            name = '[' + data.id + '][' + idL + ']';
            if(data.metro==='1') {
              $(arLocInp[1]).attr('name','metro' + name);
              $(arLocInp[2]).attr('name','lindex' + name);
              $(arLocInp[3]).attr('name','lname' + name);
            }
            else {
              $(arLocInp[0]).attr('name','lindex' + name);
              $(arLocInp[1]).attr('name','lname' + name);
            }

            for (let i = 0, n = arPers.length; i < n; i++) {
              let idP = arPers[i].dataset.id,
                arPerInp = $(arPers[i]).find('input');

              name += '[' + idP + ']';
              $(arPerInp[0]).attr('name','bdate' + name);
              $(arPerInp[1]).attr('name','edate' + name);
              $(arPerInp[2]).attr('name','btime' + name);
              $(arPerInp[3]).attr('name','etime' + name);
            }
          }

        }
        list.fadeOut();
      }
      else{
        let main = $e.is('.city-field') ? e : $e.closest('.city-field')[0];
        for(let i=0; i<arCities.length; i++) { // закрываем списки без фокуса
          let cSelect = $(arCities[i]).find('.city-select'),
              cInput = $(arCities[i]).find('.city-inp'),
              cList = $(arCities[i]).find('.select-list'),
              v = $(cSelect).text();

          if( !$(arCities[i]).is(main) ) {
              cSelect.show();
              cInput.val(v).hide();
              cList.fadeOut();
          }
          else{
              if( $e.is('b') )
                  cInput.val('');
              cInput.show().focus();
              cSelect.hide();
          }
        }
      }
    }
  }
  //      правильный ввод названия города
  AddressEdit.prototype.setFirstUpper = function (e) {
    let split = $(e).val().split(' ');

    for(let i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    split = $(e).val().split('-');
    for(let i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  }
  //       получить выбранные города
  AddressEdit.prototype.getSelectedCities = function () {
    let self = this;

    self.arIdCities = [];

    $.each($('#index .city-item'), function(){
      if(this.dataset.city!=='')
        self.arIdCities.push(Number(this.dataset.city));
    });
  }
  //
  //      МЕТРО
  //
  //      Ввод метро
  AddressEdit.prototype.inputMetros = function (e) {
      let self = this,
          val = $(e).val();

      clearTimeout(MainProject.bAjaxTimer);
      MainProject.bAjaxTimer = setTimeout(function(){ self.getAjaxMetros(val, e) },1000);
  }
  //      фокус поля метро
  AddressEdit.prototype.focusMetro = function (e) {
      let self = this,
          val = $(e).val();
      $(e).val('').val(val);
      self.getAjaxMetros(val, e);
  };
  //      запрос списка метро
  AddressEdit.prototype.getAjaxMetros = function (val, e) {
      let self = this,
          $e = $(e),
          main = $e.closest('.metro-field')[0],
          mainCity = $e.closest('.city-item')[0],
          list = $(main).find('.select-list')[0],
          input = $(main).find('[type="hidden"]')[0],
          idcity = Number(mainCity.dataset.city),
          params = 'id=' + idcity + '&query=' + val + '&select=',
          content = '';

      $(main).addClass('load'); // загрузка началась

      $.ajax({
          type: 'POST',
          url: '/ajaxvacedit/vegetmetros/',
          data: params,
          dataType: 'json',
          success: function(metros) {
              if(metros.error!==true)
                  for (let i in metros)
                      content += '<li data-id="' + metros[i].id + '">'
                          + metros[i].name + '</li>';

              content
              ? $(list).html(content).fadeIn()
              : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
              $(main).removeClass('load'); // загрузка завершена
          }
      });
  }
  //      Установка метро
  AddressEdit.prototype.checkMetro = function (e) {
      let self = this,
          $e = $(e),
          arMetros = $('#index .metro-field');

      if( !$e.closest('.metro-field').length && !$e.is('.metro-field') ) {
          for(let i=0; i<arMetros.length; i++){ // закрываем списки без фокуса
              let cSelect = $(arMetros[i]).find('.metro-select'),
                  cInput = $(arMetros[i]).find('.metro-inp'),
                  cList = $(arMetros[i]).find('.select-list'),
                  v = $(cSelect).text();

              cSelect.text()==='' ? cSelect.hide() : cSelect.show();
              cInput.val(v).hide();
              cList.fadeOut();
          }
      }
      else{ // клик по объектам списка
          if( $e.is('li') && !$e.hasClass('emp') ) { // выбираем из списка
              let main = $e.closest('.metro-item')[0],
                  select = $(main).find('.metro-select'),
                  inpText = $(main).find('.metro-inp'),
                  list = $(main).find('.select-list'),
                  input = $(inpText).siblings('[type="hidden"]'),
                  v = $(input).val();

              if(v!=='' && v===e.dataset.id) {
                  let v = select.text();
                  inpText.val(v).hide();
                  select.show();
              }
              else { // ввод нового города
                  let v = $(e).text();

                  input.val(e.dataset.id);
                  inpText.val(v).hide();
                  select.html(v+'<b></b>').show();
              }
              list.fadeOut();
          }
          else{
              let main = $e.is('.metro-field') ? e : $e.closest('.metro-field')[0];
              for(let i=0; i<arMetros.length; i++) { // закрываем списки без фокуса
                  let cSelect = $(arMetros[i]).find('.metro-select'),
                      cInput = $(arMetros[i]).find('.metro-inp'),
                      cList = $(arMetros[i]).find('.select-list'),
                      v = $(cSelect).text();

                  if( !$(arMetros[i]).is(main) ) {
                      cSelect.show();
                      cInput.val(v).hide();
                      cList.fadeOut();
                  }
                  else{
                      if( $e.is('b') )
                          cInput.val('');
                      cInput.show().focus();
                      cSelect.hide();
                  }
              }
          }
      }
  }
  //
  //      ЛОКАЦИИ
  //
  //      добавление локации
  AddressEdit.prototype.addLocation = function (e) {
    let self = this,
      main = $(e).closest('.city-item')[0],
      idC = main.dataset.city,
      arLoc = $(main).find('.loc-item'),
      newLoc = $('#loc-content').html(),
      newPeriod = $('#period-content').html(),
      empty = self.checkFields(),
      arIdies = self.getNewId(),
      arLocInp, arPerInp, row, arTime;

    if (!empty) {
      $(main).append(newLoc);
      arLoc = $(main).find('.loc-item')
      newLoc = arLoc[arLoc.length-1];
      newLoc.dataset.id = arIdies.location;
      name = '[' + idC + '][' + arIdies.location + ']';

      if($(main).find('.metro-item').length) {// если есть метро
        row = $(newLoc).find('.loc-field');
        $(row).prepend($('#metro-content').html());
        arLocInp = $(newLoc).find('.loc-field input');
        $(arLocInp[1]).attr('name','metro' + name);
        $(arLocInp[2]).attr('name','lindex' + name);
        $(arLocInp[3]).attr('name','lname' + name);
      }
      else {
        arLocInp = $(newLoc).find('.loc-field input');
        $(arLocInp[0]).attr('name','lindex' + name);
        $(arLocInp[1]).attr('name','lname' + name);
      }
      $(newLoc).append(newPeriod);
      arPerInp = $(newLoc).find('.period-item input');

      name += '[' + arIdies.period + ']';
      $(arPerInp[0]).attr('name','bdate' + name);
      $(arPerInp[1]).attr('name','edate' + name);
      $(arPerInp[2]).attr('name','btime' + name);
      $(arPerInp[3]).attr('name','etime' + name);

      arTime = $(newLoc).find('.time-inp');
      $(arTime).mask('99:99');
    }
    else
      MainProject.showPopup('notif', 'add-tt');
  }
    //
    //      ДАТА
    //
    //      Создание календарей
    AddressEdit.prototype.buildCalendar = function (item, year, month) {
        year = (typeof year=="undefined" ? new Date().getFullYear() : year);
        month = (typeof month=="undefined" ? new Date().getMonth() : month);

        let self = this,
            Dlast = new Date(year,month+1,0).getDate(),
            D = new Date(year,month,Dlast),
            DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
            DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
            content = '<tr>',
            arMonth = [
                "Январь","Февраль","Март","Апрель",
                "Май","Июнь","Июль","Август",
                "Сентябрь","Октябрь","Ноябрь","Декабрь"
            ],
            date = new Date(),
            main = $(item).closest('.period-item')[0],
            parent = $(item).closest('.period-field')[0],
            arCalendars = $(main).find('.period-field'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            type,body,mName,nDays,newDate,res;

        date.setHours(0, 0, 0, 0);

        if(begDate!=='')
            begDate = self.getDateFromData(begDate);
        if(endDate!=='')
            endDate = self.getDateFromData(endDate);

        for( let i=0, n=arCalendars.length; i<n; i++ )
            if( $(arCalendars[i]).is(parent) )
                type = i;

        if(DNfirst != 0) {
            for(let i = 1; i < DNfirst; i++) content += '<td>';
        } else {
            for(let i = 0; i < 6; i++) content += '<td>';
        }
        for(let i = 1; i <= Dlast; i++) {
            content += '<td class="day';
            newDate = new Date(D.getFullYear(),D.getMonth(),i);
            newDate.setHours(0, 0, 0, 0);
            res = self.diffDate(newDate,date);

            if(res==0)
                content += ' today'; // today
            else if(res<0)
                content += ' nofit'; // прошедшее
            if(type==0 && endDate>0 && self.diffDate(newDate,endDate)>0)
                content += ' nofit'; // обозначаем недоступные дни
            else if(type==1 && begDate>0 && self.diffDate(newDate,begDate)<0)
                content += ' nofit'; // обозначаем недоступные дни
            else if(endDate==='' && self.diffDate(newDate,begDate)==0)
                content += ' select'; // обозначаем выделенные дни
            else if(begDate==='' && self.diffDate(newDate,endDate)==0)
                content += ' select'; // обозначаем выделенные дни
            else if(
                endDate>0 && begDate>0
                &&
                self.diffDate(newDate,begDate)>=0
                &&
                self.diffDate(newDate,endDate)<=0
            )
                content += ' select'; // обозначаем выделенные дни

            content += '">' + i;
            if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0)
                content += '<tr>';
        }
        for(let i = DNlast; i < 7; i++) content += '<td>&nbsp;';

        body = item.querySelector('tbody');
        body.innerHTML = content;
        mName = item.querySelector('.mname');
        mName.innerHTML = arMonth[D.getMonth()] +' '+ D.getFullYear();
        mName.dataset.month = D.getMonth();
        mName.dataset.year = D.getFullYear();

        nDays = item.querySelectorAll('tbody tr');
        if(nDays.length < 6) { // всегда 6 строк
            content = '<tr>';
            for(let i=0; i<7; i++)
                content += '<td class="empty">&nbsp;';
            body.innerHTML += content;
        }
    }
    //      Проверка даты
    AddressEdit.prototype.checkDate = function (day) {
        let self = this,
            $it = $(day),
            main = $it.closest('.period-item')[0],
            arCalendars = $(main).find('.period-field'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            parent = $it.closest('.period-field')[0],
            data = $(parent).find('.mname')[0].dataset,
            calendar = $(parent).find('.calendar')[0],
            output = $(parent).find('span')[0],
            input = $(parent).find('input')[0],
            d = Number($(day).text()),
            m = Number(data.month),
            y = Number(data.year),
            newDate = new Date(y, m, d),
            res;

        if( $(day).hasClass('empty')  || $(day).hasClass('nofit') )
            return false;

        $(calendar).fadeOut();
        res = ('0' + Number($(day).text())).slice(-2) + '.'
            + ('0' + (Number(data.month) + 1)).slice(-2)
            + '.' + data.year;

        $(output).text(res);
        $(input).val(res);
        self.buildCalendar(arCalendars[0], y, m);
        self.buildCalendar(arCalendars[1], y, m);
    }
    //      Определение разницы во времени
    AddressEdit.prototype.diffDate = function (date1, date2) {
        let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
    }
    //      форматируем в формат даты
    AddressEdit.prototype.getDateFromData = function (date) {
        let arDate = date.split('.'),
            obj = new Date(
                Number(arDate[2]),
                Number(arDate[1]-1),
                Number(arDate[0])
            );
        return obj.setHours(0,0,0,0);
    }
    //      Изменение месяца
    AddressEdit.prototype.changeMonth = function (e, m) {
        let self = this,
            calendar = $(e).closest('.calendar')[0],
            data = $(e).siblings('.mname')[0].dataset,
            newMonth = parseFloat(data.month)+m;

        self.buildCalendar(calendar, data.year, newMonth);
    }
    //      Вывод календаря
    AddressEdit.prototype.showCalendar = function (e) {
        let calendar = e.nextElementSibling;
        $(calendar).fadeIn();
    }
    //      Закрытие календаря
    AddressEdit.prototype.closureCalendar = function (e) {
        let arCalendars = $('#index .calendar');

        for(let i=0, n=arCalendars.length; i<n; i++) {
            if($('.calendar').is(e) && !$(arCalendars[i]).is(e)) { // это точно календарь
                $(arCalendars[i]).fadeOut();
            }
            else if($(e).closest('.calendar').length) { // это составные календаря
                let calendar = $(e).closest('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else if($('.period-item span').is(e)) { // это поле даты
                let calendar = $(e).siblings('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else // это что-то другое
                $(arCalendars[i]).fadeOut();
        }
    }
    //
    //      Время
    //
    //      Добавление периода
    AddressEdit.prototype.addPeriod = function (e) {
        let self = this,
            empty = self.checkFields(),
            main = $(e).closest('.loc-item')[0],
            idL = main.dataset.id,
            city = $(e).closest('.city-item')[0],
            idC = city.dataset.city,
            newPeriod = $('#period-content').html(),
            arIdies = self.getNewId(),
            arPerInp, arPers, name;

        if(!empty) {
            $(main).append(newPeriod);
            arPers = $(main).find('.period-item');
            arPers[arPers.length-1].dataset.id = arIdies.period;
            arPerInp = $(arPers[arPers.length-1]).find('input');
            name = '[' + idC + '][' + idL + '][' + arIdies.period + ']';
            $(arPerInp[0]).attr('name','bdate' + name);
            $(arPerInp[1]).attr('name','edate' + name);
            $(arPerInp[2]).attr('name','btime' + name);
            $(arPerInp[3]).attr('name','etime' + name);
            $(main).find('.time-inp').mask('99:99');
        }
        else
            MainProject.showPopup('notif', 'add-period');
    }
    //      Проверка времени
    AddressEdit.prototype.checkTime = function (e) {
        let self = this,
            $e = $(e),
            main = $e.closest('.period-item'),
            arTimes = $(main).find('.time-item input'),
            arT1 = $(arTimes[0]).val().split(':'),
            arT2 = $(arTimes[1]).val().split(':'),
            t = $(arTimes[0]).is(e) ? 0 : 1;

        if(arT1.length==2) {
            arT1[0] = Number(arT1[0])>23 ? '23' : arT1[0];
            arT1[1] = Number(arT1[1])>59 ? '59' : arT1[1];
            self.setTime(arTimes[0], arT1);
        }
        if(arT2.length==2) {
            arT2[0] = Number(arT2[0])>23 ? '23' : arT2[0];
            arT2[1] = Number(arT2[1])>59 ? '59' : arT2[1];
            self.setTime(arTimes[1], arT2);
        }

        if(arT1.length!=2 || arT2.length!=2)
            return false;

        arT1[0] = Number(arT1[0]);
        arT1[1] = Number(arT1[1]);
        arT2[0] = Number(arT2[0]);
        arT2[1] = Number(arT2[1]);

        if(isNaN(arT1[0]) || isNaN(arT1[1]) || isNaN(arT2[0]) || isNaN(arT2[1]))
            return false;

        if(
            (arT1[0] > arT2[0])
            ||
            ( (arT1[0] == arT2[0]) && (arT1[1] > arT2[1]) )
            ||
            ( (arT1[0] == arT2[0]) && (arT1[1] == arT2[1]) )
        ) {
            MainProject.showPopup('error', 'time');
            $(arTimes[t]).val('');
        }
    }
    //      Установка времени
    AddressEdit.prototype.setTime = function (e, arT) {
        arT[0] = ('0' + arT[0]).slice(-2);
        arT[1] = ('0' + arT[1]).slice(-2);
        $(e).val(arT[0] + ':' + arT[1]);
    }
    //      Проверка заполненности полей
    AddressEdit.prototype.checkFields = function () {
        let arr = $('#index .city-item'),
            empty = false;

        for (let i = 0, l = arr.length; i < l; i++) {
            let arInputs = $(arr[i]).find('input');

            for (let j = 0, n = arInputs.length; j < n; j++) {
                let name = $(arInputs[j]).attr('name');
                if ($.inArray(name, ['c','m'])<0 && !arInputs[j].value.length)
                  empty = true;
            }
        }
        return empty;
    }
    //      удаление элементов
    AddressEdit.prototype.removeElement = function (e) {
      let self = this,
          $e = $(e),
          error = -1,
          query = true,
          arErr = ['city-del','loc-del','period-del'],
          arItems, item, main;

      if($e.hasClass('city-del')) {
          arItems = $('#index .city-item');
          item = $e.closest('.city-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будет удален город и все связанные данные.\n'
              +'Вы действительно хотите это сделать?');
          }
          else error = 0;
      }
      else if($e.hasClass('loc-del')) {
          main = $e.closest('.city-item')[0]
          arItems = $(main).find('.loc-item');
          item = $e.closest('.loc-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будет удалена ТТ и все связанные данные.\n'
              +'Вы действительно хотите это сделать?');
          }
          else error = 1;
      }
      else if($e.hasClass('period-del')) {
          main = $e.closest('.loc-item')[0]
          arItems = $(main).find('.period-item');
          item = $e.closest('.period-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будет удален период.\n'
              +'Вы действительно хотите это сделать?');
          }
          else error = 2;
      }

      if(!query)
        return false;
      if(error>=0)
          MainProject.showPopup('error',arErr[error]);
      else {
          $(item).fadeOut();
          setTimeout(function(){ $(item).remove() },500);
      }
    }
    // сохранение программы
    AddressEdit.prototype.saveProgram = function (e) {
        let self = this;

        !self.checkFields()
        ? $('#new-project').submit()
        : MainProject.showPopup('notif','save-program');
    }
  //
 	AddressEdit.prototype.scrollToBlock = function (e) {
    let scrollElem;
    if(getParams.city==='new') {
    	$('#add-city-btn').click();
    	scrollElem = $('#index .city-item:eq(-1)');
    }
    else {
    	scrollElem = $('#index [data-city='+getParams.city+']');
    }
    if(undefined!=getParams.loc) {
    	if(getParams.loc==='new') {
    		$('[data-city='+getParams.city+'] .add-loc-btn').click();
    		scrollElem = $('[data-city='+getParams.city+'] .loc-item:eq(-1)');
    	}
    	else {
    		scrollElem = $('[data-city='+getParams.city+'] .loc-item[data-id='+getParams.loc+']');
    	}
    }
    if(undefined!=getParams.per) {
      scrollElem = $('[data-city='+getParams.city+'] .period-item[data-id='+getParams.per+']');
    }
		if($(scrollElem).is('*'))
    	$('html, body').animate({ scrollTop: scrollElem.offset().top - 25 },1000);
 	}
  //    получаем уникальные ID
  AddressEdit.prototype.getNewId = function () {
    let arR = [], arT = [], r = (9999 - 1000 + 1);

    $.each($('#index .loc-item'), function(){
      arT.push(this.dataset.id);  
    });
    do {
      arR.location = Math.floor(Math.random() * r) + 1000;
    } while ($.inArray(arR.location,arT)>=0);

    arT = [];

    $.each($('#index .period-item'), function(){
      arT.push(this.dataset.id);  
    });
    do {
      arR.period = Math.floor(Math.random() * r) + 1000;
    } while ($.inArray(arR.period,arT)>=0);
    return arR;
  }
	//
  return AddressEdit;
}());
/*
*
*/
$(document).ready(function () {
	new AddressEdit();
});