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
  <div class="project__geo-filter">
    <div class="geo__header-city">
      <label>Город</label>
      <input type="text" name="city">
    </div>
    <div class="geo__header-date">
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
  <div class="project__geo-list">
    <div class="project__geo-item">
      <h2 class="geo__item-title">город: <span>Москва</span></h2>
      <table class="geo__item-table">
        <thead>
          <tr>
            <th>Сотрудник</th>
            <th>Статус</th>
            <th>Кол-во ТТ</th>
            <th>Старт работы</th>
            <th>Последнее место</th>
            <th>Дата</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="geo__table-cell geo__table-user">
                <img src="/images/applic/20180503073112204100.jpg">
                <span>Ибадулаев<br/>Павел</span>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">
                <span class="geo__green">&#9679 активен</span>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">5</div>
            </td>
            <td>
              <div class="geo__table-cell">
                <span class="geo__green">начал</span>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">
                <div class="geo__table-cell geo__table-loc">
                  <span>АТБ1</span>
                  <b class="js-g-hashint" title="Посмотреть на карте"></b>
                </div>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">06.02.2018</div>
            </td>
            <td>
              <div class="geo__table-cell">
                <a href="#">подробнее</a>
              </div>
            </td>
          </tr>
          <?
          //
          ?>
          <tr>
            <td>
              <div class="geo__table-cell geo__table-user">
                <img src="/images/applic/20180428142455264100.jpg">
                <span>Бондаренко<br/>Наталья</span>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">
                <span class="geo__grey">нет</span>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">5</div>
            </td>
            <td>
              <div class="geo__table-cell">
                <span class="geo__red">просрочил</span>
              </div>
            </td>
            <td>
              <div class="geo__table-cell geo__table-loc">
                <span>АТБ1</span>
                <b class="js-g-hashint" title="Посмотреть на карте"></b>
              </div>
            </td>
            <td>
              <div class="geo__table-cell">06.02.2018</div>
            </td>
            <td>
              <div class="geo__table-cell">
                <a href="#">подробнее</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <?
  //
  ?>
  <div class="geo__item-cart">
    <div class="geo-item__cart-data">
      <img src="/images/applic/20180503073112204100.jpg">
      <div class="geo-item__cart-info">
        <div class="geo-item__cart-filter">
          <div class="geo-item__filter-date">
            <label>Дата с</label>
            <input type="text" name="bdate" placeholder="<?=date('d.m.y')?>">
          </div>
          <div class="geo-item__filter-date">
            <label>По</label>
            <input type="text" name="edate" placeholder="<?=date('d.m.y')?>">
          </div>
        </div>

        <div class="geo-item__cart-bl1">
          <div class="geo-item__cart-name">Ибадулаев Павел</div>
          <div class="geo-item__cart-border">
            <div>
              <span class="geo__green">&#9679 активен</span> / <span class="geo__red">&#9679 неактивен</span>
            </div>
            <div>Дата: 06.02.2018</div>
          </div>
        </div>

        <div class="geo-item__cart-bl2">
          <div>
            <div>
              <span>Старт работ: </span>
              <span class="geo__green">начал в 9:30</span>
              <span> / </span>
              <span class="geo__red">опоздание на 20 мин.</span>
            </div>
            <div>
              <span>Последнее место: АТБ1 </span>
              <b></b>
            </div>
          </div>
          <div class="geo-item__cart-cur">
            <div><span>Сейчас в: АТБ1 </span><b></b></div>
            <a href="#" class="geo-item__route">Показаь маршрут передвижения</a>
          </div>
        </div>
      </div>
    </div>
    <table class="geo__item-table geo-item__table-single">
      <thead>
        <tr>
          <th>Название</th>
          <th>Адрес</th>
          <th>План работ</th>
          <th>Факт работ</th>
          <th>Задачи по ТТ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="geo__table-cell">АТБ1</div>
          </td>
          <td>
            <div class="geo__table-cell geo__table-loc">
              <span>ул. Пирогова 23</span>
              <b class="js-g-hashint" title="Посмотреть на карте"></b>
            </div>
          </td>
          <td>
            <div class="geo__table-cell">07.02.2018  14:00</div>
          </td>
          <td>
            <div class="geo__table-cell">07.02.2018 с 9:00 до 18:00</div>
          </td>
          <td>
            <div class="geo__table-cell">12</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
