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
  //
  RegisterPage.prototype.send = function ()
  {
    $('body').addClass('prmu-load');
    $.ajax({
      type: 'POST',
      data: arguments[0],
      success: function (r){
        $('body').html(r).removeClass('prmu-load');
      }
    });
  };
  //
  RegisterPage.prototype.getData = function ()
  {
    let arForm = $('#register_form').serializeArray(),
        arResult = [];

    $(arForm).each(function(){
      arResult[this.name] = this.value;
    })

    return arResult;
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