'use strict'
$(function(){
  var arSelects = $('.ideas__select'),
      inputTimer = false,
      currentPage = 1;

  if(typeof messNewIdea==='object')
  	showPopup(messNewIdea.header, messNewIdea.mess);

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
					// нужно прятать стрелочку для пунктов с иконкой
					($(this).attr('id')=='sort-params' && +e.target.dataset.id<5)
					? $(name).siblings('b').hide()
					: $(name).siblings('b').show();
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
	var self = this,
		main = this.parentNode,
		cnt = $(this).text(),
		rate = $(this).hasClass('idea__item-rpos') ? 1 : 2;

	if($(main).hasClass('active')) {
		$.ajax({
			type: 'POST',
			url: '/ajax/setideaattrib',
			data: { id:main.dataset.id, rating:rate },
			dataType: 'json',
			success: function(res){
				if(res==='error') {
					showPopup('Вы уже проголосовали','');
				}
				else if(res==='guest') {
					showPopup('Вы не авторизованы','Для голосования необходимо авторизоваться!');
				}
				else{
					showPopup('Ваш голос принят','');
					$(main).removeClass('active');
					cnt = +cnt + 1;
					$(self).text(cnt);
				}
			}
		});
	}
	else{
		showPopup('Вы уже проголосовали','');
	}
  });
  // подгрузка данных при перелистывании
  $('#ideas-content').on('click', '.paging-wrapp a', function(e){
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
  //	AJAX
  //
  function setFilter(){
  	var $content = $('#ideas-content'),
  		$form = $('#ideas-form'),
  		params = $form.serializeArray();

  	if(arguments.length)
  		params.push({name:'page',value:arguments[0]})

	$('#ideas-veil').show();

	$.ajax({
		type: 'GET',
		url: '/ideas',
		data: params,
		success: function(res){
			$content.html(res);
			$('#ideas-veil').hide();
		}
	});
	
  };
  //
  function showPopup(t, m) {
	var html = "<form data-header='" + t + "' class='text-center'>" + m + "</form>";
	ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
  }
});