<?php
  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
        $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
      }");
  $service = Yii::app()->getRequest()->getParam('service');
  $params = Yii::app()->getRequest()->getParam('Service');
  $service=='creation_vacancy' && $service='creation-vacancy';
  if($service=='all_by_vacancy') // просмотр платных услуг по конкретной вакансии
  {
    $title = 'Все услуги по вакансии №'.$params['name'];
  }
  else
  {
    $title = Services::getServiceName($service);
  }
  $this->setPageTitle($title);
  $this->breadcrumbs = ['Все услуги'=>['/service'], $title];
  $model = new Service();
  if($service!='all_by_vacancy')
  {
    $model->type=$service; // просмотр платных услуг по конкретной вакансии
  }
  $arColumns = [
    [
      'header'=>'id',
      'name' => 'id',
      'value' => '$data->id',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:2%']
    ],
  ];
  //
  if($service=='all_by_vacancy')
  {
    $arColumns[] = [
      'header' => 'Услуга',
      'name' => 'type_custom',
      'value' => 'Services::getServiceName($data->type)',
      'type' => 'raw',
      'filter' => false,
      'htmlOptions' => ['style'=>'width:8%']
    ];
  }
  //
  $arColumns[] = [
    'header' => 'Работодатель',
    'name' => 'company_search',
    'value' => 'AdminView::getLink("/admin/EmplEdit/".$data->id_user, $data->company_search)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:10%']
  ];
  $arColumns[] = [
    'header' => 'ID_USER работодателя',
    'name' => 'id_user',
    'value' => '$data->id_user',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:2%']
  ];
  //
  if($service!='api')
  {
    $arColumns[] = [
      'header' => 'Вакансия',
      'name' => 'vacancy_search',
      'value' => 'AdminView::getLink("/admin/VacancyEdit/{$data->name}",$data->vacancy_search)',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:10%']
    ];
  }
  if(in_array($service,['vacancy','upvacancy','creation-vacancy','all_by_vacancy']))
  {
    $arColumns[] = [
      'header' => 'ID вакансии',
      'name' => 'name',
      'value' => '$data->name',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:2%']
    ];
  }
  //
  if(in_array($service,['vacancy','email','sms','upvacancy','personal-invitation','creation-vacancy','all_by_vacancy'])) // платные услуги
  {
    if($service=='vacancy') // только для премиум
    {
      $arColumns[] = [
        'header' => 'Дата начала',
        'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker',
          [
            'name' => 'Service[bdate]',
            'value' => $params['bdate'],
            'options' => ['changeMonth' => true],
            'htmlOptions' => ['class' => 'grid_date', 'autocomplete' => 'off']
          ],
          true
        ),
        'value' => 'Share::getDate(strtotime($data->bdate),"d.m.Y")',
        'type' => 'raw',
        'htmlOptions' => ['style'=>'width:5%']
      ];
      $arColumns[] = [
        'header' => 'Дата окончания',
        'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker',
          [
            'name' => 'Service[edate]',
            'value' => $params['edate'],
            'options' => ['changeMonth' => true],
            'htmlOptions' => ['class' => 'grid_date', 'autocomplete' => 'off']
          ],
          true
        ),
        'value' => 'Share::getDate(strtotime($data->edate),"d.m.Y")',
        'type' => 'raw',
        'htmlOptions' => ['style'=>'width:5%']
      ];
    }
    //
    $arColumns[] = [
      'header' => 'Cумма',
      'name' => 'sum',
      'value' => '$data->sum',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:5%']
    ];
    //
    $arColumns[] = [
      'header' => 'Состояние',
      'filter' => CHtml::dropDownList(
        'Service[status]',
        isset($params['status']) ? $params['status'] : '',
        [''=>'Все', '0'=>'Не оплачено', '1'=>'Оплачено']
      ),
      'name' => 'status',
      'value' => 'getStatus($data->status)',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:5%']
    ];
    //
    $arColumns[] = [
      'header' => 'Транзакция',
      'filter' => CHtml::dropDownList(
        'Service[stack]',
        isset($params['stack']) ? $params['stack'] : '',
        [''=>'Все', '1'=>'В наличии', '2'=>'Отсутствует']
      ),
      'name' => 'stack',
      'value' => 'AdminView::getStr($data->stack)',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:5%']
    ];
    //
    $arColumns[] = [
      'header' => 'UnitpayID',
      'filter' => CHtml::dropDownList(
        'Service[key]',
        isset($params['key']) ? $params['key'] : '',
        [''=>'Все', '1'=>'В наличии', '2'=>'Отсутствует']
      ),
      'name' => 'key',
      'value' => 'AdminView::getStr($data->key)',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:5%']
    ];
    //
    $arColumns[] = [
      'header' => 'Юр. счет',
      'filter' => CHtml::dropDownList(
        'Service[legal]',
        isset($params['legal']) ? $params['legal'] : '',
        [''=>'Все', '1'=>'В наличии', '2'=>'Отсутствует']
      ),
      'name' => 'legal',
      'value' => 'AdminView::getLink(MainConfig::$PAGE_LEGAL_ENTITY_RECEIPT."{$data->legal}",$data->legal)',
      'type' => 'raw',
      'htmlOptions' => ['style'=>'width:5%']
    ];
  }
  //
  if($service=='all_by_vacancy')
  {
    $arColumns[] = [
      'header' => 'Дата',
      'name' => 'date',
      'value' => 'Share::getDate(strtotime($data->date),"d.m.Y")',
      'type' => 'raw',
      'filter' => false,
      'htmlOptions' => ['style'=>'width:5%']
    ];
  }
  //
  $arColumns[] = [
    'header'=>'Статус',
    'filter' => CHtml::dropDownList(
      'Service[is_new]',
      isset($params['is_new']) ? $params['is_new'] : '',
      [''=>'Все', '1'=>'Новые', '0'=>'Просмотреные']
    ),
    'name' => 'is_new',
    'value' => 'getLabel($data->is_new)',
    'type' => 'raw',
    'htmlOptions' => ['style'=>'width:3%']
  ];
  //
  function getStatus($s)
  {
    return '<span class="label label-'
      . (!$s ? 'warning' : 'success') . '">'
      . (!$s ? 'Не оплачено' : 'Оплачено') . '</span>';
  }
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
          return ($data->is_new ? 'new-row '.$data->type : ''.$data->type);
        },
        'columns' => $arColumns
      )
    ); ?>
  </div>
</div>