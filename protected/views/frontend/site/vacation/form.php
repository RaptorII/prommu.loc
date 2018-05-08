<?php
Yii::app()->getClientScript()->registerScriptFile('http://maps.google.com/maps/api/js?sensor=false', CClientScript::POS_HEAD);
//Yii::app()->getClientScript()->registerScriptFile('/js/jquery-ui-1.10.3.custom.min.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile('/js/ui/jquery-ui.min.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile('/js/ui/regional_ru.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile('/js/hoverIntent.min.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/jsform.js', CClientScript::POS_HEAD);
echo CHtml::form('', 'post', array('enctype' => 'multipart/form-data'));
?>

<h3>Создание вакансии</h3>

<div class="row">
<div class="span12">
<div class="form-horizontal">


<div class="accordion" id="accordion2">

<div class="accordion-group">
    <div class="accordion-heading silver">
        <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Вакансия</a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
        <div class="accordion-inner">
            <?php
            echo FormHelper::TextField('vacation_name', '', 'Название вакансии (Акции) *', 'пример: Промоутер на дегустацию вин', 'span7', '', '', 1);

            // Должность (new)
            echo FormHelper::ListRadioDB('app', 'Должность *', 'appointment', 1);

            // Тип занятости (new)
            echo FormHelper::ListRadioMemo('empl_type', 'Тип занятости *', Share::$empl_type, 0);
            echo FormHelper::TextArea('description', '', 'Описание акции *', 'пример: розыгрыш призов', 'span5', 'height:200px;', 1);
            ?>
            <div class="control-group">
                <label class="control-label">Оплата за 1 час * <span id="money_err"
                                                                     class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <input type="text" name="money" id="money" placeholder="100" onchange="checkMoney()"
                           class="nm span1"/><span class="add-on">.00</span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="accordion-group">
    <div class="accordion-heading silver">
        <a href="#collapse10" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Узкие
            требования</a>
    </div>
    <div id="collapse10" class="accordion-body collapse">
        <div class="accordion-inner">
            <?php
            echo FormHelper::ListRadioDB('narrow_req', 'Узкие требования *', 'narrow_req', 0);
            echo FormHelper::ListCheckBoxDB('jobs_lang', 'Знание языка *', 'jobs_lang', 0);
            ?>
        </div>
    </div>
</div>


<div class="accordion-group">
    <div class="accordion-heading silver">
        <a href="#collapse1" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Требования к
            кандидату</a>
    </div>
    <div id="collapse1" class="accordion-body collapse">
        <div class="accordion-inner">

            <?php
            echo FormHelper::ListCheckBoxDB('mech_ids', 'Механика акции *', 'mech', 0);
            ?>

            <div class="control-group">
                <label class="control-label">Место работы * <span id="work_err"
                                                                  class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <label class="checkbox"><input type="checkbox" id="ch_work_1" name="work" class="work" value="1"/>Помещение</label>
                    <label class="checkbox"><input type="checkbox" id="ch_work_2" name="work" class="work" value="2"/>Улица</label>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Возраст от * <span id="age_from_err"
                                                                class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <label><b id="l_age_from">16</b>&nbsp;<input type="range" min="16" max="50" value="16"
                                                                 id="p_age_from" onchange="changeAge(0)" class="span2"></label>
                </div>
            </div>


            <div class="control-group">
                <label class="control-label">Возраст до * <span id="age_to_err"
                                                                class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <label><b id="l_age_to">25</b>&nbsp;<input type="range" min="16" max="50" value="25" id="p_age_to"
                                                               onchange="changeAge(1)" class="span2"></label>
                </div>
            </div>

            <input type="hidden" name="age_from" id="age_from" value="16"/>
            <input type="hidden" name="age_to" id="age_to" value="25"/>

            <?php
            echo FormHelper::TextArea('requirements', '', 'Требования к промоутеру', 'умение проводить конкурсы', 'span5', 'height:200px;', 1);
            echo FormHelper::TextArea('responsibility', '', 'Обязанности', 'пример: розыгрыш призов', 'span5', 'height:200px;', 1);
            //echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>$model, 'attribute'=>'access_time','language'=>'ru', 'id'=>'action_money_dt'), true);
            ?>


        </div>
    </div>
</div>

