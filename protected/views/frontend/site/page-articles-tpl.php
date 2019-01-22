<div class="row articles-list__row">
    <?php foreach ($viData as $key => $val): ?>
        <div class="col-xs-12 col-sm-4 col-md-4 news-block articles-list__item">
            <?/*<p class="articles-list__date"><?= $val['pubdate'];?></p>*/?>
            <a href="<?= MainConfig::$PAGE_ARTICLES . DS . $val['link'] ?>" class="img articles-list__img-link">
                <!--img src="/images/articles/<?= $val['imgSM'] ?>" alt=""-->
                <div class="articles-list__img" style="background-image: url(/images/articles/<?= $val['imgSM']?>)"></div>
                <i></i>
            </a>
            <div class="big articles-list__text-block">
                <a href="<?= MainConfig::$PAGE_ARTICLES . DS . $val['link'] ?>">
                    <p class="articles-list__title" ><?= $val['name'] ?></p>
                </a>
            </div>         
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