<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/services/services-duplicate-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/services/services-duplicate-page.js', CClientScript::POS_END);
?>
<div class="col-xs-12 services-duplication">
	<?php if(!isset($viData['price'])): ?>
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
	<?php else: ?>
		<?php
			$vacsCnt = count($_POST['soc']);
			$repostCnt = count($_POST['soc'], COUNT_RECURSIVE) - $vacsCnt;
		?>
		<h1 class="sd__title">РАСЧЕТ УСЛУГИ</h1>
		<form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="result__form">
			<table class="result__table">
				<tr>
					<td>Количество публикуемых вакансий </td>
					<td><?=$vacsCnt?></td>
				</tr>
				<tr>
					<td>Выбранное количество публикаций по вакансиям </td>
					<td><?=$repostCnt?></td>
				</tr>
				<tr>
					<td>Стоимость публикации </td>
					<td><?=$viData['price']?>руб</td>
				</tr>
			</table>
			<?$result = $repostCnt * $viData['price'];?>
			<span class="result__result"><?echo $repostCnt . ' * ' . $viData['price'] . ' = ' . $result . 'рублей'?></span>
			<button class="result__btn">Перейти к оплате</button>
			<?php foreach ($_POST['soc'] as $idvac => $arSoc){ 
					foreach ($arSoc as $soc => $key){ ?>
						<input type="hidden" name="soc[<?=$idvac?>][<?=$soc?>]" value="1">
			<?php }} ?>
			<input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
			<input type="hidden" name="service" value="publication-vacancy-social-net">
		</form>
	<?php endif; ?>
	<br>
	<br>
</div>