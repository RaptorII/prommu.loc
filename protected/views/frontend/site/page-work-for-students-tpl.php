<?php
	$seo = (new Seo)->exist('/work-for-students');
	// устанавливаем title
	$this->pageTitle = $seo['meta_title'];
	// устанавливаем description
	Yii::app()->clientScript->registerMetaTag($seo['meta_description'], 'description');
?>
<?php //if(!(MOBILE_DEVICE && !SHOW_APP_MESS)): // /theme/css/work-for-students.css 	?>
	<style type="text/css">
		#DiContent#DiContent#DiContent,#DiSiteWrapp #DiContent{padding:0}.wfs-wrapper__title,.wfs__content{width:100%;max-width:960px}#DiContent .container .content-block{margin:0}#DiSiteWrapp #DiTop{margin-bottom:0}.row.content-header-box.mt20,.row.content-menu-box.mt20{display:none}.work-for-students__wrapper{width:100%;position:relative}.wfs-wrapper__title{padding:0;margin:0 auto;text-align:center}.wfs-wrapper__title-h1{margin:0;font-size:60px;color:#FFF;font-weight:400;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;word-break:break-all}.wfs-wrapper__title-light{font-size:36px;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif}.wfs-wrapper__title-orange{color:#ff8300;font-family:RobotoCondensedBold,RobotoCondensedRegular,Calibri,Arial,sans-serif}.wfs-wrapper__title-green{color:#abb820}.wfs-wrapper__title-bg{padding:0 15px;display:inline-block;line-height:72px;background-color:rgba(0,0,0,.7)}.wfs-prolog__text{padding:65px 70px 40px}.wfs-prolog__text p,.wfs-prolog__text ul,#DiContent .wfs-prolog__text h2{margin-bottom:20px;font-size:15px;text-align:justify;color:#646464;font-family:Roboto-Regular,Calibri,Arial,sans-serif}.wfs-prolog__list{padding:0 0 30px}.wfs-prolog__list .wfs-prolog__item{padding:0 45px 35px}.wfs-prolog-item__img{width:90px;height:85px;margin:0 auto 20px;background:url(/theme/pic/work-for-students/prolog-icons.png) no-repeat}.wfs-prolog-item__img.ico1{background-position:0 0}.wfs-prolog-item__img.ico2{background-position:0 -85px}.wfs-prolog-item__img.ico3{background-position:0 -170px}.wfs-prolog-item__img.ico4{background-position:0 -255px}.wfs-prolog-item__text{font-size:15px;color:#646464;text-align:center;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;line-height:18px}.wfs__grey-section{width:100%;position:relative;background-image:url(/theme/pic/work-for-students/grey-bg.png)}.wfs__grey-section:after{content:"";width:100%;height:100%;display:block;position:absolute;top:0;left:0;background:rgba(90,90,90,.7);background:-webkit-radial-gradient(rgba(90,90,90,.3) 10%,rgba(90,90,90,.5) 30%,rgba(90,90,90,.7) 60%,rgba(90,90,90,.9) 90%);background:-o-radial-gradient(rgba(90,90,90,.3) 10%,rgba(90,90,90,.5) 30%,rgba(90,90,90,.7) 60%,rgba(90,90,90,.9) 90%);background:-moz-radial-gradient(rgba(90,90,90,.3) 10%,rgba(90,90,90,.5) 30%,rgba(90,90,90,.7) 60%,rgba(90,90,90,.9) 90%);background:radial-gradient(rgba(90,90,90,.3) 10%,rgba(90,90,90,.5) 30%,rgba(90,90,90,.7) 60%,rgba(90,90,90,.9) 90%)}.wfs__content .wfs__services{padding:60px 45px 0}.wfs__content>.row{position:relative;z-index:1}#DiContent .wfs__services .wfs-services__title{margin:0;font-size:30px;color:#FFF;line-height:36px;font-weight:400;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif;text-transform:uppercase;text-align:center}.wfs-services-item__name,.wfs-services__text,.wfs-services__title-prommu{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}.wfs-services__title-prommu{font-size:48px;line-height:60px;display:block}.wfs-services__line{border-top:6px solid #ff8300;width:100%;max-width:350px;margin:10px auto 50px}.wfs-services__text{font-size:24px;color:#FFF;line-height:30px;margin:0 0 60px;text-align:center;text-transform:uppercase}.wfs__services .wfs-services__item{font-size:18px;text-align:center;color:#FFF;padding:0 20px 50px;min-height:360px}.wfs-services-item__img{width:90px;height:105px;margin:0 auto 10px;background:url(/theme/pic/work-for-students/prolog-icons.png) no-repeat}.wfs-services-item__img.ico1{background-position:0 -340px}.wfs-services-item__img.ico2{background-position:0 -445px}.wfs-services-item__img.ico3{background-position:0 -550px}.wfs-services-item__img.ico4{background-position:0 -655px}.wfs-services-item__img.ico5{background-position:0 -759px}.wfs-services-item__img.ico6{background-position:0 -865px}.wfs-services-item__name{min-height:60px;border-bottom:3px solid #cae100;margin-bottom:25px;font-weight:700;line-height:24px}.wfs-services-item__text{font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif;line-height:22px}.wfs__content .wfs__vacancies{padding:50px 70px 35px}#DiContent .wfs__vacancies .wfs-vacancies__title{margin:0;font-size:50px;color:#636363;line-height:72px;font-weight:400;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;text-transform:uppercase;text-align:center}.wfs-vacancies__title-light{line-height:60px;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif;display:block}.wfs-vacancies__line{border-top:6px solid #abb837;width:100%;max-width:230px;margin:0 auto 35px}.wfs-vacancies__text{font-size:15px;color:#646464;line-height:18px;font-family:Roboto-Regular,Calibri,Arial,sans-serif;margin:0 0 40px;text-align:justify}.wfs-vacancies__list{margin-bottom:20px}.wfs-vacancies__item{width:50%;padding:0 3px 30px 0;display:block;float:left}.wfs-vacancies-item__img,.wfs-vacancies-item__name{width:100%;position:relative;display:block}.wfs-vacancies-item__name{font-size:16px;text-align:center;color:#FFF;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;line-height:38px;text-transform:uppercase;background-color:#abb837}.wfs-vacancies-item__name:before{content:'';width:0;height:0;display:block;position:absolute;top:-9px;left:-9px;margin-left:50%;border-left:9px solid transparent;border-right:9px solid transparent;border-bottom:9px solid #abb837}.wfs-vacancies__item:hover .wfs-vacancies-item__img{-webkit-filter:contrast(.5);filter:contrast(.5)}.wfs-vacancies__item:hover .wfs-vacancies-item__name{background-color:#ff8300}.wfs-vacancies__item:hover .wfs-vacancies-item__name:before{border-bottom:9px solid #ff8300}.wfs-vacancies__small-list{color:#646464;font-size:15px;line-height:22px;font-family:Roboto-Regular,Calibri,Arial,sans-serif;padding:0 20px 0 20px}.wfs-vacancies__bottom-text{color:#646464;font-size:30px;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif;line-height:36px;text-align:center;margin:25px 0 0}#DiContent .wfs-tutorial__title,.wfs-vacancies__bottom-income{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;text-transform:uppercase}.wfs-vacancies__bottom-income{color:#ff8300;display:block}.wfs-vacancies__bottom-line{width:100%;max-width:260px;border-bottom:6px solid #abb837;margin:25px auto 0}.wfs__bg-grey-section{width:100%;position:relative;background-image:url(/theme/pic/work-for-students/bg-grey.jpg);background-size:cover}.wfs__content .wfs-tutorial{padding:35px 35px 15px}#DiContent .wfs-tutorial__title{color:#FFF;font-size:50px;line-height:72px;margin:0;text-align:center}.wfs-tutorial__title-light{display:block;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif}.wfs-tutorial__line{border-top:6px solid #ff8300;width:100%;max-width:135px;margin:0 auto 45px}.wfs-tutorial__list{counter-reset:section}.wfs-tutorial__list .wfs-tutorial__item{padding:115px 25px 30px;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;color:#FFF;font-size:18px;text-transform:uppercase;text-align:center;line-height:24px}.wfs-tutorial__list .wfs-tutorial__item:first-child{padding:105px 25px 30px}.wfs-tutorial__item:before{counter-increment:section;content:counter(section);width:88px;height:88px;display:block;position:absolute;top:0;left:-44px;margin-left:50%;border:3px solid #ff8300;border-radius:44px;line-height:85px;font-size:48px}#DiContent .wfs-tutorial__item-link{width:100%;max-width:235px;line-height:41px;display:block;background:#ff8300;color:#FFF;margin:0 auto 10px;position:relative;z-index:1;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}#DiContent .wfs-register__info .wfs-register__btn,.wfs-tutorial__item-link:before{-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out}#DiContent .wfs-tutorial__item-link:hover{color:#FFF}.wfs-tutorial__item-link:before{content:"";position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#bbc823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;transition:all .3s ease-out}.wfs-tutorial__item-link:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}.wfs__content .wfs__advantages{padding:70px 15px 15px}#DiContent .wfs__advantages .wfs-advantages__title{color:#636363;font-size:50px;line-height:72px;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif;text-align:center;text-transform:uppercase;margin:0}.wfs-advantages-item__name,.wfs-advantages__title-reg{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}.wfs-advantages__title-reg{display:block}.wfs-advantages__line{border-top:6px solid #ff8300;width:100%;max-width:470px;margin:10px auto 70px}.wfs-advantages__list{padding:0 30px}.wfs-advantages__list .wfs-advantages__item{padding:0 0 45px}.wfs-advantages-item__img{margin:0 auto;width:90px;display:block;height:88px;min-width:120px;background:url(/theme/pic/work-for-students/prolog-icons.png) no-repeat;vertical-align:top}.wfs-advantages-item__img.ico1{background-position:0 -980px}.wfs-advantages-item__img.ico2{background-position:0 -1068px}.wfs-advantages-item__img.ico3{background-position:0 -1156px}.wfs-advantages-item__img.ico4{background-position:0 -1244px}.wfs-advantages-item__img.ico5{background-position:0 -1332px}.wfs-advantages-item__info{width:100%;padding-top:20px;color:#646464;font-size:18px;display:inline-block}.wfs-advantages-item__name{text-align:center;font-weight:700;line-height:24px}.wfs-advantages-item__line{border-top:3px solid #abb837;width:100%;max-width:240px;margin:15px auto 10px}.wfs-advantages-item__text{line-height:22px;font-family:RobotoCondensedLight,RobotoCondensedRegular,Calibri,Arial,sans-serif;text-align:justify}.wfs__content .wfs__bottom-text{text-align:justify;padding:30px;color:#646464;font-size:15px;line-height:18px;font-family:Roboto-Regular,Calibri,Arial,sans-serif}.wfs__bottom-text b{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}.wfs-register__preview,.wfs-register__title{font-family:LatoMedium,Calibri,Arial,sans-serif}.wfs__content .wfs-register{position:relative;border-bottom:1px solid #c8c8c8;margin:55px 0 40px;text-align:left;min-height:185px;padding:0 15px 185px}.wfs-register__pic{width:135px;height:185px;position:absolute;left:70px;bottom:-6px;background:url(/theme/pic/register-form/s-reg-logo.jpg) no-repeat}.wfs-register__info{display:block;text-align:center;color:#444649}.wfs-register__title{text-transform:uppercase;margin:0;padding:0 25px;display:inline;font-size:28px}#DiContent .wfs-register h1.wfs-register__title{font-weight:400}.wfs-register__pt{font-size:50px;display:inline-block;line-height:30px;vertical-align:bottom}.wfs-register__info .orange{color:#ff6500}.wfs-register__info .green{color:#abb820}.wfs-register__preview{display:block;font-size:16px;line-height:20px;margin:35px 0;text-align:center}#DiContent .wfs-register__info .wfs-register__btn{width:230px;line-height:40px;display:block;margin:0 auto 20px;text-align:center;color:#FFF;background-color:#ff6500;position:relative;z-index:1;transition:all .3s ease-out}.wfs-register__btn:before{content:"";position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#bbc823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.wfs-register__btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}@media (min-width:768px){.work-for-students__wrapper{min-height:466px;background:url(/theme/pic/work-for-students/bg.jpg) 50% 0 no-repeat;background-size:cover}.wfs-wrapper__title{padding:140px 15px 100px}.wfs-advantages__list{padding:0 120px 0 80px}.wfs-advantages-item__info{width:75%;padding-top:0}.wfs-advantages-item__img{width:23%;height:88px;min-width:120px;display:inline-block}.wfs-wrapper__title-h1{word-break:normal}.wfs__content .wfs-register{padding:0 15px 0 270px}.wfs-register__info{display:inline-block;text-align:left}.wfs__content .wfs__bottom-text{padding:0 70px 60px}.wfs-advantages-item__line{margin:15px 0 10px}.wfs-advantages-item__name{text-align:left}.wfs-vacancies__item{width:25%}.wfs-vacancies__item:nth-child(9){margin-left:25%}}@media (min-width:992px){.wfs-vacancies__item{width:20%}.wfs-vacancies__item:nth-child(9){margin-left:0}}@media (max-width:500px){.wfs__content .wfs__vacancies{padding:50px 15px 35px}.wfs-advantages__title{word-wrap:break-word}}	
	</style>
