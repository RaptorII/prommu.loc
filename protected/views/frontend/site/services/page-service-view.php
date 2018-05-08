<? if($viData['service']['id']==42): // Условия использования сайта ?>
  <?
    $this->setBreadcrumbs($title = 'Условия использования сайта', MainConfig::$PAGE_ABOUT);
    $this->pageTitle = $title;
    $this->ViewModel->setViewData('pageTitle', '<h1>' . $title . '</h1>');
  ?>
  <div class='row'>
    <div class='col-xs-12'>
      <br /><div class='text'><?= $viData['service']['html'] ?></div><br />
    </div>
  </div>
<? else: ?>
  <link rel="stylesheet" href="/theme/css/reset.css"> <!-- CSS reset -->
  <link rel="stylesheet" href="/theme/css/style.css"> <!-- Resource style -->
  <script src="/theme/js/modernizr.js"></script> <!-- Modernizr -->
  <script src="/theme/js/main.js"></script>
  <?php
    if($viData['service']['id']==213)
      $this->ViewModel->setViewData('pageTitle', 'Заказать медкнижку');
  ?>
  <div class='row'>
    <div class='col-xs-12 col-sm-4 col-lg-3'>
      <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/services/menu-for-guest.php'; ?>
    </div>
    <div class='col-xs-12 col-sm-8 col-lg-9 service-content'>
      <div class="headimg">
        <img src="/images/servicesgr/<?= $viData['service']['img'] ?>" alt="">
      </div>
      <div class='text'>
        <?= $viData['service']['html'] ?>
      </div>
      <br />

      <?php $servid = $viData['service']['id']; if($servid != 36 && $servid != 39 ):?>
        <?
          $callPopup = $viData['service']['id'];
          if($callPopup==40){
            $callPopup = 'push';
          }
          if($callPopup==46){
            $callPopup = 'sms';
          }
        ?>
        <div class='order-btn btn-orange-sm-wr' data-id="<?=$callPopup?>">
        <a href='javascript:void(0)' class="hvr-sweep-to-right">Заказать</a>
      </div>
    <?php endif ?>
    </div>
  </div>
  <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/services/popups.php'; ?>
<? endif; ?>