<div class="accordion-group">
    <div class="accordion-heading silver">
        <a href="#collapse2" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Контактная
            информация</a>
    </div>
    <div id="collapse2" class="accordion-body collapse">
        <div class="accordion-inner">

            <div class="control-group">
                <label class="control-label">Название Рекламного агентства *<span id="ra_err"
                                                                                  class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <div id="ra_list"><select name="ra" id="ra"></select></div>
                </div>
            </div>

            <?php
            echo FormHelper::TextField('city', '', 'Регион/Город *', 'Москва', '', '', '', 1);
            echo FormHelper::TextField('address', '', 'Адрес *', 'пример: ул. Льва Толстого, 5', 'span7', '', '', 1);
            ?>
            <!-- MAP  -->
            <div class="control-group">
                <label class="control-label"></label>

                <div class="controls input-append">
                    <a href="javascript:void(0)" onclick="getPoint()" class="btn btn-success">карта</a>
                </div>
            </div>

            <div id="map_canvas" class="lifted">
                <img src="/images/handyicon.png">
                <!-- Здесь разместим карту --->
            </div>

            <div class="control-group">
                <label class="control-label">Телефон<span id="phone_err"
                                                          class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <input type="text" name="phone" id="phone" placeholder="пример: 095-1000101" disabled="disabled"/>
                </div>
            </div>


            <div class="control-group">
                <label class="control-label">Email<span id="email_err"
                                                        class="err badge badge-important">!</span></label>

                <div class="controls input-append">
                    <input type="text" name="email" id="email" placeholder="пример: vingrad@mail.ru"
                           disabled="disabled"/>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="accordion-group">
    <div class="accordion-heading silver">
        <a href="#collapse3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Дата и время
            работы</a>
    </div>
    <div id="collapse3" class="accordion-body collapse">
        <div class="accordion-inner">
            <?php
            echo FormHelper::TextField('d', '', 'Срок акции: начало - конец *', '', '', 'datepicker', '', 1);
            echo FormHelper::Hidden('action_begin', '');
            echo FormHelper::Hidden('action_end', '');
            echo FormHelper::TextField('action_money_dt', '', 'Срок выплаты (не позднее) *', '', 'span2', '', '', 1);
            ?>
            <div class="control-group">
                <label class="control-label">График работы *</label>

                <div class="controls input-append"></div>
            </div>

            <!-- График работы (время)*  -->

            <div class="control-group">
                <label class="control-label">Пн-Пт начало</label>

                <div class="controls input-append">
                    <label><b id="tm_begin">10:00</b>&nbsp;<input type="range" value="600" min="0" max="1440"
                                                                  id="size_begin" onchange="sizeTime(0)"
                                                                  style="width:200px"></label>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Пн-Пт конец</label>

                <div class="controls input-append">
                    <label><b id="tm_end">14:00</b>&nbsp;<input type="range" value="840" min="0" max="1440"
                                                                id="size_end" onchange="sizeTime(1)"
                                                                style="width:200px"></label>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Сб-Вс начало</label>

                <div class="controls input-append">
                    <label><b id="tm_begin2">10:00</b>&nbsp;<input type="range" value="600" min="0" max="1440"
                                                                   id="size_begin2" onchange="sizeTime2(0)"
                                                                   style="width:200px"></label>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Сб-Вс конец</label>

                <div class="controls input-append">
                    <label><b id="tm_end2">14:00</b>&nbsp;<input type="range" value="840" min="0" max="1440"
                                                                 id="size_end2" onchange="sizeTime2(1)"
                                                                 style="width:200px"></label>
                </div>
            </div>

            <input type="hidden" name="twd_start" id="twd_start" value="10"/>
            <input type="hidden" name="twd_end" id="twd_end" value="14"/>
            <input type="hidden" name="twe_start" id="twe_start" value="10"/>
            <input type="hidden" name="twe_end" id="twe_end" value="14"/>


        </div>
    </div>
</div>

</div>
<!-- End Accordion -->


</div>
<!-- end panel -->
</div>
</div>

<br/>
<div class="alert alert-error err" id="total_err">
    Не все поля заполнены! Проверьте фому.
</div>

<div class="modal-footer">
    <a href='/' class='btn btn-inverse' id='btn_cancel_form'>Отмена</a>
    <a href='javascript:Save();' class='btn btn-primary' id='btn_save_form'>Сохранить</a>
</div>


<?php echo CHtml::endForm(); ?>

<script src="/js/jquery.daterange.js"></script>
<script type="text/javascript">
var mode_vacation;
var vid;

