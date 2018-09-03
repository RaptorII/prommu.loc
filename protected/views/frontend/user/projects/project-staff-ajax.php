<?php if(sizeof($viData['users'])>0): ?>
    <div class="row">
        <? $cnt = 1; ?>
        <? foreach ($viData['users'] as $id_user => $user): ?>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="personal__item">
                    <img class="<?=(!$value['status'] ? 'personal__deact' : '')?>"
                         src="<?=$user['src']?>">
                    <div class="personal__item-name"><?=$user['name']?></div>
                    <div class="personal__item-add">
                        <? if(!empty($user['point'])): ?>
                            <a href="<?=$pLink?>/route/<?=$id_user?>">Закрепленные адреса</a>
                        <? endif; ?>
                    </div>
                    <div class="personal__item-city"><? echo join(', ', $user['cities']) ?></div>
                </div>
            </div>
            <? if($cnt%3==0): ?>
                <div class="clearfix visible-sm-block"></div>
            <? endif; ?>
            <? if($cnt%4==0): ?>
                <div class="clearfix visible-md-block visible-lg-block"></div>
            <? endif; ?>
            <? $cnt++; ?>
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
<?php else: ?>
    <p>Подходящий персонал не найдено</p>
<?php endif; ?>