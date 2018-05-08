<!-- <?php var_dump($viData);?> -->
            <?php for( $i = 0; $i < $val->level; $i++ ): ?>
                &nbsp;&nbsp;
            <?php endfor; ?>
<style type="text/css">
    .level0 {
            color: #212121;
            font-size: 20px;
            text-decoration: underline;
    }
    #DiContent a {
    line-height: 40px;
}
li {
    list-style-type: none; /* Убираем маркеры */
   }
</style>
 <div class='row'>
    <?php foreach ($viData as $key => $val): ?>
        
        <div class='col-xs-12 col-sm-3'>
        </div>
      
        <li class="level<?= $val->level ?>">

            <a href="<?= $val->link ?>"><?= $val->name ?></a>
        </li>
    <?php endforeach; ?>

        <div class='col-xs-12 col-sm-3'>
        </div>
         <div class='col-xs-12 col-sm-3'>
        </div>
         <div class='col-xs-12 col-sm-3'>
        </div>
        <div class='col-xs-12 col-sm-3'>
        </div>
</div>



<?php
  // display pagination
  $this->widget('CLinkPager', array(
    'pages' => $pages,
    'htmlOptions' => array('class' => 'paging-wrapp'),
    'firstPageLabel' => '1',
    'prevPageLabel' => 'Назад',
    'nextPageLabel' => 'Вперед',
    'header' => '',
)) ?>

