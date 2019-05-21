<?
	$bUrl = Yii::app()->request->baseUrl;
	$gcs = Yii::app()->getClientScript();
	$gcs->registerCssFile($bUrl . '/css/template.css');
  $gcs->registerScriptFile($bUrl . '/js/vacancy/list.js', CClientScript::POS_HEAD);
  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
      $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
    }");
  $user = $_GET['User'];
?>
<h3><?=$this->pageTitle?></h3>
<?
	$model = new User;
	$this->widget(
		'zii.widgets.grid.CGridView', 
		array(
			'id'=>'users_table',
			'dataProvider' => $model->searchAll(),
			'itemsCssClass' => 'table table-bordered table-hover custom-table',
			'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid'),
			'filter' => $model,
			'afterAjaxUpdate' => 'reinstallDatePicker',
			'enablePagination' => true,
			'columns' => array(
					array(
						'header'=>'ID USER',
						'name' => 'id_user',
						'value' => '$data->id_user',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:2%']
					),
					array(
						'header'=>'Тип',
						'filter'=>CHtml::dropDownList(
								'User[status]', 
								isset($user['status']) 
								? $user['status'] : '', 
								['0'=>'все', '2'=>'соискатели', '3'=>'работодатели']
							),
						'name' => 'status',
						'value' => 'getValue($data,"status")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:2%']
					),
					array(
						'header'=>'Наименование',
						'name' => 'search_name',
						'value' => 'getValue($data,"name")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:30%']
					),
					array(
						'header'=>'Дата создания',
						'filter'=>AdminView::filterDateRange($this, 'b_cdate', 'e_cdate'),
						'name' => 'search_cdate',
						'value' => 'getValue($data,"cdate")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:2%']
					),
					array(
						'header'=>'Дата изменения',
						'filter'=>AdminView::filterDateRange($this, 'b_mdate', 'e_mdate'),
						'name' => 'search_mdate',
						'value' => 'getValue($data,"mdate")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:2%']
					),
					array(
						'header'=>'Статус',
						'filter'=>CHtml::dropDownList(
								'User[isblocked]', 
								isset($user['isblocked']) 
								? $user['isblocked'] : '', 
								array_merge([''=>'все'],User::getIsBlockedArray())
							),
						'name' => 'isblocked',
						'value' => 'getValue($data,"isblocked")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:10%']
					),
					array(
						'header'=>'Модерация',
						'name' => 'search_moder',
						'filter'=>CHtml::dropDownList(
								'User[search_moder]', 
								isset($user['search_moder']) 
								? $user['search_moder'] : '', 
								[''=>'все','0'=>'в работе','1'=>'просмотреные']
							),
						'value' => 'getValue($data,"moder")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:10%']
					),
					array(
						'header'=>'Соцсети',
						'name' => 'messenger',
						'filter' => '',
						'value' => 'getValue($data,"messenger")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:1%']
					),
					array(
						'header'=>'В_сети',
						'name' => 'is_online',
						'filter' => '',
						'value' => 'getValue($data,"is_online")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:1%']
					),
					array(
						'header'=>'Профиль',
						'value' => 'getValue($data,"link")',
						'type' => 'raw',
						'htmlOptions'=>['style'=>'width:1%']
					),
				)
			)
	);
	/**
	 * 
	 */
	function getValue($obj, $name)
	{
		$arModer = [0=>"в работе", 1=>"просмотрено"];
		$arBlLabel = ["success","danger","warning","info","primary"];
		$result = false;
		if(in_array($name, ['moder','isblocked','messenger','is_online']))
		{
			switch ($name)
			{
				case 'moder':
					$result = '<span class="label label-' . ($obj->ismoder ? 'success' : 'warning') 
						. '">' . $arModer[$obj->ismoder] . '</span>';
					break;
				case 'isblocked':
					$result = '<span class="label label-' . $arBlLabel[$obj->isblocked] . '">' 
						. User::getIsBlockedArray()[$obj->isblocked] . '</span>';
					break;
				case 'messenger':
					$obj->messenger>0 && $result='<span class="glyphicon glyphicon-ok-sign" title="зарегистрирован через соцсети"></span>';
					break;
				case 'is_online':
					$result = $obj->is_online
						? '<span class="glyphicon glyphicon-ok  text-success" title="онлайн"></span>' 
						: '<span class="glyphicon glyphicon-remove  text-danger" title="офлайн"></span>';
					break;
			}
			return empty($result) || !$result ? ' - ' : $result;
		}
		else if(Share::isApplicant($obj->status))
		{
			switch ($name)
			{
				case 'name':
					$result = trim("{$obj->resume->firstname} {$obj->resume->lastname}");
					break;
				case 'status':
					$result = '<span class="glyphicon glyphicon-user" title="соискатель"></span>';
					break;
				case 'cdate':
					$result = Share::getPrettyDate($obj->resume->date_public,false,true);
					!$obj->resume->date_public && $result=false;
					break;
				case 'mdate':
					$result = Share::getPrettyDate($obj->resume->mdate,false,true);
					!$obj->resume->mdate && $result=false;
					break;
				case 'link':
					$result = '<a href="/admin/PromoEdit/' . $obj->id_user 
						. '" class="glyphicon glyphicon-edit" title="редактировать"></a> '
						. '<a href="/ankety/' . $obj->id_user 
						. '" class="glyphicon glyphicon-new-window" target="_blank" title="профиль"></a>';
					break;
			}
			return empty($result) || !$result ? ' - ' : $result;
		}
		else if(Share::isEmployer($obj->status))
		{
			switch ($name)
			{
				case 'name':
					$result = trim($obj->employer->name);
					break;
				case 'status':
					$result = '<span class="glyphicon glyphicon-briefcase" title="работодатель"></span>';
					break;
				case 'cdate':
					$result = Share::getPrettyDate($obj->employer->crdate,false,true);
					!$obj->employer->crdate && $result=false;
					break;
				case 'mdate':
					$result = Share::getPrettyDate($obj->employer->mdate,false,true);
					!$obj->employer->mdate && $result=false;
					break;
				case 'link':
					$result = '<a href="/admin/EmplEdit/' . $obj->id_user 
						. '" class="glyphicon glyphicon-edit" title="редактировать"></a> '
						. '<a href="/ankety/' . $obj->id_user 
						. '" class="glyphicon glyphicon-new-window" target="_blank" title="профиль"></a>';		
					break;
			}
			return empty($result) || !$result ? ' - ' : $result;
		}
		return ' - ';
	}
?>
<style type="text/css">
	.table.table-hover{
			    overflow: overlay;
	}
</style>