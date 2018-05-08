$(function(){
    $('#F1feedback').submit(function(){
        var $mailInput = $('#EdEmail');
        if($mailInput.val() != '') {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if(pattern.test($('#EdEmail').val())){
                $mailInput.removeClass('error');
            }
            else{
                $mailInput.addClass('error');
                return false;
            }
        }
        else{
            $mailInput.addClass('error');
            return false;
        }
    });
});