<div class='comm-logo-wrapp'>
  <div class='comm-logo'>
    <?php if( !$G_NOLIKES ): ?>
      <a class='like' href='#'></a>
      <div class='status' href='#'>on-line</div>
    <?php endif; ?>

    <?php /*if (!file_exists(DOCROOT . DS . ($src = MainConfig::$PATH_EMPL_LOGO . DS . Share::$UserProfile->exInfo->logo)) ) $src = MainConfig::$PATH_EMPL_LOGO . DS . 'logo.jpg';*/ ?>

    <?php if( $G_LOGO_LINK ): ?>
      <a href="<?= $G_LOGO_LINK ?>"><img alt='<?= $G_ALT ?>' src='<?= $G_LOGO_SRC ?>'></a>
    <?php else: ?>
      <?php if( $G_LOGO_SRC ): ?>
        <img alt='<?= $G_ALT ?>' src='<?= $G_LOGO_SRC ?>'>
      <?php endif; ?>
    <?php endif; ?>

    <br>
    <br>
    <?php if( $G_LOGO_LINK ): ?>
      <a href="<?= $G_LOGO_LINK ?>"><b class="name"><? echo $G_COMP_FIO ?></b></a>
    <?php else: ?>
      <b class="name"><? echo $G_COMP_FIO ?></b>
    <?php endif; ?>

    <?php if( $G_COMP_NAME ): ?>
      <i class='compname'><?= $G_COMP_NAME ?></i>
    <?php endif; ?>

    <?php if( $G_TMPL_PH1 ): ?>
      <div class='tmpl-ph1'><?= $G_TMPL_PH1 ?></div>
    <?php endif; ?>

    <?php if( !$G_NOSTATS ): ?>
    <div class='hr'>
      <?php /* if( is_numeric($G_PROFILE_VIEWS) ):
        <div class='views' title='Просмотры'><?= $G_PROFILE_VIEWS ?></div>
      <?php endif; */ ?>
      <?php if( is_numeric($G_COMMENTS_POS) ): ?>
        <div class='comments js-g-hashint' title='Отзывы положительные | отрицательные'>
          <span class='r1'><?= $G_COMMENTS_POS ?></span> | <?= $G_COMMENTS_NEG ?>
        </div>
      <?php endif; ?>
      <?php if( is_numeric($G_RATE_POS) ): ?>
        <div class='rate js-g-hashint' title='Рейтинг положительный | отрицательный'>
          <span class='r1'><?= $G_RATE_POS ?></span> | <?= $G_RATE_NEG ?>
        </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
