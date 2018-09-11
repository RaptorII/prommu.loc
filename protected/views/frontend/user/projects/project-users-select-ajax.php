<?php if(sizeof($viData['users'])>0): ?>
  <? $point = Yii::app()->getRequest()->getParam('point'); ?>
  <div class="row">
    <div class="col-xs-12 users-select__list">
      <div class="row">
        <? foreach ($viData['users'] as $user): ?>
          <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="users-select__item <?=(!$user['status']?'disable':'')?>">
              <img src="<?=$user['src']?>">
              <span><?=$user['name']?></span>
            </div>
            <input 
              type="checkbox" 
              name="user[]" 
              value="<?=$user['id_user']?>" 
              id="user-<?=$user['id_user']?>"
              <?=(in_array($point, $user['points']))?'checked':''?>>
            <label for="user-<?=$user['id_user']?>"></label>
          </div>
        <? endforeach; ?>
      </div>
    </div>
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
<?php else: ?>
  <p class="center">Подходящий персонал не найден</p>
<?php endif; ?>