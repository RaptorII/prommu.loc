<?
  $params = Yii::app()->getRequest()->getParam('ServiceGuestOrder');
  //
  function getLabel($s)
  {
    return '<span class="label label-' . (!$s ? 'warning' : 'success') . '">' . (!$s ? 'Новый' : 'Просмотрен') . '</span>';
  }
?>
<div class="row">
  <div class="col-xs-12 notifications">
    <h3><?=$this->pageTitle?></h3>
    <? $this->widget(
      'zii.widgets.grid.CGridView',
      array(
        'dataProvider' => $model->search(),
        'itemsCssClass' => 'table table-bordered table-hover custom-table',
        'htmlOptions'=> ['class'=>'guest-order'],
        'filter' => $model,
        'enablePagination' => true,
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
            'htmlOptions' => ['style'=>'width:25%']
          ),
          array(
            'header'=>'ФИО',
            'name' => 'fio',
            'value' => '$data->fio',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:25%']
          ),
          array(
            'header'=>'Email',
            'name' => 'email',
            'value' => '$data->email',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:25%']
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
            'htmlOptions' => ['style'=>'width:10%']
          )
        )
      )
    ); ?>
  </div>
</div>
<script type="text/javascript">
  'use strict'
  jQuery(function($){
    $(document).on(
      'click',
      '.custom-table tbody td',
      function(e){
        var parent = $(this).closest('.notif-module')[0],
          id = $(this).siblings('td').eq(0).text(),
          url = '/admin/services?type=guest-order&id=' + id;

        if(!$(this).hasClass('empty'))
          $(location).attr('href',url);
      });
  });
</script>