<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
?>

<div class="row project">
	<div class="col-xs-12">
		<? require __DIR__ . '/project-nav.php'; ?>
  </div>
</div>

<div class="project__module">???</div>
