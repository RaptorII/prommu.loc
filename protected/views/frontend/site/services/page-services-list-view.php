<?php 
$baseUrl = Yii::app()->baseUrl;
if(!(MOBILE_DEVICE && !SHOW_APP_MESS)): // optimization ?>
	<?php		
		Yii::app()->getClientScript()->registerCssFile($baseUrl . '/' . MainConfig::$PATH_CSS . '/' . Share::$cssAsset['modalwindow.css']);
		Yii::app()->getClientScript()->registerScriptFile($baseURL . '/theme/js/dist/jquery.maskedinput.min.js', CClientScript::POS_END);
	?>
<?php endif; ?>
<?php if(Share::$UserProfile->type == 3):?>
	<?// РАБОТОДАТЕЛЬ ?>
	<div class='row'>
		<div class='col-xs-12'>
			<div class="services__list employer">
				<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_PREMIUM?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Премиум<br>вакансии</span>
							<span class="services__item-img ico1"></span>
						</span>
					</a>
				</div>
				<div class="services__item shares">
					<span class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Приглашение<br>персонала на вакансии</span>
							<span class="services__item-img ico2"></span>
						</span>
						<span class="services__sub-list">
							<a href="<?=MainConfig::$PAGE_SERVICES_EMAIL?>" class="services__sub-item">
								<span class="services__sub-img">
									<span class="services__item-img ico16"></span>
								</span>
								<span class="services__item-name">Электронная почта</span>
							</a>
							<a href="<?=MainConfig::$PAGE_SERVICES_PUSH?>" class="services__sub-item">
								<span class="services__sub-img">
									<span class="services__item-img ico3"></span>
								</span>
								<span class="services__item-name">PUSH уведомления</span>
							</a>
							<a href="<?=MainConfig::$PAGE_SERVICES_SMS?>" class="services__sub-item">
								<span class="services__sub-img">
									<span class="services__item-img ico5"></span>
								</span>
								<span class="services__item-name">SMS информирование</span>
							</a>
							<a href="<?=MainConfig::$PAGE_SERVICES_SOCIAL?>" class="services__sub-item">
								<span class="services__sub-img">
									<span class="services__item-img ico4"></span>
								</span>
								<span class="services__item-name">Группы социальных сетей PROMMU</span>
							</a>
							<span class="services__sub-bg"></span>
							<span class="services__sub-bg"></span>
							<span class="services__sub-bg"></span>
							<span class="services__sub-bg"></span>
						</span>
					</span>
				</div>
				<div class="services__item">
					<a href="javascript:void(0)" class="services__item-block" data-disable="1">
						<span class="services__item-circle">
							<span class="services__item-name">Геолокация</span>
							<span class="services__item-img ico6"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Личный<br>менеджер / Аутсорсинг</span>
							<span class="services__item-img ico7"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_OUTSTAFFING?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Аутстаффинг</span>
							<span class="services__item-img ico8"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?//javascript:void(0)?><?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>" class="services__item-block" <?//data-disable="1"?>>
						<span class="services__item-circle">
							<span class="services__item-name">Корпоративная<br>карта "Промму"</span>
							<span class="services__item-img ico9"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_MEDICAL?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Заказать<br>медицинскую книгу</span>
							<span class="services__item-img ico14"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_API?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Получение<br>АПИ ключа</span>
							<span class="services__item-img ico10"></span>
						</span>
					</a>
				</div>
				<div class="clearfix"></div>
  			</div> 
		</div>
	</div>
	<?php if(Yii::app()->user->hasFlash('success')): ?>
		<script type="text/javascript">
			var arSuccessMess = <?=json_encode(Yii::app()->user->getFlash('success'))?>;
			$(function(){
				if(arSuccessMess.event==='social')
					var itm = $('.repost-to-social-form').clone();
				else if(arSuccessMess.event==='email' || arSuccessMess.event==='push')
					var itm = $('.email-invitation-form').clone();
				else
					var itm = $('.services-finish-form').clone();
				itm.toggleClass('services-form tmpl');
				ModalWindow.open({ content: itm, action: { active: 0 }, additionalStyle:'dark-ver' });
			});
		</script>
	<?php endif ?>
<?php endif ?>

