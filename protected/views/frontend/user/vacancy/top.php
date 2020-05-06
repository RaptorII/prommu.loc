<?php
$linkInfo = MainConfig::$PAGE_VACANCY . DS . $viData->data->id . DS;
$hasChat = count($viData->chat->users);
?>
<div class="col-xs-12">
  <ul class="vacancy-top__list<?=!$hasChat?' vacancy-top__list-small':''?>">
    <? if(!isset($section)): ?>
      <li class="vacancy-top__item js-g-hashint" title="Количество посетителей, переходивших на размещенную вакансию">
        <div class="prmu-icon icon-views">Просмотров <div class="vacancy-top__cnt"><?=$viData->counters->views?></div></div>
      </li>
    <? else: ?>
      <li class="vacancy-top__item js-g-hashint" title="Вернуться на страницу вакансии">
        <a href="<?=MainConfig::$PAGE_VACANCY . DS . $viData->data->id?>"
           class="vacancy-top__link prmu-icon icon-return vacancy-top__link-return">Назад</a>
      </li>
    <? endif; ?>
    <li class="vacancy-top__item js-g-hashint" title="Соискатели, которые откликнулись на вакансию и хотят на ней работать">
      <a href="<?=$linkInfo . MainConfig::$VACANCY_RESPONDED?>"
         class="vacancy-top__link prmu-icon icon-responded<?=$section==MainConfig::$VACANCY_RESPONDED?' vacancy-top__link-active':''?>">Откликнувшиеся
        <span class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_RESPONDED}?></span></a>
    </li>
    <li class="vacancy-top__item js-g-hashint"
        title="Соискатели, которых пригласили на вакансию">
      <a href="<?=$linkInfo . MainConfig::$VACANCY_INVITED?>"
         class="vacancy-top__link prmu-icon icon-invited<?=$section==MainConfig::$VACANCY_INVITED?' vacancy-top__link-active':''?>">Приглашенные
        <span class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_INVITED}?></span></a>
    </li>
    <li class="vacancy-top__item js-g-hashint"
        title="Соискатели, которые утверждены на вакансию">
      <a href="<?=$linkInfo . MainConfig::$VACANCY_APPROVED?>"
         class="vacancy-top__link prmu-icon icon-approved<?=$section==MainConfig::$VACANCY_APPROVED?' vacancy-top__link-active':''?>">Утвержденные
        <span class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_APPROVED}?></span></a>
    </li>
    <li class="vacancy-top__item js-g-hashint"
        title="Соискатели, которых еще не отклонили, но и не утвердили">
      <a href="<?=$linkInfo . MainConfig::$VACANCY_DEFERRED?>"
         class="vacancy-top__link prmu-icon icon-deferred<?=$section==MainConfig::$VACANCY_DEFERRED?' vacancy-top__link-active':''?>">Отложенные
        <span class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_DEFERRED}?></span></a>
    </li>
    <li class="vacancy-top__item js-g-hashint"
        title="Соискатели, которым Вы отказали в работе">
      <a href="<?=$linkInfo . MainConfig::$VACANCY_REJECTED?>"
         class="vacancy-top__link prmu-icon icon-rejected<?=$section==MainConfig::$VACANCY_REJECTED?' vacancy-top__link-active':''?>">Отклоненные
        <span class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_REJECTED}?></span></a>
    </li>
    <li class="vacancy-top__item js-g-hashint"
        title="Соискатели, которые отказались сотрудничать">
      <a href="<?=$linkInfo . MainConfig::$VACANCY_REFUSED?>"
         class="vacancy-top__link prmu-icon icon-refused<?=$section==MainConfig::$VACANCY_REFUSED?' vacancy-top__link-active':''?>">Отказавшиеся
        <span class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_REFUSED}?></span></a>
    </li>
    <? if ($hasChat): ?>
      <li class="vacancy-top__item js-g-hashint" title="Чат со всеми утвержденными соискателями">
        <a href="<?= MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $viData->data->id ?>" class="vacancy-top__link prmu-icon icon-chat">Общий чат
          <span class="vacancy-top__cnt"><?= $viData->chat->discuss_cnt?></span></a>
      </li>
      <li class="vacancy-top__item js-g-hashint">
        <div class="prmu-icon icon-chat" id="chat_list" title="Личный чат с каждым утвержденным соискателем">
          <div>Личный чат<div class="vacancy-top__cnt"><?=$viData->chat->discuss_cnt?></div></div>
          <ul class="vacancy-chat__list">
            <? foreach ($viData->chat->users as $v): ?>
              <li class="vacancy-chat__item">
                <a href="<?= MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $viData->data->id . DS . $v['id'] ?>">
                  <img src="<?= $v['src'] ?>" alt="<?= $v['name'] ?>">
                  <span><?= $v['name'] ?></span>
                </a>
              </li>
            <? endforeach; ?>
          </ul>
        </div>
      </li>
    <? endif; ?>
  </ul>
  <div class="personal__area--separator"></div>
</div>
