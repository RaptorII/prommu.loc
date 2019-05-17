<?
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/template.css');
  $gcs->registerCssFile($bUrl . '/css/vacancy/list.css');
  $gcs->registerScriptFile($bUrl . '/js/vacancy/list.js', CClientScript::POS_HEAD);
  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
      $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
    }");
  $GetVac = Yii::app()->getRequest()->getParam('Vacancy');
?>
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
        $this->widget('zii.widgets.grid.CGridView', array(
          'id'=>'vacancy_table',
          'dataProvider'=>$model->searchvac(),
          'itemsCssClass' => 'table table-bordered table-hover dataTable custom-table',
          'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid'),
          'filter'=>$model,  
          'afterAjaxUpdate' => 'reinstallDatePicker',
          'enablePagination' => true,
          'columns'=>array(
                    array(
                      'class' => 'CCheckBoxColumn',
                      'selectableRows' => 2,
                      'checkBoxHtmlOptions' => array('class' => 'checkclass'),
                      'value' => '$data->id_user',
                      'htmlOptions' => ['class'=>'column_checkbox']
                    ),
                    array(
                      'header' => 'ID',
                      'name' => 'id',
                      'value' => '$data->id',
                      'type' => 'html',
                      'htmlOptions' => ['class'=>'column_id']
                    ),
                    array(
                      'header' => 'Компания',
                      'name' => 'company_search',
                      'type' => 'html',
                      'value' => 'getLink("/admin/EmplEdit/" . $data->id_user, $data->employer->name)',
                      'htmlOptions' => ['class'=>'column_company']
                    ),
                    array(
                      'header' => 'ID компании',
                      'name' => 'id_user',
                      'value' => '$data->id_user',
                      'type' => 'html',
                      'htmlOptions' => ['class'=>'column_id_user']
                    ),
                    array(
                      'header' => 'Название',
                      'name' => 'title',
                      'value' => 'getLink("/admin/VacancyEdit/" . $data->id, $data->title)',
                      'type' => 'html',
                      'htmlOptions' => ['class'=>'column_title']
                    ),
                    array(
                      'header' => 'Город',
                      'type' => 'raw',
                      'filter' => '',
                      'value' => 'getVacancyCities($data->id)',
                      'htmlOptions' => ['class'=>'column_city']
                    ),
                    array(
                      'header' => 'Должность',
                      'type' => 'raw',
                      'filter' => '',
                      'value' => 'getVacancyPosts($data->id)',
                      'htmlOptions' => ['class'=>'column_post']
                    ),
                    array(
                      'header' => 'Дата создания',
                      'filter' => getDatePickers($this, 'b_crdate', 'e_crdate'),
                      'name' => 'crdate',
                      'type' => 'html',
                      'value' => 'Share::getPrettyDate($data->crdate,false,true)',
                      'htmlOptions' => ['class'=>'column_cdate']
                    ),
                    array(
                      'header' => 'Дата изменения',
                      'filter' => getDatePickers($this, 'b_mdate', 'e_mdate'),
                      'name' => 'mdate',
                      'type' => 'raw',
                      'value' => 'Share::getPrettyDate($data->mdate,false,true)',
                      'htmlOptions' => ['class'=>'column_mdate']
                    ),
                    array(
                      'header' => 'Дата завершения',
                      'filter' => getDatePickers($this, 'b_remdate', 'e_remdate'),
                      'name' => 'remdate',
                      'type' => 'raw',
                      'value' => 'Share::getDate(strtotime($data->remdate),"d.m.y")',
                      'htmlOptions' => ['class'=>'column_rdate']
                    ),
                    array(
                      'header' => 'Статус',
                      'filter' => CHtml::dropDownList(
                                          'Vacancy[status]', 
                                          isset($GetVac['status']) ? $GetVac['status'] : 1, 
                                          ['0'=>'Неактивные', '1'=>'Активные']
                                      ),
                      'name' => 'status',
                      'value' => 'getSelect($data, "status", [0=>"не активна",1=>"активна"])',
                      'type' => 'raw',
                      'htmlOptions' => ['class'=>'column_status']
                    ),
                    array(
                      'header' => 'Модерация',
                      'filter' => CHtml::dropDownList(
                                          'Vacancy[ismoder]', 
                                          isset($GetVac['ismoder']) ? $GetVac['ismoder'] : '', 
                                          [''=>'Все','0'=>'В работе','100'=>'Просмотреные']
                                      ),
                      'name' => 'ismoder',
                      'value' => 'getSelect($data, "ismoder", [0=>"в работе",100=>"просмотрена"])',
                      'type' => 'raw',
                      'htmlOptions' => ['class'=>'column_ismoder']
                    ),
                    array(
                      'header' => 'Архив',
                      'filter' => CHtml::dropDownList(
                                          'Vacancy[in_archive]', 
                                          isset($GetVac['in_archive']) ? $GetVac['in_archive'] : '0', 
                                          ['0'=>'Активные','1'=>'Архив']
                                      ),
                      'value' => 'getSelect($data, "in_archive", [1=>"архив",0=>"активна"])',
                      'type' => 'raw',
                      'htmlOptions' => ['class'=>'column_archive']
                    ),
                    array(
                      'value' => 'getLink("/admin/VacancyEdit/" . $data->id, "Редактировать", true)',
                      'type' => 'raw',
                      'htmlOptions' => ['class'=>'column_edit']
                    ),
                    array(
                      'header' => '',
                      'type' => 'raw',
                      'filter' => '',
                      'value' => 'getResponses($data->id)',
                      'htmlOptions' => ['class'=>'column_response']
                    )
        )));
        //
        //
        //
        // ссылка
        function getLink($link, $name, $is_btn=false)
        {
            if(!$name)
                return ' - ';

            return  "<a href='$link' " . ($is_btn ? 'class="btn btn-default"' : '') . ">$name</a> ";
        }
        // города
        function getVacancyCities($id)
        {
            $query = Yii::app()->db->createCommand()
                        ->select('c.name')
                        ->from('empl_city ec')
                        ->join('city c','ec.id_city=c.id_city')
                        ->where('ec.id_vac=:id',[':id'=>$id])
                        ->queryColumn();

            if(!count($query))
                return ' - ';
            else
                return implode(',<br>', $query);
        }
        // должности
        function getVacancyPosts($id)
        {
            $query = Yii::app()->db->createCommand()
                        ->select('uad.name')
                        ->from('empl_attribs ea')
                        ->join('user_attr_dict uad','uad.id=ea.id_attr')
                        ->where(
                            'ea.id_vac=:id and uad.id_par=110',
                            [':id'=>$id]
                        )
                        ->queryColumn();

            if(!count($query))
                return ' - ';
            else
                return implode(',<br>', $query);
        }
        // datepickers
        function getDatePickers($obj,$n1,$n2)
        {
          $arr = ['class' => 'grid_date','autocomplete'=>'off'];
          $html = '<div class="date_range">'
                . '<div class="input_' . $n1 . '"></div>'
                . '<div class="separator">-</div>'
                . '<div class="input_' . $n2 . '"></div>'
                . $obj->widget('zii.widgets.jui.CJuiDatePicker',
                    [
                      'name'=>$n1,
                      'value'=>Yii::app()->getRequest()->getParam($n1),
                      'options'=>['changeMonth'=>true],
                      'htmlOptions'=>$arr
                    ],
                    true)
                . $obj->widget('zii.widgets.jui.CJuiDatePicker',
                    [
                      'name'=>$n2,
                      'value'=>Yii::app()->getRequest()->getParam($n2),
                      'options'=>['changeMonth'=>true],
                      'htmlOptions'=>$arr
                    ],
                    true)
                . '</div>';

          return $html;     
        }
        // responses
        function getResponses($id)
        {
          $arResponses = (new ResponsesEmpl())->getVacResponsesCnt($id);
          $views = (new Termostat())->getTermostatCount($id);
          return '<div title="Просмотров"><span class="glyphicon glyphicon-eye-open">' . $views . '</span></div>'
                  . '<div title="Откликов"><span class="glyphicon glyphicon-user">' . $arResponses['cnt'] . '</span></div>'
                  . '<div title="Утвержденных"><span class="glyphicon glyphicon-thumbs-up">' . $arResponses['approved'] . '</span></div>'; 
        }
        // get select
        function getSelect($data, $name, $arr)
        {
          $randId = rand(0,999);
          $w = key($arr);
          $html = '<div class="dropdown select_update">'
            . '<button class="btn btn-default dropdown-toggle" type="button" id="'
              . $randId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">'
              . '<span class="label label-' . ($data->$name==$w?'warning':'success') . '">' . $arr[$data->$name] . '</span> '
              . '<span class="caret"></span>'
            . '</button>'
            . '<ul class="dropdown-menu" aria-labelledby="' . $randId . '">';

          foreach ($arr as $k => $v)
          {
            $html .= '<li class="label label-' . ($k==$w?'warning':'success') 
              . '" data-id="' . $data->id . '" data-param="' 
              . $name . '" data-value="' . $k . '">' . $v . '</li>';
          }

          $html .= '</ul></div>';

          return $html;
        }
        ?>
    </div>
</div> 
<?
// XLS popup
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
                        'htmlOptions'=>[
                            'id'=>'export_beg_date',
                            'class'=>'form-control',
                            'autocomplete'=>'off'
                        ]
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
                        'htmlOptions'=>[
                            'id'=>'export_end_date',
                            'class'=>'form-control',
                            'autocomplete'=>'off'
                        ]
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