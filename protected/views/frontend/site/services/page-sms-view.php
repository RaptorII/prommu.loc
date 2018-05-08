<?
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/services/services-sms-page.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . '/theme/js/services/services-sms-page.js', CClientScript::POS_END);

if(!isset($viData['step'])):?>
	<div class="row">
		<div class="col-xs-12 sms-service">
			<?php if( $viData['vacs'] ): ?>
				<h2 class="sms-service__title">ВЫБЕРИТЕ ВАКАНСИЮ ДЛЯ СМС ИНФОРМИРОВАНИЯ!</h2>
				<form action="" method="POST">
					<div class="smss-vacancies__list">
					<?php foreach ($viData['vacs'] as $key => $val): ?>
						<label class="smss-vacancies__item">
							<div class="smss-vacancies__item-bg">
								<span class="smss-vacancies__item-title"><?=$val['title'] ?></span>
							</div>
							<input type="checkbox" name="vacancy" value="<?=$val['id']?>" class="smss-vacancies__item-input">
						</label>			
					<?php endforeach; ?>
					</div>
					<input type="hidden" name="vacancy" id="vacancy">
					<button class="sms-service__btn payment-button" id="sms-vac-btn">Выбрать персонал для СМС рассылки</button>
				</form>	
			<?php else: ?>
				<h2 class="sms-service__title">У ВАС НЕТ ВАКАНСИЙ</h2>
				<a href="<?=MainConfig::$PAGE_VACPUB?>" class="sms-service__btn visible">ДОБАВИТЬ ВАКАНСИЮ</a>
			<?php endif; ?>
		</div>
	</div>
