<? 
  Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'vacancies/emp-list.css');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'vacancies/emp-list.js', CClientScript::POS_END);

	$arUser = $viData['user']['userInfo'];
	$cntComments = $viData['user']['lastComments']['count'][0] + $viData['user']['lastComments']['count'][1];
	$isActiveVacs = strpos(Yii::app()->request->requestUri, DS . MainConfig::$PAGE_VACANCIES)!==false;
?>
<div class='row employer-vacansies-list'>
	<div class="col-xs-12">
		<div class="evl__header">
			<h1 class="evl__header-name"><?=$arUser['name']?></h1>
			<a class='evl__header-btn prmu-btn' href='<?=MainConfig::$PAGE_VACPUB?>'>
				<span>ДОБАВИТЬ ВАКАНСИЮ</span>
			</a>  
		</div>
	</div>
	<?
	//
	?>
	<div class='col-xs-12 col-sm-4 col-lg-3'>
		<div class="evl__logo">
			<img src="<?=Share::getPhoto(3,$arUser['logo']);?>" class="evl-logo__img js-g-hashint" title="<?=$arUser['name']?>">
			<ul class="evl-logo__stars"><li class="full"></li></ul>
			<span class="evl-logo__subtitle"><?=Share::getRating($arUser['rate'], $arUser['rate_neg'])?></span>
			<? if($cntComments): ?>
				<? $link = DS . MainConfig::$PAGE_COMMENTS . DS . $arUser['id_user']; ?>
				<div class="evl-logo__subtitle">
					<span>Отзывы:</span> 
					<span class="evl-logo__review evl-logo__review-red js-g-hashint" title="Отрицательные отзывы">
						<a href="<?=$link?>"><?=$viData['user']['lastComments']['count'][1]?></a>
					</span>
					<span class="evl-logo__review evl-logo__review-green js-g-hashint" title="Положительные отзывы">
						<a href="<?=$link?>"><?=$viData['user']['lastComments']['count'][0]?></a>
					</span> 
					<span class="ppp__logo-allrev">Всего:</span>
					<a href="<?=$link?>"><?=$cntComments?></a>
				</div>
			<? endif; ?>
		</div>
	</div>
	<?
	//
	?>
	<div class='col-xs-12 col-sm-8 col-lg-9'>
		<div class="evl__vacancies">
			<div class="evl__tabs">
				<? if($isActiveVacs): ?>
					<div class='evl__tabs-link actual enable'>
						<span>Активные : <b><?=count($viData['active'])?></b></span>
					</div>
					<a class='evl__tabs-link archive' href='<?=DS . MainConfig::$PAGE_VACARHIVE?>'>
						<span>Архив : <b><?=count($viData['archive'])?></b></span>
					</a>
				<? else: ?>
					<a class='evl__tabs-link actual' href='<?=DS . MainConfig::$PAGE_VACANCIES?>'>
						<span>Активные : <b><?=count($viData['active'])?></b></span>
					</a>
					<div class='evl__tabs-link archive enable'>
						<span>Архив : <b><?=count($viData['archive'])?></b></span>
					</div>
				<? endif; ?>
			</div>
			<hr class="evl-vacancies__line">
			<?
			//
			?>
			<div class="evl-vacancies__list">
				<? if(count($viData['items'])): ?>
					<? foreach ($viData['items'] as $v): ?>
						<div class='evl-vacancies__item'>
							<a class='evl-vacancies__item-name' href='<?= MainConfig::$PAGE_VACANCY . DS . $v['id'] ?>'><?=$v['title']?></a>
							<div class="evl-vacancies__item-info">
								<div class="vac-item__info-item">
									<span>Отклики: 
										<a 
											class="js-g-hashint"
											title="Отклики детально"
											href="<?=MainConfig::$PAGE_VACANCY . DS . $v['id'] . DS . MainConfig::$VACANCY_RESPONDED?>"
											><?=$v['responded']?></a>
									</span>
									<span>Просмотры:
										<a 
											class="js-g-hashint"
											title="Просмотры детально"
											href="<?=MainConfig::$PAGE_ANALYTICS?>"
											><?=$viData['termostat'][$v['id']]['count']?></a>
									</span>
								</div>
								<? if($isActiveVacs): ?>
									<div class="vac-item__info-item">Состояние: <b><?=$v['vacancy_state']?></b></div>
									<? if($v['left_days_cnt']): ?>
										<div class="vac-item__info-item">До окончания: <b><?=$v['left_days']?></b></div>
									<? endif; ?>
									<? if($v['need_rating']): ?>
										<div class="vac-item__info-item">
											<a href="<?=MainConfig::$PAGE_REVIEWS?>" class="prmu-btn">
												<span>Оценить персонал</span>
											</a>
										</div>
									<? endif; ?>
								<? endif; ?>
								<? if($isActiveVacs && $v['ismoder'] && !$v['archive_date']): // только для промодерированных ?>
	                <div class="evl-vacancies__item-btns">
	                  <div class="evl__service-btn">Услуги для вакансии</div>
	                  <div class="evl__service-popup tmpl" data-id="<?=$v['id']?>" data-header="Выбор услуги">
	                    <? if(!$v['ispremium']): // если не установлен ?>
	                      <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$v['id']}&service=premium"?>" class="evl-vacancies__premium">Установить Премиум статус</a>
	                    <? endif; ?>
	                    <? if(substr($v['repost'], 0,1)=='0'): ?>
	                      <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$v['id']}&soc=1&page=0"?>" class="evl-vacancies__vk">Опубликовать в ВК</a>
	                    <? endif; ?>
	                    <? if(substr($v['repost'], 1,1)=='0'): ?>
	                      <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$v['id']}&soc=2&page=0"?>" class="evl-vacancies__fb">Опубликовать в Facebook</a>
	                    <? endif; ?>
	                    <? if(substr($v['repost'], 2,1)=='0'): ?>
	                      <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$v['id']}&soc=3&page=0"?>" class="evl-vacancies__tl">Опубликовать в Telegram</a>
	                    <? endif; ?>
	                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$v['id']}&service=sms"?>" class="evl-vacancies__sms">Произвести СМС рассылку</a>
	                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$v['id']}&service=email"?>" class="evl-vacancies__email">Произвести EMAIL рассылку</a>
	                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$v['id']}&service=push"?>" class="evl-vacancies__push">PUSH уведомления</a>
	                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$v['id']}&service=outsourcing"?>" class="evl-vacancies__atsrc">Аутсорсинг</a>
	                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$v['id']}&service=outstaffing"?>" class="evl-vacancies__outstf">Аутстаффинг</a>
	                  </div> 
	                  <? if(!empty($viData['projects'][$v['id']])): // проверяем наличие привязанного проекта ?>
	                    <a class="evl__to-project-btn" href="<?=MainConfig::$PAGE_PROJECT_LIST . DS . $viData['projects'][$v['id']] ?>">Привязанный проект</a>
	                  <? else: ?>
	                    <div class="evl__to-project-btn" data-id="<?=$v['id']?>" id="to-project-btn">Сделать проектом</div>
	                  <? endif; ?>
	                </div>
								<? endif; ?>
							</div>
						</div>
					<? endforeach; ?>
				<? else: ?>
					<span class="evl-vacancies__empty">Пока нет вакансий</span>
				<? endif; ?>  
			</div>
      <? $this->widget('CLinkPager', array(
          'pages' => $viData['pages'],
          'htmlOptions' => ['class' => 'paging-wrapp'],
          'firstPageLabel' => '1',
          'prevPageLabel' => 'Назад',
          'nextPageLabel' => 'Вперед',
          'header' => '',
      )) ?>
      <?
      //      COMMENTS
      ?>
      <? if($isActiveVacs): ?>
				<? if($cntComments):?>
					<span class="upp__subtitle">Отзывы</span>
					<hr class="upp__line">
					<div class="upp__reviews-cnt">Отрицательных: <span class="upp__review upp__review-red"><a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$arUser['id_user']?>" class="upp__link"><?=$viData['rate']['lastComments']['count'][1]?></a></span></div>
					<div class="upp__reviews-cnt">Положительных: <span class="upp__review upp__review-green"><a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$arUser['id_user']?>" class="upp__link"><?=$viData['rate']['lastComments']['count'][0]?></a></span></div>

					<? if($cntComments>3): ?>
					<a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$arUser['id_user']?>" class="upp__btn-rating-list">все отзывы</a>
					<? endif; ?>
					<? else: ?>
					<span class="upp__subtitle">Отзывы отсутствуют</span>
					<? endif;?>
      <? endif;?>
    </div>
  </div>
</div>