<?php if(sizeof($viData['items'])>0): ?>
  <div class="routes">
    <?php
      foreach ($viData['items'] as $unix => $user):
        foreach ($user as $id_user => $arCity):
          foreach ($arCity as $id_city => $city):
    ?>
      <div class="route__item">
        <h2 class="route__item-title"><?=$city['city']?> <span><?=$city['date']?></span></h2>
        <div class="route__item-box">
          <table class="route__table">
            <thead>
              <tr>
                <th class="route__table-cell-user">ФИО</th>
                <th class="route__table-cell-name">Название ТТ</th>
                <th class="route__table-cell-adres">Адрес ТТ</th>
                <? if(!empty($city['ismetro'])): ?>
                  <th class="route__table-cell-metro">Метро</th>
                <? endif; ?>
                <th class="route__table-cell-time">Время</th>
              </tr>
            </thead>
            <tbody>
              <?php $user = $viData['users'][$id_user]; ?>
              <tr>
                <td rowspan="<?=sizeof($city['points'])?>" class="route__table-cell-user">
                  <div class="route__table-cell route__table-user">
                    <img src="<?=$user['src']?>">
                    <span><?=$user['name']?></span>
                  </div>
                </td>
                <?php $cnt = 0; ?>
                <?php foreach ($city['points'] as $p): ?>
                  <?php $point = $viData['points'][$p]; ?>  
                  <td class="route__table-cell-name">
                    <div class="route__table-cell border"><?=$point['name']?></div>
                  </td>
                  <td class="route__table-cell-adres">
                    <div class="route__table-cell border route__table-index">
                      <span><?=$point['adres']?></span>
                      <b data-map-project="<?=$project?>"
                         data-map-user="<?=$id_user?>"
                         data-map-point="<?=$p?>"
                         data-map-date="<?=$unix?>"
                         class="js-g-hashint js-get-map" title="Посмотреть на карте">
                      </b>
                    </div>
                  </td>
                  <?php if(!empty($city['ismetro'])): ?>
                    <td class="route__table-cell-metro">
                      <div class="task__table-cell border task__table-index">
                        <span><?=$point['metro']?></span>
                      </div>
                    </td>
                  <?php endif; ?>

                  <td class="route__table-cell-time">
                    <div class="route__table-cell border">
                      <span><?=$point['btime'] . '-' . $point['etime']?></span>
                    </div>
                  </td>
                  <?php $cnt++; ?>
                  <? if($cnt<sizeof($viData['points'])) echo '</tr><tr>'; ?>
                <?php endforeach; ?>
              </tr>
            </tbody>
          </table>
          <div class="routes__map"></div>
          <div class="routes__btns">
            <a href="#content_top" class="route__watch-btn route__button-change">ИЗМЕНИТЬ</a>
            <span class="route__watch-btn route__button-map">СМОТРЕТЬ МАРШРУТ</span>
          </div>
        </div>                
      </div>
    <?php
          endforeach;
        endforeach;
      endforeach;
    ?>
  </div>
<?php else: ?>
  <br><p class="center">Не найдено локаций с выбранным персоналом</p>
<?php endif; ?>