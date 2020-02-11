'use strict'
$(function(){
    var INNLenth = 12;
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
    // submit
    $('#form_btn').click(function(){
        if($(this).hasClass('disable'))
        {
            //showFancyPopup('Необходимо отметить все галочки и ввести ИНН');
            showFancyPopup('Необходимо огласится с условиями и ввести ИНН');
            return false;
        }

        $('.content-block').addClass('load');
        $.ajax({
            type: 'POST',
            url: '/ajax/Self_employed',
            data: 'inn=' + $('#inn_input').val(),
            success: function(r)
            {

                console.log('input in ajaxs');
                console.log(r);

                r = JSON.parse(r);
                let message = '';

                if(r.error==true)
                {
                    $('.content-block').removeClass('load');
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
                        $('.content-block').removeClass('load');
                        message = r.response.message;
                    }
                }

                if(message.length)
                {
                    showFancyPopup(message);
                }
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
    //
    function showFancyPopup(m)
    {
        $('body').append('<div class="prmu__popup"><p>'+m+'</p></div>'),
        $.fancybox.open({
            src: "body>div.prmu__popup",
            type: 'inline',
            touch: false,
            afterClose: function(){ $('body>div.prmu__popup').remove() }
        });
    }
    //
    function checkSend()
    {
        let length = $('#inn_input').val().length,
            chBox1 = $('[name="agreement"]').is(':checked'),
            chBox2 = $('[name="oferta"]').is(':checked'),
            chBox3 = $('[name="market_oferta"]').is(':checked');

        if(length==INNLenth && chBox1 /*&& chBox2 && chBox3*/)
        {
            $('#form_btn').removeClass('disable');
        }
        else
        {
            $('#form_btn').addClass('disable');
        }
    }
});