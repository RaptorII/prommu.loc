
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
<script type="text/javascript">
$(document).ready(function() {
  $(".templatingSelect2").select2();
});
</script>
<div class='row'>
  <div class='col-xs-12 col-sm-4 col-lg-3 no-md-relat'>
    <?php
      
          $G_NOLIKES = 1;
          $G_LOGO_LINK = 0;
          $G_LOGO_SRC = DS . MainConfig::$PATH_EMPL_LOGO . DS . (!Share::$UserProfile->exInfo->logo ?  'logo.png' : (Share::$UserProfile->exInfo->logo) . '400.jpg');
          $G_COMP_FIO = Share::$UserProfile->exInfo->name;
          $G_NOSTATS = 1;
          include __DIR__ . DS . MainConfig::$VIEWS_COMM_LOGO_TPL . ".php"; ?>
  </div>
  <div class='col-xs-12 col-sm-8 col-lg-9'>
    <div class='header-021'>
      <b>Мои вакансии</b>
      <b class='-green'><?= count($viData['vacs']) ?></b>
    </div>
    <div class='btn-add btn-orange-sm-wr'>
      <a class='hvr-sweep-to-right' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
    </div>
    <br>
    <br>

    <?php if( $viData['vacs'] ): ?>
      <?php foreach ($viData['vacs'] as $key => $val): ?>
      <?php /*for( $i = 0; $i < $count = count($viData['vacs']); $i++ ): $val = array_values($vaData['vacs'])[$ii] */?>
        <div class='vac'>
          <div class='response'>
            <span title='Отклики'></span>
            <i class='hidden-xs hidden-sm'>Отклики</i>
            <?= $val['isresp'][1] ?>
          </div>
          <div class='views'>
            <span title='Просмотры'></span>
            <i class='hidden-xs hidden-sm'>Просмотры</i>
            <?= $val['isresp'][0] ?>
          </div>
          <div class='title'>

            <a class='black-orange' href='<?= MainConfig::$PAGE_VACANCY . DS . $val['id'] ?>'><?= $val['title'] ?></a>
          </div>
          <div class="date-publ">
            <?php if( $val['status'] ): ?>
              <span class='status -green js-g-hashint' colspan='4' title="опубликована">открытая</span>&nbsp;&nbsp;
            <?php else: ?>
              <span class='status js-g-hashint' colspan='4' title="снята с публикации">закрытая</span>&nbsp;&nbsp;
            <?php endif; ?>
            <span class="js-g-hashint" title='дата размещения вакансии'><?= $val['crdate'] ?></span> - <span class="js-g-hashint" title='дата окончания вакансии'><?= $val['remdate'] ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      Пока нет опубликованных вакансий
    <?php endif; ?>
    <br />
    <br />
