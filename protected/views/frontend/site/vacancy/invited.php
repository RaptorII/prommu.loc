<?
	$this->pageTitle = 'Приглашенные';
	$vacLink = MainConfig::$PAGE_VACANCY . DS . $id;
	$this->setBreadcrumbsEx([$viData['vac']['meta_h1'], $vacLink]);
	$this->setBreadcrumbsEx([$this->pageTitle, Yii::app()->request->url]);
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'vacancy/style.css');
?>
<div class="row vacancy_invited">
	<div class="col-xs-12">
		<a href="<?=$vacLink?>" class="vacancy_invited-back"><span><</span>Назад</a>
		<? if(count($viData['invited']['items'])): ?>
			<div class="center">
				<h1><?=$this->pageTitle?></h1>
			</div>
			<div class="vacancy-invited__list">
				<? foreach ($viData['invited']['items'] as $v): ?>
					<div class="vacancy-invited__item">
						<? $arUser = $viData['invited']['users'][$v['user']]; ?>
						<div class="vacancy-invited__item-logo">
							<img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
						</div>
						<div>
							<div class="vacancy-invited__item-title"><?=$arUser['name']?></div>
							<div class="vacancy-invited__item-link">
								<a href="<?=$arUser['profile']?>" class="prmu-btn prmu-btn_small">
									<span>Профиль</span>
								</a>
							</div>
						</div>
						<div class="vacancy-invited__item-right center">
							<div>Дата приглашения: <b><?=$v['date']?></b></div>
							<div>Статус: <b><?=$v['status'] ? 'Отправлено' : 'Ожидание'?></b></div>
							<div>Тип: <b><?=$v['type']?></b></div>
						</div>
					</div>
				<? endforeach; ?>
			</div>
			<div class="vacancy-invited__pages">
				<? $this->widget('CLinkPager', array(
						'pages' => $viData['invited']['pages'],
						'htmlOptions' => ['class'=>'paging-wrapp'],
						'firstPageLabel' => '1',
						'prevPageLabel' => 'Назад',
						'nextPageLabel' => 'Вперед',
						'header' => ''
				)); ?>		
			</div>	
		<? else: ?>
			<div class="center">
				<h1>На эту вакансию вы пока не приглашали соискателей</h1>
				<div class="invited-list__services">
					<a href="<?=MainConfig::$PAGE_ORDER_SERVICE . "?id=$id&service=email"?>" class="prmu-btn prmu-btn_normal">
						<span>EMAIL рассылка</span>
					</a>
					<a href="<?=MainConfig::$PAGE_ORDER_SERVICE . "?id=$id&service=sms"?>" class="prmu-btn prmu-btn_normal">
						<span>СМС рассылка</span>
					</a>
					<a href="<?=MainConfig::$PAGE_ORDER_SERVICE . "?id=$id&service=push"?>" class="prmu-btn prmu-btn_normal">
						<span>Push уведомления</span>
					</a>
				</div>
			</div>
			
		<? endif; ?>
	</div>
</div>
  

<?
/*
    echo '<pre>';
    print_r($viData['invited']); 
    echo '</pre>';
    */
?>