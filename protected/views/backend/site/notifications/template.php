<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($viData['id'])): ?>
	<div class="alert danger">Данные отсутствуют</div>
<? else: ?>
	<?
        $codeMirror = Yii::app()->request->baseUrl . '/plugins/codemirror/';
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'lib/codemirror.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/xml/xml.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'addon/edit/matchbrackets.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/javascript/javascript.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/css/css.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/vbscript/vbscript.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/clike/clike.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/php/php.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/htmlmixed/htmlmixed.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerCssFile($codeMirror . 'lib/codemirror.css');


    $item = $viData['item'];
		!is_object($item) && $item = (object) [];

		if(empty($item->body))
			$item->body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="margin:0;background-color:#FFFFFF">
	<table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0; padding:0" width="100%">
  		<tr><td height="100%">
			<table width="600" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;padding:0" width="100%">
				<tr>
					<td>
						<table background="https://prommu.com/theme/pic/letter-analitics/analitics-header-bg.png" width="600" height="84" border="0" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0"><tr><td>
								<a href="https://prommu.com" target="_blank" title="Prommu.com" style="width:104px; display:block; margin:18px auto 7px;">
									<img src="https://prommu.com/theme/pic/letter-analitics/analitics-header.jpg" alt="Prommu.com" width="104" height="30" border="0" style="display:block">
								</a>
								<span style="display:block; text-align:center; color:#565656; font-size:14px; line-height:normal; font-family:Arial,Helvetica,sans-serif">Сервис №1 в поиске временной работы и персонала для BTL и Event-мероприятий</span>
							</td></tr>
						</table>
					</td>
				</tr>
				<tr><td><span style="display:block; text-align:center; color:#7f7f7f; font-size:14px; line-height:normal; padding:14px 5px; border-top:1px solid #abb837; border-bottom:1px solid #abb837; font-family:Arial,Helvetica,sans-serif">PROMMU - это всегда актуальная и качественная база вакансий и современные сервисы для поиска работы и персонала</span></td></tr>
				<tr><td style="font-family:Arial,Helvetica,sans-serif;color:#565656;padding:15px;">
					#CONTENT#
				</td></tr>
				<tr>
					<td style="padding: 19px 0 5px 80px;border-top: 1px solid #e5e5e5">
						<a href="https://prommu.com" target="_blank" title="Prommu.com">
							<img src="https://prommu.com/theme/pic/letter-analitics/analitics-footer.png" alt="Prommu.com" width="78" height="22" border="0" style="display:block">
						</a>
					</td>
				</tr>
				<tr>
					<td style="padding: 0 0 0 80px"><span style="color:#636363;font-size:14px;line-height: normal;font-family: Arial,Helvetica,sans-serif;">С наилучшими пожеланиями, команда Промму</span></td>
				</tr>
			</table>
  		</td></tr>
	</table>
</body>
</html>';
	?>
	<? if($viData['error'] && isset($viData['messages'])): ?>
		<div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
	<? endif; ?>
	<div class="row">
		<div class="col-xs-12">
			<form action="" method="POST">
				<div class="row">
					<div class="hidden-xs col-sm-1 col-md-3"></div>
					<div class="col-xs-12 col-sm-10 col-md-6">
						<div class="row">
							<?
							//
							?>
							<div class="col-xs-12 col-md-6">
								<label class="d-label">
									<input type="radio" name="isactive" value="1"
										<?=(($item->isactive=='1' || !isset($item->isactive))?'checked="checked"':'')?>>
									<span>Активный шаблон</span>
								</label>
								<label class="d-label">
									<input type="radio" name="isactive" value="0"
										<?=($item->isactive=='0'?'checked="checked"':'')?>>
									<span>Неактивный шаблон</span>
								</label>
							</div>
							<div class="hidden-xs-12 col-md-6"></div>
							<?
							//
							?>
							<div class="col-xs-12">
								<label class="d-label">
									<span>Название шаблона</span>
									<input type="text" name="name" class="form-control" autocomplete="off" value="<?=$item->name?>">
								</label>
							</div>
						</div>
					</div>
					<div class="hidden-xs col-sm-1 col-md-3"></div>
				</div>
				<?
				//
				?>
				<label class="d-label">
					<span>Тело шаблона</span>
					<textarea name="body" class="d-textarea" id="transform-code"><?=$item->body?></textarea>
				</label>
				<div class="bs-callout bs-callout-warning">В шаблоне обязательно должна стоять константа #CONTENT# , вместо нее будет подставлятся содержимое письма. Рекомендуется делать шаблон табличной версткой и константу #CONTENT# помещать в тег '&lt;td&gt;' с присвоением стилей, необходимых для содержимого письма</div>
				<div class="pull-right">
					<span class="btn btn-success d-indent" id="check-html">Проверить</span>
				</div>
				<iframe id="iframe-html"></iframe>
				<div class="pull-right">
					<a href="<?=$this->createUrl('',['anchor'=>'tab_template'])?>" class="btn btn-success d-indent">Назад</a>
					<button type="submit" class="btn btn-success d-indent">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
	<?
	//
	?>
	<style type="text/css">
		#iframe-html{
			width: 100%;
			min-height: 600px;
			border: 1px solid #d2d6de;
			border-radius: 3px;
		}
	</style>
	<?
	//
	?>
	<script type="text/javascript">
		jQuery(function($){
			var iframe = document.getElementById('iframe-html');
      var myCodeMirror = CodeMirror.fromTextArea(
        document.getElementById('transform-code'),
        {
          lineNumbers: true,
          matchBrackets: true,
          autoCloseBrackets: true,
          //mode: mixedMode,
          mode: "application/x-httpd-php",
          indentUnit: 2
        }
      );

			iframe = iframe.contentWindow || ( iframe.contentDocument.document || iframe.contentDocument);
			iframe.document.open();
			iframe.document.write(myCodeMirror.getValue());
			iframe.document.close();

			$('#check-html').click(function()
			{
				iframe.document.open();
				iframe.document.write(myCodeMirror.getValue());
				iframe.document.close();
			});
		});
	</script>
<? endif; ?>