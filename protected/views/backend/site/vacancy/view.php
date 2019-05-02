<?
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/template.css');
?>
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
        border: #ecf0f5;
        width:80px;
    }
    #export_form input[type="radio"]{
        width: 20px;
        vertical-align: middle;
        margin: 0;
    }
    #export_form{
        width: 100%;
        max-width: 400px;
        position: absolute;
        top: 250;
        left: -200px;
        margin-left: 50%;
        padding: 15px;
        background: #ffffff;
        border-radius: 5px;
        z-index: 1100;
        display: none;
    }
    .bg_veil{
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: rgba(0,0,0,.6);
        z-index: 1099;
        display: none;
    }
    .export_form-radio{
        display:flex;
    }
    .export_form-close{
        position: absolute;
        color: #ffffff;
        background-color: #222d32;
        width: 30px;
        text-align: center;
        line-height: 32px;
        border-radius: 50%;
        cursor: pointer;
        right: -20px;
        top: -20px;
        font-size: 20px;
        height: 30px;
    }
</style>
<?
//
?>
<div class="row">
    <div class="col-xs-12">
        <h3>Администрирование вакансий</h3>
        <br>
        <div class="pull-right">
            <?/*<a href="javascript:void(0)" class="btn btn-success" onclick="create()">Создание вакансии</a>*/?>
            <a href="javascript:void(0)" class="btn btn-success export_btn">Экспорт в Excell</a>
        </div>
        <div class="clearfix"></div>
        <br>
        <?php
        echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
        echo '<input type="hidden" id="curr_status" name="curr_status">';
        $this->widget('zii.widgets.grid.CGridView', array(
          'id'=>'dvgrid',
          'dataProvider'=>$model->searchvac(),
          'itemsCssClass' => 'table table-bordered table-hover dataTable',
          'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style' =>    'padding: 10px;  overflow: scroll;'),
          'filter'=>$model,  
          'enablePagination' => true,
          'columns'=>array(
                    array(
                        'class'=>'CCheckBoxColumn',
                        'selectableRows' => 2,
                        'checkBoxHtmlOptions' => array('class' => 'checkclass'),
                        'value' => '$data->id_user',
                    ),
                    array(
                        'header'=>'Вакансия',
                        'name' => 'id',
                        'value' => '$data->id',
                        'type' => 'html',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
                    ),
                    array(
                        'header'=>'Работодатель',
                        'name' => 'id_empl',
                        'type'=>'raw',
                        'value'=>'CHtml::tag("span",array(),GetEmployerName($data->id_empl) . " (" . $data->id_empl . ")")',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;')
                    ),
                    array(
                        'header'=>'Название',
                        'name' => 'title',
                        'value' => 'ShowVacancy($data->id, $data->title)',
                        'type' => 'html',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),

                    ),
                    array(
                        'header'=>'Город',
                        'name' => 'city',
                        'type'=>'raw',
                        'value'=>'CHtml::tag("span",array(),implode(", ", GetVacCities($data->id)))',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;')
                    ),
                    array(
                        'header'=>'Создана',
                        'name' => 'crdate',
                        'value' => '$data->crdate',
                        'type' => 'html',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
                    ),
                    array(
                        'header'=>'Дата изменения',
                        'name' => 'mdate',
                        'value' => '$data->mdate',
                        'type' => 'html',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
                    ),
                    array(
                        'header'=>'Деактив',
                        'name' => 'remdate',
                        'value' => '$data->remdate',
                        'type' => 'html',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
                    ),
                    array(
                        'header'=>'Статус',
                        'name' => 'status',
                        'value' => 'ShowStatus($data->id,$data->status)',
                        'type' => 'raw',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),

                    ),
                    array(
                        'header'=>'Модерация',
                        'name' => 'ismoder',
                        'value' => 'ShowStatusModer($data->id,$data->ismoder)',
                        'type' => 'raw',
                        'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),

                    ),
                    array(
                    'name' => 'Редактор',
                    'value' => 'ShowEdit($data->id)',
                    'type' => 'raw',
                    'filter' => '',
                    'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting', 'background-color'=> 'antiquewhite')
                  ),
                     array(
                    'name' => 'Удалить',
                    'value' => 'ShowDelete($data->id, $data->id_user)',
                    'type' => 'raw',
                    'filter' => '',
                    'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting', 'background'=> '#ef0018')
                ),

        )));
        echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));

        function ShowStatus($id, $stat)
        {
        $status = ['не активна','активна'];
            $st_ico = ["label-warning", "label-success"];
            $html = 
            '<div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$stat] . '">
            <span class="label ' . $st_ico[$stat] . '">'.$status[$stat] .'</span>
            <span class="caret"></span>
            </button>';
            $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
            for ($i = 0; $i < 2; $i++) {
                $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
            }
            $html .= '</ul></div>';
            return $html;
        }

        function ShowVacancy($id, $title){
           return  "<a href='/admin/site/VacancyEdit/$id'>$title</a> ";
        }
        function ShowStatusModer($id, $stat)
        {
            if($stat == 100){
                $stat = 1;
            }
            elseif($stat == 200){
                $stat = 0;
            }
            else $stat = 0;
            $status = ['не просмотрена','просмотрена'];
            $st_ico = ["label-warning", "label-success"];
            $html = 
            '<div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$stat] . '">
            <span class="label ' . $st_ico[$stat] . '">'.$status[$stat].'</span>
            <span class="caret"></span>
            </button>';
            $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
            for ($i = 0; $i < 2; $i++) {
                if($i == 0){
                $stat = 200;
            }
            elseif($i == 1){
                $stat = 100;
            }
                $html .= '<li ><a href = "#" onclick = "doStatus(' . $id . ', ' . $stat . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
            }
            $html .= '</ul></div>';
            return $html;
        }

        function ShowEdit($id) {
            return  '<button type="button" class="btn btn-default"><a href="/admin/site/VacancyEdit/' . $id . '">Редактировать</a></button> ';
        }

        function ShowDelete($idvac, $iduser) {
            return  '<button type="button" onclick = "doDelete(' . $idvac . ',' . $iduser . ')" class="btn btn-default">Удалить</button> ';

        }
        function GetEmployerName($id){
            $emp = Yii::app()->db->createCommand()
                ->select('name')
                ->from('employer')
                ->where('id=:id', array(':id'=>$id))
                ->queryRow();
            return  html_entity_decode($emp['name']);
        }
        function GetVacCities($id){
            $arCities = Yii::app()->db->createCommand()
                ->select('c.name')
                ->from('empl_city ec')
                ->where('id_vac=:id', array(':id'=>$id))
                ->join('city c','ec.id_city=c.id_city')
                ->limit(1000)
                ->queryAll();

            $arRes = array();
            if(sizeof($arCities)>0)
                foreach($arCities as $k => $val)
                    array_push($arRes, $val['name']);

            return $arRes;
        }
        echo CHtml::endForm();
        ?>
    </div>
