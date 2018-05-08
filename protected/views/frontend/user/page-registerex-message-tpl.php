<div class='row'>
  <div class='col-xs-12 register-wrapp'>
    <div class="complete-block">
      <?php if( $viData['complete'] ): ?>
          <h3><?= $viData['message'] ?></h3><br />
          <div class="btn-reg btn-orange-wr">
            <a class="hvr-sweep-to-right" href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>">Продолжить регистрацию</a>
          </div>
      <?php else: ?>
          <div class="center">
            <h2><?= $viData['message'] ?></h2>
          </div>
      <?php endif; ?>
    </div>
  </div>
</div>