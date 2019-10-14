<?php
$params = Yii::app()->getRequest()->getParam('FeedbackTreatment');
$arColumns = [
  [
    'header' => 'ID',
    'name' => 'id',
    'value' => 'getEditLink($data->id,$data->type,$data->chat)',
    'type' => 'raw',
    'htmlOptions' => ['style' => 'width:5%']
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
    'value' => 'getStatusLabel($data->id, $data->status, $data->type)',
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
];
?>
<div class="pull-right">
  <a href="<?=$this->createUrl(
    '/feedback/0',
    [
      'id_user' => $_GET['FeedbackTreatment']['pid'],
      'back' => 'profile'
    ])?>" class="btn btn-success">Новое обращение</a>
</div>
<div class="clearfix"></div>
<? $this->renderPartial('/site/feedback/list',['arColumns'=>$arColumns]) ?>