<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/jsform.js', CClientScript::POS_HEAD);
$docroot = $_SERVER['DOCUMENT_ROOT'];
echo CHtml::form('/site/showResume', 'POST', array("id" => "form"));
?>
<h3>Поиск вакансий</h3>
<div class="row">
<div class="span4">
    <div style="display:none;">
        <?php
        echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model' => $model, 'attribute' => 'access_time', 'language' => 'ru', 'id' => 'birthday'), true);
        ?>
    </div>
    <!-- Search filter -->
    <h3>Фильтр поиска:</h3>

    <div class="accordion" id="accordion2">

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Город</a>
            </div>
            <div id="collapseOne" class="accordion-body collapse in">
                <div class="accordion-inner">
                    <input type="text" name="city" id="city" placeholder="пример: Киев"/>
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse2" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Рекл. агентство</a>
            </div>
            <div id="collapse2" class="accordion-body collapse">
                <div class="accordion-inner">
                    <input type="text" name="fra" id="fra"/>
                </div>
            </div>
        </div>


        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Возраст</a>
            </div>
            <div id="collapse3" class="accordion-body collapse">
                <div class="accordion-inner">
                    от <input type="text" name="height" id="age_from" placeholder="16" class="number"
                              style="width:40px;"/>
                    до <input type="text" name="height" id="age_to" placeholder="35" class="number"
                              style="width:40px;"/>
                </div>
            </div>
        </div>


        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse4" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Почасовая оплата</a>
            </div>
            <div id="collapse4" class="accordion-body collapse">
                <div class="accordion-inner">
                    от <input type="text" name="fpay_from" id="fpay_from" placeholder="5" class="number"
                              style="width:40px;"/>
                    до <input type="text" name="fpay_to" id="fpay_to" placeholder="200" class="number"
                              style="width:40px;"/>
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse5" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Механика Акции</a>
            </div>
            <div id="collapse5" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?php
                    echo FormHelper::ListCheckBoxDB('fmech', 'Механика Акции *', 'mech');
                    ?>

<!--
                    <label class="checkbox">Раздача листовок<input type="checkbox" value="1" name="fmech" class="mech"/></label>
                    <label class="checkbox">Сэмплинг<input type="checkbox" value="2" name="fmech" class="mech"/></label>
                    <label class="checkbox">Дегустация<input type="checkbox" value="3" name="fmech"
                                                             class="mech"/></label>
                    <label class="checkbox">Выставки/Презентации<input type="checkbox" value="4" name="fmech"
                                                                       class="mech"/></label>
                    <label class="checkbox">HoReCa<input type="checkbox" value="5" name="fmech" class="mech"/></label>
                    <label class="checkbox">ПЗП<input type="checkbox" value="6" name="fmech" class="mech"/></label>
                    <label class="checkbox">Консультант<input type="checkbox" value="7" name="fmech"
                                                              class="mech"/></label>
