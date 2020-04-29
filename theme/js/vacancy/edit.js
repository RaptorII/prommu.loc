'use strict';
/**
 *  main
 */
var EditVacancy = (function () {
  //
  function EditVacancy()
  {
    this.init();
  }
  //
  EditVacancy.prototype.init = function ()
  {
    let self = this;

    if(arguments.length) // инициализация после аякс запроса
    {
      let module = $(arguments[0]).closest('.vacancy__module'),
          moduleNum = $(arguments[0]).find('[name="module"]').val();

      if(moduleNum==='2' || moduleNum==='4') // Для 2го и 4го блока нужно обновлять все блоки
      {
        $('#edit_vacancy').replaceWith(arguments[1]);
      }
      else
      {
        $(module).html(arguments[1]);
      }
    }

    $.each($('.tooltip'),function(){
      if(!$(this).hasClass('tooltipstered'))
      {
        $(this).tooltipster({
          contentAsHTML:true,
          side:'right',
          theme:['tooltipster-noir', 'tooltipster-noir-customized']
        });
      }
    });
    // переключение на форму редактирования
    $('.vacancy__module').on('click','.personal__area--capacity-edit',function(){
      let main = $(this).closest('.vacancy__module'),
          info = $(main).find('.module_info'),
          form = $(main).find('.module_form');

      $(info).hide();
      $(form).show();
      self.changeLabelWidth();
      self.changeModuleWidth();
    });
    // переключаем обратно на информацию
    $('.vacancy__module').on('click','.personal__area--capacity-cancel',function(){
      let main = $(this).closest('.vacancy__module'),
        info = $(main).find('.module_info'),
        form = $(main).find('.module_form');

      $(form).hide();
      $(info).show();
      self.changeModuleWidth();
    });
    // Проверка ввода полей
    new CheckInputFields();

    $('#posts').initSelect({search:true});
    $('#experience').initSelect();
    $('#work_type').initSelect();
    $('#posts2').initSelect({search:true});
    $('#experience2').initSelect();
    $('#work_type2').initSelect();
    $('#hcolor').initSelect();
    $('#hlen').initSelect();
    $('#ycolor').initSelect();
    $('#chest').initSelect();
    $('#waist').initSelect();
    $('#thigh').initSelect();
    $('#self_employed').initSelect();
    $('#salary').initSelect();
    $('#salary_time').initSelect();

    new InitPeriod({
      selector:'#period',
      minDate:new Date(vacancyBeginDate*1000),
      maxDate:new Date(vacancyEndDate*1000)
    });

    new InitNicEditor('#requirements','#requirements_panel');
    new InitNicEditor('#duties','#duties_panel');
    new InitNicEditor('#conditions','#conditions_panel');
    // Добавление кастомного срока оплаты
    new CustomSalaryTime(self);

    self.changeLabelWidth(); // выравниваем лейблы
    self.changeModuleWidth();  // устанавливаем ширину полей в ГЕО
    $( window ).on('resize',function() {
      self.changeLabelWidth(); // выравниваем лейблы
      self.changeModuleWidth();  // устанавливаем ширину полей в ГЕО
    });
    // генерируем локации
    new VacancyGeo({
      'cities':window.arVacCities,
      'locations':window.arVacLocations,
      'selector':'#location',
      'state':'read'
    });
    new VacancyGeo({
      'cities':window.arVacCities,
      'locations':window.arVacLocations,
      'selector':'#location-edit',
      'state':'edit'
    });
    // Прячем блок, если в нем нет данных
    if(!$('#activate_module').text().trim().length)
    {
      $('#activate_module').addClass('block__hide');
    }
    // событие активации вакансии
    $(document).on('click','#activate',function(){
      MainScript.stateLoading(true);
      $.ajax({
        type: 'POST',
        data: {event:'activate'},
        success: function(result){
          $('#edit_vacancy').html(result);
          self.init();
          MainScript.stateLoading(false);
        },
        error: function()
        {
          confirm('Системная ошибка');
          MainScript.stateLoading(false);
        }
      });
    });
    // Событие деактивации
    $(document).on('click','#deactivate',function(){
      MainScript.stateLoading(true);
      $.ajax({
        type: 'POST',
        data: {event:'deactivate'},
        success: function(result){
          $('#edit_vacancy').html(result);
          self.init();
          MainScript.stateLoading(false);
        },
        error: function()
        {
          confirm('Системная ошибка');
          MainScript.stateLoading(false);
        }
      });
    });
    // проверяем обязательные поля
    new CheckRequiredFields(self);
  };
  //
  EditVacancy.prototype.changeLabelWidth = function ()
  {
    let arLabels = [];
    $.each($('.form__field-label'),function(){
      if(!$(this).closest('.location__calendar').length)
      {
        arLabels.push(this);
      }
    });


    $(arLabels).css('minWidth','inherit');
    var max = 0;
    $.each(arLabels,function(){
      if($(this).innerWidth()>max)
      {
        max = $(this).innerWidth();
      }
    });
    $(arLabels).css('minWidth',max+'px');
  };
  // Исправление расположения блоков
  EditVacancy.prototype.changeModuleWidth = function ()
  {
    let arItems = $('.vacancy__masonry .vacancy__module');

    arItems.css({width:'50%',height:'initial'});
    if ($(window).width() <= '767')
    {
      arItems.css({float:'none',width:'100%'});
    }
    else
    {
      $.each(arItems, function(i,e){
        if(i==0)
        {
          $(this).css({float:'left'});
        }
        else if(i==1)
        {
          $(this).css({float:'right'});
        }
        else
        {
          let oF = $(arItems[i-1]).css('float'),
              nF = oF==='right' ? 'left' : 'right',
              arr = [], result;

          $(this).css({float:'right'});
          arr.push({value:$(this).offset().top, parent:oF, float:'right'});
          $(this).css({float:'left'});
          arr.push({value:$(this).offset().top, parent:oF, float:'left'});
          $(arItems[i-1]).css('float',nF);
          $(this).css({float:'right'});
          arr.push({value:$(this).offset().top, parent:nF, float:'right'});
          $(this).css({float:'left'});
          arr.push({value:$(this).offset().top, parent:nF, float:'left'});

          $.each(arr, function(){
            if(typeof result!='object')
            {
              result = this;
            }
            else if(this.value<result.value)
            {
              result = this;
            }
          });

          $(arItems[i-1]).css('float', result.parent);
          $(this).css('float', result.float);
        }
      });
      // сортируем по самому нижнему блоку
      arItems.sort(function (a, b) {
        let v1 = $(a).offset().top + $(a).outerHeight(),
            v2 = $(b).offset().top + $(b).outerHeight();

        if (v1 > v2){ return -1; }
        if (v1 < v2){ return 1; }
        return 0;
      });

      if(arItems.length>=2) // выравниваем высоту предпоследнего блока
      {
        setTimeout(function(){
          let o1 = $(arItems[1]).offset().top,
            o2 = $(arItems[0]).offset().top,
            h1 = $(arItems[1]).outerHeight(),
            h2 = $(arItems[0]).outerHeight();

          if((o1+h1)<(o2+h2)) // если не хватает высоты у предпоследнего блока
          {
            $(arItems[1]).height((h1 + (o2+h2) - (o1+h1)));
          }
        },50);
      }
    }
  };
  //
  return EditVacancy;
}());
/**
 *  geo
 */
