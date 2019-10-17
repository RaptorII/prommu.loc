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
    });
    //
    $('#register_form').submit(function(e){
      let btn = $(this).find('button'),
          step = Number($(btn).data('step'));

      e.preventDefault();
      if(
        step==2
        &&
        self.checkName($('#register_form .input-name'))
        &&
        self.checkName($('#register_form .input-surname'))
        &&
        self.checkCompany($('#register_form .input-company'))
        &&
        self.checkText($('#register_form .input-login'))
      )
      {
        self.send();
      }
    });
  }
  // отправляем аяксом
  RegisterPage.prototype.send = function () {
    let arForm = $('#register_form').serializeArray(),
      result = {};

    $(arForm).each(function () {
      result[this.name] = this.value;
    });

    $('body').addClass('prmu-load');
    $.ajax({
      type: 'POST',
      data: {data: JSON.stringify(result)},
      success: function (r) {
        $('body').html(r).removeClass('prmu-load');
      }
    });
  }
  // проверка текстового поля
  RegisterPage.prototype.checkName = function (input)
  {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val().replace(/[0-9]/g,'');

    $(input).val((v.charAt(0).toUpperCase() + v.slice(1).toLowerCase()));
    v = $(input).val().trim();
    !v.length ? $(input).addClass('error') : $(input).removeClass('error');

    return !v.length;
  }
  // проверка компании
  RegisterPage.prototype.checkCompany = function (input)
  {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val();

    $(input).val((v.charAt(0).toUpperCase() + v.slice(1)));
    v = $(input).val().trim();

    if(v.length>=4)
    {
      this.firstInputCompany = false;
    }

    let result = ((!v.length || v.length<3) && !this.firstInputCompany);
    result ? $(input).addClass('error') : $(input).removeClass('error');

    return result;
  }
  // простая проверка на пустоту
  RegisterPage.prototype.checkText = function (input)
  {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val().trim();

    v.length ? $(input).addClass('error') : $(input).removeClass('error');

    return v.length;
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


document.addEventListener('DOMContentLoaded', function(){

    /**
     * Fly Sqare
     * Snap library
     * @type {number}
     */
    var height = window.innerHeight,
        width = window.innerWidth,
        max = height > width ? height : width,
        s = Snap('.svg-bg');

    function random(min, max) {
        return Math.floor((Math.random() * max) + min)
    }

    function pulse() {
        var coord;
        s.selectAll('rect').forEach(function (e) {
            e.animate({
                x: random(0, width),
                y: random(0, height),
                width: random(20, 120),
                height: random(30, 420),
                opacity: Math.random() / 2 ,
            }, 20000, mina.easeinout);
        });
    }

    function generate() {
        for (var i = 0; i < 50; i++) {
            var obj = s.rect(random(0, width),
                random(0, height),
                random(20, 80),
                random(30, 170));
            obj.attr({
                opacity: Math.random(),
                transform: 'r30'
            });
        }
    }

    generate();
    pulse();
    setInterval(function () {
        pulse();
    }, 20000);
    /**
     * end
     */


});

function backAway(){
    //if it was the first page
    if(history.length === 1){
        window.location = "https://prommu.com"
    } else {
        history.back();
    }
}

