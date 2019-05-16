<? 
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/css/template.css');
	$model = new Settings;
	$arData = $model->getData();
?>
<div class="row">
	<div class="col-xs-12 settings">
		<h2>Настройки</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-2">
				<ul class="nav user__menu" role="tablist" id="tablist">
					<li class="active">
						<a href="#tab_main" aria-controls="tab_main" role="tab" data-toggle="tab">Общее</a>
					</li>
					<li>
						<a href="#tab_cache" aria-controls="tab_cache" role="tab" data-toggle="tab">Кеш</a>
					</li>
			  </ul>
			</div>
			<?
			// content
			?>
			<div class="col-xs-12 col-sm-9 col-md-10">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_main">
						<h4>Общее</h4>
						<div>
							<form method="POST">
								<?

								?>
								<? foreach ($arData as $obj): ?>
									<? if($obj->code=='register_captcha'): ?>
										<label class="d-label">
											<span><?=$obj->comment?> : </span>
											<input type="checkbox" name="<?=$obj->code?>" value="1" <?=($obj->value ? 'checked="checked"' : '')?>>
										</label>
									<? endif; ?>
									<br>
									<? if($obj->code=='files_root'): ?>
										<label class="d-label">
											<span><?=$obj->comment?> : </span>
											<input type="text" name="<?=$obj->code?>" value="<?=$obj->value?>" class="form-control">
										</label>
									<? endif; ?>
								<? endforeach; ?>
								<br>
								<br>
								<br>
								<div>
									<div class="pull-right">
										<button type="submit" class="btn btn-success d-indent">Сохранить</button>
									</div>
									<div class="clearfix"></div>
								</div>
							</form>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_cache">
						<h4>Кеш</h4>
						<div>
							<div class="btn btn-success" id="clear-cache">Очистить весь кеш</div>
							<br>
							<div class="bs-callout bs-callout-warning">* Внимение: будет удален весь кеш</div>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>
</div>
<script type="text/javascript">
	$(function()
	{
		var bAjax = false;
		$('#clear-cache').click(function(){
			var self = this;

			if(!MainAdmin.bAjaxTimer)
			{
				MainAdmin.loadingButton(self, true);
				MainAdmin.bAjaxTimer = true;
        $.ajax({
            type: 'GET',
            url: '/admin/ajax/settings',
            data: {event: 'clearcache', value:'1'},
            dataType: 'json',
            success: function (result)
            {
            	var className = (!result.error ? 'success' : 'danger');
            	MainAdmin.showMessage(result.message,className);
            	MainAdmin.bAjaxTimer = false;
            	MainAdmin.loadingButton(self, false);
            }

        });
			}
		});
	});
</script>