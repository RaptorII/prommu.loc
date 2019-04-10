<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item']): ?>
	<div class="alert danger">Данные отсутствуют</div>
<? else: ?>
	<div class="row">
		<div class="hidden-xs col-sm-1 col-md-3"></div>
		<div class="col-xs-12 col-sm-10 col-md-6">
			<table class="table table-bordered template-table">
				<tbody>
					<tr><td><b>ID</b></td><td><?=$viData['item']->id?></td></tr>
					<tr><td><b>Дата создания</b></td><td><?=CommentsAboutUs::getDate($viData['item']->cdate)?></td></tr>
					<tr><td><b>Пользователь</b></td><td><?=CommentsAboutUs::getUser($viData['item']->id_user)?></td></tr>
					<tr><td><b>Статус</b></td><td><?=CommentsAboutUs::getStatus($viData['item']->is_negative)?></td></tr>
					<tr><td colspan="2"><b>Текст</b><br><div><?=$viData['item']->message?></div></td></tr>
				</tbody>
			</table>
			<div class="pull-right">
				<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
			</div>
	  </div>
	  <div class="hidden-xs col-sm-1 col-md-3"></div>
	</div>
	<style type="text/css">
		.template-table{ 
			background-color: #FFFFFF;
			font-size: 14px;
		}
		.template-table td:first-child{ width: 25% }
		.template-table tbody tr td,.template-table tbody tr th{ padding: 5px; }
		.template-table tbody tr:nth-child(odd){ background-color: #f4f4f4 }
		.template-table div{
			background-color: #ffffff;
			border: 1px solid #dedede;
			padding: 5px;
			border-radius: 5px;
		}
	</style>

<? endif; ?>