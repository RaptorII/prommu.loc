'use strict';
/*
*
* Проверка обязательных полей
* @param 1 - Объект, который нужно создать после ajax запроса
*
 */
var CheckRequiredFields = (function () {
  //
  function CheckRequiredFields()
  {
    this.init(arguments[0]);
  }
  //
  CheckRequiredFields.prototype.init = function ()
  {
    let self = this,
      arInputs = $('.prmu-required'),
      arForms = [],
      callBackFunction = arguments[0];

    if(!arInputs.length) // если обязательных полей нет - останавливаемся
    {
      return false;
    }

    $.each(arInputs,function(){ // собираем все формы с обязательными полями
      let input = this,
        form = $(input).closest('form');

      if(form.length)
      {
        $.each(form,function(){
          if($.inArray(this, arForms)<0)
          {
            arForms.push(this);
          }
        });
      }
    });

    if(!arForms.length) // если форм нет - тоже останавливаемся
    {
      return;
    }
    // события отправки всех форм
    for (let i=0; i<arForms.length; i++)
    {
      $(arForms[i]).off();
      $(arForms[i]).on('submit',function(e){
        let bText = self.checkTextFields(arForms[i]),
            bSelect = self.checkSelectFields(arForms[i]),
            bCheckbox = self.checkCheckboxFields(arForms[i]),
            bNicEditor = self.checkNicEditorFields(arForms[i]),
            bEditVacancyPeriod = self.checkEditVacancyPeriod(arForms[i]);

        // если хотя бы один false - отмена отправки
        if(bText || bSelect || bCheckbox || bNicEditor || bEditVacancyPeriod)
        {
          return false;
        }
        // ошибок нет, отправляем форму
        if(arForms[i].dataset.params!=undefined && arForms[i].dataset.params.length)
        {
          let params = JSON.parse(arForms[i].dataset.params);
          if(params!=undefined && params.ajax!=undefined) // по ajax
          {
            e.preventDefault();

            if(typeof MainScript==='function') // разные типы загрузок
            {
              MainScript.stateLoading(true);
            }
            else
            {
              $('body').addClass('prmu-load');
            }
            $.ajax({
              type: 'POST',
              url: arForms[i].action,
              data: $(arForms[i]).serialize(),
              success: function(result){
                callBackFunction.init(arForms[i],result);
                if(typeof MainScript==='function') // разные типы загрузок
                {
                  MainScript.stateLoading(false);
                }
                else
                {
                  $('body').removeClass('prmu-load');
                }
              },
              error: function()
              {
                confirm('Системная ошибка');
                if(typeof MainScript==='function') // разные типы загрузок
                {
                  MainScript.stateLoading(false);
                }
                else
                {
                  $('body').removeClass('prmu-load');
                }
              }
            });
          }
        }
      });
    }
  };
  // Проверка всех обязательных текстовых полей формы
  CheckRequiredFields.prototype.checkTextFields = function ()
  {
    let self = this,
      form = arguments[0],
      arTextInputs = $(form).find('[type="text"].prmu-required'),
      bError = false;

    if(!arTextInputs.length) // если текстовых полей нет - завершаем
    {
      return bError;
    }

    $.each(arTextInputs,function () {
      let val = $(this).val(),
        objP = self.getParams(this);

      if(!val.trim().length) // пустое поле
      {
        if(objP.hasMessage)
        {
          let parent = $(this).closest(objP.params.parent_tag);
          if(parent.length && !$(parent).find('.prmu-error-mess').length)
          {
            $(parent).prepend('<span class="prmu-error-mess">' + objP.params.message + '</span>')
          }
        }
        $(this).addClass('prmu-error');
        bError = true;
      }
      else // не пустое поле
      {
        /*if(objP.params.type!=undefined) // здесь можно проверять по типам. Например по дате, email, телефону итд
        {

        }*/
        if(objP.hasMessage)
        {
          let parent = $(this).closest(objP.params.parent_tag);
          $(parent).find('.prmu-error-mess').remove();
        }
        $(this).removeClass('prmu-error');
      }
    });

    return bError;
  };
  // Проверка всех обязательных селектов формы
  CheckRequiredFields.prototype.checkSelectFields = function ()
  {
    let self = this,
      form = arguments[0],
      arSelect = $(form).find('.prmu-required select'),
      bError = false;

    if(!arSelect.length) // если селектов нет - завершаем
    {
      return bError;
    }

    $.each(arSelect,function(){
      let main = $(this).closest('.prmu-required'),
        option = $(main).find('option:selected'),
        objP = self.getParams(main[0]);

      if(!option.length || (option.length && !option[0].value.length))
      {
        if(objP.hasMessage)
        {
          let parent = $(main[0]).closest(objP.params.parent_tag);
          if(parent.length && !$(parent).find('.prmu-error-mess').length)
          {
            $(parent).prepend('<span class="prmu-error-mess">' + objP.params.message + '</span>')
          }
        }
        $(main[0]).addClass('prmu-error');
        bError = true;
      }
      else
      {
        if(objP.hasMessage)
        {
          let parent = $(main[0]).closest(objP.params.parent_tag);
          $(parent).find('.prmu-error-mess').remove();
        }
        $(main[0]).removeClass('prmu-error');
      }
    });

    return bError;
  };
  // Проверка всех обязательных чекбоксов формы
  CheckRequiredFields.prototype.checkCheckboxFields = function ()
  {
    let self = this,
      arCheckboxes = $(arguments[0]).find('[type="checkbox"].prmu-required'),
      arNames = [],
      arNamesChecked = [],
      bError = false;

    if(!arCheckboxes.length)
    {
      return bError;
    }

    $.each(arCheckboxes, function(){
      if(!arNames.includes(this.name))
      {
        arNames.push(this.name);
      }
      if($(this).is(':checked') && !arNamesChecked.includes(this.name))
      {
        arNamesChecked.push(this.name);
      }
    });

    if(arNames.length != arNamesChecked.length)
    {
      bError = true;
      $.each(arNames,function(){
        if(!arNamesChecked.includes(this))
        {
          $.each($('[name="' + this + '"].prmu-required'), function(){
            let input = this,
              objP = self.getParams(input);

            $(input).next('label').addClass('prmu-error');
            if(objP.hasMessage)
            {
              let parent = $(input).closest(objP.params.parent_tag);
              if(parent.length && !$(parent).find('.prmu-error-mess').length)
              {
                $(parent).prepend('<span class="prmu-error-mess">' + objP.params.message + '</span>')
              }
            }
          });
        }
        else
        {
          $.each($('[name="' + this + '"].prmu-required'), function(){
            let input = this,
              objP = self.getParams(input);

            $(input).next('label').removeClass('prmu-error');
            if(objP.hasMessage)
            {
              let parent = $(input).closest(objP.params.parent_tag);
              $(parent).find('.prmu-error-mess').remove();
            }
          });
        }
      })
    }
    else
    {
      $.each(arNames,function(){
        $.each($('[name="' + this + '"].prmu-required'), function(){
          let input = this,
            objP = self.getParams(input);

          $(input).next('label').removeClass('prmu-error');
          if(objP.hasMessage)
          {
            let parent = $(input).closest(objP.params.parent_tag);
            $(parent).find('.prmu-error-mess').remove();
          }
        })
      })
    }
    return bError;
  };
  // Проверка всех textErea с nicEditor формы
  CheckRequiredFields.prototype.checkNicEditorFields = function ()
  {
    let self = this,
      arArea = $(arguments[0]).find('textarea.prmu-required'),
      bError = false,
      arNicEditMain = [], arNewArea = [];

    if(!arArea.length)
    {
      return bError;
    }

    arArea.each(function(){
      let parent = $(this).prev();
      arNicEditMain.push(parent);
      arNewArea.push(this);
    });
    if(!arNicEditMain.length)
    {
      return bError;
    }

    $.each(arNicEditMain,function(i,e){
      let objP = self.getParams(arNewArea[i]);
      if(!$(e).text().length)
      {
        if(objP.hasMessage)
        {
          let parent = $(e).closest(objP.params.parent_tag);
          if(parent.length && !$(parent).find('.prmu-error-mess').length)
          {
            $(parent).prepend('<span class="prmu-error-mess">' + objP.params.message + '</span>')
          }
        }
        $(e).addClass('prmu-error');
        bError = true;
      }
      else
      {
        if(objP.hasMessage)
        {
          let parent = $(e).closest(objP.params.parent_tag);
          $(parent).find('.prmu-error-mess').remove();
        }
        $(e).removeClass('prmu-error');
      }
    });
    return bError;
  };
  // получаем параметры объекта
  CheckRequiredFields.prototype.getParams = function ()
  {
    let params = arguments[0].dataset.params!=undefined
      ? JSON.parse(arguments[0].dataset.params)
      : undefined,
      hasMessage = params!=undefined && params.parent_tag!=undefined && params.message!=undefined;

    return {params:params, hasMessage:hasMessage};
  };
  // проверка только для страницы редактирования вакансии
  CheckRequiredFields.prototype.checkEditVacancyPeriod = function ()
  {
    let form = arguments[0],
        arCalendars = $(form).find('#period input');

    if(typeof arVacLocations!=='object' || !arCalendars.length)
    {
      return false;
    }

    let bDate = $(arCalendars[0]).datepicker('getDate').getTime(),
        eDate = $(arCalendars[1]).datepicker('getDate').getTime(),
        minDate = $(arCalendars[0]).datepicker('option', 'minDate').getTime(),
        maxDate = $(arCalendars[0]).datepicker('option', 'maxDate').getTime(),
        curDate = new Date(),
        bError1 = false, bError2 = false, message = '';

    curDate.setHours(0,0,0,0); // сегодняшняя дата, но время будет ровно 00:00:00.

    $.each(arVacLocations, function(i, location)
    {
      $.each(location.periods, function(j, period)
      {
        let periodBDate = Number(period.bdate) * 1000,
            periodEDate = Number(period.edate) * 1000;

        if(bDate > periodBDate)
        {
          bError1 = true;
        }
        if(eDate < periodEDate)
        {
          bError2 = true;
        }
      });
    });

    if(eDate < curDate.getTime())
    {
      message = 'Дата завершения не может быть меньше текущей даты';
      $(arCalendars[1]).addClass('prmu-error');
    }
    else if(bError1 && bError2)
    {
      message = 'Новые даты должны соответствовать установленным датам в событиях городов. Проверьте корректность ввода!';
      $(arCalendars[0]).addClass('prmu-error');
      $(arCalendars[1]).addClass('prmu-error');
    }
    else if(bError1)
    {
      message = 'Дата начала должна соответствовать установленным датам в событиях выбранных городов. Проверьте корректность ввода!';
      $(arCalendars[0]).addClass('prmu-error');
    }
    else if(bError2)
    {
      message = 'Дата завершения должна соответствовать установленным датам в событиях выбранных городов. Проверьте корректность ввода!';
      $(arCalendars[1]).addClass('prmu-error');
    }

    if(message.length)
    {
      $("body").append('<div class="prmu__popup"><p>' + message + "</p></div>");
      $.fancybox.open({
        src: "body>div.prmu__popup",
        type: "inline",
        touch: !1,
        afterClose: function () { $("body>div.prmu__popup").remove(); }
      })
    }

    return message.length;
  };
  //
  return CheckRequiredFields;
}());
/*
*
* Проверка ввода полей
*
 */