<?php 
	// если не моб устройство
	//endif; 
?>
	</div><?//закрываем .content-block ?>
</div><?//закрываем .container ?>
<div class="work-for-students__wrapper">
	<div class="wfs-wrapper__title">
		<h1 class="wfs-wrapper__title-h1">
			<span class="wfs-wrapper__title-light wfs-wrapper__title-bg">Временная работа для студентов в Москве</span>
			<span class="wfs-wrapper__title-bg"><span class="wfs-wrapper__title-orange">Работа для студентов</span> в cфере </span>
			<span class="wfs-wrapper__title-bg"><span class="wfs-wrapper__title-green">BTL и Event – </span> мероприятий</span>
		</h1>
	</div>
</div>
<div class="container-fluid wfs__content">
	<div class="row">
		<div class="col-xs-12 wfs-prolog__text">
			<p>В студенческие годы очень важно не только освоить определенную профессию, но еще и самореализоваться, стать независимым. Без определенного источника доходов это невозможно. Зарегистрируйтесь у нас на сайте, и вы получите доступ к широкой базе вакансий для студентов без опыта работы в Москве, России и других странах СНГ.</p>
		</div>
		<div class="wfs-prolog__list">
			<div class="col-xs-6 col-sm-3 wfs-prolog__item">
				<div class="wfs-prolog-item__img ico1"></div>
				<div class="wfs-prolog-item__text">Почасовая работа в дневное время</div>
			</div>
			<div class="col-xs-6 col-sm-3 wfs-prolog__item">
				<div class="wfs-prolog-item__img ico2"></div>
				<div class="wfs-prolog-item__text">Подработка в вечернее или ночное время</div>
			</div>
			<div class="col-xs-6 col-sm-3 wfs-prolog__item">
				<div class="wfs-prolog-item__img ico3"></div>
				<div class="wfs-prolog-item__text">Подработка на выходных</div>
			</div>
			<div class="col-xs-6 col-sm-3 wfs-prolog__item">
				<div class="wfs-prolog-item__img ico4"></div>
				<div class="wfs-prolog-item__text">Разовое участие в мероприятиях (акциях)</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="col-xs-12 wfs-prolog__text" style="padding-top:0">
			<h2 style="text-align:center;margin-top:0">Работа промо-сотрудником – лучшая подработка для студентов с ежедневной оплатой</h2>
			<p>Не так много работодателей готовы принять к себе сотрудника, у которого нет ни опыта, ни диплома, ни специфических навыков. Если вы не хотите подрабатывать на должности грузчика или уборщика, стоит попробовать себя в сфере BTL и Event-мероприятий. К таким специалистам не выдвигают определенных требований, так что получить должность можно без особого труда.<br>Кроме того, промо-персонал ищут:</p>
			<ul>
				<li>на выходные;</li>
				<li>по вечерам;</li>
				<li>по нескольку часов в день.</li>
			</ul>
			<p>График свободный, занятость не будет отвлекать вас от учебы, при этом еще и останется время для отдыха и других занятий.<br>Как правило, выплата происходит каждый день (или же по завершению всего проекта). Деньги будут в вашем распоряжении максимально быстро, что снижает риск мошенничества. Стоит отметить, что уровень оплаты промо-персонала достаточно высокий. Почасовая ставка достигает 300-500 рублей и более. Именно поэтому многие выбирают занятость в сфере BTL.</p>
		</div>
	</div>
