<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 16.07.2019
 * Time: 15:24
 */
?>
<?
$bUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($bUrl . MainConfig::$CSS . '/private/check-self-employed.css');
$cs->registerScriptFile($bUrl . MainConfig::$JS . '/private/check-self-employed.js', CClientScript::POS_END);
?>
<h1><?=$this->pageTitle?></h1>
<div class="row">
  <div class="col-xs-12 check-inn">
    <div class="check-inn__block">
      <div class="prmu-btn prmu-btn_small"><span>+</span></div>
      <label>Введите ИНН соискателя</label>
      <div class="check-inn__block-input"><input type="text" name="inn[]"></div>
    </div>
    <span class="prmu-btn prmu-btn_normal" id="check_inn">
      <span>Проверить</span>
    </span>
  </div>
</div>