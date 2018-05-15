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
    <div class="col-xs-12 page-empl__content">
        <div class="img-01 page-empl__img">
            <?php if( Share::$UserProfile->type != 2 ): ?>
                <div class="btn-wrapp btn-orange-fix-wr"><a href="<?= Share::$UserProfile->type == 3 ? MainConfig::$PAGE_VACPUB : MainConfig::$PAGE_REGISTER ?>" class="hvr-sweep-to-right">опубликовать<br/>вакансию</a></div>
            <?php endif; ?>
        </div>
        <?php echo $viData['html']; ?>
    </div>
</div>