var CheckInputFields = (function () {
  //
  function CheckInputFields()
  {
    this.init();
  }
  //
  CheckInputFields.prototype.init = function ()
  {
    let self = this, arInputs = $('.prmu-check');

    if(!arInputs.length) // если полей нет - останавливаемся
    {
      return false;
    }

    $.each(arInputs,function(){
      self.changeInputText(this);
    });
  };
  // ограничение по кол-ву
  CheckInputFields.prototype.changeInputText = function ()
  {
    if(arguments[0].dataset.params==undefined)
    {
      return;
    }

    let params = JSON.parse(arguments[0].dataset.params);
    // event
    $(arguments[0]).off();
    $(arguments[0]).on('input',function(){
      var value = $(this).val();
      // проверка на ограничение по кол-ву символов
      if(params.limit!=undefined && value.length>params.limit)
      {
        value = value.substr(0,params.limit);
      }
      if(params.regexp!=undefined)
      {
        let regexp = new RegExp(params.regexp, 'ig');
        value = value.replace(regexp,'');
      }

      $(this).val(value);
    });
  };
  //
  return CheckInputFields;
}());
/*
* Объект селекта
* Указывается объект DOM, в котором требуется отображать список.
*   В этом объекте обязательно наличие тега select. Если у select установлен атрибут multiple
*   - можно будет выбрать несколько значений одновременно. Имитерует события изменения значения select
*   Возвращает себя
*  Описание параметров в объекте
* 'search' : true - добавить поисковую строку по списку
* 'selectedValsInOtherSelect' : [] - массив значений value,
*   которые нельзя устанавливать в этом селекте(например чтобы нельзя было выбрать города, которые подтягиваются аяксом)
* 'ajax' : string - урл, на который нужно обращаться при фокусе
*/
var InitSelect = (function () {
  //
  function InitSelect() {
    this.init(arguments[0]);
  }
  //
  InitSelect.prototype.init = function ()
  {
    var self = this,
        params = arguments[0];

    self.main = (typeof params.selector=='object' ? params.selector : $(params.selector)[0]);
    self.select = $(self.main).find('select')[0];

    if(!$(self.main).is('*') || !$(self.select).is('*'))
    {
      console.log('error in init ',params.selector);
      return;
    }
    params = (typeof params.params=='object' ? params.params : {});

    $(self.select).hide();

    self.data = {};

    self.data.search = (params.search!=undefined ? true : false);
    if(params.ajax!=undefined)
    {
      self.data.ajax = params.ajax;
      self.data.search = true;
      self.ajaxTimer = false;
    }
    self.bAjax = false;
    self.data.selectedValsInOtherSelect = params.selectedValsInOtherSelect!=undefined
      ? params.selectedValsInOtherSelect
      : [];

    self.selected = [];
    $.each($(self.select).find('option:selected'), function(){
      if($(this).is(':selected'))
      {
        self.setSelected($(this).val(),$(this).text(),true);
      }
    });

    $(document).on('click',function(e){
      if($(e.target).is(self.main)) // по блоку селекта
      {
        self.showList(true);
      }
      else if($(e.target).closest('.form__field-select').length) // по элементам селекта
      {
        let b = $(e.target).closest('.form__field-select')[0];
        if($(b).is(self.main)) // по элементам нужного селекта
        {
          if($(e.target).is('li')) // по элементам списка
          {
            self.setSelected(e.target.dataset.id, $(e.target).text(),false);
          }
          else if($(e.target).is('.form__select-selected b')) // удаляем из списка, если multiple
          {
            let parent = $(e.target).parent(),
                id = parent[0].dataset.id;

            $(self.select).find('option[value="' + id + '"]').remove();
            $(e.target).parent().remove();
            self.selected.splice(self.selected.indexOf(id));
            self.showList(false);
            $(self.select).change(); // имитируем событие селекта
          }
          else if($(e.target).is('.form__select-selected')) // просто кликаем по выделенному блоку
          {
            self.showList(true);
          }
        }
        else
        {
          self.showList(false);
        }
      }
      else // вообще не по селектам
      {
        self.showList(false);
      }
    });
    //  событие поиска
    $(self.main).on('input','.form__select-search',function(){ self.showList(true) });
  };
  // строим весь список
  InitSelect.prototype.showList = function ()
  {
    let self = this,
      list = $(self.main).find('.form__select-list'),
      arS=[], arL=[], v='';

    if(self.data.search && $(self.main).find('.form__select-search').length) // если есть строка поиска
    {
      v = $(self.main).find('.form__select-search').val().toLowerCase();
    }

    if(arguments[0]==true) // отображаем список
    {
      if(self.data.ajax!=undefined) // подтягиваем данные с помощью аякса
      {
        if(v.trim().length) // фильтруем по поиску(при наличии)
        {
          clearTimeout(self.ajaxTimer);
          self.ajaxTimer = setTimeout(function(){
            self.bAjax = true;
            $(self.main).addClass('form__field-load');
            $.ajax({
              url: self.data.ajax,
              data: 'query=' + v,
              dataType: 'json',
              success: function(result){
                $(self.main).removeClass('form__field-load');
                $.each(
                  result,
                  function(){
                    if(!self.data.selectedValsInOtherSelect.includes(this.id))
                    {
                      arL.push({id:this.id, name:this.name});
                    }
                  }
                );
                self.buildList(arL);
                self.bAjax = false;
              }
            })
          },1000)
        }
        else  // поиск пустой, отображаем все
        {
          if(self.bAjax)
          {
            return false;
          }

          self.bAjax = true;
          $(self.main).addClass('form__field-load');
          clearTimeout(self.ajaxTimer);
          self.ajaxTimer = setTimeout(function(){
            $.ajax({
              url: self.data.ajax,
              dataType: 'json',
              success: function(result){
                $(self.main).removeClass('form__field-load');
                $.each(
                  result,
                  function(){
                    if(!self.data.selectedValsInOtherSelect.includes(this.id))
                    {
                      arL.push({id:this.id, name:this.name});
                    }
                  }
                );
                self.buildList(arL);
                self.bAjax = false;
              }
            })
          })
        }
      }
      else // выбираем данные из селекта
      {
        $.each(
          $(self.main).find('option'),
          function(){
            arS.push({id:this.value, name:$(this).text()})
          }
        );

        if(v.trim().length) // фильтруем по поиску(при наличии)
        {
          $.each(arS,function(){
            if(this.name.toLowerCase().indexOf(v)>=0)
            {
              arL.push(this);
            }
          });
        }
        else // поиск пустой, отображаем все
        {
          arL = arS;
        }

        self.buildList(arL);
      }
    }
    else // убираем список
    {
      if(list.length)
      {
        $(list[0]).hide();
        if(self.data.search) // очищаем поиск, если скрываем список
        {
          $(list[0]).find('input').val('');
        }
      }
    }
  };
  // строим список
  InitSelect.prototype.buildList = function ()
  {
    let self = this,
      html='',
      list = $(self.main).find('.form__select-list');

    // если список уже создавался
    if(list.length)
    {
      // удаляем все элементы
      $.each($(list).find('.form__select-li'),
        function(i,e){
          if(self.data.search)
          {
            if(i>0) // кроме поиска
            {
              $(e).remove();
            }
          }
          else
          {
            $(e).remove();
          }
        });
    }
    else // создаем список с нуля
    {
      html = '<ul class="form__select-list">';
      if(self.data.search)
      {
        html += '<li class="form__select-li" data-id="">'
          + '<input type="text" class="form__select-search" autocomplete="off"/>'
          + '</li>';
      }
    }
    // добавляем актуальные пункты в список
    if(arguments[0].length)
    {
      $.each(arguments[0], function(){
        html +='<li data-id="'
          + this.id + '" class="form__select-li' +
          (self.selected.includes(this.id)?' form__select-li-active':'') + '">'
          + this.name + '</li>';
      });
    }
    else
    {
      html +='<li class="form__select-li form__select-li-empty" data-id="">Ничего не найдено</li>';
    }

    if(list.length)
    {
      $(list[0]).append(html).show();
    }
    else
    {
      $(self.main).append(html);
    }
    if(self.data.search) // если есть поле поиска - фокусируемся на нем
    {
      $(self.main).find('.form__select-search').focus();
    }
  };
  // выбираем из списка
  InitSelect.prototype.setSelected = function ()
  {
    let self = this,
      id = arguments[0],
      name = arguments[1],
      isInit = arguments[2];

    if(!id.length) // клик по поиску
    {
      return;
    }

    if(self.selected.includes(id)) // если элемент существует - завершаем
    {
      this.showList(false);
      return;
    }

    if(!self.select.multiple) // если селект не мультипл
    {
      let option = $(self.select).find('option[value="' + id + '"]'),
        selected = $(self.main).find('.form__select-selected');

      self.selected = [];
      if(selected.length)
      {
        $(selected).text(name);
      }
      else
      {
        $(self.main).append('<div class="form__select-selected">' + name + '</div>');
      }
      self.selected.push(id);
      $.each($(self.select).find('option'),function(){ $(this).prop('selected',false) });
      option.length
        ? $(option).prop('selected',true)
        : $(self.select).append('<option value="' + id + '" selected>');
      if(!isInit)
      {
        $(self.select).change(); // имитируем событие селекта
      }
    }
    else // если селект мультипл
    {
      $(self.main).append('<div class="form__select-selected form__select-selected-multi" data-id="'
        + id + '">' + name + '<b></b></div>');
      if(!$(self.select).find('[value="' + id + '"]').length)
      {
        $(self.select).append('<option value="' + id + '" selected>');
      }
      if(!isInit)
      {
        $(self.select).change(); // имитируем событие селекта
      }
      self.selected.push(id);
    }

    this.showList(false);
  };
  //
  return InitSelect;
}());
/**
 *
 *    событие инициализации datepicker
 *
 */
