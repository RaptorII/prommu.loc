/**
 * Miha, adaptiruy)
 */

$(document).ready(function () {

    selectCities({
        'main' : '#filter-city',
        'arCity' : undefined,
        'inputName' : 'cities[]'
    });
    //
    function selectCities(obj) {
        var $main = $(obj.main).append('<ul class="filter-city-select">'+
            '<li data-id="0"><input type="text" name="c"></li></ul>'+
            '<ul class="select-list"></ul><b></b>'), // родитель
            $select = $main.find('ul').eq(0), // список ввода
            $input = $select.find('input'), // ввод города
            $list = $main.find('ul').eq(1), // список выбора
            $load = $main.find('b'), // тег загрузки
            bShowCityList = true, // флаг отображения списка городов
            cityTimer = false; // таймер обращения к серверу для поиска городов

        // добавляем уже выбранный город
        if(obj.arCity != undefined) {
            content = '<li data-id="' + obj.arCity.id + '">' +
                obj.arCity.name + '<i></i><input type="hidden" name="' +
                obj.inputName + '" value="' + obj.arCity.id + '">' +
                '</li>';
            $select.prepend(content);
        }
        // при клике по блоку фокусируем на поле ввода
        $select.click(function(e){ if(!$(e.target).is('i')) $input.focus() });
        $input.click(function(e){ if(!$(e.target).is('i')) $input.focus() })
        // обработка событий поля ввода
        $input.bind('input focus blur', function(e){
            setFirstUpper($input);

            var val = $input.val(),
                sec = e.type==='focus' ? 1 : 1000;

            // делаем ширину поля по содержимому, чтобы не занимало много места
            $input.val(val).css({width:(val.length * 10 + 5)+'px'});
            bShowCityList = true;
            clearTimeout(cityTimer);
            cityTimer = setTimeout(function(){
                setFirstUpper($input);

                var arResult = [],
                    content = '',
                    val = $input.val(),
                    piece = $input.val().toLowerCase();

                arSelectId = getSelectedCities($select);// находим выбранные города

                if(e.type!=='blur'){ // если мы не потеряли фокус
                    if(val===''){ // если ничего не введено
                        $load.show(); // показываем загрузку
                        $.ajax({
                            url: "/ajaxvacedit/vegetcities/",
                            data: 'query=' + val,
                            dataType: 'json',
                            success: function(res){
                                $.each(res.suggestions, function(){ // список городов если ничего не введено
                                    if($.inArray(this.data, arSelectId)<0)
                                        content += '<li data-id="' + this.data + '">' + this.value + '</li>';
                                });
                                if(bShowCityList)
                                    $list.empty().append(content).fadeIn();
                                else{
                                    $list.empty().append(content).fadeOut();
                                    $input.val('');
                                }
                                $load.hide();
                            }
                        });
                    }
                    else{
                        $load.show();
                        $.ajax({
                            url: "/ajaxvacedit/vegetcities/",
                            data: 'query=' + val,
                            dataType: 'json',
                            success: function(res){
                                $.each(res.suggestions, function(){ // список городов если что-то введено
                                    word = this.value.toLowerCase();
                                    // если введен именно город полностью
                                    if(
                                        word===piece
                                        &&
                                        $.inArray(this.data, arSelectId)<0
                                        &&
                                        this.data!=='man'
                                    ){
                                        html =  '<li data-id="' + this.data + '">' + this.value +
                                            '<i></i><input type="hidden" name="' +
                                            obj.inputName + '" value="' + this.data + '"/>' +
                                            '</li>';
                                        $select.find('[data-id="0"]').before(html);
                                        bShowCityList = false;
                                    }
                                    else if(
                                        word.indexOf(piece)>=0
                                        &&
                                        $.inArray(this.data, arSelectId)<0
                                        &&
                                        this.data!=='man'
                                    )
                                        arResult.push( {'id':this.data, 'name':this.value} );
                                });
                                arResult.length>0
                                    ? $.each(arResult, function(){
                                        content += '<li data-id="' + this.id + '">' + this.name + '</li>'
                                    })
                                    : content = '<li class="emp">Список пуст</li>';
                                if(bShowCityList)
                                    $list.empty().append(content).fadeIn();
                                else{
                                    $list.empty().append(content).fadeOut();
                                    $input.val('');
                                }
                                $load.hide();
                            }
                        });
                    }
                }
                else{ // если потерян фокус раньше времени
                    $input.val('');
                }
            },sec);
        });
        // Закрываем список
        $(document).on('click', function(e){
            if(
                $(e.target).is('li')
                &&
                $(e.target).closest($list).length
                &&
                !$(e.target).hasClass('emp')
            ) { // если кликнули по списку && если это не "Список пуст" &&
                $(e.target).remove();
                html =  '<li data-id="' + $(e.target).data('id') +
                    '">' + $(e.target).text() +
                    '<i></i><input type="hidden" name="' + obj.inputName +
                    '" value="' + $(e.target).data('id') + '"/>' + '</li>';
                $select.find('[data-id="0"]').before(html);
                $list.fadeOut();
            }
            // удаление выбраного города из списка
            if($(e.target).is('i') && $(e.target).closest($select).length){
                $(e.target).closest('li').remove();
                l = getSelectedCities($select).length;
            }
            // закрытие списка
            if(!$(e.target).is($select) && !$(e.target).closest($select).length){
                bShowCityList = false;
                $list.fadeOut();
            }
        });
    }
    function getSelectedCities(ul) {
        var arId = [],
            arSelected = $(ul).find('li');

        $.each(arSelected, function(){
            if($(this).data('id')!=0)
                arId.push(String($(this).data('id')));
        });
        return arId;
    }
    // делаем каждое слово в городе с большой
    function setFirstUpper(e) {
        var split = $(e).val().split(' '),
            len=split.length;

        for(var i=0; i<len; i++)
            split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1).toLowerCase();
        $(e).val(split.join(' '));

        split = $(e).val().split('-');
        len=split.length;

        for(var i=0; i<len; i++)
            split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1).toLowerCase();
        $(e).val(split.join('-'));
    }
});
