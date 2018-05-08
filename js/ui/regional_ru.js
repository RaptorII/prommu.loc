jQuery(function($){
        $.datepicker.regional['ru'] = {clearText: 'Очистить', clearStatus: '',
            minDate: "0",
            closeText: 'Закрыть', closeStatus: '',
            prevText: '<Пред',  prevStatus: '',
            nextText: 'След>', nextStatus: '',
            currentText: 'Сегодня', currentStatus: '',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
            'Июл','Авг','Сен','Окт','Ноя','Дек'],
            monthStatus: '', yearStatus: '',
            weekHeader: 'Не', weekStatus: '',
            dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
            dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
            dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            dayStatus: 'DD', dateStatus: 'D, M d',
            dateFormat: 'dd.mm.yy', firstDay: 1, 
            initStatus: '', isRTL: false};
            
            
  $.datepicker.setDefaults({
      showOn: "both",
      buttonImageOnly: true,
      buttonImage: "/images/ico/calendar.gif",
      buttonText: "Календарь",
      dateFormat: 'dd.mm.yy',
      extend: $.datepicker.regional["ru"],
  }, $.datepicker.regional['ru']
  );
              
  });