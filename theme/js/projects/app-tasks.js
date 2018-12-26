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

    return Cabinet;
}());


$(document).ready(function () {
    new Cabinet();
});
