'use strict'
/**
 *
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
      Hinter.bind('.js-g-hashint.-js-g-hintright', { side: 'right' });
    }

    $('.module').on('click','.personal__area--capacity-edit',function(){
      let main = $(this).closest('.module'),
          info = $(main).find('.module_info'),
          form = $(main).find('.module_form');

      $(info).hide();
      $(form).fadeIn();
      self.changeLabelWidth();
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
    // проверяем обязательные поля
    new CheckRequiredFields(self);
/*
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


*/
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
  /*EditVacancy.prototype.sendForm = function ()
  {
    console.log(arguments);
  };*/
  //

  return EditVacancy;
}());
/**
 *
 */
$(document).ready(function () {
  new EditVacancy();
});