<?php if(Share::$UserProfile->type == 2): ?>
	<?// СОИСКАТЕЛЬ ?>
	<div class='row'>
		<div class='col-xs-12'>
			<div class="services__list applicant">
				<?/*<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_PUSH?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Пуш<br>уведомления</span>
							<span class="services__item-img ico11"></span>
						</span>
					</a>
				</div>*/?>
				<div class="services__item">
					<a href="javascript:void(0)" class="services__item-block" data-disable="1">
						<span class="services__item-circle">
							<span class="services__item-name">Геолокация</span>
							<span class="services__item-img ico12"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?//javascript:void(0)?><?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>" class="services__item-block" <?//data-disable="1"?>>
						<span class="services__item-circle">
							<span class="services__item-name">Корпоративная<br>карта "Промму"</span>
							<span class="services__item-img ico13"></span>
						</span>
					</a>
				</div>
				<div class="services__item">
					<a href="<?=MainConfig::$PAGE_SERVICES_MEDICAL?>" class="services__item-block">
						<span class="services__item-circle">
							<span class="services__item-name">Заказать<br>медицинскую книгу</span>
							<span class="services__item-img ico15"></span>
						</span>
					</a>
				</div>
				<div class="clearfix"></div>
  			</div> 
		</div>
	</div>
<?php endif ?>


