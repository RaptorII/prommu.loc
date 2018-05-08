'use strict'
$(function(){
  var arSelects = $('.ideas__select'),
      inputTimer = false;

  // открываем селекты
  arSelects.click(function(e){
	var name = $(this).find('span')[0],
		list = $(this).find('ul')[0],
		arLi = $(list).find('li'),
		input = $(this).find('input')[0];

	if($(e.target).is(name)){ // открываем список
		$(list).fadeIn();
	}
	else{
		if($(e.target).is('li')){ // выбираем из списка
			for(var i=0, n=arLi.length; i<n; i++){
				if($(e.target).is(arLi[i])){
					$(arLi[i]).addClass('active');
					$(name).html($(arLi[i]).html());
					var isSortSelect = $(this).attr('id')=='sort-params';
					isSortSelect && $(name).siblings('b').hide();
				}
				else{
					$(arLi[i]).removeClass('active');
				}
			}
			$(input).val(e.target.dataset.id);
			setTimeout(setFilter,500);
		}
		$(list).fadeOut();

	}
  });
  // закрываем селекты
  $(document).click(function(e){
	for(var i=0, n=arSelects.length; i<n; i++)
		if(!$(e.target).is(arSelects[i]) && !$(e.target).closest(arSelects[i]).length)
			$(arSelects[i]).find('ul').fadeOut();
  });
  // вводим название
  $('.ideas__search input').on('input',function(){
	clearTimeout(inputTimer);
	inputTimer = setTimeout(function(){
		setFilter();
	},500);	
  });
  // голосование
  $('.idea__item-rpos, .idea__item-rneg').click(function(){
	var main = this.parentNode,
		cnt = $(this).text();

	if($(main).hasClass('active')){
		showPopup('Ваш голос принят','');
		$(main).removeClass('active');
		cnt = +cnt + 1;
		$(this).text(cnt);
	}
	else{
		showPopup('Вы уже проголосовали','');
	}
  });
  //
  //	AJAX
  //
  function setFilter(){
	$('#ideas-veil').show();
	confirm('Здесь подтянуться данные');
	$('#ideas-veil').hide();
  };
  //
  function showPopup(t, m) {
	var html = "<form data-header='" + t + "'>" + m + "</form>";
	ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
  }
});