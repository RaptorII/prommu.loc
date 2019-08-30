<?
	$rq = Yii::app()->getRequest();
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-sms-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-sms-page.js', CClientScript::POS_END);

if(!$rq->getParam('vacancy')):?>
	<div class="row">
		<div class="col-xs-12">
			<?php if(sizeof($viData['vacs'])): ?>
				<h2 class="sms-service__title">ВЫБЕРИТЕ ВАКАНСИЮ ДЛЯ СМС ИНФОРМИРОВАНИЯ!</h2>
				<form action="" method="POST">
					<div class="smss-vacancies__list">
					<?php foreach ($viData['vacs'] as $key => $val): ?>
						<label class="smss-vacancies__item">
							<div class="smss-vacancies__item-bg">
								<span class="smss-vacancies__item-title"><?=$val['title'] ?></span>
							</div>
							<input type="radio" name="vacancy" value="<?=$val['id']?>" class="smss-vacancies__item-input">
						</label>			
					<?php endforeach; ?>
					</div>
					<input type="hidden" name="vacancy" id="vacancy">
					<button class="service__btn prmu-btn prmu-btn_normal pull-right" id="vac-btn">
						<span>Выбрать персонал для СМС рассылки</span>
						</button>
				</form>	
			<?php else: ?>
				<br>
				<h2 class="sms-service__title center">У ВАС НЕТ АКТИВНЫХ ВАКАНСИЙ</h2>
				<a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn visible prmu-btn prmu-btn_normal"><span>ДОБАВИТЬ ВАКАНСИЮ</span></a>
			<?php endif; ?>
		</div>
	</div>
<?
/*
*		Выбор соискателей
*/
?>
<?php elseif(!$rq->getParam('workers')): ?>
	<script type="text/javascript">
		var arSelectCity = <?=json_encode($viData['workers']['city'])?>;
		var AJAX_GET_PROMO = "<?='/user'.MainConfig::$PAGE_SERVICES_SMS?>";	
	</script>
	<div class='row'>
		<?
		/*
		*		FILTER
		*/
		?>
		<div class="filter__veil"></div>
		<div class='col-xs-12 col-sm-4 col-md-3'>
			<div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
			<form action="" id="promo-filter" method="get"><? require_once 'ankety-filter.php'; ?></form>
		</div>
		<?
		/*
		*		CONTENT
		*/
		?>
		<div class='col-xs-12 col-sm-8 col-md-9'>
			<div class='view-radio clearfix'>
				<h1 class="main-h1">Выбрать персонал для СМС информирования</h1>
				<form action="" method="POST" id="mess-form" class="smss-workers__form">
					<div class="smss__all">
						<span class="smss-all__name">Выбрать всех</span>
						<div class="smss-all__checkbox">
							<input id="mess-all" name="all-workers" type="checkbox" value="true">
							<label for="mess-all" class="smss-all__checkbox-label">
								<div class="smss-all__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
							</label>
						</div>
					</div>
					<div class="smss-workers__form-area">
						<div class="smss-workers__form-desc">Размер СМС сообщения ограничен GSM стандартами <span>[Символов : <span id="mess-mlength"></span>]</span></div>
						<textarea name="message" id="mess-text" placeholder="Текст сообщения"></textarea>
					</div>
					<div class="clearfix"></div>
					<span class="smss-workers__form-workers">Выбрано получателей: <span id="mess-wcount">0</span></span>
					<button type="submit" class="smss-workers__form-btn off prmu-btn prmu-btn_normal" id="mess-form-btn">
						<span>Создать выбранному персоналу СМС</span>
					</button>
					<input type="hidden" name="workers" id="mess-workers">
					<input type="hidden" name="workers-count" id="mess-wcount-inp" value="0">
					<input type="hidden" name="sms-count" id="mess-mcount-inp" value="1">
					<input type="hidden" name="vacancy" value="<?=$rq->getParam('vacancy')?>">
				</form>
			</div>
			<div id="promo-content"><? require_once 'ankety-ajax.php'; ?></div>
		</div>
	</div>
<?
/*
*		Переход к оплате
*/
?>
<?php else: ?>
	<?php $appCount = $rq->getParam('workers-count'); ?>
	<div class="row">
		<div class="col-xs-12">
			<form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="smss__result-form">
				<h1 class="smss-result__title">РАСЧЕТ УСЛУГИ</h1>
				<table class="smss-result__table">
					<tr>
						<td>Количество получателей</td>
						<td><?=$appCount?></td>
					</tr>
					<tr>
						<td>Стоимость отправки одного сообщения</td>
						<td><?=$viData['price']?>руб</td>
					</tr>
				</table>
				<?$result = $appCount * $viData['price'];?>
				<span class="smss-result__result"><?echo $appCount . ' * ' . $viData['price'] . ' = ' . $result . 'рублей'?></span>
        <? $this->renderPartial('../site/services/legal-fields',['viData'=>$viData]); ?>
				<br>
				<br>
				<div class="center">
					<button class="prmu-btn prmu-btn_normal" id="sms_pay_btn">
						<span>Перейти к оплате</span>
					</button>
				</div>
				<input type="hidden" name="vacancy" value="<?=$rq->getParam('vacancy')?>">
				<input type="hidden" name="app_count" value="<?=$appCount?>">
				<input type="hidden" name="users" value="<?=$rq->getParam('workers')?>">
				<input type="hidden" name="text" value="<?=$rq->getParam('message')?>">
				<input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
				<input type="hidden" name="service" value="sms-informing-staff">
			</form>
		</div>
	</div>
<?php endif; ?>