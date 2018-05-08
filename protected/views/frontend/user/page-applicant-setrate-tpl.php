<?php

  $saveResp = $this->ViewModel->getViewData()->saveResp;

  if( $saveResp['saved'] == 1 ): ?>
    <div class="mess-box"><?= $saveResp['message'] ?></div>
<?php else: ?>
  <div class='row'>
    <?php if( $viData['error'] ): ?>   
      <div class="col-xs-12">
        <div class="reviews-lock">
          <?php if(Share::$UserProfile->type==2): ?>
            <h2 class="rev-lock__title">Уважаемый Соискатель,</h2>
            <p class="rev-lock__text">К сожалению Вы еще не были утверждены, ни одним Работодателем ни на одной вакансии.<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вас должен утвердить Работодатель на опубликованную вакансию в Личном кабинете.<br><br>После завершения работы по выбранной вакансии Вы сможете оставить отзыв и оценить работодателя по вопросам которые больше всего интересуют соискателей временной работы - что в дальнейшем поможет другим Вашим коллегам и нашему сервису выявлять лучших либо блокировать недобросовестных Работодателей.</p>
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
          <?php elseif(Share::$UserProfile->type==3): ?>
            <h2 class="rev-lock__title">Уважаемый работодатель,</h2>
            <p class="rev-lock__text">К сожалению Вы еще не опубликовали ни одной вакансии. (если вакансии есть опубликованные которые по времени еще актуальны - Вы еще не утвердили на свою вакансию ни одного Соискателя).<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вам необходимо разместить вакансию в Личном кабинете и утвердить Соискателей, которые отозвались на нее.<br><br>После завершения работы по выбранной вакансии Вы сможете оставить отзыв и оценить всех работников по вопросам которые больше всего интересуют Работодателей - что в дальнейшем поможет другим Вашим коллегам и нашему сервису выявлять лучших или недобросовестных Соискателей.</p>
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
          <?php endif; ?> 
        </div>
      </div>
    <?php 
    /*
    *
    *
    *
    */
    else: ?>
      <div class="reviews-applicant-item">
        <div class="row">
          <div class="col-xs-12 col-sm-3">
            <?php if(Share::$UserProfile->type==2): ?>
              <img src="<?=DS.MainConfig::$PATH_EMPL_LOGO.DS.(!$viData['user']['logo'] ?  'logo.png' : $viData['user']['logo'].'400.jpg')?>" class="rai__img  js-g-hashint" title="<?= $viData['user']['username'] ?>">
            <?php else: ?>
              <img src="<?=DS.MainConfig::$PATH_APPLIC_LOGO.DS.(!$viData['user']['logo'] ? MainConfig::$DEF_LOGO : $viData['user']['logo'].'400.jpg')?>" class="rai__img  js-g-hashint" title="<?= $viData['user']['username'] ?>">     
            <?php endif; ?>
          </div>
          <div class="col-xs-12 col-sm-9">
            <form action="" method="post" id="F1rate"> 
              <h2 class="rai__title"><span class="rai__subtitle"></span> <?= $viData['user']['username'] ?></h2>
              <span class="rai__subtitle">Общий рейтинг</span> 
              <ul class="rai__star-block">
                <? $rt = $data['rating']['full'];
      if($rt == 0):?>
         <li></li>
         <li></li>
         <li></li>
         <li></li>
         <li></li>
      <? elseif($rt > 0 && $rt <= 2):?> 
        <li class="full"></li>
        <li class="full"></li>
        <li></li>
        <li></li>
        <li></li>
      <? elseif($rt > 0 && $rt <= 1.5):?>
         <li class="full"></li>
         <li></li>
         <li></li>
         <li></li>
         <li></li>
      <? elseif($rt > 1.1 && $rt <= 2.5):?>
         <li class="full"></li>
         <li class="full"></li>
         <li></li>
         <li></li>
         <li></li>
      <? elseif($rt > 2.5 && $rt <= 3.5):?>
         <li class="full"></li>
         <li class="full"></li>
         <li class="full"></li>
         <li></li>
         <li></li>
      <? elseif($rt > 3.5 && $rt <= 4.5):?>
         <li class="full"></li>
         <li class="full"></li>
         <li class="full"></li>
         <li class="full"></li>
         <li></li>
      <? elseif($rt > 4.5 && $rt <= 5):?>
         <li class="full"></li>
         <li class="full"></li>
         <li class="full"></li>
         <li class="full"></li>
         <li class="full"></li>
      <? endif;?>
      </ul>
              <span class="rai__subtitle"><?=$rt?> из 5.0 баллов</span>
              <hr class="rai__line">
              <span class="rai__subtitle">Отзывы</span>
              <span class="rai__review rai__review-green js-g-hashint" title="Положительные отзывы"><?=$data['lastComments']['count'][1]?></span>
              <span class="rai__review rai__review-red js-g-hashint" title="Отрицательные отзывы"><?=$data['lastComments']['count'][0]?></span>
              
              <hr class="rai__line">
              <?php if(Share::$UserProfile->type==2): ?>
                <span class="rai__subtitle">Актуальные проекты: <a href="https://prommu.com/ankety/<?=$viData['user']['idusempl']?>" class="rai__link"><?=$data['lastComments']['count'][1]?></a></span>
               
              <?php else: ?>
                 <span class="rai__subtitle">Отработанные проекты: <a href="https://prommu.com/ankety/<?=$viData['user']['iduspromo']?>" class="rai__link"><?=$data['lastComments']['count'][1]?></a></span>
                
              <?php endif; ?>
              <hr class="rai__line">
              <div class="rai__b-subtitle">добавить Рейтинг</div>
              <table class="rai__table">
                <tbody> 
                  <?php foreach ($viData['pointRate'] as $key => $val): ?>
                    <tr>
                      <td class="rai-table__name"><span><?=$viData['rateNames'][$key]?></span></td>
                      <td class="rai-table__cnt">
                        <input type="radio" name="rate[<?=$key?>][]" value="1" id="plus-<?=$key?>">
                        <label class="rai-table__label plus js-g-hashint" for="plus-<?=$key?>" title="Положительная оценка">+1</label>
                      </td>
                      <td class="rai-table__cnt">
                        <input type="radio" name="rate[<?=$key?>][]" value="0" id="zero-<?=$key?>">
                        <label class="rai-table__label zero js-g-hashint" for="zero-<?=$key?>" title="Нейтральная оценка">0</label>
                      </td>
                      <td class="rai-table__cnt">
                        <input type="radio" name="rate[<?=$key?>][]" value="-1" id="minus-<?=$key?>">
                        <label class="rai-table__label minus js-g-hashint" for="minus-<?=$key?>" title="Отрицательная оценка">-1</label>
                      </td>
                    </tr>            
                  <?php endforeach; ?>
                </tbody>
              </table>

              <hr class="rai__line">
              <div class="rai__b-subtitle">Отзыв</div>
              <input type="radio" name="type" value="1" id="RB1posi" class="rai__review-input">
              <label class="rai__review-label plus" for="RB1posi" id="LPosi">добавить положительный отзыв</label>
              <input type="radio" name="type" value="2" id="RB2neg" class="rai__review-input">
              <label class="rai__review-label minus" for="RB2neg" id="LNeg">добавить негативный отзыв</label>
              <textarea placeholder="Отзыв" name="comment" class="rai__review-area" data-counter="2000" data-field-check="name:Текст отзыва,max:2000"></textarea>
              <div id="BtnSaveData"><button type="submit" class="rai__btn">СОХРАНИТЬ</button></div>
              <div class="clearfix"></div>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
