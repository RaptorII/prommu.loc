<? 
//
// Employer
//
?>
<? if(Share::isEmployer()): ?>
	<div class="pa__module">
		<h2 class="pa__title">ПУБЛИКАЦИЯ ВАКАНСИЙ</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-4 pa__count">
				<div class="pa-count__title">ОПУБЛИКОВАННЫХ ВАКАНСИЙ</div>
				<div class="pa-count__num"><?=$viData['vacancies']['cnt']?></div>
				<div class="pa-count__list">
					<div class="pa-count__list-item ico1">ВСЕГО ПРОСМОТРОВ: <span><?=$viData['vacancies']['cnt_views']?></span></div>
					<div class="pa-count__list-item ico2">ВСЕГО ОТКЛИКОВ: <span><?=$viData['vacancies']['cnt_responses']?></span></div>
					<div class="pa-count__list-item ico3">ПРИГЛАШЕНИЙ: <span><?=$viData['vacancies']['cnt_invitations']?></span></div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 pa__list">
				<? if(count($viData['vacancies']['items'])): ?>
					<div class="pa-list__title">Опубликованные вакансии</div>
					<? foreach ($viData['vacancies']['items'] as $v):?>
						<div class="pa-list__item">
							<span class="pa-list__item-name"><?=$v['title'];?></span>
							<div class="pa-list__item-count">
								<span class="pa-list__item-cnt ico1">Просмотров: <span><?=$v['views']?></span></span>
								<span class="pa-list__item-cnt ico2">Откликов: <span><?= $v['responses']?></span></span>
								<span class="pa-list__item-cnt ico3">Приглашений: <span><?=$v['invitations']?></span></span>
							</div>
						</div>
					<? endforeach; ?>
				<? else: ?>
					<div class="pa-list__title">Опубликованных вакансий нет</div>
				<? endif; ?>
			</div>
		</div>
	</div>
	<div class="pa__module">
		<h2 class="pa__title">ИСПОЛЬЗОВАНИЕ УСЛУГ</h2> 
		<div class="row">
			<div class="col-xs-12 col-sm-4 pa__count">
				<div class="pa-count__title">ВСЕГО УСЛУГ ИСПОЛЬЗОВАНО</div>
				<div class="pa-count__num"><?=$viData['services']['cnt']?></div>
			</div>
			<div class="col-xs-12 col-sm-8 pa__list">
				<div class="pa-list__title">Используемые услуги</div>
				<div class="pa-service__list">
					<div class="pa-service__item">
						<span class="pa-service__item-name premium">Премиум-вакансии</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['vacancy']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name email">Электронная почта</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['email']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name push">PUSH уведомления</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['push']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name sms">SMS информирование</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['sms']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name outsource">Личный менеджер и аутсорсинг персонала</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['outsourcing'] ?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name oustaff">Аутстаффинг персонала</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['outstaffing']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name share">Группы социальных сетей PROMMU</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['repost']?></b></span>
					</div>
					<div class="pa-service__item">
						<span class="pa-service__item-name api">Получение API ключа</span>
						<span class="pa-service__item-cnt"><span>Количество использований: </span><b><?=$viData['services']['api']?></b></span>
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
				<div class="pa-count__num"><?=$viData['cnt_profile_views']?></div>
			</div>
			<div class="col-xs-12 col-sm-8 pa__list hidden-xs hidden-sm hidden-md">
				<div class="pa-list__title">Статистика просмотров</div>
				<script type="text/javascript">var arGraph = <?echo $viData['schedule']?></script>
				<div class="pa__graph-block" id="pa-chart"></div>
			</div>
		</div>
	</div>
<?
//
// Applicant
//
?>
<? else: ?>
	<h2 class="paa__title">СОБЫТИЯ</h2>
	<div class="paa__date">События <b>С <span id="pa-begin-app"><?=$viData['dates']['bdate']?></span> ПО <span id="pa-end-app"><?=$viData['dates']['edate']?></span></b></div>
	<div class="paa-event__list">
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Просмотренных вакансий</span></div>
				<div class="paa-event__item-count"><span><i><?=$viData['cnt_views']?></i></span></div>
			</div>

		</div>
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Приглашения на вакансию от работодателя</span></div>
				<div class="paa-event__item-count"><span><i><?=$viData['cnt_invitations']?></i></span></div>
			</div>
		</div>
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Самостоятельных кликов на размещенные вакансии</span></div>
				<div class="paa-event__item-count"><span><i><?=$viData['cnt_requests']?></i></span></div>
			</div>
		</div>
		<div class="paa-event__item pa__module">
			<div class="paa-event__item-content">
				<div class="paa-event__item-name"><span>Отработанных (утвержденных) вакансий</span></div>
				<div class="paa-event__item-count"><span><i><?=$viData['cnt_approved']?></i></span></div>
			</div>
		</div>
	</div>
<? endif; ?>