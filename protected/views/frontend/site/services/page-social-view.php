<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/services/services-duplicate-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/services/services-duplicate-page.js', CClientScript::POS_END);
?>
<div class="col-xs-12 services-duplication">
	<h1 class="sd__title">Дублирование вакансий в группах Промму</h1>
	<div class="sd__tabs">
		<?php 
			if(sizeof($viData['vacs'])):
				$tab = Yii::app()->getRequest()->getParam('tab'); 
				if($tab=='info'):
		?>
				<a class='sd__tabs-link' href='?tab='>Выбор и размещение</a>
				<span class='sd__tabs-link active'>Описание</span>
			<?php else: ?>
				<span class='sd__tabs-link active'>Выбор и размещение</span>
				<a class='sd__tabs-link' href='?tab=info'>Описание</span></a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php if($tab=='info' || !sizeof($viData['vacs'])): ?>
		<p>Если Вы хотите, в кратчайшие сроки найти подходящий персонал для своей вакансии, мы предлагаем опубликовать эту вакансию в наших группах на популярных социальных ресурсах. Эта процедура потребует минимум действий.<br>Для этого Вам необходимо:</p>
		<ul>
			<li>Перейти на <a href="<?=MainConfig::$PAGE_VACPUB?>" target="_blank">страницу публикации вакансии</a>
				<img src="/theme/pic/services-page/duplication-1.jpg">
			</li>
			<li>Заполнить поля

			</li>
			<li>В нижней части страницы в разделе "Опубликовать в социальных сетях" выбрать ресурс для публикации
				<img src="/theme/pic/services-page/duplication-2.jpg">
			</li>
			<li>Нажать кнопку "Сохранить"</li>
		</ul>
		<br>
		<p>После модерации Ваша вакансия появится в наших группах в выбранных Вами социальных сетях (<a href="https://vk.com/vremennaya_rabota" target="_blank">Вконтакте</a> или <a href="https://www.facebook.com/prommu/" target="_blank">Facebook</a>).</p>
		<br>
		<p><span>С найлучшими пожеланиями команда Промму!</span></p>
	<?php else: ?>
		<form action="" method="POST">
			<div class="sd__vac-list">
				<?php foreach($viData['vacs'] as $v): ?>
					<div class="sd__vacancy" data-id="<?=$v['id']?>" data-repost="<?=$v['repost']?>">
						<div class="sd__vacancy-content">
							<span class="sd__vacancy-name" target="_blank"><?=$v['title']?></span>
							<div class="sd__vacancy-inputs">
								<input type="checkbox" name="soc[<?=$v['id']?>][vk]" id="repost-vk-<?=$v['id']?>" value="1" <?=(substr($v['repost'], 0,1)=='1' ? 'checked disabled' : '')?>>
								<label class="sd__vacancy-soc sd__vacancy-vk js-g-hashint" for="repost-vk-<?=$v['id']?>" title="<?=(substr($v['repost'], 0,1)=='1' ? 'Уже опубликовано' : 'Опубликовать в ВК')?>"></label>
								<input type="checkbox" name="soc[<?=$v['id']?>][fb]" id="repost-fb-<?=$v['id']?>" value="1" <?=(substr($v['repost'], 1,1)=='1' ? 'checked disabled' : '')?>>
								<label class="sd__vacancy-soc sd__vacancy-fb js-g-hashint" for="repost-fb-<?=$v['id']?>" title="<?=(substr($v['repost'], 1,1)=='1' ? 'Уже опубликовано' : 'Опубликовать в Facebook')?>"></label>
								<input type="checkbox" name="soc[<?=$v['id']?>][tl]" id="repost-tl-<?=$v['id']?>" value="1" <?=(substr($v['repost'],-1)=='1' ? 'checked disabled' : '')?>>
								<label class="sd__vacancy-soc sd__vacancy-tl js-g-hashint" for="repost-tl-<?=$v['id']?>" title="<?=(substr($v['repost'],-1)=='1' ? 'Уже опубликовано' : 'Опубликовать в Telegram')?>"></label>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<button class="repost-button">РАЗМЕСТИТЬ</button>
		</form>
	<?php endif; ?>
	<br>
	<br>
</div>