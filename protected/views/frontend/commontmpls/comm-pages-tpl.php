<?php if( $this->breadcrumbs ):
    if( Yii::app()->controller->route !== 'site/index' )
        $this->breadcrumbs = array_merge(array(Yii::t('zii','Главная')=>Yii::app()->homeUrl), $this->breadcrumbs);

    $this->widget('zii.widgets.CBreadcrumbs', array(
        "links" => $this->breadcrumbs,
        "homeLink" => false,
        "tagName" => "div",
        "separator" => " &gt; ",
        "activeLinkTemplate" => "<span itemscope='' itemprop='itemListElement' itemtype='http://schema.org/ListItem'><a rel='nofollow' itemprop='item' title='{label}' href='{url}'><span itemprop='name'>{label}</span><meta itemprop='position' content='{count}'></a></span>", 
        "inactiveLinkTemplate" => "<span>{label}</span>",
    )); ?>
<?php endif; ?>
<div id="DiContent" class="page-<?= Yii::app()->controller->action->getId() ?> <?= $this->ViewModel->getViewData()->addContentClass ?><?= ContentPlus::getActionID() ? ' action-' . ContentPlus::getActionID() : '' ?>">
    <div class="container">
        <?php if( $this->ViewModel->getViewData()->pageTitle ): ?>
            <div class="row mt20">
                <div class="col-xs-12">
                    <div class="content-header"><?= $this->ViewModel->getViewData()->pageTitle ?></div>
                    <div class="content-header-line"></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="content-block">
            <?= $content; ?>
            
        </div>
    </div>
</div>