var InitPeriod = (function () {
  //
  function InitPeriod() {
    this.init(arguments[0]);
  }
  //
  InitPeriod.prototype.init = function ()
  {
    var params = arguments[0];
    if(typeof params!=='object' || typeof params.selector==undefined)
    {
      console.log('error in init',params);
      return;
    }

    $.each($(params.selector),function(){
      let arInputs = $(this).find('input'),
          bParams = {
            beforeShow: function(){
              $('#ui-datepicker-div').addClass('custom-calendar');
            }
          },
          eParams = {
            beforeShow: function(){
              $('#ui-datepicker-div').addClass('custom-calendar');
            }
          };

      if(typeof params.minDate!=undefined)
      {
        bParams.minDate = params.minDate;
        eParams.minDate = params.minDate;
      }
      if(typeof params.maxDate!=undefined)
      {
        bParams.maxDate = params.maxDate;
        eParams.maxDate = params.maxDate;
      }

      bParams.firstDay = 1;
      eParams.firstDay = 1;

      $(arInputs[0]).datepicker(bParams);
      $(arInputs[1]).datepicker(eParams);

      $(arInputs).on("change", function(){
          let parent = $(this).closest(params.selector),
            arCalendars = $(parent).find('input'),
            date1 = $(arCalendars[0]).datepicker('getDate'),
            date2 = $(arCalendars[1]).datepicker('getDate');

          if($(this).is(arCalendars[0]))
          {
            $(arCalendars[0]).datepicker('setDate',date1);
            $(arCalendars[1]).datepicker("option","minDate",date1);
          }
          else
          {
            $(arCalendars[1]).datepicker('setDate',date2);
            $(arCalendars[0]).datepicker("option","maxDate",date2);
          }
        });
    });
  };
  //
  return InitPeriod;
}());
/**
 *
 *    Инициализация редактора
 *
 */
