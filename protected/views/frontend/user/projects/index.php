<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
?>

<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
      <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/nav.php'; ?>
    </div>
  </div>
</div>

<div class="project__module">
  <div class="project__addr-header">
    <div class="project__addr-xls">
      <a href="#">Изменить адресную программу</a>
      <a href="#">Скачать существующую</a>
      <a href="#">Добавить адресную программу</a>
      <input type="file" name="xls" class="hide" accept="xls">
    </div>
    <div class="project__addr-filter">
      <div class="addr__header-city">
        <label>Город</label>
        <input type="text" name="city">
      </div>
      <div class="addr__header-date">
        <div>
          <label>Дата с</label>
          <input type="text" name="bdate">
        </div>
        <div>
          <label>По</label>
          <input type="text" name="edate">
        </div>
      </div>
    </div>
  </div>
  <div class="addresses">
    <div class="address__item">
      <h2 class="address__item-title">москва</h2>
      <table class="addr__table">
        <thead>
          <tr>
            <th>Название</th>
            <th>Адрес</th>
            <th>Дата</th>
            <th>Время</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="addr__table-cell border">АТБ1</div>
            </td>
            <td>
              <div class="addr__table-cell border">ул. Исполкомовская 123</div>
            </td>
            <td>
              <div class="addr__table-cell border text-center">07.02.2018 – 08.02.2018</div>
            </td>
            <td>
              <div class="addr__table-cell border text-center">14:00 – 16:00</div>
            </td>
            <td>
              <div class="addr__table-cell text-center">
                <a href="#">изменить</a>
              </div>
            </td>
          </tr>



          <tr>
            <td>
              <div class="addr__table-cell border">АТБ1</div>
            </td>
            <td>
              <div class="addr__table-cell border">ул. Исполкомовская 123</div>
            </td>
            <td>
              <div class="addr__table-cell border text-center">07.02.2018 – 08.02.2018</div>
            </td>
            <td>
              <div class="addr__table-cell border text-center">14:00 – 16:00</div>
            </td>
            <td>
              <div class="addr__table-cell text-center">
                <a href="#">изменить</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="addresses__btns">
    <a href="#" class="addr__save-btn">Добавить</a>
  </div>
</div>
