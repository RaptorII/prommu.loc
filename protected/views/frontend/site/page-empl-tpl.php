<div class="row">
    <div class="col-xs-12 page-empl__content">
        <div class="img-01 page-empl__img">
            <?php if( Share::$UserProfile->type != 2 ): ?>
                <div class="btn-wrapp btn-orange-fix-wr"><a href="<?= Share::$UserProfile->type == 3 ? MainConfig::$PAGE_VACPUB : MainConfig::$PAGE_REGISTER .'?p=2' ?>" class="hvr-sweep-to-right btn__orange">опубликовать<br/>вакансию</a></div>
            <?php endif; ?>
        </div>
        <?php echo $viData['html']; ?>
    </div>
</div>

