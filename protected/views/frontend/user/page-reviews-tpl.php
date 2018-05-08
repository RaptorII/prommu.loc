<div class="row">
	<div class="col-xs-12">
		<?php // Applicant ?>
		<?php if(Share::$UserProfile->type==2): ?>
			<?php if(sizeof($viData)): ?>
				<div class="responses">
					<div class="responses__header">
						<h1 class="responses__header-title">Выставить оценку / Оставить отзыв работодателю, у которого работали на вакансиях</h1>
					</div>
					<div class="responses__list">
						<?php foreach($viData as $idus => $val): ?>
				            <div class="responses__item">
				              <a class="app-responses__item-title" href='<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $idus?>' target="_blank">
				                <span class="app-responses__logo">
				                    <img src="<?= Yii::app()->Controller->ViewModel->getHtmlLogo($val['logo'], ViewModel::$LOGO_TYPE_EMPL) ?>" alt="">
				                </span>
				                <span><?=$val['name']?></span>
				              </a>
				              <?php foreach ($val['resps'] as $id => $vac):?>
				                <div class="app-responses__item-resps<?=$vac['status']=='4' ? ' active' : ''?>">
				                  <div class="app-responses__content">
				                    <span class="app-responses__cid js-g-hashint" title="номер заявки">(#<?=$vac['sid']?>) </span>
				                    <?php if($vac['status'] == 6): ?>
				                      <a class='black-orange js-g-hashint' href='<?=MainConfig::$PAGE_SETRATE . DS . $id?>' title="Оставить отзыв"><?= $vac['title'] ?></a>
				                    <?php else: ?>
				                      <span class='black-orange'><?= $vac['title'] ?></span>
				                    <?php endif; ?>
				                    <div class="app-responses__rdate js-g-hashint" title="Дата заявки"><?=$vac['rdate']?></div>
				                    <div class="app-responses__bdate js-g-hashint" title='дата размещения вакансии'><?=$vac['bdate']?></div>
				                  </div>
				                  <div class="controls" data-sid="<?= $vac['sid'] ?>">
				                    <?php if( $vac['status'] == 4 ): ?>
				                      <div class="btn-green-02-wr"><a href="#" class="apply" data-status="Подтверждена обеими сторонами">Согласен работать</a></div>
				                      <div class="btn-red-02-wr"><a href="#" class="js-cancel">Отклонить</a></div>
				                    <?php endif; ?>
				                    <?php if( $vac['status']==4 && (int)$activeFilterLink!=1 ): ?>
				                      <span class="status hint js-g-hashint" title="Ваша заявка на вакансию подтверждена работодателем, нажмите согласен, если хотите работать на этой вакансии">Подтверждена</span>
				                    <?php else: ?>
				                      <span class="status hint"></span>
				                    <?php endif; ?>
				                    <?php if( $vac['status'] == 6 ): ?>
				                      <a href="<?= MainConfig::$PAGE_SETRATE . DS . $id ?>" class="responses__btn">Оставить отзыв</a>
				                    <?php endif;?>
				                    <?php if( $vac['status'] < 4 ): ?>
				                      <span> Заявка на вакансию подана </span>
				                    <?php endif; ?>
				                    <?php if( in_array($vac['status'], [5]) ): ?>
				                      <span>Подтверждена обеими сторонами</span>
				                    <?php endif; ?>
				                    <?php if( in_array($vac['status'], [7]) ): ?>
				                      <span>Вы выставили рейтинг по этой вакансии</span>
				                    <?php endif; ?>
				                    <div class="clearfix"></div>
				                  </div>
				                </div>      
				              <?php endforeach; ?>
				          		
							</div>
						<?php endforeach; ?>
				    </div>
					<br />
					<br />
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
			<?php else: ?>
				<div class="reviews-lock">
					<h2 class="rev-lock__title">Уважаемый Соискатель,</h2>
					<p class="rev-lock__text">К сожалению Вы еще не были утверждены, ни одним Работодателем ни на одной вакансии.<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вас должен утвердить Работодатель на опубликованную вакансию в Личном кабинете.<br>
					<a href="<?=MainConfig::$PAGE_VACANCY?>" class="rev__btn">Найти вакансию</a>
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
		<?php // Employer ?>
		<?php elseif(Share::$UserProfile->type==3): ?>
			<?php if(sizeof($viData)): ?>
		      <div class="responses">
		        <div class="responses__header">
		          <h1 class="responses__header-title">Выставить оценку / Оставить отзыв персоналу, который работал на моих вакансиях</h1>
		        </div>    
		        <div class="responses__list">
		        
		          <?php foreach ($viData as $id => $vac): ?>
		            <div class="responses__item">
		              <a class='responses__item-title' href='<?=MainConfig::$PAGE_VACANCY . DS . $id?>' target="_blank"><?=$vac['title']?><span class="js-g-hashint responses__item-bdate" title="Дата публикации"><?=$vac['bdate']?></span><span class="responses__item-status js-g-hashint" title="Статус вакансии"><?=($vac['status'] ? 'Открытая вакансия' : 'Закрытая вакансия')?></span></a>
		              <?php foreach ($vac['resps'] as $idus => $user):?>
		                <div class="responses__item-resps<?=(($user['status']==0 && $user['isresponse']==1) ? ' active' : '')?>">
		                  <div class="responses__resps-content">
		                    <div class="responses__resps-logo">
		                      <?php if( $user['id_vac'] ): ?>
		                        <img src=<?echo DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$user['photo'] ? ($user['sex'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F) : $user['photo'].'100.jpg')?> alt="">
		                        <span class="responses__cmplt-rate js-g-hashint" title="Рейтинг уже выставлен"></span>
		                      <?php else: ?>
		                        <a href="<?= MainConfig::$PAGE_SETRATE . DS . $id . DS . $idus ?>" class="js-g-hashint" title="Оставить отзыв соискателю">
		                          <img src=<?echo DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$user['photo'] ? ($user['sex'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F) : $user['photo'].'100.jpg')?> alt="">
		                        </a>
		                      <?php endif; ?>
		                    </div>
		                    <div class="responses__resps-data">
		                      <span class="js-g-hashint" title="номер заявки">(#<?=$user['sid']?>) </span>
		                      <?php if( $user['id_vac'] ): ?>
		                        <span class='black-orange'><?= $user['name'] ?></span>  
		                      <?php else: ?>
		                        <a class='black-orange js-g-hashint' href="<?= MainConfig::$PAGE_SETRATE . DS . $id . DS . $idus ?>" title="Оставить отзыв соискателю"><?= $user['name'] ?></a>  
		                      <?php endif; ?>                 
		                    </div>                    
		                  </div>
		                  <span class='responses__resps-date js-g-hashint' title="Дата заявки"> <?= $user['rdate'] ?> </span>
		                  <div class="controls" data-sid="<?= $user['sid'] ?>">
		                    <?php if( $user['status'] == '0'  ): ?>
		                      <div class="btn-black-02-wr"><a href="#" class="view js-g-hashint" title="Отметить заявку как просмотренная">Просмотреть</a></div>
		                    <?php endif; ?>
		                    <?php if( in_array($user['status'], [6,7]) ): ?>
		                        <?php if( $user['id_vac'] ): ?>
		                          <span>Вы выставили рейтинг данному соискателю</span>
		                        <?php else: ?>
		                          <a href="<?= MainConfig::$PAGE_SETRATE . DS . $id . DS . $idus ?>" class="responses__btn js-g-hashint" title="Оставить отзыв соискателю">Оставить отзыв</a>
		                        <?php endif; ?>
		                    <?php endif; ?>
		                    <?php if( $user['status'] == '1' || $user['status'] == '0' ): ?>
		                      <div class="btn-green-02-wr">
		                        <a href="#" class="apply js-g-hashint" title="Подтвердить заявку на вакансию">Утвердить</a>
		                      </div>
		                      <div class="btn-red-02-wr">
		                        <a href="#" class="cancel js-g-hashint" title="Отклонить заявку на вакансию">Отклонить</a>
		                      </div>
		                    <?php endif; ?>
		                    <?php if( $user['status'] != '4' && $user['status'] != 5 ): ?>
		                      <span class="status hide hint js-g-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</span>
		                    <?php endif; ?>
		                    <?php if( $user['isresponse'] == 1 && $user['status'] == 4 ): ?>
		                      <div class="hint js-g-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</div>
		                    <?php elseif( $user['isresponse'] == 2 && in_array($user['status'], [2,4]) ): ?>
		                      <div class="hint js-g-hashint" title="Вы отправили приглашение соискателю на вакансию, ожидайте его решения">Приглашение на вакансию отправлено</div>
		                    <?php endif; ?>
		                    <?php if( $user['isresponse'] == 1 && in_array($user['status'], [5]) ): ?>
		                      <span>Заявка на вакансию подтверждена обеими сторонами</span>
		                    <?php elseif( $user['isresponse'] == 2 && in_array($user['status'], [5]) ): ?>
		                      <span>Приглашение на вакансию принято соискателем</span>
		                    <?php endif; ?>
		                    <div class="clearfix"></div>
		                  </div>
		                </div>
		              <?php endforeach ?>
		            </div>
		          <?php endforeach; ?>
		        </div>
		      </div>
		      <br />
		      <br />
		      <?php
		        // display pagination
		        $this->widget('CLinkPager', array(
		          'pages' => $pages,
		          'htmlOptions' => array('class' => 'paging-wrapp'),
		          'firstPageLabel' => '1',
		          'prevPageLabel' => 'Назад',
		          'nextPageLabel' => 'Вперед',
		          'header' => '',
		      )) ?> 
			<?php else: ?>
				<div class="reviews-lock">
					<h2 class="rev-lock__title">Уважаемый работодатель,</h2>
					<p class="rev-lock__text">К сожалению Вы еще не опубликовали ни одной вакансии. (если вакансии есть опубликованные которые по времени еще актуальны - Вы еще не утвердили на свою вакансию ни одного Соискателя).<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вам необходимо разместить вакансию в Личном кабинете и утвердить Соискателей, которые отозвались на нее.<br>
					<a href="<?=MainConfig::$PAGE_VACPUB?>" class="rev__btn">Добавить вакансию</a>
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



<?
/*
*		РЕЙТИНГ ОТЗЫВОВ СОИСКАТЕЛЕЙ
*/
/*?>
<?php if(true): ?>
	<span class="red">//psd 5 5</span>
	<div class="reviews-applicant-item">
		<div class="row rai__rating-list">
			<?php $cnt = 1; ?>
			<?php for($i=0; $i<9; $i++): ?>
				<div class="col-xs-6 col-sm-4 rai-rating__item">
					<img src="/temp/reviews-list-img<?=$cnt?>.jpg" class="rai-rating__item-img js-g-hashint" title="Имя соискателя">
					<table class="rai-rating__item-table">
						<tbody>
							<tr>
								<td class="rai-rating__item-name">Рейтинг</td>
								<td class="rai-rating__item-name">Отзывы</td>
							</tr>
							<tr>
								<td>
									<ul class="rai__star-block">
										<li class="full"></li>
										<li class="full"></li>
										<li class="full"></li>
										<li class="half"></li>
										<li class="empty"></li>
									</ul>
								</td>
								<td>
									<span class="rai-rating__item-name">Всего: <a href="#" class="rai__link">18</a></span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="rai__review rai__review-green js-g-hashint" title="Положительный рейтинг">15</span>
									<span class="rai__review rai__review-red js-g-hashint" title="Отрицательный рейтинг">3</span>
								</td>
								<td>
									<span class="rai__review rai__review-green js-g-hashint" title="Положительные отзывы">15</span>
									<span class="rai__review rai__review-red js-g-hashint" title="Отрицательные отзывы">3</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php 
					if($cnt==3) $cnt=0;
					$cnt++;
				?>
			<?php endfor; ?>
			<div class="clearfix"></div>
		</div>
		<div class="rai__pagination">
			<a href="#"><</a>
			<a href="#"><<</a>
			<span>1</span>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">></a>
			<a href="#">>></a>
		</div>
		<hr class="rai__line">
		<div class="rai__see-too">так же вы можете посмотреть</div>
		<a href="#" class="rai__btn rai-btn__rating-list">отзывы соискателей</a>
	</div>
<?php endif; ?>
<?
/*
*		РЕЙТИНГ ОТЗЫВОВ РАБОТОДАТЕЛЕЙ
*/
/*?>
<?php if(true): ?>
	<span class="red">//psd 5</span>
	<div class="reviews-applicant-item">
		<div class="row rai__rating-list">
			<?php $cnt = 1; ?>
			<?php for($i=0; $i<9; $i++): ?>
				<div class="col-xs-6 col-sm-4 rai-rating__item">
					<img src="/temp/reviews-list-img1<?=$cnt?>.jpg" class="rai-rating__item-img js-g-hashint" title="Название компании">
					<table class="rai-rating__item-table">
						<tbody>
							<tr>
								<td class="rai-rating__item-name">Рейтинг</td>
								<td class="rai-rating__item-name">Отзывы</td>
							</tr>
							<tr>
								<td>
									<ul class="rai__star-block">
										<li class="full"></li>
										<li class="full"></li>
										<li class="full"></li>
										<li class="half"></li>
										<li class="empty"></li>
									</ul>
								</td>
								<td>
									<span class="rai-rating__item-name">Всего: <a href="#" class="rai__link">18</a></span>
								</td>
							</tr>
							<tr>
								<td>
									<span class="rai__review rai__review-green js-g-hashint" title="Положительный рейтинг">15</span>
									<span class="rai__review rai__review-red js-g-hashint" title="Отрицательный рейтинг">3</span>
								</td>
								<td>
									<span class="rai__review rai__review-green js-g-hashint" title="Положительные отзывы">15</span>
									<span class="rai__review rai__review-red js-g-hashint" title="Отрицательные отзывы">3</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php 
					if($cnt==3) $cnt=0;
					$cnt++;
				?>
			<?php endfor; ?>
			<div class="clearfix"></div>
		</div>
		<div class="rai__pagination">
			<a href="#"><</a>
			<a href="#"><<</a>
			<span>1</span>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">></a>
			<a href="#">>></a>
		</div>
		<hr class="rai__line">
		<div class="rai__see-too">так же вы можете посмотреть</div>
		<a href="#" class="rai__btn rai-btn__rating-list rai-btn__orange">отзывы работодателей</a>
	</div>
<?php endif; */?>