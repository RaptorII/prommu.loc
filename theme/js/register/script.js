'use strict'
/**
 *
 * @type {RegisterPage}
 */
var RegisterPage = (function () {
  //
  RegisterPage.prototype.firstInputCompany = true;
  RegisterPage.prototype.firstInputCode = true;
  RegisterPage.prototype.codeLength = 4;
  RegisterPage.prototype.passwordLength = 6;
  //
  function RegisterPage()
  {
    this.init();
  }
  //
  RegisterPage.prototype.init = function ()
  {
    let self = this;

    $('body')
      .on( // step 1
        'change',
        '#register_form .input-type',
        function(){
          let data = self.getFormData();
          self.send(data);
        })
      .on( // step 2
        'input',
        '#register_form .input-name, #register_form .input-surname',
        function(){ self.checkName(this) })
      .on(
        'input',
        '#register_form .input-company',
        function(){ self.checkCompany(this) })
      .on(
        'input',
        '#register_form .input-login',
        function(){ self.checkText(this) })
      .on(
        'click',
        '#register_form .login__error a',
        function(e){
          e.preventDefault();
          let btn = $('#register_form').find('button'),
              step = $(btn).data('step');

          self.send({step:step,redirect:'auth',href:this.href});
        })
      .on( // step 3 | 4
        'click',
        '#register_form .back-away',
        function(){
          let btn = $('#register_form').find('button'),
            step = $(btn).data('step');

          self.send({step:step,redirect:'back'});
        })
      .on(
        'click',
        '#register_form .repeat-code',
        function(){
          let btn = $('#register_form').find('button'),
              step = $(btn).data('step');

          if(!$('.repeat-code').hasClass('grey'))
          {
            self.send({step:step,send_code:'Y'});
          }
        })
      .on(
        'input',
        '#register_form .input-code',
        function(){ self.checkCode(this)})
      .on( // step 4
        'input',
        '#register_form .input-password',
        function(){ self.checkPassword(this) })
      .on(
        'input',
        '#register_form .input-r-password',
        function(){ self.checkPassword(this) });
    // выключаем копипаст
    $('#register_form [type="text"]').bind('paste',function(e) { e.preventDefault() });
    //
    $('#register_form').submit(function(e){
      let btn = $(this).find('button'),
          step = Number($(btn).data('step'));
/*
      console.log(step==2);
      console.log(self.checkName($('#register_form .input-name')));
      console.log(self.checkName($('#register_form .input-surname')));
      console.log(self.checkCompany($('#register_form .input-company')));
      console.log(self.checkText($('#register_form .input-login')));
*/
      e.preventDefault();
      if(step==2)
      {
        self.firstInputCompany = false;
        self.checkName($('#register_form .input-name'));
        self.checkName($('#register_form .input-surname'));
        self.checkCompany($('#register_form .input-company'));
        self.checkText($('#register_form .input-login'));
      }
      if(step==3)
      {
        self.firstInputCode = false;
        self.checkCode('#register_form .input-code');
      }
      if(step==4)
      {
        self.checkPassword();
      }

      if(!$('#register_form .input__error').length)
      {
        let data = self.getFormData();
        self.send(data);
      }
    });
    // установка таймера
    if($('.repeat-code span').is('*'))
    {
      self.setTimer();
    }
    //
    //self.startSvg();
  },
  // отправляем аяксом
  RegisterPage.prototype.send = function (data) {
    let self = this;
    $('body').addClass('prmu-load');
    $.ajax({
      type: 'POST',
      data: {data: JSON.stringify(data)},
      success: function (html) {
        $('#register_form').html(html);
        //self.startSvg();
        if(typeof data.href !=='undefined')
        {
          window.location.href = data.href;
        }
        else
        {
          self.setTimer();
          $('body').removeClass('prmu-load');
        }
      }
    });
  },
  // проверка текстового поля
  RegisterPage.prototype.checkName = function (input)
  {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val().replace(/[0-9]/g,'');

    $(input).val((v.charAt(0).toUpperCase() + v.slice(1).toLowerCase()));

    return this.inputError(input, !$(input).val().trim().length);
  },
  // проверка компании
  RegisterPage.prototype.checkCompany = function (input)
  {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val();

    $(input).val((v.charAt(0).toUpperCase() + v.slice(1)));
    v = $(input).val().trim();

    if(v.length>=3)
      this.firstInputCompany = false;

    let result = ((!v.length || v.length<3) && !this.firstInputCompany);

    return this.inputError(input, result);
  },
  // простая проверка на пустоту
  RegisterPage.prototype.checkText = function (input)
  {
    if(!$(input).is('*'))
      return true;

    return this.inputError(input, !$(input).val().trim().length);
  },
  // проверка кода подтверждения
  RegisterPage.prototype.checkCode = function (input)
  {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val().replace(/\D/, '').substr(0,this.codeLength),
        checkCode = v.length==this.codeLength;

    $(input).val(v);

    if(checkCode)
      this.firstInputCode = false;

    return this.inputError(input, (!checkCode && !this.firstInputCode) || !v.length);
  },
  // проверка кода подтверждения
  RegisterPage.prototype.checkPassword = function ()
  {
    let input1 = $('.input-password'),
        input2 = $('.input-r-password');

    if(!$(input1).is('*') && !$(input2).is('*'))
      return true;

    if(typeof arguments[0] == 'undefined')
    {
      this.inputError(input1, !$(input1).val().length);
      this.inputError(input2, !$(input2).val().length);
      if( $(input1).val() != $(input2).val() )
      {
        this.inputError(input1, 1);
        this.inputError(input2, 1);
      }
      else if($(input1).val().length < this.passwordLength)
      {
        this.inputError(input1, 1);
        this.inputError(input2, 1);
      }
    }
    else
    {
      this.inputError(arguments[0], !$(arguments[0]).val().length);
    }
  },
  // утсановка поля
  RegisterPage.prototype.inputError = function (input, error)
  {
    if(error)
    {
      $(input).addClass('input__error');
      return false;
    }
    else
    {
      $(input).removeClass('input__error');
      return true;
    }
  },
  //
  RegisterPage.prototype.startSvg = function ()
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
    setInterval(function () { self.svgPulse(s,width,height) }, 20000);
  },
  //
  RegisterPage.prototype.getRandom = function (min, max)
  {
    return Math.floor((Math.random() * max) + min);
  },
  //
  RegisterPage.prototype.svgPulse = function (s, width, height)
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
  },
  // получение данных с формы
  RegisterPage.prototype.getFormData = function ()
  {
    let self = this,
        arForm = $('#register_form').serializeArray(),
        result = {};

    $(arForm).each(function () {
      result[this.name] = this.value;
    });

    return result;
  },
  // таймер отправки кода
  RegisterPage.prototype.setTimer = function ()
  {
    if(!$('.repeat-code span').is('*'))
      return false;

    setInterval(function(){
      let main = $('.repeat-code span'),
          sec = Number($(main).text());

      sec--;
      if(sec<=0)
      {
        $('.repeat-code').removeClass('grey').html('Отправить повторно');
      }
      else
      {
        $(main).text(sec);
      }
    },1000);
  }
  //
  return RegisterPage;
}());
/*
*
*/
$(document).ready(function () {

  new RegisterPage();

  var elems = $('.login-img');
  elems.each(function(){
    var elem = $(this);
    var width = elem.width();
    var height = elem.height();
    if(width <= height){
      elem.addClass(' login-img-vertical ');
    }else{
      elem.addClass(' login-img-horizontal ');
    }
  })
});