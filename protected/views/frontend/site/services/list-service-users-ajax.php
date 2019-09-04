<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 03.09.2019
 * Time: 13:33
 */
$service = filter_var(Yii::app()->getRequest()->getParam('service'), FILTER_SANITIZE_NUMBER_INT);
$model = new ServiceCloud();
$viData = $model->getServiceUsers($service, Share::$UserProfile->id);
?>

<? if(count($viData['items'])): ?>
  <div class="history__users-list row">
    <? foreach ($viData['items'] as $v): ?>
      <a href="<?=$v['profile']?>" target="_blank">
        <img src="<?=$v['src']?>" alt="<?=$v['name']?>">
        <b><?=$v['name']?></b>
      </a>
    <? endforeach; ?>
  </div>
  <?php
  // display pagination
  $this->widget('CLinkPager', array(
    'pages' => $viData['pages'],
    'htmlOptions' => array('class' => 'paging-wrapp'),
    'firstPageLabel' => '1',
    'prevPageLabel' => 'Назад',
    'nextPageLabel' => 'Вперед',
    'header' => '',
    'cssFile' => false
  ));
  ?>
<? else: ?>
  <p>Пользователей не найдено</p>
<? endif; ?>