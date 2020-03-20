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
    }
    Hinter.options.contentAsHTML = true;
    Hinter.bind('.tooltip', {side:'right'});
    // переключение на форму редактирования
    $('.module').on('click','.personal__area--capacity-edit',function(){
      let main = $(this).closest('.module'),
          info = $(main).find('.module_info'),
          form = $(main).find('.module_form');

      $(info).hide();
      $(form).fadeIn();
      self.changeLabelWidth();
    });
    // переключаем обратно на информацию
    $('.module').on('click','.personal__area--capacity-cancel',function(){
      let main = $(this).closest('.module'),
        info = $(main).find('.module_info'),
        form = $(main).find('.module_form');

      $(form).hide();
      $(info).fadeIn();
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
    new InitSelect('#posts2');
    new InitSelect('#experience2');
    new InitSelect('#work_type2');
    new InitSelect('#hcolor');
    new InitSelect('#hlen');
    new InitSelect('#ycolor');
    new InitSelect('#chest');
    new InitSelect('#waist');
    new InitSelect('#thigh');
    new InitSelect('#self_employed');
    new InitSelect('#salary');
    new InitSelect('#salary_time');


    new InitNicEditor('#requirements','#requirements_panel');
    new InitNicEditor('#duties','#duties_panel');
    new InitNicEditor('#conditions','#conditions_panel');
    // проверяем обязательные поля
    new CheckRequiredFields(self);
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
  return EditVacancy;
}());
/**
 *
 */
$(document).ready(function () {
  new EditVacancy();
});