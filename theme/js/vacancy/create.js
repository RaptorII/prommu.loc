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
      new InitSelect('#posts');
      new InitSelect('#cities');
      new InitPeriod('#period');
    }
    else if(step==='2')
    {
      new InitSelect('#work_type');
      new InitSelect('#experience');
      new InitSelect('#self_employed');
      new InitPeriod('#period');
    }
    else if(step==='3')
    {
      new InitSelect('#salary');
      new InitSelect('#salary_time');
    }
    else if(step==='4')
    {
      new InitNicEditor('#requirements','#requirements_panel');
      new InitNicEditor('#duties','#duties_panel');
      new InitNicEditor('#conditions','#conditions_panel');
    }
    else if(step==='5')
    {
      $('form').attr('data-params','');
      $('form').submit(function(){
        $('body').addClass('prmu-load');
      });
    }
    // инициализация подсказок
    Hinter.bind('.tooltip', { side: 'right' });
    // выравниваем лейблы
    self.changeLabelWidth();
    $( window ).on('resize',function() {
      self.changeLabelWidth();
    });
    // проверяем обязательные поля
    new CheckRequiredFields(self);
    // запуск анимашки
    self.startSvg();
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
    var defUserOpts = { side: 'bottom', animation: 'fade'};
    var opts = this.options;
    if (this.hintSide[inOpts.side])
      defUserOpts.side = this.hintSide[inOpts.side];
    if (this.hintAnimation[inOpts.animation])
      defUserOpts.animation = this.hintAnimation[inOpts.animation];
    $.extend(opts, defUserOpts);
    $(inSel).tooltipster(opts);
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