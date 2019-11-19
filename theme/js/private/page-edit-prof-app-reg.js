jQuery(function($){
  var strAbout = 2000, // ограничение для поля "О себе"
    phoneLen = 10, // нормальное кол-во цифр в телефоне
    oldEmail = $('#epa-email').val(),
    cityM = '#city-module',
    cntctM = '#contacts-module',
    mainM = '#main-module',
    emailTimer = null,
    arSelectPhones = [],
    arErrorsFields = [];

	arIdCities = [];
	arNewPosts = [];
	arSelectMetroes = [];
	arSelect = [
		'messenger',
		'hcolor',
		'hlen',
		'ycolor',
		'chest',
		'waist',
		'thigh',
		'education',
		'language'
	];
	cropOptions = {};
	cropperObj = null;
	oldPhone = $('#phone-code').val();
	oldFlag = '';
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

  // собираем выбранные телефоны
  updateArSelectPhones();
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
  // установка параметров должности
  $('.epa__post-detail').on('change', '[type=radio]', function(e){
    var newVal = $(e.target.nextElementSibling).text(),
      list = $(e.target).closest('ul');
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
  // ввода данных 'О себе'
  $('.epa__textarea').keyup(function(){
    var val = $(this).val();
    if(val.length>strAbout)
      $(this).val(val.substr(0,strAbout));
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
      //checkAvailabilityMetro(main);
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
        //checkAvailabilityMetro(main);
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
  //		Ввод телефона
  //
  $(document).on('click',function(e){ checkPhone(e) });
  $('#phone-code').on('input',function(e){ checkPhone(e) });
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
  //
  $('.epa__save-btn').click(function(e){
    var self = this,
      epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
      nemail = $('#epa-email').val(),
      bAvatar = $('#login-img').hasClass('active-logo'),
      errors = false;

    e.preventDefault();

    if(MainScript.isButtonLoading(self))
      return false;
    else
      MainScript.buttonLoading(self,true);

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

            $.each($('.epa__post-detail .epa__required'), function(){
              if(!checkField(this)) errors = true;
            });

            checkPhone({'type':'input'});
            // роверка наличия аватара
            if(!bAvatar)
            {
              $('.avatar__logo-main').addClass('input__error');
            }
            var arErrors = $('.error');
            if(arErrors.length>0 || !bAvatar)
            {
              var scrollItem = (!bAvatar ? $('.input__error')[0] : $('.error')[0]);
              MainScript.buttonLoading(self,false);
              $.fancybox.open({
                src: '.prmu__popup',
                type: 'inline',
                touch: false,
                afterClose: function(){
                  $('html, body').animate({ scrollTop: $(scrollItem).offset().top-20 }, 1000);
                }
              });
            }

            if(!errors && !arErrors.length && bAvatar){
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
                  arVals = val.split('до');
                  newVal = getNum(arVals[0]) + '-' + getNum(arVals[1]);
                  $(this).val(newVal)
                }
              });

              var arAllInputs = $('.epa__cities-block-list input');
              $.each(arAllInputs, function(){
                var main = $(this).closest('.epa__city-item'),
                  idCity = main[0].dataset.idcity;
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
        if(!checkField(this)) {errors = true; };
      });

      $.each($('.epa__post-detail .epa__required'), function(){
        if(!checkField(this)) errors = true;
      });

      checkPhone({'type':'input'});

      // роверка наличия аватара
      if(!bAvatar)
      {
        $('.avatar__logo-main').addClass('input__error');
      }

      var arErrors = $('.error');
      if(arErrors.length>0 || !bAvatar)
      {
        var scrollItem = (!bAvatar ? $('.input__error')[0] : $('.error')[0]);
        MainScript.buttonLoading(self,false);
        $.fancybox.open({
          src: '.prmu__popup',
          type: 'inline',
          touch: false,
          afterClose: function(){
            $('html, body').animate({ scrollTop: $(scrollItem).offset().top-20 }, 1000);
          }
        });
      }

      if(!errors && !arErrors.length && bAvatar){
        var arPosts = $('#epa-list-posts input'),
          arCityItems = $('#city-module .epa__city-item'),
          //arTimeItems = $('#city-module .epa__period input'),
          addInputs = '';
        $.each(arPosts, function(){ // добавляем массив опыта вакансий
          if($(this).is(':checked'))
            addInputs += '<input type="hidden" name="donjnost-exp[]" value="'+$(this).val()+'">';
        });
        $.each(arCityItems, function(){ // добавляем ID городов
          addInputs += '<input type="hidden" name="city[]" value="'+this.dataset.idcity+'">';
        });
        $('#epa-edit-form').append(addInputs);

        var arAllInputs = $('.epa__cities-block-list input');
        $.each(arAllInputs, function(){
          var main = $(this).closest('.epa__city-item'),
            idCity = main[0].dataset.idcity;
        });
        $('#epa-edit-form').submit();
      }
    }
  });
  //
  //	добавить еще один номер
  //
  $('.epa__add-phone-btn').click(function(){
    var label = $(this).closest('.epa__label'),
      arItems = $(cntctM+' .epa__add-phone'),
      html = $('#add-additional-phone').html();

    html = html.replace(/NEWNUM/g, arItems.length);

    if(arItems.length>0)
      $(cntctM+' .epa__add-phone:eq(-1)').after(html);
    else
      $(label).after(html);
  });
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
      newId = id>0 ? id : this.id;
      temp = postBlock.replace(/NEWID/g,newId);
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
  // check fields
  function checkField(e){

    var $it = $(e),
      val = $it.val(),
      id = $it.prop('id'),
      label = $it.closest('.epa__label'),
      epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
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
    else
    if($(label).hasClass('epa__period')){ // поле установки подходящего времени в дни недели

      if(val.length>8){ // 8 минимум
        var arVals = val.split('до');
        if(arVals.length==2)
        {
          var from = Number(getNum(arVals[0])),
            to = Number(getNum(arVals[1]));

          res = (from>23 || to>24 || from>=to) ? addErr(label) : remErr(label); // проверяем правильность временного промежутка
        }
      }
      else if(val=='')
        res = addErr(label);
      else
        res = remErr(label);
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
  //	Поиск телефона
  function findPhones(e){
    var newV = getNum($(e).val()),
      newL = newV.length,
      arResult = [],
      content = '';

    updateArSelectPhones();
    $.each(arSelectPhones, function(){
      var oldV = getNum(this),
        oldL = oldV.length
      if(oldV.indexOf(newV)>=0 && newL<oldL)
        arResult.push(this);
    });

    arResult.length>0
      ? $.each(arResult, function(){ content += '<li>'+this+'</li>' })
      : content = '';

    $(e).siblings('.phone-list').empty().append(content).fadeIn();
  }
  // собираем введенные телефоны
  function updateArSelectPhones(){
    arSelectPhones = [];
    $.each($(cntctM+' .epa__phone'), function(){
      var val = $(this).val(),
        clearVal = getNum(val);
      if(clearVal!='' && clearVal.length==phoneLen && $.inArray(val, arSelectPhones)<0)
        arSelectPhones.push($(this).val());
    });
  }
  // получаем номер
  function getNum(value){ return value.replace(/\D+/g,'') }
  //
  function errorFieldName(e,show){
    var name = $(e).data('name'),
      flag = $.inArray(name, arErrorsFields)<0 ? false : true;
    strErr = '<b>';

    if(flag && !show)
      arErrorsFields.splice(arErrorsFields.indexOf(name),1);

    if(!flag && show)
      arErrorsFields.push(name);

    strErr += arErrorsFields.join('</b>, <b>') + '</b>';
    $('.epa__req-list div').html(strErr);
    arErrorsFields.length>0 ? $('.epa__req-list').show() : $('.epa__req-list').hide();
  }
  //
  //
  //
  getFlagTimer = setInterval(function(){ // ищем флаг страны
    if($('.country-phone-selected>img').is('*')){
      oldFlag = $('.country-phone-selected>img').attr('class');
      clearInterval(getFlagTimer);
    }
  },500);
  //
  function showPopupMess(t, m){
    var html = "<form data-header='" + t + "'>" + m + "</form>";
    ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
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
  if (window.screen.width < 768) {
    //
    //
    // управляем позицией блока содержания
    /*var posContentList = $('.epa__logo-name-list').offset().top - 15;
    $(window).on('resize scroll', scrollContentList);
    scrollContentList();

    function scrollContentList() {
      (
        $(document).scrollTop() > posContentList
        &&
        $(window).width() > 767
      )
        ? $('.epa__logo-name-list').addClass('fixed')
        : $('.epa__logo-name-list').removeClass('fixed');
    }*/
  }

  if ($(window).width() < 768) {
    //fixed menu in personal account
    var posAccMenu = $('.personal-acc__menu').offset().top - 100;
    $(window).on('resize scroll', scrollAccMenu);
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

  /**
   * Checked Gender
   */
  addErr('.epa__attr-block');
  if ($("input[name='sex']").is(":checked")) {
    remErr('.epa__attr-block');
  }

  $('.epa__attr-block').on("click", function() {
    if ( $("input[name='sex']").is(":checked") ) {
      remErr('.epa__attr-block');
    } else {
      addErr('.epa__attr-block');
    }
  });

  /**
   * Checked ListBox for Posts
   */
  var postAll = document.querySelectorAll('#epa-list-posts input[name="donjnost[]"]');

  if (postAll) {
    for (var i = 0; i < postAll.length; i++) {
      if ( postAll[i].checked == true ) {
        postChacked = true;
        break;
      } else {
        postChacked = false;
      }
    }
    if (postChacked) {
      remErr('.epa__label.epa__posts.epa__select');
    } else {
      addErr('.epa__label.epa__posts.epa__select');
    }

  }

  $('.epa__label.epa__posts.epa__select').on("click", function() {
    if (postAll) {
      for (var i = 0; i < postAll.length; i++) {
        if ( postAll[i].checked == true ) {
          postChacked = true;
        }
      }
      if (postChacked) {
        remErr('.epa__label.epa__posts.epa__select');
      } else {
        addErr('.epa__label.epa__posts.epa__select');
      }

    }
  });

  //
  //
  // инициализация календаря
  $("#birthday").datepicker({
    maxDate: '-14y',
    changeYear: true,
    yearRange: "1970:2005",
    beforeShow: function(){
      $('#ui-datepicker-div').addClass('custom-calendar');
    }
  });
  // проверка корректности даты
  if($('#birthday').is('*'))
  {
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
          addErr('.epa__label.epa__date');
          this.value='';
        }
        else
        {
          remErr('.epa__label.epa__date');
        }
      }
      else
      {
        addErr('.epa__label.epa__date');
      }
    });
  }
  //
  $(document).click(function(e){

    if($(e.target).closest('.epa__period-error').length || $(e.target).is('.epa__period-error'))
    {
      var main = $(e.target).closest('.epa__period')[0],
        input = $(main).find('.epa__input');
      $(input).focus();
      remErr(main);
    }
  });
  //
  $(cityM).on('blur','.epa__period .epa__input',function(e){
    setTimeout(function(){ checkField(e.target) },100);
  });
});