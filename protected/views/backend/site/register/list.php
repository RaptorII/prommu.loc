<?php
$bUrl = Yii::app()->request->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . '/css/template.css');
$gcs->registerScript(
  're-install-date-picker',
  "function reinstallDatePicker(id, data){
        $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
      }");
$get = Yii::app()->getRequest()->getParam('UserRegisterAdmin');
$arColumns = [
  [
    'header' => 'ID',
    'name' => 'id',
    'value' => '$data->id',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:5%']
  ],
  [
    'header' => 'Дата создания',
    'name' => 'date',
    'filter' => AdminView::filterDateRange($this,'b_date','e_date'),
    'value' => 'Share::getDate($data->date)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:5%']
  ],
  [
    'header' => 'Сайт',
    'filter' => CHtml::dropDownList(
      'UserRegisterAdmin[subdomen]',
      isset($get['subdomen']) ? $get['subdomen'] : '',
      Subdomain::getCacheData()->data_list
    ),
    'name' => 'subdomen',
    'value' => 'AdminView::getSubdomain($data->subdomen)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:10%']
  ],
  [
    'header' => 'Email/телефон',
    'name' => 'login',
    'value' => 'AdminView::getRegisterLogin($data->login, $data->login_type)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:20%']
  ]
];
//
//
//
if(
  Share::isApplicant($model->getType)
  &&
  in_array(
    $model->getState,
    [UserRegisterAdmin::$STATE_PROFILE, UserRegisterAdmin::$STATE_AVATAR]
  )
)
{
  $arColumns[] = [
    'header' => 'Имя',
    'name' => 'name',
    'value' => 'AdminView::getStr($data->name)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:20%']
  ];
  $arColumns[] = [
    'header' => 'Фамилия',
    'name' => 'surname',
    'value' => 'AdminView::getStr($data->surname)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:20%']
  ];
  $arColumns[] = [
    'header' => 'Дата подтверждения',
    'filter' => false,
    'value' => 'Share::getDate($data->is_confirm_time)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:5%']
  ];
  $arColumns[] = [
    'header' => 'Ссылка на профиль',
    'value' => 'AdminView::getUserProfileLink($data->id_user,$data->type)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:5%']
  ];
}
//
//
//
if(
  Share::isEmployer($model->getType)
  &&
  in_array(
    $model->getState,
    [UserRegisterAdmin::$STATE_PROFILE, UserRegisterAdmin::$STATE_AVATAR]
  )
)
{
  $arColumns[] = [
    'header' => 'Компания',
    'name' => 'name',
    'value' => 'AdminView::getStr($data->name)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:20%']
  ];
  $arColumns[] = [
    'header' => 'Дата подтверждения',
    'filter' => false,
    'value' => 'Share::getDate($data->is_confirm_time)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:5%']
  ];
  $arColumns[] = [
    'header' => 'Ссылка на профиль',
    'filter' => false,
    'value' => 'AdminView::getUserProfileLink($data->id_user,$data->type)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:5%']
  ];
}
//
//
//
$this->widget(
  'zii.widgets.grid.CGridView',
  [
    'dataProvider' => $model->search($model->getState, $model->getType),
    'itemsCssClass' => 'table table-bordered table-hover custom-table',
    'enablePagination' => true,
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'filter' => $model,
    'columns' => $arColumns
  ]
);
?>
<script>
  'use strict'
  jQuery(function($){
    // открытие заявки
    $(document).on(
      'dblclick',
      '.custom-table tbody td',
      function(e){
        var id = $(this).siblings('td').eq(0).text(),
          url = window.location.href + '&id=' + id;

        if(!$(this).hasClass('empty'))
          $(location).attr('href',url);
      });
  });
</script>
