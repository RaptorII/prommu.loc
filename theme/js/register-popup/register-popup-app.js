$(function(){
  var curDate = new Date(),
    curYear = curDate.getFullYear()-14, // возраст от 14 лет
    curMonth = curDate.getMonth(),
    curDay = Number(curDate.getDate()),
    oldPhone = $('#phone-code').val(),
    keyCode = 0,
    cityTimer = false, // таймер обращения к серверу для поиска городов
    bShowCityList = true, // флаг отображения списка городов
    arInputs = $('.required-inp');

  curDate = new Date(curYear, curMonth, curDay);

  $('body').append('<div class="bg_veil"></div>'); // фон для окна пуш настроек

  $(document).keydown(function(e){ keyCode = e.keyCode }); // код нажатой клавиши

	var timerCity = setInterval(checkCity,100),
		$code = $('[name="__phone_prefix"]'),
		oldPhoneCode = $code.val();

	function checkCity(){
		if(oldPhoneCode!=$code.val()){
			oldPhoneCode = $code.val();
			clearInterval(timerCity);
			var $input = $('#city-input'),
				val = $input.val().charAt(0).toUpperCase() + $input.val().slice(1).toLowerCase(),
				piece = $input.val().toLowerCase();

			for (var i=0; i<arCountries.length; i++)
				if(oldPhoneCode==arCountries[i].phone){
					country = arCountries[i].id_co;
					break;
				}
			$.ajax({
				url: MainConfig.AJAX_GET_VE_GET_CITIES,
				data: 'idco=' + country + '&query=' + val,
				dataType: 'json',
				success: function(res){
					var errCity = true;
					$.each(res.suggestions, function(){ // список городов если что-то введено
						if(this.value.toLowerCase()===piece && this.data!=='man'){ // если введен именно город полностью
							remEr('#city-input');
							$input.val(val);
							errCity = false;
						}
					});
					if(errCity){
						addEr('#city-input');
						$input.val('');
					}
					timerCity = setInterval(checkCity,100);
				}
			});
		}
	}

  $('#phone-code').on('blur',function(){
    var len = $(this).val().replace(/\D+/g,'').length,
        code = $('[name="__phone_prefix"]').val(),
        phoneLen = 10; 

        if(code.length==3 && len<9){ // UKR
          addEr($(this).closest('div'));
          $(this).val('');
        }
        else if(code.length==1 && len<10){ // RF
          addEr($(this).closest('div'));
          $(this).val('');
        }
        else{
          remEr($(this).closest('div')); 
        }
  });
  
  if($('#birthday').is('*')){
    // строим календарь
    Calendar("birthday",curYear,curMonth);
    $("#birthday input").mask("9999");
    // Проверяем дату по году
    $('#birthday input').keyup(function(){ checkDate(this); });
    // Проверяем дату по месяцу
    $('#birthday select').change(function(){ checkDate(this) });
    // Проверяем дату по дню
    $(document).on('click', '#birthday .day', function(){ checkDate(this) }); 
  }
  /*
  *   События
  */     
  //  поиск городов по вводу
  $('#city-input').bind('input focus', function(e){
    var $input = $(this),
        sec = e.type==='focus' ? 1 : 1000;

    bShowCityList = true;
    clearTimeout(cityTimer);
    cityTimer = setTimeout(function(){
      var val = $input.val().charAt(0).toUpperCase() + $input.val().slice(1).toLowerCase(),
          piece = $input.val().toLowerCase(),
          main = $input.closest('span'),
          content = '',
          arCities = [];

      $input.val(val); //  город с большой буквы

      if(val===''){ // если ничего не введено
        $(main).addClass('load'); // показываем загрузку
        $.ajax({
          url: MainConfig.AJAX_GET_VE_GET_CITIES,
          data: 'idco=' + country + '&query=' + val,
          dataType: 'json',
          success: function(res){
            $.each(res.suggestions, function(){ 
              arCities.push(this.value);
            });// список городов если ничего не введено
            arCities.length>0  
            ? $.each(arCities, function(){ content += '<li>'+this+'</li>' })         
            : content = '<li class="emp">Список пуст</li>';
            $('#city-list').empty().append(content);
            if(bShowCityList)
              $('#city-list').show();
            $(main).removeClass('load');
          }
        });
      }
      else{
        $(main).addClass('load');
        $.ajax({
          url: MainConfig.AJAX_GET_VE_GET_CITIES,
          data: 'idco=' + country + '&query=' + val,
          dataType: 'json',
          success: function(res){
            $.each(res.suggestions, function(){ // список городов если что-то введено
              word = this.value.toLowerCase();
              if(word===piece && this.data!=='man'){ // если введен именно город полностью
                remEr('#city-input');
                arCities.push(this.value);
              }
              else if(word.indexOf(piece)>=0 && this.data!=='man')
                arCities.push(this.value);
            });
            arCities.length>0  
            ? $.each(arCities, function(){ content += '<li>'+this+'</li>' })         
            : content = '<li class="emp">Список пуст</li>';
            $('#city-list').empty().append(content);
            if(bShowCityList)
              $('#city-list').show();
            $(main).removeClass('load');
          }
        });
      }
    },sec);
  });
  //  выбор города из списка
  $(document).on('click', '#city-list li', function(){
    if(!$(this).hasClass('emp')){
      $('#city-input').val($(this).text());
      $('#city-list').hide();
      bShowCityList = false;
      remEr('#city-input');
    }
    else{
      addEr('#city-input');
    }
  });
  //  закрываем список городов
  $(document).click(function(e){
	if(!$('#city-input').is(e.target) && !$(e.target).closest('#city-list').length){
		var $input = $('#city-input'),
			val = $input.val().charAt(0).toUpperCase() + $input.val().slice(1).toLowerCase(),
			piece = $input.val().toLowerCase();

		$.ajax({
			url: MainConfig.AJAX_GET_VE_GET_CITIES,
			data: 'idco=' + country + '&query=' + val,
			dataType: 'json',
			success: function(res){
				var errCity = true;
				$.each(res.suggestions, function(){ // список городов если что-то введено
					if(this.value.toLowerCase()===piece && this.data!=='man'){ // если введен именно город полностью
						remEr('#city-input');
						$input.val(val);
						errCity = false;
					}
				});
				if(errCity){
					addEr('#city-input');
					$input.val('');
				}
			}
		});
    bShowCityList = false;
		$('#city-list').hide();
	}
  });
  //  окно пуш настроек
  $('#push-props').click(function(){ pushProps() });
  $('#push-checkbox').change(function(){ if(!$(this).prop('checked')) pushProps() });
  $('body').on('change', '#all', function(){
    $(this).prop('checked') ? $('.pp-form__all-props').fadeOut() : $('.pp-form__all-props').fadeIn();
  });
  //  выбор всех пуш настроек
  $('#push-checkbox').change(function(){ $(this).prop('checked') ? $('#push-props').hide() : $('#push-props').show() });
  //  отправка пуш настроек на основную форму
  $('.bg_veil').click(function(){ sendPushData() });
  $('.push-popup__form').submit(function(){ return sendPushData() });
  //  проверка полей
  $('.required-inp').change(function(){ 
    checkField(this);
    error = false;
    $.each(arInputs, function(){ if(!checkFieldEasy(this)) error = true; });
    (!error && !$('.error').length) ? remEr('#applicant-btn','off') : addEr('#applicant-btn','off');
  });
  // проверка перед отправкой формы
  $('#popup-form').submit(function(){
    $.each(arInputs, function(){ checkField(this) });
    if($('.error').length>0){
      $('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
      addEr('#applicant-btn','off');
      return false;
    }
    else{
      remEr('#applicant-btn','off');
    }   
  });
  //  таймер проверки загрузки фото
  setInterval(function (e){
    if($('#HiLogo').val() != ''){
      $('#applicant-img').attr('src','/images/applic/'+$('#HiLogo').val()+'400.jpg');
      remEr('.rp-content1__logo-img');
      error = false;
      $.each(arInputs, function(){ if(!checkFieldEasy(this)) error = true; });
      (!error && !$('.error').length) ? remEr('#applicant-btn','off') : addEr('#applicant-btn','off');
    }
  }, 1000);
  /*
  *     Финкции
  */
  //  отображение пуш настроек
  function pushProps(){
    $('.push-popup__form').fadeIn();
    $('.bg_veil').fadeIn();
    $('html, body').animate({scrollTop: 0},500);   
  }
  //  отправка пуш настроек
  function sendPushData(){
    $('.push-popup__form').fadeOut();
    $('.bg_veil').fadeOut();
    var arInputs = [
      'all', 
      'rate', 
      'respond',
      'mess',
      'workday',
    ];
    $.each(arInputs, function(){
      $sourse = $('#'+this);
      $receiver = $('#popup-form [name='+this+']');
      $sourse.is(':checked') ? $receiver.val(2) : $receiver.val(0);
    });
    return false;
  } 
  // построение календаря
  function Calendar(id, year, month) {
    var Dlast = new Date(year,month+1,0).getDate(),
        D = new Date(year,month,Dlast),
        DNlast = D.getDay(),
        DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
        calendar = '<tr>',
        m = document.querySelector('#'+id+' option[value="' + D.getMonth() + '"]'),
        g = document.querySelector('#'+id+' input');
    if(DNfirst != 0){ for(var  i = 1; i < DNfirst; i++) calendar += '<td>' }
    else{ for(var  i = 0; i < 6; i++) calendar += '<td>' }
    for(var  i = 1; i <= Dlast; i++) {
      if(
        i == new Date().getDate() && 
        D.getFullYear() == new Date().getFullYear() && 
        D.getMonth() == new Date().getMonth()
      ){ calendar += '<td class="day today">' + i }
      else{ calendar += '<td class="day">' + i }
      if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0){ calendar+='<tr>' }
    }
    for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
    document.querySelector('#'+id+' tbody').innerHTML = calendar;
    g.value = D.getFullYear();
    m.selected = true;
    if(document.querySelectorAll('#'+id+' tbody tr').length < 6){
        document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
    }
  }
  //  проверка корректности даты
  function checkDate(e){
    var table =  $(e).closest('table').prop('id'),
      idTable = '#'+table,
      y = Number($(idTable+' input').val()),
      m = Number($(idTable+' select').val());

    if($(e).is(idTable+' input') || $(e).is(idTable+' select')){
      var selectDay = $(idTable).find(idTable+' .day.select');
      d = (selectDay.length>0 ? Number($(selectDay).text()) : Number(curDate.getDate()));
      elemErr = e;
    }   
    if($(e).is(idTable+' .day')){
      d = Number($(e).text());
      elemErr = $(e).closest('.pr-card__calendar');
    }
    newDate = new Date(y, m, d);

    if(Math.ceil((curDate - newDate) / (1000 * 60 * 60 * 24)) >= 1){ // дата должна быть меньше сегодняшней
      remEr(idTable+' input');
      remEr(idTable+' select');
      remEr($(e).closest('.pr-card__calendar'));        
      if($(e).is(idTable+' .day')){ 
        $.each($(idTable+' .day'), function(){ $(this).removeClass('select') });
        $(e).addClass('select'); 
        str = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + y;
      }
      else{
        if(String(y).length==4){ // 4 цифры в году
          Calendar(table,y,m);
          str = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + y;
        }
        else{
          addEr(elemErr);
          str = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.XXXX';
        }
      }        
      $(idTable+'-res').text(str);
      $(idTable+'-inp').val(str);
    }
    else{
      addEr(elemErr);
    }
  }  
  //  визуализация ошибок
  function addEr(e, style='error'){ $(e).addClass(style) }
  function remEr(e, style='error'){ $(e).removeClass(style) }
  //  проверка полей
  function checkField(e){
    var val = $(e).val(), erBlock = e;
    if($(e).is('#HiLogo')){ erBlock='.rp-content1__logo-img' }
    if($(e).is('#birthday-inp')){ erBlock='.pr-card__calendar' }
    if($(e).is('#phone-code')){ erBlock='.country-phone' }   
    (val=='' || val==null) ? addEr(erBlock) : remEr(erBlock);
  }
  function checkFieldEasy(e){
    if($(e).val()=='' || $(e).val()==null) return false;
    else return true;
  }
});