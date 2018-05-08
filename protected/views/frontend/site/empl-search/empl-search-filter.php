<div class='filter'>
  <div class='pse__filter-block filter-surname'>
    <div class='pse__filter-name opened'>Название компании</div>
    <div class='pse__filter-content opened'>
      <input name='qs' type='text' title="Введите фамилию" value="<?=$viData['get']['qs'][0]?>" class="pse__input">
      <div class="pse__filter-btn">ОК</div>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class='pse__filter-block filter-cities'>
    <div class='pse__filter-name opened'>Город</div>
    <div class='pse__filter-content opened'>
      <select class='multiple' id='pse-cities' multiple='multiple' name='cities[]'>
        <?php foreach ($viData['cities'] as $id => $val): ?>
            <option value="<?=$id?>" <?=(in_array($id, $viData['get']['cities']) ? 'selected' : '')?>><?= $val['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <?php if(sizeof($viData['cotype'])>0): ?>
    <div class='pse__filter-block filter-type'>
        <div class='pse__filter-name opened'>Тип работодателя</div>
          <div class='pse__filter-content opened'>
            <div class='right-box'>
                <?php 
                  $checked='';
                  if(sizeof($viData['get']['cotype'])>=sizeof($viData['cotype']))
                    $checked = ' checked';
                ?>
                <input name='cotype-all' type='checkbox' id="pse-cotype-all" class="pse__checkbox-input"<?=$checked?>>
                <label class='pse__checkbox-label pse__checkbox-label-ct-all' for="pse-cotype-all">Выбрать все / снять все</label>
                <?php foreach($viData['cotype'] as $id => $name): ?>
                  <input name='cotype[]' value="<?=$id?>" type='checkbox' id="pse-cotype-<?=$id?>" class="pse__checkbox-input" <?=(in_array($id, $viData['get']['cotype']) ? 'checked' : '')?>>
                  <label class='pse__checkbox-label' for="pse-cotype-<?=$id?>"><?=$name?></label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
  <?php endif; ?>
</div>