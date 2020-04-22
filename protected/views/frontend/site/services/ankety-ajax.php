<div class='questionnaire'>
	<div>
		<?=$this->ViewModel->declOfNum($viData['app_count'], array('Найдена', 'Найдено', 'Найдено'))?>
		<b><?=$viData['app_count']?></b>
		<?=$this->ViewModel->declOfNum($viData['app_count'], array('Анкета', 'Анкеты', 'Анкет'))?>
	</div>
</div>
<?
display($data);
?>
<div class='row vacancy table-view'>
	<?if( $viData['workers']['promo'] ):?>
		<?$i=1;?>
		<?foreach ($viData['workers']['promo'] as $item):?>
			<div class='col-xs-12 col-sm-6 col-md-4'>
				<div class='comm-logo-wrapp'>
				<div class='comm-logo'>
					<div class="comm-logo__img">
						<img 
							alt="<?='Соискатель '.$item['firstname'].' '.$item['lastname'].' prommu.com'?>" 
							src="<?=Share::getPhoto($item['id_user'],2,$item['photo'],'small',$item['sex'])?>">
						<? if ($item['is_online']): ?>
							<span class="promo-list__item-onl"><span>В сети</span></span>
						<? endif; ?>
					</div>
					<div class="comm-logo__presence">
						<? if(!$item['is_online']): ?>
							<span>
								<i></i>Был(а) на сервисе: <?=date_format(date_create($item['mdate']),'d.m.Y'); ?>
							</span>
						<? endif; ?>
					</div>
					<b class="name"><?= $item['firstname'] . ' ' . $item['lastname'] . ', ' . $item['age']?></b>
					<div class='tmpl-ph1'>
						<div class='med-avto'>
							<?php if($item['ishasavto']==='1'): ?>
								<div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>
							<?php endif; ?>
							<?php if($item['ismed']==='1'): ?>
								<div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
							<?php endif; ?>
						</div>
					</div>
					<div class='hr'>
						<?php if( is_numeric($item['comm']) ): ?>
							<div class='comments js-g-hashint' title='Отзывы положительные | отрицательные'>
								<span class='r1'><?=$item['comm']?></span> | <?=$item['commneg']?>
							</div>
						<?php endif; ?>
            <div class='rate'>
              <span class="js-g-hashint" title="Всего"><?=($item['rate'] + $item['rate_neg'])?></span>
              (<span class="-green js-g-hashint" title="Положительный"><?=$item['rate']?></span> 
              / <span class="-red js-g-hashint" title="Отрицательный"><?=$item['rate_neg']?></span>)
            </div>
					</div>
				</div>
				</div>
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
<script type="text/javascript">var arIdies = <?=json_encode($viData['workers']['promos'])?></script>