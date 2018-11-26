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

    $('.tasks__popup-close').click(function () {
        $('.tasks__popup').fadeOut();
    });

    $(".content-block").on('click', '.tasks__count', function() {

        let project= $(this).data('popup-project');
        let user = $(this).data('popup-user');
        let point = $(this).data('popup-point');
        let date = $(this).data('popup-date');
        let type = 'userdata';

        var data = self.initData(project,user,point,date, type);

        $.ajax({
            type: 'GET',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value){
                if(value.tasks){
                    self.popupShow(value);
                }
            }
        });
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
  };


    BaseProgram.prototype.popupShow = function (d) {

        var data = new Object();
        data = d.tasks;
        var user = d.user;
        var size = Object.keys(data).length;
        if(size>0){
            this.popupClear();
            var html = '';

            $.each(data, function (i, e) {
                html+='<tr>';
                html+='<td>'+ data[i].date+'</td>';
                html+='<td>'+ data[i].name+'</td>';
                html+='<td>'+ data[i].text+'</td>';
                html+='</tr>';
            });
            var popup = $(".tasks__popup");
            $(popup).find(".popup__table tbody").append(html);
            $(popup).find(".popup__user-name").html(user.firstname);
            $(popup).find(".popup__user-secondname").html(user.lastname);
            $(popup).find(".popup__content-logo").prop("src",user.logo);
            var status = user.is_online;
            var status_html = '';

            if(status==1){
                status_html = "<span class='geo__green'>● активен</span>";
            }else{
                status_html = "<span class='geo__red'>● неактивен</span>";
            }
            $(popup).find(".popup__user-status").html(status_html);

            $('.tasks__popup').fadeIn();
        }
    };

    BaseProgram.prototype.popupClear = function () {
        var popup = $(".tasks__popup");
        $(popup).find(".popup__table tbody").html('');
        $(popup).find(".popup__user-name").html('');
        $(popup).find(".popup__user-secondname").html('');
        $(popup).find(".popup__user-status").html('');
        $(popup).find(".popup__content-logo").prop("src",'');
    };

    BaseProgram.prototype.initData = function (project, user, point, date, type) {
        var data_object = {};

        project = project.toString();
        user = user.toString();
        point = point.toString();
        date = date.toString();
        type = type.toString();

        if(project.length>0) {
            data_object.project = project;
        }
        if(user.length>0) {
            data_object.user = user;
        }
        if(point.length>0){
            data_object.point = point;
        }
        if(date.length>0) {
            data_object.date = date;
        }
        if(type.length>0) {
            data_object.type = type;
        }

        return data_object;
    };
  //
  return BaseProgram;
}());
/*
*
*/
$(document).ready(function () {
	new BaseProgram();
});