<?php if(Share::$UserProfile->type != 3 && Share::$UserProfile->type != 2): ?>
	<?php if(!(MOBILE_DEVICE && !SHOW_APP_MESS)): // optimization ?>
		<?php
			Yii::app()->getClientScript()->registerScriptFile($baseURL . '/theme/js/modernizr.min.js', CClientScript::POS_END);
			Yii::app()->getClientScript()->registerScriptFile($baseURL . '/theme/js/main.min.js', CClientScript::POS_END);
		?>
		<style type="text/css">
			/*	/theme/css/reset.css	*/
			a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{margin:0;padding:0;border:0;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,main,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:after,blockquote:before,q:after,q:before{content:'';content:none}.img img{width:100%}table{border-collapse:collapse;border-spacing:0}
			/*	/theme/css/style.css	*/
			#DiContent a:hover,a{text-decoration:none}#DiContent.page-services .service-menu ul{margin-bottom:14px}#DiContent a:hover{color:#abb820;transition:.3s all}#DiContent a{color:#212121}#DiContent .btn-green-02-wr a{display:inline-block;margin:10px 0;text-decoration:none;text-transform:uppercase;font-family:RobotoCondensed-Regular;border:1px solid #212121;font-size:13px;text-align:center;transition:.3s all;background:#fff;color:#212121}body{background-color:#fff;font-family:Roboto-Regular,verdana,arial,Myriad-Pro,verdana,arial,Verdana,Arial;font-size:14px;line-height:1.42857143;color:#333}.cd-faq-categories a,header h1{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;color:#fff}body::after{position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(78,83,89,.8);visibility:hidden;opacity:0;-webkit-transition:opacity .3s 0s,visibility 0s .3s;-moz-transition:opacity .3s 0s,visibility 0s .3s;transition:opacity .3s 0s,visibility 0s .3s}body.cd-overlay::after{visibility:visible;opacity:1;-webkit-transition:opacity .3s 0s,visibility 0s 0s;-moz-transition:opacity .3s 0s,visibility 0s 0s;transition:opacity .3s 0s,visibility 0s 0s}@media only screen and (min-width:768px){body::after{display:none}}header{position:relative;height:180px;line-height:180px;text-align:center;background-color:#a9c056}header h1{font-size:20px;font-size:1.25rem}@media only screen and (min-width:1024px){header{height:240px;line-height:240px}header h1{font-size:36px;font-size:2.25rem;font-weight:300}}.cd-faq{width:90%;max-width:1024px;margin:4em auto;box-shadow:0 1px 5px rgba(0,0,0,.1)}.cd-faq:after{content:"";display:table;clear:both}@media only screen and (min-width:768px){.cd-faq{position:relative;margin:4em auto;box-shadow:none}}@media (max-width:768px){.airSticky_relative{width:320px;position:relative;top:auto}.cd-faq-trigger{font-size:1rem;margin:0;padding:0 20px 30px 50px}}.cd-faq-categories a{position:relative;display:block;overflow:hidden;height:50px;line-height:50px;padding:0 28px 0 16px;background-color:#4e5359;white-space:nowrap;border-bottom:1px solid #555b61;text-overflow:ellipsis}.cd-faq-categories li:last-child a{border-bottom:none}@media only screen and (min-width:768px){.cd-faq-categories{width:20%;float:left;box-shadow:0 1px 2px rgba(0,0,0,.08)}.cd-faq-categories a{font-size:13px;font-size:.8125rem;font-weight:600;padding:0 24px;-webkit-transition:background .2s,padding .2s;-moz-transition:background .2s,padding .2s;transition:background .2s,padding .2s}.cd-faq-categories a::after,.cd-faq-categories a::before{display:none}.no-touch .cd-faq-categories a:hover{background:#555b61}.no-js .cd-faq-categories{width:100%;margin-bottom:2em}}@media only screen and (min-width:1024px){.cd-faq-categories{position:absolute;top:0;left:0;width:200px;z-index:2}.cd-faq-categories a::before{display:block;top:0;right:auto;left:0;height:100%;width:3px;background-color:#a9c056;opacity:0;-webkit-transition:opacity .2s;-moz-transition:opacity .2s;transition:opacity .2s}.cd-faq-categories .selected{background:#42464b!important}.cd-faq-categories .selected::before{opacity:1}.cd-faq-categories.is-fixed{position:fixed}.no-js .cd-faq-categories{position:relative}}.cd-faq-items{position:fixed;height:100%;width:90%;top:0;right:0;background:#fff;padding:0 5% 1em;overflow:auto;-webkit-overflow-scrolling:touch;z-index:1;-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-transform:translateZ(0) translateX(100%);-moz-transform:translateZ(0) translateX(100%);-ms-transform:translateZ(0) translateX(100%);-o-transform:translateZ(0) translateX(100%);transform:translateZ(0) translateX(100%);-webkit-transition:-webkit-transform .3s;-moz-transition:-moz-transform .3s;transition:transform .3s}.cd-faq-items.slide-in{-webkit-transform:translateZ(0) translateX(0);-moz-transform:translateZ(0) translateX(0);-ms-transform:translateZ(0) translateX(0);-o-transform:translateZ(0) translateX(0);transform:translateZ(0) translateX(0)}.no-js .cd-faq-items{position:static;height:auto;width:100%;-webkit-transform:translateX(0);-moz-transform:translateX(0);-ms-transform:translateX(0);-o-transform:translateX(0);transform:translateX(0)}@media only screen and (min-width:768px){.cd-faq-items{position:static;height:auto;width:78%;float:right;overflow:visible;-webkit-transform:translateZ(0) translateX(0);-moz-transform:translateZ(0) translateX(0);-ms-transform:translateZ(0) translateX(0);-o-transform:translateZ(0) translateX(0);transform:translateZ(0) translateX(0);padding:0;background:0 0}}@media only screen and (min-width:1024px){.cd-faq-items{float:none;width:100%;padding-left:220px}.no-js .cd-faq-items{padding-left:0}}.cd-close-panel{position:fixed;top:5px;right:-100%;display:block;height:40px;width:40px;overflow:hidden;text-indent:100%;white-space:nowrap;z-index:2;-webkit-transform:translateZ(0);-moz-transform:translateZ(0);-ms-transform:translateZ(0);-o-transform:translateZ(0);transform:translateZ(0);-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-transition:right .4s;-moz-transition:right .4s;transition:right .4s}.cd-close-panel::after,.cd-close-panel::before{position:absolute;top:16px;left:12px;display:inline-block;height:3px;width:18px;background:#6c7d8e}#eight i,#five i,#four i,#one i,#seven i,#six i,#two i{height:30px}#eight i,#five i,#four i,#one i,#seven i,#six i,#tree i,#two i{position:absolute;width:37px;left:2px}.cd-close-panel::before{-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg)}.cd-close-panel::after{-webkit-transform:rotate(-45deg);-moz-transform:rotate(-45deg);-ms-transform:rotate(-45deg);-o-transform:rotate(-45deg);transform:rotate(-45deg)}.cd-close-panel.move-left{right:2%}@media only screen and (min-width:768px){.cd-close-panel{display:none}}.cd-faq-group{display:none}#eight i,#five i,#one i,#seven i,#six i,#tree i,#two i,.cd-faq-group.selected,.cd-faq-trigger,.no-js .cd-faq-group{display:block}.cd-faq-group .cd-faq-title{background:0 0;box-shadow:none;margin:1em 0}.no-touch .cd-faq-group .cd-faq-title:hover{box-shadow:none}.cd-faq-group .cd-faq-title h2{text-transform:uppercase;font-size:12px;font-size:.75rem;font-weight:700;color:#bbbbc7}@media only screen and (min-width:768px){.cd-faq-group{display:block}.cd-faq-group>li{background:#fff;margin-bottom:6px;box-shadow:0 1px 2px rgba(0,0,0,.08);-webkit-transition:box-shadow .2s;-moz-transition:box-shadow .2s;transition:box-shadow .2s}.no-touch .cd-faq-group>li:hover{box-shadow:0 1px 10px rgba(108,125,142,.3)}.cd-faq-group .cd-faq-title{margin:2em 0 1em}.cd-faq-group:first-child .cd-faq-title{margin-top:0}}a{color:#212121}#one i{top:1px;background:url(/theme/pic/1.png) no-repeat}#two i{top:1px;background:url(/theme/pic/2.png) no-repeat}#tree i{height:35px;top:-7px;background:url(/theme/pic/3.png) no-repeat}#four i{display:block;top:1px;background:url(/theme/pic/4.png) no-repeat}#five i{top:-7px;background:url(/theme/pic/5.png) no-repeat}#six i{top:17px;background:url(/theme/pic/6.png) no-repeat}#seven i{top:-3px;background:url(/theme/pic/8.png) no-repeat}#eight i{top:-3px;background:url(/theme/pic/7.png) no-repeat}.cd-faq-trigger{position:relative;margin:1.6em 0 .4em;line-height:1.2}@media only screen and (min-width:768px){.cd-faq-trigger{font-size:1rem;margin:0;padding:0 20px 30px 45px}.cd-faq-trigger::after,.cd-faq-trigger::before{position:absolute;right:24px;top:50%;height:2px;width:13px;background:#cfdca0;-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-transition-property:-webkit-transform;-moz-transition-property:-moz-transform;transition-property:transform;-webkit-transition-duration:.2s;-moz-transition-duration:.2s;transition-duration:.2s}}.cd-faq-content p{font-size:14px;font-size:.875rem;line-height:1.4;color:#6c7d8e}a.cd-faq-trigger{outline:0}#DiContent li.content-visible>.cd-faq-trigger{padding:0 20px 20px 45px;font-weight:700;color:#abb820;font-size:1.1rem}#DiContent .btn-green-02-wr a{width:100%;padding:0;line-height:28px}#DiContent.page-services .service-menu ul.airSticky.fixed{position:fixed}#DiContent .btn-green-02-wr{width:50%;padding:0 5px;margin-right:-2.5px}@media only screen and (min-width:768px){.cd-faq-content{display:none;margin:0 0 10px}.cd-faq-content p{line-height:1.6}.no-js .cd-faq-content{display:block}}
			#DiContent.page-services .service .services-page__invita{
				background: url(/images/servicesgr/akcii.jpg) no-repeat;
				background-size: cover;
			}
			.services__invita-block{
				width: 100%;
				display: table;
				padding: 65px 5px 0;
			}
			.serv__invita-row:first-child{ display:none; }
			.serv__invita-name{
				width: 25%;
				display: table-cell;
				text-align: center;
				color: #FFFFFF;
				text-transform: uppercase;
				font-size: 12px;
				padding: 5px;
				vertical-align: middle;
				position: relative;
			}
			.serv__invita-name:before{
				content: '';
				background-color: #ff8300;
				position: absolute;
				top: 0;
				right: 5px;
				bottom: 0;
				left: 5px;
				z-index: 0;
			}
			.serv__invita-name span{
				position: relative;
				z-index: 1;
				padding: 0 5px;
			}
			.serv__invita-btns{
				width: 50%;
				float: left;
				padding: 0 5px 10px;
			}
			.serv__invita-btns .serv__invita-name{
				background-color: #ff8300;
			}
			.serv__invita-btns .serv__invita-name:before{ content:initial; }
			#DiContent .order-btn a,
			#DiContent a.serv__invita-link{
				display: block;
				text-align: center;
				text-transform: uppercase;
				line-height: 28px;
				color: #FFFFFF;
				font-size: 14px;
				background-color: #abb820;
				position: relative;
				z-index: 1;
				border: 1px solid #ffffff;
			}
			#DiContent a.serv__invita-link{ border-right: 0px }
			#DiContent a.serv__invita-link,
			.serv__invita-btns .order-btn{
				width: 50%;
				float: left;
			}
			#DiContent .serv__invita-btns a:before{
				content: "";
				position: absolute;
				z-index: -1;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background: #ff8300;
				-webkit-transform: scaleX(0);
				transform: scaleX(0);
				-webkit-transform-origin: 0 50%;
				transform-origin: 0 50%;
				-webkit-transition-property: transform;
				transition-property: transform;
				-webkit-transition-duration: 0.3s;
				transition-duration: 0.3s;
				-webkit-transition-timing-function: ease-out;
				transition-timing-function: ease-out;
			}
			#DiContent .serv__invita-btns a:hover:before{
				-webkit-transform: scaleX(1);
				transform: scaleX(1);
			}
			.serv__invita-btns:nth-child(3) .serv__invita-name{ height:44px }
			/*
			*		MEDIA
			*/
			@media (min-width: 768px){
				.serv__invita-btns:nth-child(3) .serv__invita-name{ height:initial }
			}
			@media (min-width: 1200px){
				.serv__invita-row{ display: table-row }
				.serv__invita-row:first-child{ display:table-row  }
				.serv__invita-btns{
					width: 25%;
					float: none;
					display: table-cell;
				}
				.serv__invita-btns .serv__invita-name{ display:none }
			}
		</style>	
	<?php endif; ?>
	<div class='row airSticky_stop-block'>
		<div class='col-xs-12 col-sm-4 col-lg-3'>
			<? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/services/menu-for-guest.php'; ?>
		</div>


		<div class='col-xs-12 col-sm-8 col-lg-9 service-content'>
			<div class="header-022 services-page__title" id="premvac">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="premium-vacancy"></a><a href="<?=MainConfig::$PAGE_SERVICES_PREMIUM?>">Премиум-вакансии</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/servbg-06.jpg" alt="Премиум вакансии">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Чтобы в кратчайшие сроки найти временных сотрудников для проведения BTL- и event-мероприятия, лучшее решение - обеспечить максимально эффективный поиск персонала! С этой целью работодатель может воспользоваться услугой «Премиум-вакансия». С ее помощью добиться желаемого результата — привлечь к частичной занятости промо-персонал — удается в разы быстрее, чем при стандартной публикации предложения о работе!</p>
				</div>
				<div class="more btn-white-green-wr" data-id="39">
					<a href="<?=MainConfig::$PAGE_SERVICES_PREMIUM?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="shares">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="email-invitation"></a><a href="javascript:void(0)">Приглашениие персонала на вакансии</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img services-page__invita">
					<div class="services__invita-block">
						<div class="serv__invita-row">
							<div class="serv__invita-name"><span>Электронная почта</span></div>
							<div class="serv__invita-name"><span>PUSH уведомления</span></div>
							<div class="serv__invita-name"><span>SMS информирование</span></div>
							<div class="serv__invita-name"><span>Группы социальных сетей PROMMU</span></div>
						</div>
						<div class="serv__invita-row">
							<div class="serv__invita-btns">
								<div class="serv__invita-name">Электронная почта</div>
								<a class="serv__invita-link" href="<?=MainConfig::$PAGE_SERVICES_EMAIL?>">Смотреть</a>
								<div class="order-btn" data-id="327"><a href="javascript:void:(0)">Заказать</a></div>
								<div class="clearfix"></div>
							</div>	
							<div class="serv__invita-btns">
								<div class="serv__invita-name">PUSH уведомления</div>
								<a class="serv__invita-link" href="<?=MainConfig::$PAGE_SERVICES_PUSH?>">Смотреть</a>
								<div class="order-btn" data-id="push"><a href="javascript:void:(0)">Заказать</a></div>
								<div class="clearfix"></div>
							</div>
							<div class="serv__invita-btns">
								<div class="serv__invita-name">SMS информирование</div>
								<a class="serv__invita-link" href="<?=MainConfig::$PAGE_SERVICES_SMS?>">Смотреть</a>
								<div class="order-btn" data-id="sms"><a href="javascript:void:(0)">Заказать</a></div>
								<div class="clearfix"></div>
							</div>
							<div class="serv__invita-btns">
								<div class="serv__invita-name">Группы социальных сетей PROMMU</div>
								<a class="serv__invita-link" href="<?=MainConfig::$PAGE_SERVICES_SOCIAL?>">Смотреть</a>
								<div class="serv__invita-btn order-btn" data-id="41"><a href="javascript:void:(0)">Заказать</a></div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>

			<div class="header-022 services-page__title" id="email">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="email-invitation"></a><a href="<?=MainConfig::$PAGE_SERVICES_EMAIL?>">Электронная почта</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/email-invitation.jpg" alt="Пуш уведомления">
				</div>
				<div class="text"><p style="font-family:Roboto-Regular,verdana,arial">В процессе поиска сотрудников для частичной занятости работодателю необходимо максимально оперативно сформировать подходящую команду, чтобы проинструктировать и подготовить ее к проведению промо-мероприятия. В этом случае одной лишь публикации вакансии может оказаться недостаточно, ведь на изучение откликов соискателей и оценку кандидатов потребуется время.<br>Гораздо эффективнее сразу после размещения вакансии дополнительно разослать предложение о временной работе наиболее подходящим сотрудникам использую для этого фильтры.<br>В этом аспекте отлично подойдет услуга: <stron style="font-style:italic">"Уведомление зарегистрированного персонала на электронную почту"</strong>.</p></div>
				<div class="more btn-white-green-wr" data-id="40">
					<a href="<?=MainConfig::$PAGE_SERVICES_EMAIL?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="push">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="push-notification"></a><a href="<?=MainConfig::$PAGE_SERVICES_PUSH?>">Push-уведомления</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/push.jpg" alt="Пуш уведомления">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Поиск временной работы и персонала для проведения промо-мероприятий требует от нанимателей и соискателей оперативно реагировать на поступающие предложения. С этой целью можно либо постоянно находиться за монитором ПК или регулярно обновлять информацию на мобильном устройстве, либо воспользоваться услугой «Push-уведомления» и посещать личный кабинет на сайте только при поступлении персональных оповещений. Разумеется, второй путь более эффективный и удобный!</p>
				</div>
				<div class="more btn-white-green-wr" data-id="40">
					<a href="<?=MainConfig::$PAGE_SERVICES_PUSH?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="double">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="publication-vacancy-social-net"></a><a href="<?=MainConfig::$PAGE_SERVICES_SOCIAL?>">Дублирование вакансий в группы соц. сетей Prommu</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/dublirovanie.jpg" alt="Дублирование вакансии в группах PROMMU соц. сетей">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Чтобы найти персонал для проведения Event- или BTL-мероприятия как можно быстрее, необходимо разместить свое предложение о временной работе на ресурсах, доступных максимальному числу заинтересованных пользователей. Для этого мы рекомендуем зарегистрироваться на специализированном сайте поиска сотрудников Prommu и воспользоваться дополнительным функционалом сервиса. В частности, речь идет об услуге публикации вакансий в группах соцсетей.</p>
				</div>
				<div class="more btn-white-green-wr" data-id="41">
					<a href="<?=MainConfig::$PAGE_SERVICES_SOCIAL?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="sms">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="sms-informing-staff"></a><a href="<?=MainConfig::$PAGE_SERVICES_SMS?>">СМС-информирование персонала</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/sms-info.jpg" alt="СМС-информирование выбранного персонала">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">В процессе поиска сотрудников для частичной занятости работодателю необходимо максимально оперативно сформировать подходящую команду, чтобы проинструктировать и подготовить ее к проведению промо-мероприятия. В этом случае одной лишь публикации вакансии может оказаться недостаточно, ведь на изучение откликов соискателей и оценку кандидатов потребуется время. Гораздо эффективнее сразу разослать предложение о временной работе наиболее подходящим сотрудникам. Для этого существует услуга «СМС-информирование персонала»!</p>
				</div>
				<div class="more btn-white-green-wr" data-id="46">
					<a href="<?=MainConfig::$PAGE_SERVICES_SMS?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="geo">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="geolocation-staff"></a><a href="<?=MainConfig::$PAGE_SERVICES_GEO?>">Геолокация</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/geo.jpg" alt="Геолокация">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Геолокация — возможность отслеживания местоположения устройства (смартфона, планшета), а значит, и его владельца, по беспроводной онлайн-связи. Данный инструмент с успехом используется при управлении промо-персоналом, нанятым на условиях частичной занятости. Услуга геолокации доступна всем работодателям, зарегистрированным на нашем сайте поиска временной работы и сотрудников для BTL-, event-мероприятий!</p>
				</div>
				<div class="more btn-white-green-wr" data-id="49">
					<a href="<?=MainConfig::$PAGE_SERVICES_GEO?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="manager">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="personal-manager-outsourcing"></a><a href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>">Личный менеджер и аутсорсинг персонала</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/personal-manager.jpg" alt="">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Организация БТЛ-мероприятий – достаточно ответственная задача, которая требует особого подхода. С помощью аутсорсинга можно значительно упростить этот процесс, передав часть заданий по подбору персонала для нашей компании. Специализированная площадка по поиску сотрудников в сфере маркетинга «Промму» сможет с легкостью помочь вам с этим вопросом.</p>
				</div>
				<div class="more btn-white-green-wr" data-id="37">
					<a href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="outstaff">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="оutstaffing"></a><a href="<?=MainConfig::$PAGE_SERVICES_OUTSTAFFING?>">Аутстаффинг персонала</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/autstaffing.jpg" alt="Аутстаффинг">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">С помощью аутстаффинга можно эффективно провести рекламную кампанию любого типа, при этом фирма-заказчик значительно экономит время, силы и деньги, необходимые для оформления подходящего персонала в свой штат. Специализированная площадка для поиска сотрудников в сфере BTL «Промму» станет хорошим выбором, чтобы заключить договор аутстаффинга для организации как кратковременных, так и длительных мероприятий.</p>
				</div>
				<div class="more btn-white-green-wr" data-id="38">
					<a href="<?=MainConfig::$PAGE_SERVICES_OUTSTAFFING?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="card">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="сorp-card-prommu"></a><a href="<?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>">Получение корпоративной карты Prommu</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/card.jpg" alt="Получение банковской карты">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Банковская карта «Промму» - это новый функциональный платежный инструмент для каждого, кто имеет или ищет дополнительный источник дохода. Это не только простота и удобство, но и безопасность в использовании и множество выгодных предложений. Карта представляет собой обыкновенную кредитку, с помощью которой вы можете расплачиваться вживую и онлайн, а также снимать наличные деньги в банкомате.</p>
				</div>
				<div class="more btn-white-green-wr" data-id="43">
					<a href="<?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="med">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="api-key-prommu"></a><a href="<?=MainConfig::$PAGE_SERVICES_MEDICAL?>">Получение медицинской книги</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/servbg-07.jpg" alt="Получение медицинской книги">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">При трудоустройстве многие работодатели требуют предоставить медкнижку. Для того, чтобы оформить ее нужно потратить много времени на обход большого списка узконаправленных специалистов. Для многих соискателей трата драгоценного времени нецелесообразна, так как нужно приступить к работе максимально быстро. С помощью нашего портала можно оперативно оформить медкнижку.</p>
				</div>
				<div class="more btn-white-green-wr" data-id="213">
					<a href="<?=MainConfig::$PAGE_SERVICES_MEDICAL?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>


			<div class="header-022 services-page__title" id="api">
				<hr class='services-page__line'>
				<div>
					<a class="anc" name="api-key-prommu"></a><a href="<?=MainConfig::$PAGE_SERVICES_API?>">Получение API-ключа</a>
				</div>
			</div>
			<div class="service">
				<div class="img services-page__img">
					<img src="/images/servicesgr/servbg-05.jpg" alt="Получение API ключа">
				</div>
				<div class="text">
					<p style="text-align: justify; font-family: Roboto-Regular, verdana, arial, Myriad-Pro;">Подбор квалифицированного персонала как для постоянной, так и для временной работы — важнейший этап менеджмента сотрудников. Как известно, кадры в компании решают все! Поэтому при поиске персонала HR-менеджеры уделяют отбору кандидатов максимум внимания.<br>И в этом случае доступ к базам данных соискателей — важное преимущество! Сайт поиска временной работы и персонала для промо-мероприятий Prommu.com предоставляет разработчикам и системным администраторам возможность предложить услуги своих пользователей на внешних ресурсах!</p>
				</div>
				<div class="more btn-white-green-wr" data-id="50">
					<a href="<?=MainConfig::$PAGE_SERVICES_API?>">Смотреть</a>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
<?php endif ?>

<? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/services/popups.php'; ?>