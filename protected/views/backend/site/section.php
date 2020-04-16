<?php 
	$section = Yii::app()->getRequest()->getParam('p'); 
	$hUrl = Yii::app()->homeUrl;
	$title = '';
?>
<style type="text/css">
	#section-menu{
		max-width: 250px;

	}
	#section-menu ul{
		background: #222d32;
	}
	#section-menu a{
		padding: 12px 5px 12px 15px;
		border-left: 3px solid transparent;
	}
	#section-menu a:hover{
		border-left-color: #3c8dbc;
		background: #1e282c;
	}
	#section-menu li *{
		line-height: 20px;
		font-size: 16px;
		color: #b8c7ce;
	}
	#section-menu li i{ 
		font-size: 14px;
		padding-right: 5px;
	}
</style>
<div class = "col-xs-12 col-md-4" id="section-menu">
	<ul class="nav">
		<?php if($section=='app'): //Соискатели ?>
			<?php $title = 'Соискатели'; ?>
			<li>
				<a href="<?=$hUrl?>users">
					<i class="glyphicon glyphicon-ok-circle"></i>
					<span>Зарегистрированные</span>
				</a>
			</li>
      <li>
        <a href="<?=$hUrl . 'register?user=2&state=profile'?>">
          <i class="glyphicon glyphicon-registration-mark"></i>
          <span>Активация профиля</span>
        </a>
      </li>
      <li>
        <a href="<?=$hUrl . 'register?user=2&state=avatar'?>">
          <i class="glyphicon glyphicon-registration-mark"></i>
          <span>Незаполненное фото</span>
        </a>
      </li>
      <li>
        <a href="<?=$hUrl . 'register?user=2&state=code'?>">
          <i class="glyphicon glyphicon-registration-mark"></i>
          <span>Не подтвердил код</span>
        </a>
      </li>
			<li>
				<a href="<?=$hUrl?>wait?type=2">
					<i class="glyphicon glyphicon-hourglass"></i>
					<span>Брошенные(устар.)</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>comments?type=1">
					<i class="glyphicon glyphicon-heart"></i>
					<span>Отзывы</span>
				</a>
			</li>

		<?php elseif($section=='emp'): //Работодатели ?>
			<?php $title = 'Работодатели'; ?>
			<li>
				<a href="<?=$hUrl?>empl">
					<i class="glyphicon glyphicon-ok-circle"></i>
					<span>Зарегистрированные</span>
				</a>
			</li>
      <li>
        <a href="<?=$hUrl . 'register?user=3&state=profile'?>">
          <i class="glyphicon glyphicon-registration-mark"></i>
          <span>Активация профиля</span>
        </a>
      </li>
      <li>
        <a href="<?=$hUrl . 'register?user=3&state=avatar'?>">
          <i class="glyphicon glyphicon-registration-mark"></i>
          <span>Незаполненное фото</span>
        </a>
      </li>
      <li>
        <a href="<?=$hUrl . 'register?user=3&state=code'?>">
          <i class="glyphicon glyphicon-registration-mark"></i>
          <span>Не подтвердил код</span>
        </a>
      </li>
			<li>
				<a href="<?=$hUrl?>wait?type=3">
					<i class="glyphicon glyphicon-hourglass"></i>
					<span>Брошенные(устар.)</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>comments?type=0">
					<i class="glyphicon glyphicon-heart"></i>
					<span>Отзывы</span>
				</a>
			</li>

		<?php elseif($section=='vac'): //Вакансии ?>
			<?php $title = 'Вакансии'; ?>
			<li>
				<a href="<?=$hUrl?>vacancy">
					<i class="glyphicon glyphicon-ok-circle"></i>
					<span>Действующие</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>vacancymail">
					<i class="glyphicon glyphicon-hourglass"></i>
					<span>Брошенные</span>
				</a>
			</li>
      <li>
        <a href="<?=$hUrl?>cost_vacancy">
          <i class="glyphicon glyphicon-rub"></i>
          <span>Платные/бесплатные</span>
        </a>
      </li>

		<?php elseif($section=='service'): //Услуги ?>
			<?php $title = 'Услуги'; ?>
      <li>
        <a href="<?=$hUrl?>services?type=guest-order">
          <i class="glyphicon glyphicon-envelope"></i>
          <span>Заказ услуг гостями</span>
        </a>
      </li>
      <li>
        <a href="<?=$hUrl?>services?type=creation_vacancy">
          <i class="glyphicon glyphicon-floppy-disk"></i>
          <span>Создание вакансии</span>
        </a>
      </li>
			<li>
				<a href="<?=$hUrl?>services?type=vacancy">
                	<i class="glyphicon glyphicon-star-empty"></i>
					<span>Премиум</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>services?type=upvacancy">
                	<i class="glyphicon glyphicon-level-up"></i>
					<span>Вверх вакансию</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>services?type=email">
					<i class="glyphicon">@</i>
					<span>Электронная почта</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>services?type=push">
					<i class="glyphicon glyphicon-comment"></i>
					<span>PUSH уведомления</span>
				</a>
			</li>
            <li>
                <a href="<?=$hUrl?>services?type=personal-invitation">
                    <i class="glyphicon glyphicon-envelope"></i>
                    <span>Приглашение на вакансию</span>
                </a>
            </li>
			<li>
				<a href="<?=$hUrl?>services?type=sms">
					<i class="glyphicon glyphicon-envelope"></i>
					<span>SMS информирование</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>services?type=repost">
					<i class="glyphicon glyphicon-bullhorn"></i>
					<span>Соцсети</span>
				</a>
			</li>
			<li>
				<a href="#" onclick="alert('Страница в разработке'); return false">
					<i class="glyphicon glyphicon-globe"></i>
					<span>Геолокация</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>servicess?type=outsourcing">
					<i class="glyphicon glyphicon-check"></i>
					<span>Аутсорсинг</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>servicess?type=outstaffing">
					<i class="glyphicon glyphicon-edit"></i>
					<span>Аутстаффинг</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>cards">
					<i class="glyphicon glyphicon-credit-card"></i>
					<span>Карта Prommu</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>medcards">
					<i class="glyphicon glyphicon-plus-sign"></i>
					<span>Мед. книга</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>servicess?type=api">
					<i class="glyphicon glyphicon-cog"></i>
					<span>API</span>
				</a>
			</li>

		<?php elseif($section=='analytic'): //Аналитика ?>
			<?php $title = 'Аналитика'; ?>
			<li>
				<a href="<?=$hUrl?>analytic?subdomen=">
					<i class="glyphicon glyphicon-text-background"></i>
					<span>Общая</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>analytic?subdomen=0">
					<i class="glyphicon glyphicon-text-background"></i>
					<span>PROMMU</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>analytic?subdomen=1">
					<i class="glyphicon glyphicon-text-background"></i>
					<span>SPB.PROMMU</span>
				</a>
			</li>

		<?php elseif($section=='seo'): //СЕО ?>
			<?php $title = 'СЕО'; ?>
			<li>
				<a href="<?=$hUrl?>vacancy?seo=1">
					<i class="glyphicon glyphicon-list-alt"></i>
					<span>SEO мониторинг</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>articlespages">
					<i class="glyphicon glyphicon-duplicate"></i>
					<span>Статьи</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>seo">
					<i class="glyphicon glyphicon-filter"></i>
					<span>Мета данные</span>
				</a>
			</li>

		<?php elseif($section=='add'): //Дополнительно ?>
			<?php $title = 'Дополнительно'; ?>
			<li>
				<a href="<?=$hUrl?>PageUpdate/7?lang=ru&pagetype=about">
					<i class="glyphicon glyphicon-file"></i>
					<span>О нас</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>forstudents">
					<i class="glyphicon glyphicon-file"></i>
					<span>Работа для студентов</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>PageUpdate/19?lang=ru&pagetype=empl">
					<i class="glyphicon glyphicon-file"></i>
					<span>Работодателям</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>PageUpdate/19?lang=ru&pagetype=prom">
					<i class="glyphicon glyphicon-file"></i>
					<span>Соискателям</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>newspages">
					<i class="glyphicon glyphicon-flash"></i>
					<span>Новости</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>faq">
					<i class="glyphicon glyphicon-info-sign"></i>
					<span>FAQ</span>
				</a>
			</li>
			<li>
				<a href="<?=$hUrl?>admin">
					<i class="glyphicon glyphicon-sunglasses"></i>
					<span>Администраторы</span>
				</a>
			</li>
		<?php else: ?>
			<?php $this->redirect(array('/')); ?>
		<?php endif; ?>
	</ul>
</div>
<?php
	$this->setPageTitle($title);
	$this->breadcrumbs = array($title); 
?>