<div class="row">
	<div class="col-xs-12">
		<h3><?=$this->pageTitle?></h3>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-2" style="overflow-y: auto; height: 85vh;">
				<ul class="nav user__menu" role="tablist" id="tablist">
					
					<li class="active">
						<a href="#tab_<?=$viData['domain']->id?>" aria-controls="tab_<?=$viData['domain']->id?>" role="tab" data-toggle="tab"><?=$viData['domain']->city?></a>
					</li>
					<? foreach ($viData['subdomains'] as $key => $v): ?>
						<li>
							<a href="#tab_<?=$key?>" aria-controls="tab_<?=$key?>" role="tab" data-toggle="tab"><?=$v['city']?></a>
						</li>
					<? endforeach; ?>
			  </ul>
			</div>
			<?
			// content
			?>
			<div class="col-xs-12 col-sm-9 col-md-10">
				<div class="tab-content">
					<?
					// domain
					?>
					<div role="tabpanel" class="tab-pane fade active in" id="tab_<?=$viData['domain']->id?>">
						<? 
							$viData['id'] = $viData['domain']->id;
							$viData = array_merge($viData, $model->getDataList($viData['domain']->seo)); 
						?>
						<h4>Домен <?=$viData['domain']->city?></h4>
						<div class="pull-right">
							<a href="<?=$this->createUrl('',['table'=>$viData['domain']->id,'id'=>0])?>" class="btn btn-success">Добавить мета данные</a>
						</div>
						<div class="clearfix"></div><br>
						<?
						 if(count($viData['items'])): ?>
							<? $this->renderPartial('seo/list_table',['viData'=>$viData]); ?>
						<? else: ?>
							Ничего не найдено
						<? endif; ?>
					</div>
					<?
					// subdomains
					?>
					<? foreach ($viData['subdomains'] as $key => $v): ?>
						<? 
							$viData['id'] = $key;
							$viData = array_merge($viData, $model->getDataList($v['seo'])); 
						?>
						<div role="tabpanel" class="tab-pane fade" id="tab_<?=$key?>">
							<h4>Поддомен <?=$v['city']?></h4>
							<div class="pull-right">
								<a href="<?=$this->createUrl('',['table'=>$key,'id'=>0])?>" class="btn btn-success">Добавить мета данные</a>
							</div>
							<div class="clearfix"></div><br>
							<? if(count($viData['items'])): ?>
								<? $this->renderPartial('seo/list_table',['viData'=>$viData]); ?>
							<? else: ?>
								Ничего не найдено
							<? endif; ?>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.glyphicon-remove{ cursor: pointer; }
</style>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		// redirect to detail page
		$(document).on(
			'click',
			'.seo_table tbody td',
			function(e){ 
				var parent = $(this).closest('.seo_data')[0],
						id = $(this).siblings('td').eq(0).text(),
						url = '/admin/seo/' + id + '?table=' + parent.dataset.table;

				if(!$(this).hasClass('empty'))
					$(location).attr('href',url);
			});
		// pagination
		$(document).on(
			'click',
			'.pager a',
			function(e){
				var parent = $(this).closest('.seo_data')[0],
						table = parent.dataset.table,
						link = e.target.href;

				e.preventDefault();

				link += (link.indexOf('?')>=0 ? '&' : '?') + 'table=' + table;

				$.ajax({
						url : link,
						success : function(result)
						{	$('[data-table="'+table+'"]').html(result); }
					});
			});
		// sort
		$(document).on(
			'click',
			'.seo_table thead th a',
			function(e){
				var parent = $(this).closest('.seo_data')[0],
						table = parent.dataset.table,
						link = e.target.href;

				e.preventDefault();

				$.ajax({
						url : link,
						success : function(result)
						{ $('[data-table="'+table+'"]').html(result); }
					});
			});
		// delete
		$(document).on(
			'click',
			'.glyphicon-remove',
			function(e){
				var parent = $(this).closest('.seo_data')[0],
						id = $(this).closest('td').eq(0).siblings('td').eq(0).text(),
						url = '/admin/seo/' + id + '?delete=1&table=' + parent.dataset.table,
						message = confirm('Вы действительно хотите удалить запись '+id+'?');

				if(message)
					$(location).attr('href',url);
			});
	});
</script>