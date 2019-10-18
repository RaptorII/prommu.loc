<? $this->renderPartial('../site/page-index.tpl_css'); ?>
<div class="m-wrapper">
	<?if( Yii::app()->session['au_us_type'] < 2 ):?>
		<div class="m-wrapper__btn-block">
			<a 
			href="javascript:void(0)" 
			class="m-wrapper__btn m-wrapper__green-btn" 
			onclick="$('html, body').animate({scrollTop: $('#reg-how-block').offset().top - 30});" >Регистрация</a>
			<a href="<?=MainConfig::$PAGE_LOGIN?>" class="m-wrapper__btn m-wrapper__white-btn">Вход</a>
		</div>
	<?endif;?>
	<div class="m-wrapper__m-title">
		<div class="m-title__orange">Сервис №1</div>
		<div class="m-title__green">в поиске временной работы</div>
		<div class="m-title__grey">и персонала для BTL и Event-мероприятий</div>
	</div>
	<div class="m-wrapper__table-block">
		<a href="<?=MainConfig::$PAGE_SEARCH_PROMO?>" class="m-wrapper__btn m-wrapper__orange-btn">Быстро найти нужный персонал</a>
		<a href="<?=MainConfig::$PAGE_SEARCH_VAC?>" class="m-wrapper__btn m-wrapper__white-btn">Найти интересную временную работу</a>
	</div>
</div>
<div id="DiSliderWrapp">
	<div class="slider-wrapp">
		<div class="vacancy-wrapp">
			<div class="vacancy-container">
				<div class="vacancy">
					<div class="vacancy__buttons">
						<div class="search__right">
							<p class="search__cat-module">
								<a class="selectInd search-category active" data-val='1' href="javascript:void(0)">Вакансии</a>
								<a class="selectInd search-category" data-val='2' href="javascript:void(0)">Анкеты</a>
								<a class="selectInd search-category" data-val='3' href="javascript:void(0)">Компании</a>
								<input type="hidden" name="search-category" id='CBvacancy' value='1'>
							</p>
						</div>
						<div class="search-module__left-btn">
							<?php if(Share::$UserProfile->type==3): ?>
								<a class="left" href="<?=MainConfig::$PAGE_VACPUB?>">Опубликовать вакансию</a>
							<?php endif; ?>
							<?php if(!in_array(Share::$UserProfile->type, [2,3])): ?>
								<a class="left" href="<?= MainConfig::$PAGE_REGISTER ?>?p=1">Разместить анкету</a>
								<a class="left" href="<?= MainConfig::$PAGE_REGISTER ?>?p=2">Опубликовать вакансию</a>
							<?php endif; ?>
						</div>
						<div class="search-container">
							<div class="search">
								<div class="row">
									<div class="col-xs-12">
										<div class="search__search-line clearfix" ></div>
										<form action="/vacancy" method="GET" id="FmSearch">
											<div class="search__search-line clearfix">
												<input type="hidden" id="NamePromo" name='qs' value=""/>
												<input type="hidden" id="EdSearchPromo" name='posts[]' value=""/>
												<div class="text"><input type="text" id="EdSearch" name='post[<?$posts?>]' placeholder="Введите название вакансии..." data-ph1="Введите название вакансии..." data-ph2="Введите название резюме..." data-ph3="Введите название компании..." style='background: rgb(245, 245, 245); width: 97%; border: 0; font-size: 16px; line-height: 17px;' /></div>
												<div id="multyselect-cities"></div>
												<button type="submit">Найти</button>
											</div>
										</form>
									</div>
								</div>
								<a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" rel="nofollow" class="app-top-link" target="_blank"></a>
							</div>
						</div>
						<?php if( Share::$UserProfile->exInfo->status != 3 ): ?>
							<div class="search__right">
								<p class="search__wrd-module">Например:  
									<a  class="selectInd search__small" id="#prom" href="javascript:void(0)"><ins class='search-word'>промоутер</ins></a>
									<a  class="selectInd search__small" id="#mench" href="javascript:void(0)"><ins class='search-word'> мерчендайзер</ins></a>
								</p>
							</div>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
		<div id="DiOwlSlider">
			<div class="item" style="background: url(/images/slides/4.jpg) 50% 0% no-repeat;"></div>
		</div>
	</div>
</div>

<div id="DiHr01"></div>

