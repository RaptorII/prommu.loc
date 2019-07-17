<div class="row news-list__row">
    <?php foreach ($viData as $key => $val): ?>
        <div class="col-xs-12 col-sm-4 col-md-4 news-block news-list__item">
            <a href="<?= MainConfig::$PAGE_NEWS . DS . $val['link'] ?>" class="img news-list__img-link">
                <div class="news-list__img" style="background-image: url(<?=Settings::getFilesUrl() . 'news/' . $val['img'] . News::$SMALL_IMG?>)"></div>
                <i></i>
            </a>
            <div class="big news-list__text-block">
                <a href="<?= MainConfig::$PAGE_NEWS . DS . $val['link'] ?>">
                    <p class="news-list__title"><?= $val['name'] ?></p>
                </a>
            </div>
            <p class="news-list__date"><?= $val['pubdate'];?></p>      
        </div>
    <?php endforeach; ?>
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