var InitNicEditor = (function () {
  //
  function InitNicEditor() {
    this.init(arguments[0], arguments[1]);
  }
  //
  InitNicEditor.prototype.init = function ()
  {
    let self = this;

    self.params = {buttonList: ['bold', 'italic', 'underline', 'ol', 'ul']};

    if(typeof nicEditor!=='function')
    {
      console.log('error in NicEditor init');
    }

    if(!$(arguments[0]).length || $(arguments[0]).prev().find('.nicEdit-main').length)
    {
      return;
    }
    let id = $(arguments[0]).attr('id');
    self.item = $(arguments[0]);
    self.parent = $(arguments[0]).parent();

    self.object = new nicEditor(self.params).addInstance(id);

    if(!$(arguments[1]).length)
    {
      return;
    }
    self.object.setPanel($(arguments[1]).attr('id'));

    $('.nicEdit-main').bind('paste',function() {
      let field = this;

      if(!$(field).closest(self.parent).length)
      {
        return;
      }
      setTimeout(function(){
        let html = $(field).html().replace(/(<([^>]+)>)/ig,' ');
        $(field).html(html);
      },100);
    });
  };
  //
  return InitNicEditor;
}());
/*
*
*   Jquery методы
*
*/
$(function(){
  $.fn.initSelect = (function(){
    return new InitSelect({
      selector : this.selector,
      params : arguments[0]
    });
  });
});