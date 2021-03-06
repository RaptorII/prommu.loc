'use strict'
/**
 *
 */
var CreateVacancy = (function () {
  //
  function CreateVacancy()
  {
    this.init();
  }
  //
  CreateVacancy.prototype.init = function ()
  {
    if(arguments.length) // инициализация после аякс запроса
    {
      $(arguments[0]).html(arguments[1]);
    }

    let self = this,
        step = $('[name="step"]').val();

    // Проверка ввода полей
    new CheckInputFields();

    if(step==='1')
    {
      $('#posts').initSelect({search:true});
      $('#cities').initSelect({ajax:'/ajax/GetCitiesByName'});
      new InitPeriod({
        selector:'#period',
        minDate:'0',
        maxDate:'+30D'
      });
      //self.setCost();
    }
    else if(step==='2')
    {
      $('#work_type').initSelect();
      $('#experience').initSelect();
      $('#self_employed').initSelect();
    }
    else if(step==='3')
    {
      $('#salary').initSelect();
      $('#salary_time').initSelect();
      // Добавление кастомного срока оплаты
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
          self.changeLabelWidth();
          Hinter.bind('.tooltip');
          new CheckInputFields();
        })
        .on('click','#salary_time_del-btn',function(e){
          $('#salary_time_add-btn').prev().removeClass('form__field-disable');
          $('#salary_time_add-btn').removeClass('form__field-disable');
          $('#salary_time_add-block').remove();
      });
    }
    else if(step==='4')
    {
      new InitNicEditor('#requirements','#requirements_panel');
      new InitNicEditor('#duties','#duties_panel');
      new InitNicEditor('#conditions','#conditions_panel');
    }
    else if(step==='6')
    {
      $('form').attr('data-params','');
      $('form').submit(function(){
        $('body').addClass('prmu-load');
      });
    }
    else if(step==='duplicate')
    {
      new InitPeriod({
        selector:'#period',
        minDate:'0',
        maxDate:'+30D'
      });
      $('form').attr('data-params','');
      $('form').submit(function(){
        $('body').addClass('prmu-load');
      });
    }
    // инициализация подсказок
    Hinter.bind('.tooltip');
    // выравниваем лейблы
    self.changeLabelWidth();
    $( window ).on('resize',function() {
      self.changeLabelWidth();
    });
    // проверяем обязательные поля
    new CheckRequiredFields(self);
    // запуск анимашки
    self.startSvg();
    // возвращаемся назад
    $(document).off('click','#prev_step');
    $(document).on('click','#prev_step',function(){
      let step = Number($('[name="step"]').val());
      $('body').addClass('prmu-load');
      $.ajax({
        type: 'GET',
        url: '?change_step=' + --step,
        success: function(result){
          self.init($('form'),result);
          $('body').removeClass('prmu-load');
        },
        error: function()
        {
          confirm('Системная ошибка');
          $('body').removeClass('prmu-load');
        }
      });
    });
  };
  //
  CreateVacancy.prototype.changeLabelWidth = function ()
  {
    $('.form__field-label').css('minWidth','inherit');
    var max = 0;
    $.each($('.form__field-label'),function(){
      if($(this).width()>max)
      {
        max = $(this).width();
      }
    });
    $('.form__field-label').css('minWidth',max+'px');
  };
  //
  CreateVacancy.prototype.startSvg = function ()
  {
    let self = this,
      height = window.innerHeight,
      width = window.innerWidth,
      s = Snap('.svg-bg');

    for (var i = 0; i < 50; i++) {
      var obj = s.rect(self.getRandom(0, width),
        self.getRandom(0, height),
        self.getRandom(20, 80),
        self.getRandom(30, 170));
      obj.attr({opacity: Math.random(), transform: 'r30'});
    }
    self.svgPulse(s,width,height);
  };
  //
  CreateVacancy.prototype.getRandom = function (min, max)
  {
    return Math.floor((Math.random() * max) + min);
  };
  //
  CreateVacancy.prototype.svgPulse = function (s, width, height)
  {
    let self = this;
    s.selectAll('rect').forEach(function (e)
    {
      e.animate({
        x: self.getRandom(0, width),
        y: self.getRandom(0, height),
        width: self.getRandom(20, 120),
        height: self.getRandom(30, 420),
        opacity: Math.random() / 2 ,
      }, 20000, mina.easeinout);
    });
  };
  //
  CreateVacancy.prototype.setCost = function ()
  {
    if(typeof arVacancyPrice!=undefined) // вычисляем стоимость услуги
    {
      $('#cities').off('change');
      $('#cities').on('change',function(e){
        let arOptions = $(e.target).find('option:selected'),
          arVals = [], cost = 0;

        $.each(arOptions,function(){ arVals.push(Number(this.value)) });
        $.each(arVacancyPrice,function(){
          if(arVals.includes(this.id_city) && this.price>cost)
          {
            cost = this.price;
          }
        });
        $('#cost').text(cost + ' руб.');
      });
    }
  };
  //
  return CreateVacancy;
}());
/**
 *
 *  Блок заказа Премиум услуги
 *
 */
