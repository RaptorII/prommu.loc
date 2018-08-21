'use strict'
var BaseProgram = (function () {
  BaseProgram.prototype.ID = $('.project-inp').val();
  
	function BaseProgram() {
    this.init();
  }

	BaseProgram.prototype.init = function () {
  	let self = this;
    // удаление элементов
    $('.project__program').on(
        'click', 
        '.delcity,.delperiod', 
        function(e){ self.ajaxDelIndex(e.target) 
    });
  }
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
        : $('[data-period="'+id+'"]');

    if(arItems.length==1) {
      i==='c'
      ? MainProject.showPopup('error','onecity')
      : MainProject.showPopup('error','oneperiod');
    }
    else {
      if(confirm(query)) {
        $.ajax({
          type: 'POST',
          url: '/ajax/123', //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
          data: 'project=' + self.ID + (i==='c' ? '&city=' : '&period=') + id,
          dataType: 'json',
          success: function(r) { },
          complete: function() {
            arDels.fadeOut();
            setTimeout(function(){ arDels.remove() },500);
            i==='c'
            ? MainProject.showPopup('success','delcity')
            : MainProject.showPopup('success','delperiod');
          }
        });
      }
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