<?php
	$pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
	$point = Yii::app()->getRequest()->getParam('point');
  $this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array($viData['title'], $pLink),
    array(
    	'ВЫБОР ПОЛЬЗОВАТЕЛЕЙ', 
    	$pLink . '/users-select' . '/' . $point
    )
  );
  $this->setPageTitle($viData['title']);
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/users-select.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/users-select.js', CClientScript::POS_END);
?>
<div class="row project">
	<div class="col-xs-12">
		<h2 class="project__title">ВЫБОР ПОЛЬЗОВАТЕЛЕЙ<span></span></h2>
		<table class="index-table">
			<tr>
				<td><b><?=$viData['point']['city'] ?></b></td>
			</tr>
			<? if(isset($viData['point']['ismetro'])): ?>
				<tr>
					<td><?=$viData['point']['metro'] ?></td>
				</tr>
			<? endif; ?>
			<tr>
				<td><?=$viData['point']['locname'] ?></td>
			</tr>
			<tr>
				<td><?=$viData['point']['locindex'] ?></td>
			</tr>
			<tr>
				<td><?=$viData['point']['date'] . ' ' . $viData['point']['time']?></td>
			</tr>
		</table>
		<br>
		<br>
		<form action="" method="POST" id="select-form">

			<div class="row">
				<div class="col-xs-12 users-select__list">
					<div class="row">
						<? foreach ($viData['users'] as $user): ?>
							<div class="col-xs-12 col-sm-4 col-md-3">
								<div class="users-select__item <?=(!$user['point']?'disable':'')?>">
									<img src="<?=$user['src']?>">
									<span><?=$user['name']?></span>
								</div>
								<input 
									type="checkbox" 
									name="user[]" 
									value="<?=$user['id_user']?>" 
									id="user-<?=$user['id_user']?>"
									<?=($user['status']&&($user['point']==$point))?'checked':''?>>
								<label for="user-<?=$user['id_user']?>"></label>
							</div>
						<? endforeach; ?>
					</div>
				</div>
			</div>
			<div class="project__all-btns">
				<span class="save-btn" id="save-btn">СОХРАНИТЬ</span>
				<a class="save-btn" href="<?=$pLink?>">НАЗАД</a>
			</div>
		</form>
	</div>
</div>