'use strict'
/**
 *
 * @type {RegisterPage}
 */
var ApplicantEditPage = (function () {
  //
  ApplicantEditPage.prototype.EMAIL = $('#email_input').val();
  ApplicantEditPage.prototype.PHONE = $('#phone-code').val();
  ApplicantEditPage.prototype.EMAIL_CONFIRM = $('#email-block .d-none').is('*');
  //ApplicantEditPage.prototype.PHONE_CONFIRM = $('#phone-block .d-none').is('*');
  ApplicantEditPage.prototype.URL_CODE_REQUEST = '/ajax/restorecode';
  ApplicantEditPage.prototype.URL_CODE_CHECK = '/ajax/confirm';
  ApplicantEditPage.prototype.URL_EMAIL_CHECK = '/ajax/emailVerification';
  ApplicantEditPage.prototype.RUSSIAN_PHONE_LEN = 10;
  ApplicantEditPage.prototype.CONTENT_POSITION = $('.epa__logo-name-list').offset().top - 15;
  ApplicantEditPage.prototype.ABOUT_LEN = 2000;
  ApplicantEditPage.prototype.KEY = false;
  ApplicantEditPage.prototype.LANGS = $('#epa-list-language label').get();
	//
  function ApplicantEditPage()
  {
    this.init();
  }
  //
  //
  ApplicantEditPage.prototype.init = function ()
	{
    let self = this;
    // код клавиши
    $(document).keydown(function(e){ self.KEY = e.keyCode });
		// проверка имени
		$('#main-module [name="name"]')
			.on(
				'input',
				function(){self.checkName(this)}
			)
			.on(
				'change',
				function(){
          $(this).val($(this).val().trim());
          self.checkName(this);
				}
			);
		// проверка фамилии
    $('#main-module [name="lastname"]')
			.on(
				'input',
				function(){self.checkName(this)}
      )
			.on(
				'change',
				function(){
					$(this).val($(this).val().trim());
					self.checkName(this);
				}
			);
		// проверка телефона
    $('#phone-code')
			.on(
				'input focus change paste',
				function(e){ self.checkPhone(e.type) }
			);
    // проверка email
    $('#email_input')
      .on(
        'input focus change paste',
        function(e){ self.checkEmail(e.type) }
      );
    // проверка копипаста
    $('#main-module [name="name"]').bind('paste',function() {
      $(this).val($(this).val().trim());
    	self.checkName(this);
    });
    $('#main-module [name="lastname"]').bind('paste',function() {
      $(this).val($(this).val().trim());
    	self.checkName(this);
    });
    // подтверждение контакта
		$('#phone-block .prmu-btn, .confirm-user.phone').click(function(){
			self.confirmCodeRequest('phone');
		});
    $('#email-block .prmu-btn, .confirm-user.email').click(function(){
      self.confirmCodeRequest('email');
    });
    $('#phone-confirm .prmu-btn').click(function(){
      self.confirmCodeCheck('phone');
    });
    $('#email-confirm .prmu-btn').click(function(){
      self.confirmCodeCheck('email');
    });
    // добавление нового телефона
    $('#add_phone').click(function(){
      let len = $('#contacts-module .epa__add-phone').length,
        	html = $('#add-additional-phone').html().replace(/NEWNUM/g, len),
					block = (len ? '#contacts-module .epa__add-phone:eq(-1)' : '#email-confirm');

      if(len==9)
			{
				$(this).hide();
			}
      $(block).after(html);
    });
    // перемещение содержания
		$(window).on('resize scroll', self.scrollContentList);
    self.scrollContentList();
    // ввод данных 'О себе'
    $('.epa__textarea').on('input', function(){
			let v = $(this).val();
      if(v.length>self.ABOUT_LEN)
			{
        $(this).val(val.substr(0, self.ABOUT_LEN));
			}
		});
    $('.epa__textarea').on('change', function(){
      let v = $(this).val().trim().substr(0, self.ABOUT_LEN);
      self.error(this,!v.length)
    });
    // проверка доп телефона
    $(document).on(
      'input',
      '.epa__phone',
      function(){
        let v = $(this).val().replace(/[^0-9-)(+]/gi,'');

        $(this).val(v.substr(0, self.RUSSIAN_PHONE_LEN));
      });
    // проверка периода времени работы
    $(document).click(function(e){
      if(
      	$(e.target).closest('.epa__period-error').length
				||
				$(e.target).is('.epa__period-error')
			)
      {
        let main = $(e.target).closest('.epa__period'),
						input = $(main).find('.profile__field-input');

				$(input).focus();
				self.error(main, false);
      }
    });
    $(document).on(
    	'blur',
			'.epa__period .profile__field-input',
			function(e){
				let v = $(e.target).val().trim();

				setTimeout(function(){ // поле установки подходящего времени в дни недели
					if(v.length>8)
					{ // 8 минимум
						let arVals = v.split('до');
						if(arVals.length==2)
						{
							let from = Number(self.getNum(arVals[0])),
									to = Number(self.getNum(arVals[1]));

							self.error(e.target, (from>23 || to>24 || from>=to)); // проверяем правильность временного промежутка
						}
					}
					else if(!v.length)
					{
						self.error(e.target, true);
					}
					else
					{
						self.error(e.target, false);
					}
				},100);
    });
    // инициализация календаря
    $("#birthday").datepicker({
      maxDate: '-14y',
      changeYear: true,
      yearRange: "1970:2005",
      beforeShow: function(){ $('#ui-datepicker-div').addClass('custom-calendar') }
    });
    // проверка корректности даты
		$('#birthday').change(function(){
			if(this.value.length)
			{
				let objDate = $(this).datepicker('getDate'),
						checkYear = new Date().getFullYear() - 14,
						d = String(objDate.getDate()),
						m = String(objDate.getMonth()+1),
						y = objDate.getFullYear();

				d = d.length<2 ? ('0'+d) : d;
				m = m.length<2 ? ('0'+m) : m;
				y = checkYear<y ? checkYear : y;

				this.value = d + '.' + m + '.' + y;
				if(this.value=='01.01.1970')
				{
					self.error(this, true);
					this.value='';
				}
				else
				{
          self.error(this, false);
				}
			}
			else
			{
        self.error(this, true);
			}
		});
    //	устанавливаем маски
    $(document).on(
      'input',
      '.epa__period input',
      function(){ self.checkPeriod(this) }
    );
    //  выбор языка
    $('#sel-lang').on('input', function () {
      let msg = $(this).val();

      $.each(
      	self.LANGS,
				function( key, value )
				{
					msg = msg.toLowerCase();
					value = (value.firstChild.data).toLowerCase();

					if (value.search(msg)==(-1))
					{
						$(this).parent().hide();
					}
					else if (!msg.toLowerCase().length)
					{
						$(this).parent().show();
					}
				});
    });
		//
    $('#epa-veil-language').click( function(){
      $('#sel-lang').focus();
    });
  };
  //
  // маска для имени и фамилии
  ApplicantEditPage.prototype.checkName = function (input) {
    if(!$(input).is('*'))
      return true;

    let v = $(input).val().replace(/[^ a-zA-ZА-Яа-яЁё]/gi,'');

    $(input).val((v.charAt(0).toUpperCase() + v.slice(1).toLowerCase()));

    return this.error(input, !$(input).val().trim().length);
	};
  //
	// Проверка телефона
  ApplicantEditPage.prototype.checkPhone = function (event)
	{
    let self = this,
				$inp = $('#phone-code'),
				v = $inp.val(),
				isPhone = self.getNum(v).length == self.RUSSIAN_PHONE_LEN;

    if(event!='input')
    {
      self.error($inp, !isPhone);
      if(event=='focus' && !isPhone)
      {
        $inp.val('');
      }
      else if(event=='change' && v!==self.PHONE && isPhone)
			{
        self.setConfirmMark('phone',true);
				$('#phone-block .profile__col').eq(1).show();
        self.EMAIL_CONFIRM
          ? $('#phone-block .clearfix').hide()
          : $('#phone-block .clearfix').show();
      }
      else if(event=='paste')
			{
				setTimeout(function(){
					if($('#phone-code').val()!==self.PHONE && isPhone)
					{
            self.setConfirmMark('phone',true);
            $('#phone-block .profile__col').eq(1).show();
            self.EMAIL_CONFIRM
              ? $('#phone-block .clearfix').hide()
              : $('#phone-block .clearfix').show();
					}
				},50);
			}
    }
    else
    {
      self.error($inp, !isPhone);
    }
  };
  //
	// Запрос на уникальность email
  ApplicantEditPage.prototype.checkEmail = function (event)
  {
    let self = this,
      	$inp = $('#email_input'),
      	v = $inp.val().trim(),
      	pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
				error = false;

    if(event=='input')
		{
      self.error($inp, !v.length);
		}
		else if(event=='focus')
		{
			if(!$('#email-field').hasClass('erroremail'))
			{
        self.error($inp, !pattern.test(v));
			}
		}
		else
    {
      error = !pattern.test(v);
			if(!error) // похоже на почту
			{
				if(v!=self.EMAIL) // проверка на уникальность
				{
          MainScript.stateLoading(true);
					$.ajax({
						type: 'POST',
						url: self.URL_EMAIL_CHECK,
						data: 'nemail='+v+'&oemail='+self.EMAIL,
						dataType: 'json',
						success: function(ajaxError)
						{
              MainScript.stateLoading(false);
							self.error($inp, ajaxError);
							if(ajaxError)
							{
								$('#email-field').addClass('erroremail');
                self.setConfirmMark('email',false);
                $('#email-block .profile__col').eq(1).hide();
							}
							else
							{
								$('#email-field').removeClass('erroremail');
                self.setConfirmMark('email',true);
                $('#email-block .profile__col').eq(1).show();
								self.EMAIL = v;
                self.EMAIL_CONFIRM = false;
                $('#phone-block .clearfix').show();
							}
							//errorFieldName('#epa-email',r);
						}
					});
				}
				else // введена текущая почта
				{
          $('#email-field').removeClass('erroremail');
          self.error($inp, error);
				}
			}
			else // ошибка по шаблону
			{
        self.error($inp, error);
        $('#email-field').removeClass('erroremail');
			}
		}
  };
  //
  // Запрос кода для подтверждения
  ApplicantEditPage.prototype.confirmCodeRequest = function (e)
  {
    let self = this,
				v = e==='email'
					? $('#email_input').val()
					: ($('[name="__phone_prefix"]').val() + $('#phone-code').val()),
				input = e==='email' ? $('#email_input') : $('#phone-code'),
				parent = $(input).closest('.profile__field'),
      	check = e==='email'
					? $('#email_input').val().trim()
					: $('#phone-code').val().trim();

		if(check.length && !$(parent).hasClass('error'))
		{
      MainScript.stateLoading(true);
			$.ajax({
				type: 'POST',
				url: self.URL_CODE_REQUEST,
				data: e + '='+ v,
				success: function(){
          MainScript.stateLoading(false);
					self.showMessage('На ' + (e==='email' ? 'почту' : 'телефон')
						 + ' выслан код для подтверждения. Введите его в поле "Проверочный код"');
          $('#' + e + '-block').hide();
          $('#' + e + '-confirm').fadeIn();
				}
			});
		}
		else
		{
			self.error(input);
		}
  };
  //
	// проверка введенного кода
  ApplicantEditPage.prototype.confirmCodeCheck = function (e)
  {
    let self = this,
				p = e==='email',
      	value = p
					? $('#email_input').val()
					: ($('[name="__phone_prefix"]').val() + $('#phone-code').val()),
      	code = $('#' + e + '-confirm input').val(),
				btnBlock = $('#' + e + '-block .profile__col').eq(1);

    if(code.length)
    {
      MainScript.stateLoading(true);
      $('#' + e + '-confirm').hide();
      $.ajax({
        type: 'POST',
        url: self.URL_CODE_CHECK,
        data: 'code='+ code + '&' + e + '=' + value,
        dataType: 'json',
        success: function(r)
				{
          MainScript.stateLoading(false);

          if(r.code==200)
          {
            if(p)
            {
            	self.showMessage('Электронная почта подтверждена');
              self.EMAIL = value;
              self.setConfirmMark(e, false);
              self.EMAIL_CONFIRM
                ? $('#phone-block .clearfix').hide()
                : $('#phone-block .clearfix').show();
            }
            else
						{
              self.showMessage('Номер телефона подтвержден');
              self.PHONE = $('#phone-code').val();
              self.setConfirmMark(e, false);
              self.EMAIL_CONFIRM
								? $('#phone-block .clearfix').hide()
								: $('#phone-block .clearfix').show();
            }
            $(btnBlock).hide();
          }
          else
					{
            self.showMessage('Введен некорректный код');
            p ? $('#email_input').val(self.EMAIL) : $('#phone-code').val(self.PHONE);
            $(btnBlock).show();
          }
          $('#' + e + '-confirm input').val('');
          $('#' + e + '-block').fadeIn();
        }
      });
    }
  };
  //
  // вывод сообщений
  ApplicantEditPage.prototype.showMessage = function (message)
	{
    $("body").append('<div class="prmu__popup" id="edit_profile_popup"><p>' + message + "</p></div>");
    $.fancybox.open({
      src: "#edit_profile_popup",
      type: "inline",
      touch: false,
      afterClose: function() { $("body>div.prmu__popup").remove() }
    })
	};
  //
  // утсановка поля
  ApplicantEditPage.prototype.error = function (e, error)
  {
    let block = ($(e).hasClass('.profile__field')
      	? e : $(e).closest('.profile__field'));

    if(error)
    {
      $(block).addClass('error');
      return false;
    }
    else
    {
      $(block).removeClass('error');
      return true;
    }
  };
  //
  // получаем строку чисел
  ApplicantEditPage.prototype.getNum = function (value)
	{
    return value.replace(/\D+/g,'')
	};
  //
	// установка маркера "Не подтверждено"
  ApplicantEditPage.prototype.setConfirmMark = function (type, setBlock)
	{
		if(setBlock==false)
		{
			$('.confirm-user.'+type).remove();
			type==='email' ? this.EMAIL_CONFIRM = true : this.PHONE_CONFIRM = true;
		}
		else
		{
      if(type==='phone' && !$('.confirm-user.phone').is('*'))
      {
        $('.epa__logo-name-list').before('<div class="confirm-user phone">Необходимо подтвердить телефон</div>');
        this.PHONE_CONFIRM = false;
      }
      if(type==='email' && !$('.confirm-user.email').is('*'))
      {
        $('.epa__logo-name-list').before('<div class="confirm-user email">Необходимо подтвердить почту</div>');
        this.EMAIL_CONFIRM = false;
      }
		}
	};
  // Позиция содержания
  ApplicantEditPage.prototype.scrollContentList = function ()
  {
    (
      $(document).scrollTop() > ApplicantEditPage.prototype.CONTENT_POSITION
      &&
      $(window).width() > 767
    )
      ? $('.epa__logo-name-list').addClass('fixed')
      : $('.epa__logo-name-list').removeClass('fixed');
  };
  // Проверка периода
  ApplicantEditPage.prototype.checkPeriod = function (e)
  {
    let self=this, v=e.value, l=v.length;

    if(self.KEY==8)
    { //backspace
      if(l==8 || l==7)
      {
        let arV = v.split(' до ');
        if(arV[0]===v)
          v = 'С ' + self.getNum(v).substr(0,2);
        else if(!self.getNum(arV[1]).length)
          v = 'С ' + self.getNum(v);
      }
      if(l==2)
			{
        v = '';
			}
    }
    else
		{
      if(!self.getNum(v).length)
        v = 'С ';
      if(l==1 && self.getNum(v).length==1)
        v = 'С ' + self.getNum(v);
      if(l==4 && self.getNum(v).length==2 )
        v = 'С ' + self.getNum(v) + ' до ';
      if(l==4 && self.getNum(v).length==1)
        v = (v.substr(-1)==' ' ? 'С ' + self.getNum(v)
							+ ' до ' : 'С ' + self.getNum(v));
      if(l==5)
      {
        let s = v.substr(-1);
        if(self.getNum(s).length==1)
          v = 'С ' + self.getNum(v).substr(0,2) + ' до ' + s;
        else if(s==' ')
          v = 'С ' + self.getNum(v) + ' до ';
        else
          v = 'С ' + self.getNum(v);
      }
      if(l>=7)
      {
        let arV = v.split(' до ');
        if(arV[0]===v)
        {
          v = 'С ' + self.getNum(v).substr(0,2) + ' до ' + self.getNum(v).substr(2,4);
        }
        else
				{
          v = 'С ' + self.getNum(arV[0]).substr(0,2) + ' до ' + self.getNum(arV[1]).substr(0,2);
        }
      }
    }

    e.value = v;
  };
	//
  return ApplicantEditPage;
}());
/*
*
*/
$(document).ready(function () {
  new ApplicantEditPage();
});











