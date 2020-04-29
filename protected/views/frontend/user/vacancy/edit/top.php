<?php
$linkInfo = MainConfig::$PAGE_VACANCY . DS . $viData->data->id . DS;
$hasChat = count($viData->chat->users);
if(!$viData->data->is_actual_remdate && $viData->data->date_public)
  $col = 3;
elseif(!$viData->data->is_actual_remdate)
  $col = 4;
elseif ($viData->data->is_actual)
  $col = 3;
elseif ($viData->data->date_public)
  $col = 4;
else
  $col = 6;
?>
<? if($viData->data->date_public): ?>
  <div class="col-xs-12">
    <ul class="vacancy-top__list<?=!$hasChat?' vacancy-top__list-small':''?>">
      <li class="vacancy-top__item js-g-hashint" title="Количество посетителей, переходивших на размещенную вакансию">
        <div class="prmu-icon icon-views">Просмотров <div class="vacancy-top__cnt"><?=$viData->counters->views?></div></div>
      </li>
      <li class="vacancy-top__item js-g-hashint" title="Соискатели, которые откликнулись на вакансию и хотят на ней работать">
        <a href="<?=$linkInfo . MainConfig::$VACANCY_RESPONDED?>" class="vacancy-top__link prmu-icon icon-responded">Откликнувшиеся
          <div class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_RESPONDED}?></div></a>
      </li>
      <li class="vacancy-top__item js-g-hashint" title="Соискатели, которых пригласили на вакансию">
        <a href="<?=$linkInfo . MainConfig::$VACANCY_INVITED?>" class="vacancy-top__link prmu-icon icon-invited">Приглашенные
          <div class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_INVITED}?></div></a>
      </li>
      <li class="vacancy-top__item js-g-hashint" title="Соискатели, которые утверждены на вакансию">
        <a href="<?=$linkInfo . MainConfig::$VACANCY_APPROVED?>" class="vacancy-top__link prmu-icon icon-approved">Утвержденные
          <div class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_APPROVED}?></div></a>
      </li>
      <li class="vacancy-top__item js-g-hashint" title="Соискатели, которых еще не отклонили, но и не утвердили">
        <a href="<?=$linkInfo . MainConfig::$VACANCY_DEFERRED?>" class="vacancy-top__link prmu-icon icon-deferred">Отложенные
          <div class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_DEFERRED}?></div></a>
      </li>
      <li class="vacancy-top__item js-g-hashint" title="Соискатели, которым Вы отказали в работе">
        <a href="<?=$linkInfo . MainConfig::$VACANCY_REJECTED?>" class="vacancy-top__link prmu-icon icon-rejected">Отклоненные
          <div class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_REJECTED}?></div></a>
      </li>
      <li class="vacancy-top__item js-g-hashint" title="Соискатели, которые отказались сотрудничать">
        <a href="<?=$linkInfo . MainConfig::$VACANCY_REFUSED?>" class="vacancy-top__link prmu-icon icon-refused">Отказавшиеся
          <div class="vacancy-top__cnt"><?=$viData->counters->{MainConfig::$VACANCY_REFUSED}?></div></a>
      </li>
      <? if ($hasChat): ?>
        <li class="vacancy-top__item js-g-hashint" title="Чат со всеми утвержденными соискателями">
          <a href="<?= MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $viData->data->id ?>" class="vacancy-top__link prmu-icon icon-chat">Общий чат
            <div class="vacancy-top__cnt"><?= $viData->chat->discuss_cnt?></div></a>
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
    <div class="row vacancy__buttons">
      <? if($viData->data->date_public): ?>
        <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
          <p class="vacancy__date-public">Вакансия опубликована: <span class="text__nowrap"><?=$viData->data->date_public?></span></p>
        </div>
      <? endif; ?>
      <? if(!$viData->data->is_actual_remdate): // Вакансия завершена ?>
        <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
          <a href='<?=MainConfig::$PAGE_REVIEWS?>' class="btn__orange">Оценить персонал</a>
        </div>
      <? endif; ?>
      <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
        <a href="<?=MainConfig::$PAGE_VACPUB . "?duplicate=Y&id={$viData->data->id}"?>" class="btn__orange">Дублировать вакансию</a>
        <span class="form__field-hint tooltip" title="Выберите одну должность, которая необходима Вам для набора персонала.
              Если Вам необходимо подобрать несколько должностей Вы сможете дублировать размещенную
              вакансию и при этом изменить должность или другие параметры вакансии"></span>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
        <a href="<?=MainConfig::$VIEW_CHECK_SELF_EMPLOYED?>" class="btn__orange">Проверка налогового статуса</a>
      </div>
      <? if($viData->data->is_actual && $viData->data->is_actual_remdate): ?>
        <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
          <a href="javascript:void(0)" class="btn__orange" id="deactivate">Скрыть вакансию</a>
        </div>
      <? endif; ?>
    </div>
    <div class="personal__area--separator"></div>
  </div>
<? endif; ?>