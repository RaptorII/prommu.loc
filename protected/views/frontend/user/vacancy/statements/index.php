<?php
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-prof-personal-area.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'vacancy/edit.css');
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/personal.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'vacancy/edit.js', CClientScript::POS_END);

switch ($section)
{
  case MainConfig::$VACANCY_APPROVED:
    $title = "Утвержденные";
    break;
  case MainConfig::$VACANCY_INVITED:
    $title = "Приглашенные";
    break;
  case MainConfig::$VACANCY_RESPONDED:
    $title = "Откликнувшиеся";
    break;
  case MainConfig::$VACANCY_DEFERRED:
    $title = "Отложенные";
    break;
  case MainConfig::$VACANCY_REJECTED:
    $title = "Отклоненные";
    break;
  case MainConfig::$VACANCY_REFUSED:
    $title = "Отказавшиеся";
    break;
}
$this->pageTitle = $title;
$this->breadcrumbs = [
  "Мои вакансии" => MainConfig::PAGE_USER_VACANCIES_LIST,
  $viData->data->title => MainConfig::$PAGE_VACANCY . DS . $viData->data->id,
  $title
];
$linkService = MainConfig::$PAGE_ORDER_SERVICE . "?id={$viData->data->id}&service=";
?>
<div class="row vacancy__statements" id="statements_vacancy">
  <?
  $this->renderPartial('../user/vacancy/top',['viData'=>$viData, 'section'=>$section]);
  ?>
  <div class="col-xs-12">
    <? if($section==MainConfig::$VACANCY_INVITED): ?>
      <?
      // вкладка с приглашенными
      ?>
      <div class="vacancy__services-list">
        <div class="vacancy__services-item">
          <a href="<?=$linkService . "push"?>"
             class="vacancy__services-link prmu-icon icon-push">
            <span>PUSH информирование</span>
          </a>
        </div>
        <?  //  ?>
        <div class="vacancy__services-item">
          <a href="<?=$linkService . "personal-invitation"?>"
             class="vacancy__services-link prmu-icon icon-personal-invitation">
            <span>Личное приглашение</span>
          </a>
        </div>
        <?  //  ?>
        <div class="vacancy__services-item">
          <a href="<?=$linkService . "email"?>"
             class="vacancy__services-link prmu-icon icon-email-invitation">
            <span>EMAIL информирование</span>
          </a>
        </div>
        <?  //  ?>
        <div class="vacancy__services-item">
          <a href="<?=$linkService . "sms"?>"
             class="vacancy__services-link prmu-icon icon-sms-informing-staff">
            <span>СМС информирование</span>
          </a>
        </div>
      </div>
      <br>
      <? if(count($viData->responses->items)): ?>
        <table class="vacancy__statements-table">
          <thead>
          <tr><th>Соискатель<th>Дата приглашения<th>Статус<th>Тип</tr>
          </thead>
          <tbody>
          <? foreach ($viData->responses->items as $v): ?>
            <? $arUser = $viData->responses->users[$v['user']]; ?>
            <td class="vacancy__statements-cell">
              <a href="<?=$arUser['profile']?>" target="_blank" class="vacancy__applicant">
                <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>" class="vacancy__applicant-avatar">
                <span class="vacancy__applicant-name"><?=$arUser['name']?></span>
              </a>
            </td>
            <td class='vacancy__statements-cell'><b>Дата приглашения:</b> <?=$v['date']?></td>
            <td class='vacancy__statements-cell'><b>Статус:</b> <?=$v['status']?></td>
            <td class='vacancy__statements-cell'><b>Тип:</b> <?=$v['type']?></td>
          <? endforeach; ?>
          </tbody>
        </table>
        <br/>
        <? $this->widget('CLinkPager', [
          'pages' => $viData->responses->pages,
          'htmlOptions' => ['class' => 'paging-wrapp'],
          'firstPageLabel' => '1',
          'prevPageLabel' => 'Назад',
          'nextPageLabel' => 'Вперед',
          'header' => '',
        ]) ?>
      <? else: ?>
        <div class="center">
          <h2>На эту вакансию вы пока не приглашали соискателей</h2>
        </div>
      <? endif; ?>
    <? else: ?>
      <?
      // остальные вкладки
      ?>
      <? if (count($viData->responses->items)): ?>
        <table class="vacancy__statements-table">
          <thead>
          <tr><th>Соискатель<th># заявки<th>Дата заявки<th>Статус</tr>
          </thead>
          <tbody>
          <? foreach ($viData->responses->items as $v): ?>
            <? $arUser = $viData->responses->users[$v['user']]; ?>
            <tr class="vacancy__statements-row">
              <td class="vacancy__statements-cell">
                <a href="<?=$arUser['profile']?>" target="_blank" class="vacancy__applicant">
                  <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>" class="vacancy__applicant-avatar">
                  <span class="vacancy__applicant-name"><?=$arUser['name']?></span>
                </a>
              </td>
              <td class='vacancy__statements-cell'><b>Номер заявки:</b> <?=$v['sid']?></td>
              <td class='vacancy__statements-cell'><b>Дата заявки:</b> <?=$v['rdate']?></td>
              <td class='vacancy__statements-cell'>
                <? if (!($v['isresponse'] == Responses::$STATE_INVITE && in_array($v['status'],[Responses::$STATUS_REJECT,Responses::$STATUS_EMPLOYER_ACCEPT]))): ?>
                  <b>Статус:</b>
                  <div class="vacancy__statements-select form__field-input form__field-select">
                    <select name="status" data-sid="<?=$v['sid']?>">
                      <option value="0" selected><?=ResponsesEmpl::getStatus($v['isresponse'],$v['status'])?></option>
                      <? if(in_array($v['status'],[Responses::$STATUS_NEW, Responses::$STATUS_VIEW, Responses::$STATUS_REJECT])): ?>
                        <option value="<?=Responses::$STATUS_APPLICANT_ACCEPT?>">Утвердить</option>
                      <? endif; ?>
                      <? if(in_array($v['status'],[Responses::$STATUS_NEW, Responses::$STATUS_REJECT, Responses::$STATUS_APPLICANT_ACCEPT])): ?>
                        <option value="<?=Responses::$STATUS_VIEW?>">Отложить</option>
                      <? endif; ?>
                      <? if(in_array($v['status'],[Responses::$STATUS_NEW, Responses::$STATUS_VIEW, Responses::$STATUS_APPLICANT_ACCEPT])): ?>
                        <option value="<?=Responses::$STATUS_REJECT?>">Отклонить</option>
                      <? endif; ?>
                    </select>
                  </div>
                <? endif; ?>
                <? if($v['status'] > Responses::$STATUS_EMPLOYER_ACCEPT): // писать только утвержденным ?>
                  <a href="<?= MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $id . DS . $v['idusr'] ?>"
                     class="vacancy__statements-btn btn__green">Написать сообщение</a>
                <? endif; ?>
                <? if (in_array($v['status'], [Responses::$STATUS_BEFORE_RATING, Responses::$STATUS_APPLICANT_RATED])): ?>
                  <a href="<?=MainConfig::$PAGE_SETRATE . DS . $id . DS . $v['idusr']?>"
                     class="vacancy__statements-btn btn__orange">Оставить отзыв</a>
                <? endif; ?>
              </td>
            </tr>
            <tr class="vacancy__statements-row-empty"><td colspan="4"></td></tr>
          <? endforeach; ?>
          </tbody>
        </table>
        <br/>
        <? $this->widget('CLinkPager', [
          'pages' => $viData->responses->pages,
          'htmlOptions' => ['class' => 'paging-wrapp'],
          'firstPageLabel' => '1',
          'prevPageLabel' => 'Назад',
          'nextPageLabel' => 'Вперед',
          'header' => '',
        ]) ?>
      <? else: ?>
        <div class="center">
          <h2>Нет заявок</h2>
        </div>
      <? endif; ?>
    <? endif; ?>
  </div>
</div>
