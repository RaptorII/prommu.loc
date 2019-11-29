<?php
  $model = new FeedbackTreatment;
  //
  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
        $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
      }");
  //
  function getEditLink($id, $type, $chat, $icon=false)
  {
    $link = '/admin/' . (!$type ? 'mail/' . $id : 'update/' . $chat);
    if($icon)
    {
      return '<a href="' . $link . '" class="glyphicon glyphicon-edit" title="Ответить"></a>';
    }
    else
    {
      return '<a href="' . $link . '" title="редактировать">' . $id . '</a>';
    }
  }
  //
  function getStatusLabel($id, $status, $type)
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
      if(!Share::isGuest($type) && in_array($key,[2,5]))
        continue;

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
  //
  //
  //
  $this->widget('zii.widgets.grid.CGridView',
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
    'htmlOptions' => ['id'=>'feedback_list'],
    'columns' => $arColumns
  ]);