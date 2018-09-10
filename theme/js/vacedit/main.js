$(function(){
  var curDate = new Date(),
      titleLen = 70,//  Заголовок не более 70и символов
      ageLen = 2,//  Возраст = 2 цифры
      hwLen = 3, // Вес-рост - 3 цифры
      ageMinLimit = 14, // Возраст от 14 лет
      cityTimer = false, // таймер обращения к серверу для поиска городов
      bShowCityList = true, // флаг отображения списка городов
      idCo = $('#city-module').data('co'),
      arSelect = ['vacancy','expirience','paylims', 'hcolor', 'hlen', 'ycolor', 'chest', 'waist', 'thigh'],
      arValues = [];
      arBDates = [];
      arEDates = [];

  // LOCATION
  var cityCnt = $('.erv-city__item').length, // счетчик городов
      cModule = '#city-module',
      arIdCities = [];

  //  Собираем массив уже выбраных городов
  getSelectedCities();
  //  строим все календари
  var arCalendars = document.querySelectorAll('.city-calendar');
  for (var i = 0; i < arCalendars.length; i++)
    Calendar(arCalendars[i], curDate.getFullYear(), curDate.getMonth());
  //  Если есть флеш сообщение
  if(typeof flashMes !== "undefined" && flashMes.length>0){
    callPopup(flashMes);
  }
  // Удаление вакансии
  $('#rv-vac-del').click(function(e){
    if(!confirm('Вы действительно хотите удалить вакансию?')) e.preventDefault();
  });
  //
  //  блок для создания города
  //
  $('.add-city-btn').on('click', function(){
    var content = $('#add-city-content').html();
    $(content).insertBefore($(this));
    $(this).hide();

    var main = $(cModule).find('.erv-city__item:eq(-1)'),
        cityInput = main.find('.erv-city__label-input'),
        cityBtn = main.find('.erv-city__btn'),
        cityBdate = main.find('.city-bdate'),
        cityEdate = main.find('.city-edate'),
        cityDateBlock = main.find('.city-date-block'),
        cityAddLoc = main.find('.add-loc-btn'),
        d = main[0].dataset;
      
    main.find('i').text(cityCnt++);

    //  запрос на создание города
    cityBtn.click(function(e){

      d.idcity!= '' ? remErr(cityInput) : addErr(cityInput);
      d.bdate!='' ? remErr(cityBdate) : addErr(cityBdate);
      d.edate!='' ? remErr(cityEdate) : addErr(cityEdate);

      if(d.idcity!='' && d.bdate!='' && d.edate!=''){     
        var params = 'id=' + d.id + 
          '&idcity=' + d.idcity + 
          '&name=' + $(cityInput).find('input').val() + 
          '&bdate=' + d.bdate + 
          '&edate=' + d.edate;

        $.ajax({
          type: 'POST',
          url: MainConfig.AJAX_POST_CITY_DATA,
          data: params,
          dataType: 'json',
          success: function(res){
            callPopup(res.message);
            d.id = res.id;
            arIdCities.push(d.idcity);
            cityDateBlock.fadeOut();
            changeDates(main[0],1);// заносим даты
            setTimeout(function(){
              cityDateBlock.remove();
              cityAddLoc.fadeIn();
              $('.add-city-btn').fadeIn();
            },500);
          }
        });
      }
    });
  });
  //
  //  блок для создания локации
  //
  $(cModule).on('click', '.add-loc-btn', function(){
    $($('#add-loc-content').html()).insertBefore($(this));

    var locAddLoc = $(this),
        main = $(this.previousSibling),
        locName = main.find('.locname-input'),
        locIndex = main.find('.index-input'),
        locBDate = main.find('.city-bdate'),
        locEDate = main.find('.city-edate'),
        locBTime = main.find('.btime-input'),
        locETime = main.find('.etime-input'),
        locDateBlock = main.find('.loc-date-block'),
        locBtn = main.find('.erv-loc__btn'),
        locAddPeriod = main.find('.add-per-btn'),
        d = main[0].dataset;
  
    d.idcity = main.closest('.erv-city__item').data('id');
    locAddLoc.hide();
    locAddPeriod.hide();

    // работаем с метро
    var idcity = main.closest('.erv-city__item').data('idcity');
    if(typeof arMetroes[idcity] !== "undefined"){     
      $($('#add-metro-content').html()).insertBefore(locDateBlock);
      var locMetro = main.find('.ev-metro-select');
      locMetro[0].dataset.idcity = idcity;
      d.metro = '';
    }

    // маска для полей времени
    $(locBTime).mask("99:99");
    $(locETime).mask("99:99");
    //  запрос на создание локации
    locBtn.click(function(e){
      if(d.name!=''){ remErr(locName) }
      else if(d.index!=''){
        d.name = d.index;
        $(locName).val(d.index);
        remErr(locName);
      }
      else{ addErr(locName) }

      if(d.index!=''){ remErr(locIndex) }
      else if(d.name!=''){
        d.index = d.name;
        $(locIndex).val(d.name);
        remErr(locIndex);
      }
      else{ addErr(locIndex) }

      //d.name!='' ? remErr(locName) : addErr(locName);
      //d.index!='' ? remErr(locIndex) : addErr(locIndex);
      d.bdate!='' ? remErr(locBDate) : addErr(locBDate);
      d.edate!='' ? remErr(locEDate) : addErr(locEDate);
      d.btime!='' ? remErr(locBTime) : addErr(locBTime);
      d.etime!='' ? remErr(locETime) : addErr(locETime);

      if(d.metro!='null'){
        arMInps = $(locMetro).find('[name="city[metro][]"]');
        arVals = [];
        value = '';

        if(arMInps.length>0)
          $.each(arMInps, function(){ arVals.push($(this).val()) });

        d.metro = arVals.length>1 ? arVals.join(',') : arVals;
      }

      if(checkTime(d.btime, d.etime)){
        addErr(locBTime);
        addErr(locETime);
        d.btime = '';
        d.etime = '';
      }

      if(d.name!='' && d.index!='' && d.bdate!='' 
        && d.edate!='' && d.btime!='' && d.etime!=''){
        var params = 'idloc=' + d.idloc + 
          '&idcity=' + d.idcity + 
          '&name=' + d.name + 
          '&addr=' + d.index + 
          '&bdate[]=' + d.bdate + 
          '&edate[]=' + d.edate + 
          '&btime[]=' + d.btime + 
          '&etime[]=' + d.etime;

        if(d.metro!='null')
          params += '&metro=' + d.metro;

        $.ajax({
          type: 'POST',
          url: MainConfig.AJAX_POST_LOCATION_DATA,
          data: params,
          dataType: 'json',
          success: function(result){
            var content = $('#period-content').html();
            callPopup(result.message);
            d.idloc = result.id;
            locDateBlock.fadeOut();
            setTimeout(function(){
              locDateBlock.remove();
              $($('#period-content').html()).insertBefore(locAddPeriod);

              var perMain = locAddPeriod.siblings('.erv-city__time'),
                  table = $(perMain).find('table');

              perMain[0].dataset.bdate = d.bdate;
              perMain[0].dataset.edate = d.edate;
              perMain[0].dataset.btime = d.btime;
              perMain[0].dataset.etime = d.etime;

              bDate = getDateFromData(main[0], 'bdate');
              eDate = getDateFromData(main[0], 'edate');
              /*cntDate = diffDate(eDate, bDate) + 1;
              temp = bDate;
              tableContent = '';
              for(var i=0; i<cntDate; i++){
                day = Number(temp.getDate());
                mon = Number(temp.getMonth());
                year = Number(temp.getFullYear());
                resDate = ('0' + day).slice(-2) + '.' + ('0' + (mon + 1)).slice(-2) + '.' + year;
                tableContent += '<tr><td>' + resDate + '<td>' + d.btime + '<td>-<td>' + d.etime;
                temp.setDate(temp.getDate()+1); // добавляем день
              }*/
              if(diffDate(eDate, bDate)){
                day = Number(bDate.getDate());
                m = Number(bDate.getMonth());
                y = Number(bDate.getFullYear());
                d1 = ('0' + day).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + ('0' + y).slice(-2);     
                day = Number(eDate.getDate());
                m = Number(eDate.getMonth());
                y = Number(eDate.getFullYear());
                d2 = ('0' + day).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + ('0' + y).slice(-2);
                tableContent = 'c ' + d1 + ' по ' + d2 + ' ' + d.btime + '-' + d.etime;
              }
              else{
                day = Number(bDate.getDate());
                m = Number(bDate.getMonth());
                y = Number(bDate.getFullYear());
                tableContent = ('0' + day).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + ('0' + y).slice(-2) + ' ' + d.btime + '-' + d.etime;
              }
              $(table).append(tableContent);
              locAddLoc.fadeIn();
              locAddPeriod.fadeIn();
            },500);
          }
        });
      }
    });
  });
  //
  //  Блок создания периода
  //
  $(cModule).on('click', '.add-per-btn', function(){
    $($('#period-content').html()).insertBefore($(this)); // добавляем erv-city__time
    $(this.previousSibling).find('.erv-city__label-ltime').remove();
    $(this.previousSibling).append($('#add-period-content').html()); // добавляем редактируемое содержимое в erv-city__time

    var perAddPer = $(this),
        perMain = $(this.previousSibling),
        perBDate = perMain.find('.city-bdate'),
        perEDate = perMain.find('.city-edate'),
        perBTime = perMain.find('.btime-input'),
        perETime = perMain.find('.etime-input'),
        perDateBlock = perMain.find('.loc-date-block'),
        perBtn = perMain.find('.save-per-btn'),
        d = perMain[0].dataset;
  
    perAddPer.hide();
    perMain.find('.rst-per-btn').remove();
       

    // маска для полей времени
    $(perBTime).mask("99:99");
    $(perETime).mask("99:99");
    //  запрос на создание локации
    perBtn.click(function(e){
      d.bdate!='' ? remErr(perBDate) : addErr(perBDate);
      d.edate!='' ? remErr(perEDate) : addErr(perEDate);
      d.btime!='' ? remErr(perBTime) : addErr(perBTime);
      d.etime!='' ? remErr(perETime) : addErr(perETime);

      if(checkTime(d.btime, d.etime)){
        addErr(perBTime);
        addErr(perETime);
        d.btime = '';
        d.etime = '';
      }

      if(d.bdate!='' && d.edate!='' && d.btime!='' && d.etime!=''){
        var loc = getLocation(perMain),
            arPerMain = $(loc).find('.erv-city__time'),
            params = 
              'idloc=' + loc.dataset.idloc + 
              '&idcity=' + loc.dataset.idcity + 
              '&name=' + loc.dataset.name + 
              '&addr=' + loc.dataset.index;

        for(var i=0; i<arPerMain.length; i++){
          var data = arPerMain[i].dataset;
          params += '&bdate[]=' + data.bdate 
              + '&edate[]=' + data.edate 
              + '&btime[]=' + data.btime 
              + '&etime[]=' + data.etime;        
        }
        $.ajax({
          type: 'POST',
          url: MainConfig.AJAX_POST_LOCATION_DATA,
          data: params,
          dataType: 'json',
          success: function(result){ 
            callPopup(result.message);
            setNewPeriodContent(perMain[0]);
            perAddPer.show();
            checkTimePeriods(); // убираем возможность добавлять период, если время одинаковок
          }
        });
      }
    });
  });
  //
  //  Блок изменения периода
  //
  $(document).click(function(e){
    //  вызываем календарь в поле временного периода
    if($(e.target).closest('.city-period').length){
      var perMain = getPeriod(e.target),
          d = perMain.dataset,
          oldBDate = d.bdate,
          oldEDate = d.edate,
          oldBTime = d.btime,
          oldETime = d.etime;

      $(perMain).html($('#add-period-content').html());

      var perBdate = $(perMain).find('.city-bdate'),
          perEdate = $(perMain).find('.city-edate'),
          perBTime = $(perMain).find('.btime-input'),
          perETime = $(perMain).find('.etime-input'),
          perBtn = $(perMain).find('.save-per-btn'),
          perReset = $(perMain).find('.rst-per-btn');

      bDate = getDateFromData(perMain, 'bdate');
      if(diffDate(bDate,curDate) < 0){ // если дата устарела то нужно ее убрать, чтобы пользователь не тупил
        d.bdate='';
        d.edate='';
      }

      perBdate.find('span').append(d.bdate);
      perEdate.find('span').append(d.edate);
      perBTime.mask('99:99');
      perETime.mask('99:99');
      perBTime.val(d.btime);
      perETime.val(d.etime);

      // сохраняем измененный период
      perBtn.click(function(){
        d.bdate!='' ? remErr(perBdate) : addErr(perBdate);
        d.edate!='' ? remErr(perEdate) : addErr(perEdate);
        d.btime!='' ? remErr(perBTime) : addErr(perBTime);
        d.etime!='' ? remErr(perETime) : addErr(perETime);

        if(checkTime(d.btime, d.etime)){
          addErr(perBTime);
          addErr(perETime);
          d.btime = '';
          d.etime = '';
        }
        if(d.bdate!='' && d.edate!='' && d.btime!='' && d.etime!=''){
          result = confirm('Вы действительно хотите изменить период?');
          if(result){
            var loc = getLocation(perMain),
                arPerMain = $(loc).find('.erv-city__time'),
                params = 
                  'idloc=' + loc.dataset.idloc + 
                  '&idcity=' + loc.dataset.idcity + 
                  '&name=' + loc.dataset.name + 
                  '&addr=' + loc.dataset.index;

            for(var i=0; i<arPerMain.length; i++){
              var data = arPerMain[i].dataset;
              params += '&bdate[]=' + data.bdate 
                  + '&edate[]=' + data.edate 
                  + '&btime[]=' + data.btime 
                  + '&etime[]=' + data.etime;        
            }

            $.ajax({
              type: 'POST',
              url: MainConfig.AJAX_POST_LOCATION_DATA,
              data: params,
              dataType: 'json',
              success: function(result){ 
                callPopup(result.message);
                setNewPeriodContent(perMain);
                checkTimePeriods(); // убираем возможность добавлять период, если время одинаковок
              }
            });
          }
          else
            perReset.click();
        }
      });

      // отменяем изменения по периоду
      perReset.click(function(){
        d.bdate = oldBDate;
        d.edate = oldEDate;
        d.btime = oldBTime;
        d.etime = oldETime;
        setNewPeriodContent(perMain);
      });   
    }
  });
  //
  //  Удаление
  //  
  $(cModule).on('click', '.erv-city__close', function(){
      var parent = this.parentNode;    
    if($(parent).hasClass('erv-city__time')){   // удаляем период
      if(confirm('Вы действительно хотите удалить период?')){
            var loc = getLocation(parent),
                d = loc.dataset;
            if(!$(parent).find('.save-per-btn').length){         
              var arPerMain = $(loc).find('.erv-city__time');
              if(arPerMain.length>1){
                $(parent).remove();
                arPerMain = $(loc).find('.erv-city__time');
                params = 'idloc=' + d.idloc + '&idcity=' + d.idcity + '&name=' + d.name + '&addr=' + d.index;

                for(var i=0; i<arPerMain.length; i++){
                  var d = arPerMain[i].dataset;
                  params += '&bdate[]=' + d.bdate + '&edate[]=' + d.edate 
                          + '&btime[]=' + d.btime + '&etime[]=' + d.etime;        
                }
                $.ajax({
                  type: 'POST',
                  url: MainConfig.AJAX_POST_LOCATION_DATA,
                  data: params,
                  dataType: 'json',
                  success: function(res){ 
                    callPopup(res.message);
                    checkTimePeriods(); // убираем возможность добавлять период, если время одинаковок
                  }
                });
              }
              else{
                confirm('У локации должен быть установлен хотя бы один период. Вы можете изменить существующий период.');
              }
            }
            else{
              $(parent).fadeOut(); 
              setTimeout(function(){ 
                $(parent).remove();
                $(loc).find('.add-per-btn').show();
                checkTimePeriods(); // убираем возможность добавлять период, если время одинаковок
              },500); 
            }
          }       
    }
      else if($(parent).hasClass('erv-city__location')){  // удаляем локацию
      if(confirm('Вы действительно хотите удалить локацию?')){
        if(parent.dataset.idloc!='new'){
          $.ajax({
            type: 'POST',
            url: MainConfig.AJAX_POST_VE_LOCATION_DELETE,
            data: 'id=' + parent.dataset.idloc,
            dataType: 'json',
            success: function(res){ callPopup(res.message) }
          });
        }
        else{
          $(parent).siblings('.add-loc-btn').fadeIn();
        }
        $(parent).fadeOut();   
        setTimeout(function(){ $(parent).remove() },500);
      }
      }
      else if($(parent).hasClass('erv-city__item')){  // удаляем город
      if(confirm('Вы действительно хотите удалить город?')){
            if($('.erv-city__item').length>2){
              if(parent.dataset.id!='new'){
                $.ajax({
                  type: 'POST',
                  url: MainConfig.AJAX_POST_VE_CITY_DELETE_BLOCK,
                  data: 'id=' + parent.dataset.id,
                  dataType: 'json',
                  success: function(res){
                    callPopup(res.message); 
                    arIdCities.splice(arIdCities.indexOf(parent.dataset.idcity), 1);
                    // удаляем город из js массива
                  }
                });
              }
              $(parent).fadeOut();   
              setTimeout(function(){
                $(parent).remove();
                $('.add-city-btn').fadeIn();
                var arCityItems = $('.erv-city__item i');
                cityCnt = arCityItems.length,
                $.each(arCityItems, function(i){ $(this).text(i+1) });
              },500);           
            }
            else{
          confirm('В вакансии должен быть установлен хотя бы один город. Вы можете изменить существующий город.');
            }
      }
    }
  });
  //
  //  Ввод города
  //
  $(cModule).on('input','.city-input',function(e){ // input
      var $e = $(e.target),
          list = $e.siblings('.city-list'),
          main = $e.closest('.erv-city__label-input'),
          mainCity = getCity($e),
          idcity = Number(mainCity.dataset.idcity), 
          val = $e.val(),
          l = val.length;

    setFirstUpper(e.target);
    getSelectedCities();
    bShowCityList = true;
    clearTimeout(cityTimer);

    cityTimer = setTimeout(function(){
      var piece = val.toLowerCase(),     
          content = '';

      $(main).addClass('load'); // загрузка завершена
      getSelectedCities();
      $.ajax({
        type: 'POST',
        url: MainConfig.AJAX_GET_VE_GET_CITIES,
        data: 'query=' + val + '&idco=' + idCo,
        dataType: 'json',
        success: function(r){
          $.each(r.suggestions, function(){ // идем по результату
            if(this.data!=='man'){ // если что-то найдено
              id = Number(this.data);

              if(($.inArray(id, arIdCities)<0 || id==idcity) && this.value.toLowerCase().indexOf(piece)>=0){ // собираем список
                content += '<li data-id="' + this.data + '" data-metro="' + this.ismetro + '">' + this.value + '</li>';
              }
            }
          });
          if(bShowCityList)
            content ? $(list).html(content).fadeIn() : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
          $(main).removeClass('load'); // загрузка завершена
        }
      });
    },1000);
  });
  // focus инпута
  $(cModule).on('focus','.city-input',function(e){
    var list = $(e.target).siblings('.city-list'),
        main = $(e.target).closest('.erv-city__label-input'),
        mainCity = getCity(e.target),
        idcity = Number(mainCity.dataset.idcity),
        val = e.target.value,
        piece = val.toLowerCase(),
        content = '';

    $(e.target).val('').val(val);
    getSelectedCities();
    $(main).addClass('load'); // загрузка завершена

    $.ajax({
      type: 'POST',
      url: MainConfig.AJAX_GET_VE_GET_CITIES,
      data: 'query=' + val + '&idco=' + idCo,
      dataType: 'json',
      success: function(r){
        $.each(r.suggestions, function(){ // идем по результату
          if(this.data!=='man'){ // если что-то найдено
            id = Number(this.data);
            if(($.inArray(id, arIdCities)<0 || id==idcity) && this.value.toLowerCase().indexOf(piece)>=0){ // собираем список
              content += '<li data-id="' + this.data + '" data-metro="' + this.ismetro + '">' + this.value + '</li>';
            }
          }
        });
        content ? $(list).html(content).fadeIn() : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
        $(main).removeClass('load'); // загрузка завершена
      }
    });
  });
  // blur
  $(document).on('click',function(e){
    var $e = $(e.target),
        arC = $(cModule+' .erv-city__label-city div');

    if(!$e.closest('.erv-city__label-city div').length && !$e.is('.erv-city__label-city div')){
      for(var i=0; i<arC.length; i++){ // закрываем списки без фокуса
        var Cselect = $(arC[i]).find('.city-select'),
            Cinput = $(arC[i]).find('input'),
            Clist = $(arC[i]).find('.city-list'),
            v = $(Cselect).text();

        Cselect.text()==='' ? Cselect.hide() : Cselect.show();
        Cinput.val(v).hide();
        Clist.fadeOut();
      }
      bShowCityList = false;
    }
    else{ // клик по объектам списка
      var main = ($e.is('.erv-city__label-city div') ? e.target : $e.closest('.erv-city__label-city div'));

      if($e.is('li') && !$e.hasClass('emp')){ // выбираем из списка
        changeCityAjax(e.target);
      }
      else{
        for(var i=0; i<arC.length; i++){ // закрываем списки без фокуса
          var Cselect = $(arC[i]).find('.city-select'),
              Cinput = $(arC[i]).find('input'),
              Clist = $(arC[i]).find('.city-list'),
              v = $(Cselect).text();

          if(!$(arC[i]).is(main)){
            Cselect.show();
            Cinput.val(v).hide();
            Clist.fadeOut();
          }
          else{
            if($e.is('b'))
              Cinput.val('');
            Cinput.show().focus();
            Cselect.hide();
          }
        }
        bShowCityList = false;
      }
    }
  });
  //
  //  Ввод метро
  //
  $(cModule).on('click','.ev-metro-select',function(e){ 
    if(!$(e.target).is('b')) $(e.target).find('[name="m"]').focus();
  });
  //
  $(document).on('click', function(e){  // Закрываем список
    var sList = '.ev-metro-select',
        pList = '.metro-list';

    if($(e.target).is(pList+' li') && !$(e.target).hasClass('emp')){ // если кликнули по списку && если это не "Список пуст"
      loc = getLocation(e.target);
      itPList = $(e.target).closest(pList);
      itSList = $(itPList).siblings('.ev-metro-select');
      if(loc.dataset.idloc!=='new')
        changeLocationAjax(loc.dataset, e.target, 'adlmetro', {'id':e.target.dataset.id, 'name':$(e.target).text(), 'list':itSList});
      else{
        addMetro($(e.target).data('id'), $(e.target).text(), itSList);
        $(e.target).remove();
      }
      $(itPList).fadeOut();
    }
    if($(e.target).is(sList+' b')){ // удаление выбранного метро из списка
      metro = $(e.target).closest('li')[0];
      loc = getLocation(e.target);
      if(loc.dataset.idloc!=='new')
        changeLocationAjax(loc.dataset, metro, 'remmetro', {'id':metro.dataset.id});
      else
        $(metro).remove();
    }
    if(!$(e.target).is(sList) && !$(e.target).closest(sList).length)
      $(pList).fadeOut();
  });
  //
  $(cModule).on('input','.ev-metro-select input',function(e){ inputMetros(e) });
  $(cModule).on('focus','.ev-metro-select input',function(e){ inputMetros(e) });
  $(cModule).on('blur','.ev-metro-select input',function(e){ $(this).val('') });
  //
  function inputMetros(e){ 
    var arResult = [],
        content = '',
        sList = $(e.target).closest('.ev-metro-select'),
        pList = $(sList).siblings('.metro-list'),
        city = getCity(e.target).dataset.idcity,
        val = $(e.target).val().charAt(0).toUpperCase() + $(e.target).val().slice(1).toLowerCase(),
        piece = $(e.target).val().toLowerCase(),
        showList = true;
    arSelectId = GetSelectMetroes(sList);
    $(e.target).val(val);
    $(e.target).css({width:(val.length * 10 + 5)+'px'});

    if(val===''){
      $.each(arMetroes[city], function(id,name){ // список метро, если ничего не введено
        if($.inArray(id, arSelectId)<0)
          content += '<li data-id="' + id + '">' + name + '</li>';
      });
    }
    else{
      $.each(arMetroes[city], function(id,name){ // список метро, если что-то введено
        word = name.toLowerCase();

        if(word===piece){ // если введена именно станция полностью
          loc = getLocation(e.target);
          if(loc.dataset.idloc!=='new')
            changeLocationAjax(loc.dataset, sList, 'addmetro', {'id':id, 'name':name});
          else{
            addMetro(id,name,sList);
            showList = false;
          }
        }
        else if(word.indexOf(piece)>=0 && $.inArray(id, arSelectId)<0)
          arResult.push( {'id':id, 'name':name} );
      });
      arResult.length>0
      ? $.each(arResult, function(){ content += '<li data-id="' + this.id + '">' + this.name + '</li>' })         
      : content = '<li class="emp">Список пуст</li>';
    }
    $(pList).empty().append(content);
    if(showList){
      $(pList).fadeIn();
    }
    else{
      $(pList).fadeOut();
      $(e.target).val('');
    }
  }
  //  добавляем выбранное метро
  function addMetro(id,name,list){ 
    html = '<li data-id="' + id + '">' + name + '<b></b><input type="hidden" name="city[metro][]" value="' + id + '"/></li>';
    $(list).find('[data-id="0"]').before(html);
  }
  // считываем выбранное метро
  function GetSelectMetroes(list){
    var arId = [];
    $.each($(list).find('li'), function(){
      if($(this).data('id')!=0)
        arId.push(String($(this).data('id')));
    });
    return arId; 
  }
  //
  //    Работаем с датами
  //
  //    переключаем месяц
  $(cModule).on('click', '.mleft', function(e){
    var calendar = $(this).closest('.city-calendar'),
        mname = $(this).siblings('.mname');
    Calendar(calendar[0], mname[0].dataset.year, parseFloat(mname[0].dataset.month)-1);
  });
  $(cModule).on('click', '.mright', function(e){
    var calendar = $(this).closest('.city-calendar'),
        mname = $(this).siblings('.mname');
    Calendar(calendar[0], mname[0].dataset.year, parseFloat(mname[0].dataset.month)+1);
  });
  //  выбор даты
  $(document).on('click', '.city-calendar .day', function(){ checkDate(this) });
  // вызов календарей
  $(cModule).on('click', '.city-bdate', function(){ $(this).find('.city-calendar').fadeIn() });
  $(cModule).on('click', '.city-edate', function(){ $(this).find('.city-calendar').fadeIn() });
  //
  $(document).click(function(e){
    //  закрываем календарь
    if(!$('.city-bdate').is(e.target) && !$(e.target).closest('.city-bdate').length){
      $('.city-bdate .city-calendar').fadeOut();
      $('.city-bdate .city-calendar b').fadeOut();
    }
    if(!$('.city-edate').is(e.target) && !$(e.target).closest('.city-edate').length){
      $('.city-edate .city-calendar').fadeOut();
      $('.city-edate .city-calendar b').fadeOut();
    }
  });
  //
  //  Работа с полями локации
  //
  // изменение названия локации
  $(cModule).on('input', '.locname-input', function(){ $(this).val()!='' ? remErr(this) : addErr(this) });
  //
  $(cModule).on('blur', '.locname-input', function(){
    var val = $(this).val(),
        d = getLocation(this).dataset;

    if(d.idloc=='new'){
      val!='' ? remErr(this) : addErr(this);
      d.name = val;
    }
    else{
      if(val!=d.name && val!=''){
        changeLocationAjax(d, $(this), 'name');  
      }
      else{
        remErr(this);
        $(this).val(d.name);
      }
    }
  });
  // изменение адресе локации
  $(cModule).on('keyup', '.index-input', function(){ $(this).val()!='' ? remErr(this) : addErr(this) });
  //
  $(cModule).on('blur', '.index-input', function(){
    var val = $(this).val(),
        d = getLocation(this).dataset;

    if(d.idloc=='new'){
      val!='' ? remErr(this) : addErr(this);
      d.index = val;
    }
    else{
      if(val!=d.index && val!=''){
        changeLocationAjax(d, $(this), 'index');  
      }
      else{
        remErr(this);
        $(this).val(d.index);
      }
    }
  });
  //
  //  Ввод времени
  //
  // проверка ввода времени
  $(cModule).on('blur', '.btime-input', function(){ 
    var val = $(this).val();
    var main = ($(this).closest('.erv-city__time').length ? getPeriod(this) : getLocation(this));

    if(!getNum(val).length || val.substr(0,2)>=24 || val.substr(-2)>=60){
      addErr(this);
      main.dataset.btime = '';
    }
    else{
      remErr(this);
      main.dataset.btime = val;
    }
  });
  $(cModule).on('blur', '.etime-input', function(){
    var val = $(this).val();
    var main = ($(this).closest('.erv-city__time').length ? getPeriod(this) : getLocation(this));

    if(!getNum(val).length || val.substr(0,2)>=24 || val.substr(-2)>=60){
      addErr(this);
      main.dataset.etime = '';
    }
    else{
      remErr(this);
      main.dataset.etime = val;
    }
  });
  // события подсказок
  $('.erv__input').focus(function(){ 
    $(this).closest('.erv__label').addClass('focus');
    $(this).closest('.erv__label-half').addClass('focus');
  });
  $('.erv__input').blur(function(){ 
    $(this).closest('.erv__label').removeClass('focus');
    $(this).closest('.erv__label-half').removeClass('focus');
  });
  //  события поля заголовок
  $('#rv-vac-title').on('input', function(){
    var val = $(this).val();
    if(val.length>titleLen) 
      $(this).val(val.substr(0,titleLen));
  });
  //  события полей select
  $(document).on('click', function(e){
    for(var i=0; i<arSelect.length; i++){
      if(e.target.id == 'rv-'+arSelect[i]+'-veil'){
        $('#rv-'+arSelect[i]+'-list').fadeIn();
        $('#rv-'+arSelect[i]+'-list').closest('.erv__label').addClass('focus');
        $('#rv-'+arSelect[i]+'-list').closest('.erv__label-half').addClass('focus');
      }
      else if(
        $(e.target).is('#rv-'+arSelect[i]+'-list i') 
        || 
        !$(e.target).closest('#rv-'+arSelect[i]+'-list').length
        )
      {
        $('#rv-'+arSelect[i]+'-list').fadeOut();
        $('#rv-'+arSelect[i]+'-list').closest('.erv__label').removeClass('focus');
      }
    }
  });
  /*
  *
  */  // новый ввод вакансий
  $('#ev-posts-select').click(function(e){ 
    if(!$(e.target).is('i')){
      $('#ev-posts-list').fadeIn();
      $('#ev-posts-list input').focus();
    }
  });
  $('#ev-posts-list input').bind('input focus blur', function(e){
    var arResult = [],
      content = '',
      $pList = $('#ev-posts-list'),
      placeholder = $('#ev-posts-select').siblings('span'),
      val = $(this).val().charAt(0).toUpperCase() + $(this).val().slice(1).toLowerCase(),
      piece = $(this).val().toLowerCase(),
      showList = true;

    val = val.replace(/[^а-яА-ЯїЇєЄіІёЁ -]/g,'');
    arSelectId = GetSelectPosts();
    $(this).val(val);

    if(arSelectId.length) $(placeholder).hide(); 
    if(e.type!=='blur'){
      if(val===''){
        $.each(arPosts, function(){ // список вакансий если ничего не введено
          if($.inArray(this.id, arSelectId)<0)
            content += '<li data-id="' + this.id + '">' + this.name + '</li>';
        });
      }
      else{
        $.each(arPosts, function(){ // список вакансий если что-то введено
          word = this.name.toLowerCase();
          if(word===piece){ // если введена именно вакансия полностью
            addPost(this.id, this.name);
            showList = false;
          }
          else if(word.indexOf(piece)>=0 && $.inArray(this.id, arSelectId)<0)
            arResult.push( {'id':this.id, 'name':this.name} );
        });
        arResult.length>0
        ? $.each(arResult, function(){ content += '<li data-id="' + this.id + '">' + this.name + '</li>' })         
        : content = '<li class="emp">Список пуст</li>';
      }
      arList = $pList.find('li');
      for(var i=0; i<arList.length; i++){
        if(arList[i].dataset.id!='0')
          $(arList[i]).remove();
      }
      $pList.append(content);
      if(showList){
        $pList.fadeIn();
      }
      else{
        $pList.fadeOut();
        $(this).val('');
      }
    }
    else{ // если потерян фокус раньше времени
      //$(this).val('');
      GetSelectPosts().length ? $(placeholder).hide() : $(placeholder).show();
      GetSelectPosts().length ? remErr('.fav__select-posts') : addErr('.fav__select-posts');
    }
  });
  $(document).on('click', function(e){  // Закрываем список
    var sList = '#ev-posts-select',
      pList = '#ev-posts-list',
      addV = '#add-new-vac';

    if($(e.target).is(pList+' li') && !$(e.target).hasClass('emp')){ // если кликнули по списку && если это не "Список пуст" && 
      $(e.target).remove();
      $(sList).siblings('span').hide();
      addPost($(e.target).data('id'), $(e.target).text());
      $(pList).fadeOut();
    }
    if($(e.target).is(sList+' i')){ // удаление выбраной должности из списка
      var li = $(e.target).closest('li');

      if($(li).find('[name="post-self"]').length)
        $(addV).fadeIn();
      $(li).remove();
      GetSelectPosts().length ? $('#ev-posts-select').siblings('span').hide() : $('#ev-posts-select').siblings('span').show();
      GetSelectPosts().length ? remErr('.fav__select-posts') : addErr('.fav__select-posts');
    }
    if($(e.target).is(addV)) {
      var inp = $(addV).siblings('input'),
          val = $(inp).val();
      if(val.length<3) {
        confirm('Некорректное название вакансии');
      }
      else if(val.length) {
        $(sList).siblings('span').hide();
        html = '<li data-id="new">' + val 
          + '<i></i><input type="hidden" name="post-self" value="' 
          + val + '"/></li>';
        $('#ev-posts-select').append(html);
        remErr('.fav__select-posts');
        $(inp).val('');
        $(pList).fadeOut();
        $(addV).fadeOut();
      }
      else
        confirm('Поле не должно быть пустым');
    }
    if(!$(e.target).is(addV) && !$(e.target).is(sList) && !$(e.target).closest(sList).length && !$(e.target).is(pList+' input'))
      $(pList).fadeOut();
  });
  // сроки оплаты
  $('#rv-paylims-list').on('change', 'input', function(){
    $('#rv-paylims').val($(this).data('name'));
    if($(this).val()=='164'){
      $('#ev-custom-paylims').val($(this).data('name'));
    }
    $('#rv-paylims-list').fadeOut();
  });
  //  добавление нового срока оплаты
  $('#add-new-term').click(function(){
    var newPay = $('#inp-new-term').val(),
        rand = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
    if(newPay!==''){
      html = '<li>' + 
          '<input type="radio" name="user-attribs[paylims]" value="164" id="paylims-' + rand + '" data-name="' + newPay + '" checked>' + 
          '<label for="paylims-' + rand + '"><table><td><p>' + newPay + '</p><td><b></b></table></label>' + 
        '</li>';
      $('.erv__exp-new').before(html)
        .children('#inp-new-term').val('');
      $('#rv-paylims').val(newPay)
        .siblings('ul').fadeOut();
      $('#ev-custom-paylims').val(newPay);
    }
  });
  $('#inp-new-term').on('input',function(){
    $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1).toLowerCase());
  });
  // опыт работы
  $('#rv-expirience-list input').on('change', function(){
    var $this = $(this);
    if($this.is(':checked'))
      $('#rv-expirience').val($this.data('name'));
    $('#rv-expirience-list').fadeOut();
    checkField('#rv-expirience');
  });
  // properties
  $('#rv-hcolor-list input').on('change', function(){
    if($(this).is(':checked'))
      $('#rv-hcolor').val($(this).data('name'));
    $('#rv-hcolor-list').fadeOut();
    $(this).closest('.erv__label-half').removeClass('focus');
  });
  $('#rv-hlen-list input').on('change', function(){
    if($(this).is(':checked'))
      $('#rv-hlen').val($(this).data('name'));
    $('#rv-hlen-list').fadeOut();
    $(this).closest('.erv__label-half').removeClass('focus');
  });
  $('#rv-ycolor-list input').on('change', function(){
    if($(this).is(':checked'))
      $('#rv-ycolor').val($(this).data('name'));
    $('#rv-ycolor-list').fadeOut();
    $(this).closest('.erv__label-half').removeClass('focus');
  });
  $('#rv-chest-list input').on('change', function(){
    if($(this).is(':checked'))
      $('#rv-chest').val($(this).data('name'));
    $('#rv-chest-list').fadeOut();
    $(this).closest('.erv__label-half').removeClass('focus');
  }); 
  $('#rv-waist-list input').on('change', function(){
    if($(this).is(':checked'))
      $('#rv-waist').val($(this).data('name'));
    $('#rv-waist-list').fadeOut();
    $(this).closest('.erv__label-half').removeClass('focus');
  });
  $('#rv-thigh-list input').on('change', function(){
    if($(this).is(':checked'))
      $('#rv-thigh').val($(this).data('name'));
    $('#rv-thigh-list').fadeOut();
    $(this).closest('.erv__label-half').removeClass('focus');
  });
  //  событие полей возраста
  $('#rv-age-from, #rv-age-to').on('input', function(){
    var val = getNum($(this).val());
    if(val.length>ageLen) 
      val = val.substr(0,ageLen); // двузначного возраста достаточно
    $(this).val(val);
    checkField(this);
  });
  //  событие полей роста и веса
  $('#rv-user-height, #rv-user-weight').on('input', function(){
    var val = getNum($(this).val());
    if(val.length>hwLen) 
      val = val.substr(0,hwLen); // 3значного числа достаточно
    $(this).val(val);
  });  
  // событие ввода оплаты
  $('.erv__input-salary').on('input', function(){
    $(this).val(getNum($(this).val()));
  });
  //
  //    Проверка полей
  //
  $('.erv__required').bind('keyup change', function(){ checkField(this) });
  //
  $('.erv__button').click(function(e){
    e.preventDefault();
    var arElems = $('.erv__label-textarea .nicEdit-main'),
      errors = false;
    $('#rv-requirements').val($(arElems[0]).html());
    $('#rv-duties').val($(arElems[1]).html());
    $('#rv-conditions').val($(arElems[2]).html());

    $.each($('.erv__required'), function(){ 
      if(!checkField(this))
        errors = true; 
    });

    if(!$('#ev-posts-select [name="posts[]"]').length){
      addErr('.fav__select-posts');
      errors = true; 
    }
    
    var arErrors = $('.error');
    if(arErrors.length>0){
      $('html, body').animate({ scrollTop: $(arErrors[0]).offset().top-20 }, 1000);
    }
    if(!errors)
      $("#reg-vac-form").submit();
  });
  //  editor
  var requirements = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline', 'ol', 'ul'] });
  requirements.addInstance('rv-requirements');
  requirements.setPanel('rv-requirements-panel');

  var duties = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline', 'ol', 'ul'] });
  duties.addInstance('rv-duties');
  duties.setPanel('rv-duties-panel');

  var conditions = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline', 'ol', 'ul'] });
  conditions.addInstance('rv-conditions');
  conditions.setPanel('rv-conditions-panel');
  $('.nicEdit-main:first').addClass('erv__required');
  $('.nicEdit-main.erv__required').on('keyup', function(){ checkField(this) });
  // 
  // добавляем первый развернутый блок локации при загрузке страницы
  //
  var fCity = $('.erv-city__item:eq(0)');
  var fLocBtn = fCity.find('.add-loc-btn:eq(0)');
  fLocBtn.click();
  var fNewLoc = fLocBtn[0].previousSibling;

  tempBDate = getDateFromData(fCity[0], 'bdate'),
  tempEDate = getDateFromData(fCity[0], 'edate');
  // дата начала не прошла и меньше даты окончания
  if(
    diffDate(tempBDate,curDate) >= 0 && 
    diffDate(tempEDate,tempBDate) >= 0
    ){ 
    fNewLoc.dataset.bdate = fCity[0].dataset.bdate;
  }
  $(fNewLoc).find('.city-bdate span').append(fCity[0].dataset.bdate);
  // дата окончания не прошла и больше даты начала
  if(
    diffDate(tempEDate,curDate) >= 0 && 
    diffDate(tempEDate,tempBDate) >= 0
    ){ 
    fNewLoc.dataset.edate = fCity[0].dataset.edate;
  }
  $(fNewLoc).find('.city-edate span').append(fCity[0].dataset.edate);
  //
  //
  //
  checkTimePeriods(); // убираем возможность добавлять период, если время одинаковок
  //
  //  сохраняем данные полей
  //
  $.each($('.erv__input'), function(){
    var $it = $(this);
    if(
      !$it.closest('#city-module').length &&
      !$it.closest('#add-city-content').length &&
      !$it.closest('#add-loc-content').length &&
      !$it.closest('#period-content').length &&
      !$it.closest('#add-period-content').length
    ){
      arObj = { 'name':$it.attr('name'), 'type':$it.attr('type'), 'value':$it.val(), 'checked':'' };
      if($it.is('textarea'))
        arObj.type = 'textarea';
      if($it.attr('type')=='checkbox')
        arObj.checked = $it.is(':checked');
      arValues.push(arObj);
    }
  });

  $(document).on('click', function(e){
    if($(e.target).is('a')){
      var arElems = $('.erv__label-textarea .nicEdit-main'),
          arNewValues = [];

      $('#rv-requirements').val($(arElems[0]).html());
      $('#rv-duties').val($(arElems[1]).html());
      $('#rv-conditions').val($(arElems[2]).html());

      $.each($('.erv__input'), function(){
        var $it = $(this);
        if(
          !$it.closest('#city-module').length &&
          !$it.closest('#add-city-content').length &&
          !$it.closest('#add-loc-content').length &&
          !$it.closest('#period-content').length &&
          !$it.closest('#add-period-content').length
        ){
          arObj = { 'name':$it.attr('name'), 'type':$it.attr('type'), 'value':$it.val(), 'checked':'' };
          if($it.is('textarea'))
            arObj.type = 'textarea';
          if($it.attr('type')=='checkbox')
            arObj.checked = $it.is(':checked');
          arNewValues.push(arObj);
        }
      });

     /* var bChanged = false;
      for (var i=0; i<arValues.length; i++){
        if(arValues[i].type==='checkbox' && arValues[i].checked != arNewValues[i].checked)
          bChanged = true;
        else if(arValues[i].type==='textarea' && arValues[i].value!=arNewValues[i].value && arNewValues[i].value!=='<br>')
          bChanged = true;     
        else if(arValues[i].type!=='checkbox' && arValues[i].type!=='textarea' && arValues[i].value != arNewValues[i].value)
          bChanged = true;
      }

      if(bChanged && !confirm('Вы действительно хотите покинуть эту страницу? Не сохраненные данные будут утеряны.') ){
        e.preventDefault();     
      }*/
    }
  });
  //
  //
  //  ФУНКЦИИ
  //
  //
  function Calendar(item, year, month){
    var Dlast = new Date(year,month+1,0).getDate(),
      D = new Date(year,month,Dlast),
      DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
      DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
      calendar = '<tr>',
      month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
      date = new Date();

    if(DNfirst != 0){
      for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
    }else{
      for(var  i = 0; i < 6; i++) calendar += '<td>';
    }
    for(var  i = 1; i <= Dlast; i++){
      newDate = new Date(D.getFullYear(),D.getMonth(),i);
      if(i==date.getDate() && D.getFullYear()==date.getFullYear() && D.getMonth()==date.getMonth()){ // today
        calendar += '<td class="day today">' + i;
      }
      else if(Math.ceil((newDate - date)/(1000 * 60 * 60 * 24))<=0){ // переводим милисекунды в дни
        calendar += '<td class="day past">' + i;
      }
      else{
        calendar += '<td class="day">' + i;
      }
      if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
        calendar += '<tr>';
      }
    }
    for(var i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
    item.querySelector('tbody').innerHTML = calendar;
    monthName = item.querySelector('.mname');
    monthName.innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
    monthName.dataset.month = D.getMonth();
    monthName.dataset.year = D.getFullYear();
    if(item.querySelectorAll('tbody tr').length < 6){ // всегда 6 строк
      item.querySelector('tbody').innerHTML += '<tr><td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td>&nbsp;';
    }
  }
  //
  function checkDate(elem){
    var calendar = $(elem).closest('.city-calendar'),
        calendarType = $(calendar).data('type'),
        mname = $(calendar).find('.mname');

    if($(elem).closest('.erv-city__time').length>0)
      var main = getPeriod(elem);
    else if($(elem).closest('.erv-city__location').length>0)
      var main = getLocation(elem);
    else
      var main = getCity(elem);

    erBdate = $(main).find('.city-bdate b');
    erEdate = $(main).find('.city-edate b');        

    newDate = new Date(mname[0].dataset.year,mname[0].dataset.month, Number($(elem).text()));
    begDate = getDateFromData(main, 'bdate');
    endDate = getDateFromData(main, 'edate');

    if(calendarType=='bdate'){ // дата начала
      if(diffDate(newDate,curDate) >= 0){ // не прошедшая ли дата
        if(endDate){  // дата окончания уже есть
          diffDate(endDate,newDate) >= 0 // а вдруг позже даты окончания
          ? setDate(newDate, elem, main)
          : $(erBdate).show();
        }
        else{
          setDate(newDate, elem, main);
        }
      }
      else{
        $(erBdate).show();
      }
      if(endDate && diffDate(endDate,begDate) > 0)
        $(erEdate).hide();
      checkDateField(elem);
    }
    if(calendarType=='edate'){  // дата окончания
      if(diffDate(newDate,curDate) >= 0){ // не прошедшая ли дата
        if(begDate){  // дата начала уже есть
          diffDate(newDate,begDate) >= 0 // а вдруг раньше даты начала
          ? setDate(newDate, elem, main)
          : $(erEdate).show();
        }
        else{
          setDate(newDate, elem, main);
        }
      }
      else{
        $(erEdate).show();
      }
      if(begDate && diffDate(begDate,curDate) >= 0)
        $(erBdate).hide();
      checkDateField(elem);
    }
  }
  //
  function getDateFromData(e, type){
    var result = 0;
    if(e.dataset[type]!=''){
      arDate = e.dataset[type].split('.');
      result = new Date(Number(arDate[2]),Number(arDate[1]-1),Number(arDate[0]));
    }
    return result;
  }
  //
  function diffDate(date1, date2){
    miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
    return Math.ceil((date1 - date2) / miliToDay);
  }
  //
  function setDate(date, e, main){
    var d = Number(date.getDate()),
        m = Number(date.getMonth()),
        y = Number(date.getFullYear()),
        calendar = $(e).closest('.city-calendar'),
        type = $(calendar).data('type'),
        error = $(main).find('.city-'+type+' b');

    result = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + y;

    $(calendar).fadeOut();
    main.dataset[type] = result;
    $(main).find('.city-'+type+' span').text(result); 
    $(error).fadeOut();

    arDays = $('.city-'+type+' .day');
    $.each(arDays, function(){ $(this).removeClass('select') });
    $(e).addClass('select');
  }
  //
  function checkDateField(e){
    var main = getCity(e),
      bd = $(e).closest('.city-bdate'),
      ed = $(e).closest('.city-edate');

    if(bd.length>0){
      main.dataset.bdate!='' ? remErr(bd) : addErr(bd);
    }
    if(ed.length>0){
      main.dataset.edate!='' ? remErr(ed) : addErr(ed);
    }
  }
  //
  function checkTime(t1, t2){ // true = ошибка
    var res = false;
    if(t1!='' && t2!=''){
      arT1 = t1.split(':');
      arT2 = t2.split(':');
      if(Number(arT1[0]) > Number(arT2[0])){
        res = true;
      }
      else if(Number(arT1[0]) == Number(arT2[0]) && Number(arT1[1]) > Number(arT2[1])){
        res = true;
      }
    }
    return res;
  }
  function setNewPeriodContent(e){
    var content = $('#period-content').find('.erv-city__time').html();
    $(e).html(content);

    bd = getDateFromData(e, 'bdate');
    ed = getDateFromData(e, 'edate');

    /*cnt = diffDate(ed, bd) + 1;
    t = bd;
    content = '';
    for(var i=0; i<cnt; i++){
      d = Number(t.getDate());
      m = Number(t.getMonth());
      y = Number(t.getFullYear());
      res = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + y;
      content += '<tr><td>' + res + '<td>' + e[0].dataset.btime + '<td>-<td>' + e[0].dataset.etime;
      t.setDate(t.getDate()+1); // добавляем день
    }*/
    if(diffDate(ed, bd)){
      d = Number(bd.getDate());
      m = Number(bd.getMonth());
      y = Number(bd.getFullYear());
      d1 = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + ('0' + y).slice(-2);     
      d = Number(ed.getDate());
      m = Number(ed.getMonth());
      y = Number(ed.getFullYear());
      d2 = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + ('0' + y).slice(-2);
      content = 'c ' + d1 + ' по ' + d2 + ' ' + e.dataset.btime + '-' + e.dataset.etime; 
    }
    else{
      d = Number(bd.getDate());
      m = Number(bd.getMonth());
      y = Number(bd.getFullYear());
      content = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + ('0' + y).slice(-2) + ' ' + e.dataset.btime + '-' + e.dataset.etime;
    }
    $(e).find('table').append(content);
  }
  //
  function addErr(e){ 
    $(e).addClass('error');
    return false;
  }
  function remErr(e){ 
    $(e).removeClass('error');
    return true;
  }
  //
  function callPopup(m){
    var message = '<div>' + m + '</div>';
    ModalWindow.open({ content: message, action: { active: 0 }, additionalStyle:'dark-ver' });
  }
  //
  function changeLocationAjax(d, e, type, arPar = []){
    var AJAX_POST_VE_LOCATION_CHANGE = '/ajaxvacedit/locationdatachange/';

    result = confirm('Вы действительно хотите изменить локацию?');
    if(result){
      if(type==='remmetro'){
        arM = d.metro.split(',');
        arM.splice(arM.indexOf(arPar.id), 1);
        d.metro = arM.join(','); // заносим id метро
        $(e).remove();
      }
      else if(type==='addmetro'){
        arM = d.metro.split(',');
        arM.push(arPar.id);
        d.metro = arM.join(','); // заносим id метро
        addMetro(arPar.id, arPar.name, e);
      }
      else if(type==='adlmetro'){
        arM = d.metro.split(',');
        arM.push(arPar.id);
        d.metro = arM.join(','); // заносим id метро
        addMetro(arPar.id, arPar.name, arPar.list);
        $(e).remove();
      }
      else
        d[type] = e.val();

      $.ajax({
        type: 'POST',
        url: AJAX_POST_VE_LOCATION_CHANGE,
        data: 'idloc=' + d.idloc + '&name=' + d.name + '&addr=' + d.index + '&metro=' + d.metro,
        dataType: 'json',
        success: function(result){ callPopup(result.message) }
      });
    }
    else if($.inArray(type,['remmetro','addmetro','adlmetro'])<0)
      e.val(d[type]);
  }
  //
  function checkField(e){
    var val = $(e).val(),
      id = $(e).prop('id'),
      res = false;
    if(id=='rv-sex-man' || id=='rv-sex-woman'){
      if(!$('#rv-sex-man').is(':checked') && !$('#rv-sex-woman').is(':checked')){       
        res = addErr('[for=rv-sex-man]');
        addErr('[for=rv-sex-woman]');    
      }
      else{
        res = remErr('[for=rv-sex-man]');
        remErr('[for=rv-sex-woman]');
      }
    }
    else if(id=='rv-age-from' || id=='rv-age-to'){
      var $from = $('#rv-age-from'), 
          $to = $('#rv-age-to'),
          $parent = $from.closest('.erv__label');
          ageFrom = $from.val(), 
          ageTo = $to.val();

      if(ageFrom==''){
        res = addErr($from);
      }
      else if(ageFrom<ageMinLimit){
        res = addErr($from);
        addErr($parent);
      }
      else if(ageTo==''){
        res = remErr($from);
        remErr($to);
        remErr($parent);
      }
      else if(ageFrom>ageTo){
          res = addErr($from);
          addErr($to);
          addErr($parent);
      }
      else{
        res = remErr($from);
        remErr($to);
        remErr($parent);
      }
    }
    else if($(e).hasClass('erv__input-salary')){
      var arSalary = $('.erv__input-salary'), cntEmp = 0;
      $.each(arSalary, function(){ if($(this).val()==''){ cntEmp++ }});
      cntEmp==4 // 4 поля ввода
      ? $.each(arSalary, function(){ res=addErr(this) })
      : $.each(arSalary, function(){ res=remErr(this) });
    }
    else if($(e).hasClass('nicEdit-main')){
      res = ($(e).text().length>0 ? remErr(e) : addErr(e));
    }
    else{
      res = ((val=='' || val==null) ? addErr(e) : remErr(e));     
    }
    return res;
  }
  //
  //
  //
  // заносим актуальные даты начала и окончания
  /*arTemp = begWorkDate.split('.');
  begWorkDate = new Date(Number(arTemp[2]),Number(arTemp[1]-1),Number(arTemp[0]));
  arBDates.push(begWorkDate);
  arTemp = endWorkDate.split('.');
  endWorkDate = new Date(Number(arTemp[2]),Number(arTemp[1]-1),Number(arTemp[0]));
  arEDates.push(endWorkDate);*/
  // актууализация дат
  function changeDates(e,f){
    var $bd = $('#rv-vac-bdate'),
        $ed = $('#rv-vac-edate'),
        begDate = getDateFromData(e, 'bdate'),
        endDate = getDateFromData(e, 'edate');

    if(f==1){ // заносим даты в массив
      arBDates.push(begDate);
      arEDates.push(endDate);
    }
    if(f==1){ // заносим даты в массив
      arBDates.push(begDate);
      arEDates.push(endDate);
    }

    /*$.each(arBDates,function(){
      if(begWorkDate>this){
        $bd.text(this);
        begWorkDate = this;
      }
    });
    $.each(arEDates,function(){
      if(endWorkDate<this){
        $ed.text(this);
        endWorkDate = this;
      }
    });

    console.log(begWorkDate);
    console.log(endWorkDate);
    console.log(arEDates);
    console.log(arBDates);*/
  }
  //  добавляем выбранную должность
  function addPost(id,name){ 
    html = '<li data-id="' + id + '">' + name + '<i></i><input type="hidden" name="posts[]" value="' + id + '"/></li>';
    $('#ev-posts-select').append(html);
    remErr('.fav__select-posts'); 
  }
  //  считаем выбранные должности
  function GetSelectPosts(){
    var arId = [];
    $.each($('#ev-posts-select li'), function(){ arId.push(String($(this).data('id'))) });
    return arId; 
  }
  //
  //
  //
  //
  //
  function getCity(e){ return $(e).closest('.erv-city__item')[0] }
  function getLocation(e){ return $(e).closest('.erv-city__location')[0] }
  function getPeriod(e){ return $(e).closest('.erv-city__time')[0] }

  function getNum(e){ return e.replace(/\D+/g,'') }
  //
  function getSelectedCities(){
    arIdCities = [];
    $.each($(cModule+' .erv-city__item'), function(){
      if(this.dataset.idcity!='')
        arIdCities.push(Number(this.dataset.idcity));
    });
  }
  //
  function setFirstUpper(e){
    var split = $(e).val().split(' ');

    for(var i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    var split = $(e).val().split('-');
    for(var i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  }
  //
  function changeCityAjax(e){
    var main = getCity(e),
        select = $(main).find('.city-select'),
        input = $(main).find('.city-input'),
        list = $(main).find('.city-list'),
        content = $('#add-metro-content').html(),
        arLoc = $(main).find('.erv-city__location');

    if(main.dataset.idcity!=='' && main.dataset.idcity===e.dataset.id){
      v = select.text();
      input.val(v).hide();
      select.show();
      list.fadeOut();
    }
    else if(main.dataset.id!='new'){
      result = confirm('Вы действительно хотите изменить город?');
      if(result){
        $.ajax({
          type: 'POST',
          url: '/ajaxvacedit/citydatachange/',
          data: 'id=' + main.dataset.id + '&idcity=' + e.dataset.id,
          dataType: 'json',
          success: function(r){
            callPopup(r.message);
            main.dataset.idcity = e.dataset.id;
            v = $(e).text();
            input.val(v).hide();
            select.html(v+'<b></b>').show();
            list.fadeOut();
            for(var i=0; i<arLoc.length; i++){
              if($(arLoc[i]).find('.erv-city__label-lmetro').length)
                $(arLoc[i]).find('.erv-city__label-lmetro').remove();
              if(e.dataset.metro==='1')
                  $(arLoc[i]).find('.erv-city__label-lindex').after(content);
            }
            m = $(main).find('.erv-city__label-city .erv-city__label-input');
            remErr(m);
          }
        });        
      }
      else{
        v = select.text();
        input.val(v).hide();
        select.show();
        list.fadeOut();
      }
    }
    else{ // ввод нового города
      main.dataset.idcity = e.dataset.id;
      v = $(e).text();
      input.val(v).hide();
      select.html(v+'<b></b>').show();
      list.fadeOut();
      for(var i=0; i<arLoc.length; i++){    
        if(e.dataset.metro==='1'){
          if(!$(arLoc[i]).find('.erv-city__label-lmetro').length)
            $(arLoc[i]).find('.erv-city__label-lindex').after(content);
        }
        else if($(arLoc[i]).find('.erv-city__label-lmetro').length)
          $(arLoc[i]).find('.erv-city__label-lmetro').remove();
      }
      m = $(main).find('.erv-city__label-city .erv-city__label-input');
      remErr(m);
    }
  }
  //
  function checkTimePeriods(){
    arLoc = $(cModule).find('.erv-city__location');
    if(arLoc.length)
      for(var i=0; i<arLoc.length; i++){
        arPeriods = $(arLoc[i]).find('.erv-city__time');
        if(arPeriods.length==2){
          if(arPeriods[0].dataset.btime === arPeriods[1].dataset.btime && arPeriods[0].dataset.etime === arPeriods[1].dataset.etime)
            $(arLoc[i]).find('.add-per-btn').hide();
          else
            $(arLoc[i]).find('.add-per-btn').show();
        }
        else{
          if(arLoc[i].dataset.idloc!=='new')
            $(arLoc[i]).find('.add-per-btn').show();
        }
      }
  }
  /*
  *     Изменение заголовка
  */
  var $titleInput = $('.erv__label-title input'),
      firstTitle = $titleInput.val();

  $('.erv__title').click(function(){
    $('.erv__label-title').css({display:'inline-block'});
    $titleInput.val('').focus().val(firstTitle);
    $(this).hide();
  });
  $('.erv__label-title input').on('blur',function(){
    var t = $titleInput.val();
    if(t==='') {
      $titleInput.val(firstTitle);
    }
    else {
      $('.erv__title').text(t);
      firstTitle = t;
    }
    $('.erv__label-title').hide();
    $('.erv__title').show();
  });
  /*
  *     скроллинг к блоку
  */
  $('.erv__salary').click(function(){
    var b = $('.erv__label.erv__salary');
    $('html, body').animate({ scrollTop: $(b).offset().top-50 }, 1000);
  });
  $('.erv__publ-date').click(function(){
    var b = $('#city-module');
    $('html, body').animate({ scrollTop: $(b).offset().top-40 }, 1000);
  });
  
});