<div class="row">
    <div class="col-xs-12 page-prom__content">
        <div class="img-02 page-prom__img">
            <?php if( Share::$UserProfile->type != 3 ): ?>
                <div class="btn-wrapp"><a href="<?= Share::$UserProfile->type == 2 ? MainConfig::$PAGE_VACANCY : MainConfig::$PAGE_REGISTER .'?p=1' ?>" class="btn-big-swipe hvr-sweep-to-right btn__orange">найти<br/>вакансию</a></div>
            <?php endif; ?>
        </div>
        <?php echo $viData['html']; ?>
    </div>
</div>