var VacancyGeo = (function () {
  //
  function VacancyGeo()
  {
    this.init(arguments[0]);
  }
  //
  VacancyGeo.prototype.init = function ()
  {
    var self = this;

    if(
      typeof arguments[0]!=='object'
      ||
      typeof arguments[0].cities!=='object'
      ||
      typeof arguments[0].locations!=='object'
      ||
      typeof arguments[0].selector!=='string'
    )
    {
      console.log('error in init Geo: ' + arguments[0].selector);
      return;
    }

    self.main = $(arguments[0].selector);
    self.arCities = arguments[0].cities;
    self.arLocations = arguments[0].locations;
    self.isEdit = (typeof arguments[0].state!=='string'
      ? false
      : arguments[0].state=='edit');
    self.month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
    self.monthR=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
    self.time=["00:00","01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","23:59"];
    self.current = new Date();

    self.current.setHours(0,0,0,0); // сегодняшняя дата, но время будет ровно 00:00:00.

    if(!self.main.length)
    {
      console.log('error in find selector ' + arguments[0].selector);
      return;
    }

    $.each(self.arCities,function(){
      let oD = self.getCityDates(this.id);

      self.buildCityData(this); // рисуем данные по городу
      self.buildCalendar(this.id, oD.start.year, oD.start.month); // Рисуем календарь
    });
    // События
    $(self.main).on('click', function(e) {
      self.changeMonth(e.target); // Событие переключения календаря
      self.changeEvent(e.target); // Событие клика по дню
      self.addEvent(e.target); // Событие создания события
      self.saveEvent(e.target); // Событие сохранения события
      self.breakEvent(e.target); // Событие отмены изменения события
      self.editEvent(e.target); // Событие редактирования
      self.deleteEvent(e.target); // Событие удаления
    });
    // убираем просмотр событий
    $(document).on('click', function(e) {
      if(
        !$(e.target).closest('.location__calendar-item.edit').length
        &&
        !$(e.target).closest('.location__calendar-tr-edit').length
        &&
        (!$(e.target).closest('.custom-calendar').length && !$(e.target).hasClass('ui-icon'))
        &&
        !$(e.target).closest('.location__event-break').length
      )
      {
        $('.location__calendar-tr-edit').remove();
      }
    });
    // Работаем с городом
    $(document).off('click','#city-add');
    $(document).off('click','#city-create');
    $(document).off('click','#city-break');
    $(document).off('click','.city__delete-btn');
    $(document).on('click','#city-add',function(e){ // Добавление нового города
        let content = '<div id="city-block">'
            + '<div class="form__field">'
              + '<label class="form__field-label text__nowrap">Город</label>'
              + '<div class="form__field-content form__content-indent">'
                + '<div class="form__field-input form__field-select" id="city-input">'
                  + '<select name="city[]"></select>'
                + '</div>'
              + '</div>'
           + '</div>'
          + '<span class="btn__orange" id="city-create">Сохранить</span>'
          + '<span class="btn__orange" id="city-break">Отмена</span>'
        + '</div>';
        $(e.target).hide();
        $(e.target).after(content);
        let arCities = [];
        $.each(self.arCities,function(){
          arCities.push(this.id_city);
        });

        $('#city-input').initSelect({ajax:'/ajax/GetCitiesByName', selectedValsInOtherSelect:arCities});
        self.changeModuleWidth();
      })
      .on('click','#city-create',function(e){ // Сохранение города
        let option = $('#city-input select option:selected'),
            value = $(option).val(),
            costCity = false;

        if(!option.length)
        {
          $('#city-input').addClass('prmu-error');
          return;
        }
        else
        {
          $('#city-input').removeClass('prmu-error');
        }

        // проход по стандарту оплаты
        $.each(arPaymentMain, function(){
          if(this.id_city==Number(value))
          {
            costCity = this.price;
          }
        });
        //
        if(costCity==false) // выбор бесплатного города
        {
          self.ajax({event:'create_city', id_city:value}, e.target);
        }
        else // выбор платного города
        {
          let payment = 0,
            message = '';
          // проход по выбранным ранее городам
          $.each(arCitiesNotPaid, function(){
            if(this==value)
            {
              payment = 1;
            }
          });

          if(payment==0) // этот город ранее не выбирался
          {
            let link = '?event=pay_create_city&new_id_city=' + value;
            message = 'Данное изменение является платным. Оплата за этот город составит: ' + costCity + 'руб.<br><a href="' + link + '" class="btn__orange">Продолжить</a>';
          }
          if(payment==1) // этот город выбирался, но оплата не прошла
          {
            message = 'Для изменения на этот город необходимо произвести оплату. Ссылка для оплаты в блоке "Важно"';
          }
          /*else if(payment>0) // этот город уже выбирался и оплачен
          {
            self.ajax({event:'change_city', old_id_city:city.id_city, new_id_city:value},e.target);
          }*/
          //
          if(message.length)
          {
            $("body").append('<div class="prmu__popup"><p>' + message + "</p></div>");
            $.fancybox.open({
              src: "body>div.prmu__popup",
              type: "inline",
              touch: !1,
              afterClose: function () {
                $("body>div.prmu__popup").remove();
              }
            })
          }
        };
        self.changeModuleWidth();
      })
      .on('click','#city-break',function(e){ // Отмена добавления города
        $('#city-block').remove();
        $('#city-add').show();
        self.changeModuleWidth();
      })
      .on('click','.city__delete-btn',function(e){ // Удаление города
        let result = {event:'delete_city', id_city:$(e.target).data('id')};
        self.ajax(result,e.target);
        self.changeModuleWidth();
    });
    self.changeModuleWidth();  // устанавливаем ширину полей в ГЕО
    $( window ).on('resize',function() { self.changeModuleWidth() }); // устанавливаем ширину полей в ГЕО
  };
  // разница двух unix дат
  VacancyGeo.prototype.diffDate = function ()
  {
    let miliToDay = 1000 * 60 * 60 * 24,// переводим милисекунды в дни
        date1 = Number(arguments[0]),
        date2 = Number(arguments[1]);

    return Math.ceil((date2 - date1) / miliToDay);
  };
  // правильное окончание слова День
  VacancyGeo.prototype.getEnding = function ()
  {
    let num = Number(arguments[0]);

    if(num < 21 && num > 4)
    {
      return 'дней';
    }
    num = num%10;
    if(num == 1)
    {
      return 'день';
    }
    if(num > 1 && num < 5)
    {
      return 'дня';
    }
    return 'дней';
  };
  // вычисляем даты города
  VacancyGeo.prototype.getCityDates = function () // принимает ID города
  {
    let self = this,
        miliBDate = Number(self.arCities[arguments[0]].bdate) * 1000,
        miliEDate = Number(self.arCities[arguments[0]].edate) * 1000,
        start = new Date(miliBDate),
        finish = new Date(miliEDate);

    return {
      miliBDate:miliBDate,
      miliEDate:miliEDate,
      oBdate:start,
      oEdate:finish,
      start:{
        year:start.getFullYear(),
        month:start.getMonth(),
        day:start.getDate(),
        week:start.getDay()
      },
      finish:{
        year:finish.getFullYear(),
        month:finish.getMonth(),
        day:finish.getDate(),
        week:finish.getDay()
      }
    };
  };
  // рисуем календарь
  VacancyGeo.prototype.buildCalendar = function (city, year, month)
  {
     let self = this,
        oD = self.getCityDates(city),
        calendar = '<div class="location__calendar" data-city="' + self.arCities[city].id_city + '" data-id="'
          + self.arCities[city].id + '" data-year="' + year + '" data-month="' + month + '">'
          + '<table class="location__calendar-table"><thead><tr>'
            + '<th class="location__calendar-mleft">'
            + '<th colspan="5" class="location__calendar-mname">'
            + '<th class="location__calendar-mright">'
            + '<tr><th>Пн<th>Вт<th>Ср<th>Чт<th>Пт<th>Сб<th>Вс<tbody><tr>',
        WDFirst = new Date(year, month, 0).getDay(), // день недели, на котором заканчивается предыдущий месяц
        WDLast = new Date(year, month+1, 1).getDay(), // день недели, с которого начинается следующий месяц
        leftArrow = true, rightArrow = true;

    self.current = new Date();
    self.current.setHours(0,0,0,0);
    // рисуем предыдущий месяц
    if(WDFirst) // рисуем предыдущий месяц только если текущий начинается не с понедельника
    {
      let date = new Date(year, month, -(--WDFirst));

      if(self.diffDate(date.getTime(),oD.miliBDate)>0) // Проверяем выводить ли кнопку переключения месяца назад
      {
        leftArrow = false;
      }
      for(let  i=date.getDate(), n=new Date(year, month, 0).getDate(); i<=n; i++)
      {
        date.setDate(i);
        calendar += self.buildDay(date, city, false);
      }
    }
    else
    {
      if(self.diffDate(new Date(year, month, 1),oD.miliBDate)>=0) // Проверяем выводить ли кнопку переключения месяца назад
      {
        leftArrow = false;
      }
    }
    // рисуем текущий месяц
    let date = new Date(year, month, 1);
    for(let  i=date.getDate(), n=new Date(year, month+1, 0).getDate(); i<=n; i++)
    {
      date.setDate(i);
      calendar += self.buildDay(date, city, true);
      if(date.getDay() == 0)
      {
        calendar += '<tr>';
      }
    }
    // рисуем следующий месяц
    if(WDLast!=1)
    {
      let date = new Date(year, month+1, 1);
      for(let  i=date.getDate(), n=new Date(year, month+1, (!WDLast ? 1 : 8-WDLast)).getDate(); i<=n; i++)
      {
        date.setDate(i);
        calendar += self.buildDay(date, city, false);
      }

      if(self.diffDate(date.getTime(),oD.miliEDate)<0)
      {
        rightArrow = false;
      }
    }
    else
    {
      if(self.diffDate(new Date(year, month+1, 0).getTime(),oD.miliEDate)<=0) // Проверяем выводить ли кнопку переключения месяца назад
      {
        rightArrow = false;
      }
    }

    calendar += '</div>';
    let oldCalendar = $(self.main).find('.location__calendar[data-id="' + city + '"]')
    if(oldCalendar.length)
    {
      $(oldCalendar).replaceWith(calendar);
    }
    else
    {
      $(self.main).find('.location__item:eq(-1)').append(calendar);
    }

    let parent = $(self.main).find('.location__calendar[data-id="' + city + '"]'),
        block = $(parent).find('.location__calendar-mname'),
        mName = self.month[month] +' '+ (year!=self.current.getFullYear() ? year : ''); // Выводим год только если он не текущий

    $(block).text(mName);

    if(!leftArrow)
    {
      block = $(parent).find('.location__calendar-mleft');
      $(block).addClass('disable');
    }
    if(!rightArrow)
    {
      block = $(parent).find('.location__calendar-mright');
      $(block).addClass('disable');
    }
    self.initHints();
  };
  // Рисуем день
  VacancyGeo.prototype.buildDay = function (date, city, isActual)
  {
    let self = this,
        time = date.getTime(),
        day = date.getDate(),
        oD = self.getCityDates(city),
        count = 0,
        periods = '',
        isToday = !self.diffDate(time, self.current.getTime());

    $.each(self.arLocations, function(i, location)
    {
      if(location.id_city==city)
      {
        $.each(location.periods, function(j, period)
        {
          let d1 = Number(period.bdate) * 1000,
            d2 = Number(period.edate) * 1000;

          if(self.diffDate(d1,time)>=0 && self.diffDate(time,d2)>=0)
          {
            count++;
            periods += '<li><strong>' + location.name + '</strong> (' +
              location.addr + ') [' + period.btime +  '-' + period.etime + ']</li>';
          }
        });
      }
    });

    if(self.diffDate(time,oD.miliBDate)<=0 && self.diffDate(time,oD.miliEDate)>=0 && !count) // активный период в городе
    {
      return '<td class="active' + (isToday?' today':'') + '">'
        + '<div class="location__calendar-item' + (self.isEdit?' edit':'') + (isActual?' actual':'') + '" data-year="' + date.getFullYear() + '" data-month="' + date.getMonth() + '">'
          + '<i title="' + (isToday?'Сегодня':day+' '+self.monthR[date.getMonth()]) + '" class="hint">' + day + '</i>'
          + (self.isEdit?'<p class="hint" title="Редактировать"></p>':'')
          + '<b title="Нет событий" class="hint">' + count + '</b>'
        + '</div>';
    }
    else if(count) // дни периода
    {
      return '<td class="active' + (isToday?' today':'') + '">'
        + '<div class="location__calendar-item' + (self.isEdit?' edit':'') + (isActual?' actual':'') + '" data-year="' + date.getFullYear() + '" data-month="' + date.getMonth() + '">'
          + '<i title="' + (isToday?'Сегодня':day+' '+self.monthR[date.getMonth()]) + '" class="hint">' + day + '</i>'
          + (self.isEdit?'<p class="hint" title="Редактировать"></p>':'')
          + '<b title="Кол-во событий" class="hint">' + count + '</b>'
          + (self.isEdit?'':('<div class="hint location__periods" title="<ol class=\'location__periods-list\'>' + periods + '</ol>"></div>'))
        + '</div>';
    }
    else // остальные дни
    {
      return '<td class="day' + (isToday?' today':'') + '"><div class="location__calendar-item'
        + (isActual?' actual':'') + '" data-year="' + date.getFullYear() + '" data-month="' + date.getMonth()
        + '"><i title="' + (isToday?'Сегодня':day+' '+self.monthR[date.getMonth()])
        + '" class="hint">' + day + '</i></div>';
    }
  };
  // Рисуем город
  VacancyGeo.prototype.buildCityData = function (city)
  {
    let self = this,
        content = '',
        oD = self.getCityDates(city.id),
        manyCities = Object.keys(self.arCities).length>1;

    if(self.isEdit) // Селект города
    {
      content = '<div class="form__field">'
                + '<label class="form__field-label text__nowrap">Город</label>'
                + '<div class="form__field-content form__content-indent' + (manyCities?' city__delete-indent':'') + '">'
                  + '<div class="form__field-input form__field-select" id="city_' + city.id_city + '">'
                    + '<select name="city[' + city.id_city + ']" class="city__input">'
                      + '<option value="' + city.id_city + '" selected="selected">' + city.city + '</option>'
                    + '</select>'
                  + '</div>'
                  + (manyCities ? '<div title="Удалить город и все его данные" class="city__delete-btn js-g-hashint" data-id="' + city.id_city + '"></div>' : '')
                + '</div>';

      if($('#city_' + city.id_city).length)
      {
        return;
      }

      // Выводим
      $(self.main).append('<div class="location__item">'+content+'</div>');
      let arCities = [];
      $.each(self.arCities,function(){
        arCities.push(this.id_city);
      });
      let objCitySelect = $('#city_' + city.id_city).initSelect({ajax:'/ajax/GetCitiesByName', selectedValsInOtherSelect:arCities});
      // событие изменения города
      $('#city_' + city.id_city).change(function(e){
        let option = $(e.target).find('option:selected'),
            value = $(option).val(),
            nameCity = $(e.target).next().text(),
            costCity = false;
        // проход по стандарту оплаты
        $.each(arPaymentMain, function(){
          if(this.id_city==Number(value))
          {
            costCity = this.price;
          }
        });
        //
        if(costCity==false) // выбор бесплатного города
        {
          self.ajax({event:'change_city', old_id_city:city.id_city, new_id_city:value},e.target);
        }
        else // выбор платного города
        {
          let payment = 0,
              message = '';
          // проход по выбранным ранее городам
          $.each(arCitiesNotPaid, function(){
            if(this==value)
            {
              payment = 1;
            }
          });

          if(payment==0) // этот город ранее не выбирался
          {
            let link = '?event=pay_change_city&old_id_city=' + city.id_city + '&new_id_city=' + value;
            message = 'Данное изменение является платным. Оплата за этот город составит: ' + costCity + 'руб.<br><a href="' + link + '" class="btn__orange">Продолжить</a>';
          }
          if(payment==1) // этот город выбирался, но оплата не прошла
          {
            message = 'Для изменения на этот город необходимо произвести оплату. Ссылка для оплаты в блоке "Важно"';
          }
          /*else if(payment>0) // этот город уже выбирался и оплачен
          {
            self.ajax({event:'change_city', old_id_city:city.id_city, new_id_city:value},e.target);
          }*/
          //
          if(message.length)
          {
            $("body").append('<div class="prmu__popup"><p>' + message + "</p></div>");
            $.fancybox.open({
              src: "body>div.prmu__popup",
              type: "inline",
              touch: !1,
              afterClose: function () {
                $("body>div.prmu__popup").remove();
                objCitySelect.setSelected(city.id_city, city.name, false);
              }
            })
          }
        }
      });
    }
    else
    {
      content = '<div class="location__city-name" data-id="' + city.id_city + '"><div class="location__city-info">Город</div>'
        + '<div class="location__city-value">' + city.city + '</div></div>'; // Рисуем город
      // Рисуем дату
      content+='<div class="location__city-date" data-id="' + city.id_city + '"><div class="location__city-info">Дата и время</div>'
        + '<div class="location__city-value">' + oD.start.day;
      if(oD.start.year!=oD.finish.year || oD.start.month!=oD.finish.month) // если месяцы не совпадают
      {
        content+= ' ' + self.monthR[oD.start.month];
      }
      if(oD.start.year!=self.current.getFullYear() && oD.start.year!=oD.finish.year) // если не текущий год дописываем год
      {
        content+= ' ' + oD.start.year;
      }
      if(oD.miliBDate!=oD.miliEDate) // если даты различны - выводим обе
      {
        content+=' - ' + oD.finish.day + ' ' + self.monthR[oD.finish.month];
        if(oD.finish.year!=self.current.getFullYear()) // если не текущий год дописываем год
        {
          content+= ' ' + oD.finish.year;
        }
      }
      let days = self.diffDate(oD.miliBDate, oD.miliEDate);
      content+= ' (' + days + ' ' + self.getEnding(days) + ')</div></div>'; // выводим кол-во дней
      // Выводим
      let bCityExist = false;
      $.each($('.location__city-name'),function(){
        if(this.dataset.id==city.id_city) bCityExist = true;
      });
      if(!bCityExist)
      {
        $(self.main).append('<div class="location__item">'+content+'</div>');
      }
    }
  };
  // Меняем месяц
  VacancyGeo.prototype.changeMonth = function (target)
  {
    let self = this;

    if(
      !(
        !$(target).hasClass('disable')
        &&
        (
          $(target).hasClass('location__calendar-mright')
          ||
          $(target).hasClass('location__calendar-mleft')
        )
      )
    )
    {
      return;
    }

    let calendar = $(target).closest('.location__calendar')[0],
        op = ($(target).hasClass('location__calendar-mright') ? 1 : -1),
        y = Number(calendar.dataset.year),
        m = Number(calendar.dataset.month);

    if(m==11 && op>0)
    {
      y++;
      m=0;
    }
    else if(m==0 && op<0)
    {
      y--;
      m=11;
    }
    else
    {
      m=m+($(target).hasClass('location__calendar-mright') ? 1 : -1);
    }

    self.buildCalendar(calendar.dataset.id, y, m);
  };
  // Изменение события
  VacancyGeo.prototype.changeEvent = function (target)
  {
    let self = this;

    if(!$(target).closest('.location__calendar-item.edit').length)
    {
      return;
    }

    let calendar = $(target).closest('.location__calendar')[0],
        item = $(target).closest('.location__calendar-item.edit')[0],
        row = $(target).closest('tr'),
        date = new Date(
          Number(item.dataset.year),
          Number(item.dataset.month),
          Number($(item).find('i').text())
        ),
        periods = '',
        content = '<tr class="location__calendar-tr-edit">'
          + '<td colspan="7">'
            + '<div class="location__edit-block"  data-day="' + date.getDate() + '" data-month="' + date.getMonth() + '" data-year="' + date.getFullYear() + '">'
              + '<div class="location__edit-data">' + date.getDate() + ' ' + self.monthR[date.getMonth()] + (date.getFullYear()!=self.current.getFullYear() ? ' '+date.getFullYear() : '') + '</div>'; // Выводим год только если он не текущий

    $(calendar).find('.location__calendar-tr-edit').remove();

    $.each(self.arLocations, function(i, location)
    {
      if(location.id_city==calendar.dataset.id)
      {
        $.each(location.periods, function(j, period)
        {
          let d1 = Number(period.bdate) * 1000,
              d2 = Number(period.edate) * 1000;
          if(self.diffDate(d1,date.getTime())>=0 && self.diffDate(date.getTime(),d2)>=0)
          {
            periods+= '<div class="location__edit-item"><strong>' + location.name + '</strong> (' +
            location.addr + ') [' + period.btime +  '-' + period.etime + '] '
              + '<div><span class="location__event-edit hint" data-id="' + location.id + '" title="Редактировать событие"></span>'
              + '<span class="location__event-delete hint" data-id="' + location.id + '" title="Удалить событие"></span></div></div>';
          }
        });
      }
    });

    content+='<div class="location__edit-list">';
    content+= (periods.length ? periods : 'На данный день нет событий');
    content+='</div><span class="btn__orange location__event-add">Добавить</span></div>';

    $(row).after(content);
    self.initHints();
  };
  // Создать событие
  VacancyGeo.prototype.addEvent = function (target)
  {
    if(!$(target).hasClass('location__event-add'))
    {
      return;
    }
    $(target).hide();
    this.event(target,{});
  };
  // Сохранить событие
  VacancyGeo.prototype.saveEvent = function (target)
  {
    if(!$(target).hasClass('location__event-save'))
    {
      return;
    }
    let self = this,
        main = $(target).closest('.location__event')[0],
        calendar = $(target).closest('.location__calendar')[0],
        arInputs = $(main).find('input'),
        arSelect = $(main).find('select'),
        timeFrom = $(arSelect[0]).find('option:selected'),
        timeTo = $(arSelect[1]).find('option:selected'),
        bError = false;

    $.each(arInputs,function(){
      if(!$(this).val().length)
      {
        bError = true;
        $(this).addClass('prmu-error');
      }
      else
      {
        $(this).removeClass('prmu-error');
      }
    });

    if(!bError)
    {
      let result = {
        name:$(arInputs[0]).val(),
        index:$(arInputs[1]).val(),
        bdate:$(arInputs[2]).val(),
        edate:$(arInputs[3]).val(),
        btime:$(timeFrom).val(),
        etime:$(timeTo).val(),
        event:(main.dataset.id!=undefined?'edit_loc':'create_loc'),
        city_id:calendar.dataset.id
      };
      if(main.dataset.id!=undefined)
      {
        result.location = main.dataset.id;
      }
      self.ajax(result,target);
    }
  };
  // Отмена события
  VacancyGeo.prototype.breakEvent = function (target)
  {
    if(!$(target).hasClass('location__event-break'))
    {
      return;
    }

    let event = $(target).closest('.location__event'),
        main = $(target).closest('.location__edit-block');

    $(event).siblings('.location__event-add').show();
    $(event).remove();
    $(main).find('.location__event-edit').show();
  };
  // редактирование события
  VacancyGeo.prototype.editEvent = function (target)
  {
    if(!$(target).hasClass('location__event-edit'))
    {
      return;
    }
    let objLocation = {},
        parent = $(target).closest('.location__edit-list'),
        btn = $(parent).siblings('.location__event-add');

    $.each(this.arLocations,function () {
      if(this.id==target.dataset.id)
      {
        let objP = this.periods[0],
            bdate = new Date(objP.bdate*1000),
            edate = new Date(objP.edate*1000);

        objLocation = {
          name:this.name,
          index:this.addr,
          bdate:String(bdate.getDate()).padStart(2,'0') + '.' + String((bdate.getMonth()+1)).padStart(2,'0') + '.' + bdate.getFullYear(),
          edate:String(edate.getDate()).padStart(2,'0') + '.' + String((edate.getMonth()+1)).padStart(2,'0') + '.' + edate.getFullYear(),
          btime:objP.btime,
          etime:objP.etime,
          id:this.id
        };
      }
    });
    $(target).hide();
    $(btn).hide();
    this.event(btn, objLocation);
  };
  // удаление события
  VacancyGeo.prototype.deleteEvent = function (target)
  {
    if(!$(target).hasClass('location__event-delete'))
    {
      return;
    }
    this.ajax({event:'delete_loc', location:target.dataset.id},target,);
  };
  // аякс редактирование локаций
  VacancyGeo.prototype.ajax = function (obj, target)
  {
    let module = $(target).closest('#geo_module');

    MainScript.stateLoading(true);

    $.ajax({
      type: 'POST',
      data: obj,
      success: function(result){
        $(module).html(result);
        // генерируем локации
        new VacancyGeo({
          'cities':window.arVacCities,
          'locations':window.arVacLocations,
          'selector':'#location',
          'state':'read'
        });
        new VacancyGeo({
          'cities':window.arVacCities,
          'locations':window.arVacLocations,
          'selector':'#location-edit',
          'state':'edit'
        });
        MainScript.stateLoading(false);
      },
      error: function()
      {
        confirm('Системная ошибка');
        MainScript.stateLoading(false);
      }
    });
  };
  // Блок события
  VacancyGeo.prototype.event = function (target, obj)
  {
    let self = this,
      parent = $(target).closest('.location__calendar')[0],
      edit = $(target).closest('.location__edit-block')[0],
      y = Number(edit.dataset.year),
      m = Number(edit.dataset.month),
      sDate = edit.dataset.day.padStart(2,'0') + '.' + String((m+1)).padStart(2,'0') + '.' + y,
      content = '<div class="location__event"' + (obj.id!=undefined?'data-id="'+obj.id+'"':'') + '>'
        + '<div class="location__event-input">'
          + '<label class="form__field-label">Заголовок<span class="text__red">*</span></label>'
          + '<input type="text" name="location[name]" class="form__field-input" autocomplete="off" value="' + (obj.name!=undefined?obj.name:'Нет заголовка') + '">'
        + '</div>'
        + '<div class="location__event-input">'
          + '<label class="form__field-label">Адрес<span class="text__red">*</span></label>'
          + '<input type="text" name="location[index]" class="form__field-input" autocomplete="off" value="' + (obj.index!=undefined?obj.index:'Нет адреса') + '">'
        + '</div>'
        + '<div class="location__event-input location__event-calendar">'
          + '<label class="form__field-label">Дата<span class="text__red">*</span></label>'
          + '<div class="form__field-date form__field-content">'
            + '<input type="text" name="location[bdate]" class="form__field-input form__field-select" autocomplete="off" value="' + (obj.bdate!=undefined?obj.bdate:sDate) + '">'
          + '</div>'
          + '<span>-</span>'
          + '<div class="form__field-date form__field-content">'
            + '<input type="text" name="location[edate]" class="form__field-input form__field-select" autocomplete="off" value="' + (obj.edate!=undefined?obj.edate:sDate) + '">'
          + '</div>'
        + '</div>'
        + '<div class="location__event-input">'
          + '<label class="form__field-label">Время<span class="text__red">*</span></label>'
          + self.getTimeSelect(1, obj.btime)
          + '<span>-</span>'
          + self.getTimeSelect(2, obj.etime)
        + '</div>'
        + '<span class="btn__orange location__event-save">Сохранить</span>'
        + '<span class="btn__orange location__event-break">Отмена</span>'
        + '</div>';

    $(edit).find('.location__event').remove();
    $(target).after(content);
    let form = $(target).next(),
      periodParent = $(form).find('.location__event-calendar').eq(0),
      oD = self.getCityDates(parent.dataset.id),
      arLabels = $(form).find('.location__event-input label');
    // исправляем ширину лейблов
    $(arLabels).css('minWidth','inherit');
    var max = 0;
    $.each(arLabels,function(){
      if($(this).innerWidth()>max)
      { max = $(this).innerWidth(); }
    });
    $(arLabels).css('minWidth',max+'px');
    // инициализируем datepicker
    new InitPeriod({selector:periodParent, minDate:oD.oBdate, maxDate:oD.oEdate});
    // инициализируем временные периоды
    var objSelectFrom = $('.location__event-time:eq(0)').initSelect();
    var objSelectTo = $('.location__event-time:eq(1)').initSelect();

    $('.location__event-time').change(function(){
      let selectFrom = $('.location__event-time').eq(0),
        selectTo = $('.location__event-time').eq(1),
        optionFrom = $(selectFrom).find('option:selected'),
        optionTo = $(selectTo).find('option:selected'),
        arFrom = $(optionFrom).val().split(':'),
        arTo = $(optionTo).val().split(':');
      if(
        (Number(arFrom[0])>Number(arTo[0]))
        ||
        (
          (Number(arFrom[0])==Number(arTo[0]))
          &&
          (Number(arFrom[1])>=Number(arTo[1]))
        )
      )
      {
        let i = $(optionFrom).index();
        optionTo = $(selectTo).find('option').eq(i);
        objSelectTo.setSelected($(optionTo).val(),$(optionTo).text());
      }
    });
    $('.location__event input').on('input',function () {
      /*let regexp = new RegExp('[?!,.а-яА-ЯёЁ\D\s+]', 'g'),
          value = $(this).val();

      value = value.replace(regexp,'');

      console.log(value);

      $(this).val(value);*/
    });
  };
  // Создать селект времени
  VacancyGeo.prototype.getTimeSelect = function ()
  {
    let isStart = arguments[0],
        value = arguments[1],
        content = '<div class="form__field-input form__field-select location__event-time"><select name="location[' + (isStart?'b':'e') + 'time]">';

    $.each(this.time,function(i,e){
      if(isStart==1 && i!=24)
      {
        content+='<option value="' + e + '"' + (e===value?' selected="selected"':'') + '>'  + e + '</option>';
      }
      if(isStart==2 && i!=0)
      {
        content+='<option value="' + e + '"' + (e===value?' selected="selected"':'') + '>'  + e + '</option>';
      }
    });

    content+='</select></div>';
    return content;
  };
  // включаем подсказки
  VacancyGeo.prototype.initHints = function ()
  {
    $.each($('#edit_vacancy').find('.hint'),function(){
      if(!$(this).hasClass('tooltipstered'))
      {
        $(this).tooltipster({contentAsHTML:true, theme:['tooltipster-calendar']});
      }
    });
  };
  // Исправление расположения блоков
  VacancyGeo.prototype.changeModuleWidth = function ()
  {
    if ($(window).width() <= '767')
    {
      $('#geo_module').css({'width':'100%'});
      $('.location__item').css({'width':'100%'});
      $('#city-block').css({'width':'100%'});
    }
    else
    {
      if($('.location__item').length==2) // если по одному блоку
      {
        $('#geo_module').css({'float':'left','width':'50%'});
        $('.location__item').css({'width':'100%'});
        $('#city-block').css({'width':'100%'});
      }
      else
      {
        $('#geo_module').css({'float':'left','width':'100%'});
        $('.location__item').css({'width':'50%'});
        $('#city-block').css({'width':'50%'});
      }
    }
  };
  //
  return VacancyGeo;
}());
/**
 *  Кастомный срок оплаты
 */
