$(function(){
    $('#F2feedback').submit(function(){
      var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
          button = document.querySelector('.feedback-page__button'),
          $nameInput = $('#EdName'),
          $mailInput = $('#EdEmail'),
          $selectTheme = $('#feedback_select select'),
          selectThemeVal = $selectTheme.find('option:selected').val(),
          $themeInput = $('#EdTheme'),
          $direct = $('#feedback_direct select'),
          directVal = $direct.find('option:selected').val(),
          $textInput = $('#MText'),
          arErrors = 0;

      if(MainScript.isButtonLoading(button)){ return false; }
      else{ MainScript.buttonLoading(button,true); }
      // name
      if(!$.trim($nameInput.val()).length)
      {
        $nameInput.addClass('error');
        arErrors++;
      }
      else
      {
        $nameInput.removeClass('error');
      }
      // email
      if(!pattern.test($mailInput.val()))
      {
        $mailInput.addClass('error');
        arErrors++;
      }
      else
      {
        $mailInput.removeClass('error');
      }
      // direct
      if (!directVal.length)
      {
        $direct.addClass('error');
        arErrors++;
      }
      else
      {
        $direct.removeClass('error');
      }
      // theme
      var themeName = $.trim($themeInput.val());
      if($selectTheme.is('*')) // user
      {
        if(!selectThemeVal.length)
        {
          $('#feedback_select select').addClass('error');
          arErrors++;
        }
        else
        {
          $('#feedback_select select').removeClass('error');
          $themeInput.removeClass('error');
          if(selectThemeVal==='0')
          {
            if(!themeName.length)
            {
              $themeInput.addClass('error');
              arErrors++;
            }
            else
            {
              $themeInput.removeClass('error');
            }
          }
        }
      }
      else // guest
      {
        if(!themeName.length)
        {
          $themeInput.addClass('error');
          arErrors++;
        }
        else
        {
          $themeInput.removeClass('error');
        }
      }
      // message
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

    $('#feedback_select select').change(function(e, value) {
        if(e.target.value != 0)
        {
           $("#EdTheme").hide();
           $("#feedback_direct").hide();
           if(typeof arFeedbacks=='object')
           {
              $.each(arFeedbacks,function(){
                if(e.target.value===this.id)
                {
                  $("#feedback_direct select option[value=" + this.direct + "]").attr('selected', 'true');
                }
              });
           }
        }
        else
        {
           $("#EdTheme").val("").show();
           $("#feedback_direct").show();
        }
    });
});