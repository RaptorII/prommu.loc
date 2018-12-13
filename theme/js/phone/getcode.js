jQuery(function($){
    var getParams = window
        .location
        .search
        .replace('?','')
        .split('&')
        .reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );

    setTimeout(function(){ $('.repeat-sending').fadeIn() },5000);
    //
    $('.repeat-sending').click(function(){
        if(!getParams['phone'].length)
            return false;

        $.ajax({
            type: 'POST',
            url: '/ajax/restorecode',
            data: 'phone='+getParams['phone'],
            success: function(d){ console.log(d); }
        });
    });
    //
    $('#code-field').on('input',function(){ 
        this.value = this.value.replace(/\D+/g,''); 
    });
    //
    $('#get-code-form').submit(
        function(e){
            var button = document.querySelector('.hvr-sweep-to-right');
            if(MainScript.isButtonLoading(button)){ return false; }
            else{ MainScript.buttonLoading(button,true); }
        });
});