</div>
<div class="wfs__grey-section">
	<div class="container-fluid wfs__content">
		<div class="row">
			<div class="col-xs-12 wfs__services">
				<h2 class="wfs-services__title">
					Основные преимущества поиска подработки для студентов
					<span class="wfs-services__title-prommu">на Prommu.com</span>
				</h2>
				<hr class="wfs-services__line">
				<p class="wfs-services__text">Используя наш сайт для временного трудоустройства, вы получаете важные преимущества:</p>
				<div class="wfs-services__list">
					<div class="col-xs-6 col-md-4 wfs-services__item">
						<div class="wfs-services-item__img ico1"></div>
						<div class="wfs-services-item__name">Большое количество предложений от работодателей</div>
						<div class="wfs-services-item__text">Работодатели оставляют заявки ежедневно, чтобы найти ответственных сотрудников.</div>
					</div>
					<div class="col-xs-6 col-md-4 wfs-services__item">
						<div class="wfs-services-item__img ico2"></div>
						<div class="wfs-services-item__name">Удобный фильтр</div>
						<div class="wfs-services-item__text">Выбрать подходящий проект по времени и месту проведения, половой принадлежности и возрастным ограничениям не составит труда.</div>
					</div>
					<div class="col-xs-6 col-md-4 wfs-services__item">
						<div class="wfs-services-item__img ico3"></div>
						<div class="wfs-services-item__name">Push-уведомления</div>
						<div class="wfs-services-item__text">Теперь вы ничего не пропустите - получайте уведомления о новых вакансиях и прочих событиях на портале.</div>
					</div>
					<div class="col-xs-6 col-md-4 wfs-services__item">
						<div class="wfs-services-item__img ico4"></div>
						<div class="wfs-services-item__name">Мобильное приложение Prommu</div>
						<div class="wfs-services-item__text">Поиск работы стал еще удобнее - теперь вы можете найти интересное предложение по работе прямо на мобильном устройстве.</div>
					</div>
					<div class="col-xs-6 col-md-4 wfs-services__item">
						<div class="wfs-services-item__img ico5"></div>
						<div class="wfs-services-item__name">Участие в рейтингах</div>
						<div class="wfs-services-item__text">Рейтинги позволяют выявить лучшего специалиста и лучшего работодателя. Также возможна и обратная ситуация, если работа выполнена недобросовестно.</div>
					</div>
					<div class="col-xs-6 col-md-4 wfs-services__item">
						<div class="wfs-services-item__img ico6"></div>
						<div class="wfs-services-item__name">Безопасность сотрудничества</div>
						<div class="wfs-services-item__text">Вакансии работодателей проходят модерацию перед публикацией на сайте. А значит, каждое предложение частичной занятости является подлинным.</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid wfs__content">
	<div class="row">
		<div class="col-xs-12 wfs__vacancies">
			<h3 class="wfs-vacancies__title">Самые популярные вакансии работы на лето для студентов
				<span class="wfs-vacancies__title-light">в Москве в области BTL</span>
			</h3>
			<hr class="wfs-vacancies__line">
			<p class="wfs-vacancies__text">Чаще всего, работодатели ищут студентов для раздачи листовок. Промоутеры отправляются в людные места, где предлагают прохожим ознакомиться с конкретным рекламным предложением (буклеты, флаера и пр.). Кроме того, они могут консультировать потенциальных покупателей, описывать им преимущества товара и бренда, привлекать внимание публики к конкретному заведению или событию и пр.</p>
			<div class="wfs-vacancies__list">
				<a class="wfs-vacancies__item" href="/vacancy/promouter" title="Промоутер">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac1.jpg">
					<span class="wfs-vacancies-item__name">промоутер</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/merchendayzer" title="Мерчандайзер">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac2.jpg">
					<span class="wfs-vacancies-item__name">Мерчандайзер</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/animator" title="Аниматор">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac3.jpg">
					<span class="wfs-vacancies-item__name">Аниматор</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/konsuljtant" title="Консультант">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac4.jpg">
					<span class="wfs-vacancies-item__name">Консультант</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/modelj" title="Консультант">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac5.jpg">
					<span class="wfs-vacancies-item__name">Промо-модель</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/intervjyuer" title="Интервьюер">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac6.jpg">
					<span class="wfs-vacancies-item__name">Интервьюер</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/rostovaya-kukla" title="Ростовая кукла">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac7.jpg">
					<span class="wfs-vacancies-item__name">Ростовая кукла</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/supervayzer" title="Супервайзер">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac8.jpg">
					<span class="wfs-vacancies-item__name">Супервайзер</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/tayniy-pokupatelj" title="Тайный покупатель">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac9.jpg">
					<span class="wfs-vacancies-item__name">Тайный покупатель</span>
				</a>
				<a class="wfs-vacancies__item" href="/vacancy/hostes" title="Хостес">
					<img class="wfs-vacancies-item__img" src="/theme/pic/work-for-students/vac10.jpg">
					<span class="wfs-vacancies-item__name">Хостес</span>
				</a>		
				<div class="clearfix"></div>
			</div>
			<p style="font-size:15px;color:#646464;font-family: Roboto-Regular,Calibri,Arial,sans-serif;">Но на промоутерах перечень должностей в сфере BTL не заканчиваются. На нашем сайте работы для студентов вы увидите такие вакансии:</p>
			<ul class="wfs-vacancies__small-list">
				<li class="wfs-vacancies__li"><span>аниматор;</span></li>
				<li class="wfs-vacancies__li"><span>хостес;</span></li>
				<li class="wfs-vacancies__li"><span>ростовая кукла;</span></li>
				<li class="wfs-vacancies__li"><span>стендист;</span></li>
				<li class="wfs-vacancies__li"><span>мерчендайзер;</span></li>
				<li class="wfs-vacancies__li"><span>тайный покупатель;</span></li>
				<li class="wfs-vacancies__li"><span>супервайзер и т. д.</span></li>
			</ul>
			<p style="font-size:15px;color:#646464;font-family: Roboto-Regular,Calibri,Arial,sans-serif;border-bottom:6px solid #abb837;padding-bottom:45px">Все профессии достаточно разнообразные, многие из них предполагают общение с людьми, а также участие в интересных шоу-программах и других акциях. Таким образом, занятость принесет вам массу положительных впечатлений.</p>
			<p class="wfs-vacancies__bottom-text">Частичное трудоустройство в одной из этих сфер позволяет без специального опыта выполнять задачи клиента <b class="wfs-vacancies__bottom-income">и получать доход</b></p>
			<hr class="wfs-vacancies__bottom-line">
		</div>
	</div>
