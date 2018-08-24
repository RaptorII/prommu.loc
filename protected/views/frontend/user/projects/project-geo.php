<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-geo.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-geo.js', CClientScript::POS_END);

  $bDate = '07.02.18';
  $bDateFull = '07.02.2018';
  $eDate = '01.10.18';
  $eDateFull = '01.10.2018';
  $arProgram = array(
    1307 => array(
      'name' => 'Москва',
      'id' => 1307,
      'metro' => 1,
      'locations' => array(
        1 => array(
          'id' => 1,
          'name' => 'АТБ1',
          'index' => 'ул. Исполкомовская 123',
          'metro' => array(
            1 => 'Авиамоторная',
            2 => 'Автозаводская (Замоскворецкая линия)',
            4 => 'Алексеевская'
          ),
          'periods' => array(
            1 => array(
              'id' => 1,
              'bdate' => '07.02.18',
              'edate' => '08.02.18',
              'btime' => '14:00',
              'etime' => '16:00'
            ),
            2 => array(
              'id' => 2,
              'bdate' => '20.02.18',
              'edate' => '22.02.18',
              'btime' => '09:00',
              'etime' => '18:00'
            )
          )
        ),
        2 => array(
          'id' => 2,
          'name' => 'АТБ2',
          'index' => 'ул. Исполкомовская 777',
          'metro' => array(
            4 => 'Алексеевская'
          ),
          'periods' => array(
            3 => array(
              'id' => 3,
              'bdate' => '01.08.18',
              'edate' => '01.08.18',
              'btime' => '12:00',
              'etime' => '13:00'
            )
          )
        )
      )
    ),
    2582 => array(
      'name' => 'Донецк',
      'id' => 2582,
      'metro' => 0,
      'locations' => array(
        3 => array(
          'id' => 3,
          'name' => 'АТБ3',
          'index' => 'ул. Исполкомовская 999',
          'metro' => array(),
          'periods' => array(
            4 => array(
              'id' => 4,
              'bdate' => '07.02.18',
              'edate' => '08.02.18',
              'btime' => '14:00',
              'etime' => '16:00'
            ),
            5 => array(
              'id' => 5,
              'bdate' => '20.02.18',
              'edate' => '22.02.18',
              'btime' => '09:00',
              'etime' => '18:00'
            )
          )
        ),      
      )
    )
  );
?>
<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($arProgram); ?>
</pre>
<div class="filter__veil"></div>
<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
      <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
    </div>
  </div>
</div>
<?
//
?>
<div class="project__module">
  <form action="" class="project__geo-filter" id="filter-form">
    <div class="geo__header-city">
      <label>Город</label>
      <div class="city-filter">
        <span class="city-filter__select">Все</span>
        <ul class="city-list">
          <li data-id="0">Все</li>
          <? foreach ($arProgram as $id => $arCity)
            echo '<li data-id="' . $id . '">' . $arCity['name'] . '</li>';
          ?>
        </ul>
        <input type="hidden" name="city" class="city-input" value="0">      	
      </div>
    </div>
    <div class="geo__header-date">
			<div class="calendar-filter">
				<label>Дата с</label>
				<span><?=$bDate?></span>
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
				<input type="hidden" name="bdate" value="<?=$bDateFull?>">
			</div>
			<div class="calendar-filter">
				<label>По</label>
				<span><?=$eDate?></span>
				<div class="calendar" data-type="edate">
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
				<input type="hidden" name="edate" value="<?=$eDateFull?>">
			</div>
    </div>
    <input type="hidden" name="project" value="<?=Yii::app()->getRequest()->getParam('id')?>" class="project-inp">
  </form>
  <?
  //
  ?>
  <div class="project__geo-list" id="geo-list">
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
            <input type="text" name="fbdate" placeholder="<?=date('d.m.y')?>">
          </div>
          <div class="geo-item__filter-date">
            <label>По</label>
            <input type="text" name="fedate" placeholder="<?=date('d.m.y')?>">
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
