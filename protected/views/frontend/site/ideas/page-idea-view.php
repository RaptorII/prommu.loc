<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/ideas/page-idea.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/ideas/page-idea.js', CClientScript::POS_END);

	$title = 'Идеи и предложения';
	$this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_IDEAS_LIST));
	// устанавливаем title
	$this->pageTitle = $viData['name'];
	// устанавливаем h1
	$this->ViewModel->setViewData('pageTitle', '<h1>' . $viData['name'] . '</h1>');
	// breadcrumbs
	$this->setBreadcrumbsEx(array($viData['name'], MainConfig::$PAGE_IDEAS_LIST . DS . $viData['id']));

	$author = $viData['users'][$viData['id_user']];
?>
<div class="row">
	<div class="col-xs-12">
		<div class="idea__module">
			<div id="ideas-content">
				<div class="idea__item" data-id="<?=$viData['id']?>">
					<div class="idea__status <?=$viData['statuses'][$viData['status']]['class']?>"><?=$viData['statuses'][$viData['status']]['idea']?></div>
					<div class="idea__item-logo">
						<?php if($author['is_online']): ?>
							<b class="js-g-hashint" title="В сети"></b>
						<?php endif; ?>
						<a href="<?=$author['profile']?>"><img src="<?=$author['src']?>" alt="<?=$author['name']?>"></a>
					</div>
					<div class="idea__item-info">
						<div class="idea__item-top">
							<span class="idea__item-type js-g-hashint <?=$viData['types'][$viData['type']]['class']?>" title="<?=$viData['types'][$viData['type']]['idea']?>"></span>
							<span class="idea__item-name"><?=$viData['name']?></span>
						</div>
						<div class="idea__item-bottom">
							<a href="<?=$author['profile']?>" class="idea__item-author"><?=$author['name']?></a>
							<b>•</b>
							<span><?=$viData['crdate']?></span>
							<b>•</b>
							<span class="idea__item-comments"><?=$viData['comments_cnt']?></span>
						</div>
					</div>
					<div class="idea__item-rating<?=(!$viData['is_guest']?' active':'')?>">
						<div class="idea__item-rpos js-g-hashint" title="Поддерживаю"><?=$viData['posrating']?></div>
						<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю"><?=$viData['negrating']?></div>
					</div>
				</div>
				<div class="idea__description"><?php echo $viData['text']; ?></div>
				<div class="idea__comment">
					<?php if(!$viData['is_guest']): ?>
						<div class="idea__set-rating active">
							<span class="idea__set-r-name hidden-xs">Проголосовать:</span>
							<div class="idea__set-rpos js-g-hashint" title="Поддерживаю"><?=$viData['posrating']?></div>
							<div class="idea__set-rneg js-g-hashint" title="Не поддерживаю"><?=$viData['negrating']?></div>
						</div>
						<div class="idea__set-comment btn__orange">Написать комментарий</div>
						<form id="comment-form">
							<textarea placeholder="Текст комментария"></textarea>
							<button type="submit" class="new-idea__btn" id="add-comment">Отправить</button>
						</form>
					<?php endif; ?>
				</div>
				<div class="comments__module">
					<div id="ideas-veil"></div>			
						<?php if(sizeof($viData['comments'])): ?>
							<div class="idea__comment-sort">
								<div class="idea__comment-cnt">Комментарии (<?=$viData['comments_cnt']?>)</div>
								<form id="comments-sort">
									<div class="ideas__select" id="sort-params">
										<span>Сортировка</span>
										<ul>
											<li data-id="1" class="active">По дате <b class="glyphicon glyphicon-sort-by-attributes"></b></li>
											<li data-id="2">По дате <b class="glyphicon glyphicon-sort-by-attributes-alt"></b></li>
										</ul>
										<input type="hidden" name="type" value="1">
										<b class="glyphicon glyphicon-triangle-bottom"></b>
									</div>
									<input type="hidden" name="idea" value="<?=$viData['id']?>">
									<input type="hidden" name="sort-comments" value="1">
								</form>
							</div>
							<div id="comment-list">
								<?php foreach ($viData['comments'] as $cmnt): ?>
									<?php $user = $viData['users'][$cmnt['id_user']]; ?>
									<div class="idea__comment-item">
										<div class="idea__item-logo">
											<?php if($user['is_online']): ?>
												<b class="js-g-hashint" title="В сети"></b>
											<?php endif; ?>
											<a href="<?=$user['profile']?>"><img src="<?=$user['src']?>" alt="<?=$user['name']?>"></a>
										</div>
										<div class="idea__comment-info">
											<div class="idea__comment-name">
												<a href="<?=$user['profile']?>"><?=$user['name']?></a> <?=$cmnt['date_comment']?>
											</div>
											<div class="idea__comment-text"><?php echo $cmnt['comment'] ?></div>
										</div>
									</div>
								<?php endforeach; ?>
								<div class="ideas__pagination<?=($viData['comments_cnt'] <= $viData['pages']->pageSize ? ' hide' : '')?>">
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
						<?php else: ?>
							<div class="without__comments">Комментариев пока нет</div>
						<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>