<?php if(sizeof($viData['users'])>0): ?>
  <? $point = Yii::app()->getRequest()->getParam('point'); ?>
  <div class="row">
    <div class="col-xs-12 users-select__list">
      <div class="row">
        <? $cnt = 0; ?>
        <? foreach ($viData['users'] as $user): ?>
          <div class="col-xs-12 col-sm-4 col-md-3">
            <? $s = $user['status']; ?>
            <div class="users-select__item <?=$s<0?'fail':($s>0?'':'disable')?>">
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
          <? 
            $cnt++;
            if($cnt%3==0)
              echo '<div class="clearfix visible-xs visible-sm"></div>';
            if($cnt%4==0)
              echo '<div class="clearfix visible-md visible-lg"></div>';
          ?>
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
    <div class="staff__block">
        <a class="staff__new" target="_blank" href="/user/projects/<?=$project?>/staff?type=add">Добавить персонал</a>
        <a class="staff__new" target="_blank" href="/user/projects/<?=$project?>/staff?type=new">Пригласить персонал</a>
    </div>
<?php endif; ?>