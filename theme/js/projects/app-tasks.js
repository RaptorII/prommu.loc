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

        this.clock();
        this.tick();
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
        setTimeout(clock(), 10000);
    };

    Cabinet.prototype.getWeekDay= function(date){
        var days = ['воскресенье', 'пн', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'];
        return days[date.getDay()];
    };

    Cabinet.prototype.tick=function(){
        $('.timer__sw').toggleClass('t_hide');
        setTimeout("tick()", 500);
    };

    return Cabinet;
}());


$(document).ready(function () {
    new Cabinet();
});