// tp - тип переменной:
// 0-текст;
// 1-radiogroup;
// 2-checkbox;
// для типов 1 и 2 должно быть задано name html элемента.
// 3-сравнение 2-ух полей
// 4-selected (выпадающий список)
// 5-selected multiselect
// 6-textbox с поиском (для моб.)

var objForm = (
    {"total_err":0, "param":""},
        [
            { "name":"vacation_name", "value":"", "tp":0, "validate":1 },
            { "name":"app", "value":"", "tp":1, "validate":1 },
            { "name":"ra", "value":"", "tp":4, "validate":1 },
            { "name":"action_begin", "value":"", "tp":0, "validate":0 },
            { "name":"action_end", "value":"", "tp":0, "validate":0 },
            { "name":"city", "value":"", "tp":0, "validate":1 },
            { "name":"address", "value":"", "tp":0, "validate":1 },
            { "name":"mech_ids", "value":"", "tp":2, "validate":1 },
            { "name":"work", "value":"", "tp":2, "validate":1 },
            { "name":"twd_start", "value":"", "tp":0, "validate":1 },
            { "name":"twd_end", "value":"", "tp":0, "validate":1 },
            { "name":"twe_start", "value":"", "tp":0, "validate":1 },
            { "name":"twe_end", "value":"", "tp":0, "validate":1 },
            { "name":"money", "value":"", "tp":0, "validate":1 },
            { "name":"age_from", "value":"", "tp":0, "validate":1 },
            { "name":"age_to", "value":"", "tp":0, "validate":1 },
            { "name":"action_money_dt", "value":"", "tp":0, "validate":1 },
            { "name":"description", "value":"", "tp":0, "validate":1 },
            { "name":"requirements", "value":"", "tp":0, "validate":0 },
            { "name":"responsibility", "value":"", "tp":0, "validate":0 },
            { "name":"phone", "value":"", "tp":0, "validate":0 },
            { "name":"email", "value":"", "tp":0, "validate":0 },
            { "name":"empl_type", "value":"", "tp":1, "validate":0 },
            { "name":"narrow_req", "value":"", "tp":1, "validate":0 },
            { "name":"jobs_lang", "value":"", "tp":2, "validate":1 }
        ]);


var vac = {
    vacation_name:"",
    action_begin:"",
    action_end:"",
    action_money_dt:"",
    email:"",
    phone:"",
    city:0,
    mech_ids:"",
    description:"",
    requirements:"",
    responsibility:"",
    iswork_room:0,
    iswork_street:0,
    money:0,
    ra_id:0,
    age_from:"",
    age_to:""
};

var isMap = false;

function getForm_Blank() {
    if (!!uid) {
        link = site_url + "?cmd=GET_VACATION_BLANK&value=&uid=" + uid + "&callback=?";
        jsonp(link, function (data) {
            parseForm(data);
            getRaList();
        });
    }
}

function getForm(id) {
    if (!!uid) {
        link = site_url + "?cmd=GET_VACATION&value=&uid=" + uid + "&id=" + id + "&callback=?";
        jsonp(link, function (data) {
            parseEditForm(data);
            getRaList();
        });
    }
}

