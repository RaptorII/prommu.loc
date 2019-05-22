<?
	$model = new User;
	$viData = $model->searchAll();
?>
<? if(count($viData['id'])): ?>
	<?
	// count
	?>	
	<tr class="default">
		<td colspan="10" class="default empty"><? 
			$n = count($viData['id']);
			$sum = $viData['offset'] + $viData['limit'];
			echo 'Элементы ' . ($viData['offset']+1) . '—' . ($n<$sum ? $n : $sum) . ' из ' . count($viData['id']) . '.';
		?></td>
	</tr>
	<? foreach ($viData['items'] as $k => $item): ?>
		<tr>
			<td style="width:2%"><?=$item['id_user']?></td>
			<td style="width:2%"><?=getValue($item,"status")?></td>
			<td style="width:30%"><?=getValue($item,"name")?></td>
			<td style="width:2%"><?=getValue($item,"cdate")?></td>
			<td style="width:2%"><?=getValue($item,"mdate")?></td>
			<td style="width:10%"><?=getValue($item,"isblocked")?></td>
			<td style="width:10%"><?=getValue($item,"ismoder")?></td>
			<td style="width:1%"><?=getValue($item,"messenger")?></td>
			<td style="width:1%"><?=getValue($item,"is_online")?></td>
			<td style="width:1%">
				<a href="/admin/EmplEdit/21887" class="glyphicon glyphicon-edit" title="редактировать"></a>
				<a href="/ankety/21887" class="glyphicon glyphicon-new-window" target="_blank" title="профиль"></a>
			</td>
		</tr>
	<? endforeach; ?>
	<?
	// pagination
	?>
	<tr class="default pagination_cell">
		<td colspan="10" class="default">
			<div class="pager">
				<? $this->widget('CLinkPager', ['pages' => $viData['pages']]); ?>
			</div>
		</td>
	</tr>
<? else: ?>
	<?
	// empty result
	?>
	<tr class="empty">
		<td colspan="10">Ничего не найдено.</td>
	</tr>
<? endif; ?>
<?
	/**
	 * 
	 */
	function getValue($arr, $name)
	{
		$arModer = [0=>"в работе", 1=>"просмотрено"];
		$arBlLabel = ["success","danger","warning","info","primary"];
		$result = false;
		if(in_array($name, ['ismoder','isblocked','messenger','is_online']))
		{
			switch ($name)
			{
				case 'ismoder':
					$result = '<span class="label label-' . ($arr['ismoder'] ? 'success' : 'warning') 
						. '">' . $arModer[$arr['ismoder']] . '</span>';
					break;
				case 'isblocked':
					$result = '<span class="label label-' . $arBlLabel[$arr['isblocked']] . '">' 
						. User::getIsBlockedArray()[$arr['isblocked']] . '</span>';
					break;
				case 'messenger':
					$arr['messenger']>0 && $result='<span class="glyphicon glyphicon-ok-sign" title="зарегистрирован через соцсети"></span>';
					break;
				case 'is_online':
					$result = $arr['is_online']
						? '<span class="glyphicon glyphicon-ok  text-success" title="онлайн"></span>' 
						: '<span class="glyphicon glyphicon-remove  text-danger" title="офлайн"></span>';
					break;
			}
			return empty($result) || !$result ? ' - ' : $result;
		}
		else if(Share::isApplicant($arr['status']))
		{
			switch ($name)
			{
				case 'name':
					$result = trim("{$arr['firstname']} {$arr['lastname']}");
					break;
				case 'status':
					$result = '<span class="glyphicon glyphicon-user" title="соискатель"></span>';
					break;
				case 'cdate':
					$result = Share::getPrettyDate($arr['rcdate'],false,true);
					!$arr['rcdate'] && $result=false;
					break;
				case 'mdate':
					$result = Share::getPrettyDate($arr['rmdate'],false,true);
					!$arr['rmdate'] && $result=false;
					break;
				case 'link':
					$result = '<a href="/admin/PromoEdit/' . $arr['id_user'] 
						. '" class="glyphicon glyphicon-edit" title="редактировать"></a> '
						. '<a href="/ankety/' . $arr['id_user'] 
						. '" class="glyphicon glyphicon-new-window" target="_blank" title="профиль"></a>';
					break;
			}
			return empty($result) || !$result ? ' - ' : $result;
		}
		else if(Share::isEmployer($arr['status']))
		{
			switch ($name)
			{
				case 'name':
					$result = trim($arr['name']);
					break;
				case 'status':
					$result = '<span class="glyphicon glyphicon-briefcase" title="работодатель"></span>';
					break;
				case 'cdate':
					$result = Share::getPrettyDate($arr['ecdate'],false,true);
					!$arr['ecdate'] && $result=false;
					break;
				case 'mdate':
					$result = Share::getPrettyDate($arr['emdate'],false,true);
					!$arr['emdate'] && $result=false;
					break;
				case 'link':
					$result = '<a href="/admin/EmplEdit/' . $arr['id_user'] 
						. '" class="glyphicon glyphicon-edit" title="редактировать"></a> '
						. '<a href="/ankety/' . $arr['id_user'] 
						. '" class="glyphicon glyphicon-new-window" target="_blank" title="профиль"></a>';		
					break;
			}
			return empty($result) || !$result ? ' - ' : $result;
		}
		return ' - ';
	}
?>