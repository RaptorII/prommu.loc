<? if(sizeof($viData['location'])>0): ?>
  <? foreach ($viData['location'] as $id => $arCity): ?>
    <div class="address__item">
      <h2 class="address__item-title">
        <b><?=$arCity['name']?></b>
        <span class="address__item-change">
          <span><a href="<? echo 'address-edit?city=' . $id ?>">изменить</a></span>
          <ul>
            <li><a href="<? echo 'address-edit?city=' . $id ?>">изменить</a></li>
            <li data-id="<?=$id?>" class="delcity">удалить</li>
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
            <tr class="loc-item">
              <td>
                <div class="addr__table-cell"><?=$arLoc['name']?></div>
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
                <div class="addr__table-cell border text-center">
                  <span class="address__item-change loc">
                      <span><a href="<? echo 'address-edit?city=' . $id . '&loc=' . $idloc?>">изменить</a></span>
                    <ul>
                      <li><a href="<? echo 'address-edit?city=' . $id . '&loc=' . $idloc?>">изменить</a></li>
                      <li data-id="<?=$idloc?>" data-idcity="<?=$id?>" class="delloc">удалить</li>
                    </ul>
                  </span>
                </div>
              </td>
            </tr>
          <? endforeach; ?>
        </tbody>
      </table>
    </div>
  <? endforeach; ?>
<? else: ?>
  <h2 class="address__item-title">Подходящиe локации не найдено</h2>
<? endif; ?>