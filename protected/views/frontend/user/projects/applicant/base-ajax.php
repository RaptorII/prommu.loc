<?php
/**
 * Created by PhpStorm.
 * User: Stanislav
 * Date: 19.09.2018
 * Time: 14:40
 */

if(sizeof($viData['items'])>0): ?>
  <?php
    foreach ($viData['items'] as $d => $date):
      foreach ($date as $city):
  ?>
    <div class="task__item">
      <h2 class="task__item-title"><?=$city['city']?> <span><?=$city['date']?></span></h2>
      <table class="task__table">
        <thead>
          <tr>
            <th class="name">Название ТТ</th>
            <th class="index">Адрес ТТ</th>
            <th class="metro">Метро</th>
            <th class="time">Время</th>
            <th class="task">Кол-во заданий</th>
            <th class="btn"></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            foreach ($city['users'] as $idus => $arPoints): 
              foreach ($arPoints as $p):
                $point = $viData['points'][$p];
          ?>
            <tr>
              <td class="name">
                <div class="task__table-cell border"><?=$point['name']?></div>
              </td>
              <td class="index">
                <div class="task__table-cell border task__table-index">
                    <span><?=$point['adres']?></span>
                    <b
                            data-map-project="<?= $project ?>"
                            data-map-user="<?= $idus ?>"
                            data-map-point="<?=$p;?>"
                            data-map-date="<?= $d ?>"
                            class="js-g-hashint js-get-target all__geo-data" title="Посмотреть на карте">
                    </b>
                </div>
              </td>
                <td class="metro">
                  <div class="task__table-cell border task__table-index">
                    <span>
                        <?if(!empty($point['metro'])): ?>
                            <?=$point['metro']?>
                        <?else:?>
                            -
                        <?endif;?>
                    </span>
                  </div>
                </td>
              <td class="time">
                <div class="task__table-cell border text-center"><?=$point['btime'].'-'.$point['etime']?></div>
              </td>
              <td class="task">
                <div class="task__table-cell border task__table-cnt">
                  <? $tasks = sizeof($viData['tasks'][$d][$p][$idus]); ?>
                  <span><?=$tasks?></span>
                  <?if($tasks):?>
                    <span
                      class="task__table-watch"
                      data-user="<?=$idus?>"
                      data-date="<?=$city['date']?>"
                      data-point="<?=$p?>"
                      >подробнее</span>
                  <?endif;?>
                </div>
              </td>
              <td class="btn">
                <div class="app__loc-send" 
                  data-project="<?=$project?>"
                  data-point="<?=$p?>"
                  data-user="<?=$idus?>"
                  >ОТМЕТИТЬСЯ</div>
              </td>
            </tr>
          <?php 
              endforeach;
            endforeach; 
          ?>
        </tbody>
      </table>
    </div>
  <?php
      endforeach;
    endforeach;
  ?>
<?php else: ?>
  <br><p class="center">Не найдено локаций с выбранным персоналом</p>
<?php endif; ?>