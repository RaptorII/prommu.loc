'use strict'
$(function(){
    var INNLenth = 12,
        bSuccess = false;
    // tabs
    $('.self-employed__tab-title').click(function(){
        let parent = $(this).closest('.self-employed__tab'),
            content = $(parent).find('.self-employed__tab-content');

        if(!$(parent).hasClass('enable'))
        {
            $(content).fadeIn();
            setTimeout(function(){ $(parent).addClass('enable') },400);
        }
        else
        {
            $(content).fadeOut();
            setTimeout(function(){ $(parent).removeClass('enable') },400);
        }
    });
    // checkboxes
    $('[type="checkbox"]').change( checkSend );
    // inn
    $('#inn_input').on('input',function(){
        var v = this.value.replace (/\D/, '').substr(0,INNLenth);

        $(this).val(v);
        checkSend();
    });
    //
    function checkSend()
    {
        let length = $('#inn_input').val().length,
            chBox1 = $('[name="agreement"]').is(':checked'),
            chBox2 = $('[name="oferta"]').is(':checked'),
            chBox3 = $('[name="market_oferta"]').is(':checked');

        if(length==INNLenth && chBox1 && chBox2 && chBox3)
            $('#form_btn').show()
        else
            $('#form_btn').hide();
    }
    // submit
    $('#form_btn').click(function(){
        $('.content-block').addClass('load');
        $.ajax({
            type: 'POST',
            url: '/ajax/Self_employed',
            data: 'inn=' + $('#inn_input').val(),
            success: function(r)
            {
                r = JSON.parse(r);
                let message = '';

                $('.content-block').removeClass('load');
                if(r.error==true)
                {
                    message = 'Непредвиденная ошибка. Пожалуйста обратитесь к администратору';
                }
                else
                {
                    if(r.response.status==true)
                    {
                      $('.content-block').addClass('load');
                      $('#self_employed_form').submit();
                    }
                    else
                    {
                        message = r.response.message;
                    }
                }

                if(message.length)
                {
                    $('body').append('<div class="prmu__popup"><p>'+message+'</p></div>'),
                    $.fancybox.open({
                        src: "body>div.prmu__popup",
                        type: 'inline',
                        touch: false,
                        afterClose: function(){ $('body>div.prmu__popup').remove() }
                    });
                }
            },
            complete: function() {
                $('.content-block').removeClass('load');
            }
        });
    });
    //
    $('#self_employed_form').keydown(function(e){
        if(e.keyCode == 13)
        {
            e.preventDefault();
            return false;
        }
    });
});