<div id="DiContent"  class="page-index">
	<div class="container">
		<div class="row">
			<div class="how-block">
				<a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" rel="nofollow" class="app-top-mob-link" target="_blank"></a>    
				<div class="header h-indent first"><hr class="line"><h2><span>Как найти работу или персонал<br/> на нашем портале</span></h2></div>

				<div class="col-xs-12 col-sm-4 col-md-3 how-block__item" id='reg-how-block'>
					<div class="vacancy" >
						<div class="how-block__icon icon100" title="Зарегистрируйтесь">
                            <span class="icn-note-page-prommu color-green "></span>
                        </div>
						<h2 class="how-block__title">Зарегистрируйтесь на портале</h2>
						<p class="how-block__text">Чтобы начать искать работу или размещать вакансии своей компании, вам необходимо зарегистрироваться на портале и заполнить свой профиль.</p>
						<br/>
                        <?php
                        /*
						<a href="/user/register?p=2"><ins>Регистрация для работодателя</ins></a><br/>
						<a href="/user/register?p=1"><ins>Регистрация для соискателя</ins></a>
                        */
                        ?>

                        <a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER)?>" class="reg btn__orange">
                            <ins>Регистрация</ins>
                        </a>
						<?if( Yii::app()->session['au_us_type'] < 2 ):?>
						<div class="m-how-block__reg-block">
