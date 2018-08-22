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

    	arParams.length==2
    	? MainProject.showPopup('notif','addition')
    	: $form.submit();
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