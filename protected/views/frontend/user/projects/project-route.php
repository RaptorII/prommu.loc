<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
?>

<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
      <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
    </div>
  </div>
</div>

<div class="project__module">
  <div class="project__route-header">
    <div class="project__addr-xls">
      <a href="#">Изменить адресную программу</a>
      <a href="#">Скачать существующую</a>
      <a href="#">Добавить адресную программу</a>
      <input type="file" name="xls" class="hide" accept="xls">
    </div>
    <div class="project__route-filter">
      <div class="route__header-city">
        <label>Город</label>
        <input type="text" name="city">
      </div>
      <div class="route__header-date">
        <div>
          <label>Дата с</label>
          <input type="text" name="bdate" class="route__filter-date">
        </div>
        <div>
          <label>По</label>
          <input type="text" name="edate" class="route__filter-date">
        </div>
      </div>
    </div>
  </div>
  <div class="routes">
    <div class="route__item">
      <h2 class="route__item-title">Харьков</h2>
      <table class="route__table">
        <thead>
          <tr>
            <th>ФИО</th>
            <th>Название ТТ</th>
            <th>Адрес ТТ</th>
            <th>Статус посещения</th>
            <th>Дата</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td rowspan="3">
              <div class="route__table-cell route__table-user">
                <img src="/images/applic/20180503073112204100.jpg">
                <span>Дмитриев<br/>Николай</span>
              </div>
            </td>
            <td>
              <div class="route__table-cell border">АТБ1</div>
            </td>
            <td>
              <div class="route__table-cell border route__table-index">
                <span>ул. Пирогова 23</span>
                <b class="js-g-hashint" title="Посмотреть на карте"></b>
              </div>
            </td>
            <td>
              <div class="route__table-cell border route__table-status">
                <span>2</span>
                <a href="#">изменить</a>
              </div>
            </td>
            <td>
              <div class="route__table-cell border text-center">14.02.2018</div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="route__table-cell border">ВАРУС</div>
            </td>
            <td>
              <div class="route__table-cell border route__table-index">
                <span>пр. Кирова 18</span>
                <b class="js-g-hashint" title="Посмотреть на карте"></b>
              </div>
            </td>
            <td>
              <div class="route__table-cell border route__table-status">
                <span>1</span>
                <a href="#">изменить</a>
              </div>
            </td>
            <td>
              <div class="route__table-cell border text-center">14.02.2018</div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="route__table-cell border">СЕЛЬПО</div>
            </td>
            <td>
              <div class="route__table-cell border route__table-index">
                <span>ул. Строителей 4</span>
                <b class="js-g-hashint" title="Посмотреть на карте"></b>
              </div>
            </td>
            <td>
              <div class="route__table-cell border route__table-status">
                <span>3</span>
                <a href="#">изменить</a>
              </div>
            </td>
            <td>
              <div class="route__table-cell border text-center">14.02.2018</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="routes__map">
    <div class="routes__map-city">Харьков</div>
    <div class="routes__map-map">
      <img src="/theme/pic/projects/temp-map-2.jpg">
    </div>
  </div>
  <div class="routes__btns">
    <a href="#" class="route__watch-btn">ИЗМЕНИТЬ</a>
    <a href="#" class="route__watch-btn">СМОТРЕТЬ МАРШРУТ</a>
  </div>
</div>
