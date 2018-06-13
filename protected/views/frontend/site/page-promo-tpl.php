<?php
    // устанавливаем title
    $this->pageTitle = $viData['meta_title'];
    $this->setBreadcrumbsEx(array($viData['meta_title'], MainConfig::$PAGE_PROMO_INFO));
    // устанавливаем h1
    $this->ViewModel->setViewData('pageTitle', '<h1>' . $viData['meta_title'] . '</h1>');
    // устанавливаем description
    Yii::app()->clientScript->registerMetaTag($viData['meta_description'], 'description');
?>
<div class="row">
    <div class="col-xs-12 page-prom__content">
        <div class="img-02 page-prom__img">
            <?php if( Share::$UserProfile->type != 3 ): ?>
                <div class="btn-wrapp"><a href="https://prommu.com/user/register?p=1" class="btn-big-swipe hvr-sweep-to-right">найти<br/>вакансию</a></div>
            <?php endif; ?>
        </div>
        <?php echo $viData['html']; ?>
    </div>
</div>