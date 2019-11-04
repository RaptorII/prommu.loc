<? if(!Yii::app()->getRequest()->isAjaxRequest): ?>
<script>var pageCondition = <?=json_encode($model->data['condition']['html'])?>;</script>
<? endif; ?>
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
                Хочу разместить вакансии и найти сотрудников
            </label>
            <input type="radio" name="type" value="<?= UserProfile::$EMPLOYER ?>" id="radio-2" class="input-type">
        </p>

    </div>
</div>
<?php $get = Yii::app()->getRequest(); ?>
<input type="hidden" class="referer" name="referer" value="<?=$get->getParam('referer')?>">
<input type="hidden" class="transition" name="transition" value="<?=$get->getParam('transition')?>">
<input type="hidden" class="canal" name="canal" value="<?=$get->getParam('canal')?>">
<input type="hidden" class="campaign" name="campaign" value="<?=$get->getParam('campaign')?>">
<input type="hidden" class="content" name="content" value="<?=$get->getParam('content')?>">
<input type="hidden" class="keywords" name="keywords" value="<?=$get->getParam('keywords')?>">
<input type="hidden" class="point" name="point" value="<?=$get->getParam('point')?>">
<input type="hidden" class="last_referer" name="last_referer" value="<?=$get->getParam('last_referer')?>">
<input type="hidden" name="ip" value="<?=$_SERVER['HTTP_X_FORWARDED_FOR']?>">
<input type="hidden" name="pm_source" value="<?=Yii::app()->request->cookies['pm_source']?>">
<input type="hidden" name="client" value="<?=Yii::app()->request->cookies['_ga']?>">