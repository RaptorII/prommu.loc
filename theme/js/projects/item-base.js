'use strict'
var BaseProgram = (function () {
  BaseProgram.prototype.ID = $('.project-inp').val();
  
	function BaseProgram() {
    this.init();
  }

	BaseProgram.prototype.init = function () {
  	let self = this;
    //
    $('#add-xls').click(function(){ self.addXlsFile(this) });
    $('body').on('click','.xls-popup-btn',function(){
      $('#add-xls-inp').click();
    });
    $('#add-xls-inp').change(function() { self.checkFormatFile(this) });
    // удаление элементов
    $('.project__program').on(
        'click', 
        '.delcity,.delperiod', 
        function(e){ self.ajaxDelIndex(e.target) 
    });

    $('.program__tasks').click(function () {
        $('.tasks__popup').fadeIn();
    });
    $('.tasks__popup-close').click(function () {
        $('.tasks__popup').fadeOut();
    });


  };
  //
  BaseProgram.prototype.ajaxDelIndex = function (e) {
    let self = this, 
      id = e.dataset.id,
      main = $(e).closest('.period-data')[0],
      i = $(e).hasClass('delcity') ? 'c' : 'p',
      query = i==='c'
        ? 'Будет удален город и все связанные данные.\nВы действительно хотите это сделать?'
        : 'Будет удален период и все связанные данные.\nВы действительно хотите это сделать?',
      arItems = i==='c'
        ? $('.program__item')
        : $(main).find('.program__cell-period'),
      arDels = i==='c'
        ? $('[data-city="'+id+'"]')
        : $('[data-period="'+id+'"]'),
      params = {type:'del-index', project:self.ID};

      i==='c' ? params.city=id : params.point=id;

    if(arItems.length==1) {
      i==='c'
      ? MainProject.showPopup('error','onecity')
      : MainProject.showPopup('error','oneperiod');
    }
    else {
      if(confirm(query)) {
        $.ajax({
          type: 'POST',
          url: '/ajax/Project',
          data: {data: JSON.stringify(params)},
          dataType: 'json',
          success: function(r) { 
            if(r.error==true) {
              MainProject.showPopup('error','server');
            }
            else {
              arDels.fadeOut();
              setTimeout(function(){ arDels.remove() },500);
              i==='c'
              ? MainProject.showPopup('success','delcity')
              : MainProject.showPopup('success','delperiod');             
            }
          },
        });
      }
    }
  }
  //
  BaseProgram.prototype.addXlsFile = function () {
    let self = this;

    let html = "<div class='xls-popup' data-header='Изменение программы'>"+
      "1) Необходимо открыть скачаный файл<br>"+
      "2) Исправить существующие данные, либо добавить новые<br>"+
      "3) Загрузить измененный файл<br>"+
      '<span class="xls-popup-err">Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!</span>'+
      "<div class='xls-popup-btn'>ЗАГРУЗИТЬ</div>"+
      "</div>";

    ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
  }
  //      Проверка формата файла .XLS .XLSX
  BaseProgram.prototype.checkFormatFile = function () {
    let self = this,
      $inp = $('#add-xls-inp'),
      $name = $('#add-xls-name'),
      arExt = $inp.val().match(/\\([^\\]+)\.([^\.]+)$/);

    if(arExt[2]!=='xls' && arExt[2]!=='xlsx'){
      $inp.val('');
      $('.xls-popup-err').show();
    }
    else{
      $('.xls-popup-err').hide();
      $('#base-form').submit();
    }
  }
  //
  return BaseProgram;
}());
/*
*
*/
$(document).ready(function () {
	new BaseProgram();
});