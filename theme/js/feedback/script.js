$(function(){
    $('#F1feedback').submit(function(){
        var arErrors = 0;
        var $mailInput = $('#EdEmail');
        if($mailInput.val() != '') {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if(pattern.test($('#EdEmail').val())){
                $mailInput.removeClass('error');
            }
            else{
                $mailInput.addClass('error');
                arErrors++;
            }
        }
        else{
            $mailInput.addClass('error');
            arErrors++;
        }


        var $wayInput = $('#EdWay');
        if($wayInput.val() == null){
            $wayInput.addClass('error');
            arErrors++;
        }else{
            $wayInput.removeClass('error');
        }

        var $themeInput = $('#EdTheme');
        if(!$.trim($themeInput.val())){
            $themeInput.addClass('error');
            arErrors++;
        }else{
            $themeInput.removeClass('error');
        }

        var $textInput = $('#MText');
        if(!$.trim($textInput.val())){
            $textInput.addClass('error');
            arErrors++;
        }else{
            $textInput.removeClass('error');
        }



        if(arErrors>0){
            return false;
        }else{
            return true;
        }
    });
});