jQuery(function($){
	var hwLen = 3, // Вес-рост - 3 цифры
			oldEmail = $('#epa-email').val(),
			cityM = '#city-module',
			cntctM = '#contacts-module',
			mainM = '#main-module',
			emailTimer = null,
			arErrorsFields = [],
			arIdCities = [],
			arNewPosts = [],
			arSelectMetroes = [],
			arSelect = [
				'messenger',
				'hcolor',
				'hlen',
				'ycolor',
				'chest',
				'waist',
				'thigh',
							//'posts', // list of vacancy <ul id="epa-list-posts"> in
				'education',
				'language'
			],
			keyCode = false;

	$(document).keydown(function(e){ keyCode = e.keyCode });

	// прокрутка по содержанию
	$('.epa__logo-name').click(function(){
		var num = $(this).index();
		$('html, body').animate({ scrollTop: $('.epa__content-title:eq('+num+')').offset().top-20 }, 1000);
	});
	// LOCATION
	//	Собираем массив уже выбраных городов
	$.each($(cityM+' .epa__city-item'), function(){
		if(this.dataset.id!='')
			arIdCities.push(this.dataset.idcity);
	});
	// собираем выбранные метро
	$.each($(cityM+' .epa__metro-item [type=hidden]'), function(){
		arSelectMetroes.push($(this).val());
	});
	//
	$(document).on('click', function(e){
		var it = e.target;
		// select
		for(var i=0; i<arSelect.length; i++){
			var veil = 'epa-veil-' + arSelect[i],
				list = '#epa-list-' + arSelect[i],
				btn = '#epa-list-' + arSelect[i] + ' i';

			if(it.id == veil)
				$(list).fadeIn();
			else if($(it).is(btn) || !$(it).closest(list).length)
				$(list).fadeOut();
		}
		// single post select	
		if($(it).hasClass('epa__post-veil')){
			var list = $(it).siblings('.epa__post-list');
			$(list).fadeIn();
		}
		else if($(it).is('.epa__post-btn') || !$(it).closest('.epa__post-list').length)
			$('.epa__post-list').fadeOut();
	});
	// события изменения внешности
	$('#epa-list-hcolor input').on('change', function(){ changeRadio('hcolor') }); 
	$('#epa-list-hlen input').on('change', function(){ changeRadio('hlen') }); 
	$('#epa-list-ycolor input').on('change', function(){ changeRadio('ycolor') }); 
	$('#epa-list-chest input').on('change', function(){ changeRadio('chest') }); 
	$('#epa-list-waist input').on('change', function(){ changeRadio('waist') }); 
	$('#epa-list-thigh input').on('change', function(){ changeRadio('thigh') });
	// ввода данных роста и веса
	$('#epa-height, #epa-weight').keyup(function(){
		var val = getNum($(this).val());
		$(this).val(val.substr(0,hwLen));
	});
	// событие изменения образования
	$('#epa-list-education input').on('change', function(){ changeRadio('education') });
	// события выбора языка
	$('#epa-list-language input').on('change', function(){
		var arInputs = $('#epa-list-language input'),
		arLang = [];
		$.each(arInputs, function(){
			if($(this).is(':checked'))
				arLang.push($(this).siblings('label').text());
		});
		$('#epa-str-language').val(arLang.join(', '));
	});
	// события выбора вакансии
	$('#epa-list-posts').on('change', 'input', function(e){
		var arInputs = $('#epa-list-posts [name="donjnost[]"]:checked');
		// нельзя удалять послeднюю должность
		if(!arInputs.length) {
			var el = e.target.nextElementSibling;
			confirm('Должна быть установлена хотя бы одна вакансия');
			setTimeout(function(){ $(el).click() },10);
			return false;
		}
		checkPosts();
	});
	//
	$('.epa__post-detail').on('input', '[type=text]', function(){ checkField(this) });
	// создать новую должнотсть
	$('#epa-posts-add span').click(function(){
		$('#epa-posts-add').hide();
		$('#epa-posts-save').show();
		$('#epa-posts-save input').focus();
	});
	// сохранить новую должность
	$('#epa-posts-save span').click(function(){
		var val = $('#epa-posts-save input').val();
		if(val!==''){
			id = randomInt();
			html = 	'<li>'+
						'<input type="checkbox" name="donjnost[]" value="'+id+'" data-name="'+val+'" id="epa-post-'+id+'" checked>'+
						'<label for="epa-post-'+id+'">'+val+'<b></b></label>'+
					'</li>';
			$('#epa-posts-add').before(html);
			checkPosts(id);
			$('#epa-posts-add').show();
			$('#epa-posts-save').hide();
			$('#epa-posts-save input').val('');
		}
	});
	// установка параметров должности
	$('.epa__post-detail').on('change', '[type=radio]', function(e){
		let newVal = $(e.target.nextElementSibling).text(),
				list = $(e.target).closest('ul'),
				input = $(list).siblings('input');

		$(input).val(newVal);
		$(list).fadeOut();
	});
	// удаление должности
	$('.epa__post-detail').on('click', '.epa__post-close', function(){
		var id = $(this).closest('.epa__post-block').data('id'),
			arInputs = $('#epa-list-posts').find('input'),
			$it = 0,
			checked = 0;

		$.each(arInputs, function(){
			if($(this).val()==id) $it = $(this);
			if($(this).is(':checked')) checked++;
		});

		if($it!=0 && checked>1){
			$it.attr('checked', false);
			checkPosts();		
		}
		else{
			confirm('Должна быть установлена хотя бы одна вакансия');
		}
	});
	//
	//  блок для создания города
	//
	$('.epa__add-city-btn').on('click', function(){
		$('.epa__cities-block-list').append($('#add-city-content').html());
		var main = $(cityM).find('.epa__city-item:eq(-1)');

		$('html, body').animate({ scrollTop: $(main).offset().top-20 }, 1000);
	});
	//
	//  Ввод города
	//
	$(cityM).on('focus', '.city-input', function(){ findCities(this) });
	//
	$(cityM).on('keyup', '.city-input', function(){
		var main = $(this).closest('.epa__city-item'),
			cityError = $(this).siblings('.epa__city-err'),
			cityLabel = $(this).closest('.epa__label'),
			res = verificationCities($(this).val(), main[0].dataset.idcity);

		if(res.error == 2){ // проверяем только совпадение ID
			addErr(cityError);
			addErr(cityLabel);
		}
		else{
			remErr(cityError);
			remErr(cityLabel);
			main[0].dataset.idcity = res.id!='' ? res.id : main[0].dataset.idcity;
		}
		findCities(this);
	});
	//
	$(cityM).on('blur', '.city-input', function(){
		var main = $(this).closest('.epa__city-item'),
			cityError = $(this).siblings('.epa__city-err'),
			cityLabel = $(this).closest('.epa__label'),
			res = verificationCities($(this).val(), main[0].dataset.idcity);

		if(res.error){
			if(res.error==2) addErr(cityError);
			addErr(cityLabel);
		}
		else{
			remErr(cityError);
			remErr(cityLabel);
			main[0].dataset.idcity = res.id!='' ? res.id : main[0].dataset.idcity;
			checkSelectCity(main);
			checkAvailabilityMetro(main);
		}
	});
	//  выбор города из списка
	$(cityM).on('click', '.city-list li', function(){
		var main = $(this).closest('.epa__city-item'),
			cityList = $(this).closest('.city-list'),
			cityError = $(cityList).siblings('.epa__city-err'),
			cityInput = $(cityList).siblings('.city-input'),
			cityLabel = $(this).closest('.epa__label');

		if(!$(this).hasClass('emp')){
			$(cityInput).val($(this).text());
			var res = verificationCities($(this).text(), main[0].dataset.idcity);

			if(res.error==2){ // проверяем только совпадение ID
				addErr(cityError);
				addErr(cityLabel);
			}
			else{
				$(cityList).fadeOut();
				remErr(cityError);
				remErr(cityLabel);
				main[0].dataset.idcity = res.id!='' ? res.id : main[0].dataset.idcity;
				checkSelectCity(main);
				checkAvailabilityMetro(main);
			}     
		}
		else{ addErr(cityLabel) }
	});
	//  закрываем список городов
	$(document).click(function(e){
		if(!$(e.target).closest('.city-list').length){
			if($('.city-input').is(e.target)){// закрываем ненужные списки
					$.each($('.city-list'), function(){
					var input = $(this).siblings('.city-input');
					if(!$(input).is(e.target)) 
						$(this).fadeOut();
				});
			}
			else{ $('.city-list').fadeOut() }
		}
	});
	// удалить город
	$(cityM).on('click', '.epa__city-del', function(){
		var main = $(this).closest('.epa__city-item'),
			arNames = $('.epa__cities-list b'),
			idCity = main[0].dataset.idcity,
			name = arCities[idCity];
			num = -1;
		
		$.each(arNames, function(){ if($(this).text()==name) num=$(this).index() });
		$(arNames[num]).remove(); // удалили зеленое название

		arIdCities.splice(arIdCities.indexOf(idCity),1); // убираем город из массива выбранных
		main.remove();
	});
	//
	//  Ввод метро
	//
	$(cityM).on('focus', '.metro-input', function(){ findMetroes(this) });
	//
	$(cityM).on('keyup', '.metro-input', function(){ 
		var $it = $(this),
			find = -1,
			value = $it.val(),
			main = $it.closest('.epa__city-item'),
			metroLabel = $it.closest('.epa__label');

		$.each(arMetroes[main[0].dataset.idcity], function(i, metro){
			if(value.toLowerCase()==metro.toLowerCase()){
				find = true;
				selectMetro($it, i);
			} 
		});
		(find<0 && value!='') ? addErr(metroLabel) : remErr(metroLabel);
		findMetroes(this);
	});
	//
	$(cityM).on('blur', '.metro-input', function(){
		var $it = $(this),
			find = -1,
			value = $it.val(),
			main = $it.closest('.epa__city-item'),
			metroLabel = $it.closest('.epa__label');

		$.each(arMetroes[main[0].dataset.idcity], function(i, metro){
			if(value.toLowerCase()==metro.toLowerCase()){
				find = i;
				selectMetro($it, i);
			}
		});
		(find<0 && value!='') ? addErr(metroLabel) : remErr(metroLabel);
	});
	//  выбор метро из списка
	$(cityM).on('click', '.metro-list li', function(){
		var $it = $(this),
			metroList = $it.closest('.metro-list'),
			metroInput = $(metroList).siblings('.metro-input'),
			metroLabel = $it.closest('.epa__label');

		if(!$it.hasClass('emp')){
			$(metroInput).val($it.text());
			metroList.fadeOut();
			remErr(metroLabel);
			selectMetro(metroInput, $it.data('val'));
		}
		else{ addErr(metroLabel) }  
	});
	//  закрываем список метро
	$(document).click(function(e){
		if(!$(e.target).closest('.metro-list').length){
			if($('.metro-input').is(e.target)){
				// закрываем ненужные списки
				$.each($('.metro-list'), function(){
					var input = $(this).siblings('.metro-input');
					if(!$(input).is(e.target)) $(this).fadeOut();
				});
			}
			else{ $('.metro-list').fadeOut() }
		}
	});
	// удаление метро
	$(cityM).on('click', '.epa__metro-close', function(){
		var metroItem = $(this).closest('.epa__metro-item'),
			id = $(this).siblings('[type=hidden]').val();

		arSelectMetroes.splice(arSelectMetroes.indexOf(id), 1); // удалили из массива выбранных метро
		metroItem.remove();
	});
	//
	//		Ввод телефона
	//
	//$(document).on('click',function(e){ checkPhone(e) });
	//$('#phone-code').on('input',function(e){ checkPhone(e) });
	//$(cntctM).on('focus', '.phone-input', function(){ findPhones(this) });  // рлеп выкдючили поиск телефона
	//$(cntctM).on('keyup', '.phone-input', function(){ findPhones(this) });
	//  выбор телефона из списка
	$(cntctM).on('click', '.phone-list li', function(){
		var phoneList = $(this).closest('.phone-list'),
			phoneInput = $(phoneList).siblings('.phone-input');

		$(phoneInput).val($(this).text());
		phoneList.fadeOut();
	});
	//  закрываем список телефонов
	$(document).click(function(e){
		if(!$(e.target).closest('.phone-list').length){
			if($('.phone-input').is(e.target)){		
				$.each($('.phone-list'), function(){// закрываем ненужные списки
					var input = $(this).siblings('.phone-input');
					if(!$(input).is(e.target)) $(this).fadeOut();
				});
			}
			else{ $('.phone-list').fadeOut() }
		}
	});
	//
	//
	// добавление/удаление периода
	$(cityM).on('change', '.epa__day-input', function(){
		var main = $(this).closest('.epa__city-item'),
			perList = $(main).find('.epa__period-list>.row'),
			content = $('#add-day-period').html(),
			idDay = $(this).val(),
			dayName = $(this).data('day'),
			idCity = main[0].dataset.idcity,
			prnt = $(this).closest('.epa__days-checkboxes');

		if($(this).is(':checked')){
			content = content.replace(/NEWDAY/g, idDay);
			content = content.replace(/NEWID/g, idCity);
			$(perList).append(content);
			var item = $(perList).find('.epa__period:eq(-1)');
			
			$(item).find('i').text(dayName);
			$(item).find('.epa__input').on('input',function(){
				checkPeriod(this);
			});

            $.each($(item).find('.epa__input'),function(){
                checkField(this);
            });

		}
		else{
			var arItems = $(perList).find('.epa__period');
			$.each(arItems, function(){ 
				if($(this).data('id')==idDay) 
					$(this).remove(); 
			});
		}
		var checked = false,
				arL = $(prnt).find('.epa__checkbox');
		$.each($(prnt).find('.epa__day-input'), function(){
			if($(this).is(':checked')) checked = true;
		});
		checked
		? $.each(arL, function(){ remErr(this) })
		: $.each(arL, function(){ addErr(this) });
	});
	// удаление периода
	$(cityM).on('click', '.epa__period-close', function(){
		var main = $(this).closest('.epa__city-item'),
			period = $(this).closest('.epa__period'),
			arDays = $(main).find('.epa__days-checkboxes input'),
			idDay = $(period).data('id');

		$.each(arDays, function(){ if($(this).val()==idDay) $(this).attr('checked',false) });
		period.remove();
	});
	// проверка периода
	$(cityM).on('blur', '.epa__period input', function(){
		var val = $(this).val(),
			label = $(this).closest('.epa__label');

        //addErr(label);

		if(val!=='') {
            let arVals = val.split('до');
            if (arVals.length == 1) {
                $(this).val('');
                addErr(label);
            }
            else if (getNum(arVals[0]).length == 0 || getNum(arVals[1]).length == 0) {
                $(this).val('');
                addErr(label);
            }
        }
	});
	//	проверка ввода ЗП
	$('.epa__post-detail').on('keyup', '.epa__payment input', function(){
		var val = getNum($(this).val());
		$(this).val(val);
	});
	//    Проверка полей
	$(mainM).on('keyup','.epa__required',function(){ checkField(this) });	
	$(cityM).on('keyup','.epa__required',function(){ checkField(this) });
	$(cityM).on('change','.epa__required',function(){ checkField(this) });
	$(cntctM).on('keyup','.epa__required',function(){ checkField(this) });
	$(cntctM).on('change','.epa__required',function(){ checkField(this)	});
	$('#epa-mail').keyup(function(){ checkField(this) });
	$('#epa-gmail').change(function(){ checkField(this)	});
	$('#epa-gmail').change(function(){ checkField(this)	});
	$('#epa-gmail').change(function(){ checkField(this)	});
	$('.epa__education [type="radio"], .epa__language [type="checkbox"]')
		.change(function(){ checkField(this) });
	//
	$('.epa__save-btn').click(function(e){
		var self = this,
				epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
				nemail = $('#epa-email').val(),
				resEmail = false,
				errors = false;

		e.preventDefault();

    MainScript.stateLoading(true);

		resEmail = epattern.test(nemail) ? remErr('.epa__email') : addErr('.epa__email');
		$('.epa__email').removeClass('erroremail');

		if(resEmail && nemail!=oldEmail){
			clearTimeout(emailTimer);
			emailTimer = setTimeout(function(){
				$.ajax({
					type: 'POST',
					url: '/ajax/emailVerification',
					data: 'nemail='+nemail+'&oemail='+oldEmail,
					dataType: 'json',
					success: function(res){
						res
						? $('.epa__email').addClass('erroremail error')
						: $('.epa__email').removeClass('erroremail error');

						errorFieldName('#epa-email',res);

						$.each($(cityM+' .epa__required'), function(){
							if(!checkField(this)) errors = true; 
						});
						$.each($(cityM+' .epa__days-checkboxes'), function(){
							var checked = false,
									$p = $(this);

							$.each($p.find('.epa__day-input'), function(){ 
								if($(this).is(':checked')) checked=true;
							});
							if(!checked) {
								$.each($p.find('.epa__checkbox'), function(){ addErr(this) })
								errors = true; 
							}
						});

						if(!checkField('[name="about-mself"]')) errors = true;

						if(!checkField($('[name="user-attribs[edu]"]'))) errors = true;
						if(!checkField($('[name="langs[]"]'))) errors = true;
						$.each($('.epa__post-detail .epa__required'), function(){
							if(!checkField(this)) errors = true; 
						});

						checkPhone({'type':'input'});

						var arErrors = $('.error');
						if(arErrors.length>0)
						{
              MainScript.stateLoading(false);
							$.fancybox.open({
								src: '.prmu__popup',
								type: 'inline',
								touch: false,
								afterClose: function(){
									$('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
								}
							});
						}

						if(!errors && !arErrors.length){
							var arPosts = $('#epa-list-posts input'),
								arCityItems = $('#city-module .epa__city-item'),
								arTimeItems = $('#city-module .epa__period input'),
								addInputs = '';
							$.each(arPosts, function(){ // добавляем массив опыта вакансий
								if($(this).is(':checked'))
									addInputs += '<input type="hidden" name="donjnost-exp[]" value="'+$(this).val()+'">';
							});
							$.each(arCityItems, function(){ // добавляем ID городов
								addInputs += '<input type="hidden" name="city[]" value="'+this.dataset.idcity+'">';
							});
							$('#epa-edit-form').append(addInputs);	

							$.each(arTimeItems, function(){ 	// преображаем время в достойный вид
								var val = $(this).val();
								if(val!='')
								{
									let arVals = val.split('до');
                  let newVal = getNum(arVals[0]) + '-' + getNum(arVals[1]);
									$(this).val(newVal)
								}
							});
							
							var arAllInputs = $('.epa__cities-block-list input');
							$.each(arAllInputs, function(){
								var main = $(this).closest('.epa__city-item'),
									idCity = main[0].dataset.idcity;

								if($(this).closest('.epa__days-checkboxes').length){
									$(this).attr('name', 'days['+idCity+']');
								}
								if($(this).closest('.epa__period-list').length){
									var day = $(this).attr('name').slice(-3);
									$(this).attr('name', 'time['+idCity+']'+day);
								}					
							});
							$('#epa-edit-form').submit();
							//console.log($('#epa-edit-form').serializeArray());
						}
					}
				});
			}, 500);
		}
		else{
			$.each($(cityM+' .epa__required'), function(){
				if(!checkField(this)) errors = true; 
			});
			$.each($(cityM+' .epa__days-checkboxes'), function(){
				var checked = false,
						$p = $(this);

				$.each($p.find('.epa__day-input'), function(){ 
					if($(this).is(':checked')) checked=true;
				});
				if(!checked) {
					$.each($p.find('.epa__checkbox'), function(){ addErr(this) })
					errors = true; 
				}
			});

			if(!checkField('[name="about-mself"]')) errors = true; 

			if(!checkField($('[name="user-attribs[edu]"]'))) errors = true;
			if(!checkField($('[name="langs[]"]'))) errors = true;
			$.each($('.epa__post-detail .epa__required'), function(){
				if(!checkField(this)) errors = true; 
			});

			checkPhone({'type':'input'});

			var arErrors = $('.error');
			if(arErrors.length>0)
			{
        MainScript.stateLoading(false);
        $.fancybox.open({
          src: '.prmu__popup',
          type: 'inline',
          touch: false,
          afterClose: function(){
            $('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
          }
        });
			}
		//console.log(arErrors);
			if(!errors && !arErrors.length){
				var arPosts = $('#epa-list-posts input'),
					arCityItems = $('#city-module .epa__city-item'),
					arTimeItems = $('#city-module .epa__period input'),
					addInputs = '';
				$.each(arPosts, function(){ // добавляем массив опыта вакансий
					if($(this).is(':checked'))
						addInputs += '<input type="hidden" name="donjnost-exp[]" value="'+$(this).val()+'">';
				});
				$.each(arCityItems, function(){ // добавляем ID городов
					addInputs += '<input type="hidden" name="city[]" value="'+this.dataset.idcity+'">';
				});
				$('#epa-edit-form').append(addInputs);	

				$.each(arTimeItems, function(){ 	// преображаем время в достойный вид
					var val = $(this).val();
					if(val!=''){
						let arVals = val.split('до');
						let newVal = getNum(arVals[0]) + '-' + getNum(arVals[1]);
						$(this).val(newVal)
					}
				});
				
				var arAllInputs = $('.epa__cities-block-list input');
				$.each(arAllInputs, function(){
					var main = $(this).closest('.epa__city-item'),
						idCity = main[0].dataset.idcity;

					if($(this).closest('.epa__days-checkboxes').length){
						$(this).attr('name', 'days['+idCity+']');
					}
					if($(this).closest('.epa__period-list').length){
						var day = $(this).attr('name').slice(-3);
						$(this).attr('name', 'time['+idCity+']'+day);
					}
				});
				$('#epa-edit-form').submit();
				//console.log($('#epa-edit-form').serializeArray());
			}
		}
	});
	//
	//	добавить еще один номер
	//

	//
	$('.epa__req-list').on('click', 'b', function(){
		var name = $(this).text();
		$('html, body').animate({ scrollTop: $('[data-name="' + name + '"]').offset().top-20 }, 1000);
	});
	/*
	*     Финкции
	*/
	// additional functions
	function addErr(e){ 
		$(e).addClass('error');
		return false;
	}
	function remErr(e){ 
		$(e).removeClass('error');
		return true;
	}
	// select radio
	function changeRadio(str){
		var arInputs = $('#epa-list-' + str + ' input');
		$.each(arInputs, function(){
			if($(this).is(':checked')) 
				$('#epa-str-' + str).val($(this).siblings('label').text());
		});
		$('#epa-list-' + str).fadeOut();
	}
	function checkPosts(id=0){
		var arInputs = $('#epa-list-posts').find('input'),
			postBlock = $('#epa-post-single').html(),
			arPostBlock = $('.epa__post-detail').find('.epa__post-block'),
			arPostsName = [],
			arPostId = [],
			arPostsNewId = [],
			htmlStr = '',
			htmlBlock = '';

		//	собираем ID блоков должностей
		$.each(arPostBlock, function(){ 
			arPostId.push(Number($(this).data('id')));
		});
		// проверяем выбранные должности
		$.each(arInputs, function(){
			var $it = $(this),
				elId = Number($it.val()),
				elName = $it.siblings('label').text(),
				custom = typeof $it.data('name')=='string';

			if($it.is(':checked')){
				arPostsName.push(elName);
				arPostsNewId.push(elId);
				if(custom)
					changeArrNewPosts(id>0 && id==elId ? id : elId, elName, true);
			}
			else if(custom)
				changeArrNewPosts(elId, elName, false);
		});
		//	записываем в псевдоинпут
		$('#epa-str-posts').val(arPostsName);
		//	добавляем зелен. должность
		$.each(arPostsName, function(){ htmlStr += '<b> ' + this + '</b>' });
		$('.epa__posts-list').html(htmlStr);
		// выбираем ID к удалению
		var arTemp = [];
		$.each(arPostId, function(){
			if($.inArray(Number(this),arPostsNewId)<0) 
				arTemp.push(Number(this));
		});
		// удаляем блоки с этим ID
		$.each(arPostBlock, function(){
			if($.inArray(Number($(this).data('id')), arTemp)>=0) $(this).remove();
		});
		// выбираем ID к добавлению
		arTemp = [];
		$.each(arPostsNewId, function(i){
			if($.inArray(Number(this),arPostId)<0){
				arTemp.push({'id':Number(this), 'name':arPostsName[i]});
			}
		});
		$.each(arTemp, function(){
			var newId = id>0 ? id : this.id;
      var temp = postBlock.replace(/NEWID/g,newId);
			temp = temp.replace('NEWNAME',this.name);
			htmlBlock += temp;
		});

		// добавляем блоки
		//$('.epa__post-detail .clearfix').before(htmlBlock); //в конец
        $('.epa__post-detail').prepend(htmlBlock); //в начало

        $.each($('.epa__post-detail input'),function(){
            checkField(this);
        });
	}
	function randomInt(){
		var min = 1000,
			max = 9999;
		do{
			rand = min + Math.random() * (max + 1 - min);
			rand = Math.floor(rand);
		}while($.inArray(rand, arNewPosts)>=0);
		return rand;
	}
	function changeArrNewPosts(id, name, add){
		var pos = -1;
		$.each(arNewPosts, function(i){ if(this.id==id) pos = i });
		if(pos<0 && add)
			arNewPosts.push({'id':id,'name':name});
		if(pos>=0 && !add)
			arNewPosts.splice(pos,1);
	}
  	//
	function findCities(e){
		var val = $(e).val().toLowerCase(),
			arResult = [],
			arResultId = [],
			content = '';

		if(val.length>2){ // если введено более 3х символов
			$.each(arCities, function(i){
				if(this.toLowerCase().indexOf(val)>=0){ 
					arResult.push(this);
					arResultId.push(i);
				}
			});
			arResult.length>0  
			? $.each(arResult, function(i){ content += '<li data-val="'+arResultId[i]+'">'+this+'</li>' })         
			: content = '<li class="emp">Список пуст</li>';

			$(e).siblings('.city-list').empty().append(content).fadeIn(); 
		}
	} 
	// поиск в выбраных городах
	function verificationCities(value, idcity=''){
		var result = {'error' : 0, 'id' : ''},
			find = false;

		$.each(arCities, function(i){
			if(value.toLowerCase()==arCities[i].toLowerCase()){
				if(idcity=='' || (idcity!='' && i!=idcity)){
					if($.inArray(i, arIdCities)>=0){
						result.error = 2; // такой город уже выбран
					}
					else{   
						result.error = 0; // этот город еще не выбирался
						result.id = i;
					}
				}
				find = true;
			}
		});
		if(!find) result.error = 1;
		return result;
	}
	function checkSelectCity(main){
		var arNames = $('.epa__cities-list b'),
			arCBlocks = $('#city-module .epa__city-item'),
			index = $(main).index(),
			name = arCities[main[0].dataset.idcity],
			html = '<b>'+name+'</b>';

		arCBlocks.length-arNames.length==1
		? $('.epa__cities-list>div').append(html)
		: $(arNames[index]).text(name);
	}
	//
	function findMetroes(e){ 
		var val = $(e).val().toLowerCase(),
			arResult = [],
			content = '',
			main = $(e).closest('.epa__city-item'),
			idcity = main[0].dataset.idcity;

		$.each(arMetroes[idcity], function(i){
			if(this.toLowerCase().indexOf(val)>=0 && $.inArray(i, arSelectMetroes)<0) 
				arResult.push({'id':i,'name':this});
		});

		arResult.length>0  
		? $.each(arResult, function(){ content += '<li data-val="'+this.id+'">'+this.name+'</li>' })
		: content = '<li class="emp">Список пуст</li>';

		$(e).siblings('.metro-list').empty().append(content).fadeIn();
	}
	// metro
	function checkAvailabilityMetro(main){
		var idcity = main[0].dataset.idcity,
			cityBlock = $(main).find('.epa__city'),
			metroBlock = $(main).find('.epa__metro'),
			metroList = $(main).find('.epa__metro-list');

		if(typeof arMetroes[idcity] === "object" && metroBlock.length==0){
			$(cityBlock).after($('#add-metro-content').html());
		}
		if(typeof arMetroes[idcity] !== "object" && metroBlock.length>0){
			$(metroBlock).remove();
			$(metroList).remove();
		}
	}
	//
	function selectMetro(input, idMetro){
		var main = $(input).closest('.epa__city-item'),
			metroLabel = $(input).closest('.epa__metro'),
			metroList = $(input).siblings('.metro-list'),
			metroListBlock = $(main).find('.epa__metro-list'),
			idCity = main[0].dataset.idcity,
			nameMetro = arMetroes[idCity][idMetro],
			html = $('#add-metro-item').html();

		if($.inArray(idMetro,arSelectMetroes)<0)
			arSelectMetroes.push(String(idMetro)); // добавили в массив выбранных, чтобы не было дублей

		html = html.replace(/IDCITY/g, idCity);
		html = html.replace(/IDMETRO/g, idMetro);
		html = html.replace(/NAMEMETRO/g, nameMetro);

		$(metroListBlock).append(html);
		$(input).val('');
		$(input).blur();
		$(metroList).fadeOut();
		remErr(metroLabel);
	}
	// check fields
	function checkField(e){
		var $it = $(e),
        val = $it.val(),
        id = $it.prop('id'),
        label = $it.closest('.profile__field'),
        epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
        res = false;

		if(id=='epa-email'){ // email
			res = epattern.test(val) ? remErr(label) : addErr(label);
			$('.epa__email').removeClass('erroremail');
			if(res && val!=oldEmail){
				clearTimeout(emailTimer);
				emailTimer = setTimeout(function(){
					$.ajax({
						type: 'POST',
						url: '/ajax/emailVerification',
						data: 'nemail='+val+'&oemail='+oldEmail,
						dataType: 'json',
						success: function(res){
							if(res){
								$('.epa__email').addClass('erroremail error'); 
							}
							else{
								$('.epa__email').removeClass('erroremail error');
							}
						}
					});
				}, 500);
			}	
		}
		else if($(label).hasClass('epa__education')) { // образование
			var selected = false;
			$.each($(label).find('[type="radio"]'),
				function(){ if(this.checked) selected = true; });
			res = (!selected ? addErr(label) : remErr(label));
		}
		else if($(label).hasClass('epa__language')) { // языки
			var selected = false;
			$.each($(label).find('[type="checkbox"]'),
				function(){ if(this.checked) selected = true; });
			res = (!selected ? addErr(label) : remErr(label));
		}
		else{
			if($(label).hasClass('epa__payment')) // исключения для поля "Ожидаемая оплата""
			{
				label = $it;
			}
			res = ((val=='' || val==null) ? addErr(label) : remErr(label));
		}
		if(id=='epa-mail' || id=='epa-gmail'){
			res = (epattern.test(val) || val=='') ? remErr(label) : addErr(label);
		}
		errorFieldName(e,!res);
		return res;
	};
	// получаем номер
	function getNum(value){ return value.replace(/\D+/g,'') }
  	//
	function errorFieldName(e,show){
		var name = $(e).data('name'),
		flag = $.inArray(name, arErrorsFields)<0 ? false : true,
		strErr = '<b>';

		if(flag && !show)
			arErrorsFields.splice(arErrorsFields.indexOf(name),1);

		if(!flag && show)
			arErrorsFields.push(name);

		strErr += arErrorsFields.join('</b>, <b>') + '</b>';
		$('.epa__req-list div').html(strErr);
		arErrorsFields.length>0 ? $('.epa__req-list').show() : $('.epa__req-list').hide();
	}

	// проверка номера
	function checkPhone(e){
		var $inp = $('#phone-code'),
			len = getNum($inp.val()).length,
			code = $('[name="__phone_prefix"]').val().length;

		if(e.type=='click' && !$(e.target).is('.country-phone') && !$(e.target).closest('.country-phone').length){
			if((code==3 && len<9) || (code==1 && len<10)){ // UKR || RF
				addErr($inp.closest('.epa__label'));
				$inp.val('');
			}
			else{
				remErr($inp.closest('.epa__label'));
			}
		}
		if(e.type=='input'){
			if((code==3 && len<9) || (code==1 && len<10) || len==0){
				addErr($inp.closest('.epa__label'));
			}
			else{
				remErr($inp.closest('.epa__label'));
			}
		}
	}
    //fixed menu in personal account
    var posAccMenu = $('.personal-acc__menu').offset().top - 100;
    $(window).on('resize scroll',scrollAccMenu);
    scrollAccMenu();
    function scrollAccMenu() {
        (
            $(document).scrollTop() > posAccMenu
            &&
            $(window).width() < 768
        )
            ? $('.personal-acc__menu').addClass('fixed')
            : $('.personal-acc__menu').removeClass('fixed');
    }

    //
	// начальное выделение полей
	//
	$.each($('.epa__post-detail .epa__required'), function(){ checkField(this) });
	$.each($(mainM + ' .epa__required'), function(){ checkField(this) });
	$.each($(cntctM + ' .epa__required'), function(){ checkField(this) });
	$.each($('.epa__education [type="radio"]'), function(){ checkField(this) });
	$.each($('.epa__language [type="checkbox"]'), function(){ checkField(this) });
	checkField('[name="about-mself"]');
	checkField('[name="user-attribs[edu]"]');
	checkField('[name="langs[]"]');
	checkPhone({type:'input'});
	//
});