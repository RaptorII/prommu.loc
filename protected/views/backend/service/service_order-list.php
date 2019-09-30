<?php
  $title = 'Заказ услуг гостями';
  $this->setPageTitle($title);
  $this->breadcrumbs = ['Все услуги'=>['/service'], $title];
  $params = Yii::app()->getRequest()->getParam('ServiceGuestOrder');
  $model = new ServiceGuestOrder();
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
      <a href="<?=$this->createUrl('/service')?>" class="btn btn-success">Все услуги</a>
    </div>
    <div class="clearfix"></div>
    <? $this->widget(
      'zii.widgets.grid.CGridView',
      array(
        'dataProvider' => $model->search(),
        'itemsCssClass' => 'table table-bordered table-hover custom-table',
        'filter' => $model,
        'enablePagination' => true,
        'rowCssClassExpression' => function($row, $data)
        {
          return (!$data->is_viewed ? 'new-row' : '');
        },
        'columns' => array(
          array(
            'header'=>'id',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:5%']
          ),
          array(
            'header'=>'Услуга',
            'name' => 'id_se',
            'filter' => CHtml::dropDownList(
              'ServiceGuestOrder[id_se]',
              $params['id_se'],
              ServiceGuestOrder::getServiceName()
            ),
            'value' => 'ServiceGuestOrder::getServiceName($data->id_se)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:20%']
          ),
          array(
            'header'=>'ФИО',
            'name' => 'fio',
            'value' => '$data->fio',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:30%']
          ),
          array(
            'header'=>'Email',
            'name' => 'email',
            'value' => '$data->email',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:30%']
          ),
          array(
            'header'=>'Дата создания',
            'filter' => '',
            'name' => 'crdate',
            'value' => 'Share::getPrettyDate($data->crdate)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:10%']
          ),
          array(
            'header'=>'Статус',
            'filter' => CHtml::dropDownList(
              'ServiceGuestOrder[is_viewed]',
              isset($params['is_viewed']) ? $params['is_viewed'] : '',
              [''=>'Все', '0'=>'Новые', '1'=>'Просмотреные']
            ),
            'name' => 'is_viewed',
            'value' => 'getLabel($data->is_viewed)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:5%']
          )
        )
      )
    ); ?>
  </div>
</div>