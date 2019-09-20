<?
	$bUrl = Yii::app()->request->baseUrl;
	$gcs = Yii::app()->getClientScript();
	$gcs->registerCssFile($bUrl . '/css/template.css');
?>
<style type="text/css">
	.custom-table.table-hover>tbody>tr.default:hover,
	.custom-table tr:hover .default,
	.custom-table tr .default{
		font-size: 14px;
		color: #333;
		background-color: inherit;
	}
	.custom-table.table-hover>tbody>tr.pagination_cell,
	.custom-table.table-hover>tbody>tr.pagination_cell:hover{ background-color:#ecf0f5; }
	.table_form{ 
		position: relative;
		overflow: overlay; 
	}
	.table_form.load:before{
		content: '';
		background: rgba(255,255,255,.7) url(/theme/pic/vacancy/loading.gif) center no-repeat;
		display: block;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 2;
	}
	.table_form .custom-table tbody tr:hover td:not(.empty){
		color: initial;
		cursor: initial;
	}
</style>
<h3><?=$this->pageTitle?></h3>
<form class="table_form">
	<table class="table table-bordered table-hover custom-table">
		<thead>
			<?
			// title
			?>
			<tr>
				<th><a class="sort-link" data-value="id_user" href="javascript:void(0)">ID USER</a></th>
				<th>Тип</th>
				<th>Наименование</th>
				<th>Email</th>
				<th>Дата создания</th>
				<th>Дата изменения</th>
				<th>Статус</th>
				<th>Модерация</th>
				<th>Соцсети</th>
				<th>В_сети</th>
				<th>Профиль</th>
			</tr>
			<?
			// filter
			?>
			<tr class="filters">
				<td><input name="User[id_user]" type="text"></td>
				<td>
					<select name="User[status]">
						<option value="">все</option>
						<option value="2">соискатели</option>
						<option value="3">работодатели</option>
					</select>
				</td>
				<td><input name="User[name]" type="text"></td>
				<td><input name="User[email]" type="text"></td>
				<td>
					<div class="filter_date_range">
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker',array(
									'name'=>'cbdate',
									'options'=>['changeMonth'=>true],
									'htmlOptions'=>[
										'class'=>'grid_date',
										'autocomplete'=>'off'
									]
								));
						?>
						<div class="separator">-</div>
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker',array(
									'name'=>'cedate',
									'options'=>['changeMonth'=>true],
									'htmlOptions'=>[
										'class'=>'grid_date',
										'autocomplete'=>'off'
									]
								));
						?>
					</div>
				</td>
				<td>
					<div class="filter_date_range">
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker',array(
									'name'=>'mbdate',
									'options'=>['changeMonth'=>true],
									'htmlOptions'=>[
										'class'=>'grid_date',
										'autocomplete'=>'off'
									]
								));
						?>
						<div class="separator">-</div>
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker',array(
									'name'=>'medate',
									'options'=>['changeMonth'=>true],
									'htmlOptions'=>[
										'class'=>'grid_date',
										'autocomplete'=>'off'
									]
								));
						?>
					</div>
				</td>
				<td>
					<select name="User[isblocked]">
						<option value="">все</option>
						<option value="0">активен</option>
						<option value="1">заблокирован</option>
						<option value="2">ожидает активации</option>
						<option value="3">активирован</option>
						<option value="4">не отображается</option>
					</select>
				</td>
				<td>
					<select name="User[ismoder]">
						<option value="">все</option>
						<option value="0">в работе</option>
						<option value="1">просмотреные</option>
					</select>
				</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</thead>
		<tbody><?=$this->renderPartial('users/list-all-ajax'); ?></tbody>
	</table>
</form>
<script type="text/javascript">
	'user strict'
	$(function ($) {
		var data = {sort:'id_user',dir:'desc'};

		$('.filters select,.filters input').on('change',function(){
			var arInput = $('.table_form').serializeArray();

			$.each(arInput,function(){ data[this.name] = this.value });
			data['page'] = '';
			getAjaxData(data);
		});
		// pagination
		$('.table_form').on('click','.page a',function(e){
			e.preventDefault();
			data['page'] = $(this).text();
			getAjaxData(data);
		});
		// sort
		$('.table_form').on('click','th a',function(e){
			e.preventDefault();
			data.dir = (data.dir==='desc' ? 'asc' : 'desc');
			data.sort = this.dataset.value;
			getAjaxData(data);
		});
		//
		function getAjaxData()
		{
			//console.log(arguments[0]);

			$('.table_form').addClass('load');

			$.ajax({
				data: arguments[0],
				success: function(res){
					$('.table_form table tbody').html(res);
					$('.table_form').removeClass('load');
				}
			});
		}
	});
</script>