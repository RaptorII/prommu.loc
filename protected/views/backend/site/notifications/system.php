<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item']): ?>
	<div class="alert danger">Данные отсутствуют</div>
<? else: ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="hidden-xs hidden-sm col-md-2"></div>
				<div class="col-xs-12 col-md-8">
					<table class="table table-bordered template-table">
						<tbody>
							<tr><td><b>ID</b></td><td><?=$viData['item']->id?></td></tr>
							<tr><td><b>Дата создания</b></td><td><?=Mailing::getDate($viData['item']->cdate)?></td></tr>
							<tr><td><b>Статус</b></td><td><?=Mailing::getStatus($viData['item']->status)?></td></tr>
							<tr><td><b>Дата отправки</b></td><td><?=$viData['item']->rdate ? Mailing::getDate($viData['item']->rdate) : "-"?></td></tr>
							<? $arResult = !empty($viData['item']->result) ? unserialize($viData['item']->result) : [] ?>
							<tr><td><b>Результат</b></td><td><pre><?=print_r($arResult)?></pre></td></tr>
							<tr><td><b>Срочное</b></td><td><?=Mailing::getBool($viData['item']->is_urgent)?></td></tr>
							<tr><td><b>Получатель</b></td><td><?=$viData['item']->receiver?></td></tr>
							<tr><td><b>Заголовок письма</b></td><td><?=$viData['item']->title?></td></tr>
							<tr><td colspan="2"><b>Тело письма</b><br><iframe id="iframe-html"></iframe></td></tr>
						</tbody>
					</table>
					<div class="pull-right">
						<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
					</div>			
				</div>
				<div class="hidden-xs col-sm-1 col-md-3"></div>
		</div>
	</div>
	<style type="text/css">
		#iframe-html{
			width: 100%;
			min-height: 600px;
			border: 1px solid #d2d6de;
			border-radius: 3px;
		}
		.template-table{ 
			background-color: #FFFFFF;
			font-size: 14px;
		}
		.template-table td:first-child{ width: 25% }
		.template-table tbody tr td,.template-table tbody tr th{ padding: 5px; }
		.template-table tbody tr:nth-child(odd){ background-color: #f4f4f4 }
	</style>
	<script type="text/javascript">
		jQuery(function($){
			var iframe = document.getElementById('iframe-html'),
					content = <?=json_encode($viData['item']->body)?>;

			iframe = iframe.contentWindow || ( iframe.contentDocument.document || iframe.contentDocument);
			iframe.document.open();
			iframe.document.write(content);
			iframe.document.close();
		});
	</script>
<? endif; ?>