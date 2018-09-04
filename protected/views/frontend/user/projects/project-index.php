<?php
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
  Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-index.css');
  Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-index.js', CClientScript::POS_END);
?>
<div class="filter__veil"></div>
<div class="row project">
	<div class="col-xs-12">
    <? require __DIR__ . '/project-nav.php'; ?>
  </div>
</div>
<div class="project__module">
  <div class="project__addr-header">
    <div class="project__addr-xls">
      <a href="/user/uploadprojectxls?id=<?=$project?>&type=index" id="add-xls">Изменить адресную программу</a>
      <a href="/uploads/prommu_example.xls" download>Скачать пример для добавления</a>
      <form enctype="multipart/form-data" action="" method="POST" id="xls-form">
        <input type="hidden" name="project" class="project-inp" value="<?=$project?>">
        <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
        <input type="file" name="xls" id="add-xls-inp" class="hide">
        <input type="hidden" name="xls-index" value="1">
      </form>
    </div>
    <form class="project__addr-filter" id="filter-form">
      <div class="addr__header-city">
        <label>Город</label>
        <span class="city-filter">Все</span>
        <ul class="city-list">
          <li data-id="0">Все</li>
          <? foreach ($viData['cities'] as $id => $city)
            echo '<li data-id="' . $id . '">' . $city . '</li>';
          ?>
        </ul>
        <input type="hidden" name="city" class="city-input" value="0">
      </div>
      <div class="addr__header-date">
        <div class="calendar-filter">
          <label>Дата с</label>
          <span><?=$viData['bdate-short']?></span>
          <div class="calendar" data-type="bdate">
            <table>
              <thead>
              <tr>
                <td class="mleft">‹
                <td colspan="5" class="mname">
                <td class="mright">›
              </tr>
              <tr>
                <td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
              </tr>
              <tbody></tbody>
            </table>
          </div>
          <input type="hidden" name="bdate" value="<?=$viData['bdate']?>">
        </div>
        <div class="calendar-filter">
          <label>По</label>
          <span><?=$viData['edate-short']?></span>
          <div class="calendar" data-type="bdate">
            <table>
              <thead>
              <tr>
                <td class="mleft">‹
                <td colspan="5" class="mname">
                <td class="mright">›
              </tr>
              <tr>
                <td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
              </tr>
              <tbody></tbody>
            </table>
          </div>
          <input type="hidden" name="edate" value="<?=$viData['edate']?>">
        </div>
      </div>
      <input type="hidden" name="project" value="<?=$project?>" class="project-inp">
    </form>
  </div>
  <div class="addresses">
    <?php require __DIR__ . '/project-index-ajax.php'; ?>
  </div>
  <div class="addresses__btns">
    <a href="<? echo 'address-edit?city=new' ?>" class="addr__save-btn">Добавить город</a>
  </div>
</div>
