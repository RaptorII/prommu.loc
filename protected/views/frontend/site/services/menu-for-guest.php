<?
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/services/menu.min.js', CClientScript::POS_END);
?>
<div class='service-menu'> 
	<ul class="airSticky" id="menu">
		<li>
			<a class="cd-faq-trigger" href="#premvac" id="one"><i></i>Премиум-вакансии</a>
			<div class="cd-faq-content">
				<div class="btn-green-02-wr">
					<a href="<?=MainConfig::$PAGE_SERVICES_PREMIUM?>" class="apply js-hashint tooltipstered">Посмотреть</a>
				</div>
				<div class="order-btn btn-green-02-wr" data-id="premium">
					<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
				</div>
			</div>
		</li>
		<li>
			<a class="cd-faq-trigger" href="#shares" id="two"><i></i>Приглашение персонала на вакансии</a>
			<ul class="lvl-1">
				<li>
					<a class="cd-faq-trigger" href="#email">- Электронная почта</a>
					<div class="cd-faq-content">
						<div class="btn-green-02-wr">
							<a href="<?=MainConfig::$PAGE_SERVICES_EMAIL?>" class="apply js-hashint tooltipstered">Посмотреть</a>
						</div>
						<div class="order-btn btn-green-02-wr" data-id="email">
							<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
						</div>
					</div>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#push">- PUSH уведомления</a>
					<div class="cd-faq-content">
						<div class="btn-green-02-wr">
							<a href="<?=MainConfig::$PAGE_SERVICES_PUSH?>" class="apply js-hashint tooltipstered">Посмотреть</a>
						</div>
						<div class="order-btn btn-green-02-wr" data-id="push">
							<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
						</div>
					</div>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#sms">- SMS информирование</a>
					<div class="cd-faq-content">
						<div class="btn-green-02-wr">
							<a href="<?=MainConfig::$PAGE_SERVICES_SMS?>" class="apply js-hashint tooltipstered">Посмотреть</a>
						</div>
						<div class="order-btn btn-green-02-wr" data-id="sms">
							<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
						</div>
					</div>
				</li>
				<li>
					<a class="cd-faq-trigger" href="#double">- Группы социальных сетей PROMMU</a>
					<div class="cd-faq-content">
						<div class="btn-green-02-wr">
							<a href="<?=MainConfig::$PAGE_SERVICES_SOCIAL?>" class="apply js-hashint tooltipstered">Посмотреть</a>
						</div>
						<div class="order-btn btn-green-02-wr" data-id="41">
							<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
						</div>
					</div>
				</li>
			</ul>
		</li>

		<li>
			<a class="cd-faq-trigger" href="#geo" id="tree"><i></i>Геолокация</a>
			<div class="cd-faq-content">
				<div class="btn-green-02-wr">
					<a href="<?=MainConfig::$PAGE_SERVICES_GEO?>" class="apply js-hashint tooltipstered">Посмотреть</a>
				</div>
				<div class="order-btn btn-green-02-wr" data-id="36">
					<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
				</div>
			</div>
		</li>

		<li>
			<a class="cd-faq-trigger" href="#manager" id="four"><i></i>Личный менеджер и аутсорсинг персонала</a>
			<div class="cd-faq-content">
				<div class="btn-green-02-wr">
					<a href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>" class="apply js-hashint tooltipstered">Посмотреть</a>
				</div>
				<div class="order-btn btn-green-02-wr" data-id="37">
					<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
				</div>
			</div>
		</li>

		<li>
			<a class="cd-faq-trigger" href="#outstaff" id="five"><i></i>Аутстаффинг персонала</a>
			<div class="cd-faq-content">
				<div class="btn-green-02-wr">
					<a href="<?=MainConfig::$PAGE_SERVICES_OUTSTAFFING?>" class="apply js-hashint tooltipstered">Посмотреть</a>
				</div>
				<div class="order-btn btn-green-02-wr" data-id="38">
					<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
				</div>
			</div>
		</li>

		<li>
			<a class="cd-faq-trigger" href="<?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>" id="six" data-state='normal'><i></i>Получение корпоративной карты Prommu</a>
		</li>
		<li>
			<a class="cd-faq-trigger" href="#med" id="seven"><i></i>Получение медицинской книги</a>
			<div class="cd-faq-content">
				<div class="btn-green-02-wr">
					<a href="<?=MainConfig::$PAGE_SERVICES_MEDICAL?>" class="apply js-hashint tooltipstered">Посмотреть</a>
				</div>
				<div class="order-btn btn-green-02-wr" data-id="178">
					<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
				</div>
			</div>
		</li>
		<li>
			<a class="cd-faq-trigger" href="#api" id="eight"><i></i>Получение API-ключа</a>
			<div class="cd-faq-content">
				<div class="btn-green-02-wr">
					<a href="<?=MainConfig::$PAGE_SERVICES_API?>" class="apply js-hashint tooltipstered">Посмотреть</a>
				</div>
				<div class="order-btn btn-green-02-wr" data-id="50">
					<a href="javascript:void(0)" class="apply js-hashint tooltipstered">Заказать</a>
				</div>
			</div>
		</li>
	</ul>
</div>