function parseEditForm(data) {
    var t;
    $("#email").val(data.email);
    $("#phone").val(data.phone1 + "; " + data.phone2);
    vac.id_city = data.id_city;
    vac.ra_id = data.id_ra;
    $("#city").val(data.city);
    $("#action_begin").val(data.date_begin);
    $("#d").val(data.date_begin + ' - ' + data.date_end);
    $("#address").val(data.address);
    $("#action_end").val(data.date_end);
    $("#vacation_name").val(urldecode(data.name_act));
    vac.ra_id = data.id_ra;
    vac.iswork_room = data.iswork_room;
    vac.iswork_street = data.iswork_street;
    if (data.iswork_room == '1') {
        $("#ch_work_1").attr("checked", true);
    }
    if (data.iswork_street == '1') {
        $("#ch_work_2").attr("checked", true);
    }
    $("#twd_start").val(data.work_twd_start);
    $("#twd_end").val(data.work_twd_end);
    $("#twe_start").val(data.work_twe_start);
    $("#twe_end").val(data.work_twe_end);
    t = data.work_twd_start.split('.');
    $("#size_begin").val(parseInt(t[0]) * 60 + parseInt(t[1]));
    t = data.work_twd_end.split('.');
    $("#tm_begin").text(data.work_twd_start.replace('.', ':'));
    $("#tm_end").text(data.work_twd_end.replace('.', ':'));
    $("#size_end").val(parseInt(t[0]) * 60 + parseInt(t[1]));
    t = data.work_twe_start.split('.');
    $("#size_begin2").val(parseInt(t[0]) * 60 + parseInt(t[1]));
    t = data.work_twe_end.split('.');
    $("#tm_begin2").text(data.work_twe_start.replace('.', ':'));
    $("#tm_end2").text(data.work_twe_end.replace('.', ':'));
    $("#size_end2").val(parseInt(t[0]) * 60 + parseInt(t[1]));
    $("#money").val(data.pay);
    $("#action_money_dt").val(data.date_pay);
    $("#description").val(urldecode(data.description));
    $("#requirements").val(urldecode(data.req));
    $("#responsibility").val(urldecode(data.resp));
    $("#age_from").val(data.age_from);
    $("#age_to").val(data.age_to);
    $("#l_age_from").text(data.age_from);
    $("#p_age_from").val(data.age_from);
    $("#l_age_to").text(data.age_to);
    $("#p_age_to").val(data.age_to);
    $("#app_" + data.appoint).attr('checked', true);
    $("#empl_type_" + data.empl_type).attr('checked', true);
    $("#narrow_req_" + data.narrow_req).attr('checked', true);
    SetCheckBoxList(data.jobs_lang, "jobs_lang");
    SetCheckBoxList(data.mech, "mech_ids");
/*
    var elm = DecodeToBite(data.mech);
    for (var i = 0; i < elm.length; i++) {
        $("#ch_mech_" + elm[i]).attr("checked", true);
    }
*/
    formValidate();
}

function parseForm(data) {
    $("#email").val(data.email);
    $("#phone").val(data.phone1 + "; " + data.phone2);
    vac.id_city = data.id_city;
    vac.ra_id = data.id_ra;
    $("#city").val(data.city);
}

function generateRa(data) {
    var html = [];
    html.push('<select id="ra" name="ra" class="dropdown" >');
    for (var i = 0; i < data.length; i++) {
        html.push('<option value="' + data[i].id_ra + '">' + data[i].name + '</option>');
    }
    html.push('</select>');
    $("#ra_list").html(html.join(''));
    console.log("vac.ra_id=" + vac.ra_id);
    $("#ra_list [value='" + vac.ra_id + "']").attr("selected", "selected");
    //$("#ra_list").val(vac.ra_id);
}

function getRaList() {
    if (!!uid) {
        link = site_url + "?cmd=GET_RA_LIST&value=&uid=" + uid + "&callback=?";
        jsonp(link, function (data) {
            generateRa(data);
        });
    }
}


function Save() {
    // ..... Validate
    formValidate();
    if (objForm.total_err == 0) {
        var ra_id = getValueObjForm('ra');
        var iswork_room = 0;
        var iswork_street = 0;
        var t = getValueObjForm('work');
        if (t == 1 || t == 3) iswork_room = 1;
        if (t == 2 || t == 3) iswork_street = 1;

        var val = objForm.param + "ra_id:" + ra_id + ",iswork_room:" + iswork_room + ",iswork_street:" + iswork_street;
        console.log(val);

        if (vid == undefined) {
            // Insert
            var xen = encript(val, token);
            link = site_url + "?cmd=SAVE_VACATION&mode=3&uid=" + uid + "&value=" + xen + "&callback=?";
        } else {
            // Update
            var xen = encript(val, token);
            link = site_url + "?cmd=UPDATE_VACATION&mode=3&id=" + vid + "&uid=" + uid + "&value=" + xen + "&callback=?";
        }


        jsonp(link, function (data) {
            if (data.value > 0) {
                location.href = "/site/VacationList/";
            } else {
                alert("Некоторые поля введены некорректно или ошибка связи!");
            }
        });

    } else {
        showPopUp('Валидация формы','Заполнены не все поля!');
    }

}

function Clear() {
    var mess = '<br/><br/><br/><br/><br/><br/><div><h3>Поздравляем, вакансия опубликована</h3>';
    $("#wrapper").html(mess);
    $("#btn_save_form").hide();
}


function isNumber(cCode) {
    return /[0-9\.]/.test(String.fromCharCode(cCode))
}


