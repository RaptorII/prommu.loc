<?php
  $model = new FeedbackTreatment;
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/template.css');
  $gcs->registerScriptFile($bUrl . '/js/feedback/list.js', CClientScript::POS_END);

  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
        $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
      }");
  $params = Yii::app()->getRequest()->getParam('FeedbackTreatment');
  //
  function getEditLink($id, $type, $chat, $icon=false)
  {
    $link = '/admin/' . (!$type ? 'mail/' . $id : 'update/' . $chat);
    if($icon)
    {
      return '<a href="' . $link . '" class="glyphicon glyphicon-edit" title="редактировать"></a>';
    }
    else
    {
      return '<a href="' . $link . '" title="редактировать">' . $id . '</a>';
    }
  }
  //
  function getStatusLabel($id, $status)
  {
    $arNames = Feedback::getAdminStatus(false);
    $arIcons = ['warning','double','important','pending','spam','success'];
    $html = '<div class="dropdown select_update">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >
        <span class="label label-' . $arIcons[$status] . '">' . $arNames[$status] . '</span>
        <span class="caret"></span>
      </button>';

    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    foreach ($arNames as $key => $v)
    {
      $html .= '<li data-id="' .  $id. '" data-value="' . $key
        . '" data-table="feedback" data-field="status" class="label label-' . $arIcons[$key] . '" style="width:160px"> ' . $v . '</li >';
    }
    $html .= '</ul></div>';
    return $html;
  }
  //
  function getLabel($s)
  {
    return '<span class="glyphicon ' . (!$s ? 'glyphicon-flash' : '') . '"></span>';
  }
?>
<div class="row">
  <div class="col-xs-12">
    <h3><?=$this->pageTitle?></h3>
    <div class="pull-right">
      <a href="<?=$this->createUrl('/feedback/0')?>" class="btn btn-success">Новое обращение</a>
    </div>
    <div class="clearfix"></div>
    <? $this->widget('zii.widgets.grid.CGridView',
      [
        'filter' => $model,
        'dataProvider' => $model->search(),
        'itemsCssClass' => 'table table-bordered table-hover custom-table',
        'enablePagination' => true,
        'afterAjaxUpdate' => 'reinstallDatePicker',
        'rowCssClassExpression' => function($row, $data)
        {
          return (!$data->is_smotr ? 'new-row' : '');
        },
        'htmlOptions' => ['id'=>'custom_list'],
        'columns' => [
          [
            'header' => 'ID',
            'name' => 'id',
            'value' => 'getEditLink($data->id,$data->type,$data->chat)',
            'type' => 'raw',
            'htmlOptions' => ['style' => 'width:5%']
          ],
          [
            'header' => 'Тип',
            'name' => 'type',
            'value' => 'AdminView::getUserType($data->type)',
            'type' => 'raw',
            'filter' => CHtml::dropDownList(
              'FeedbackTreatment[type]',
              $params['type']!=='' ? $params['type'] : '',
              [
                ''=>'Все',
                '0'=>'Гости',
                '2'=>'Соискатели',
                '3'=>'Работодатели',
              ]
            ),
            'htmlOptions' => ['style'=>'width:5%']
          ],
          [
            'header' => 'ID пользователя',
            'name' => 'pid',
            'value' => 'AdminView::getStr($data->pid)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:5%']
          ],
          [
            'header' => 'ФИО',
            'name' => 'name',
            'value' => '$data->name',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:25%']
          ],
          [
            'header' => 'Направление',
            'name' => 'direct',
            'value' => 'Feedback::getAdminDirects($data->direct)',
            'type' => 'raw',
            'filter' => CHtml::dropDownList(
              'FeedbackTreatment[direct]',
              $params['direct']!=='' ? $params['direct'] : '',
              Feedback::getAdminDirects()
            ),
            'htmlOptions' => ['style'=>'width:15%']
          ],
          [
            'header' => 'Тема',
            'name' => 'theme',
            'value' => 'AdminView::getStr($data->theme)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:20%']
          ],
          [
            'header' => 'Дата создания',
            'name' => 'crdate',
            'value' => 'Share::getPrettyDate($data->crdate)',
            'type' => 'raw',
            'filter' => AdminView::filterDateRange($this,'b_crdate','e_crdate'),
            'htmlOptions' => ['style'=>'width:5%']
          ],
          [
            'header' => 'Состояние',
            'name' => 'status',
            'value' => 'getStatusLabel($data->id, $data->status)',
            'type' => 'raw',
            'filter' => CHtml::dropDownList(
              'FeedbackTreatment[status]',
              $params['status']!=='' ? $params['status'] : '',
              Feedback::getAdminStatus()
            ),
            'htmlOptions' => ['style'=>'width:2%;padding:0']
          ],
          [
            'header'=>'Статус',
            'filter' => CHtml::dropDownList(
              'FeedbackTreatment[is_smotr]',
              isset($params['is_smotr']) ? $params['is_smotr'] : '',
              [
                ''=>'Все',
                '0'=>'Новые',
                '1'=>'Просмотренные',
              ]
            ),
            'name' => 'is_smotr',
            'value' => 'getLabel($data->is_smotr)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:2%']
          ],
          [
            'header' => 'Детально',
            'value' => 'getEditLink($data->id,$data->type,$data->chat,true)',
            'type' => 'raw',
            'filter' => false,
            'htmlOptions' => ['style'=>'width:3%']
          ]
        ],
      ]); ?>
  </div>
</div>