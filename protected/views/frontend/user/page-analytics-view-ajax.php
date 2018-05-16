<?php 
	$countP = 0;
	$countO = 0;
	$countPr = 0;
	foreach ($viData['vacs'] as $key => $val) {
		$countP += $viData['analytic'][$val['id']];
		$countO += $val['isresp'][1];
		$countPr += $viData['responses'][$val['id']];
	}
	$Termostat = new Termostat();
	$services = $Termostat->getTermostatServices(Share::$UserProfile->id, $arDates);
	$rest = [];
	$rest['outsourcing'] = 0;
	$rest['outstaffing'] = 0;
	$rest['vacancy'] = 0;
	$rest['sms'] = 0;
	$rest['push'] = 0;
	$rest['email'] = 0;

	foreach ($services[0] as $key => $val) {
		if($val['type'] == "sms"){
			$rest['sms']++;
		}
		if($val['type'] == 'vacancy'){
			$rest['vacancy']++;
		}
		if($val['type'] == "push"){
			$rest['push']++;
		}
		if($val['type'] == "email"){
			$rest['email']++;
		}
	}

	foreach ($services[1] as $key => $val) {
		if($val['type'] == "outsourcing"){
			$rest['outsourcing']++;
		}
		
		if($val['type'] == 'outstaffing'){
			$rest['outstaffing']++;
		}
		
	}
	$restsum = $rest['sms'] + $rest['vacancy'] + $rest['outstaffing'] + $rest['outsourcing'] + $rest['push'] + $rest['email'];
//
// Employer
//
?>
<?php if(Share::$UserProfile->type==3): ?>
	<?
		$arGraph = $Termostat->getTermostatEmplCount(Share::$UserProfile->id, $arDates);
		$counts = $Termostat->getTermostatEmplCounts(Share::$UserProfile->id, $arDates);
	?>
	<script type="text/javascript">var arGraph = <?=json_encode($arGraph)?></script>
	<div class="pa__module">
		<h2 class="pa__title">ПУБЛИКАЦИЯ ВАКАНСИЙ</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-4 pa__count">
				<div class="pa-count__title">ОПУБЛИКОВАННЫХ ВАКАНСИЙ</div>
				<div class="pa-count__num"><?= $count?></div>
				<div class="pa-count__list">
					<div class="pa-count__list-item ico1">ВСЕГО ПРОСМОТРОВ: <span><?=$countP?></span></div>
					<div class="pa-count__list-item ico2">ВСЕГО ОТКЛИКОВ: <span><?=$countO?></span></div>
					<div class="pa-count__list-item ico3">ПРИГЛАШЕНИЙ: <span><?=$countPr?></span></div>					
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 pa__list">
				<div class="pa-list__title">Опубликованные вакансии</div>
				<? foreach ($viData['vacs'] as $key => $val):?>
				<div class="pa-list__item">
					<span class="pa-list__item-name"><?= $val['title'];?></span>
					<div class="pa-list__item-count">
						<span class="pa-list__item-cnt ico1">Просмотров: <span><?=$viData['analytic'][$val['id']]?></span></span>
						<span class="pa-list__item-cnt ico2">Откликов: <span><?= $val['isresp'][1];?></span></span>
						<span class="pa-list__item-cnt ico3">Приглашений: <span><?=$viData['responses'][$val['id']];?></span></span>
						<? $countP += $viData['analytic'][$val['id']];?>
					</div>
				</div>
				<? endforeach;?>
			</div>
		</div>
	</div>
	<div class="pa__module">
		<h2 class="pa__title">ИСПОЛЬЗОВАНИЕ УСЛУГ</h2> 
		<div class="row">
			<div class="col-xs-12 col-sm-4 pa__count">
				<div class="pa-count__title">ВСЕГО УСЛУГ ИСПОЛЬЗОВАНО</div>
				<div class="pa-count__num"><?= $restsum;?></div>
			</div>
			<div class="col-xs-12 col-sm-8 pa__list">
				<div class="pa-list__title">Используемые услуги</div>
				<div class="pa-service__list">
					<div class="pa-service__item">
						<span class="pa-service__item-name premium">Премиум-вакансии</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$rest['vacancy']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name email">Электронная почта</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$rest['email']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name push">PUSH уведомления</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$rest['push']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name sms">SMS информирование</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$rest['sms']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name outsource">Личный менеджер и аутсорсинг персонала</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$rest['outsourcing'] ?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name oustaff">Аутстаффинг персонала</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$rest['outstaffing']?></b></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pa__module">
		<h2 class="pa__title">ПРОСМОТРЫ АККАУНТА</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-4 pa__count">
				<div class="pa-count__title">ВСЕГО ПРОСМОТРОВ</div>
				<div class="pa-count__num"><?= $counts?></div>
			</div>
			<div class="col-xs-12 col-sm-8 pa__list hidden-xs hidden-sm hidden-md">
				<div class="pa-list__title">Статистика просмотров</div>
				<div class="pa__graph-block" id="pa-chart"></div>
			</div>
		</div>
	</div>
<?
//
// Applicant
//
?>
<?php else: ?>
	<h2 class="paa__title">СОБЫТИЯ</h2>
	<div class="paa__date">События <b>С <span id="pa-begin-app"><?=$arDates['bdate']?></span> ПО <span id="pa-end-app"><?=$arDates['edate']?></span></b></div>
	<div class="paa-event__list">
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Просмотренных вакансий</span></div>
				<div class="paa-event__item-count"><span>Количество использований: <i><?=$countView?></i></span></div>
			</div>
		</div>
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Приглашения на вакансию от работодателя</span></div>
				<div class="paa-event__item-count"><span>Количество использований: <i><?=$countResponse?></i></span></div>
			</div>
		</div>
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Самостоятельных кликов на размещенные вакансии</span></div>
				<div class="paa-event__item-count"><span>Количество использований: <i><?=$countInvite?></i></span></div>
			</div>
		</div>
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Отработанных (утвержденных) вакансий</span></div>
				<div class="paa-event__item-count"><span>Количество использований: <i><?=$countProject?></i></span></div>
			</div>
		</div>
	</div>
<?php endif; ?>