</div> 
<!-- <a href="#" onclick="export_send()">Экспорт в Excel</a> -->
<script type="text/javascript">
    function export_send() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/ExportVacancy";
        document.forms['form'].submit();
    }

    function doStatusModer(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/VacancyChangeModer/" + id);
        $("#btn_submit").click();
    }

    function doStatus(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/VacancyModer/" + id);
        $("#btn_submit").click();
    }

    function doDelete(idvac,iduser) {
        $("#form").attr("action", "/admin/site/VacancyDelete/?id=" + idvac + '&id_user=' + iduser);
        $("#btn_submit").click();
    }

    function create() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/VacancyCreate/" ;
        document.forms['form'].submit();
    }
    //
    //
    //
    $(function(){
        // datepicker
        $('#export_beg_date').on("change", function(){ changeDate(1) });
        $('#export_end_date').on("change", function(){ changeDate(2) });

        $(document).on(
            'click',
            '#export_beg_date,#export_end_date', 
            function(){ 
                if (!$(this).hasClass('hasDatepicker')) { 
                    $(this).datepicker(); $(this).datepicker('show'); 
                }
        }); 

        function changeDate(cnt)
        {
            var date1 = $('#export_beg_date').datepicker('getDate'),
                date2 = $('#export_end_date').datepicker('getDate');

            if(cnt==1)
            {
                $('#export_beg_date').datepicker('setDate',date1);
                $('#export_end_date').datepicker("option","minDate",date1);   
            }
            else
            {
                $('#export_end_date').datepicker('setDate',date2);
                $('#export_beg_date').datepicker("option","maxDate",date2);                
            }
        }
        // popup
        $('.export_btn').click(function(){
            $('#export_form').fadeIn();
            $('.bg_veil').fadeIn();
        });
        $('.bg_veil,.export_form-close,.export_start_btn').click(function(){
            $('#export_form').fadeOut();
            $('.bg_veil').fadeOut();
        });
    });
</script>
<?
//
?>
<form method="POST" action="/admin/vacancy?export_xls=Y" id="export_form">
    <label class="d-label">
        <span>Даты создания</span>
        <input type="radio" name="export_date" value="create" checked>
    </label>
    <label class="d-label">
        <span>Даты начала работ</span>
        <input type="radio" name="export_date" value="begin">
    </label>
    <label class="d-label">
        <span>Даты завершения</span>
        <input type="radio" name="export_date" value="end">
    </label>
    <div class="row">
        <div class="col-xs-6">
            <label class="d-label">
                <span>Период с</span>
                <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'name'=>'export_beg_date',
                        'options'=>['changeMonth'=>true],
                        'htmlOptions'=>['id'=>'export_beg_date','class'=>'form-control']
                    ));
                ?>
            </label>  
        </div>
        <div class="col-xs-6">
            <label class="d-label">
                <span>по</span>
                <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'name'=>'export_end_date',
                        'options'=>['changeMonth'=>true],
                        'htmlOptions'=>['id'=>'export_end_date','class'=>'form-control']
                    ));
                ?>
            </label>  
        </div>
        <div class="hidden-xs col-sm-6"></div>
    </div>
    <br>
    <div class="export_form-radio">
        <label class="d-label">
            <span>все</span>
            <input type="radio" name="export_status" value="all" checked>
        </label>
        <label class="d-label">
            <span>активные</span>
            <input type="radio" name="export_status" value="active">
        </label>                
        <label class="d-label">
            <span>не активные</span>
            <input type="radio" name="export_status" value="no_active">
        </label>
    </div>
    <br>
    <div class="text-center">
        <button type="submit" class="btn btn-success export_start_btn">Выгрузить</button>
    </div>
    <div class="export_form-close">&#10006</div>
</form>
<div class="bg_veil"></div>