'use strict'
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
      let module = $(arguments[0]).closest('.module');
      $(module).html(arguments[1]);
    }
    $('.tooltip').tooltipster({
      contentAsHTML:true,
      side:'right',
      theme:['tooltipster-noir', 'tooltipster-noir-customized']
    });
    // переключение на форму редактирования
    $('.module').on('click','.personal__area--capacity-edit',function(){
      let main = $(this).closest('.module'),
          info = $(main).find('.module_info'),
          form = $(main).find('.module_form');

      $(info).hide();
      $(form).fadeIn();
      self.changeLabelWidth();
    });
    // переключаем обратно на информацию
    $('.module').on('click','.personal__area--capacity-cancel',function(){
      let main = $(this).closest('.module'),
        info = $(main).find('.module_info'),
        form = $(main).find('.module_form');

      $(form).hide();
      $(info).fadeIn();
    });
    // Проверка ввода полей
    new CheckInputFields();
    // выравниваем лейблы
    self.changeLabelWidth();
    $( window ).on('resize',function() {
      self.changeLabelWidth();
    });

    new InitSelect('#posts');
    new InitSelect('#experience');
    new InitSelect('#work_type');
    new InitSelect('#posts2');
    new InitSelect('#experience2');
    new InitSelect('#work_type2');
    new InitSelect('#hcolor');
    new InitSelect('#hlen');
    new InitSelect('#ycolor');
    new InitSelect('#chest');
    new InitSelect('#waist');
    new InitSelect('#thigh');
    new InitSelect('#self_employed');
    new InitSelect('#salary');
    new InitSelect('#salary_time');

    new InitNicEditor('#requirements','#requirements_panel');
    new InitNicEditor('#duties','#duties_panel');
    new InitNicEditor('#conditions','#conditions_panel');
    // проверяем обязательные поля
    new CheckRequiredFields(self);
  };
  //
  EditVacancy.prototype.changeLabelWidth = function ()
  {
    $('.form__field-label').css('minWidth','inherit');
    var max = 0;
    $.each($('.form__field-label'),function(){
      if($(this).innerWidth()>max)
      {
        max = $(this).innerWidth();
      }
    });
    $('.form__field-label').css('minWidth',max+'px');
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
    let self = this;

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
      console.log('error in init Geo');
      return;
    }

    self.main = $(arguments[0].selector);
    self.arCities = arguments[0].cities;
    self.arLocations = arguments[0].locations;
    self.month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
    self.monthR=["января","февраля","марта","апрела","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
    self.content = '';
    self.current = new Date();

    self.current.setHours(0,0,0,0); // сегодняшняя дата, но время будет ровно 00:00:00.

    if(!self.main.length)
    {
      console.log('error in find selector');
      return;
    }

    $.each(self.arCities,function(){
      let oD = self.getCityDates(this.id);
      // Рисуем город
      self.content+='<div class="location__city-name" data-id="'
        + this.id + '" data-id_city="' + this.id_city + '"><div class="location__city-info">Город</div>'
        + '<div class="location__city-value">' + this.city + '</div></div>';
      // Рисуем дату
      self.content+='<div class="location__city-date"><div class="location__city-info">Дата и время</div>'
        + '<div class="location__city-value">' + oD.start.day;
      if(oD.start.year!=oD.finish.year || oD.start.month!=oD.finish.month) // если месяцы не совпадают
      {
        self.content+= ' ' + self.monthR[oD.start.month];
      }
      if(oD.start.year!=self.current.getFullYear() && oD.start.year!=oD.finish.year) // если не текущий год дописываем год
      {
        self.content+= ' ' + oD.start.year;
      }
      if(oD.miliBDate!=oD.miliEDate) // если даты различны - выводим обе
      {
        self.content+=' - ' + oD.finish.day + ' ' + self.monthR[oD.finish.month];
        if(oD.finish.year!=self.current.getFullYear()) // если не текущий год дописываем год
        {
          self.content+= ' ' + oD.finish.year;
        }
      }
      let days = self.diffDate(oD.miliBDate, oD.miliEDate);
      self.content+= ' (' + days + ' ' + self.getEnding(days) + ')</div></div>'; // выводим кол-во дней
      // Выводим пока что есть
      $(self.main).append(self.content);
      // Рисуем календарь
      self.buildCalendar(this.id, oD.start.year, oD.start.month);
    });
    // Событие переключения календаря
    $(document).on('click', function(e) {
        if(
          $(e.target).hasClass('location__calendar-mright')
          ||
          $(e.target).hasClass('location__calendar-mleft')
        )
        {
          let item = $(e.target).closest('.location__calendar')[0],
              op = ($(e.target).hasClass('location__calendar-mright') ? 1 : -1),
              y = Number(item.dataset.year),
              m = Number(item.dataset.month);

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
            m=m+($(e.target).hasClass('location__calendar-mright') ? 1 : -1);
          }

          self.buildCalendar(item.dataset.city, y, m);
        }
      });
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
  VacancyGeo.prototype.getCityDates = function ()
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
        calendar = '<div class="location__calendar" data-city="' + city +
          '" data-year="' + year + '" data-month="' + month + '">' +
          '<table class="location__calendar-table"><thead><tr>' +
          '<th class="location__calendar-mleft">' +
          '<th colspan="5" class="location__calendar-mname">' +
          '<th class="location__calendar-mright">'+
          '<tr><th>Пн<th>Вт<th>Ср<th>Чт<th>Пт<th>Сб<th>Вс'+
          '<tbody><tr>',
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
        leftArrow = false;
      }
    }

    calendar += '</div>';
    let oldCalendar = $(self.main).find('.location__calendar[data-city="' + city + '"]')
    if(oldCalendar.length)
    {
      $(oldCalendar).replaceWith(calendar);
    }
    else
    {
      $(self.main).append(calendar);
    }
    let parent = $(self.main).find('.location__calendar[data-city="' + city + '"]'),
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
    $(self.main).attr({'data-month':month,'data-year':year});
    $('.location__calendar-hint').tooltipster({contentAsHTML:true, theme:['tooltipster-calendar']});
  };
  // Рисуем день
  VacancyGeo.prototype.buildDay = function (date, city, isActual)
  {
    let self = this,
        time = date.getTime(),
        day = date.getDate(),
        oD = self.getCityDates(city),
        count = 0,
        periods = '';

    $.each(self.arLocations, function(i, location)
    {
      let bCnt = false;

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
            bCnt = true;
          }
        });
      }
      bCnt ? periods='<b title="Кол-во событий">' + count
        + '</b><div class="location__calendar-hint" title="<ol class=\'location__calendar-periods\'>'
        + periods + '</ol>"></div>' : periods='';
    });
    if(self.diffDate(time,oD.miliBDate)<=0 && self.diffDate(time,oD.miliEDate)>=0 && !periods.length) // активный период в городе
    {
      periods = '<b title="Нет событий" class="location__calendar-hint">' + count + '</b>';
    }
    // выводим
    if(periods.length) // дни периода
    {
      return '<td class="active"><div class="location__calendar-item' +
        (isActual?' actual':'') + '"><i title="Число" class="location__calendar-hint">' + day + '</i>' + periods + '</div>';
    }
    else if(!self.diffDate(time, self.current.getTime())) // today
    {
      return '<td class="today"><div class="location__calendar-item' +
        (isActual?' actual':'') + '"><i title="Сегодня" class="location__calendar-hint">' + day + '</i></div>';
    }
    else // остальные дни
    {
      return '<td class="day"><div class="location__calendar-item' +
        (isActual?' actual':'') + '"><i title="Число" class="location__calendar-hint">' + day + '</i></div>';
    }
  };
  //
  return VacancyGeo;
}());
/**
 *
 */
$(document).ready(function () {
  new EditVacancy();
});