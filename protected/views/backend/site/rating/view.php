<?php
$grp=1;
if(isset($_GET['grp'])) {
    $grp = $_GET['grp'];
}
$grp_txt = array('', 'Работодатели', 'Промоутеры');

echo '<h3><i>Редактирование Рейтинга : '.$grp_txt[$grp].'</i></h3>';
?>
<a href="/admin/site/rating?grp=1">Работодатели</a>&nbsp;&nbsp;<a href="/admin/site/rating?grp=2">Промоутеры</a>
<div class="modal hide fade" id="Modal" tabindex="-1" role="dialog">
    <div class="modal-header">
        <h3 id="edit_title">Редактирование</h3>
    </div>
    <div class="modal-body">
        <p>
            <label for="ename">Название</label>

        <div class="controls input-append">
            <input type="text" name="ename" id="ename" class="admform span6"><span class="add-on"><i
                    class="icon-tag"></i></span>

            <p>
                <label>Баллы</label>

            <div class="btn-group" data-toggle="buttons">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    echo '<label class="btn btn-default">';
                    echo '<input name="radioGroup" id="pt' . $i . '" value="' . $i . '" type="radio">' . $i;
                    echo '</label>';
                }
                ?>
            </div>
        </p>
    </div>
    </p>
</div>
<div class="modal-footer">
    <button class="btn btn-warning" data-dismiss="modal">Закрыть</button>
    <button class="btn btn-success" data-dismiss="modal" onclick='EditForm()'>Сохранить</button>
</div>
<input type="hidden" id="pkid"/>
</div>

<div class="span11">
    <?php
    $criteria = new CDbCriteria();
    $criteria->compare('grp',$grp);
    $criteria->condition = "grp = ".$grp;
    $pagination = array('pageSize' => 20,);

    $dataProvider = new CActiveDataProvider('PointRating', array('criteria' => $criteria, 'pagination' => $pagination,));

    //print_r($dataProvider); die;
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'my-grid',
        'dataProvider' => $dataProvider, //$model->search(),
        //'filter' => $model,
        'htmlOptions' => array('class' => 'table table-hover'),
        'columns' => array(
            array(
                'name' => 'id',
                'value' => '$data->id',
                'type' => 'html',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
                'filter' => '',
            ),

            array(
                'name' => 'value',
                'type' => 'raw',
                'value' => 'ShowPoint($data->value, $data->id)',
                //'value' => 'CHtml::link($data->value, array("ctrl/act"))',
                'htmlOptions' => array('style' => 'width: 70px;'),
                'filter' => '',
            ),

            array(
                'name' => 'descr',
                'value' => 'ShowName($data->descr, $data->id, $data->value)',
                //'value'=> 'CHtml::button($data->name, array("onclick"=>"open_pop_up(\"#pop-up\");edt(".$data->id_city.")", "id"=>"btn_".$data->id_city, "style"=>"width:100%"))',
                'type' => 'raw',
                'id' => '$data->id',
                'htmlOptions' => array('class' => 'm_element'),
            ),

        )));

    function ShowName($name, $id, $point)
    {
        return '<a href="#Modal" id="btn_' . $id . '" data-toggle="modal" onclick="javascript:edt(' . $id . ',' . $point . ')">' . $name . '</a>';
    }

    function ShowPoint($val, $id)
    {
        return '<span id="val_' . $id . '">'. $val .'</span>';
    }

    ?>
    <a href='#Modal' data-toggle="modal" class='btn' id='btn_add' style='width:50px' onclick="edt(0,0);">Создать</a>
</div>
<script type="text/javascript">
    var grp = <?echo $grp?>;
    function Cancel() {
        $("#editform").hide();
        $('#btn_add').show();
    }

    function ShowForm(id) {
        $('#btn_add').hide();
        $('#editform').show();
    }

    function SaveForm(id) {
        // validate
        var valid = true;
        if (isEmpty($('#name_ru').val())) {
            valid = false;
            $('#name_ru').attr({style: "background:#ff90c0;"});
        }
        else
            $('#name_ru').attr({style: "background:#fff;"});


        if (isEmpty($('#name_en').val())) {
            valid = false;
            $('#name_en').attr({style: "background:#ff90c0;"});
        }
        else
            $('#name_en').attr({style: "background:#fff;"});

        if (valid) {
            $("#btn_add").attr("disabled", "true");
            var address = "/admin/ajax/AddCity";
            $.ajax({
                type: 'GET',
                url: address + '?name_ru=' + $("#name_ru").val() + '&name_en=' + $("#name_en").val(),
                cache: false,
                dataType: 'text',
                success: function (data) {
                    $("#menu_edit").html(data);
                    document.location.href = '';
                },
                error: function (data) {
                    alert("Download error!");
                    $("#btn_add").attr("disabled", "false");
                }
            });

        }

    }


    function isEmpty(obj) {
        if (typeof obj == 'undefined' || obj === null || obj === '') return true;
        if (typeof obj == 'number' && isNaN(obj)) return true;
        if (obj instanceof Date && isNaN(Number(obj))) return true;
        return false;
    }


    function edt(id, point) {
        if (id == 0) {
            // Insert mode
            $("#ename").val("");
            $("#pkid").val(0);
            $("#edit_title").text("Добавить");
            $('input[type=radio][value=1]').attr('checked', 'checked')
        }
        else {
            // Edit mode
            var name = $("#btn_" + id).text();
            $("#ename").val(name);
            $("#pkid").val(id);
            $("#edit_title").text("Редактирование");
            $('input[type=radio]').attr('checked', false);
            $('input[type=radio][value=' + point + ']').attr('checked', 'checked')
        }
    }

    function EditForm() {
        var id = $("#pkid").val();
        var val = $('input[name="radioGroup"]:checked').val();
        var address = "/admin/ajax/EditRating";
        if (id == 0) address = "/admin/ajax/AddRating";

        $.ajax({
            type: 'GET',
            url: address + '?name=' + $("#ename").val() + '&id=' + id + '&val=' + val + '&grp=' + grp,
            cache: false,
            dataType: 'text',
            success: function (data) {
                if (id > 0) {
                    $("#btn_" + id).text($("#ename").val());
                    $("#btn_" + id).attr('onclick','edt('+id+','+val+')');
                    $("#val_" + id).text(val);
                    $("#ename").val('');
                    edt(id, val);
                }
                else {
                    location.href = "";
                }
            },
            error: function (data) {
                alert("Download error!");
            }
        });

    }

    $(document).ready(function () {

        jQuery.fn.center_pop_up = function () {
            this.css('position', 'absolute');
            this.css('top', ($(window).height() - this.height()) / 2 + $(window).scrollTop() + 'px');
            this.css('left', ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + 'px');
        }

    });

    function setShow(id, element) {
        var address = "/admin.php/ajax/SetActiveCity";

        $.ajax({
            type: 'GET',
            url: address + '?id=' + id,
            cache: false,
            dataType: 'text',
            success: function (data) {
                element.setAttribute('class', 'isblocked_' + data);
            },
            error: function (data) {
                alert("Download error!");
            }
        });

    }

</script>