<?php
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
  Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-index.css');
  Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-index.js', CClientScript::POS_END);

  $bDate = '07.02.18';
  $bDateFull = '07.02.2018';
  $eDate = '01.08.18';
  $eDateFull = '01.08.2018';
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
<div class="project__module">
  <div class="project__addr-header">
    <div class="project__addr-xls">
      <a href="#">Изменить адресную программу</a>
      <a href="#">Скачать существующую</a>
      <a href="#">Добавить адресную программу</a>
      <input type="file" name="xls" class="hide" accept="xls">
    </div>
    <form class="project__addr-filter" id="filter-form">
      <div class="addr__header-city">
        <label>Город</label>
        <span class="city-filter">Все</span>
        <ul class="city-list">
          <li data-id="0">Все</li>
          <? foreach ($arProgram as $id => $arCity)
            echo '<li data-id="' . $id . '">' . $arCity['name'] . '</li>';
          ?>
        </ul>
        <input type="hidden" name="city" class="city-input" value="0">
      </div>
      <div class="addr__header-date">
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
          <input type="hidden" name="edate" value="<?=$eDateFull?>">
        </div>
      </div>
      <input type="hidden" name="project" value="<?=Yii::app()->getRequest()->getParam('id')?>" class="project-inp">
    </form>
  </div>
  <div class="addresses">
    <? foreach ($arProgram as $id => $arCity): ?>
      <div class="address__item">
        <h2 class="address__item-title">
          <b><?=$arCity['name']?></b>
          <span class="address__item-change">
            <span>изменить</span>
            <ul>
              <li><a href="<? echo 'address-edit?city=' . $id . '&loc=new' ?>">добавить локацию</a></li>
              <li data-id="<?=$id?>" class="delcity">удалить город</li>
            </ul>
          </span>
        </h2>
        <table class="addr__table">
          <thead>
            <tr>
              <th>Название</th>
              <th>Адрес</th>
              <? if(!empty($arCity['metro'])): ?><th>Метро</th><? endif; ?>
              <th>Дата</th>
              <th>Время</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <? foreach ($arCity['locations'] as $idloc => $arLoc): ?>
              <tr>
                <td>
                  <div class="addr__table-cell border"><?=$arLoc['name']?></div>
                </td>
                <td>
                  <div class="addr__table-cell border"><?=$arLoc['index']?></div>
                </td>
                <? if(!empty($arCity['metro'])): ?>
                  <td>
                    <div class="addr__table-cell border"><? echo join(',</br>', $arLoc['metro']) ?></div>
                  </td>
                <? endif; ?>
                <td><div class="addr__table-cell border text-center">
                  <? foreach ($arLoc['periods'] as $idper => $arPer)
                    echo '<span>' . $arPer['bdate'] . ' - ' . $arPer['edate'] . '</span>';
                  ?>
                </div></td>
                <td><div class="addr__table-cell border text-center">
                  <? foreach ($arLoc['periods'] as $idper => $arPer)
                    echo '<span>' . $arPer['btime'] . ' - ' . $arPer['etime'] . '</span>';
                  ?> 
                </div></td>
                <td>
                  <div class="addr__table-cell text-center">
                    <a href="<? echo 'address-edit?city=' . $id . '&loc=' . $idloc ?>">изменить</a>
                  </div>
                </td>
              </tr>
            <? endforeach; ?>
          </tbody>
        </table>
      </div>
    <? endforeach; ?>
    <?/*
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
    */?>
  </div>
  <div class="addresses__btns">
    <a href="<? echo 'address-edit?city=new' ?>" class="addr__save-btn">Добавить город</a>
  </div>
</div>
