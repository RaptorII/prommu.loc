<?php if(sizeof($viData['ideas'])): ?>
	<?php foreach ($viData['ideas'] as $item): ?>
		<?php $user = $viData['users'][$item['id_user']]; ?>
		<div class="idea__item">
			<div class="idea__status <?=$viData['statuses'][$item['status']]['class']?>"><?=$viData['statuses'][$item['status']]['idea']?></div>
			<div class="idea__item-logo">
				<?php if($user['is_online']): ?>
					<b class="js-g-hashint" title="В сети"></b>
				<?php endif; ?>
				<a href="<?=$user['profile']?>">
					<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
				</a>
			</div>
			<div class="idea__item-info">
				<div class="idea__item-top">
					<span class="idea__item-type js-g-hashint <?=$viData['types'][$item['type']]['class']?>" title="<?=$viData['types'][$item['type']]['name']?>"></span>
					<a href="<?=$item['link']?>" class="idea__item-name"><?=$item['name']?></a>
				</div>
				<div class="idea__item-bottom">
					<a href="<?=$user['profile']?>" class="idea__item-author"><?=$user['name']?></a>
					<b>•</b>
					<span><?=$item['crdate']?></span>
					<b>•</b>
					<span class="idea__item-comments"><?=$item['comments']?></span>
				</div>
			</div>
			<div class="idea__item-rating<?=(!$viData['is_guest']?' active':'')?>" data-id="<?=$item['id']?>">
				<div class="idea__item-rpos js-g-hashint" title="Поддерживаю"><?=$item['posrating']?></div>
				<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю"><?=$item['negrating']?></div>
			</div>
		</div>
	<?php endforeach ?>
	<div class="ideas__pagination<?=($viData['ideas_cnt'] <= $viData['pages']->pageSize ? ' hide' : '')?>">
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
<?php else: ?>
	<p class="text-center ideas__null">Нет заявок с такими параметрами</p>
<?php endif; ?>