<?
/*
*		Выбор соискателей
*/
?>
<?php elseif($viData['step']==2): ?>
	<?
		Yii::app()->getClientScript()->registerCssFile("/theme/css/select2.min.css");
		Yii::app()->getClientScript()->registerScriptFile('/theme/js/select2.min.js', CClientScript::POS_END);
		$viData['app_idies'] = array();
		foreach ($viData['workers']['promos'] as $key => $idus)
			$viData['app_idies'][] = intval($idus['id_user']);	
	?>
	<script type="text/javascript">
		var arIdies = <?=json_encode($viData['app_idies'])?>;
		var AJAX_GET_PROMO = "<?=MainConfig::$PAGE_SERVICES_SMS?>";	
	</script>
	<div class='row'>
		<?
		/*
		*		FILTER
		*/
		?>
		<div class="smss__veil"></div>
		<div class='col-xs-12 col-sm-4 col-md-3'>
			<form action="" id="F1Filter" method="get">
				<div class='filter'>
					<div class='smss__filter-block filter-cities'>
						<div class='smss__filter-name opened'>Город</div>
						<div class='smss__filter-content opened'>
							<select class='templatingSelect2'  multiple='multiple' name='cities[]' id="ank-srch-cities">
								<?php foreach ($viData['cities'] as $city): ?>
									<option value='<?=$city['id']?>'><?=$city['name']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class='smss__filter-block filter-sex'>
						<div class='smss__filter-name opened'>Пол</div>
						<div class='smss__filter-content opened'>
							<div class='right-box'>
								<input name='sm' type='checkbox' value='1' class="smss__checkbox-input" id="psa-sex-m">
								<label class="smss__checkbox-label" for="psa-sex-m">Мужской</label>
								<input name='sf' type='checkbox' value='1' class="smss__checkbox-input" id="psa-sex-w">
								<label class="smss__checkbox-label" for="psa-sex-w">Женский</label>
							</div>
						</div>
					</div>
					<div class='smss__filter-block filter-additional'>
						<div class='smss__filter-name opened'>Дополнительно</div>
						<div class='smss__filter-content opened'>
							<div class='right-box'>
								<input name='mb' type='checkbox' value='1' class="smss__checkbox-input" id="psa-med">
								<label class="smss__checkbox-label" for="psa-med">Наличие медкнижки</label>
								<input name='avto' type='checkbox' value='1' class="smss__checkbox-input" id="psa-auto">
								<label class="smss__checkbox-label" for="psa-auto">Наличие автомобиля</label>
								<input name='smart' type='checkbox' value='1' class="smss__checkbox-input" id="psa-smart">
								<label class="smss__checkbox-label" for="psa-smart">Наличие смартфона</label>
							</div>
						</div>
					</div>
		  		</div>
			</form>
		</div>
		<?
		/*
		*		CONTENT
		*/
		?>
		<div class='col-xs-12 col-sm-8 col-md-9 sms-service'>
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
					<span class="smss-workers__form-workers">Выбрано получателей: <span id="mess-wcount">0</span></span>
					<button type="submit" class="smss-workers__form-btn off" id="mess-form-btn">Создать выбранному персоналу СМС</button>
					<input type="hidden" name="workers" id="mess-workers">
					<input type="hidden" name="workers-count" id="mess-wcount-inp" value="0">
					<input type="hidden" name="sms-count" id="mess-mcount-inp" value="1">
				</form>
			</div>
			<div id="content">
				<div class='questionnaire'>
					<div>
						<?=$this->ViewModel->declOfNum($viData['app_count'], array('Найдена', 'Найдено', 'Найдено'))?>
						<b><?=$viData['app_count']?></b>
						<?=$this->ViewModel->declOfNum($viData['app_count'], array('Анкета', 'Анкеты', 'Анкет'))?>
					</div>
				</div>
				<div class='row vacancy table-view'>
					<?if( $viData['workers']['promo'] ):?>
						<?$i=1;?>
						<?foreach ($viData['workers']['promo'] as $item):?>
							<div class='col-xs-12 col-sm-6 col-md-4'>
								<?
									$G_NOLIKES = 1;
									$G_ALT = "Соискатель {$item['firstname']} {$item['lastname']} prommu.com ";
									$G_LOGO_LINK = MainConfig::$PAGE_PROFILE_COMMON . DS . $item['id_user'];
									if($item['sex'] === '1'){
										$G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO : $item['photo'] . '400.jpg');
									}
									else 
										$G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO_F : $item['photo'] . '400.jpg');
									$G_COMP_FIO = $item['firstname'] . ' ' . $item['lastname'] . ', ' . $item['age'];
									$G_RATE_POS = $item['rate'];
									$G_RATE_NEG = $item['rate_neg'];
									$G_COMMENTS_POS = $item['comm'];
									$G_COMMENTS_NEG = $item['commneg'];
									$G_TMPL_PH1 = '';
									if( $item['ishasavto'] === '1' ) $G_TMPL_PH1 = "<div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>";
									if( $item['ismed'] === '1' ) $G_TMPL_PH1 .= '<div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>';
									$G_TMPL_PH1 = "<div class='med-avto'>{$G_TMPL_PH1}</div>";
									include $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user' . DS . MainConfig::$VIEWS_COMM_LOGO_TPL . ".php";
								?>
								<input type="checkbox" name="promo[]" value="<?=$item['id_user']?>" class="promo_inp" id="promo<?=$item['id_user']?>">
								<label class="smss-promo__label" for="promo<?=$item['id_user']?>"></label>
							</div>
							<?if($i % 2 == 0):?>
								<div class="clear visible-sm"></div>
							<?endif?>
							<?if( $i % 3 == 0 ):?>
								<div class="clear visible-md visible-lg"></div>
							<?endif?>
							<?$i++;?>
						<?endforeach?>
					<?else:?>
						Нет подходящих соискателей
					<?endif;?>
				</div>
				<br>
				<br>
				<div class='paging-wrapp hidden-xs'>
				<?// display pagination
					$this->widget('CLinkPager', array(
							'pages' => $viData['pages'],
							'htmlOptions' => array('class' => 'paging-wrapp'),
							'firstPageLabel' => '1',
							'prevPageLabel' => 'Назад',
							'nextPageLabel' => 'Вперед',
							'header' => ''
						)
					)?>
				</div>
			</div>
		</div>
	</div>
<?
/*
*		Выбор соискателей
*/
?>
<?php elseif($viData['step']==3): ?>
	<?php 
		$appCount = Yii::app()->getRequest()->getParam('workers-count'); 
		define('SMS_SERVICE_PRICE', '10');
	?>
	<div class="row">
		<div class="col-xs-12 sms-service">
			<form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="smss__result-form">
				<h1 class="smss-result__title">РАСЧЕТ УСЛУГИ</h1>
				<table class="smss-result__table">
					<tr>
						<td>Количество получателей</td>
						<td><?=$appCount?></td>
					</tr>
					<tr>
						<td>Стоимость отправки одного сообщения</td>
						<td><?=SMS_SERVICE_PRICE?>руб</td>
					</tr>
				</table>
				<?$result = $appCount * SMS_SERVICE_PRICE;?>
				<span class="smss-result__result"><?echo $appCount . ' * ' . SMS_SERVICE_PRICE . ' = ' . $result . 'рублей'?></span>
				<button class="smss-result__btn">Перейти к оплате</button>
				<input type="hidden" name="vacsms" value="<?=$viData['vacancy']?>">
				<input type="hidden" name="app_count" value="<?=$appCount?>">
				<input type="hidden" name="user" value="<?=Yii::app()->getRequest()->getParam('workers')?>">
				<input type="hidden" name="text" value="<?=Yii::app()->getRequest()->getParam('message')?>">
				<input type="hidden" name="account" value="<?=Share::$UserProfile->id?>">
				<input type="hidden" name="sms" value="1">
			</form>
		</div>
	</div>
<?php endif; ?>