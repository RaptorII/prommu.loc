'use strict'
/**
 *
 * @type {RegisterPage}
 */
var RegisterPage = (function () {
  //
  RegisterPage.prototype.firstInputCompany = true;
  //
  function RegisterPage()
  {
    this.init();
  }
  //
  RegisterPage.prototype.init = function ()
  {
    let self = this;

    $('body').on( // step 1
      'change','#register_form .input-type',
      function(){ self.send() })
    .on('input','#register_form .input-name, #register_form .input-surname',function(){ // step 2
      self.checkName(this);
    })
    .on('input','#register_form .input-company',function(){
      self.checkCompany(this);
    })
    .on('input','#register_form .input-login',function(){
      self.checkText(this);
    });
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

      if(!$('#register_form .input__error').length)
      {
        self.send();
      }
    });
    //
    self.startSvg();
  },
  // отправляем аяксом
  RegisterPage.prototype.send = function () {
    let self = this,
        arForm = $('#register_form').serializeArray(),
        result = {};

    $(arForm).each(function () {
      result[this.name] = this.value;
    });

    $('body').addClass('prmu-load');
    $.ajax({
      type: 'POST',
      data: {data: JSON.stringify(result)},
      success: function (html) {
        $('#register_form').html(html);
        self.startSvg();
        $('body').removeClass('prmu-load');
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
    setInterval(function () { self.svgPulse() }, 20000);
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
  }
  //
  return RegisterPage;
}());
/*
*
*/
$(document).ready(function () {
  new RegisterPage();
});