</div>
<div class="wfs__bg-grey-section">
	<div class="container-fluid wfs__content">
		<div class="row">
			<div class="col-xs-12 wfs-tutorial">
				<h3 class="wfs-tutorial__title">
					Как найти подработку на лето для студентов 
					<span class="wfs-tutorial__title-light">в Москве с порталом Prommu</span>
				</h3>
				<hr class="wfs-tutorial__line">
				<p style="font-size:18px;color:#ffffff;font-family:Roboto-Regular,Calibri,Arial,sans-serif;">Чтобы найти подходящую вакансию, прежде всего, стоит зарегистрироваться на нашем портале. Далее вы можете:</p>
				<ul style="font-size:18px;color:#ffffff;font-family:Roboto-Regular,Calibri,Arial,sans-serif;padding-left:20px">
					<li>заполнить свою анкету, после чего ждать сообщения по вакансии от работодателя (чем больше подробностей будет в резюме, тем быстрее с вами свяжется организатор промо-акции);</li>
					<li>ознакомиться с нашей обширной базой вакансий.</li>
				</ul>	
				<p style="font-size:18px;color:#ffffff;font-family:Roboto-Regular,Calibri,Arial,sans-serif;margin-bottom:30px">Скачав мобильное приложение PROMMU на свой смартфон, все уведомления о новых предложениях от работодателей будут приходить прямо на ваше мобильное устройство.</p>
				<div class="row wfs-tutorial__list">
					<div class="col-xs-12 col-sm-4 wfs-tutorial__item"><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1'))?>" class="wfs-tutorial__item-link" title='Зарегистрируйтесь'>Зарегистрируйтесь</a>на нашем портале</div>
					<div class="col-xs-12 col-sm-4 wfs-tutorial__item">Заполните анкету максимально подробно</div>
					<div class="col-xs-12 col-sm-4 wfs-tutorial__item">Откликайтесь на вакансии или получайте уведомления о новых предложениях</div>
				</div>
			</div>			
		</div>
	</div>
