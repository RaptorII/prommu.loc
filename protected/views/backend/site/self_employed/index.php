<h3><?=$this->pageTitle?></h3>
<div class="row">
  <div class="col-xs-12">
    <form id="self_employed_form">
      <div class="d-label">
        <div class="inn_block">
          <div class="btn btn-success">+</div>
          <div class="input"><input type="text" name="inn[]" class="form-control"></div>
        </div>
      </div>
      <br>
      <span class="btn btn-success d-indent" id="check_inn">Проверить</span>
    </form>
  </div>
</div>
<style>
  /* */
  .inn_block{
    max-width: 100%;
    position: relative;
    padding-left: 40px;
  }
  .inn_block input{
    width: 200px;
    display: inline-block;
  }
  .inn_block .input span{
    padding-left: 5px;
    display: inline-block;
  }
  .inn_block .btn-success{
    min-width: 34px;
    position: absolute;
    left: 0px;
  }
  .form-control.error{ border-color: #F44336 }
  .inn_block .input{ position: relative }
  .input.load:before{
    content: '';
    width: 30px;
    height: 30px;
    position: absolute;
    top: 2px;
    left: 203px;
    background: url(/theme/pic/vacancy/loading.gif) no-repeat;
    background-size: contain;
  }
</style>
<script>
  'use strict'
  jQuery(function($){
    var INNLenth = 12;
    $('.inn_block').on('input','input',function(){
      var v = this.value.replace (/\D/, '').substr(0,INNLenth);
      $(this).val(v);
    });
    $('.inn_block').on('blur','input',function(){
      if(this.value.length<3)
        this.value = '';
    });
    $('.inn_block .btn').click(function(){
      if($(".inn_block input").length==5)
        return;
      $('.inn_block').append('<div class="input"><input type="text" name="inn[]" class="form-control">');
    });
    //
    $('#check_inn').on('click',function(){
      var arInput = $(".inn_block input"),
          arValid = [];

      $.each(arInput, function(){
        if(this.value.length!=INNLenth)
        {
          $(this).addClass('error');
        }
        else
        {
          $(this).removeClass('error');
          arValid.push(this);
        }
      });

      if(arValid.length)
      {
        var cnt = 0;
        ajaxRequest(arValid, cnt);
        $('#check_inn').hide();


        /*$.ajax({
          type: 'POST',
          url: '/admin/ajax/Self_employed',
          data: $('#self_employed_form').serialize(),
          success: function(r)
          {
            r = JSON.parse(r);
            console.log(r);
            let message = '';

            if(r.error==true)
            {
              $(parent).removeClass('load');
              message = 'Непредвиденная ошибка. Пожалуйста обратитесь к администратору';
            }
            else
            {
              $(parent).removeClass('load');
              message = r.response.message;
            }
            $(parent).remove('span').append('<span>' + message + '</span>');
            if($(arValid[cnt+1]).is('*'))
            {
              ajaxRequest(arValid, cnt+1);
            }
            else
            {
              $('#check_inn').show();
            }
          }
        });*/
      }
    });

    var ajaxRequest = function(arValid, cnt)
    {
      let self = arValid[cnt],
          parent = $(self).closest('.input');

      $(parent).addClass('load');
      $.ajax({
        type: 'POST',
        url: '/admin/ajax/Self_employed',
        data: 'inn=' + $(self).val(),
        success: function(r)
        {
          r = JSON.parse(r);
          let message = '';

          if(r.error==true)
          {
            $(parent).removeClass('load');
            message = 'Непредвиденная ошибка. Пожалуйста обратитесь к администратору';
          }
          else
          {
            $(parent).removeClass('load');
            message = r.response.message;
          }
          $(parent).remove('span').append('<span>' + message + '</span>');
          if($(arValid[cnt+1]).is('*'))
          {
            ajaxRequest(arValid, cnt+1);
          }
          else
          {
            $('#check_inn').show();
          }
        }
      });
    }
  });
</script>