var ServicePremium = (function () {
  //
  function ServicePremium() {
    this.init(arguments[0]);
  }
  //
  ServicePremium.prototype.init = function ()
  {
    let self = this,
        max = arguments[0].period;

    self.prices = arguments[0].prices;
    // ввод периода
    $('#premium_input').on('change input',function(e){
      let v = $(this).val();

      v = v.replace(/\D+/g,'');

      if(v==='')
      {
        if(e.type=='change')
        {
          v = 1;
        }
      }
      else if(v>max)
      {
        v = max;
      }
      else if(v<1)
      {
        v = 1;
      }
      $(this).val(v);

      if(e.type=='change')
      {
        self.setPrice();
        self.setInfo();
      }
    });
    // изменение списка городов
    $('[name="premium_region[]"]').on('change',function(){
      let input = this,
          checked = false;
      $.each($('[name="premium_region[]"]'),function(){
        if($(this).is(':checked'))
        {
          checked = true;
        }
      });
      if(!checked) // один город полюбому должен быть чекнутый
      {
        setTimeout(function(){
          $(input).prop('checked', true);
          self.setPrice();
        },10);
      }
      else
      {
        self.setPrice();
      }
    });
    //
    self.setPrice();
    self.setInfo();
  };
  //
  ServicePremium.prototype.setPrice = function ()
  {
    let self = this,
        price = 0,
        period = Number($('#premium_input').val());

    $.each($('[name="premium_region[]"]'),function(){
      let v = $(this).val();
      if($(this).is(':checked'))
      {
        $.each(self.prices,function(){
          if(v===this.id_city)
          {
            price += Number(this.price);
          }
        })
      }
    });
    $('#premium_price').text(price * period);
  };
  //
  ServicePremium.prototype.setInfo = function ()
  {
    let period = Number($('#premium_input').val()),
        percent = 0;

    if(period==1)
    {
      percent = 89;
    }
    else if(period==2)
    {
      percent = 98;
    }
    else
    {
      percent = 100;
    }
    $('#premium_percent').text(percent);
    $('#premium_days').text(period);
  };
  //
  return ServicePremium;
}());
/**
 *
 * Подсказки
 *
 */
var Hinter = (function () {
  function Hinter() {
    var self = this;
  }
  Hinter.prototype.init = function () { };
  Hinter.bind = function (inSel, inOpts) {
    if (inOpts === void 0) { inOpts = {}; }
    var defUserOpts = { side: 'right', animation: 'fade'};
    var opts = this.options;
    if (this.hintSide[inOpts.side])
      defUserOpts.side = this.hintSide[inOpts.side];
    if (this.hintAnimation[inOpts.animation])
      defUserOpts.animation = this.hintAnimation[inOpts.animation];
    $.extend(opts, defUserOpts);
    $.each($(inSel),function(){
      if(!$(this).hasClass('tooltipstered'))
      { $(this).tooltipster(opts); }
    });
  };
  Hinter.hintSide = { 'top': 'top', 'bottom': 'bottom', 'right': 'right', 'left': 'left' };
  Hinter.hintAnimation = { 'fade': 'fade', 'swing': 'swing' };
  Hinter.options = {
    side: 'bottom',
    theme: ['tooltipster-noir-customized'],
    animation: 'fade',
    contentAsHTML: true
  };
  return Hinter;
}());
/**
 *
 */
$(document).ready(function () {
  new CreateVacancy();
});


