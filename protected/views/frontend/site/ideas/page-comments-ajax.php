<?php foreach ($viData['arr_comments'] as $cmnt): ?>
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