-->
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse6" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Сроки акции</a>
            </div>
            <div id="collapse6" class="accordion-body collapse">
                <div class="accordion-inner">
                    от <input type="text" name="ftime1" id="ftime1" placeholder="1" class="number" style="width:40px;"/>
                    до <input type="text" name="ftime2" id="ftime2" placeholder="14" class="number"
                              style="width:40px;"/>
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse61" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Должность</a>
            </div>
            <div id="collapse61" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?php
                    $res = Yii::app()->db->createCommand()
                        ->select('key, val as name')
                        ->from('dictionary')
                        ->where("grp='APPT'")
                        ->queryAll();
                    foreach ($res as $r) {
                        echo '<label class="checkbox">' . $r['name'] . '<input type="checkbox" value="' . $r['key'] . '" name="app" /></label>';
                    }

                    ?>
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse62" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Тип занятости</a>
            </div>
            <div id="collapse62" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?php
                    foreach (Share::$empl_type as $key => $value) {
                        echo '<label class="radio">' . $value . '<input type="radio" value="' . $key . '" name="empl_type" id="empl_type_' . $key . '"/></label>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse63" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Узкие требования</a>
            </div>
            <div id="collapse63" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?php echo FormHelper::ListRadioDB('narrow_req', 'Узкие требования', 'narrow_req', 0); ?>
                </div>
            </div>
        </div>


        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse7" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Наличие мед. книжки</a>
            </div>
            <div id="collapse7" class="accordion-body collapse">
                <div class="accordion-inner">
                    <label class="radio">Есть<input type="radio" name="fismed" value="1"></label>
                    <label class="radio">Не важно<input type="radio" name="fismed" checked value="0"></label>
                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading silver">
                <a href="#collapse8" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2"><i
                    class="icon-chevron-down"></i> Языки</a>
            </div>
            <div id="collapse8" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?php echo FormHelper::ListRadioDB('jobs_lang', 'Языки', 'jobs_lang', 0); ?>
                </div>
            </div>
        </div>


    </div>

    <a href="#" class="btn btn-inverse" onclick="ClearFilter()">Очистить фильтр</a>
    <a href="#" class="btn btn-success" onclick="Search()">Искать</a>
    <!---End search filter----->

</div>

<!-- Vacancy lists block -->
<div class="span8"><!--pan2-->
    <input type="hidden" name="vid" id="vid"/>
    <!-- Search resume block -->
    <h3 id="title_res">Результаты поиска</h3>

    <div id="list_vac"><br></div>

    <div id="pan_resume" style="display:none; ">
        <?php include_once($docroot . "/protected/views/frontend/site/vacation/show2.php"); ?>
    </div>

</div>
<!--span8 end-->

<script type="text/javascript">
<?php Share::PrintMechJson(); ?>
var objForm =
    (
        {"total_err":0, "param":""},
            [
                {
                    "name":"lastname",
                    "value":"",
                    "tp":0,
                    "validate":1,
                    "errmess":"введите фамилию"
                }
            ]);
var city_id = 0;
var ra_id = 0;
function Search() {
    if (!!uid) {
        if ($("#city").val() == '') city_id = 0;
        if ($("#fra").val() == '') ra_id = 0;

        console.log(city_id);
        console.log(ra_id);

        var age_from = $("#age_from").val();
        var age_to = $("#age_to").val();
        var pay_from = $("#fpay_from").val();
        var pay_to = $("#fpay_to").val();
        var ismale = $("input[name=ismale]:checked").val();
        var iswork_promo = $("input[name=iswork_promo]:checked").val();
        var ismed = $("input[name=fismed]:checked").val();
        var empl_type = $("input[name=empl_type]:checked").val();
        var narrow_req = $("input[name=narrow_req]:checked").val();
        var jobs_lang = $("input[name=jobs_lang]:checked").val();
        var fmech = 0;
        var app = '';
        $("input[name=app]:checked").each(function (i, selected) {
            app += ' ' + $(selected).val();
        });

        //--size--
        var sel = '';
        $("input[name=fmech]:checked").each(function (i, selected) {
            sel += '-' + $(selected).val();
        });
        if (sel != '') {
            fmech = EncodeBiteToIntN(sel.substring(1));
        }
        console.log('mech:' + fmech);

        var val = 'city_id:' + city_id +
            ',ra_id:' + ra_id +
            ',age_from:' + age_from +
            ',age_to:' + age_to +
            ',pay_from:' + pay_from +
            ',pay_to:' + pay_to +
            ',ismed:0' +
            ',mech:' + fmech +
            ',ftime1:' + $("#ftime1").val() +
            ',ftime2:' + $("#ftime2").val() +
            ',appoint:' + app +
            ',empl_type:' + empl_type +
            ',narrow_req:' + narrow_req+
            ',jobs_lang:' + jobs_lang;

        var xen = encript(val, token);
        link = site_url + "?cmd=SEARCH_VAC&mode=3&value=" + xen + "&uid=" + uid + "&callback=?";
        jsonp(link, function (data) {
            parseView(data);
        });
    }
}

function ClearFilter() {
    $("#city").val('');
    $("#fra").val('');
    $("#age_from").val('');
    $("#age_to").val('');
    $("#fpay_from").val('');
    $("#fpay_to").val('');
    $("input[name=fmech]:checked").removeAttr('checked');
    $("#ftime1").val('');
    $("#ftime2").val('');
    $('#empl_type_0').attr('checked', true);
    $('#narrow_req_00').attr('checked', true);
    $('#jobs_lang_--').attr('checked', true);

    showPopUp("Сообщение", "Фильтр поиска очищен");
}

function parseView(data) {
    var html = [];

    for (var i = 0; i < data.length; i++) {
        html.push('<div class="block_vac" id="b', data[i].id_jobs, '">');
        html.push('<h4>', urldecode(data[i].name_act), '</h4>');
        html.push('<table border="0">');

        html.push('<tr><td>дата публикации</td>');
        html.push('<td>', data[i].date_public, '</td></tr>');

        html.push('<tr><td>город</td>');
        html.push('<td>', data[i].city, '</td></tr>');

        html.push('<tr><td>механика</td>');
        html.push('<td>', ShowMech(data[i].mech), '</td></tr>');

        html.push('<tr><td>оплата в час</td>');
        html.push('<td>', data[i].pay, '</td></tr>');

        html.push('<tr><td>сроки акции</td>');
        html.push('<td>', data[i].date_begin, ' - ', data[i].date_end, '</td></tr>');

        html.push('</table>');
        html.push('<div style="display:inline">');
        html.push('<a href="javascript:viewVac(', data[i].id_jobs, ')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
        html.push('<a href="javascript:showVac(', data[i].id_jobs, ')" class="btn btn-primary btn-mini">анкета <i class="icon-chevron-down"></i></a>');
        html.push('</div><div class="clear"></div>');
        html.push('<div id="p_', data[i].id_jobs, '"></div>');
        html.push('</div>');
    }

    $("#list_vac").html(html.join(''));

}

function showVac(id) {
    $("#title_res").hide();
    vid = id;
    getForm(id);
    //getForm_Resume();
    $("#list_vac").css("display", "none");
    $("#pan_resume").show(500);

}

function viewVac(id) {
    $("#b" + id + " table").toggle("slow");
}


function AutoHeight(mode) {
    //return;
    //$(".pan1").css("height","auto");
    $("#pan_resume").css("height", "auto");

    var p1 = $(".pan1").height();
    var p2 = $(".pan2").height();
    var p3 = $("#pan_resume").height();


    if (mode == 0) {
        if (p2 > p1) {
            $(".pan1").height(p2);
            $("#list").height(p2 - 40);
        } else {
            $(".pan2").height(p1);
            $("#list").height(p1 - 40);
        }
    }
    else {
        //p2=p3-780;
        $("#pan2").height(p3);
        $(".pan1").height(p3 - 810);
    }
    console.log("autoH:" + $(".pan2").height() + " pan2=" + $(".pan2").height());
}

function showResume(id) {
    $("#title_res").hide();
    vid = id;
    getForm_Resume();
    $("#list").css("display", "none");
    $("#pan_resume").show(500);
    //AutoHeight(1);
}

function hideVac() {
    $("#title_res").show();
    $("#pan_resume").hide();
    $("#list_vac").show(500);
    //AutoHeight(0);
}

/*
function ShowMech(val) {
    var txt = []
    var arrM = DecodeToBiteN(parseInt(val));
    for (i = 0; i < arrM.length; i++) {
        txt.push(mechInfo[arrM[i]], '; ');
    }
    return(txt.join(''));
}
*/

$(function () {
    $('#empl_type_0').attr('checked', true);
    $('#narrow_req_00').attr('checked', true);
    $('#jobs_lang_--').attr('checked', true);
});


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
            city_id = ui.item.id;
            //$("#lcountry").text('#'+ui.item.id);
        }
    });
});


$(function () {
    $("#fra").autocomplete({
        source:function (request, response) {

            link = site_url + "?cmd=SEARCH_ALL_RA&mode=1&filter=" + request.term + "&callback=?";
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
            ra_id = ui.item.id;
            //$("#lcountry").text('#'+ui.item.id);
        }
    });
});

</script>

</div>
<?php
echo CHtml::submitButton('submit', array("id" => "btn_submit", 'style' => "visibility:hidden"));
echo CHtml::endForm();
?>                                                           