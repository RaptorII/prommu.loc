<?
$model = new UserRegisterPageCounter();
$viData = $model->searchAll();
?>
<? if(count($viData['id'])): ?>
  <?
  // count
  ?>
  <tr class="default">
    <td colspan="13" class="default empty"><?
      $n = count($viData['id']);
      $sum = $viData['offset'] + $viData['limit'];
      echo 'Элементы ' . ($viData['offset']+1) . '—' . ($n<$sum ? $n : $sum) . ' из ' . count($viData['id']) . '.';
      ?></td>
  </tr>
  <? foreach ($viData['items'] as $k => $item): ?>
    <tr>
      <td style="width:5%"><?=($item['id'] ?: '-')?></td>
      <td style="width:5%"><?=AdminView::getUserType($item['type'])?></td>
      <td style="width:2%"><?=$item['page_1']?></td>
      <td style="width:2%"><?=$item['page_2']?></td>
      <td style="width:2%"><?=$item['page_3']?></td>
      <td style="width:2%"><?=$item['page_4']?></td>
      <td style="width:2%"><?=$item['page_5']?></td>
      <td style="width:2%"><?=$item['page_6']?></td>
      <td style="width:2%"><?=$item['page_7']?></td>
      <td style="width:5%"><?=$item['time_page']?></td>
      <td style="width:5%"><?=$item['time_create']?></td>
      <td style="width:3%"><?=($item['social']
          ? '<span class="glyphicon glyphicon-ok-sign" title="зарегистрирован через соцсети"></span>' : '-')?></td>
      <td style="width:3%"><?=($item['id_user']
          ? $item['id_user'] . ' ' . AdminView::getUserProfileLink($item['id_user'],$item['type'])
          : '-')?></td>
    </tr>
  <? endforeach; ?>
  <?
  // pagination
  ?>
  <tr class="default pagination_cell">
    <td colspan="13" class="default">
      <div class="pager">
        <? $this->widget('CLinkPager', ['pages' => $viData['pages']]); ?>
      </div>
    </td>
  </tr>
<? else: ?>
  <?
  // empty result
  ?>
  <tr class="empty">
    <td colspan="13">Ничего не найдено.</td>
  </tr>
<? endif; ?>