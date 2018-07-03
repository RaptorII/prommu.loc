'use strict'
var ProjectPage = (function () {
    ProjectPage.prototype.bAjaxTimer = false;
    ProjectPage.prototype.arIdCities = [];
    ProjectPage.prototype.idCo = $('#index').data('country');
    ProjectPage.prototype.arOldPhones = [];
    ProjectPage.prototype.keyCode = 0;
    ProjectPage.prototype.arSelectIdies = [];

	function ProjectPage() {
        let self = this;
        self.init();
    }

    ProjectPage.prototype.init = function () {
    	let self = this,
            arCalendars = document.querySelectorAll('.calendar');
        //
        //      Главная страница
        //
        $('#add-xls').click(self.addXlsFile);
        $('#save-project').click(self.checkErrors);
        $('.project__opt-btn').click(function() { self.showModule(this) });
        $('#add-xls-inp').change(function() { self.checkFormatFile(this) });
        $('#project-name').on('input', self.checkProjectName); // работа с названием проекта
        // 
        //      Страница создания адресной программы
        //
        // события кнопок программы
        $('#save-index').click(function(){
            let params = $('#new-project').serializeArray();

            console.log(params);
        });

        $('#index').on('click', '.add-loc-btn', function(){ self.addLocation(this) });
        $('#index').on('click', '.add-period-btn', function() { self.addPeriod(this) });
        // работа с городами
        $('#index').on('input', '.city-inp', function() { self.inputCity(this) });
        $('#index').on('focus', '.city-inp', function() { self.focusCity(this) });
        $('#add-city-btn').click(function(){ self.addCity() });
        // работаем с метро
        $('#index').on('input','.metro-inp',function(){ self.inputMetros(this) });
        $('#index').on('focus','.metro-inp',function(){ self.focusMetro(this) });
        $('#index').on('click','.metro-select',function(e){ 
            if(!$(e.target).is('b'))
                $(e.target).find('.metro-inp').focus();
        });
        // работа с датами
        for (let i=0; i<arCalendars.length; i++)
            self.buildCalendar(arCalendars[i]);
        $('#index').on('click', '.project__index-period span', function() { self.showCalendar(this) });
        $('#index').on('click', '.mleft', function(){ self.changeMonth(this,-1) });
        $('#index').on('click', '.mright', function(){ self.changeMonth(this,1) });
        $('#index').on('click', '.calendar .day', function(e){ self.checkDate(e.target) });
        // работа с временем
        $('.time-inp').mask('99:99');
        $('#index').on('blur', '.time-inp', function() { self.checkTime(this) });
        // обрабатываем клики
        $(document).on('click', function(e) {
            self.checkCity(e.target);
            self.checkMetro(e.target);
            self.closureCalendar(e.target);
            self.checkFilterCity(e.target);
        });
        //
        //      Страница приглашения персонала
        //
        //      события нажатия кнопок
        $('#add-prsnl-btn,#save-prsnl-btn').click(function(){ 
            self.checkInvitations(this)
        });
        //      события заполнения полей
        window.phoneCode.element = $('#invitation .invite-inp.phone');
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
        //
        //      Страница добавления персонала
        //
        //      просмотреть все вакансии
        $('.more-posts').click(function(){ 
            $(this.parentNode).css({height:'initial'});
            $(this).remove();
        });
        //      устанавливаем выбрть все/снять все вакансии
        $('.filter-posts input').change(function(){ self.changeSelPosts(this) });
        //      прячем фильтр для моб. разрешения
        $(window).on('load resize',function(){ self.visibilityFilter('load') });
        $('.filter__vis').click(function(){ self.visibilityFilter('click') });
        // вкладки фильтра
        $('.filter__item-name').click(function(){ self.visibilityTab(this) });
        // подгрузка данных для пола, и дополнительно
        $('.filter-sex input, .filter-additional input').change(
            function(){ setTimeout(self.getAppsAjax(), 300) }
        );
        // подгрузка данных для возраста
        $('.filter__age-btn').click(function(){ self.getAppsAjax() });
        // подгрузка данных при перелистывании
        $('#promo-content').on('click', '.paging-wrapp a', function(e){ 
            self.getAppsAjax(e) 
        })
        .on('change', '.promo_inp', function(){ //  выбор работников
            self.addWorkers(this)
        });
        $('#all-workers').change(function(){ self.addWorkers(this) }); //  выбрать всех
        $('#filter-city').on('input','.city-inp',function(){ self.inputFilterCity(this) });
        $('#filter-city').on('focus','.city-inp',function(){ self.focusFilterCity(this) });
        $('#filter-city').on('click','.filter-city-select',function(e){ 
            if(!$(e.target).is('b'))
                $(e.target).find('.city-inp').focus();
        });
        $('#workers-btn').click(function(){ self.checkAddition() });
    };
    //
    //
    //      Проверка готовности для создания проекта
    ProjectPage.prototype.checkErrors = function () {
        let nameInp = $('#project-name'),
            name = $(nameInp).val();

        if(name.length<1){
            $(nameInp).addClass('error');
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
            self.showPopup('error','xls');
            self.addXlsFile;
            $inp.val('');
            $name.text('').hide();
        }
        else{
            $name.text(arExt[1] + '.' + arExt[2]).show();
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
            self.showPopup('notif','name');
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
            for (let i = 0, n = arTitles.length; i < n; i++)
                $(arTitles[i]).text('«' + val + '»');
            return true;
        }
    }
    //
    //      ГОРОДА
    //
    //		добавление города
    ProjectPage.prototype.addCity = function () {
    	let self = this,
    		arCities = $('#index .project__body'),
            newCity = $('#city-content').html(),
            empty = self.checkFields(arCities);

        if (!empty) {
            $(arCities[arCities.length-1]).after(newCity);
            let arTime = $('#index .project__body:eq(-1)').find('.time-inp');
            $(arTime).mask('99:99');
        }
        else
            self.showPopup('notif', 'add-city');
    }
    //      ввод города
    ProjectPage.prototype.inputCity = function (e) {
        let self = this,
            val = $(e).val();

        clearTimeout(self.bAjaxTimer);
        self.setFirstUpper(e);

        self.bAjaxTimer = setTimeout(function(){ self.getAjaxCities(val, e) },1000);
    }
    //      фокус поля города
    ProjectPage.prototype.focusCity = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.setFirstUpper(e);
        self.getAjaxCities(val, e);
    };
    //      запрос списка городов
    ProjectPage.prototype.getAjaxCities = function (val, e) {
        let self = this,
            $e = $(e),
            list = $e.siblings('.select-list')[0],
            main = $e.closest('.city-field')[0],
            mainCity = $e.closest('.project__body')[0],
            idcity = Number(mainCity.dataset.city), 
            piece = val.toLowerCase(),
            content = '',
            params = 'query=' + val + '&idco=' + self.idCo;
        
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
    ProjectPage.prototype.checkCity = function (e) {
        let self = this,
            $e = $(e),
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
                let main = $e.closest('.project__body')[0],
                    select = $(main).find('.city-select'),
                    inpText = $(main).find('.city-inp'),
                    list = $(main).find('.select-list'),
                    input = $(inpText).siblings('[type="hidden"]'),
                    mContent = $('#metro-content').html(),
                    cContent = $('#item-col').html(),
                    arLoc = $(main).find('.loc-part'),
                    arMetro = $(main).find('.metro-item'),
                    arCols = main.getElementsByClassName('project__index-col');

                if(main.dataset.city!=='' && main.dataset.city===e.dataset.id) {
                    let v = select.text();
                    inpText.val(v).hide();
                    select.show();
                    list.fadeOut();
                }
                else { // ввод нового города
                    let v = $(e).text();
                    main.dataset.city = e.dataset.id;
                    input.val(e.dataset.id);
                    inpText.val(v).hide();
                    select.html(v+'<b></b>').show();
                    list.fadeOut();

                    

                    if(e.dataset.metro==='1') {

                        if (!$(main).find('.metro-item').length) {
                            for(var i=0; i<arLoc.length; i+=2) {
                                let html = !i ? (cContent+cContent+mContent) : mContent;
                                $(arLoc[i]).before(html); 
                            }
                            let cnt = 0;
                            for(var i=0; i<arCols.length; i++) {
                                if($(arCols[i]).hasClass('empty')) {
                                    if(cnt>=2) {
                                       $(arCols[i]).remove(); 
                                    }
                                    cnt++;
                                }
                            }
                        }
                    }
                    else if($(main).find('.metro-item').length) {
                        let eCnt = 0, lCnt = 0;
                        for(var i=0; i<arCols.length; i++) {
                            if ($(arCols[i]).hasClass('empty') && eCnt<2) {
                                $(arCols[i]).remove(); 
                                eCnt++;
                            }
                            if ($(arCols[i]).hasClass('loc-part')) {
                                lCnt++;
                            }
                            if (lCnt==2) {
                                $(arCols[i]).after(cContent);
                                lCnt = 0;
                            }
                        }
                        /*
                        arCols[1].remove();
                        arCols[2].remove();
                        $(arMetro).remove();
                        */
                    }
                }
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
    ProjectPage.prototype.setFirstUpper = function (e) {
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
    ProjectPage.prototype.getSelectedCities = function () {
        let self = this;

        self.arIdCities = [];

        $.each($('#index .project__body'), function(){
            if(this.dataset.city!=='')
                self.arIdCities.push(Number(this.dataset.city));
        });
    }
    //
    //      МЕТРО
    //
    //      Ввод метро
    ProjectPage.prototype.inputMetros = function (e) {
        let self = this,
            val = $(e).val();

        $(e).css({width:(val.length * 10 + 5)+'px'});
        clearTimeout(self.bAjaxTimer);
        self.bAjaxTimer = setTimeout(function(){ self.getAjaxMetros(val, e) },1000);
    }
    //      фокус поля метро
    ProjectPage.prototype.focusMetro = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.getAjaxMetros(val, e);
    };
    //      запрос списка метро
    ProjectPage.prototype.getAjaxMetros = function (val, e) {
        let self = this,
            $e = $(e),
            main = $e.closest('.metro-field')[0],
            mainCity = $e.closest('.project__body')[0],
            list = $(main).find('.select-list')[0],
            input = $(main).find('[type="hidden"]')[0],
            idcity = Number(mainCity.dataset.city),
            params = 'id=' + idcity + '&query=' + val + '&select=' + $(input).val(),
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
    ProjectPage.prototype.checkMetro = function (e) {
        let self = this,
            $e = $(e),
            arMetros = $('#index .metro-field');

        if( !$e.closest('.metro-field').length && !$e.is('.metro-field') ) {
            for(let i=0; i<arMetros.length; i++){ // закрываем списки без фокуса
                let cInput = $(arMetros[i]).find('.metro-inp'),
                    cList = $(arMetros[i]).find('.select-list');

                cInput.val('').css({width:'5px'});
                cList.fadeOut();
            }
        }
        else{ // клик по объектам списка
            if( $e.is('.select-list li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('.metro-item')[0],
                    select = $(main).find('.metro-select'),
                    inpText = $(main).find('.metro-inp'),
                    list = $(main).find('.select-list'),
                    input = $(main).find('[type="hidden"]'),
                    name = $(e).text(),
                    v = $(input).val(),
                    arMetros = v.length ? v.split(',') : [];

                arMetros.push(e.dataset.id);
                $(input).val(arMetros);
                inpText.val('').css({width:'5px'});
                $(select).find('[data-id="0"]').before('<li data-id="' 
                    + e.dataset.id + '">' + name 
                    + '<b></b></li>');
                list.fadeOut();
            }
            else if($e.is('.metro-select b')) { // удаление выбранного метро из списка
                let main = $e.closest('.metro-item')[0],
                    input = $(main).find('[type="hidden"]'),
                    name = $(e).text(),
                    metro = $e.closest('li')[0],
                    arMetros = $(input).val().split(',');

                arMetros.pop(arMetros.indexOf(metro.dataset.id));
                $(input).val(arMetros.join(','));
                $(metro).remove();
            }
        }
    }
    //
    //      ЛОКАЦИИ
    //
    //      добавление локации
    ProjectPage.prototype.addLocation = function (e) {
        let self = this,
            btnBlock = $(e).closest('.project__all-btns')[0],
            body = btnBlock.previousElementSibling,
            arCols = body.getElementsByClassName('project__index-col'),
            arCities = $('#index .project__body'),
            newLoc = $('#loc-content').html(),
            newCol = $('#item-col').html(),
            empty = self.checkFields(arCities),
            htmlBtn = $(arCols).eq(-1).html();

        if (!empty) {
            if($(body).find('.metro-item').length) {// если есть метро
                newLoc = $('#metro-content').html() + $('#period-content').html() + newLoc;
            }
            else {
                newLoc = newLoc + newCol + $('#period-content').html();
            }
            $(arCols).eq(-1).after(newLoc + newCol);
            $(arCols).eq(-1).removeClass('empty').html(htmlBtn);
            let arTime = $(body).find('.time-inp');
            $(arTime).mask('99:99');
        }
        else
            self.showPopup('notif', 'add-tt');
    }
    //
    //      ДАТА
    //
    //      Создание календарей
    ProjectPage.prototype.buildCalendar = function (item, year, month) {
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
            main = $(item).closest('.project__index-period')[0],
            parent = $(item).closest('.period-item')[0],
            arCalendars = $(main).find('.period-item'),
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
    ProjectPage.prototype.checkDate = function (day) {
        let self = this,
            $it = $(day),
            main = $it.closest('.project__index-period')[0],
            arCalendars = $(main).find('.period-item'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            parent = $it.closest('.period-item')[0],
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
    ProjectPage.prototype.diffDate = function (date1, date2) {
        let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
    }
    //      форматируем в формат даты
    ProjectPage.prototype.getDateFromData = function (date) {
        let arDate = date.split('.'), 
            obj = new Date(
                Number(arDate[2]),
                Number(arDate[1]-1),
                Number(arDate[0])
            );
        return obj.setHours(0,0,0,0);
    }
    //      Изменение месяца
    ProjectPage.prototype.changeMonth = function (e, m) {
        let self = this,
            calendar = $(e).closest('.calendar')[0],
            data = $(e).siblings('.mname')[0].dataset,
            newMonth = parseFloat(data.month)+m;

        self.buildCalendar(calendar, data.year, newMonth);
    }
    //      Вывод календаря
    ProjectPage.prototype.showCalendar = function (e) {
        let calendar = e.nextElementSibling;
        $(calendar).fadeIn();
    }
    //      Закрытие календаря
    ProjectPage.prototype.closureCalendar = function (e) {
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
    ProjectPage.prototype.addPeriod = function (e) {
        let self = this,
            empty = false,
            main = $(e).closest('.project__body')[0],
            btnCol = $(e).closest('.project__index-col')[0],
            arInputs = $(main).find('.project__index-period input'),
            newPeriod = $('#period-content').html();

        for (let i = 0, l = arInputs.length; i < l; i++)
            if( !arInputs[i].value.length )
                    empty = true;

        if(!empty) {
            $(btnCol).before(newPeriod);
            $(main).find('.time-inp').mask('99:99');
        }
        else 
            self.showPopup('notif', 'add-period');
    }
    //      Проверка времени
    ProjectPage.prototype.checkTime = function (e) {
		let self = this,
			$e = $(e),
			main = $e.closest('.project__index-period'),
			arTimes = $(main).find('input'),
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

		if( (arT1[0] > arT2[0]) || (arT1[1] > arT2[1]) ) {
			self.showPopup('error', 'time');
			$(arTimes[t]).val('');
		}
        if( (arT1[0] == arT2[0]) && (arT1[1] == arT2[1]) ) {
            self.showPopup('error', 'time');
            $(arTimes[t]).val('');
        }
    }
    //		Установка времени
    ProjectPage.prototype.setTime = function (e, arT) {
		arT[0] = ('0' + arT[0]).slice(-2);
		arT[1] = ('0' + arT[1]).slice(-2);
		$(e).val(arT[0] + ':' + arT[1]);
    }
    //      Проверка заполненности полей
    ProjectPage.prototype.checkFields = function (arr) {
        let empty = false;

        for (let i = 0, l = arr.length; i < l; i++) {
            let arInputs = $(arr[i]).find('input');
    
            for (let j = 0, n = arInputs.length; j < n; j++) {
                let name = $(arInputs[j]).attr('name');
                if ($.inArray(name, ['c','m'])<0 && !arInputs[j].value.length) {
                    //console.log(arInputs[j]);
                    empty = true;
                }
            }
        }
        empty = false;
        return empty;
    }
    //
    //      ПРИГЛАШЕНИЕ НА ВАКАНСИЮ
    //
    //      Проверка по нажатии кнопок
    ProjectPage.prototype.checkInvitations = function (e) {
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
        empty = false;
        if (!empty) {
            if(save) {
                self.showModule(e);
                return true;
            }

            let html = $('#invitation-content').html(),
                main = document.getElementById('invitation'),
                arInv = main.getElementsByClassName('invitation-item'),
                id = Number(arInv[arInv.length-1].dataset.id) + 1;

            $(arInv[arInv.length-1]).after(html);
            arInputs = $(arInv[arInv.length-1]).find('.invite-inp');

            $(arInputs[0]).attr('name','inv-name['+id+'][]');
            $(arInputs[1]).attr('name','inv-sname['+id+'][]');
            $(arInputs[2]).attr('name','inv-phone['+id+'][]');
            $(arInputs[3]).attr('name','inv-email['+id+'][]');

            let p = $(arInv[arInv.length-1]).find('.invite-inp.phone');
            //console.log(p);

            window.phoneCode.element = $(arInv[arInv.length-1]).find('.invite-inp.phone');
            window.phoneCode._create();
            $(arInv[arInv.length-1])
                .find('[type="hidden"]')
                .attr('name','prfx-phone['+id+'][]');
            self.arOldPhones[id] = '';
        }
        else
            save
            ? self.showPopup('error', 'save-notif')
            : self.showPopup('notif', 'add-notif');
    }
    //
    ProjectPage.prototype.inputInvitation = function (item,event) {
        let self = this,
            $e = $(item),
            v = $e.val(),
            id = $e.closest('.invitation-item')[0].dataset.id;

        if ($e.hasClass('name') || $e.hasClass('sname')) {
            v = v.replace(/[^а-яА-ЯїЇєЄіІёЁ ]/g,'');
            v = v.charAt(0).toUpperCase() + v.slice(1);
            $e.val(v);
        }
        if (event=='focusout' && $e.hasClass('email') && v!=='') {
            let ePattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if(!ePattern.test(v)) {
                self.showPopup('error', 'bad-email');
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
                if(l<phoneLen) self.showPopup('error', 'bad-phone');
            }
        }
    }
    //
    //      ДОБАВЛЕНИЕ СОИСКАТЕЛЕЙ
    //
    //      устанавливаем выбрть все/снять все для вакансий
    ProjectPage.prototype.changeSelPosts = function (e) {
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
    ProjectPage.prototype.getAppsAjax = function (e) {
        let params = '',
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
            e.preventDefault();
            params = e.target.href.slice(e.target.href.indexOf(AJAX_GET_PROMO) + 30);// вырезаем GET
        }
        $('.filter__veil').show(); // процесс загрузки

        $.ajax({
            type: 'GET',
            url: AJAX_GET_PROMO,
            data: params,
            success: function(res){
                $content.html(res);
                if(e){   // постраничное обновление
                    $('html, body').animate({ 
                        scrollTop: $content.offset().top - 100 },
                        700
                    );//прокручиваем к заголовку
                    $.each($content.find('.promo_inp'), function(){ 
                        var id = Number($(this).val());
                        if($.inArray(id,self.arSelectIdies)>=0 || $('#all-workers').is(':checked'))
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
    ProjectPage.prototype.visibilityFilter = function (event) {
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
    ProjectPage.prototype.visibilityTab = function (e) {
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
    ProjectPage.prototype.addWorkers = function (e) {
        let self = this,
            $e = $(e),
            arInputs = $('#promo-content').find('.promo_inp');

        if( $e.attr('id')==='all-workers' ) {
            if($e.is(':checked')) {
                self.arSelectIdies = arIdies.slice(); // arIdies берем с вьюхи
                $.each(arInputs, function(){ $(this).prop('checked',true) });
            }
            else {
                self.arSelectIdies = [];
                $.each(arInputs, function(){ $(this).prop('checked',false) });
            }
        }
        else {
            let id = Number($e.val());

            if($e.is(':checked')){
                // записуем выбраный ID
                if($.inArray(id, self.arSelectIdies)<0){ self.arSelectIdies.push(id) };
                if(self.arSelectIdies.length == arIdies.length) // arIdies берем с вьюхи
                $('#all-workers').prop('checked',true);  
            }
            else {
                // убираем ID
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
    ProjectPage.prototype.inputFilterCity = function (e) {
        let self = this,
            val = $(e).val();

        $(e).css({width:(val.length * 10 + 5)+'px'});
        clearTimeout(self.bAjaxTimer);
        self.bAjaxTimer = setTimeout(function(){ self.getAjaxFilterCities(val, e) },1000);
    }
    //      фокус поля города в фильтре
    ProjectPage.prototype.focusFilterCity = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.getAjaxFilterCities(val, e);
    };
    //      запрос списка городов из фильтра
    ProjectPage.prototype.getAjaxFilterCities = function (val, e) {
        let self = this,
            $e = $(e),

            main = $e.closest('.filter-city-select')[0],
            list = $(main).siblings('.select-list')[0],
            arInputs = $('.filter-city-select').find('[type="hidden"]'),
            params = 'query=' + val + '&idco=' + self.idCo,
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
                        id = +item.data;

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
    ProjectPage.prototype.checkFilterCity = function (e) {
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
    ProjectPage.prototype.checkAddition = function () {
        let btn = document.querySelector('#workers-btn');
        if($('#mess-workers').val()!=='')
            this.showModule(btn);
        else
            this.showPopup('notif','addition');
    }
    //
    //      ADDITIONAL
    //
    //      Вывод ошибок
    ProjectPage.prototype.showPopup = function (event, type) {
        let header = '',
            body = '';

        if(event=='error') {
            header = 'Ошибка';
            switch(type) {
                case 'xls':     
                    body = 'Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!';  
                    break;
				case 'time':
                    body = 'Неправильно задано время работы';  
                    break;
                case 'save-notif':
                    body = 'Необходимо заполнить все поля в приглашении';  
                    break;
                case 'bad-email':
                    body = 'Email не соответствует общепринятому формату';  
                    break;
                case 'bad-phone':
                    body = 'Неправильное количество символов в телефоне';  
                    break;
                default: break;  
            }
        }
        if(event=='notif') {
            header = 'Предупреждение';
            switch(type) {
                case 'name':
                    body = 'Для продолжения необходимо ввести название проекта';  
                    break;
                case 'add-city':
                    body = 'Перед добавлением нового города необходимо '
                        + 'заполнить все поля у существующих городов';  
                    break;
                case 'add-period':
                    body = 'Перед добавлением нового периода необходимо '
                        + 'заполнить все поля у существующих периодов';  
                    break;
                case 'add-tt':
                    body = 'Перед добавлением ТТ необходимо '
                        + 'заполнить все поля у существующих ТТ';  
                    break;
                case 'add-notif':
                    body = 'Перед добавлением нового приглашения необходимо '
                        + 'корректно заполнить все поля в существующих'; 
                    break;
                case 'addition':
                    body = 'Не было выбрано ни одного соискателя';  
                    break;
                default: break;  
            }            
        }

        let html = "<form data-header='" + header + "'>" + body + "</form>";
        ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
    }
    return ProjectPage;
}());
$(document).ready(function () {
	let Project = new ProjectPage();
});