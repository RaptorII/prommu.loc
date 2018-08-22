/**
 * Created by Stanislav on 22.08.2018.
 */
'use strict'
var ProjectAddPersonal = (function () {
    //ProjectAddPersonal.prototype.Project = [];
    ProjectAddPersonal.prototype.arSelectIdies = [];

    function ProjectAddPersonal() {
        this.init();
        //var self = this;
        //ProjectAddPersonal.winObj = self;
    }
    ProjectAddPersonal.prototype.init = function (project) {
        let self = this;

        //self.Project = project;

        //      просмотреть все вакансии
        $('.more-posts').click(function(){
            $(this.parentNode).css({height:'initial'});
            $(this).remove();
        });


        $('#workers-btn').click(function(){
            $('#addition').css({display:'none'});
            $('#main').css({display:'block'});
        });
        $('#control__add-personal').click(function(){
            $('#addition').css({display:'block'});
            $('#main').css({display:'none'});
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
