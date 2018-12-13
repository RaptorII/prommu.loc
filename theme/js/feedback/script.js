$(function(){
    $('#F1feedback').submit(function(){
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
            button = document.querySelector('.feedback-page__button'),
            $themeInput = $('#EdTheme'),
            $mailInput = $('#EdEmail'),
            $textInput = $('#MText'),
            $wayInput = $('#EdWay'),
            arErrors = 0;

        if(MainScript.isButtonLoading(button)){ return false; }
        else{ MainScript.buttonLoading(button,true); }

        if($mailInput.val() != '')
        {
            if(pattern.test($('#EdEmail').val()))
            {
                $mailInput.removeClass('error');
            }
            else
            {
                $mailInput.addClass('error');
                arErrors++;
            }
        }
        else
        {
            $mailInput.addClass('error');
            arErrors++;
        }


        if($wayInput.val() == null)
        {
            $wayInput.addClass('error');
            arErrors++;
        }
        else
        {
            $wayInput.removeClass('error');
        }

        if(!$.trim($themeInput.val()))
        {
            $themeInput.addClass('error');
            arErrors++;
        }
        else
        {
            $themeInput.removeClass('error');
        }

        if(!$.trim($textInput.val()))
        {
            $textInput.addClass('error');
            arErrors++;
        }
        else
        {
            $textInput.removeClass('error');
        }



        if(arErrors>0)
        {
            MainScript.buttonLoading(button,false);
            return false;
        }
        else
        {
            return true;
        }
    });
});