<!--                            <a href="--><?//=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER)?><!--" class="m-wrapper__btn m-wrapper__white-btn">-->
<!--                                <span>Регистрация</span>-->
<!--                            </a>-->
							<a href="/user/register?p=2" class="m-wrapper__btn m-wrapper__white-btn">Регистрация для работодателя</a>
							<a href="/user/register?p=1" class="m-wrapper__btn m-wrapper__whiteo-btn">Регистрация для соискателя</a>
						</div>
						<?endif?>
					</div>
				</div>

				<div class="hidden-xs hidden-sm indent-block"></div>

				<div class="col-xs-12 col-sm-4 col-md-3 how-block__item locate">
					<div class="vacancy">
						<div class="how-block__icon icon100" title="Разместите анкету">
                            <span class="icn-beidg-prommu color-green "></span>
                        </div>
						<h2 class="how-block__title">Разместите анкету или опубликуйте вакансию</h2>
						<p class="how-block__text">Если вы соискатель - разместите анкету по специальности, которая вам интересна. Если вы работодатель - опубликуйте одну или несколько вакансий.</p>
					</div>
				</div>

				<div class="hidden-xs hidden-sm indent-block"></div>

				<div class="col-xs-12 col-sm-4 col-md-3 how-block__item">
					<div class="vacancy">
						<div class="how-block__icon icon100" title="Найдите интересную работу">
                            <span class="icn-heads-circle-prommu color-green "></span>
                        </div>
						<h2 class="how-block__title">Найдите интересную работу или квалифицированный персонал</h2>
						<p class="how-block__text">Откликайтесь на вакансии, которые вас заинтересовали, получайте push-уведомления о новых вакансиях.</p>
						<p class="how-block__text">Оставляйте отзывы и участвуйте в рейтинге.</p>
						<br/>
						<p class="how-block__text">Также рекламодатели при необходимости могут воспользоваться гибким фильтром для поиска персонала.</p>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php if( count($content['vacancies']) ): ?>
				<div class="vacancies">
					<div class="header h-indent vacancies__title"><hr class="line">
                        <h2>
                            <i class="icon90 icn-iii-prommu color-green"></i>
                            <span>Вакансии</span>
                        </h2>
                    </div>
					<div class="container">
						<div class="vacancies__list">
							<?php foreach ($content['vacancies'] as $vac):?>
								<div class="vacancies__item <?=($vac['ispremium']?'premium':'')?>">
									<? if($vac['ispremium']): ?>
										<div class="vacancies-item__prem-icon js-g-hashint" title="Премиум вакансия"></div>
										<div class="vacancies-item__prem-border"></div>
									<? else: ?>
										<div class="vacancies-item__norm-border"></div>
										<div class="vacancies-item__norm-bg"></div>
									<? endif; ?>
									<!--noindex-->
									<div class="vacancies-item__content">
										<h3 class="vacancies-item__content-title"><?echo join(', ', $vac['posts'])?></h3>
										<div class="vacancies-item__content-middle">
											<div>
												<div class="vacancies-item__content-gender">
                                                    <span class="icon22 icn-man-m-prommu <?=(!$vac['isman']?' color-l-grey':' color-green')?>"></span>
                                                    <span class="icon22 icn-woman-m-prommu <?=(!$vac['iswoman']?' color-l-grey':' color-green')?>"></span>
												</div>
												<div class="vacancies__item-payment"><?=$vac['payment']?></div>
												<div class="clearfix"></div>
											</div>
											
											<div class="vacancies__item-info">
												<b>Город: </b><?=join(', ', $vac['city'])?><br>
												<b>Вид работы:</b><?=$vac['work_type']?><br>
												<b>Период:</b><?=$vac['period']?>
											</div>											
										</div>
										<div class="vacancies__item-author">
											<div class="vacancies__item-author-img">
												<img src="<?=$vac['logo_src']?>" alt="<?=$vac['coname']?>">
											</div>
											<div>
												<b><?=$vac['coname']?></b>
												<span>от <?=$vac['crdate']?></span>
											</div>
										</div>
									</div>
									<!--/noindex-->
									<a href="<?=$vac['detail_url']?>" class="vacancies__item-link"></a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="btn-go-vacancy"><a href="<?= MainConfig::$PAGE_SEARCH_VAC ?>" rel="nofollow" class="btn-01">Смотреть все вакансии</a></div>
					<a href="<?=MainConfig::$PAGE_SEARCH_VAC?>" rel="nofollow" class="m-wrapper__btn m-wrapper__whiteo-btn m-detail-list-btn">Все вакансии подробнее</a>
				</div>
			<?php endif; ?>
			<?php if( count($content['applicants']) ):?>    
				<div class="applicants">             
					<div class="clearfix"></div>
					<div class="header h-indent applicants__title"><hr class="line">
                        <h2>
                            <i class="icon90 icn-lupa-humans-prommu color-orange"></i>
                            <span>Соискатели</span>
                        </h2>
                    </div>
					<div id="DiEmplSlider" class="applicant applicants__list"> 
						<?foreach($content['applicants'] as $key => $item):?>
						<div class="item applicants__item">
							<div class="m-applicants__item">
								<a href="<?=$item['datail-url']?>" class="applicants__item-ilink" >
									<img src="<?=$item['logo']?>" alt="" class="applicants__item-img js-g-hashint" title="<?= $item['firstname'].' '.$item['lastname'] ?>"/>
									<?php if($item['is_online']): ?>
										<span class="promo__item-onl"><span>В сети</span>
									<?php endif; ?>
								</a>
								<div class="applicants__item-info">
									<a class="applicants__item-name" href="<?=$item['datail-url']?>"><?=$item['firstname']?></a>
									<a class="applicants__item-name applicants__item-lastname" href="<?=$item['datail-url']?>"><?=$item['lastname']?></a>
                                    <a class="applicants__item-name bold" href="<?=$item['datail-url']?>"><?=$item['birthday']?></a>
									<div class="applicants__item-line"></div>
                                    <?php
                                    if (count(explode(", ", $item['positions'])) > 2) {
                                    ?>
									<div class="applicants__item-pos applicants__item-open" data-itempos="<?=$item['positions'];?>">
                                        <?=implode( array_slice((explode(", ", $item['positions'])), 0, 2), ', ') . '...';?>
                                    </div>
                                    <?php } else { ?>
                                        <div class="applicants__item-pos">
                                            <?=$item['positions'] . '.';?>
                                        </div>
                                    <?php } ?>
									<div class="companies__item-com-rate">
										<?if($item['rate_count']>0):?>
										<div class="companies__item-rating">
											<span class="rating-count js-g-hashint" title="Всего"><?=$item['rate_count']?></span>
											( <span class="rating-positive js-g-hashint" title="Положительный"><?=$item['rate']?></span>
											/ <span class="rating-negative js-g-hashint" title="Отрицательный"><?=$item['rate_neg']?></span> )
										</div>
										<?endif;?>
										<?if($item['comment_count']>0):?>
                                            <div class="companies__item-com">
                                                <a href="<?=$item['comment-url']?>" class="com-count js-g-hashint" title="Всего">
                                                    <?=$item['comment_count']?>
                                                </a>
                                                ( <span class="com-positive js-g-hashint" title="Положительный"><?php echo (integer)($item['comment_count'] - $item['comment_neg'])?></span>
                                                / <span class="com-negative js-g-hashint" title="Отрицательный"><?php  echo $item['comment_neg']; ?></span> )
                                            </div>
										<?endif;?>
									</div>
								</div>
							</div>
						</div>                      
						<?endforeach;?>
					</div>           
					<div class="btn-go-vacancy"><a href="<?= MainConfig::$PAGE_SEARCH_PROMO ?>" rel="nofollow" class="btn-01">Просмотреть всеx соискателей</a></div>
					<a href="<?=MainConfig::$PAGE_SEARCH_PROMO?>" rel="nofollow" class="m-wrapper__btn m-wrapper__white-btn m-detail-list-btn">все соискатели подробнее</a>
				</div>
			<?php endif; ?>

			<?if( count($content['companies']) ):?>
			<div class="companies">
				<div class="clearfix"></div>
				<div class="header h-indent companies__title"><hr class="line">
                    <h2>
                        <i class="icon90 icn-portfolio-prommu color-green"></i>
                        <span>Работодатели</span>
                    </h2>
                </div>
				<div id="DiEmpl1Slider" class="applicant companies__list">
					<?foreach($content['companies'] as $key => $item):?>
					<div class="item companies__item">
						<div class="m-companies__item">
							<a href="<?=$item['datail-url']?>" class="companies__item-ilink">
								<img src="<?=$item['logo']?>" alt="" class="js-g-hashint companies__item-img" title="<?=$item['name']?>"/>
								<?php if($item['is_online']): ?>
									<span class="promo__item-onl"><span>В сети</span>
								<?php endif; ?>
							</a>
							<div class="companies__item-info">
								<a class="companies__item-name" href="<?=$item['datail-url']?>"><?=$item['fullname'];?></a>

								<div class="companies__item-line"></div>
								<?if(!empty($item['cities'])):?>
								<div class="companies__item-cities">Город: <?=$item['cities']?></div>
								<?endif;?>
								<div class="companies__item-com-rate">
									<?if($item['rate_count']>0):?>
									<div class="companies__item-rating">
										<span class="rating-count js-g-hashint" title="Всего"><?=$item['rate_count']?></span>
										( <span class="rating-positive js-g-hashint" title="Положительный"><?=$item['rate']?></span>
										/ <span class="rating-negative js-g-hashint" title="Отрицательный"><?=$item['rate_neg']?></span> )
									</div>
									<?endif;?>
                                    <?if($item['comment_count']>0):?>
                                        <div class="companies__item-com">
                                            <a href="<?=$item['comment-url']?>" class="com-count js-g-hashint" title="Всего">
                                                <?=$item['comment_count']?>
                                            </a>
                                            ( <span class="com-positive js-g-hashint" title="Положительный"><?php echo (integer)($item['comment_count'] - $item['comment_neg'])?></span>
                                            / <span class="com-negative js-g-hashint" title="Отрицательный"><?php  echo $item['comment_neg']; ?></span> )
                                        </div>
                                    <?endif;?>
								</div>
							</div>
						</div>
					</div>                           
					<?endforeach;?>
				</div> 
				<div class="btn-go-vacancy"><a href="<?=MainConfig::$PAGE_SEARCH_EMPL?>" rel="nofollow" class="btn-01">Просмотреть всеx работодателей</a></div>
				<a href="<?=MainConfig::$PAGE_SEARCH_PROMO?>" rel="nofollow" class="m-wrapper__btn m-wrapper__whiteg-btn m-detail-list-btn">все работодатели подробнее</a>
			</div>
			<?endif;?>

			<?if(count($content['vacs'])):?>
			<div class="main-vacancies-list">
				<div class="clearfix"></div>
				<div class="header h-indent">
					<hr class="line">
					<h2><span><span class="header--break">Найти работу</span> по следующим <br/> специальностям</span></h2>
				</div>
				<div class="vacancy-sections vacancy">
					<div class="vacancy-sections--center">
						<?php foreach ($content['vacs'] as $val): ?>
							<div id="<?= $val['id'];?>">
								<a href="<?= MainConfig::$PAGE_SEARCH_VAC . "/{$val['comment']}" ?>"><ins><?= $val['name'] ?></ins></a><br/>
							</div>      
						<?php endforeach; ?>          
					</div>
				</div>
			</div>
			<?endif;?>

			<div class="advantages">
				<div class="clearfix"></div>
				<div class="header h-indent">
					<hr class="line">
					<h2><span>ПОЧЕМУ НА НАШЕМ САЙТЕ ПРОСТО И ЭФФЕКТИВНО<br/>ИСКАТЬ РАБОТУ И ПЕРСОНАЛ?</span></h2>
				</div>
				<div class="col-xs-12">
					<div class="benefits__grid">
						<div class="benefits__cell">
							<div class="benefits__icon icon80">
                                <span class="icn-tablet-phone-prommu color-green"></span>
                            </div>
						</div>
						<div class="benefits__cell">
							<h2 class="benefits__cell-title">Мобильное приложение PROMMU</h2>
							<p class="benefits__cell-text">Благодаря мобильному приложению Prommu вы можете без проблем найти работу или персонал с помощью вашего мобильного устройства.<br/>
								<a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" rel="nofollow" target="_blank"><ins>Скачать приложение</ins></a></p>
							</div>
						</div>
						<div class="benefits__grid">
							<div class="benefits__cell">
								<div class="benefits__icon icon80">
                                    <span class="icn-stars-ten-prommu color-green"></span>
                                </div>
							</div>
							<div class="benefits__cell">
								<h2 class="benefits__cell-title">Участие в рейтингах</h2>
								<p class="benefits__cell-text">Вы можете участвовать в системе рейтингов и получить статус лучший работодатель или лучший соискатель</p>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="benefits__grid">
							<div class="benefits__cell">
								<div class="benefits__icon icon80">
                                    <span class="icn-mail-phone-prommu color-green"></span>
                                </div>
							</div>
							<div class="benefits__cell">
								<h2 class="benefits__cell-title">Push - уведомления</h2>
								<p class="benefits__cell-text">Вы можете получать следующие уведомления о новой вакансии, отвечающей вашим требованиям, о приглашении от работодателя, об изменении рейтинга или добавлении отзыва и многое другое<br/>
                                <a href="https://prommu.com/services/push-notification"><ins>Подробнее</ins></a></p>
                            </div>
                        </div>
                        <div class="benefits__grid">
								<div class="benefits__cell">
									<div class="benefits__icon icon80">
                                        <span class="icn-net-prommu color-green"></span>
                                    </div>
								</div>
								<div class="benefits__cell">
									<h2 class="benefits__cell-title">Публикация вакансий в соц. сетях</h2>
									<p class="benefits__cell-text">Когда вы размещаете вакансии на нашем портале, мы автоматически дополнительно публикуем вакансии в нашей группе Вконтакте, тем самым увеличивая количество просмотров и обращений</p>
								</div>
                        </div>
							<div class="clearfix"></div>
							<div class="benefits__grid">
								<div class="benefits__cell">
									<div class="benefits__icon icon80">
                                        <span class="icn-globe-pointer-prommu color-green"></span>
                                    </div>
								</div>
								<div class="benefits__cell">
									<h2 class="benefits__cell-title">Геолокация</h2>
									<p class="benefits__cell-text">Вы не знаете где сейчас нанятый специалист? Воспользуйтесь геолокацией в мобильном приложении Prommu и вы всегда сможете отследить его местоположение</p>
								</div>
							</div>
							<div class="benefits__grid">
								<div class="benefits__cell">
									<div class="benefits__icon icon80">
                                        <span class="icn-wi-fi-prommu color-green"></span>
                                    </div>
								</div>
								<div class="benefits__cell">
									<h2 class="benefits__cell-title">Подписка на вакансии</h2>
									<p class="benefits__cell-text">Вы можете создать подписки на интересующие вакансии и регулярно получать на email уведомления о новых вакансиях.</p>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="benefits__grid">
								<div class="benefits__cell">
									<div class="benefits__icon icon80">
                                        <span class="icn-save-clouds-prommu color-green"></span>
                                    </div>
								</div>
								<div class="benefits__cell">
									<h2 class="benefits__cell-title">Отзывы и комментарии</h2>
									<p class="benefits__cell-text">Специалист некачественно выполнил свою работу? Или работодатель вам не доплатил? Оставьте отзыв и это поможет отслеживать недобросоветсных исполнителей и заказчиков.</p>
								</div>  
							</div>
							<div class="benefits__grid">
								<div class="benefits__cell">
									<div class="benefits__icon icon80">
                                        <span class="icn-file-plus-prommu color-green"></span>
                                    </div>
								</div>
								<div class="benefits__cell">
									<h2 class="benefits__cell-title">Комплекс дополнительных услуг</h2>
									<p class="benefits__cell-text">Дополнительно вы можете воспользоваться услугами: <br/>
										-персональный менеджер и аутсорсинг <br/>
										-премиум вакансии <br/>
										-корпоративная карта Prommu <br/>
										-аутстаффинг <br/>
										<a href="https://prommu.com/services"><ins>Подробнее</ins></a>
									</p>
								</div>
							</div>
						</div>    
						<div class="clearfix"></div>
					</div>

					<div class="services">
						<div class="clearfix"></div>
						<h2 class="services__title">
							<a href="<?=MainConfig::$PAGE_SERVICES?>" class="services__link">
                                <i class="icon90 icn-square-prommu color-orange"></i>
                                <span>услуги</span>
                            </a>
						</h2>
						<ul class="services__list">
							<li class="services__item">
                                <i class="icon30 icn-briliant-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_PREMIUM?>">Премиум вакансии</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-people-connect-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_SHARES?>">Приглашение персонала на акции</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-bell-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_PUSH?>">Пуш уведомления</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-net-smbl-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_SOCIAL?>">Дублирование вакансии в группах Промму Соц сетей</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-sms-sq-two-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_SMS?>">СМС информирование персонала</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-globe-cheked-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_GEO?>">Геолокация</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-man-busines-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>">Личный менеджер / Аутсорсинг</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-bell-globe-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_OUTSTAFFING?>">Аутстаффинг</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-cards-visa-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>">Получение корпоративной карты Промму</a>
                            </li>
							<li class="services__item">
                                <i class="icon30 icn-api-prommu color-white"></i>
                                <a href="<?=MainConfig::$PAGE_SERVICES_API?>">Получение API ключа</a>
                            </li>
						</ul>
					</div>

					<div class="articles-news">
						<div class="header h-indent"><hr class="line"><h2><span>Полезные статьи и новости</span></h2></div>
						<div class="col-xs-12 col-sm-8 col-md-8">
							<div class="vacancy posts" >
								<h2>СТАТЬИ О РАБОТЕ</h2>
								<?php for($i = 0; $i < 2; $i++): ?>
								  <?
								  $article = $content['articles'][$i];
									if(empty($article))
									{
										break;
									}
									else
                  { ?>
                    <div class="col-md-6 posts__item">
                      <a href="<?=MainConfig::$PAGE_ARTICLES . DS . $article['link']?>">
                        <img src="<?=Settings::getFilesUrl() . MainConfig::$PAGE_ARTICLES . DS . $article['img'] . Articles::$SMALL_IMG?>" alt="<?=$article['name']?>">
                      </a>
                      <a href="<?=MainConfig::$PAGE_ARTICLES . DS . $article['link']?>">
                        <p class="posts__item-title">
                          <b><?=$article['name']?></b>
                        </p>
                      </a>
                      <p><?=$article['pubdate']?></p>
                    </div>
									<? } ?>
								<? endfor; ?>
								<div style="padding-top: 5px;">
									<a href="/articles/"><p style="font-size: 15px;"><b><ins>Смотреть все статьи</ins></b></p></a>
								</div>
							</div>
						</div>

						<div style="  border-left: 1px solid #a9bc10; height: 440px;" class="col-xs-12 col-sm-4 col-md-4">
							<div class="vacancy">
								<h2>НОВОСТИ ПОРТАЛА PROMMU</h2>
								<?php 
								for($i = 0; $i < 4; $i++){
									if(empty($content['news'][$i])){
										break;
									}
									else {
										echo  '<div style="padding-top: 5px;">'; 
										$image = $content['news'][$i]['img'];
										$url = $content['news'][$i]['link'];
										$title = $content['news'][$i]['name'];
										$pubdate = $content['news'][$i]['pubdate'];
										echo "<a href='/about/news/$url'><p style='font-size: 16px; border-bottom: 1px solid;border-color: #c0cc4b;'><b>$title</b></p></a>";

										echo "<p>$pubdate</p>";

										echo '</div>';

									}
								}
								?>

								<div style="padding-top: 5px;">
									<a href="about/news/"><p style="font-size: 15px;"><b><ins>Смотреть все новости</ins></b></p></a>

								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<? $description = $this->ViewModel->getViewData()->pageMetaKeywords; ?>
					<? if(!empty($description)): ?>
						<div class="about-block">
							<div class="header h-indent about-block__title">
								<hr class="line">
								<h2>
                                    <i class="icon80 icn-table-prommu color-green"></i>
                                    <span>О нас</span>
                                </h2>
							</div>
							<div class="italic-non about-block__text" style="text-align: justify;"><?php echo $this->ViewModel->getViewData()->pageMetaKeywords; ?></div>
						</div>
					<? endif; ?>
					<div class="m-bottom-registration">
						<div class="m-bottom-reg__line"></div>
						<div class="m-wrapper__btn-block">
							<?php if(Share::$UserProfile->type==3): ?>
								<a href="<?=MainConfig::$PAGE_VACPUB?>" class="m-wrapper__btn m-wrapper__orange-btn">разместить вакансию</a>
							<?php endif; ?>
							<?php if(!in_array(Share::$UserProfile->type, [2,3])): ?>
								<a href="<?=MainConfig::$PAGE_REGISTER?>?p=1" class="m-wrapper__btn m-wrapper__orange-btn">разместить вакансию</a>
								<a href="<?=MainConfig::$PAGE_REGISTER?>?p=2" class="m-wrapper__btn m-wrapper__white-btn">разместить анкету</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(function($){
				setInterval(function (e){
					<?php 
					$vac = new Vacancy();
					$posts = $vac->getPost();  
					foreach ($posts as $key => $value):?>
						function ucFirst(str)
						{
							if(!str)
								return str;
							return str[0].toUpperCase() + str.slice(1);
						}
						if(ucFirst($("#EdSearch").val()) == "<?= $value['name']; ?>")
						{
							$("#EdSearch").attr("name","post[<?= $value['id']; ?>]");
							$("#EdSearchPromo").attr("value", "<?= $value['id']?>");
						}
					<?php endforeach;?>
				}, 500);

 	  /*
	  *
	  */ // ввод нового города
	  selectCities({
	  	'main' : '#multyselect-cities', 
	  	'arCity' : <?=json_encode($city)?>,
	  	'inputName' : 'cities[]'
	  });
	  //
	  function selectCities(obj) {
	  	var $main = $(obj.main).append('<ul class="cities-select">'+
	  		'<li data-id="0"><input type="text" name="c"></li></ul>'+
	      	'<ul class="cities-list"></ul><b></b>'), // родитель
	        $select = $main.find('ul').eq(0), // список ввода
	        $input = $select.find('input'), // ввод города
	        $list = $main.find('ul').eq(1), // список выбора
	        $load = $main.find('b'), // тег загрузки
	        bShowCityList = true, // флаг отображения списка городов
	        cityTimer = false; // таймер обращения к серверу для поиска городов

	    // добавляем уже выбранный город
	    if(obj.arCity != undefined) {
	    	content = '<li data-id="' + obj.arCity.id + '">' + 
	    	obj.arCity.name + '<i></i><input type="hidden" name="' +
	    	obj.inputName + '" value="' + obj.arCity.id + '">' + 
	    	'</li>';
	    	$select.prepend(content);
	    }
	    // при клике по блоку фокусируем на поле ввода 
	    $select.click(function(e){ if(!$(e.target).is('i')) $input.focus() });
	    $input.click(function(e){ if(!$(e.target).is('i')) $input.focus() })
	    // обработка событий поля ввода
	    $input.bind('input focus blur', function(e){
	    	setFirstUpper($input);

	    	var val = $input.val(),
	    	sec = e.type==='focus' ? 1 : 1000;

	      // делаем ширину поля по содержимому, чтобы не занимало много места
	      $input.val(val).css({width:(val.length * 10 + 5)+'px'});
	      bShowCityList = true;
	      clearTimeout(cityTimer);
	      cityTimer = setTimeout(function(){
	      	setFirstUpper($input);

	      	var arResult = [],
	      	content = '',
	      	val = $input.val(),
	      	piece = $input.val().toLowerCase();

	        arSelectId = getSelectedCities($select);// находим выбранные города

	        if(e.type!=='blur'){ // если мы не потеряли фокус
	          if(val===''){ // если ничего не введено
	            $load.show(); // показываем загрузку
	            $.ajax({
	            	url: MainConfig.AJAX_GET_VE_GET_CITIES,
	            	data: 'idco=' + obj.arCity.country + '&query=' + val,
	            	dataType: 'json',
	            	success: function(res){
	                $.each(res.suggestions, function(){ // список городов если ничего не введено
	                	if($.inArray(this.data, arSelectId)<0)
	                		content += '<li data-id="' + this.data + '">' + this.value + '</li>';
	                });
	                if(bShowCityList)
	                	$list.empty().append(content).fadeIn();
	                else{
	                	$list.empty().append(content).fadeOut();
	                	$input.val('');
	                }
	                $load.hide();
	              }
	            });
	          }
	          else{
	          	$load.show();
	          	$.ajax({
	          		url: MainConfig.AJAX_GET_VE_GET_CITIES,
	          		data: 'idco=' + obj.arCity.country + '&query=' + val,
	          		dataType: 'json',
	          		success: function(res){
	                $.each(res.suggestions, function(){ // список городов если что-то введено
	                	word = this.value.toLowerCase();
	                  // если введен именно город полностью
	                  if(
	                  	word===piece 
	                  	&& 
	                  	$.inArray(this.data, arSelectId)<0 
	                  	&&
	                  	this.data!=='man'
	                  	){
	                  	html =  '<li data-id="' + this.data + '">' + this.value + 
	                  '<i></i><input type="hidden" name="' + 
	                  obj.inputName + '" value="' + this.data + '"/>' +
	                  '</li>';
	                  $select.find('[data-id="0"]').before(html);
	                  bShowCityList = false;
	                }
	                else if(
	                	word.indexOf(piece)>=0 
	                	&& 
	                	$.inArray(this.data, arSelectId)<0 
	                	&& 
	                	this.data!=='man'
	                	)
	                	arResult.push( {'id':this.data, 'name':this.value} );
	              });
	                arResult.length>0
	                ? $.each(arResult, function(){ 
	                	content += '<li data-id="' + this.id + '">' + this.name + '</li>'
	                })
	                : content = '<li class="emp">Список пуст</li>';
	                if(bShowCityList)
	                	$list.empty().append(content).fadeIn();
	                else{
	                	$list.empty().append(content).fadeOut();
	                	$input.val('');
	                }
	                $load.hide();
	              }
	            });
	          }
	        }
	        else{ // если потерян фокус раньше времени
	        	$input.val('');
	        }
	      },sec);
	    });
	    // Закрываем список
	    $(document).on('click', function(e){
	    	if(
	    		$(e.target).is('li') 
	    		&& 
	    		$(e.target).closest($list).length 
	    		&& 
	    		!$(e.target).hasClass('emp')
	      ) { // если кликнули по списку && если это не "Список пуст" && 
	    		$(e.target).remove();
	    	html =  '<li data-id="' + $(e.target).data('id') + 
	    	'">' + $(e.target).text() + 
	    	'<i></i><input type="hidden" name="' + obj.inputName + 
	    	'" value="' + $(e.target).data('id') + '"/>' + '</li>';
	    	$select.find('[data-id="0"]').before(html);
	    	$list.fadeOut();
	    }
	      // удаление выбраного города из списка
	      if($(e.target).is('i') && $(e.target).closest($select).length){ 
	      	$(e.target).closest('li').remove();
	      	l = getSelectedCities($select).length;
	      }
	      // закрытие списка
	      if(!$(e.target).is($select) && !$(e.target).closest($select).length){
	      	bShowCityList = false;
	      	$list.fadeOut();
	      }
	    });
	  }
	  function getSelectedCities(ul) {
	  	var arId = [],
	  	arSelected = $(ul).find('li');

	  	$.each(arSelected, function(){
	  		if($(this).data('id')!=0)
	  			arId.push(String($(this).data('id')));
	  	});
	  	return arId; 
	  }
	  // делаем каждое слово в городе с большой
	  function setFirstUpper(e) {
	  	var split = $(e).val().split(' '),
	  	len=split.length;

	  	for(var i=0; i<len; i++)
	  		split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1).toLowerCase();
	  	$(e).val(split.join(' '));

	  	split = $(e).val().split('-');
	  	len=split.length;

	  	for(var i=0; i<len; i++)
	  		split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1).toLowerCase();
	  	$(e).val(split.join('-'));
	  }
	});
</script>