$(function () {
    $("#city").autocomplete({
        source:function (request, response) {

            link = site_url + "?cmd=GET_CITY_LIST&mode=1&filter=" + request.term + "&callback=?";
            jsonp(link, function (data) {
                //alert(data.message+", ip="+ip);
                response($.map(data, function (item) {
                    return {
                        label:item.name,
                        value:item.name,
                        id:item.id
                    }
                }));
            });
        },
        minLength:1,
        select:function (event, ui) {
            //$("#lcountry").text('#'+ui.item.id);
            formValidate();
        }
    });
});


function ProtSymb(s) {
    if (s == undefined) return s;
    s = s.replace(/,/g, "\\u002c");
    s = s.replace(/"/g, "\\u0022");
    s = s.replace(/&/g, "\\u0026");
    s = s.replace(/:/g, "\\u003a");
    return s;
}

function sizeTime(m) {
    var size = 0;
    if (m == 0)
        size = $("#size_begin").val();
    else
        size = $("#size_end").val();

    var hh = parseInt(size / 60);
    var mm = size - hh * 60;
    if (hh < 10) hh = '0' + hh;
    mm = parseInt(mm / 15) * 15;
    if (mm < 10) mm = '0' + mm;
    if (hh == 24) hh = 0;

    if (m == 0) {
        $("#tm_begin").text(hh + ':' + mm);
        if (parseInt(size) > parseInt($("#size_end").val())) {
            $("#size_end").val(size);
            $("#tm_end").text(hh + ':' + mm);
        }
        $("#twd_start").val(hh + '.' + mm);

    }
    else {
        var s = hh + ':' + mm;
        if (s < $("#tm_begin").text()) {
            s = $("#tm_begin").text();
            $("#size_end").val($("#size_begin").val());
        }
        $("#tm_end").text(s);
        $("#twd_end").val(s.replace(':', '.'));
    }

}


function sizeTime2(m) {
    var size = 0;
    if (m == 0)
        size = $("#size_begin2").val();
    else
        size = $("#size_end2").val();

    var hh = parseInt(size / 60);
    var mm = size - hh * 60;
    if (hh < 10) hh = '0' + hh;
    mm = parseInt(mm / 15) * 15;
    if (mm < 10) mm = '0' + mm;
    if (hh == 24) hh = 0;

    if (m == 0) {
        $("#tm_begin2").text(hh + ':' + mm);
        if (parseInt(size) > parseInt($("#size_end2").val())) {
            $("#size_end2").val(size);
            $("#tm_end2").text(hh + ':' + mm);
        }
        $("#twe_start").val(hh + '.' + mm);
    }
    else {
        var s = hh + ':' + mm;
        if (s < $("#tm_begin2").text()) {
            s = $("#tm_begin2").text();
            $("#size_end2").val($("#size_begin2").val());
        }
        $("#tm_end2").text(s);
        $("#twe_end").val(s.replace(':', '.'));
    }

}

function changeAge(m) {
    var size = 0;
    if (m == 0) {
        size = $("#p_age_from").val();
        $("#l_age_from").text(size);
        $("#age_from").val(size);
        if (parseInt(size) > parseInt($("#p_age_to").val())) {
            $("#p_age_to").val(size);
            $("#l_age_to").text(size);
        }
    }
    else {
        size = $("#p_age_to").val();
        $("#l_age_to").text(size);
        $("#age_to").val(size);
        if (parseInt(size) < parseInt($("#p_age_from").val())) {
            $("#p_age_from").val(size);
            $("#l_age_from").text(size);
        }
    }
}


$(document).ready(function () {
    //$("#vacation_name").bind("change", function() { checkVacationName(); });
    //$(".err").hide();

    $('#empl_type_0').attr('checked', true);
    $('#narrow_req_00').attr('checked', true);
    $('#jobs_lang_--').attr('checked', true);
    formValidate();

    $('.nm').keypress(function (e) {
        if ($.browser.msie)
            return isNumber(e.keyCode)
        else
            return isNumber(e.charCode)
    });

    //google.maps.event.addDomListener(window, "load", initMap);

    $("#action_money_dt").datepicker();
    $("#d").daterange({
        dateFormat:'dd.mm.yy',
        extend:$.datepicker.regional["ru"],
        onClose:function (dateRangeText) {
            //$("#d").after("<p>" + dateRangeText + "</p>");
            if (dateRangeText.length > 10) {
                var adt = dateRangeText.split('-');
                $("#action_begin").val(adt[0].trim());
                $("#action_end").val(adt[1].trim());
            }
        }
    });
    $.datepicker.setDefaults($.datepicker.regional['ru']);

});

<?php
if ($vid != '') {
    echo "vid=$vid;";
}
?>

</script>