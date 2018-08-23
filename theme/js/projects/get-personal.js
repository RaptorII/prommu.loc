/**
 * Created by Stanislav on 22.08.2018.
 */
'use strict'
var ProjectAddPersonal = (function () {
    //ProjectAddPersonal.prototype.Project = [];
    ProjectAddPersonal.prototype.arSelectIdies = [];

    ProjectAddPersonal.prototype.arOldPhones = [];
    ProjectAddPersonal.prototype.keyCode = 0;

    function ProjectAddPersonal() {
        this.init();
        //var self = this;
        //ProjectAddPersonal.winObj = self;
    }
    ProjectAddPersonal.prototype.init = function (project) {
        let self = this;

        //self.Project = project;


        $("#invitation").on( "click", ".invitation-del", function() {
            self.removeElement(this);
        });

        $('#invitation #save-prsnl-cancel').click(function(){ self.deleteElement(this) });

        //      просмотреть все вакансии
        $('.more-posts').click(function(){
            $(this.parentNode).css({height:'initial'});
            $(this).remove();
        });


        $('#workers-btn').click(function(){
            $('#addition').css({display:'none'});
            $('#main').css({display:'block'});
            $('#invitation').css({display:'none'});
        });
        $('#control__add-personal').click(function(){
            $('#addition').css({display:'block'});
            $('#main').css({display:'none'});
            $('#invitation').css({display:'none'});
        });
        $('#control__new-personal').click(function(){
            $('#addition').css({display:'none'});
            $('#main').css({display:'none'});
            $('#invitation').css({display:'block'});
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
        // закрытие списка городов
        $(document).on('click', function(e) { self.checkFilterCity(e.target) });

        $('#add-prsnl-btn,#save-prsnl-btn').click(function(){
            self.checkInvitations(this)
        });
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
    ProjectAddPersonal.prototype.deleteElement = function (e) {
        $("#invitation .invitation-item").each(function() {
            if($(this).data('id')!=0){
                $(this).remove();
            }
            else{
                $(this).find("input[type='text']").val('');
            }
        });
        $('#addition').css({display:'none'});
        $('#main').css({display:'block'});
        $('#invitation').css({display:'none'});
    }
    ProjectAddPersonal.prototype.removeElement = function (e) {
        let $e = $(e),
            error = -1,
            query = true,
            arErr = ['city-del','loc-del','period-del','invitation-del'],
            arItems, item, main;

        if($e.hasClass('invitation-del')) {
            arItems = $('#invitation .invitation-item');
            item = $e.closest('.invitation-item')[0];
            if(arItems.length>1) {
                error = -1;
                query = confirm('Будут удалены данные контакта.\n'
                    +'Вы действительно хотите это сделать?');
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

    ProjectAddPersonal.prototype.inputInvitation = function (item,event) {
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


    ProjectAddPersonal.prototype.checkInvitations = function (e) {

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
            if(save) {
                //self.showModule(e);
                $('#addition').css({display:'none'});
                $('#main').css({display:'block'});
                $('#invitation').css({display:'none'});
                return true;
            }

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

    ProjectAddPersonal.prototype.getAppsAjax = function (e) {
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
 */
$(document).ready(function () {
    new ProjectAddPersonal();
});
