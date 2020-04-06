<? $this->renderPartial('../site/page-index.tpl_css'); ?>
<div class="m-wrapper">
	<?if(Share::isGuest()):?>
		<div class="m-wrapper__btn-block">
			<a href="<?=MainConfig::$PAGE_REGISTER?>" class="m-wrapper__btn m-wrapper__green-btn">Регистрация</a>
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
							<?php if(Share::isEmployer()): ?>
                <?=VacancyView::createVacancyLink('Опубликовать вакансию','left')?>
							<?php endif; ?>
							<?php if(Share::isGuest()): ?>
								<a class="left" href="<?=MainConfig::$PAGE_REGISTER?>">Разместить анкету</a>
								<a class="left" href="<?=MainConfig::$PAGE_LOGIN?>">Опубликовать вакансию</a>
							<?php endif; ?>
						</div>
						<div class="search-container">
							<div class="search">
								<div class="row">
									<div class="col-xs-12">
										<div class="search__search-line clearfix" ></div>
										<form action="<?=MainConfig::$PAGE_VACANCY?>" method="GET" id="FmSearch">
											<div class="search__search-line clearfix">
												<div class="text"><input type="text" id="EdSearch" name='srch_post' placeholder="Введите название вакансии..." data-ph1="Введите название вакансии..." data-ph2="Введите название резюме..." data-ph3="Введите название компании..." style='background: rgb(245, 245, 245); width: 97%; border: 0; font-size: 16px; line-height: 17px;' /></div>
												<div id="multyselect-cities">
                          <span class="search__search-city">Введите город</span>
                        </div>
												<button type="submit">Найти</button>
											</div>
										</form>
									</div>
								</div>
								<a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" rel="nofollow" class="app-top-link" target="_blank"></a>
							</div>
						</div>
						<?php if(!Share::isEmployer()): ?>
							<div class="search__right">
								<p class="search__wrd-module">Например:  
									<a  class="selectInd search__small" data-id="111" href="javascript:void(0)"><ins class='search-word'>промоутер</ins></a>
									<a  class="selectInd search__small" data-id="114" href="javascript:void(0)"><ins class='search-word'> мерчендайзер</ins></a>
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
<?/*
            <div class="index__message-block">
                Уважаемые пользователи! Доводим до вашего сведения, что <?=date('d.m.Y')?> и <?=date('d.m.Y',strtotime("+1 day"))?> в период с 03:00 до 22:00 по московскому времени на некоторых из серверов «PROMMU» будут проводиться плановые технические работы на оборудовании канального оператора с целью улучшения качества предоставляемых услуг. В связи с этим возможны кратковременные перерывы в работе сервисов. Приносим извинения за доставленные неудобства.
            </div>
*/?>
			<div class="how-block">
				<a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" rel="nofollow" class="app-top-mob-link" target="_blank"></a>
				<div class="header h-indent first"><hr class="line"><h2><span>Как найти работу или персонал<br/> на нашем портале</span></h2></div>
        <div class="how-block__list">
          <div class="col-xs-12 col-sm-4 col-md-3 how-block__item">
            <div class="how-block__icon icon100" title="Зарегистрируйтесь">
              <span class="icn-note-page-prommu color-green "></span>
            </div>
            <h2 class="how-block__title">Зарегистрируйтесь на портале</h2>
            <div class="how-block__info">
              <p class="how-block__text">Чтобы начать искать работу или размещать вакансии своей компании, вам необходимо зарегистрироваться на портале и заполнить свой профиль.</p>
              <br/>
              <a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER)?>" class="reg btn__orange">
                <ins>Регистрация</ins>
              </a>
            </div>
          </div>
          <div class="hidden-xs hidden-sm indent-block"></div>
          <div class="col-xs-12 col-sm-4 col-md-3 how-block__item locate">
            <div class="how-block__icon icon100" title="Разместите анкету">
              <span class="icn-beidg-prommu color-green "></span>
            </div>
            <h2 class="how-block__title">Разместите анкету или опубликуйте вакансию</h2>
            <div class="how-block__info">
              <p class="how-block__text">Если вы соискатель - разместите анкету по специальности, которая вам интересна. Если вы работодатель - опубликуйте одну или несколько вакансий.</p>
            </div>
          </div>
          <div class="hidden-xs hidden-sm indent-block"></div>
          <div class="col-xs-12 col-sm-4 col-md-3 how-block__item">
            <div class="how-block__icon icon100" title="Найдите интересную работу">
              <span class="icn-heads-circle-prommu color-green "></span>
            </div>
            <h2 class="how-block__title">Найдите интересную работу или квалифицированный персонал</h2>
            <div class="how-block__info">
              <p class="how-block__text">Откликайтесь на вакансии, которые вас заинтересовали, получайте push-уведомления о новых вакансиях.</p>
              <p class="how-block__text">Оставляйте отзывы и участвуйте в рейтинге.</p>
              <br/>
              <p class="how-block__text">Также рекламодатели при необходимости могут воспользоваться гибким фильтром для поиска персонала.</p>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
            <?php if( count($content['vacancies']) ): ?>
                <div class="vacancies">
                    <div class="header h-indent vacancies__title">
                        <hr class="line">
                        <h2>
                            <i class="icon90 icn-iii-prommu color-green"></i>
                            <span>Вакансии</span>
                        </h2>
                    </div>
                    <div class="container">
                        <div class="vacancies__list">
                            <?php foreach ($content['vacancies'] as $v):?>
                                <div class="vacancies__item <?=($v['ispremium']?'premium':'')?>">
                                    <? if($v['ispremium']): ?>
                                        <div class="vacancies-item__prem-icon js-g-hashint" title="Премиум вакансия"></div>
                                        <div class="vacancies-item__prem-border"></div>
                                    <? else: ?>
                                        <div class="vacancies-item__norm-border"></div>
                                        <div class="vacancies-item__norm-bg"></div>
                                    <? endif; ?>
                                    <!--noindex-->
																		<div class="vacancies-item__content">
																			<h3 class="vacancies-item__content-title"><?=$v['str_posts']?></h3>
																			<div class="vacancies-item__content-middle">
																				<div>
                                          <div class="vacancies-item__content-gender">
                                              <span class="icon22 icn-man-m-prommu <?=(!$v['isman']?' color-l-grey':' color-green')?>"></span>
                                              <span class="icon22 icn-woman-m-prommu <?=(!$v['iswoman']?' color-l-grey':' color-green')?>"></span>
                                          </div>
																					<div class="vacancies__item-payment"><?=$v['payment']?></div>
																					<div class="clearfix"></div>
																				</div>

																				<div class="vacancies__item-info">
                                           <div class="vacancies__item-info-city"><b>Город: </b><?
                                            echo $v['str_cities'];
                                            if(!empty($v['str_cities_all']))
                                            {
                                              echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path transform="rotate(90 256 256)" d="M90,256c0,91.755,74.258,166,166,166c91.755,0,166-74.259,166-166c0-91.755-74.258-166-166-166   C164.245,90,90,164.259,90,256z M462,256c0,113.771-92.229,206-206,206S50,369.771,50,256S142.229,50,256,50S462,142.229,462,256z    M199.955,168.598l32.263-32.107L352.154,257L232.218,377.51l-32.263-32.107L287.937,257L199.955,168.598z"/></svg>';
                                              echo '<div class="over__hint">' . $v['str_cities_all'] . '</div>';
                                            }
                                             ?></div>
                                          <b>Вид работы: </b><?=$v['work_type']?><br>
                                          <b>Период: </b><?=$v['period']?>
																				</div>
																			</div>
																			<div class="vacancies__item-author">
																				<div class="vacancies__item-author-img">
																					<img src="<?=$v['user']['small_src']?>" alt="<?=$v['user']['name']?>">
																				</div>
																				<div>
																					<b><?=$v['user']['name']?></b>
																					<span>от <?=$v['crdate']?></span>
																				</div>
																			</div>
																		</div>
                                    <!--/noindex-->
                                    <a href="<?=$v['detail_url']?>" class="vacancies__item-link"></a>
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
                    <div class="header h-indent applicants__title">
                        <hr class="line">
                        <h2>
                            <i class="icon90 icn-lupa-humans-prommu color-orange"></i>
                            <span>Соискатели</span>
                        </h2>
                    </div>
                    <div id="DiEmplSlider" class="applicant applicants__list">
                        <?foreach($content['applicants'] as $key => $item):?>
                            <div class="item applicants__item">
                                <div class="m-applicants__item">
                                    <a href="<?=$item['detail_url']?>" class="applicants__item-ilink" >
                                        <img src="<?=$item['logo']?>" alt="" class="applicants__item-img js-g-hashint" title="<?=$item['fullname']?>"/>
                                        <?php if($item['is_online']): ?>
                                        <span class="promo__item-onl"><span>В сети</span>
                                            <?php endif; ?>
                                    </a>
                                    <div class="applicants__item-info">
                                        <a class="applicants__item-name" href="<?=$item['detail_url']?>"><?=$item['firstname']?></a>
                                        <a class="applicants__item-name applicants__item-lastname" href="<?=$item['detail_url']?>"><?=$item['lastname']?></a>
                                        <a class="applicants__item-name bold" href="<?=$item['detail_url']?>"><?=$item['birthday']?></a>
                                        <div class="applicants__item-line"></div>
                                        <?php if (!empty($item['str_posts_all'])): ?>
                                            <div class="applicants__item-pos applicants__item-open" data-itempos="<?=$item['str_posts_all'];?>">
                                                <?= $item['str_posts'] . '...';?>
                                            </div>
                                        <?php else: ?>
                                            <div class="applicants__item-pos"><?=$item['str_posts']?></div>
                                        <?php endif; ?>
                                        <div class="companies__item-com-rate">
                                            <?if($item['rate_count']):?>
                                                <div class="companies__item-rating">
                                                    <span class="rating-count js-g-hashint" title="Всего"><?=$item['rate_count']?></span>
                                                    ( <span class="rating-positive js-g-hashint" title="Положительный"><?=$item['rate']?></span>
                                                    / <span class="rating-negative js-g-hashint" title="Отрицательный"><?=$item['rate_neg']?></span> )
                                                </div>
                                            <?endif;?>
                                            <?if($item['comments_count']):?>
                                                <div class="companies__item-com">
                                                  <?=$item['comment_count']?>
                                                  ( <span class="com-positive js-g-hashint" title="Положительный"><?php echo (integer)($item['comments_count'] - $item['comments_negative'])?></span>
                                                  / <span class="com-negative js-g-hashint" title="Отрицательный"><?php  echo (integer) $item['comments_negative']; ?></span> )
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
                    <div class="header h-indent companies__title">
                        <hr class="line">
                        <h2>
                            <i class="icon90 icn-portfolio-prommu color-green"></i>
                            <span>Работодатели</span>
                        </h2>
                    </div>
                    <div id="DiEmpl1Slider" class="applicant companies__list">
                        <?foreach($content['companies'] as $key => $item):?>
                            <div class="item companies__item">
                                <div class="m-companies__item">
                                    <a href="<?=$item['detail_url']?>" class="companies__item-ilink">
                                        <img src="<?=$item['logo']?>" alt="" class="js-g-hashint companies__item-img" title="<?=$item['name']?>"/>
                                        <?php if($item['is_online']): ?>
                                          <span class="promo__item-onl"><span>В сети</span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="companies__item-info">
                                        <a class="companies__item-name" href="<?=$item['detail_url']?>"><?=$item['fullname'];?></a>

                                        <div class="companies__item-line"></div>
                                        <?if(!empty($item['str_cities'])):?>
                                            <div class="companies__item-cities">Город: <?=$item['str_cities']?></div>
                                        <?endif;?>
                                        <div class="companies__item-com-rate">
                                            <?if($item['rate_count']>0):?>
                                                <div class="companies__item-rating">
                                                    <span class="rating-count js-g-hashint" title="Всего"><?=$item['rate_count']?></span>
                                                    ( <span class="rating-positive js-g-hashint" title="Положительный"><?=$item['rate']?></span>
                                                    / <span class="rating-negative js-g-hashint" title="Отрицательный"><?=$item['rate_neg']?></span> )
                                                </div>
                                            <?endif;?>
                                            <?if($item['comments_count']>0):?>
                                                <div class="companies__item-com">
                                                  <?=$item['comments_count']?>
                                                  ( <span class="com-positive js-g-hashint" title="Положительный"><?php echo (integer)($item['comments_count'] - $item['comments_negative'])?></span>
                                                  / <span class="com-negative js-g-hashint" title="Отрицательный"><?php  echo $item['comments_negative']; ?></span> )
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
                <div class="main-vacancies-list" id="specialties">
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
                            <p class="benefits__cell-text">Вы можете участвовать в системе рейтингов и получить статус лучший работодатель или лучший соискатель.</p>
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
                            <h2 class="benefits__cell-title">Удобные уведомления</h2>
                            <p class="benefits__cell-text">Вы можете получать следующие уведомления: о новой вакансии, отвечающей вашим требованиям, о приглашении от работодателя, об изменении рейтинга или добавлении отзыва и многое другое.<br/>
                                <a href="https://prommu.com/services/push-notification"><ins>Подробнее</ins></a></p>
                        </div>
                    </div>
                    <div class="benefits__grid">
                        <div class="benefits__cell">
                            <div class="benefits__icon icon80">
                                <span class="icn-globe-pointer-prommu color-green"></span>
                            </div>
                        </div>
                        <div class="benefits__cell">
                            <h2 class="benefits__cell-title">Геолокация</h2>
                            <p class="benefits__cell-text">Вы не знаете где сейчас нанятый специалист? Воспользуйтесь геолокацией в мобильном приложении Prommu и вы всегда сможете отследить его местоположение.</p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
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
                    <div class="clearfix"></div>
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
							<?php if(Share::isEmployer()): ?>
								<a href="<?=MainConfig::$PAGE_VACPUB?>" class="m-wrapper__btn m-wrapper__orange-btn">разместить вакансию</a>
							<?php endif; ?>
							<?php if(Share::isGuest()): ?>
								<a href="<?=MainConfig::$PAGE_REGISTER?>" class="m-wrapper__btn m-wrapper__orange-btn">разместить вакансию</a>
								<a href="<?=MainConfig::$PAGE_REGISTER?>" class="m-wrapper__btn m-wrapper__white-btn">разместить анкету</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