var CustomSalaryTime = (function () {
  //
  function CustomSalaryTime() {
    this.init(arguments[0]);
  }
  //
  CustomSalaryTime.prototype.init = function ()
  {
    let objEditVacancy = arguments[0];
    $(document).on('click','#salary_time_add-btn',function(e){
      if($(e.target).hasClass('form__field-disable'))
      {
        return;
      }

      let parent = $(e.target).parent(),
        select = $(parent).find('.form__field-content'),
        content = '<div id="salary_time_add-block" class="form__field">'
          + '<label class="form__field-label">Свой вариант</label>'
          + '<div class="form__field-content form__content-indent form__content-hint">'
          + '<input '
          + 'type="text" '
          + 'name="salary_time_custom" '
          + 'class="form__field-input prmu-required prmu-check" '
          + 'data-params=\'{"limit":"70","parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}\' autocomplete="off">'
          + '</div>'
          + '<div id="salary_time_del-btn" class="form__field-hint tooltip" title="Удалить"></div>'
          + '</div>';

      $(parent).after(content);
      $(select).addClass('form__field-disable');
      $(e.target).addClass('form__field-disable');
      objEditVacancy.changeLabelWidth();
      $.each($('.tooltip'),function(){
        if(!$(this).hasClass('tooltipstered'))
        {
          $(this).tooltipster({
            contentAsHTML:true,
            side:'right',
            theme:['tooltipster-noir', 'tooltipster-noir-customized']
          });
        }
      });
      new CheckInputFields();
    })
    .on('click','#salary_time_del-btn',function(e){
      $('#salary_time_add-btn').prev().removeClass('form__field-disable');
      $('#salary_time_add-btn').removeClass('form__field-disable');
      $('#salary_time_add-block').remove();
    });
  };
  //
  return CustomSalaryTime;
}());
/**
 *
 */
$(document).ready(function () {
  new EditVacancy();
});