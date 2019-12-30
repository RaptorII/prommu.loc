<? if(!Yii::app()->getRequest()->isAjaxRequest): ?>
<script>var pageCondition = <?=json_encode($model->data['condition']['html'])?>;</script>
<? endif; ?>
<? UserRegisterPageCounter::set($model->step); ?>
<div class="login-wrap">

    <svg x="0" y="0" class="svg-bg" />

    <h2 class="login__header">Регистрация</h2>
    <h6 class="login__header">Выберите, что вас интересует</h6>

    <div class="login__container">

        <p class="input">
            <label for="radio-1" class="btn-orange">Я ищу работу</label>
            <label class="txt">
                Хочу разместить резюме и найти работу мечты
            </label>
            <input type="radio" name="type" value="<?= UserProfile::$APPLICANT ?>" id="radio-1" class="input-type">
        </p>

        <p class="input">
            <label for="radio-2" class="btn-orange">Я ищу сотрудников</label>
            <label class="txt">
                Хочу разместить вакансию и найти сотрудников
            </label>
            <input type="radio" name="type" value="<?= UserProfile::$EMPLOYER ?>" id="radio-2" class="input-type">
        </p>

    </div>
</div>
<button class="mob-hidden" data-step="1"></button>
<?php
$rq = Yii::app()->getRequest();
$utm = Yii::app()->session['utm'];
//  Источник
if (!empty($utm->transition))
  $transition = $utm->transition;
elseif (!empty($rq->getParam('utm_source')))
  $transition = $rq->getParam('utm_source');
else
  $transition = $model->data['transition'];
//  Тип трафика
if (!empty($utm->referer))
  $referer = $utm->referer;
elseif (!empty($rq->getParam('utm_medium')))
  $referer = $rq->getParam('utm_medium');
else
  $referer = $model->data['referer'];
//  Кампания
if (!empty($utm->campaign))
  $campaign = $utm->campaign;
elseif (!empty($rq->getParam('utm_campaign')))
  $campaign = $rq->getParam('utm_campaign');
else
  $campaign = $model->data['campaign'];
//  Контент
if (!empty($utm->content))
  $content = $utm->content;
elseif (!empty($rq->getParam('utm_content')))
  $content = $rq->getParam('utm_content');
else
  $content = $model->data['content'];
//  Ключевые слова
if (!empty($utm->keywords))
  $keywords = $utm->keywords;
elseif (!empty($rq->getParam('utm_term')))
  $keywords = $rq->getParam('utm_term');
else
  $keywords = $model->data['keywords'];
//  Площадка
if (!empty($utm->pm_source))
  $pm_source = $utm->pm_source;
elseif (!empty($rq->getParam('pm_source')))
  $pm_source = $rq->getParam('pm_source');
else
  $pm_source = $model->data['pm_source'];
//  Реферер
if (!empty($utm->last_referer))
  $last_referer = $utm->last_referer;
elseif (!empty($rq->getParam('last_referer')))
  $last_referer = $rq->getParam('last_referer');
else
  $last_referer = $model->data['last_referer'];
//  Точка входа
if (!empty($utm->point))
  $point = $utm->point;
elseif (!empty($rq->getParam('point')))
  $point = $rq->getParam('point');
else
  $point = $model->data['point'];
?>
<input type="hidden" name="transition" value="<?=$transition?>">
<input type="hidden" name="referer" value="<?=$referer?>">
<input type="hidden" name="campaign" value="<?=$campaign?>">
<input type="hidden" name="content" value="<?=$content?>">
<input type="hidden" name="keywords" value="<?=$keywords?>">
<input type="hidden" name="pm_source" value="<?=$pm_source?>">
<input type="hidden" name="ip" value="<?=$_SERVER['HTTP_X_FORWARDED_FOR']?>">
<input type="hidden" name="last_referer" value="<?=$last_referer?>">
<input type="hidden" name="point" value="<?=$point?>">
<input type="hidden" name="client" value="<?=Yii::app()->request->cookies['_ga']?>">



