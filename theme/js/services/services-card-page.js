var Prommucard = (function (){
    function Prommucard(){
      var strYear = 4, // год - 4 цифры
        curDate = new Date(),
        curYear = curDate.getFullYear(),
        curMonth = curDate.getMonth(),
        curDay = Number(curDate.getDate());

      //
      if($('*').is('#pr-card-mes')){
        message = $('#pr-card-mes').toggleClass('tmpl');
        ModalWindow.open({ content: message, action: { active: 0 }, additionalStyle:'dark-ver'});
      }
      //  добавляем маски
      $("#pr-card-phone").mask("+7(999) 999-99-99");
      $("#pr-card-code").mask("999-99");
      $.mask.definitions['~']='[А-ЯЁа-яёA-Za-z0-9]';
      $('#pr-card-serial').mask("~~~~-~~~~~~");
      //  строим календарь
      if($('*').is('#birthday')){
        curBYear = curYear;
        curBMonth = curMonth;
        curBDay = 0;
        if($('#birthday-inp').val()!=''){
          arBirthday = $('#birthday-inp').val().split('.');
          curBYear = Number(arBirthday[2]);
          curBMonth = Number(arBirthday[1]) - 1;
          curBDay = Number(arBirthday[0]);
        }
        Calendar("birthday",curBYear,curBMonth,curBDay);
        $("#birthday input").mask("9999");
      }        
      Calendar("passport",curYear,curMonth);
      $("#passport input").mask("9999");
      /*
      *     События
      */
      // Проверяем дату по году
      $('#birthday input, #passport input').keyup(function(){ checkDate(this); });
      // Проверяем дату по месяцу
      $('#birthday select, #passport select').change(function(){ checkDate(this) });
      // Проверяем дату по дню
      $(document).on('click', '#birthday .day', function(){ checkDate(this) });
      $(document).on('click', '#passport .day', function(){ checkDate(this) });
      // Проверяем поля
      $('.required-inp').change(function(){
        var value = $(this).val();
        if(value!='' || value!=null){
          remEr(this);
        }
        else{
          addEr(this);
        }
      });
      //  копируем адрес
      $(document).on('click', '#copy-index', function(){
        value = $('#EdRegaddr').val();
        if(value != ''){ $('#EdAddr').val(value) }
      });
      //  таймер проверки
      setInterval(function(){
        var result = true;
        $.each($('.required-inp'), function(){
          if($(this).val()=='' || $(this).val()==null){ result = false }        
        });
        result ? $('#pr-card-btn').removeClass('off') : $('#pr-card-btn').addClass('off');
      },500);
      //  перед отправкой формы
      $(document).on('click', '#pr-card-btn', function(){
        var errors = false;
        $.each($('.required-inp'), function(){
          if($(this).val()=='' || $(this).val()==null){ 
            if($(this).prop('type')=='hidden'){
              addEr($(this).closest('.pr-card__calendar'));
            }
            else{
              addEr(this);
            }
            errors = true;
          }       
        });
        var arErrors = $('.error');
        if(arErrors.length>0){
          $('html, body').animate({ scrollTop: $(arErrors[0]).offset().top-20 }, 1000);
        }
        if(!errors)
          $("#F1cardOrder").submit();   
      });










      //  подсказка для некоторых полей
      $('.pr-card__form-label input').bind('focus blur', function(){
        $(this).closest('.pr-card__form-label').toggleClass('focus');
      });
      //  выбор типа пользователя
      $('#type').change(function(){
        var $selectType = $('.pr-card__form-select-block.type');
        if($(this).is(':checked')){
          $selectType.fadeOut().find('select').removeClass('required-inp error');
        }
        else{
          $selectType.fadeIn().find('select').addClass('required-inp');
        }
      });
      //  ввод только цифр для фио
      $('#pr-card-surname, #pr-card-name, #pr-card-patronymic').bind('keyup change blur', function(){ // first symbol to upper case, without numbers
        var val = $(this).val().replace(/[^а-яА-ЯїЇєЄіІёЁ ]/g,'');
          $(this).val(val.charAt(0).toUpperCase() + val.slice(1).toLowerCase());
      }); 
      //
      $('#pr-card-mail').bind('keyup change blur', function(){ // mask for mail
        var $this = $(this);
        if($this.val() != '') {
          var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
          pattern.test($this.val()) ? $this.removeClass('error') : $this.addClass('error');
        }
        else{
          $this.addClass('error');
        }
      });
      /*
      *     Финкции
      */
      function Calendar(id, year, month, bday) {
        var Dlast = new Date(year,month+1,0).getDate(),
            D = new Date(year,month,Dlast),
            DNlast = D.getDay(),
            DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
            calendar = '<tr>',
            m = document.querySelector('#'+id+' option[value="' + D.getMonth() + '"]'),
            g = document.querySelector('#'+id+' input');
        if(DNfirst != 0){ for(var  i = 1; i < DNfirst; i++) calendar += '<td>' }
        else{ for(var  i = 0; i < 6; i++) calendar += '<td>' }
        for(var  i = 1; i <= Dlast; i++) {
          if(
            i == new Date().getDate() && 
            D.getFullYear() == new Date().getFullYear() && 
            D.getMonth() == new Date().getMonth()
          ){ calendar += '<td class="day today">' + i }
          else if(id=='birthday' && bday==i){
            calendar += '<td class="day select">' + i
          }
          else{ calendar += '<td class="day">' + i }
          if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0){ calendar+='<tr>' }
        }
        for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
        document.querySelector('#'+id+' tbody').innerHTML = calendar;
        g.value = D.getFullYear();
        m.selected = true;
        if(document.querySelectorAll('#'+id+' tbody tr').length < 6){
            document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
        }
      }
      //
      function checkDate(e){
        var table =  $(e).closest('table').prop('id'),
          idTable = '#'+table,
          y = Number($(idTable+' input').val()),
          m = Number($(idTable+' select').val());

        if($(e).is(idTable+' input') || $(e).is(idTable+' select')){
          var selectDay = $(idTable).find(idTable+' .day.select');
          d = (selectDay.length>0 ? Number($(selectDay).text()) : curDay);
          elemErr = e;
        }   
        if($(e).is(idTable+' .day')){
          d = Number($(e).text());
          elemErr = $(e).closest('.pr-card__calendar');
        }
        newDate = new Date(y, m, d);

        if(Math.ceil((curDate - newDate) / (1000 * 60 * 60 * 24)) > 0){ // дата должна быть меньше сегодняшней
          remEr(idTable+' input');
          remEr(idTable+' select');
          remEr($(e).closest('.pr-card__calendar'));        
          if($(e).is(idTable+' .day')){ 
            $.each($(idTable+' .day'), function(){ $(this).removeClass('select') });
            $(e).addClass('select'); 
            str = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + y;
          }
          else{
            if(String(y).length==strYear){
              Calendar(table,y,m);
              str = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.' + y;
            }
            else{
              addEr(elemErr);
              str = ('0' + d).slice(-2) + '.' + ('0' + (m + 1)).slice(-2) + '.XXXX';
            }
          }        
          $(idTable+'-res').text(str);
          $(idTable+'-inp').val(str);
        }
        else{
          addEr(elemErr);
        }
      }
      // additional functions
      function addEr(e){ $(e).addClass('error') }
      function remEr(e){ $(e).removeClass('error') }
    }
    return Prommucard;
}());
if(!Prommucard.winObj) new Prommucard();