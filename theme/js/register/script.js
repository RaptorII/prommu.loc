'use strict'
var RegisterPage = (function () {
  //
  function RegisterPage()
  {
    this.init();
  }
  //
  RegisterPage.prototype.init = function ()
  {
    let self = this;
    // step 1
    $('body').on('change','#register_form .input-type',function(){
      let data = self.getData();
      self.send(data);
    });
    //
  }
  // отправляем аяксом
  RegisterPage.prototype.send = function ()
  {
    $('body').addClass('prmu-load');
    $.ajax({
      type: 'POST',
      data: {data: JSON.stringify(arguments[0])},
      success: function (r){
        $('body').html(r).removeClass('prmu-load');
      }
    });
  };
  // собираем данные из инпутов
  RegisterPage.prototype.getData = function ()
  {
    let arForm = $('#register_form').serializeArray(),
        result = {};

    $(arForm).each(function(){
      result[this.name] = this.value;
    })

    return result;
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
                opacity: Math.random()
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

