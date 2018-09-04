'use strict'
var SelectUsers = (function () {
	function SelectUsers() {
    this.init();
  }
  //
	SelectUsers.prototype.init = function () {
  	let self = this;
    // удаление элементов
    $('#save-btn').click(function(){
    	let $form = $('#select-form'),
    		arParams = $form.serializeArray();

    	!arParams.length
    	? MainProject.showPopup('notif','addition')
    	: $form.submit();
    });
    //  отображение фильтра
    $('.prommu__universal-filter__button').click(function(){
      if($('.project__header-filter').is(':visible'))
        $('.project__header-filter').fadeOut();
      else
        $('.project__header-filter').fadeIn();
      $(this).toggleClass('u-filter__close');
    });
  }
  //
  return SelectUsers;
}());
/*
*
*/
$(document).ready(function () {
	new SelectUsers();
});