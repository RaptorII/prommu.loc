<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/ideas/page-list.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/ideas/page-list.js', CClientScript::POS_END);

	$title = 'Идеи и предложения';
	// устанавливаем title
	$this->pageTitle = $title;
	// устанавливаем h1
	$this->ViewModel->setViewData('pageTitle', '<h1>' . $title . '</h1>');
	// breadcrumbs
	$this->setBreadcrumbsEx(array($title, $_SERVER['REQUEST_URI']));

	$mess = false;
	if(Yii::app()->user->hasFlash('success')){
		$flash = Yii::app()->user->getFlash('success');
		if($flash['event']=='new'){
			$name = '';
			if($flash['type']==1)
				$name = 'Заявка-идея';
			if($flash['type']==2)
				$name = 'Заявка-вопрос';
			if($flash['type']==3)
				$name = 'Заявка-ошибка';

			$mess = array(
				'header' => $name . ' создана',
				'mess' => $name . ' успешно создана. После прохождения модерации она появится на сайте. После появления мы оповестим Вас'
			);
		}
	}
?>
<script type="text/javascript">
	<?php if(is_array($mess)): ?>var messNewIdea = <?=json_encode($mess)?>;<?php endif; ?>
</script>
<div class="row">
	<div class="col-xs-12">
		<?php if(sizeof($viData['ideas'])): ?>
			<div id="ideas-veil"></div>
			<form action="" method="GET" id="ideas-form">
				<div class="ideas__search">
					<input type="text" name="q" placeholder="Поиск Идеи / Предложения">
					<?php if(!$viData['is_guest']): ?>
						<a href="<?=MainConfig::$PAGE_IDEA_NEW?>" class="ideas__btn"><b class="glyphicon glyphicon-plus"></b> Добавить идею/Предложение</a>
					<?php endif; ?>			
				</div>
				<div class="ideas__sort">
					<div class="ideas-sort__left" id="sort-type">
						<div class="ideas__select">
							<span>Тип</span>
							<ul>
								<?php foreach ($viData['types'] as $key => $item): ?>
									<li data-id="<?=$key?>"><?=$item['sort']?></li>
								<?php endforeach; ?>
							</ul>
							<input type="hidden" name="type" value="">
							<b class="glyphicon glyphicon-triangle-bottom"></b>
						</div>
						<div class="ideas__select" id="sort-status">
							<span>Статус</span>
							<ul>
								<?php foreach ($viData['statuses'] as $key => $item): ?>
									<li data-id="<?=$key?>"><?=$item['sort']?></li>
								<?php endforeach; ?>
							</ul>
							<input type="hidden" name="status" value="">
							<b class="glyphicon glyphicon-triangle-bottom"></b>
						</div>
					</div>
					<div class="ideas__select ideas-sort__right" id="sort-params">
						<span>Сортировка</span>
						<ul>
							<li data-id="1">по рейтингу <b class="glyphicon glyphicon-sort-by-attributes-alt"></b></li>
							<li data-id="2">по рейтингу <b class="glyphicon glyphicon-sort-by-attributes"></b></li>
							<li data-id="3">по дате добавления <b class="glyphicon glyphicon-sort-by-attributes-alt"></b></li>
							<li data-id="4">по дате добавления <b class="glyphicon glyphicon-sort-by-attributes"></b></li>
							<li data-id="5">по кол-ву просмотров</li>
							<li data-id="6">по кол-ву комментариев</li>
						</ul>
						<input type="hidden" name="sort" value="">
						<b class="glyphicon glyphicon-triangle-bottom"></b>
					</div>
				</div>
				<input type="hidden" name="filter-ideas" value="1">
			</form>
			<div class="ideas__module">
				<div id="ideas-content">
					<?php foreach ($viData['ideas'] as $item): ?>
						<div class="idea__item">
							<div class="idea__status <?=$viData['statuses'][$item['status']]['class']?>"><?=$viData['statuses'][$item['status']]['idea']?></div>
							<div class="idea__item-logo">
								<?php if($item['author']['is_online']): ?>
									<b class="js-g-hashint" title="В сети"></b>
								<?php endif; ?>
								<a href="<?=$item['author']['profile']?>">
									<img src="<?=$item['author']['src']?>" alt="<?=$item['author']['name']?>">
								</a>
							</div>
							<div class="idea__item-info">
								<div class="idea__item-top">
									<span class="idea__item-type js-g-hashint <?=$viData['types'][$item['type']]['class']?>" title="<?=$viData['types'][$item['type']]['idea']?>"></span>
									<a href="<?=$item['link']?>" class="idea__item-name"><?=$item['name']?></a>
								</div>
								<div class="idea__item-bottom">
									<a href="<?=$item['author']['profile']?>" class="idea__item-author"><?=$item['author']['name']?></a>
									<b>•</b>
									<span><?=$item['crdate']?></span>
									<b>•</b>
									<span class="idea__item-comments"><?=$item['comments']?></span>
								</div>
							</div>
							<div class="idea__item-rating active" data-id="<?=$item['id']?>">
								<div class="idea__item-rpos js-g-hashint" title="Поддерживаю"><?=$item['posrating']?></div>
								<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю"><?=$item['negrating']?></div>
							</div>
						</div>
					<?php endforeach ?>
					<div class="ideas__pagination">
						<?php
							// display pagination
							$this->widget('CLinkPager', array(
								'pages' => $viData['pages'],
								'htmlOptions' => array('class' => 'paging-wrapp'),
								'firstPageLabel' => '1',
								'prevPageLabel' => 'Назад',
								'nextPageLabel' => 'Вперед',
								'header' => '',
								'cssFile' => false
							));
						?>
					</div>
				</div>
			</div>
		<?php else: ?>
			<p class="text-center ideas__null">Активных заявок пока нет</p>
		<?php endif; ?>
	</div>
</div>