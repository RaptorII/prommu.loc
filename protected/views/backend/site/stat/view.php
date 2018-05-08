
<div class="table table-hover" name="my-grid" style="padding: 10px;" id="dvgrid">
<div class="summary">Мониторинг системы</div>
<table class="table table-bordered table-hover dataTable">
<thead>
    <tr>
        <th id="dvgrid_c1">Время</th>
        <th id="dvgrid_c1">Модуль</th>
        <th id="dvgrid_c2">Ошибка</th>
        <th id="dvgrid_c2">Ответственный</th>
        <th id="dvgrid_c2">Исправление</th>
    </tr>
    <tr class="filters">
</thead>
<tbody>
  <!--   <? if($items == "Проблем не обнаружено"): ?>
    <tr class="odd">
        <td>Все модули</td>
        <td><span class="ф" >Проблем нет</span></td>
    </tr>
<? else:?> -->
    <? for($i = count($items)-2; $i > 0; $i--):?>
    <?  
               $dat = explode("[", $items[$i]);
               $tex = explode("Stack", $items[$i]);
               $module = explode("/var", $items[$i]);
    ?>
    
    <tr class="odd">
        <td><?=$dat[0]?></td>
        <td><span class="" ><?=$module[1];?></span></td>
        <td><span class="" ><?=$tex[0];?></span></td>
        <td><span class="" >Бекенд</span></td>
        <td><span class="" >В процессе</span></td>
    </tr>
    <? endfor;?>
<!-- <? endif;?> -->
</tbody>
</table>

</div>