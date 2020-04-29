<? $serviceCreate = $viData->services->creation_vacancy; ?>
<? if(count($serviceCreate->items)): // есть неоплаченные услуги по созданию вакансии ?>
  <div class="personal__area--capacity border__red">
    <div class="personal__area--capacity-name text__red">Важно!</div>
    <p class="text__justify text__red">На данный момент Ваша вакансия сохранена, но не опубликована. Для публикации необходимо произвести оплату</p>
    <? if(count($serviceCreate->legal_links)): ?>
      <? foreach ($serviceCreate->legal_links as $key => $v): ?>
        <a href="<?=$v?>" class="text__underline" target="_blank">Ссылка на счет №<?=$key+1?></a><br>
      <? endforeach; ?>
    <? endif; ?>
    <? if(!empty($serviceCreate->individual_link)): ?>
      <a href="<?=$serviceCreate->individual_link?>" class="text__underline" target="_blank">Ссылка для оплаты</a><br>
    <? endif; ?>
    <br>
  </div>
<? elseif($viData->data->status==Vacancy::$STATUS_NO_ACTIVE && $viData->data->is_actual_remdate): ?>
  <div class="personal__area--capacity border__red">
    <div class="personal__area--capacity-name text__red">Важно!</div>
    <? if(Share::$UserProfile->ismoder==User::$ISMODER_ACTIVE): // Пользователь промодерирован ?>
      <p class="text__justify text__red">На данный момент Ваша вакансия сохранена, но не опубликована - Вы можете опубликовать вакансию сразу,
        нажав кнопку “ОПУБЛИКОВАТЬ ВАКАНСИЮ” или согласно наших рекомендаций заполнить дополнительные данные,
        которые помогут оперативнее и качественнее, в сжатые сроки, найти необходимый персонал,
        ну и главное проверить корректность введения данных по Вашей вакансии.</p>
      <a href="javascript:void(0)" class="btn__orange" id="activate">ОПУБЛИКОВАТЬ ВАКАНСИЮ</a>
    <? else: ?>
      <p class="text__justify text__red">Сейчас нельзя опубликовать вакансию: Ваш профиль на стадии модерации</p>
    <? endif; ?>
  </div>
<? endif; ?>