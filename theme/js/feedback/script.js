$(function(){
    $('#F2feedback').submit(function(){
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
            button = document.querySelector('.feedback-page__button'),
            $themeInput = $('#EdTheme'),
            $mailInput = $('#EdEmail'),
            $textInput = $('#MText'),
            $wayInput = $('#EdWay'),
            $fdbkVal = $("#IdFdBck option:selected").val(),
            arErrors = 0;

        if(MainScript.isButtonLoading(button)){ return false; }
        else{ MainScript.buttonLoading(button,true); }

        if ($fdbkVal == 0) {
            if ($wayInput.val() == null) {
                $wayInput.addClass('error');
                arErrors++;
            }
            else {
                $wayInput.removeClass('error');
            }

            if (!$.trim($themeInput.val())) {
                $themeInput.addClass('error');
                arErrors++;
            }
            else {
                $themeInput.removeClass('error');
            }
        } else {
            arErrors = 0;
            $themeInput.removeClass('error');
            $wayInput.removeClass('error');
        }


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

        if(!$.trim($textInput.val()))
        {
            $textInput.addClass('error');
            arErrors++;
        }
        else
        {
            $textInput.removeClass('error');
        }

console.log('arErrors=',arErrors);

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

    $('#IdFdBck').change(function(e, value) {
        if(e.target.value != 0) {
           $("#EdTheme").hide();
           $("#EdWay").hide();
        } else {
           $("#EdTheme").val("").show();
           $("#EdWay").show();
        }
    });
});