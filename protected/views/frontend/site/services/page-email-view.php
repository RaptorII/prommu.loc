<?
	$rq = Yii::app()->getRequest();
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-email-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-email-page.js', CClientScript::POS_END);
if(!$rq->getParam('vacancy')):?>
	<div class="row">
		<div class="col-xs-12">
			<?php if(sizeof($viData['vacs'])): ?>
				<h2 class="service__title">ВЫБЕРИТЕ ВАКАНСИЮ ДЛЯ ИНФОРМИРОВАНИЯ ПО EMAIL!</h2>
				<form action="" method="POST">
					<div class="service__vac-list">
					<?php foreach ($viData['vacs'] as $key => $val): ?>
						<label class="service-vac__item">
							<div class="service-vac__item-bg">
								<span class="service-vac__item-title"><?=$val['title'] ?></span>
							</div>
							<input type="radio" name="vacancy" value="<?=$val['id']?>" class="service-vac__item-input">
						</label>			
					<?php endforeach; ?>
					</div>
					<input type="hidden" name="vacancy" id="vacancy">
					<button class="service__btn prmu-btn prmu-btn_normal pull-right" id="vac-btn"><span>Выбрать персонал для EMAIL рассылки</span></button>
				</form>	
			<?php else: ?>
				<br>
				<h2 class="service__title center">У ВАС НЕТ АКТИВНЫХ ВАКАНСИЙ</h2>
				<a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn visible prmu-btn prmu-btn_normal"><span>ДОБАВИТЬ ВАКАНСИЮ</span></a>
			<?php endif; ?>
		</div>
	</div>
<?php elseif(!$rq->getParam('users')): ?>
	<?
	//    Выбор соискателей
	//
	?>
	<script type="text/javascript">
		var arSelectCity = <?=json_encode($viData['workers']['city'])?>;
		var AJAX_GET_PROMO = "<?='/user'.MainConfig::$PAGE_SERVICES_EMAIL?>";
	</script>
	<div class='row'>
		<?
		//		FILTER
		?>
		<div class="filter__veil"></div>
		<div class='col-xs-12 col-sm-4 col-md-3'>
			<div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
			<form action="" id="promo-filter" method="get"><? require_once 'ankety-filter.php'; ?></form>
		</div>
		<?
		//		CONTENT
		?>
		<div class='col-xs-12 col-sm-8 col-md-9'>
			<div class='view-radio clearfix'>
				<h1 class="main-h1">Выбрать персонал для EMAIL информирования</h1>
				<form action="" method="POST" id="workers-form">
					<?php if($viData['price']!=0 && $viData['price']<1): ?>
						<div class="price-warning">Стоимость отправки сообщения для одного соискателя составляет <b><?=$viData['price']?> руб.</b><br/>Сумма минимальной платежной операции - <b>1 руб.</b></div>
					<?php endif; ?>
					<span class="workers-form__cnt">Выбрано получателей: <span id="mess-wcount">0</span></span>
					<div class="service__switch">
						<span class="service__switch-name">Выбрать всех</span>
						<input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
						<label for="all-workers">
							<span data-enable="вкл." data-disable="выкл."></span>
						</label>
					</div>
					<button type="submit" class="prmu-btn prmu-btn_normal off" id="workers-btn"><span>Отправить приглашение</span></button>
					<input type="hidden" name="users" id="mess-workers">
					<input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
					<input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
					<input type="hidden" name="vacancy" value="<?=$rq->getParam('vacancy')?>">
				</form>
			</div>
			<div id="promo-content"><? require_once 'ankety-ajax.php'; ?></div>
		</div>
	</div>
<?
//		Выбор соискателей
?>
<?php else: ?>
	<?php 
		$appCount = $rq->getParam('users-cnt');
		$vacancy = $rq->getParam('vacancy');
		?>
	<div class="row">
		<div class="col-xs-12">
			<form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="smss__result-form">
				<h1 class="smss-result__title">ТЕКСТ РАССЫЛКИ</h1>
				<div class="smss-result__text">
					Добрый день, <ФИО><br>
					Работодатель <span><?=$viData['user']['name']?>
					<a href="<?=Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . $viData['user']['id']?>">
						<img src="<?=$viData['user']['src']?>">
					</a></span><br> приглашает Вас на вакансию <a href="<?=Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacancy?>">&laquo;<?=$viData['vac']['title']?>&raquo;</a>
					<? if(sizeof($viData['vac']['posts'])==1): ?>
						на должность:<br><?=reset($viData['vac']['posts'])['name']?><br>
					<? else: ?>
						на должности:<br> <?
						foreach ($viData['vac']['posts'] as $k => $v)
								echo  ($k+1) . ') ' . $v['name'] . ($k<(sizeof($viData['vac']['posts'])-1)?';<br>':'');
						?>
					<? endif; ?>
						Заработная плата:<br> 
						<? if( $viData['vac']['salary_hour'] > 0 )
							echo '- ' . $viData['vac']['salary_hour'] . ' руб/час<br/>';
						if( $viData['vac']['salary_week'] > 0 )
							echo '- ' . $viData['vac']['salary_week'] . ' руб/неделю<br/>';
						if( $viData['vac']['salary_month'] > 0 )
							echo '- ' . $viData['vac']['salary_month'] . ' руб/месяц<br/>';
						if( $viData['vac']['salary_visit'] > 0 )
							echo '- ' . $viData['vac']['salary_visit'] . ' руб/посещение<br/>';
						?><br>
						Если интересно - за более детальной информацией переходи по <a class="smss-result__text-link" href="<?=Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacancy?>">ссылке</a>
				</div>
				</br></br>
				<h1 class="smss-result__title">Стоимость услуги</h1>
				<table class="smss-result__table">
					<tr>
						<td>Количество получателей</td>
						<td><?=$appCount?></td>
					</tr>
					<tr>
						<td>Стоимость рассылки</td>
						<td><?=$viData['price']?> руб.</td>
					</tr>
				</table>
				<span class="smss-result__result"></span>
        <? $this->renderPartial('../site/services/legal-fields',['viData'=>$viData]); ?>
        <br>
        <br>
				<div class="center">
					<button class="prmu-btn prmu-btn_normal" id="email_pay_btn">
						<span>Перейти к оплате</span>
					</button>
				</div>
				<input type="hidden" name="vacancy" value="<?=$vacancy?>">
				<input type="hidden" name="users-cnt" value="<?=$appCount?>">
				<input type="hidden" name="users" value="<?=$rq->getParam('users')?>">
				<input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
				<input type="hidden" name="service" value="email-invitation">
			</form>
		</div>
	</div>
<?php endif; ?>