</div>
<div class="container-fluid wfs__content">
	<div class="row">
		<div class="col-xs-12 wfs__advantages">
			<h3 class="wfs-advantages__title">
				у временной работы для студентов
				<span class="wfs-advantages__title-reg">есть преимущества</span>
			</h3>
			<hr class="wfs-advantages__line">
			<div class="row wfs-advantages__list">
				<div class="col-xs-12 wfs-advantages__item">
					<div class="wfs-advantages-item__img ico1"></div>
					<div class="wfs-advantages-item__info">
						<div class="wfs-advantages-item__name">Свободный график</div>
						<hr class="wfs-advantages-item__line">
						<p class="wfs-advantages-item__text">Вы выбираете сами свободное время, в которое можете работать. Работайте на выходных, в вечернее время или пару часов в день - и у вас останется время на личные дела.</p>
					</div>
				</div>
				<div class="col-xs-12 wfs-advantages__item">
					<div class="wfs-advantages-item__img ico2"></div>
					<div class="wfs-advantages-item__info">
						<div class="wfs-advantages-item__name">Свобода выбора работодателя</div>
						<hr class="wfs-advantages-item__line">
						<p class="wfs-advantages-item__text">Вы всегда можете выбрать - с кем работать в этом месяце, а к кому идти на работу в следующем. Не понравился работодатель - вы без проблем сможете найти другого, условия которого вам подходят. Также вы можете выбрать уровень заработной платы, которая подходит вам.</p>
					</div>
				</div>
				<div class="col-xs-12 wfs-advantages__item">
					<div class="wfs-advantages-item__img ico3"></div>
					<div class="wfs-advantages-item__info">
						<div class="wfs-advantages-item__name">Перспективы на будущее</div>
						<hr class="wfs-advantages-item__line">
						<p class="wfs-advantages-item__text">Опыт работы - часто это требование фигурирует в требованиях при устройстве на работу. А где взять опыт молодому человеку? Временная работа в компании - это неоценимый профессиональный опыт, который пригодиться вам в будущем. Кроме того, при устройстве в другие компании в своем резюме вы сможете указать, что у вас уже есть опыт и тем самым повысить свои шансы при трудоустройстве.</p>
					</div>
				</div>
				<div class="col-xs-12 wfs-advantages__item">
					<div class="wfs-advantages-item__img ico4"></div>
					<div class="wfs-advantages-item__info">
						<div class="wfs-advantages-item__name">Получение новых знаний</div>
						<hr class="wfs-advantages-item__line">
						<p class="wfs-advantages-item__text">Подработка студентом - это один способов научиться чему-либо новому, получить практический опыт в одной или нескольких специальностях.</p>
					</div>
				</div>
				<div class="col-xs-12 wfs-advantages__item">
					<div class="wfs-advantages-item__img ico5"></div>
					<div class="wfs-advantages-item__info">
						<div class="wfs-advantages-item__name">Почасовая оплата</div>
						<hr class="wfs-advantages-item__line">
						<p class="wfs-advantages-item__text">Преимущества почасовой оплаты - порой в сумме за месяц на временной почасовой работе вы можете получит больше, чем специалист на постоянном трудоустройстве. Уровень оплаты в целом зависит от вашего опыта, навыков и профессионализма в той или иной специальности.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid wfs__content">
	<div class="row">
		<div class="col-xs-12 wfs-register">
			<div class="wfs-register__pic"></div>
			<div class="wfs-register__info">
				<span class="wfs-register__pt orange">&bull;</span>
				<span class="wfs-register__pt orange">&bull;</span>
				<span class="wfs-register__pt orange">&bull;</span>
				<h1 class='wfs-register__title'>Регистрация соискателя</h1>
				<span class="wfs-register__pt orange">&bull;</span>
				<span class="wfs-register__preview">Зарегистрируйтесь и получите работу <span class="orange">ЗДЕСЬ</span> и <span class="green">СЕЙЧАС</span></span>
				<a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p'=>'1'))?>" class="wfs-register__btn" title='Зарегистрироваться'>ЗАРЕГИСТРИРОВАТЬСЯ</a>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid wfs__content">
	<div class="row">
		<div class="col-xs-12 wfs__bottom-text">
			<h3 style="text-align:center;font-size:16px;color:#646464;font-family: Roboto-Regular,Calibri,Arial,sans-serif">PROMMU – лучший портал поиска работы для студентов</h3>
			<p style="font-size:15px;color:#646464;font-family: Roboto-Regular,Calibri,Arial,sans-serif">Для соискателей мы предлагаем:</p>
			<ul style="font-size:15px;color:#646464;font-family: Roboto-Regular,Calibri,Arial,sans-serif">
				<li>бесплатный доступ к обширной базе вакансий;</li>
				<li>систему поиска, благодаря которой можно отсортировать предложения по заданным параметрам;</li>
				<li>участие в рейтингах, что помогут определить лучшего работодателя;</li>
				<li>посетить наш блог, где находится много полезной для трудоустройства информации (какую должность из области Event-маркетинга выбрать, какие качества вам пригодятся на работе, как лучше подготовиться к собеседованию и пр.).</li>
			</ul>
			<p style="font-size:15px;color:#646464;font-family: Roboto-Regular,Calibri,Arial,sans-serif">С нами вы без проблем найдете высокооплачиваемую и интересную подработку в своем городе.</p>
		</div>
	</div>
</div>
<div class="container"><?//открываем .content-block ?>
	<div class="content-block"><?//открываем .container ?>