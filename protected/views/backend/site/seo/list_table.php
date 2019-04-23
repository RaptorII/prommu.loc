<? 
	if(Yii::app()->getRequest()->isAjaxRequest)
	{
		$table = Yii::app()->getRequest()->getParam('table');
		if($table==$viData['domain']->id)
		{
			$viData['id'] = $viData['domain']->id;
			$viData = array_merge($viData, $model->getDataList($viData['domain']->seo)); 
		}
		elseif(is_array($viData['subdomains'][$table]))
		{
			$viData['id'] = $table;
			$viData = array_merge($viData, $model->getDataList($viData['subdomains'][$table]['seo']));
		}
	}
?>
<div class="seo_data" data-table="<?=$viData['id']?>">
	<table class="table table-bordered table-hover custom-table seo_table">
		<thead>
			<? foreach ($viData['head']  as $key => $name): ?>
				<th>
					<a href="<?=$this->createUrl('seo',['table'=>$viData['id'],'sort'=>$key,'dir'=>$viData['dir']])?>"><?=$name?></a>
				</th>
			<? endforeach; ?>
		</thead>
		<tbody>
			<? foreach ($viData['items'] as $v): ?>
				<tr>
					<td style="width:5%"><?=$v['id']?></td>
					<td style="width:20%"><?=$v['url']?></td>
					<td style="width:65%"><?=$v['meta_title']?></td>
					<td style="width:10%"><?=$v['mdate']?></td>
					<td class="empty">
						<span class="glyphicon glyphicon-remove text-danger" title="Удалить"></span>
					</td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
	<div class="pager">
		<? $this->widget('CLinkPager',['pages'=>$viData['pages']]) ?>
	</div>
</div>