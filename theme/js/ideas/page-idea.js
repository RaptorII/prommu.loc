'use strict'
$(function(){
	var arSelects = $('.ideas__select'),
		ideaId = $('.idea__item')[0],
		currentPage = 1;

	if(ideaId!==undefined)
		ideaId = ideaId.dataset.id;
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
		e.preventDefault();
		var text = this.previousElementSibling;

		if(!text.value.length) {
			$(text).addClass('error');
			return false;
		}
		$.ajax({
			type: 'POST',
			url: '/ajax/setideaattrib',
			data: { id:ideaId, comment:text.value },
			dataType: 'json',
			success: function(res){
				$('#comment-form').fadeOut();
				$('.idea__set-comment').fadeIn();
				showPopup('Комментарий отправлен','Комментарий успешно отправлен и в ближайшее время появится на сайте');
				$(text).removeClass('error').val('');
			}
		})
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
	var main1 = document.querySelector('.idea__item-rating'),
		cnt = $(e).text(),
		rate = pos==='rpos' ? 1 : 2; 

	if($(main1).hasClass('active')){
		$.ajax({
			type: 'POST',
			url: '/ajax/setideaattrib',
			data: { id:ideaId, rating:rate },
			dataType: 'json',
			success: function(r){
				if(r.type==='rempos')
					pos = 'rpos';
				if(r.type==='remneg')
					pos = 'rneg';
				if(r.type==='rempos' || r.type==='remneg'){
					showPopup('Выбор изменен',r.mess);
					cnt = $('.idea__set-'+pos).text();
					cnt = +cnt - 1;
					$('.idea__set-'+pos).text(cnt);
					$('.idea__item-'+pos).text(cnt);
				}
				if(r.type=='create'){
					showPopup('Ваш голос принят',r.mess);
					cnt = +cnt + 1;
					$('.idea__set-'+pos).text(cnt);
					$('.idea__item-'+pos).text(cnt);
				}
				if(r.type=='guest'){
					showPopup('Вы не авторизованы','Для голосования необходимо авторизоваться!');
				}
			}
		})		
	}
	else{
		showPopup('Вы не авторизованы','Для голосования необходимо авторизоваться!');
	}
  }
  // подгрузка данных при перелистывании
  $('#comment-list').on('click', '.paging-wrapp a', function(e){
  	var li = $(e.target).closest('li');
  	e.preventDefault();
  	if($(li).hasClass('previous'))
  		currentPage--;
  	if($(li).hasClass('page'))
  		currentPage = $(e.target).text();
  	if($(li).hasClass('next'))
  		currentPage++;

  	setFilter(currentPage);
  });
  //
  function setFilter() {
  	var $content = $('#comment-list'),
  		$form = $('#comments-sort'),
  		params = $form.serializeArray(),
  		idea = $('[name="idea"]').val();

  	if(arguments.length)
  		params.push({name:'page',value:arguments[0]})


	$('#ideas-veil').show();

	$.ajax({
		type: 'GET',
		url: '/ideas/'+idea,
		data: params,
		success: function(res){
			$content.html(res);
			$('#ideas-veil').hide();
		}
	});
  };
});