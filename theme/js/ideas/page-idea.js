'use strict'
$(function(){
	var arSelects = $('.ideas__select');

	// открываем селекты
	$('.ideas__select').click(function(e){
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
						isSortSelect && $(name).siblings('b').hide() && setTimeout(setFilter,500);
					}
					else{
						$(arLi[i]).removeClass('active');
					}
				}
				$(input).val(e.target.dataset.id);
			}
			$(list).fadeOut();

		}
	});
	// закрываем селекты
	$(document).click(function(e){
		if(!$(e.target).is('.ideas__select') && !$(e.target).closest('.ideas__select').length)
			$('.ideas__select').find('ul').fadeOut();
	});
	// отправляем форму
	$('#new-idea-btn').click(function(e){
		var $name = $('#new-idea-name'),
			$text = $('#new-idea-text'),
			$type = $('#new-idea-type');

		e.preventDefault();

		if($name.val()!=='' && $text.val()!=='' && $type.val()!==''){
			$('#new-idea').submit();
		}
		else{
			$name.val()==='' ? $name.addClass('error') : $name.removeClass('error');
			$text.val()==='' ? $text.addClass('error') : $text.removeClass('error');
			$type.val()==='' 
			? $type.parent('.ideas__select').addClass('error')
			: $type.parent('.ideas__select').removeClass('error');
		}
	});
	// добавляем коммент
	$('.idea__set-comment').click(function(){
		$(this).fadeOut();
		$('#comment-form').css({display:'flex'});
	});
	// отправляем коммент
	$('#add-comment').click(function(e){
		var text = this.previousElementSibling;

		if(!text.value.length) {
			$(text).addClass('error');
		}
		else {
			$('#comment-form').fadeOut();
			$('.idea__set-comment').fadeIn();
			showPopup('Комментарий отправлен','Комментарий успешно отправлен и в ближайшее время появится на сайте');
			// Отправка коммента
			$(text).removeClass('error').val();
		}
		e.preventDefault();//!!!!!!!!!!!!!!
	});
	// голосование
	$('.idea__set-rpos, .idea__item-rpos').click(function(){ setRating('rpos',this) });
	$('.idea__set-rneg, .idea__item-rneg').click(function(){ setRating('rneg',this) });
	//
	$('#sort-params')
  //
  //
  //
  function showPopup(t, m) {
	var html = "<form data-header='" + t + "'>" + m + "</form>";
	ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
  }
  //
  function setRating(pos, e) {
	var main1 = $('.idea__item-rating'),
		main2 = $('.idea__set-rating'),
		cnt = $(e).text();

	if($(main1).hasClass('active') && $(main2).hasClass('active')){
		showPopup('Ваш голос принят','');
		$(main1).removeClass('active');
		$(main2).removeClass('active');
		cnt = +cnt + 1;
		$('.idea__set-'+pos).text(cnt);
		$('.idea__item-'+pos).text(cnt);
	}
	else{
		showPopup('Вы уже проголосовали','');
	}
  }
  //
  function setFilter() {
	$('#ideas-veil').show();
	confirm('Здесь подтянуться данные');
	$('#ideas-veil').hide();
  };
});