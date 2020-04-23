<div class='filter'>
	<div class='filter__item filter-surname'>
		<div class='filter__item-name opened'>Фамилия</div>
		<div class='filter__item-content opened'>
			<input name='qs' type='text' title="Введите фамилию" class="psa__input">
            <input type="hidden" name="vacancy" value="<?=$vacancy?>">
			<div class="filter__name-btn prmu-btn prmu-btn_small pull-right">
				<span>ОК</span>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class='filter__item filter-cities'>
		<div class='filter__item-name opened'>Город</div>
		<div class='filter__item-content opened'>
				<div class="fav__select-cities" id="multyselect-cities"></div>
		</div>
	</div>
	<div class='filter__item filter-posts'>
		<div class='filter__item-name opened'>Должность</div>
		<?php
		$sel = 0;
		foreach($viData['workers']['posts'] as $p)
			if($p['selected']) $sel++;
		?>
		<div class='filter__item-content opened' <?=$sel>0?'style="height:initial"':''?>>
			<div class='right-box'>
				<input name='posts-all' type='checkbox' id="f-all-posts" class="filter__chbox-inp"<?=sizeof($viData['workers']['posts'])==$sel ?' checked':''?>>
				<label class='filter__chbox-lab' for="f-all-posts">Выбрать все / снять все</label>
				<?php foreach($viData['workers']['posts'] as $p): ?>
					<input name='posts[]' value="<?=$p['id']?>" type='checkbox' id="f-post-<?=$p['id']?>" class="filter__chbox-inp" <?=$p['selected'] ? 'checked' : ''?>>
					<label class='filter__chbox-lab' for="f-post-<?=$p['id']?>"><?=$p['name']?></label>
				<?php endforeach; ?>
			</div>
			<? if(!$sel): ?>
				<span class="more-posts">Показать все</span>
			<? endif; ?>
		</div>
	</div>
	<div class='filter__item filter-sex'>
		<div class='filter__item-name opened'>Пол</div>
		<div class='filter__item-content opened'>
			<div class='right-box'>
				<input name='sm' type='checkbox' value='1' class="filter__chbox-inp" id="f-male"<?=($_GET['sm']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-male">Мужской</label>
				<input name='sf' type='checkbox' value='1' class="filter__chbox-inp" id="f-female"<?=($_GET['sf']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-female">Женский</label>
			</div>
		</div>
	</div>
	<div class='filter__item filter-salary'>
		<div class='filter__item-name opened'>Заработная плата</div>
		<div class='filter__item-content opened'>
			<div class="filter__salary">
				<span class="filter__salary-name">В час</span>
				<div class="filter__salary-block">
					<label class="filter__salary-label">
						<span>от</span>
						<input name=sphf type='text' class="psa__input">
					</label>
					<label class="filter__salary-label">
						<span>до</span>
						<input name='spht' type='text' class="psa__input">
					</label>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="filter__salary">
				<span class="filter__salary-name">В неделю</span>
				<div class="filter__salary-block">
					<label class="filter__salary-label">
						<span>от</span>
						<input name=spwf type='text' class="psa__input">
					</label>
					<label class="filter__salary-label">
						<span>до</span>
						<input name='spwt' type='text' class="psa__input">
					</label>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="filter__salary">
				<span class="filter__salary-name">В месяц</span>
				<div class="filter__salary-block">
					<label class="filter__salary-label">
						<span>от</span>
						<input name=spmf type='text' class="psa__input">
					</label>
					<label class="filter__salary-label">
						<span>до</span>
						<input name='spmt' type='text' class="psa__input">
					</label>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="filter__salary">
				<span class="filter__salary-name">За посещение</span>
				<div class="filter__salary-block">
					<label class="filter__salary-label">
						<span>от</span>
						<input name=spvf type='text' class="psa__input">
					</label>
					<label class="filter__salary-label">
						<span>до</span>
						<input name='spmt' type='text' class="psa__input">
					</label>
					<div class="clearfix"></div>
				</div>
			</div>
			<input id='psa-salary-type' name='sr' type='hidden'>
			<div class="filter__name-btn prmu-btn prmu-btn_small pull-right">
				<span>ОК</span>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class='filter__item filter-age'>
		<div class='filter__item-name opened'>Возраст</div>
		<div class='filter__item-content opened'>
			<div class="filter__age">
				<label>
					<span>от</span>
					<input name=af type='text' value="<?=$_GET['af']?>">
				</label>
				<label>
					<span>до</span>
					<input name='at' type='text' value="<?=$_GET['at']?>">
				</label>
			</div>
			<div class="filter__name-btn prmu-btn prmu-btn_small pull-right">
				<span>ОК</span>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class='filter__item filter-additional'>
		<div class='filter__item-name opened'>Дополнительно</div>
		<div class='filter__item-content opened'>
			<div class='right-box'>
				<input name='mb' type='checkbox' value='1' class="filter__chbox-inp" id="f-med"<?=($_GET['mb']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-med">Наличие медкнижки</label>
				<input name='avto' type='checkbox' value='1' class="filter__chbox-inp" id="f-auto"<?=($_GET['avto']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-auto">Наличие автомобиля</label>
				<input name='smart' type='checkbox' value='1' class="filter__chbox-inp" id="f-smart"<?=($_GET['smart']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-smart">Наличие смартфона</label>
				<input name='cardPrommu' type='checkbox' value='1' class="filter__chbox-inp" id="f-pcard"<?=($_GET['cardPrommu']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-pcard">Банковская карта Prommu</label>
				<input name='card' type='checkbox' value='1' class="filter__chbox-inp" id="f-card"<?=($_GET['card']?' checked':'')?>>
				<label class="filter__chbox-lab" for="f-card">Банковская карта</label>
			</div>
		</div>
	</div>
</div>