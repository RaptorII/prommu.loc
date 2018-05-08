<?php
	Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());

	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/ideas/page-list.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/ideas/page-list.js', CClientScript::POS_END);
	$arTypes = array(
		1 => array('class' => 'idea', 'name' => 'Идея'),
		2 => array('class' => 'error', 'name' => 'Ошибка'),
		3 => array('class' => 'question', 'name' => 'Вопрос'),
	);
?>
<div class="row">
	<div class="col-xs-12">
		<div id="ideas-veil"></div>
		<form action="" method="GET" id="ideas-form">
			<div class="ideas__search">
				<input type="text" name="q" placeholder="Поиск Идеи / Предложения">
				<a href="<?=MainConfig::$PAGE_IDEA_NEW?>" class="ideas__btn"><b class="glyphicon glyphicon-plus"></b> Добавить идею/Предложение</a>				
			</div>
			<div class="ideas__sort">
				<div class="ideas-sort__left" id="sort-type">
					<div class="ideas__select">
						<span>Тип</span>
						<ul>
							<li data-id="1" class="active">Идеи</li>
							<li data-id="2">Ошибки</li>
							<li data-id="3">Вопросы</li>
						</ul>
						<input type="hidden" name="type" value="1">
						<b class="glyphicon glyphicon-triangle-bottom"></b>
					</div>
					<div class="ideas__select" id="sort-status">
						<span>Статус</span>
						<ul>
							<li data-id="1" class="active">На рассмотрении</li>
							<li data-id="2">В работе</li>
							<li data-id="3">Завершенные</li>
							<li data-id="4">Отклоненные</li>
						</ul>
						<input type="hidden" name="status" value="1">
						<b class="glyphicon glyphicon-triangle-bottom"></b>
					</div>
				</div>
				<div class="ideas__select ideas-sort__right" id="sort-params">
					<span>Сортировка</span>
					<ul>
						<li data-id="1" class="active">по рейтингу <b class="glyphicon glyphicon-sort-by-attributes-alt"></b></li>
						<li data-id="2">по рейтингу <b class="glyphicon glyphicon-sort-by-attributes"></b></li>
						<li data-id="3">по дате добавления <b class="glyphicon glyphicon-sort-by-attributes-alt"></b></li>
						<li data-id="4">по дате добавления <b class="glyphicon glyphicon-sort-by-attributes"></b></li>
						<li data-id="5">по количеству просмотров</li>
						<li data-id="6">по количеству комментариев</li>
					</ul>
					<input type="hidden" name="sort" value="1">
					<b class="glyphicon glyphicon-triangle-bottom"></b>
				</div>
			</div>
		</form>
		<div class="ideas__module">
			<div id="ideas-content">
				<div class="idea__item">
					<div class="idea__item-logo">
						<?php if(true): ?>
							<b class="js-g-hashint" title="В сети"></b>
						<?php endif; ?>
						<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180417134811177100.jpg"></a>
					</div>
					<div class="idea__item-info">
						<div class="idea__item-top">
							<span class="idea__item-type js-g-hashint <?=$arTypes[1]['class']?>" title="<?=$arTypes[1]['name']?>"></span>
							<a href="/ideas/1" class="idea__item-name">Стабилизировать рейтинг, чтоб опускался в случае отрицательного отзыва</a>
						</div>
						<div class="idea__item-bottom">
							<a href="/ankety/7000" class="idea__item-author">Владимир Прищепа</a>
							<b>•</b>
							<span>05.04.2018</span>
							<b>•</b>
							<span class="idea__item-comments">12</span>
						</div>
					</div>
					<div class="idea__item-rating active">
						<div class="idea__item-rpos js-g-hashint" title="Поддерживаю">10</div>
						<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю">2</div>
					</div>
				</div>
				<div class="idea__item">
					<div class="idea__item-logo">
						<?php if(false): ?>
							<b class="js-g-hashint" title="В сети"></b>
						<?php endif; ?>
						<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180406151855248100.jpg"></a>
					</div>
					<div class="idea__item-info">
						<div class="idea__item-top">
							<span class="idea__item-type js-g-hashint <?=$arTypes[2]['class']?>" title="<?=$arTypes[2]['name']?>"></span>
							<a href="/ideas/1" class="idea__item-name">Стабилизировать рейтинг, чтоб опускался в случае отрицательного отзыва</a>
						</div>
						<div class="idea__item-bottom">
							<a href="/ankety/7000" class="idea__item-author">Владимир Прищепа</a>
							<b>•</b>
							<span>05.04.2018</span>
							<b>•</b>
							<span class="idea__item-comments">12</span>
						</div>
					</div>
					<div class="idea__item-rating active">
						<div class="idea__item-rpos js-g-hashint" title="Поддерживаю">10</div>
						<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю">2</div>
					</div>
				</div>
				<div class="idea__item">
					<div class="idea__item-logo">
						<?php if(false): ?>
							<b class="js-g-hashint" title="В сети"></b>
						<?php endif; ?>
						<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180301171707314100.jpg"></a>
					</div>
					<div class="idea__item-info">
						<div class="idea__item-top">
							<span class="idea__item-type js-g-hashint <?=$arTypes[3]['class']?>" title="<?=$arTypes[3]['name']?>"></span>
							<a href="/ideas/1" class="idea__item-name">Стабилизировать рейтинг, чтоб опускался в случае отрицательного отзыва</a>
						</div>
						<div class="idea__item-bottom">
							<a href="/ankety/7000" class="idea__item-author">Владимир Прищепа</a>
							<b>•</b>
							<span>05.04.2018</span>
							<b>•</b>
							<span class="idea__item-comments">12</span>
						</div>
					</div>
					<div class="idea__item-rating active">
						<div class="idea__item-rpos js-g-hashint" title="Поддерживаю">10</div>
						<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю">2</div>
					</div>
				</div>
				<div class="idea__item">
					<div class="idea__item-logo">
						<?php if(true): ?>
							<b class="js-g-hashint" title="В сети"></b>
						<?php endif; ?>
						<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180227175816437100.jpg"></a>
					</div>
					<div class="idea__item-info">
						<div class="idea__item-top">
							<span class="idea__item-type js-g-hashint <?=$arTypes[4]['class']?>" title="<?=$arTypes[4]['name']?>"></span>
							<a href="/ideas/1" class="idea__item-name">Стабилизировать рейтинг, чтоб опускался в случае отрицательного отзыва</a>
						</div>
						<div class="idea__item-bottom">
							<a href="/ankety/7000" class="idea__item-author">Владимир Прищепа</a>
							<b>•</b>
							<span>05.04.2018</span>
							<b>•</b>
							<span class="idea__item-comments">12</span>
						</div>
					</div>
					<div class="idea__item-rating active">
						<div class="idea__item-rpos js-g-hashint" title="Поддерживаю">10</div>
						<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю">2</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>