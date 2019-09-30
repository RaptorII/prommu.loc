<?php
  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
          $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
        }");
  $service = Yii::app()->getRequest()->getParam('service');
  $title = Services::getServiceName($service);
  $this->setPageTitle($title);
  $this->breadcrumbs = ['Все услуги'=>['/service'], $title];
  $params = Yii::app()->getRequest()->getParam('Outstaffing');
  $model = new Outstaffing();
  $model->type=$service;
//
function getLabel($s)
{
  return '<span class="glyphicon ' . ($s ? 'glyphicon-flash' : '') . '"></span>';
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
        'afterAjaxUpdate' => 'reinstallDatePicker',
        'enablePagination' => true,
        'rowCssClassExpression' => function($row, $data)
        {
          return ($data->is_new ? 'new-row' : '');
        },
        'columns' => [
          [
            'header'=>'id',
            'name' => 'id_key',
            'value' => '$data->id_key',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:2%']
          ],
          [
            'header' => 'Работодатель',
            'name' => 'company_search',
            'value' => 'AdminView::getLink("/admin/EmplEdit/".$data->id, $data->company_search)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:20%']
          ],
          [
            'header' => 'Услуга',
            'filter' => CHtml::dropDownList(
              'Outstaffing[subservice_search]',
              isset($params['subservice_search']) ? $params['subservice_search'] : '',
              Outstaffing::getSubService($service)
            ),
            'name' => 'subservice_search',
            'value' => 'Outstaffing::getService($data)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:20%']
          ],
          [
            'header' => 'Дата',
            'filter' => false,
            'name' => 'date',
            'value' => 'Share::getPrettyDate($data->date)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:10%']
          ],
          [
            'header'=>'Статус',
            'filter' => CHtml::dropDownList(
              'Outstaffing[is_new]',
              isset($params['is_new']) ? $params['is_new'] : '',
              [''=>'Все', '1'=>'Новые', '0'=>'Просмотреные']
            ),
            'name' => 'is_new',
            'value' => 'getLabel($data->is_new)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:3%']
          ]
        ]
      )
    ); ?>
  </div>
</div>