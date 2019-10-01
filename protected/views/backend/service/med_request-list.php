<?php
  $title = Services::getServiceName('medbook');
  $this->setPageTitle($title);
  $this->breadcrumbs = ['Все услуги'=>['/service'], $title];
  $params = Yii::app()->getRequest()->getParam('MedCard');
  $model = new MedCard('search');
  $model->unsetAttributes();
  //
  function getStatus($status, $id)
  {
    $arNames = ['Новая','Просмотрена','Отменена','Обработка','Не хватает данных','Выполнена'];
    $arIcons = ['success','warning','important','info','primary','info','success'];
    $html = '<div class="dropdown select_update">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >
        <span class="label label-' . $arIcons[$status] . '">' . $arNames[$status] . '</span>
        <span class="caret"></span>
      </button>';

    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
    foreach ($arNames as $key => $v)
    {
      $html .= '<li data-id="' .  $id. '" data-value="' . $key
        . '" data-table="med_request" data-field="status" class="label label-' . $arIcons[$key] . '"> ' . $v . '</li >';
    }
    $html .= '</ul></div>';
    return $html;
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
	          return (!$data->status ? 'new-row' : '');
	        },
	        'htmlOptions' => ['id'=>'custom_list'],
	        'columns' => [
	          [
	            'header'=>'id',
	            'name' => 'id',
	            'value' => '$data->id',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:3%']
	          ],
	          [
	            'header'=>'Фамилия',
	            'name' => 'fff',
	            'value' => '$data->fff',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:20%']
	          ],
	          [
	            'header'=>'Телефон',
	            'name' => 'tel',
	            'value' => '$data->tel',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:10%']
	          ],
	          [
	            'header'=>'Email',
	            'name' => 'email',
            	'value' => '$data->email',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:10%']
	          ],
	          [
	            'header'=>'Выбранный адрес',
	            'filter' => CHtml::dropDownList(
	              'MedCard[regaddr]',
	              isset($params['regaddr']) ? $params['regaddr'] : '',
	              MedCard::getIndex(true)
	            ),
	            'name' => 'regaddr',
	            'value' => '$data->regaddr',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:20%']
	          ],
	          [
	            'header'=>'Способ оплаты',
	            'filter' => CHtml::dropDownList(
	              'MedCard[pay]',
	              isset($params['pay']) ? $params['pay'] : '',
	              MedCard::getPayType(true)
	            ),
	            'name' => 'pay',
	            'value' => '$data->pay',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:10%']
	          ],
	          [
	            'header'=>'Дата создания',
	            'filter'=>false,
	            'name' => 'crdate',
	            'value' => 'Share::getPrettyDate($data->crdate)',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:10%']
	          ],
	          [
	            'header'=>'Статус',
	            'filter' => CHtml::dropDownList(
	              'MedCard[status]',
	              isset($params['status']) ? $params['status'] : '',
	              [
	                'all'=>'Все',
	                '0'=>'Новые',
	                '1'=>'Просмотренные',
	                '2'=>'Отмененные',
	                '3'=>'В обработке',
	                '4'=>'Не хватает данных',
	                '5'=>'Выполненные',
	              ]
	            ),
	            'name' => 'status',
	            'value' => 'getStatus($data->status, $data->id)',
	            'type' => 'raw',
	            'htmlOptions' => ['style'=>'width:5%; padding:0']
	          ]
	        ]
	      )
	    ); ?>
	</div>
</div>