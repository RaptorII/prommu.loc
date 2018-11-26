'use strict'
var selectUsers = 0;
var ProjectPage = (function () {
	function ProjectPage() {
        this.init();
    }

    ProjectPage.prototype.init = function () {
    	let self = this;

        //      Главная страница
        $('#add-xls').click(self.addXlsFile);
        $('#save-project').click(self.checkErrors);
        $('.project__opt-btn').click(function() { self.showModule(this) });
        $('#add-xls-inp').change(function() { self.checkFormatFile(this) });
        $('#project-name').on('input', self.checkProjectName);
        $('#new-project').on(
            'click',
            '.city-del,.loc-del,.period-del,.invitation-del',
            function(){ self.removeElement(this) }
        );
        ProjectAddIndexProg.init(self); //  Страница добавления адресной программы
        ProjectAddPersonal.init(self);  //  Страница добавления персонала
        ProjectInvitePersonal.init(self);  //  Страница приглашения персонала
    };
    //      Проверка готовности для создания проекта
    ProjectPage.prototype.checkErrors = function () {
        let nameInp = $('#project-name'),
            name = $(nameInp).val();

        if(name.length<1) {
            $(nameInp).addClass('error');
        }
        else {
            let arP = $('#index .city-item'),
                arI = $('#invitation input'),
                empAddrProg = false,
                empAddrFile = !$('#add-xls-inp').val().length,
                empUsersSelect = !Number($('#mess-wcount-inp').val()),
                empUsersInvite = false;

            for (let i = 0, l = arP.length; i < l; i++) {
                let arInputs = $(arP[i]).find('input');

                for (let j = 0, n = arInputs.length; j < n; j++) {
                    let data_m = $(arInputs[j]).attr('data-checker');
                    if(data_m!='metro') {
                        let name = $(arInputs[j]).attr('name');
                        if ($.inArray(name, ['c', 'm']) < 0 && !arInputs[j].value.length)
                            empAddrProg = true;
                    }
                }
            }
            for (var i = 0, n = arI.length; i < n; i++) {
                if(
                    !$(arI[i]).hasClass('country-phone-search')
                    &&
                    !arI[i].value.length
                ) {
                    empUsersInvite = true;
                    break;
                }
            }

            if((empAddrProg && empAddrFile) && (empUsersSelect && empUsersInvite)) {
                MainProject.showPopup('error','full-in-create');
                return false;
            }
            else if(empAddrProg && empAddrFile) {
                MainProject.showPopup('error','addr-in-create');
                return false;
            }
            else if(empUsersSelect && empUsersInvite) {
                MainProject.showPopup('error','users-in-create');
                return false;
            }
            else $('#new-project').submit();
        }
    }
    //      Выбор файла для адресной программы
    ProjectPage.prototype.addXlsFile = function () {
        $('#add-xls-inp').click();
    }
    //      Проверка формата файла .XLS .XLSX
    ProjectPage.prototype.checkFormatFile = function () {
        let self = this,
            $inp = $('#add-xls-inp'),
            $name = $('#add-xls-name'),
            arExt = $inp.val().match(/\\([^\\]+)\.([^\.]+)$/);

        if(arExt[2]!=='xls' && arExt[2]!=='xlsx'){
            MainProject.showPopup('error','xls');
            self.addXlsFile;
            $inp.val('');
            $name.text('').hide();
        }
        else{
            let fd = new FormData;

            fd.append('xls', $inp.prop('files')[0]);
            fd.append('type', 'xls-index');

            $.ajax({
                type: 'POST',
                url: '/ajax/Project',
                data: fd,
                processData: false,
                contentType: false,
                success: function (r){
                    r = JSON.parse(r);
                    if(r.error==true)
                    {
                        r.message = (r.message!=null ? r.message : 'load-file');
                        MainProject.showPopup('error',r.message);
                        $inp.val('');
                    }
                    else
                    {
                        $name.text(arExt[1] + '.' + arExt[2]).show();
                        $('[data-btn="index"]').addClass('disable');
                    }
                }
            });
        }
    }
    //      показать нужный модуль
    ProjectPage.prototype.showModule = function (e) {
        let self = this,
            data = e.dataset.event,
            arBlocks = $('.project__module'),
            name = self.checkProjectName();

        if(data==undefined)
            return false;

        if(!name){
            MainProject.showPopup('notif','name');
            return false;
        }

        for (let i = 0, n = arBlocks.length; i<n; i++) {
            $(arBlocks[i]).attr('id')===data
            ? $(arBlocks[i]).fadeIn()
            : $(arBlocks[i]).hide();
        }
    }
    //      Проверка ввода названия проекта
    ProjectPage.prototype.checkProjectName = function () {
        let $it = $('#project-name'),
            val = $it.val(),
            arTitles = $('.project__title span');

        if(!val.length){
            $it.addClass('error');
            return false;
        }
        else{
            $it.removeClass('error');
            val = val.substring(0, 70);
            $it.val(val);
            for (let i = 0, n = arTitles.length; i < n; i++)
                $(arTitles[i]).text('«' + val + '»');
            return true;
        }
    }
    //      удаление элементов
    ProjectPage.prototype.removeElement = function (e) {
      let $e = $(e),
          error = -1,
          query = true,
          arErr = ['city-del','loc-del','period-del','invitation-del'],
          arItems, item, main;

      if($e.hasClass('city-del')) {
          arItems = $('#index .city-item');
          item = $e.closest('.city-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будет удален город и все связанные данные.\n'
              +'Вы действительно хотите это сделать?');
          }
          else error = 0;
      }
      else if($e.hasClass('loc-del')) {
          main = $e.closest('.city-item')[0]
          arItems = $(main).find('.loc-item');
          item = $e.closest('.loc-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будет удалена ТТ и все связанные данные.\n'
              +'Вы действительно хотите это сделать?');
          }
          else error = 1;
      }
      else if($e.hasClass('period-del')) {
          main = $e.closest('.loc-item')[0]
          arItems = $(main).find('.period-item');
          item = $e.closest('.period-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будет удален период.\n'
              +'Вы действительно хотите это сделать?');
          }
          else error = 2;
      }
      else if($e.hasClass('invitation-del')) {
          arItems = $('#invitation .invitation-item');
          item = $e.closest('.invitation-item')[0];
          if(arItems.length>1) {
            error = -1;
            query = confirm('Будут удалены данные контакта.\n'
              +'Вы действительно хотите это сделать?');
            selectUsers--; // счетчик пользователей в проекте
          }
          else error = 3;
      }

      if(!query)
        return false;
      if(error>=0)
          MainProject.showPopup('error',arErr[error]);
      else {
          $(item).fadeOut();
          setTimeout(function(){ $(item).remove() },500);
      }
    }

    return ProjectPage;
}());
/*
*
*       Страница добавления адресно программы
*
*/
var ProjectAddIndexProg = (function () {
    ProjectAddIndexProg.prototype.Project = [];
    ProjectAddIndexProg.prototype.arIdCities = [];

    function ProjectAddIndexProg() {
        var self = this;
        ProjectAddIndexProg.winObj = self;
    }
    ProjectAddIndexProg.init = function (project) {
        new ProjectAddIndexProg();

        let self = ProjectAddIndexProg.winObj,
            arCalendars = document.querySelectorAll('.calendar');

        self.Project = project;

        $('#save-index').click( function(){ self.saveProgram(this) });
        $('#index').on('click', '.add-loc-btn', function(){ self.addLocation(this) });
        $('#index').on('click', '.add-period-btn', function() { self.addPeriod(this) });
        // работа с городами
        $('#index').on('input', '.city-inp', function() { self.inputCity(this) });
        $('#index').on('focus', '.city-inp', function() { self.focusCity(this) });
        $('#add-city-btn').click(function(){ self.addCity() });
        // работаем с метро
        $('#index').on('input','.metro-inp',function(){ self.inputMetros(this) });
        $('#index').on('focus','.metro-inp',function(){ self.focusMetro(this) });
        // работа с датами
        for (let i=0; i<arCalendars.length; i++)
            self.buildCalendar(arCalendars[i]);
        $('#index').on('click', '.period-item span', function() { self.showCalendar(this) });
        $('#index').on('click', '.mleft', function(){ self.changeMonth(this,-1) });
        $('#index').on('click', '.mright', function(){ self.changeMonth(this,1) });
        $('#index').on('click', '.calendar .day', function(e){ self.checkDate(e.target) });
        // работа с временем
        $('.time-inp').mask('99:99');
        $('#index').on('blur', '.time-inp', function() { self.checkTime(this) });
        // работаем с должностями
        $('#index').on('input','.post-inp',function(){ self.inputPost(this) });
        $('#index').on('focus','.post-inp',function(){ self.focusPost(this) });
        // обрабатываем клики
        $(document).on('click', function(e) {
            self.checkCity(e.target);
            self.checkMetro(e.target);
            self.checkPost(e.target);
            self.closureCalendar(e.target);
        });
    }
    //
    //      ГОРОДА
    //
    //      добавление города
    ProjectAddIndexProg.prototype.addCity = function () {
        let self = this,
          arCities = $('#index .city-item'),
          content = $('#city-content').html(),
          empty = self.checkFields(),
          arIdies = self.getNewId(),
          arTime;

        if (!empty) {
          $(arCities[arCities.length-1]).after(content);
          content = $('#index .city-item:eq(-1)');
          $(content).append($('#loc-content').html());
          content = $(content).find('.loc-item:eq(-1)')[0];
          content.dataset.id = arIdies.location;
          $(content).append($('#period-content').html());
          content = $(content).find('.period-item')[0];
          content.dataset.id = arIdies.period;
          arTime = $(content).find('.time-inp');
          $(arTime).mask('99:99');
        }
        else
          MainProject.showPopup('notif', 'add-city');
    }
    //      ввод города
    ProjectAddIndexProg.prototype.inputCity = function (e) {
        let self = this,
            val = $(e).val();

        clearTimeout(MainProject.bAjaxTimer);
        self.setFirstUpper(e);

        MainProject.bAjaxTimer = setTimeout(function(){ self.getAjaxCities(val, e) },1000);
    }
    //      фокус поля города
    ProjectAddIndexProg.prototype.focusCity = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.setFirstUpper(e);
        self.getAjaxCities(val, e);
    };
    //      запрос списка городов
    ProjectAddIndexProg.prototype.getAjaxCities = function (val, e) {
        let self = this,
            $e = $(e),
            list = $e.siblings('.select-list')[0],
            main = $e.closest('.city-field')[0],
            mainCity = $e.closest('.city-item')[0],
            idcity = Number(mainCity.dataset.city),
            piece = val.toLowerCase(),
            content = '',
            params = 'query=' + val + '&idco=' + MainProject.idCo;

        self.getSelectedCities();
        $(main).addClass('load'); // загрузка началась

        $.ajax({
            type: 'POST',
            url: MainConfig.AJAX_GET_VE_GET_CITIES,
            data: params,
            dataType: 'json',
            success: function(r) {
                for (let i in r.suggestions) {
                    let item = r.suggestions[i],
                        id = +item.data;

                    if(isNaN(item.data))
                        break;

                    if(
                        ( $.inArray(id, self.arIdCities)<0 || id==idcity )
                        &&
                        item.value.toLowerCase().indexOf(piece) >= 0
                    ){ // собираем список
                        content += '<li data-id="'
                            + item.data + '" data-metro="' + item.ismetro
                            + '">' + item.value + '</li>';
                    }
                }
                content
                ? $(list).html(content).fadeIn()
                : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
                $(main).removeClass('load'); // загрузка завершена
            }
        });
    }
    //      фокус инпута и выбор города
    ProjectAddIndexProg.prototype.checkCity = function (e) {
        let self = this,
            $e = $(e),
            data = e.dataset,
            arCities = $('#index .city-field');

       if( !$e.closest('.city-field').length && !$e.is('.city-field') ) {
            for(let i=0; i<arCities.length; i++){ // закрываем списки без фокуса
                let cSelect = $(arCities[i]).find('.city-select'),
                    cInput = $(arCities[i]).find('.city-inp'),
                    cList = $(arCities[i]).find('.select-list'),
                    v = $(cSelect).text();

                cSelect.text()==='' ? cSelect.hide() : cSelect.show();
                cInput.val(v).hide();
                cList.fadeOut();
            }
        }
        else{ // клик по объектам списка
            if( $e.is('li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('.city-item')[0],
                    select = $(main).find('.city-select'),
                    inpText = $(main).find('.city-inp'),
                    list = $(main).find('.select-list'),
                    input = $(inpText).siblings('[type="hidden"]');

                if(main.dataset.city!=='' && main.dataset.city===data.id) {
                    let v = select.text();
                    inpText.val(v).hide();
                    select.show();
                }
                else { // ввод нового города
                    let v = $(e).text();

                    main.dataset.city = data.id;
                    input.val(data.id);
                    inpText.val(v).hide();
                    select.html(v+'<b></b>').show();

                    if(data.metro==='1' && !$(main).find('.metro-item').length) {
                        let mContent = $('#metro-content').html(),
                            arRows = $(main).find('.loc-item .project__index-row');

                        for (var i = 0, n = arRows.length; i < n; i++) {
                            $(arRows[i]).prepend(mContent);
                            /*let loc = $(arRows[i]).closest('.loc-item')[0],
                                inp = $(arRows[i]).find('input')[0],
                                name = '[' + data.id + '][' + loc.dataset.id + ']';
                            $(inp).attr('name','metro' + name);*/
                        }
                    }
                    else if($(main).find('.metro-item').length) {
                        $(main).find('.metro-item').remove();
                    }
                    //
                    let arLocs = $(main).find('.loc-item'),
                        arPers = $(main).find('.period-item'),
                        name = '';

                    for (let i = 0, n = arLocs.length; i < n; i++) {
                        let idL = arLocs[i].dataset.id,
                            arLocInp = $(arLocs[i]).find('.loc-field input');

                        name = '[' + data.id + '][' + idL + ']';
                        if(data.metro==='1') {
                            $(arLocInp[1]).attr('name','metro' + name);
                            $(arLocInp[2]).attr('name','lindex' + name);
                            $(arLocInp[3]).attr('name','lname' + name);
                        }
                        else {
                            $(arLocInp[0]).attr('name','lindex' + name);
                            $(arLocInp[1]).attr('name','lname' + name);
                        }

                        for (let i = 0, n = arPers.length; i < n; i++) {
                            let idP = arPers[i].dataset.id,
                                arPerInp = $(arPers[i]).find('input');

                            name += '[' + idP + ']';
                            $(arPerInp[1]).attr('name','post' + name);
                            $(arPerInp[2]).attr('name','bdate' + name);
                            $(arPerInp[3]).attr('name','edate' + name);
                            $(arPerInp[4]).attr('name','btime' + name);
                            $(arPerInp[5]).attr('name','etime' + name);
                        }
                    }

                }
                list.fadeOut();
            }
            else{
                let main = $e.is('.city-field') ? e : $e.closest('.city-field')[0];
                for(let i=0; i<arCities.length; i++) { // закрываем списки без фокуса
                    let cSelect = $(arCities[i]).find('.city-select'),
                        cInput = $(arCities[i]).find('.city-inp'),
                        cList = $(arCities[i]).find('.select-list'),
                        v = $(cSelect).text();

                    if( !$(arCities[i]).is(main) ) {
                        cSelect.show();
                        cInput.val(v).hide();
                        cList.fadeOut();
                    }
                    else{
                        if( $e.is('b') )
                            cInput.val('');
                        cInput.show().focus();
                        cSelect.hide();
                    }
                }
            }
        }
    }
    //      правильный ввод названия города
    ProjectAddIndexProg.prototype.setFirstUpper = function (e) {
        let split = $(e).val().split(' ');

        for(let i=0, len=split.length; i<len; i++)
            split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
        $(e).val(split.join(' '));

        split = $(e).val().split('-');
        for(let i=0, len=split.length; i<len; i++)
            split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
        $(e).val(split.join('-'));
    }
    //       получить выбранные города
    ProjectAddIndexProg.prototype.getSelectedCities = function () {
        let self = this;

        self.arIdCities = [];

        $.each($('#index .city-item'), function(){
            if(this.dataset.city!=='')
                self.arIdCities.push(Number(this.dataset.city));
        });
    }
    //
    //      МЕТРО
    //
    //      Ввод метро
    ProjectAddIndexProg.prototype.inputMetros = function (e) {
        let self = this,
            val = $(e).val();

        clearTimeout(MainProject.bAjaxTimer);
        MainProject.bAjaxTimer = setTimeout(function(){ self.getAjaxMetros(val, e) },1000);
    }
    //      фокус поля метро
    ProjectAddIndexProg.prototype.focusMetro = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.getAjaxMetros(val, e);
    };
    //      запрос списка метро
    ProjectAddIndexProg.prototype.getAjaxMetros = function (val, e) {
        let self = this,
            $e = $(e),
            main = $e.closest('.metro-field')[0],
            mainCity = $e.closest('.city-item')[0],
            list = $(main).find('.select-list')[0],
            input = $(main).find('[type="hidden"]')[0],
            idcity = Number(mainCity.dataset.city),
            params = 'id=' + idcity + '&query=' + val + '&select=',
            content = '';

        $(main).addClass('load'); // загрузка началась

        $.ajax({
            type: 'POST',
            url: '/ajaxvacedit/vegetmetros/',
            data: params,
            dataType: 'json',
            success: function(metros) {
                if(metros.error!==true)
                    for (let i in metros)
                        content += '<li data-id="' + metros[i].id + '">'
                            + metros[i].name + '</li>';

                content
                ? $(list).html(content).fadeIn()
                : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
                $(main).removeClass('load'); // загрузка завершена
            }
        });
    }
    //      Установка метро
    ProjectAddIndexProg.prototype.checkMetro = function (e) {
        let self = this,
            $e = $(e),
            arMetros = $('#index .metro-field');

        if( !$e.closest('.metro-field').length && !$e.is('.metro-field') ) {
            for(let i=0; i<arMetros.length; i++){ // закрываем списки без фокуса
                let cSelect = $(arMetros[i]).find('.metro-select'),
                    cInput = $(arMetros[i]).find('.metro-inp'),
                    cList = $(arMetros[i]).find('.select-list'),
                    v = $(cSelect).text();

                cSelect.text()==='' ? cSelect.hide() : cSelect.show();
                cInput.val(v).hide();
                cList.fadeOut();
            }
        }
        else{ // клик по объектам списка
            if( $e.is('li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('.metro-item')[0],
                    select = $(main).find('.metro-select'),
                    inpText = $(main).find('.metro-inp'),
                    list = $(main).find('.select-list'),
                    input = $(inpText).siblings('[type="hidden"]'),
                    v = $(input).val();

                if(v!=='' && v===e.dataset.id) {
                    let v = select.text();
                    inpText.val(v).hide();
                    select.show();
                }
                else { // ввод нового города
                    let v = $(e).text();

                    input.val(e.dataset.id);
                    inpText.val(v).hide();
                    select.html(v+'<b></b>').show();
                }
                list.fadeOut();
            }
            else{
                let main = $e.is('.metro-field') ? e : $e.closest('.metro-field')[0];
                for(let i=0; i<arMetros.length; i++) { // закрываем списки без фокуса
                    let cSelect = $(arMetros[i]).find('.metro-select'),
                        cInput = $(arMetros[i]).find('.metro-inp'),
                        cList = $(arMetros[i]).find('.select-list'),
                        v = $(cSelect).text();

                    if( !$(arMetros[i]).is(main) ) {
                        cSelect.show();
                        cInput.val(v).hide();
                        cList.fadeOut();
                    }
                    else{
                        if( $e.is('b') )
                            cInput.val('');
                        cInput.show().focus();
                        cSelect.hide();
                    }
                }
            }
        }
    }
    //
    //      ЛОКАЦИИ
    //
    //      добавление локации
    ProjectAddIndexProg.prototype.addLocation = function (e) {
        let self = this,
          main = $(e).closest('.city-item')[0],
          idC = main.dataset.city,
          arLoc = $(main).find('.loc-item'),
          newLoc = $('#loc-content').html(),
          newPeriod = $('#period-content').html(),
          empty = self.checkFields(),
          arIdies = self.getNewId(),
          arLocInp, arPerInp, row, arTime;

        if (!empty) {
          $(main).append(newLoc);
          arLoc = $(main).find('.loc-item')
          newLoc = arLoc[arLoc.length-1];
          newLoc.dataset.id = arIdies.location;
          name = '[' + idC + '][' + arIdies.location + ']';

          if($(main).find('.metro-item').length) {// если есть метро
            row = $(newLoc).find('.loc-field');
            $(row).prepend($('#metro-content').html());
            arLocInp = $(newLoc).find('.loc-field input');
            $(arLocInp[1]).attr('name','metro' + name);
            $(arLocInp[2]).attr('name','lindex' + name);
            $(arLocInp[3]).attr('name','lname' + name);
          }
          else {
            arLocInp = $(newLoc).find('.loc-field input');
            $(arLocInp[0]).attr('name','lindex' + name);
            $(arLocInp[1]).attr('name','lname' + name);
          }
          $(newLoc).append(newPeriod);
          arPerInp = $(newLoc).find('.period-item input');

          name += '[' + arIdies.period + ']';
          $(arPerInp[1]).attr('name','post' + name);
          $(arPerInp[2]).attr('name','bdate' + name);
          $(arPerInp[3]).attr('name','edate' + name);
          $(arPerInp[4]).attr('name','btime' + name);
          $(arPerInp[5]).attr('name','etime' + name);

          arTime = $(newLoc).find('.time-inp');
          $(arTime).mask('99:99');
        }
        else
          MainProject.showPopup('notif', 'add-tt');
    }
    //
    //      ДАТА
    //
    //      Создание календарей
    ProjectAddIndexProg.prototype.buildCalendar = function (item, year, month) {
        year = (typeof year=="undefined" ? new Date().getFullYear() : year);
        month = (typeof month=="undefined" ? new Date().getMonth() : month);

        let self = this,
            Dlast = new Date(year,month+1,0).getDate(),
            D = new Date(year,month,Dlast),
            DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
            DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
            content = '<tr>',
            arMonth = [
                "Январь","Февраль","Март","Апрель",
                "Май","Июнь","Июль","Август",
                "Сентябрь","Октябрь","Ноябрь","Декабрь"
            ],
            date = new Date(),
            main = $(item).closest('.period-item')[0],
            parent = $(item).closest('.period-field')[0],
            arCalendars = $(main).find('.period-field'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            type,body,mName,nDays,newDate,res;

        date.setHours(0, 0, 0, 0);

        if(begDate!=='')
            begDate = self.getDateFromData(begDate);
        if(endDate!=='')
            endDate = self.getDateFromData(endDate);

        for( let i=0, n=arCalendars.length; i<n; i++ )
            if( $(arCalendars[i]).is(parent) )
                type = i;

        if(DNfirst != 0) {
            for(let i = 1; i < DNfirst; i++) content += '<td>';
        } else {
            for(let i = 0; i < 6; i++) content += '<td>';
        }
        for(let i = 1; i <= Dlast; i++) {
            content += '<td class="day';
            newDate = new Date(D.getFullYear(),D.getMonth(),i);
            newDate.setHours(0, 0, 0, 0);
            res = self.diffDate(newDate,date);

            if(res==0)
                content += ' today'; // today
            else if(res<0)
                content += ' nofit'; // прошедшее
            if(type==0 && endDate>0 && self.diffDate(newDate,endDate)>0)
                content += ' nofit'; // обозначаем недоступные дни
            else if(type==1 && begDate>0 && self.diffDate(newDate,begDate)<0)
                content += ' nofit'; // обозначаем недоступные дни
            else if(endDate==='' && self.diffDate(newDate,begDate)==0)
                content += ' select'; // обозначаем выделенные дни
            else if(begDate==='' && self.diffDate(newDate,endDate)==0)
                content += ' select'; // обозначаем выделенные дни
            else if(
                endDate>0 && begDate>0
                &&
                self.diffDate(newDate,begDate)>=0
                &&
                self.diffDate(newDate,endDate)<=0
            )
                content += ' select'; // обозначаем выделенные дни

            content += '">' + i;
            if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0)
                content += '<tr>';
        }
        for(let i = DNlast; i < 7; i++) content += '<td>&nbsp;';

        body = item.querySelector('tbody');
        body.innerHTML = content;
        mName = item.querySelector('.mname');
        mName.innerHTML = arMonth[D.getMonth()] +' '+ D.getFullYear();
        mName.dataset.month = D.getMonth();
        mName.dataset.year = D.getFullYear();

        nDays = item.querySelectorAll('tbody tr');
        if(nDays.length < 6) { // всегда 6 строк
            content = '<tr>';
            for(let i=0; i<7; i++)
                content += '<td class="empty">&nbsp;';
            body.innerHTML += content;
        }
    }
    //      Проверка даты
    ProjectAddIndexProg.prototype.checkDate = function (day) {
        let self = this,
            $it = $(day),
            main = $it.closest('.period-item')[0],
            arCalendars = $(main).find('.period-field'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            parent = $it.closest('.period-field')[0],
            data = $(parent).find('.mname')[0].dataset,
            calendar = $(parent).find('.calendar')[0],
            output = $(parent).find('span')[0],
            input = $(parent).find('input')[0],
            d = Number($(day).text()),
            m = Number(data.month),
            y = Number(data.year),
            newDate = new Date(y, m, d),
            res;

        if( $(day).hasClass('empty')  || $(day).hasClass('nofit') )
            return false;

        $(calendar).fadeOut();
        res = ('0' + Number($(day).text())).slice(-2) + '.'
            + ('0' + (Number(data.month) + 1)).slice(-2)
            + '.' + data.year;

        $(output).text(res);
        $(input).val(res);
        self.buildCalendar(arCalendars[0], y, m);
        self.buildCalendar(arCalendars[1], y, m);
    }
    //      Определение разницы во времени
    ProjectAddIndexProg.prototype.diffDate = function (date1, date2) {
        let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
    }
    //      форматируем в формат даты
    ProjectAddIndexProg.prototype.getDateFromData = function (date) {
        let arDate = date.split('.'),
            obj = new Date(
                Number(arDate[2]),
                Number(arDate[1]-1),
                Number(arDate[0])
            );
        return obj.setHours(0,0,0,0);
    }
    //      Изменение месяца
    ProjectAddIndexProg.prototype.changeMonth = function (e, m) {
        let self = this,
            calendar = $(e).closest('.calendar')[0],
            data = $(e).siblings('.mname')[0].dataset,
            newMonth = parseFloat(data.month)+m;

        self.buildCalendar(calendar, data.year, newMonth);
    }
    //      Вывод календаря
    ProjectAddIndexProg.prototype.showCalendar = function (e) {
        let calendar = e.nextElementSibling;
        $(calendar).fadeIn();
    }
    //      Закрытие календаря
    ProjectAddIndexProg.prototype.closureCalendar = function (e) {
        let arCalendars = $('#index .calendar');

        for(let i=0, n=arCalendars.length; i<n; i++) {
            if($('.calendar').is(e) && !$(arCalendars[i]).is(e)) { // это точно календарь
                $(arCalendars[i]).fadeOut();
            }
            else if($(e).closest('.calendar').length) { // это составные календаря
                let calendar = $(e).closest('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else if($('.period-item span').is(e)) { // это поле даты
                let calendar = $(e).siblings('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else // это что-то другое
                $(arCalendars[i]).fadeOut();
        }
    }
    //
    //      Время
    //
    //      Добавление периода
    ProjectAddIndexProg.prototype.addPeriod = function (e) {
        let self = this,
            empty = self.checkFields(),
            main = $(e).closest('.loc-item')[0],
            idL = main.dataset.id,
            city = $(e).closest('.city-item')[0],
            idC = city.dataset.city,
            newPeriod = $('#period-content').html(),
            arIdies = self.getNewId(),
            arPerInp, arPers, name;

        if(!empty) {
            $(main).append(newPeriod);
            arPers = $(main).find('.period-item');
            arPers[arPers.length-1].dataset.id = arIdies.period;
            arPerInp = $(arPers[arPers.length-1]).find('input');
            name = '[' + idC + '][' + idL + '][' + arIdies.period + ']';
            $(arPerInp[1]).attr('name','post' + name);
            $(arPerInp[2]).attr('name','bdate' + name);
            $(arPerInp[3]).attr('name','edate' + name);
            $(arPerInp[4]).attr('name','btime' + name);
            $(arPerInp[5]).attr('name','etime' + name);
            $(main).find('.time-inp').mask('99:99');
        }
        else
            MainProject.showPopup('notif', 'add-period');
    }
    //      Проверка времени
    ProjectAddIndexProg.prototype.checkTime = function (e) {
        let self = this,
            $e = $(e),
            main = $e.closest('.period-item'),
            arTimes = $(main).find('.time-item input'),
            arT1 = $(arTimes[0]).val().split(':'),
            arT2 = $(arTimes[1]).val().split(':'),
            t = $(arTimes[0]).is(e) ? 0 : 1;

        if(arT1.length==2) {
            arT1[0] = Number(arT1[0])>23 ? '23' : arT1[0];
            arT1[1] = Number(arT1[1])>59 ? '59' : arT1[1];
            self.setTime(arTimes[0], arT1);
        }
        if(arT2.length==2) {
            arT2[0] = Number(arT2[0])>23 ? '23' : arT2[0];
            arT2[1] = Number(arT2[1])>59 ? '59' : arT2[1];
            self.setTime(arTimes[1], arT2);
        }

        if(arT1.length!=2 || arT2.length!=2)
            return false;

        arT1[0] = Number(arT1[0]);
        arT1[1] = Number(arT1[1]);
        arT2[0] = Number(arT2[0]);
        arT2[1] = Number(arT2[1]);

        if(isNaN(arT1[0]) || isNaN(arT1[1]) || isNaN(arT2[0]) || isNaN(arT2[1]))
            return false;

        if(
            (arT1[0] > arT2[0])
            ||
            ( (arT1[0] == arT2[0]) && (arT1[1] > arT2[1]) )
            ||
            ( (arT1[0] == arT2[0]) && (arT1[1] == arT2[1]) )
        ) {
            MainProject.showPopup('error', 'time');
            $(arTimes[t]).val('');
        }
    }
    //      Установка времени
    ProjectAddIndexProg.prototype.setTime = function (e, arT) {
        arT[0] = ('0' + arT[0]).slice(-2);
        arT[1] = ('0' + arT[1]).slice(-2);
        $(e).val(arT[0] + ':' + arT[1]);
    }
    //
    //      Должность
    //
    //      Ввод должности
    ProjectAddIndexProg.prototype.inputPost = function (e) {
        this.getPosts(e.value, e);
    }
    //      фокус поля должности
    ProjectAddIndexProg.prototype.focusPost = function (e) {
        let val = $(e).val();
        $(e).val('').val(val);
        this.getPosts(val, e);
    };
    //      запрос списка должностей
    ProjectAddIndexProg.prototype.getPosts = function (val, e) {
        let self = this,
            v = e.value.replace(/[^а-яА-ЯїЇєЄіІёЁ\- ]/g,''),
            main = $(e).closest('.post-field')[0],
            list = $(main).find('.select-list'),
            arLi = $(list).find('li'),
            cnt = 0;

        v = v.charAt(0).toUpperCase() + v.slice(1).toLowerCase();
        e.value = v;

        for (var i=1; i < arLi.length; i++) {
            let vNew = $(arLi[i]).text();

            if( vNew.indexOf(v) >= 0 ) {
                $(arLi[i]).show();
                cnt++;
            }
            else {
                $(arLi[i]).hide();
            }
        }
        cnt ? $(arLi[0]).hide() : $(arLi[0]).show();
        $(list).show();
    }
    //      Установка должности
    ProjectAddIndexProg.prototype.checkPost = function (e) {
        let self = this,
            $e = $(e),
            arPosts = $('#index .post-field');

        if( !$e.closest('.post-field').length && !$e.is('.post-field') ) {
            for(let i=0; i<arPosts.length; i++){ // закрываем списки без фокуса
                let cSelect = $(arPosts[i]).find('.post-select'),
                    cInput = $(arPosts[i]).find('.post-inp'),
                    cList = $(arPosts[i]).find('.select-list'),
                    v = $(cSelect).text();

                cSelect.text()==='' ? cSelect.hide() : cSelect.show();
                cInput.val(v).hide();
                cList.fadeOut();
            }
        }
        else{ // клик по объектам списка
            if( $e.is('li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('.post-item')[0],
                    select = $(main).find('.post-select'),
                    inpText = $(main).find('.post-inp'),
                    list = $(main).find('.select-list'),
                    input = $(inpText).siblings('[type="hidden"]'),
                    v = $(input).val();

                if(v!=='' && v===e.dataset.id) {
                    let v = select.text();
                    inpText.val(v).hide();
                    select.show();
                }
                else { // ввод нового города
                    let v = $(e).text();

                    input.val(e.dataset.id);
                    inpText.val(v).hide();
                    select.html(v+'<b></b>').show();
                }
                list.fadeOut();
            }
            else{
                let main = $e.is('.post-field') ? e : $e.closest('.post-field')[0];
                for(let i=0; i<arPosts.length; i++) { // закрываем списки без фокуса
                    let cSelect = $(arPosts[i]).find('.post-select'),
                        cInput = $(arPosts[i]).find('.post-inp'),
                        cList = $(arPosts[i]).find('.select-list'),
                        v = $(cSelect).text();

                    if( !$(arPosts[i]).is(main) ) {
                        cSelect.show();
                        cInput.val(v).hide();
                        cList.fadeOut();
                    }
                    else{
                        if( $e.is('b') )
                            cInput.val('');
                        cInput.show().focus();
                        cSelect.hide();
                    }
                }
            }
        }
    }
    //      Проверка заполненности полей
    ProjectAddIndexProg.prototype.checkFields = function () {
        let arr = $('#index .city-item'),
            empty = false;

        for (let i = 0, l = arr.length; i < l; i++) {
            let arInputs = $(arr[i]).find('input');

            for (let j = 0, n = arInputs.length; j < n; j++) {
                let data_m = $(arInputs[j]).attr('data-checker');
                if(data_m!='metro') {
                    let name = $(arInputs[j]).attr('name');
                    if ($.inArray(name, ['c', 'm', 'p']) < 0 && !arInputs[j].value.length)
                        empty = true;
                }
            }
        }
        return empty;
    }
    // сохранение программы
    ProjectAddIndexProg.prototype.saveProgram = function (e) {
        let self = this;

        if(!self.checkFields()) {
            self.Project.showModule(e);
            $('[data-btn="xls"]').addClass('disable');
        }
        else {
            MainProject.showPopup('notif','save-program');
        }
    }
    //    получаем уникальные ID
    ProjectAddIndexProg.prototype.getNewId = function () {
        let arR = [], arT = [], r = (9999 - 1000 + 1);

        $.each($('#index .loc-item'), function(){
          arT.push(this.dataset.id);  
        });
        do {
          arR.location = Math.floor(Math.random() * r) + 1000;
        } while ($.inArray(arR.location,arT)>=0);

        arT = [];

        $.each($('#index .period-item'), function(){
          arT.push(this.dataset.id);  
        });
        do {
          arR.period = Math.floor(Math.random() * r) + 1000;
        } while ($.inArray(arR.period,arT)>=0);
        return arR;
    }
    return ProjectAddIndexProg;
}());
/*
*
*       Страница добавления персонала на проект
*
*/
var ProjectAddPersonal = (function () {
    ProjectAddPersonal.prototype.Project = [];
    ProjectAddPersonal.prototype.arSelectIdies = [];

    function ProjectAddPersonal() {
        var self = this;
        ProjectAddPersonal.winObj = self;
    }
    ProjectAddPersonal.init = function (project) {
        new ProjectAddPersonal();

        let self = ProjectAddPersonal.winObj;

        self.Project = project;

        //      просмотреть все вакансии
        $('#addition').on('click','.more-posts',function(){
            $(this.parentNode).css({height:'initial'});
            $(this).remove();
        });
        //      прячем фильтр для моб. разрешения
        $(window).on('load resize',function(){ self.visibilityFilter('load') });
        //      устанавливаем выбрть все/снять все вакансии
        $('#addition').on('change','.filter-posts input', function(){ 
            self.changeSelPosts(this) 
        })
        //      прячем фильтр для моб. разрешения
        .on('click','.filter__vis',function(){ 
            self.visibilityFilter('click')
        })
        .on('click','.filter__item-name',function(){
            self.visibilityTab(this) // вкладки фильтра
        })  
        .on('change','.filter-sex input',
            // подгрузка данных для пола, и дополнительно
            function(){ setTimeout(self.getAppsAjax(), 300) }
        )
        .on('change','.filter-additional input',
            function(){ setTimeout(self.getAppsAjax(), 300) }
        )
        .on('click','.filter__age-btn',
            function(){ self.getAppsAjax()}  // подгрузка данных для возраста
        )
        .on('click', '.paging-wrapp a', 
            function(e){ self.getAppsAjax(e) } // подгрузка данных при перелистывании
        )
        .on('change', '.promo_inp', 
            function(){ self.addWorkers(this) } //  выбор работников
        )
        .on('change', '#all-workers', 
            function(){ self.addWorkers(this) } //  выбрать всех
        )
        .on('input','#filter-city .city-inp',function(){ self.inputFilterCity(this) })
        .on('focus','#filter-city .city-inp',function(){ self.focusFilterCity(this) })
        .on('click','#filter-city .filter-city-select',function(e){
            if(!$(e.target).is('b'))
                $(e.target).find('.city-inp').focus();
        })
        .on('click','#workers-btn',function(){ self.checkAddition() });
        // закрытие списка городов
        $(document).on('click', function(e) { self.checkFilterCity(e.target) });
    }
    //      устанавливаем выбрть все/снять все для вакансий
    ProjectAddPersonal.prototype.changeSelPosts = function (e) {
        let self = this,
            arCheckbox = $('.filter-posts input');

        if($(e).is(arCheckbox[0])) {
            for (var i=1; i<arCheckbox.length; i++)
                $(e).is(':checked')
                ? $(arCheckbox[i]).prop('checked',true)
                : $(arCheckbox[i]).prop('checked',false);
        }
        setTimeout( self.getAppsAjax(), 300);
    }
    ProjectAddPersonal.prototype.getAppsAjax = function (e) {
        let self = this,
            params = '',
            $content = $('#promo-content'),
            arInputs = $('#promo-filter input');

        for (let i = 0, n = arInputs.length; i<n; i++) {
            let t = $(arInputs[i]).attr('type'),
                n = $(arInputs[i]).attr('name'),
                v = $(arInputs[i]).val();

            if(
                (t==='checkbox' && $(arInputs[i]).is(':checked'))
                ||
                t==='text'
                ||
                n==='cities[]'
            )
                params += n + '=' + v + '&';
        }

        if(e){  // прокрутка страниц
            e.preventDefault(); // для пагинации
            params += e.target.href.slice(e.target.href.indexOf('page='));
        }
        $('.filter__veil').show(); // процесс загрузки

        $.ajax({
            type: 'GET',
            url: window.location.pathname,
            data: params,
            success: function(res){
                $content.html(res);
                if(e){   // постраничное обновление
                    $('html, body').animate({
                        scrollTop: $content.offset().top - 100 },
                        700
                    );//прокручиваем к заголовку

                    $.each($content.find('.promo_inp'), function(){
                        if($.inArray(this.value, self.arSelectIdies)>=0)
                            $(this).prop('checked',true);
                    });
                }
                else{
                    self.arSelectIdies = [];
                    $('#mess-workers').val(self.arSelectIdies);
                    $('#mess-wcount').html(0);
                    $('#mess-wcount-inp').val(0);
                    $('#all-workers').prop('checked',false);
                }
                $('.filter__veil').hide();
            }
        });
    }
    //      Прячем фильтр для моб. разрешения
    ProjectAddPersonal.prototype.visibilityFilter = function (event) {
        if(event=='click') {
            $('.filter__vis').hasClass('active')
                ? $('#promo-filter').fadeOut()
                : $('#promo-filter').fadeIn();
            $('.filter__vis').toggleClass('active');
        }
        else {
            if($(window).width() < '768')
                $('.filter__vis').hasClass('active')
                    ? $('#promo-filter').show()
                    : $('#promo-filter').hide();
            else
                $('#promo-filter').show();
        }
    }
    //      Видимость вкладок пользователя
    ProjectAddPersonal.prototype.visibilityTab = function (e) {
        let $e = $(e);
        if($e.hasClass('opened')){
            $e.siblings('.filter__item-content').slideUp(200);
            setTimeout(function(){
                $e.removeClass('opened');
                $e.siblings('.filter__item-content').removeClass('opened');
            },200);
        }
        else{
            $e.addClass('opened');
            $e.siblings('.filter__item-content').slideDown(500);
            $e.siblings('.filter__item-content').addClass('opened');
        }
    }
    //      Выбор соискателей при добавлении
    ProjectAddPersonal.prototype.addWorkers = function (e) {
        let self = this,
            $e = $(e),
            arInputs = $('#promo-content').find('.promo_inp');

        if( $e.attr('id')==='all-workers' ) {
            if($e.is(':checked')) {
                let i = 0;

                while(selectUsers < maxUsersInProject) {
                    self.arSelectIdies.push(arIdies[i]);
                    selectUsers++;
                    i++;
                }
                $.each(arInputs, function(){
                    if($.inArray(this.value, self.arSelectIdies)>=0)
                        $(this).prop('checked',true);
                });
                if(selectUsers >= maxUsersInProject) {
                    arIdies.length != self.arSelectIdies.length
                    ? MainProject.showPopup('notif','added-max-users')
                    : MainProject.showPopup('notif','max-users');
                }
                if(arIdies.length != self.arSelectIdies.length) // выключаем "Выбрать всех" если сработало ограничение
                   setTimeout(function(){ $e.prop('checked',false) },500); 
            }
            else {
                self.arSelectIdies = [];
                $.each(arInputs, function(){ $(this).prop('checked',false) });
            }
        }
        else {
            let id = $e.val();

            if($e.is(':checked')){
                // записуем выбраный ID
                if($.inArray(id, self.arSelectIdies)<0){
                    if(selectUsers < maxUsersInProject) {
                        selectUsers++;
                        self.arSelectIdies.push(id)  
                    }
                    else {
                        MainProject.showPopup('notif','max-users');
                        setTimeout(function(){ $e.prop('checked',false) },10);
                    }
                };
                if(self.arSelectIdies.length == arIdies.length) // arIdies берем с вьюхи
                    $('#all-workers').prop('checked',true);
            }
            else {
                // убираем ID
                selectUsers--;
                if($.inArray(id, self.arSelectIdies)>=0)
                    self.arSelectIdies.splice(self.arSelectIdies.indexOf(id),1);
                $('#all-workers').prop('checked',false);
            }
        }
        $('#mess-workers').val(self.arSelectIdies);
        $('#mess-wcount').html(self.arSelectIdies.length);
        $('#mess-wcount-inp').val(self.arSelectIdies.length);
    }
    //      Ввод города в фильтре
    ProjectAddPersonal.prototype.inputFilterCity = function (e) {
        let self = this,
            val = $(e).val();

        $(e).css({width:(val.length * 10 + 5)+'px'});
        clearTimeout(MainProject.bAjaxTimer);
        MainProject.bAjaxTimer = setTimeout(function(){ self.getAjaxFilterCities(val, e) },1000);
    }
    //      фокус поля города в фильтре
    ProjectAddPersonal.prototype.focusFilterCity = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.getAjaxFilterCities(val, e);
    };
    //      запрос списка городов из фильтра
    ProjectAddPersonal.prototype.getAjaxFilterCities = function (val, e) {
        let self = this,
            $e = $(e),

            main = $e.closest('.filter-city-select')[0],
            list = $(main).siblings('.select-list')[0],
            arInputs = $('.filter-city-select').find('[type="hidden"]'),
            params = 'query=' + val + '&idco=' + MainProject.idCo,
            piece = val.toLowerCase(),
            arIdCities = [],
            content = '';

        if(arInputs.length)
            for (let i = 0, n = arInputs.length; i < n; i++)
                arIdCities.push($(arInputs[i]).val());

        $(main).addClass('load'); // загрузка началась

        $.ajax({
            type: 'POST',
            url: MainConfig.AJAX_GET_VE_GET_CITIES,
            data: params,
            dataType: 'json',
            success: function(r) {
                for (let i in r.suggestions) {
                    let item = r.suggestions[i],
                        id = item.data;

                    if(isNaN(item.data))
                        break;

                    if(
                        $.inArray(id, arIdCities)<0
                        &&
                        item.value.toLowerCase().indexOf(piece) >= 0
                    ){ // собираем список
                        content += '<li data-id="'
                            + item.data + '" data-metro="' + item.ismetro
                            + '">' + item.value + '</li>';
                    }
                }
                content
                ? $(list).html(content).fadeIn()
                : $(list).html('<li class="emp">Список пуст</li>').fadeIn();

                $(main).removeClass('load'); // загрузка завершена
            }
        });
    }
    //      Установка города в фильтре
    ProjectAddPersonal.prototype.checkFilterCity = function (e) {
        let self = this,
            $e = $(e),
            fCity = $('#filter-city .select-list');

        if( !$e.closest('#filter-city').length && !$e.is('#filter-city') ) {
            $('#filter-city .city-inp').val('').css({width:'5px'});
            $('#filter-city .select-list').fadeOut();
        }
        else{ // клик по объектам списка
            if( $e.is('.select-list li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('#filter-city')[0],
                    select = $(main).find('.filter-city-select'),
                    inpText = $(main).find('.city-inp'),
                    list = $(main).find('.select-list');

                inpText.val('').css({width:'5px'});
                $(select).find('[data-id="0"]').before(
                    '<li>' + $e.text() + '<b></b>' +
                    '<input name="cities[]" type="hidden" value="' +
                    e.dataset.id + '"/></li>');
                list.fadeOut();
                setTimeout( self.getAppsAjax(), 300);
            }
            else if($e.is('.filter-city-select b')) { // удаление выбранного метро из списка
                $e.closest('li').remove();
                setTimeout( self.getAppsAjax(), 300);
            }
        }
    }
    //
    ProjectAddPersonal.prototype.checkAddition = function () {
        let btn = document.querySelector('#workers-btn');
        if($('#mess-workers').val()!=='')
            this.Project.showModule(btn);
        else
            MainProject.showPopup('notif','addition');
    }
    return ProjectAddPersonal;
}());
/*
*
*       Страница приглашения персонала на проект
*
*/
var ProjectInvitePersonal = (function () {
    ProjectInvitePersonal.prototype.arOldPhones = [];
    ProjectInvitePersonal.prototype.keyCode = 0;
    ProjectInvitePersonal.prototype.hasInvitation = false;

    function ProjectInvitePersonal() {
        var self = this;
        ProjectInvitePersonal.winObj = self;
    }
    ProjectInvitePersonal.init = function (project) {
        new ProjectInvitePersonal();

        let self = ProjectInvitePersonal.winObj;

        self.Project = project;
        //      события нажатия кнопок
        $('#add-prsnl-btn,#save-prsnl-btn').click(function(){ 
            self.checkInvitations(this)
        });
        //      события заполнения полей
        window.phoneCode._create();
        self.arOldPhones[0] = '';
        $(document).keydown(function(e){ self.keyCode = e.keyCode }); // ловим код клавиши
        $('#invitation').on(
            'input',
            '.invite-inp.name,.invite-inp.sname,.invite-inp.phone',
            function(e){ self.inputInvitation(this,e.type) }
        ).on(
            'blur',
            '.invite-inp.email,.invite-inp.phone',
            function(e){ self.inputInvitation(this,e.type) }
        );

    }
    //
    ProjectInvitePersonal.prototype.inputInvitation = function (item,event) {
        let self = this,
            $e = $(item),
            v = $e.val(),
            id = $e.closest('.invitation-item')[0].dataset.id;

        if ($e.hasClass('name') || $e.hasClass('sname')) {
            v = v.replace(/[^а-яА-ЯїЇєЄіІёЁ-]/g,'');
            v = v.charAt(0).toUpperCase() + v.slice(1);
            $e.val(v);
        }
        if (event=='focusout' && $e.hasClass('email') && v!=='') {
            let ePattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if(!ePattern.test(v)) {
                MainProject.showPopup('error', 'bad-email');
                $e.val('');
                return false;
            }
        }
        if ($e.hasClass('phone') && v!=='') {
            v = v.replace(/\D+/g,'');
            let nV = '',
                code = item.nextElementSibling.value,
                l = v.length,
                phoneLen = 10;

            if (event==='input') {
                if(code.length==3){ // UKR
                    phoneLen = 9;
                    if(self.keyCode==8){ //backspace
                        if($.inArray(l,[8,6,4,3,1])>=0)
                           nV = self.arOldPhones[id].slice(0, -1);
                        if($.inArray(l,[7,5,2])>=0)
                            nV = self.arOldPhones[id].slice(0, -2);
                    }
                    else{
                        if(l>=1) nV = '(' + v.slice(0,1);
                        if(l>=2) nV += v.slice(1,2) + ')';
                        if(l>=3) nV += v.slice(2,3);
                        if(l>=4) nV += v.slice(3,4);
                        if(l>=5) nV += v.slice(4,5) + '-';
                        if(l>=6) nV += v.slice(5,6);
                        if(l>=7) nV += v.slice(6,7) + '-';
                        if(l>=8) nV += v.slice(7,8);
                        if(l>=9) nV += v.slice(8,9);
                    }
                }
                if(code.length==1){ // RF
                    phoneLen = 10;
                    if(self.keyCode==8){ //backspace
                        if($.inArray(l,[9,7,5,4,2,1])>=0)
                           nV = self.arOldPhones[id].slice(0, -1);
                        if($.inArray(l,[8,6,3])>=0)
                            nV = self.arOldPhones[id].slice(0, -2);
                    }
                    else{
                        if(l>=1) nV = '(' + v.slice(0,1);
                        if(l>=2) nV += v.slice(1,2);
                        if(l>=3) nV += v.slice(2,3) + ')';
                        if(l>=4) nV += v.slice(3,4);
                        if(l>=5) nV += v.slice(4,5);
                        if(l>=6) nV += v.slice(5,6) + '-';
                        if(l>=7) nV += v.slice(6,7);
                        if(l>=8) nV += v.slice(7,8) + '-';
                        if(l>=9) nV += v.slice(8,9);
                        if(l>=10) nV += v.slice(9,10);
                    }
                }
                self.arOldPhones[id] = nV;
                $e.val('').val(nV);
            }
            if (event==='focusout') {
                if(code.length==3) phoneLen = 9;
                if(l<phoneLen) MainProject.showPopup('error', 'bad-phone');
            }
        }
    }
    //      Проверка по нажатии кнопок
    ProjectInvitePersonal.prototype.checkInvitations = function (e) {

        let self = this,
            arInputs = $('#invitation input'),
            save = $(e).hasClass('save-btn') ? true : false,
            empty = false;

        for (var i = 0, n = arInputs.length; i < n; i++) {
            if(
                !$(arInputs[i]).hasClass('country-phone-search')
                &&
                !arInputs[i].value.length
            ) {
                empty = true;
                break;
            }
            if($(arInputs[i]).hasClass('email')) {
                let ePattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                if(!ePattern.test($(arInputs[i]).val())) {
                    $(arInputs[i]).val('');
                    empty = true;
                    break;
                }
            }
        }
        if (!empty) {
            if(!self.hasInvitation) {
                selectUsers++; // счетчик пользователей в проекте
                self.hasInvitation = true;
            }
            if(save) {
                self.Project.showModule(e);
                return true;
            }
            if(selectUsers >= maxUsersInProject) {
              MainProject.showPopup('notif','max-users');
              return true;  
            }
            selectUsers++; // счетчик пользователей в проекте

            let html = $('#invitation-content').html(),
                main = document.getElementById('invitation'),
                arInv = main.getElementsByClassName('invitation-item'),
                id = Number(arInv[arInv.length-1].dataset.id) + 1;

            $(arInv[arInv.length-1]).after(html);
            $(arInv[arInv.length-1]).attr('data-id',id); //Присвоение data-id для новых invitation-item

            arInputs = $(arInv[arInv.length-1]).find('.invite-inp');

            $(arInputs[0]).attr('name','inv-name['+id+']');
            $(arInputs[1]).attr('name','inv-sname['+id+']');
            $(arInputs[2]).attr('name','inv-phone['+id+']');
            $(arInputs[3]).attr('name','inv-email['+id+']');

            let p = $(arInv[arInv.length-1]).find('.invite-inp.phone');
            window.phoneCode._create();
            $(arInv[arInv.length-1])
                .find('[type="hidden"]')
                .attr('name','prfx-phone['+id+']');
            self.arOldPhones[id] = '';
        }
        else
            save
            ? MainProject.showPopup('error', 'save-notif')
            : MainProject.showPopup('notif', 'add-notif');

    }

    return ProjectInvitePersonal;
}());
/*
*
*/
$(document).ready(function () {
	new ProjectPage();
});