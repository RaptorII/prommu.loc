jQuery(function($){
    setTimeout(function(){ $('.repeat-sending').fadeIn() },5000);
    $('.repeat-sending').click(function(){
        $.ajax({
            type: 'POST',
            url: '/ajax/restorecode',
            data: 'phone='+get,
            success: function(d){ console.log(d); }
        });
    });
    $('#code-field').on('input',function(){ 
        this.value = this.value.replace(/\D+/g,''); 
    });
});