'use strict'
var Cabinet = (function () {


    Cabinet.prototype.statusMain = [];
    Cabinet.prototype.statusMain[0] = 'Ожидание';
    Cabinet.prototype.statusMain[1] = 'В работе';
    Cabinet.prototype.statusMain[3] = 'Готова';
    Cabinet.prototype.statusMain[4] = 'Отменена';

    function Cabinet() {
        this.init();
    }
    //
    Cabinet.prototype.init = function () {
        let self = this;

        $('.warning').click(function () {
            if($(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).closest('.point').next('.point__descr').fadeOut();
            }else{
                $(this).addClass('active');
                $(this).closest('.point').next('.point__descr').fadeIn();
            }
        });

        $('.task__descr-ico').click(function () {
            if($(this).hasClass('active')){
                $(this).removeClass('active');
                $(this).closest('.task').next('.task_descr').fadeOut();
            }else{
                $(this).addClass('active');
                $(this).closest('.task').next('.task_descr').fadeIn();

            }
        });

        $(".cabinet").on("click",".task__select", function (e) {
            if($(this).next('.task__ul-hidden').is(":visible")){
                $('.task__ul-hidden').fadeOut();
            }else{
                $('.task__ul-hidden').fadeOut();
                $(this).next('.task__ul-hidden').fadeIn();
            }
        });

        $(".cabinet").on("click",".task__li-hidden", function (e) {
            var data = $(this).data('id');
            var value = $(this).text();
            var parent = $(this).closest('.task__item-data');
            $(parent).find('.task__select').text(value);
            $(parent).find('.task__li-visible').val(data);
            $(parent).find('.task__ul-hidden').fadeOut();
        });

        $(document).on('click', function (e) {
            self.closureHiddenUlContent(e.target);
        });

        $('.point__timer').on('click', function (e) {
            let cabinet_project= $('#project_id').val();
            let cabinet_point = $(this).data('point');
            let cabinet_date = $(this).data('date');
            let type = 'coordinates';
            var lat = 0;
            var lon = 0;

            navigator.geolocation.getCurrentPosition(
                function(position){ // все в порядке
                    lat = position.coords.latitude;
                    lon = position.coords.longitude;
                },
                function(){ // ошибка

                }
            );

            //Статус 0 - начало 1 - конец
            var status = 0;
            if($(this).hasClass('start')){
                status = 0;
            }else if($(this).hasClass('stop')){
                status = 1;
            }
            let data = self.initData(cabinet_project, '', type, status ,cabinet_point, lat,lon, cabinet_date);
            self.ajaxWorkControl(data, this);

        });


        $(".cabinet").on("click",".task__li-hidden", function (e) {
            let cabinet_project= $('#project_id').val();
            let cabinet_task_value = $(this).data('id');
            let cabinet_task = $(this).data('task-id');
            let type = 'change-task-status';

            let data = self.initData(cabinet_project, cabinet_task, type, cabinet_task_value);
            self.ajaxSetTasksStatus(data);
        });

        $(".cabinet").on("click",".cabinet__link-target", function (event) {
            event.preventDefault();
            var id  = $(this).attr('href'),
            top = $(id).offset().top - 100;
            $('body,html').animate({scrollTop: top}, 1500);
        });

        this.clock();
        this.tick();
    };

    Cabinet.prototype.closureHiddenUlContent = function (e) {
        if(!$(e).hasClass('task__select') && !$(e).hasClass('task__li-hidden')){
            $('.task__ul-hidden').fadeOut();
        }
    };



    Cabinet.prototype.clock = function (){
        var self = this;
        var d = new Date();
        var month_num = d.getMonth();
        var day = d.getDate();
        var hours = d.getHours();
        var minutes = d.getMinutes();
        var seconds = d.getSeconds();
        var week =  this.getWeekDay(d);

        if (day <= 9) day = "0" + day;
        if (hours <= 9) hours = "0" + hours;
        if (minutes <= 9) minutes = "0" + minutes;
        if (seconds <= 9) seconds = "0" + seconds;

        var t_hours = hours;
        var t_minutes = minutes;

        document.getElementById("t_hours").innerHTML = t_hours;
        document.getElementById("t_minutes").innerHTML = t_minutes;
        document.getElementById("t_week").innerHTML = week;
        setTimeout(function () {
            self.clock();
        }, 10000);
    };

    Cabinet.prototype.getWeekDay= function(date){
        var days = ['воскресенье', 'пн', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'];
        return days[date.getDay()];
    };

    Cabinet.prototype.tick=function(){
        var self = this;
        $('.timer__sw').toggleClass('t_hide');
        setTimeout(function(){
            self.tick();
        }, 500);
    };


    /*Cabinet.prototype.initData = function (project, user, point, date, type, task) {
        var data_object = {};

        project = project.toString();
        user = user.toString();
        point = point.toString();
        date = date.toString();
        type = type.toString();

        if(project.length>0) {
            data_object.project = project;
        }
        if(user.length>0) {
            data_object.user = user;
        }
        if(point.length>0){
            data_object.point = point;
        }
        if(date.length>0) {
            data_object.date = date;
        }
        if(type.length>0) {
            data_object.type = type;
        }
        if(task.length>0) {
            data_object.task = task;
        }

        return data_object;
    };*/

    Cabinet.prototype.initData = function (project, task, type, status, point='', lat='',lon='', date='') {
        var data_object = {};

        project = project.toString();
        type = type.toString();
        task = task.toString();
        status = status.toString();

        point = point.toString();
        lat = lat.toString();
        lon = lon.toString();
        date = date.toString();


        if(project.length>0) {
            data_object.project = project;
        }
        if(type.length>0) {
            data_object.type = type;
        }
        if(task.length>0){
            data_object.task = task;
        }
        if(status.length>0) {
            data_object.status = status;
        }

        if(point.length>0) {
            data_object.point = point;
        }
        if(lat.length>0) {
            data_object.latitude = lat;
        }
        if(lon.length>0) {
            data_object.longitude = lon;
        }
        if(date.length>0) {
            data_object.date = date;
        }

        return data_object;
    };

    Cabinet.prototype.ajaxSetTasksStatus = function (data) {
        if (!data) return;
        let self = this;
        $.ajax({
            type: 'POST',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                //console.log(value);
                if(value){
                    console.log(value);
                }
            }
        });
    };

    Cabinet.prototype.ajaxWorkControl = function (data, e) {
        if (!data) return;
        let self = this;
        e = $(e);

        $.ajax({
            type: 'POST',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                if(value.error==false){
                    console.log(data);
                    if(data.status == 0){
                        e.addClass('stop');
                        e.removeClass('start');

                        var ico = e.find('.timer__play');
                        ico.addClass('timer__stop');
                        ico.removeClass('timer__play');

                        var text = e.find('.timer__control-st');
                        text.text('завершить');


                        e.closest('.cabinet__point').find('.task').each(function () {
                            var target = $(this).find('.task__status');
                            var text = target.text();
                            var html = self.template(text,$(this).data('id'),data.point,data.date);
                            target.html(html);
                        });

                    }else{
                        e.addClass('start');
                        e.removeClass('stop');

                        var ico = e.find('.timer__stop');
                        ico.addClass('timer__play');
                        ico.removeClass('timer__stop');

                        var text = e.find('.timer__control-st');
                        text.text('начать');

                        e.closest('.cabinet__point').find('.task').each(function () {
                            var target = $(this).find('.task__status');
                            var text = target.find('.task__select').text();
                            target.html('');
                            target.text(text);
                        });
                    }
                }
            }
        });

    };


    Cabinet.prototype.template = function (text, task_id, point_id, date) {
        var self = this;
        var html = '<div class="task__item-data">'
                +'<span class="task__select">'+ text +'</span>'
                +'<ul class="task__ul-hidden">';


                $.each(self.statusMain, function(i,e) {
                    if(e){
                        html+='<li class="task__li-hidden" data-id="'+i+'" data-task-id="'+task_id+'">'+e+'</li>'
                    }
                });

        html+='</ul>'
                +'<input type="hidden" class="task__li-visible" value="0" data-map-point="'+point_id+'" data-map-date="'+date+'"></div>';

               return html;
    };

    return Cabinet;
}());


$(document).ready(function () {
    new Cabinet();
});
