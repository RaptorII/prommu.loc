'use strict'
var Cabinet = (function () {
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

        $('.task__select').click(function () {
            if($(this).next('.task__ul-hidden').is(":visible")){
                $('.task__ul-hidden').fadeOut();
            }else{
                $('.task__ul-hidden').fadeOut();
                $(this).next('.task__ul-hidden').fadeIn();
            }
        });

        $('.task__li-hidden').click(function () {
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

        $('.point__timer.start').on('click', function (e) {
            let cabinet_project= $('#project_id').val();
            let cabinet_user = $('#user_id').val();
            let cabinet_point = $(this).data('point');
            let cabinet_date = $(this).data('date');
            let type = 'coordinates';

            console.log(cabinet_project);
            console.log(cabinet_user);
            console.log(cabinet_point);
            console.log(cabinet_date);
        });

        $('.point__timer.stop').on('click', function (e) {
            let cabinet_project= $('#project_id').val();
            let cabinet_user = $('#user_id').val();
            let cabinet_point = $(this).data('point');
            let cabinet_date = $(this).data('date');
            let type = 'coordinates';

            console.log(cabinet_project);
            console.log(cabinet_user);
            console.log(cabinet_point);
            console.log(cabinet_date);
        });



        $('.task__li-hidden').on('click', function (e) {
            let cabinet_project= $('#project_id').val();
            let cabinet_task_value = $(this).data('id');
            let cabinet_task = $(this).data('task-id');
            let type = 'change-task-status';

            let data = self.initTaskData(cabinet_project, cabinet_task, type, cabinet_task_value);
            self.ajaxSetTasksStatus(data);
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


    Cabinet.prototype.initData = function (project, user, point, date, type, task) {
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
    };

    Cabinet.prototype.initTaskData = function (project, task, type, status) {
        var data_object = {};

        project = project.toString();
        type = type.toString();
        task = task.toString();
        status = status.toString();

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

        return data_object;
    };

    Cabinet.prototype.ajaxSetTasksStatus = function (data) {
        if (!data) return;

        let self = this;

        $.ajax({
            type: 'GET',
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

    return Cabinet;
}());


$(document).ready(function () {
    new Cabinet();
});
