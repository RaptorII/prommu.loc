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