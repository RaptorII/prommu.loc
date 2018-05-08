$(function(){
    var apm = '.app-message',
        $apmBody = $(apm);

    $(window).on('load scroll resize',function(e){
        var $body = $('body'),
            hMessBody = $apmBody.height(),
            pMessBody = $apmBody.offset().top,
            scrollPos = $(document).scrollTop(),
            hScreen = $(window).height(),
            pHScreen = scrollPos + hScreen,
            pHMessBody = hMessBody + pMessBody;

        // прокрутка только если вышли за пределы видимости окна
        if(hScreen<hMessBody){ // окно больше экрана
            if(pHScreen>pHMessBody) // прокрутка вниз
                $apmBody.css({ top:(pMessBody + pHScreen - pHMessBody) });
            if(scrollPos<pMessBody) // прокрутка вверх
                $apmBody.css({ top:scrollPos });
        }
        else // окно меньше экрана
            $apmBody.css({ top:scrollPos });
    });

    $apmBody.on('click', apm+'__close', function(){
        $.cookie('show_mob_mess', 1);// already show 
        location.reload();
    });
    $apmBody.on('click', apm+'__continue', function(){
        $.cookie('show_mob_mess', 1);// already show  
        location.reload();
    });
});