<?
Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-empl-assessment.js', CClientScript::POS_END);
?>

<div class="row">
	<div class="col-xs-12">
		<?
		// Applicant 
		?>
		<? if(Share::isApplicant()): ?>
			<? if(count($viData['items'])): ?>
				<div class="responses">
					<div class="responses__header">
						<h1 class="responses__header-title">Выставить оценку / Оставить отзыв работодателю, у которого работали на вакансиях</h1>
					</div>
					<div class="responses__list">
						<? foreach($viData['items'] as $idus => $arEmp): ?>
							<? $arUser = $viData['users'][$idus]; ?>
							<div class="responses__item">
								<a class="app-responses__item-title" href="<?=$arUser['profile']?>" target="_blank">
									<span class="app-responses__logo">
										<img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
									</span>
									<span><?=$arUser['name']?></span>
								</a>
								<? foreach ($arEmp as $v):?>
									<div class="app-responses__item-resps<?=$v['status']=='4' ? ' active' : ''?>">
										<div class="app-responses__content">
											<span class="app-responses__cid js-g-hashint" title="номер заявки">(#<?=$v['id']?>) </span>
											<? if(in_array($v['status'], [Responses::$STATUS_BEFORE_RATING,Responses::$STATUS_EMPLOYER_RATED])): ?>
												<a class='black-orange js-g-hashint' href="<?=MainConfig::$PAGE_SETRATE . DS . $v['id_vacancy']?>" title="Оставить отзыв"><?= $v['title'] ?></a>
											<? else: ?>
												<span class='black-orange'><?= $v['title'] ?></span>
											<? endif; ?>
											<div class="app-responses__rdate js-g-hashint" title="Дата заявки"><?=$v['rdate']?></div>
											<div class="app-responses__bdate js-g-hashint" title='дата размещения вакансии'><?=$v['bdate']?></div>
										</div>
										<div class="controls" data-sid="<?= $v['id'] ?>">
											<? if( $v['status'] == 4 ): ?>
												<div class="btn-green-02-wr"><a href="#" class="apply" data-status="Подтверждена обеими сторонами">Согласен работать</a></div>
												<div class="btn-red-02-wr"><a href="#" class="js-cancel">Отклонить</a></div>
											<? endif; ?>
											<? if( $v['status']==4 && (int)$activeFilterLink!=1 ): ?>
												<span class="status hint js-g-hashint" title="Ваша заявка на вакансию подтверждена работодателем, нажмите согласен, если хотите работать на этой вакансии">Подтверждена</span>
											<? else: ?>
												<span class="status hint"></span>
											<? endif; ?>
											<? if(in_array($v['status'], [Responses::$STATUS_BEFORE_RATING,Responses::$STATUS_EMPLOYER_RATED])): ?>
												<a href="<?= MainConfig::$PAGE_SETRATE . DS . $v['id_vacancy'] ?>" class="responses__btn btn__orange">Оставить отзыв</a>
											<? endif;?>
											<? if( $v['status'] < 4 ): ?>
												<span> Заявка на вакансию подана </span>
											<? endif; ?>
											<? if( in_array($v['status'], [5]) ): ?>
											<span>Подтверждена обеими сторонами</span>
											<? endif; ?>
											<? if($v['status']==Responses::$STATUS_APPLICANT_RATED): ?>
												<span>Вы выставили рейтинг по этой вакансии</span>
											<? endif; ?>
											<div class="clearfix"></div>
										</div>
									</div>
								<? endforeach; ?>
							</div>
						<? endforeach; ?>
					</div>
					<?php
						$this->widget('CLinkPager', array(
								'pages' => $pages,
								'htmlOptions' => array('class' => 'paging-wrapp'),
								'firstPageLabel' => '1',
								'prevPageLabel' => 'Назад',
								'nextPageLabel' => 'Вперед',
								'header' => '',
							))
					?>
				</div>
			<? else: ?>
				<div class="reviews-lock">
					<h2 class="rev-lock__title">Уважаемый Соискатель,</h2>
					<p class="rev-lock__text">К сожалению Вы еще не были утверждены, ни одним Работодателем ни на одной вакансии.<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вас должен утвердить Работодатель на опубликованную вакансию в Личном кабинете.<br>
					<a href="<?=MainConfig::$PAGE_VACANCY?>" class="rev__btn btn__orange">Найти вакансию</a>
					<br>После завершения работы по выбранной вакансии Вы сможете оставить отзыв и оценить работодателя по вопросам которые больше всего интересуют соискателей временной работы - что в дальнейшем поможет другим Вашим коллегам и нашему сервису выявлять лучших либо блокировать недобросовестных Работодателей.</p>
					<br>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<p class="rev-lock__text">Оцениваем Работодателя по таким вопросам:</p>
							<ul class="rev-lock__list">
								<li class="rev-lock__list-item"><span>Соблюдение сроков оплаты</span></li>
								<li class="rev-lock__list-item"><span>Размер оплаты</span></li>
								<li class="rev-lock__list-item"><span>Четкость постановки задач</span></li>
								<li class="rev-lock__list-item"><span>Четкость требований</span></li>
								<li class="rev-lock__list-item"><span>Контактность</span></li>
							</ul>
						</div>
						<div class="col-xs-12 col-sm-6"><div class="rev-lock__planet"></div></div>
					</div>
					<div class="rev-lock__logo"></div>
					<span class="rev-lock__signature">С наилучшими пожеланиями, команда Промму</span>		
				</div>
			<?php endif; ?>
		<?
		// Employer
		?>
		<? elseif(Share::isEmployer()): ?>
			<? if(count($viData['items'])): ?>
				<div class="responses">
					<div class="responses__header">
						<h1 class="responses__header-title">
                            Выставить оценку / Оставить отзыв персоналу, который работал на моих вакансиях
                            <a class="download__btn download__btn-flt-right download__btn-inst" href='/theme/pdf/Instruction-PROMMU-com-rating.pdf' target="_blank" title="Скачать иструкцию пользования сервисом PROMMU.com">
                                <span class="btn-inst__txt">
                                    Инструкция <br> по выставлению рейтинга
                                </span>
                            </a>
                        </h1>
					</div>
					<div class="responses__list">
						<? foreach ($viData['items'] as $id_vacancy => $arItems): ?>
							<? $arVac = reset($arItems); ?>
							<div class="responses__item">
								<a 
									class='responses__item-title' 
									href='<?=MainConfig::$PAGE_VACANCY . DS . $id_vacancy?>'
									target="_blank"><?=$arVac['title']?>
									<span class="js-g-hashint responses__item-bdate" title="Дата публикации"><?=$arVac['bdate']?></span>
									<span class="responses__item-status js-g-hashint" title="Статус вакансии"><?=($arVac['vstatus'] ? 'Открытая вакансия' : 'Закрытая вакансия')?></span>
								</a>
								<? foreach ($arItems as $v):?>
									<? 
										$arUser = $viData['users'][$v['id_user']];
										$linkSetrate = MainConfig::$PAGE_SETRATE . DS . $id_vacancy . DS . $arUser['id'];
									?>
									<div class="responses__item-resps<?=(($v['status']==0 && $v['isresponse']==1) ? ' active' : '')?>">
										<div class="responses__resps-content">
											<div class="responses__resps-logo">
												<? if($v['status']==Responses::$STATUS_EMPLOYER_RATED): ?>
													<img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
													<span class="responses__cmplt-rate js-g-hashint" title="Рейтинг уже выставлен"></span>
												<? else: ?>
													<a href="<?=$linkSetrate?>" class="js-g-hashint" title="Оставить отзыв соискателю">
														<img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
													</a>
												<? endif; ?>
											</div>
											<div class="responses__resps-data">
												<span class="js-g-hashint" title="номер заявки">(#<?=$v['id']?>) </span>
												<? if($v['status']==Responses::$STATUS_EMPLOYER_RATED): ?>
													<span class='black-orange'><?=$arUser['name']?></span>  
												<? else: ?>
													<a class='black-orange js-g-hashint' href="<?=$linkSetrate?>" title="Оставить отзыв соискателю"><?=$arUser['name']?></a>  
												<? endif; ?>
											</div>
										</div>
										<span class='responses__resps-date js-g-hashint' title="Дата заявки"> <?=$v['rdate']?> </span>
										<div class="controls" data-sid="<?=$v['id']?>">
											<? if( $v['status'] == '0'  ): ?>
												<div class="btn-black-02-wr"><a href="#" class="view js-g-hashint" title="Отметить заявку как просмотренная">Просмотреть</a></div>
											<? endif; ?>
											<? if($v['status']>=Responses::$STATUS_BEFORE_RATING): ?>
												<? if($v['status']==Responses::$STATUS_EMPLOYER_RATED): ?>
													<span>Вы выставили рейтинг данному соискателю</span>
												<? else: ?>
													<a href="<?=$linkSetrate?>" class="responses__btn js-g-hashint btn__orange" title="Оставить отзыв соискателю">Оставить отзыв</a>
												<? endif; ?>
											<? endif; ?>
											<? if( $v['status'] == '1' || $v['status'] == '0' ): ?>
												<div class="btn-green-02-wr">
													<a href="#" class="apply js-g-hashint" title="Подтвердить заявку на вакансию">Утвердить</a>
												</div>
												<div class="btn-red-02-wr">
													<a href="#" class="cancel js-g-hashint" title="Отклонить заявку на вакансию">Отклонить</a>
												</div>
											<? endif; ?>
											<? if( $v['isresponse'] == 1 && $v['status'] == 4 ): ?>
												<div class="hint js-g-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</div>
											<? elseif( $v['isresponse'] == 2 && in_array($v['status'], [2,4]) ): ?>
												<div class="hint js-g-hashint" title="Вы отправили приглашение соискателю на вакансию, ожидайте его решения">Приглашение на вакансию отправлено</div>
											<? endif; ?>
											<? if($v['isresponse']==1 && $v['status']==Responses::$STATUS_APPLICANT_ACCEPT): ?>
												<span>Заявка на вакансию подтверждена обеими сторонами</span>
											<? elseif($v['isresponse']==2 && $v['status']==Responses::$STATUS_APPLICANT_ACCEPT): ?>
												<span>Приглашение на вакансию принято соискателем</span>
											<? endif; ?>
											<div class="clearfix"></div>
										</div>
									</div>
								<? endforeach ?>
							</div>
						<? endforeach; ?>
					</div>
				</div>
				<br />
				<br />
				<? $this->widget('CLinkPager', array(
					'pages' => $pages,
					'htmlOptions' => array('class' => 'paging-wrapp'),
					'firstPageLabel' => '1',
					'prevPageLabel' => 'Назад',
					'nextPageLabel' => 'Вперед',
					'header' => ''
				)) ?> 
			<?php else: ?>
				<div class="reviews-lock">
					<h2 class="rev-lock__title">Уважаемый работодатель,</h2>
					<p class="rev-lock__text">К сожалению Вы еще не опубликовали ни одной вакансии. (если вакансии есть опубликованные которые по времени еще актуальны - Вы еще не утвердили на свою вакансию ни одного Соискателя).<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вам необходимо разместить вакансию в Личном кабинете и утвердить Соискателей, которые отозвались на нее.<br>
					<a href="<?=MainConfig::$PAGE_VACPUB?>" class="rev__btn btn__orange">Добавить вакансию</a>
					<br>После завершения работы по выбранной вакансии Вы сможете оставить отзыв и оценить всех работников по вопросам которые больше всего интересуют Работодателей - что в дальнейшем поможет другим Вашим коллегам и нашему сервису выявлять лучших или недобросовестных Соискателей.</p>
					<br>
					<div class="row rev-lock__emp">
						<div class="col-xs-12 col-sm-6">
							<p class="rev-lock__text">Оцениваем Соискателя по таким вопросам:</p>
							<ul class="rev-lock__list">
								<li class="rev-lock__list-item"><span>Качество выполненной работы</span></li>
								<li class="rev-lock__list-item"><span>Контактность</span></li>
								<li class="rev-lock__list-item"><span>Пунктуальность</span></li>
							</ul>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="rev-lock__social"></div>
							<div class="rev-lock__planet"></div>
						</div>
					</div>
					<div class="rev-lock__logo"></div>
					<span class="rev-lock__signature">С наилучшими пожеланиями, команда Промму</span>		
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>