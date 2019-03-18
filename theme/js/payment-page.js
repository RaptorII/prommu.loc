var Payment = (function (){
    function Payment(){
      var 
        price = Number($('#payment-price').text()),
        vacCount = Number($('#payment-count').text()),
        index = $('.payment-date').length, // кол-во календарей
        dateObj = {'year': 0, 'month': 0, 'day': 0},
        dateBeg = {},
        dateEnd = {},       
        dayCount = {},
        radioLegal = false,
        curDate = new Date(),
        self = this;
        Payment.winObj = self;

      // Проверяем поля
      $('#legal-inn').mask('9999999999');
      $('#legal-kpp').mask('999999999');

      //  переключаем action в зависимости от типа лица
      $('.payment-form__radio-input').change(function(e){ 
        if($(this).val()=='legal'){
          $('#payment-legal').fadeIn();
          $('#payment-form').prop('action', $('#payment-form').data('leg'));
          radioLegal = true;
        }
        else{         
          $('#payment-legal').fadeOut();
          $('#payment-form').prop('action', $('#payment-form').data('ind'));
          radioLegal = false;
        } 
      });
      //  проверяем поля
      $('#legal-name').keyup(function(){ checkInp(this, 1) });
      $('#legal-inn').keyup(function(){ checkInp(this, 1) });
      $('#legal-kpp').keyup(function(){ checkInp(this, 1) });    
      // проверяем форму
      setInterval(function(){
        flagDate = true;
        for(var i=0; i<index; i++){
          if(dayCount[i]==0){
            flagDate = false;
          }
          else{
            var arCldBlocks = $('.payment-date').eq(i).find('.payment__date-border');
            $.each(arCldBlocks, function(){
              $(this).removeClass('error');
            });
          }
        }
      }, 500);
      //
      //    календари
      //
      // создаем данные для календарей
      for (var i=0 ; i<index; i++){
        dateBeg[i]={ 'year':dateObj.year, 'month':dateObj.month, 'day':dateObj.day };
        dateEnd[i]={ 'year':dateObj.year, 'month':dateObj.month, 'day':dateObj.day };
        dayCount[i] = 0;
      }
      //  строим календари
      $.each($('.payment__calendar'), function(){
        Calendar(this, curDate.getFullYear(), curDate.getMonth());
      });
      //  выбор даты
      $(document).on('click', '.payment__calendar .day', function(){ checkDate(this) });
      //  переключаем месяцы
      $('.month-left').click(function(e){
        var table = $(this).closest('table')[0],      
          monthName = table.querySelector('.month-name');
        Calendar(table, monthName.dataset.year, parseFloat(monthName.dataset.month)-1);
      });
      $('.month-right').click(function(e){ 
        var table = $(this).closest('table')[0],
          monthName = table.querySelector('.month-name');
        Calendar(table, monthName.dataset.year, parseFloat(monthName.dataset.month)+1);
      });
      //
      //    calendar
      //
      function Calendar(item, year, month){
        var Dlast = new Date(year,month+1,0).getDate(),
          D = new Date(year,month,Dlast),
          DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
          DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
          calendar = '<tr>',
          month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
          date = new Date(),
          body = item.querySelector('tbody'),
          monthName =  item.querySelector('.month-name');      

        if(DNfirst != 0){
          for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
        }else{
          for(var  i = 0; i < 6; i++) calendar += '<td>';
        }
        for(var  i = 1; i <= Dlast; i++){
          newDate = new Date(D.getFullYear(),D.getMonth(),i);
          if(i==date.getDate() && D.getFullYear()==date.getFullYear() && D.getMonth()==date.getMonth()){ // today
            calendar += '<td class="day today">' + i;
          }
          else if(diffDate(newDate, date)<=0){
            calendar += '<td class="day past">' + i;
          }
          else{
            calendar += '<td class="day">' + i;
          }
          if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
            calendar += '<tr>';
          }
        }
        for(var i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';

        body.innerHTML = calendar;
        monthName.innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
        monthName.dataset.month = D.getMonth();
        monthName.dataset.year = D.getFullYear();
        if($(body).find('tr').length < 6){// всегда 6 строк
          body.innerHTML += '<tr><td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td class="empty">&nbsp;<td>&nbsp;';
        }
      }
      function checkDate(elem){
        var parent = $(elem).closest('.payment-date')[0],
          i = $('.payment-date').index(parent);
          subparent = $(elem).closest('.payment__date-calendar')[0],
          date = subparent.querySelector('.month-name'),
          begErr = $(parent).find('.begin .payment__date-error'),
          endErr = $(parent).find('.end .payment__date-error'),
          begCalendar = parent.querySelector('.begin .payment__calendar'),
          endCalendar = parent.querySelector('.end .payment__calendar'),
          newDate = new Date(date.dataset.year, date.dataset.month, Number($(elem).text()));
        dateBeg[i].year!=0 ? begDate = new Date(dateBeg[i].year, dateBeg[i].month, dateBeg[i].day) : begDate=0;
        dateEnd[i].year!=0 ? endDate = new Date(dateEnd[i].year, dateEnd[i].month, dateEnd[i].day) : endDate=0;

        if($(subparent).hasClass('begin')){ // дата начала
          if(diffDate(newDate,curDate) >= 0){ // не прошедшая ли дата
            if(endDate){  // дата окончания уже есть
              if(diffDate(endDate,newDate) > 0){ // а вдруг позже даты окончания
                setDate(dateBeg[i], newDate, elem, 'begin');
                dayCount[i] = diffDate(endDate,newDate);
                $(begErr).hide();
              }
              else{
                $(begErr).show();
              }
            }
            else{
              setDate(dateBeg[i], newDate, elem, 'begin');
              Calendar(endCalendar, parseFloat(newDate.getFullYear()), parseFloat(newDate.getMonth()));
              $(begErr).hide();
            }
          }
          else{
            $(begErr).show();
          }
          if(endDate && diffDate(endDate,begDate) > 0){
            $(endErr).hide();
          }
        }
        if($(subparent).hasClass('end')){  // дата окончания
          if(diffDate(newDate,curDate) > 0){ // не прошедшая ли дата
            if(begDate){  // дата начала уже есть
              if(diffDate(newDate,begDate) > 0){ // а вдруг раньше даты начала
                setDate(dateEnd[i], newDate, elem, 'end');
                dayCount[i] = diffDate(newDate,begDate);
                $(endErr).hide();
              }
              else{
                $(endErr).show();
              }
            }
            else{
              setDate(dateEnd[i], newDate, elem, 'end');
              Calendar(begCalendar, parseFloat(newDate.getFullYear()), parseFloat(newDate.getMonth()));
              $(endErr).hide();
            }
          }
          else{
            $(endErr).show();
          }
          if(begDate && diffDate(begDate,curDate) >= 0){
            $(begErr).hide();
          }
        }
        $(parent).find('.payment-period').text(ending(dayCount[i]));
        $(parent).find('.payment-period-inp').val(dayCount[i]);
        allDayCount = 0;
        for(var i=0; i<index; i++){ allDayCount+=dayCount[i] }
        $('#payment-period').text(ending(allDayCount));
        if(allDayCount){
          str = price + ' * ' + allDayCount + ' = ' + price*allDayCount + 'руб'; 
          $('#payment-result').text(str);
        }
      }
      //
      function diffDate(date1, date2){
        miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
      }
      //
      function setDate(obj, date, e, type){
        obj.year = Number(date.getFullYear());
        obj.month = Number(date.getMonth());
        obj.day = Number(date.getDate());
        tDate = new Date();
        hours = tDate.getHours();
        min = tDate.getMinutes();
        sec = '00';
        str = ('0' + obj.day).slice(-2) + '.' + ('0' + (obj.month + 1)).slice(-2) + '.' +  obj.year 
          + ' ' + 
          hours + ':' + min + ':' + sec;
        parent = $(e).closest('.payment-date');
        parent.find('.payment-'+type+'-inp').val(str);
        parent.find('.payment-'+type).text(str);
        arDays = parent.find('.'+type+' .day');
        $.each(arDays, function(){ $(this).removeClass('select') });
        $(e).addClass('select');
      }
      //
      function ending(num){
        var real = num;
        if (num < 21 && num > 4) return real+' дней';
        num = num%10;
        if (num == 1) return real+' день';
        if (num > 1 && num < 5) return real+' дня';
        return real+' дней';
      }
      //
      function checkInp(e, err=0){
        var val = $(e).val();
        if(val==''){
          if(err){ $(e).addClass('error') };
          return false;
        }
        else{
          if(err){ $(e).removeClass('error') };
          return true;
        }
      }
      //
      //
      //
      $('#payment-btn').click(function(e){
        e.preventDefault();
        flagDate = true;
        for(var i=0; i<index; i++){
          if(dayCount[i]==0){
            flagDate = false;
            var arCldBlocks = $('.payment-date').eq(i).find('.payment__date-border');
            $.each(arCldBlocks, function(){
              $(this).addClass('error');
            });
          }
        }

        if(
          (radioLegal&&flagDate&&checkInp('#legal-inn')&&checkInp('#legal-kpp')&&checkInp('#legal-name'))
          ||
          (!radioLegal&&flagDate)
        ){
          console.log(111);

          if(MainScript.isButtonLoading(this))
            return false;
          else
            MainScript.buttonLoading(this,true);
          $('#payment-form').submit();
        }
      });
    }
    return Payment;
}());
if(!Payment.winObj) new Payment();