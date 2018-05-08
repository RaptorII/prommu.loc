<?php
    $this->pageTitle = 'Редактирование оплаты для вакансии';

?>
<input type="hidden" name="block" value="2"/>
<div class="erv__subtitle"><h2>ОПЛАТА ЗА ПРОЕКТ</h2></div>
<div class="erv__module">
    <div class="erv__label erv__salary">
        <div class="erv__input-name erv__salary-name">Заработная плата *</div>
        <div class="erv__salary-inputs">
            <label class="erv__input-name erv__quarter-item">     
                <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-hour" value="<?=$viData['vac']['shour']>0 ? intval($viData['vac']['shour']) : ''?>"/>
                <span class="erv__input-name"><?=(!$viData['vac']['istemp'] ? 'руб / час' : 'за проект')?></span>
            </label>
            <label class="erv__input-name erv__quarter-item">
                <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-week" value="<?=$viData['vac']['sweek']>0 ? intval($viData['vac']['sweek']) : ''?>"/>
                <span class="erv__input-name">руб / неделя</span>
            </label>
            <label class="erv__input-name erv__quarter-item">
                <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-month" value="<?=$viData['vac']['smonth']>0 ? intval($viData['vac']['smonth']) : ''?>"/>
                <span class="erv__input-name">руб / месяц</span>
            </label>
            <label class="erv__input-name erv__quarter-item">
                <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-visit" value="<?=$viData['vac']['svisit']>0 ? intval($viData['vac']['svisit']) : ''?>"/>
                <span class="erv__input-name">руб / посещение</span>
            </label>
        </div>
    </div>

    <div class="erv__label erv__select" data-info="Сроки оплаты *">
        <?php
            $paylims = '';
            foreach($viData['vacAttribs'] as $key => $item)
                if(in_array($key, [130,132,133,134,163]))
                    $paylims = $item['name'];
            $custPay = false;
            foreach($viData['vacAttribs'] as $key => $item)
                if(in_array($key, [164])){
                    $paylims = $item['val'];
                    $custPay = true;
                }
        ?>
        <input type="text" name="str-paylims" class="erv__input erv__required" placeholder="Сроки оплаты *" id="rv-paylims" value="<?=$paylims?>" disabled>
        <input type="hidden" name="paylimit" id="ev-custom-paylims" value="<?=($custPay ? $paylims : '')?>">
        <div class="erv__veil" id="rv-paylims-veil"></div>
        <ul class="erv__select-list" id="rv-paylims-list">
            <i class="erv__select-list-icon">OK</i>
            <?php foreach ($viData['userDictionaryAttrs'] as $val):?>
                <?php if( $val['idpar'] == 131 ): ?>
                    <li>
                        <input type="radio" name="user-attribs[paylims]" value="<?= $val['id'] ?>" id="paylims-<?=$val['id']?>" <?=($this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $val['id']))  ? 'checked' : ''?> data-name="<?=$val['name']?>">
                        <label for="paylims-<?=$val['id']?>">
                            <table><td><p><?=$val['name']?></p><td><b></b></table>
                        </label>
                    </li>
                <?php endif; ?>
            <?php endforeach;?>
            <?php if($custPay): // свой вариант ?>
                <li>
                    <input type="radio" name="user-attribs[paylims]" value="164" id="paylims-164" checked>
                    <label for="paylims-164">
                        <table><td><p><?=$paylims?></p><td><b></b></table>
                    </label>
                </li>

            <?php endif; ?>
            <li class="erv__exp-new">
              <input type="text" name="inp-new-term" placeholder="Свой вариант" id="inp-new-term">
              <span id="add-new-term">Добавить</span>
            </li>
        </ul>
    </div>
    <div class="erv__label" data-info="Комментарии">
        <textarea name="user-attribs[salary-comment]" class="erv__input"><?=$viData['vacAttribs'][165]['val']?></textarea>
    </div>
    <div class="erv__label erv__label-tbl">
        <div>
            <input type="checkbox" name="card-prommu" class="erv__input erv__hidden" id="rv-card-prommu" value="1" <?=($viData['vac']['cardPrommu'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-card-prommu">Наличие банковской карты Prommu</label>    
        </div>
        <div>
            <input type="checkbox" name="bank-card" class="erv__input erv__hidden" id="rv-bank-card" value="1" <?=($viData['vac']['card'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-bank-card">Наличие банковской карты</label>    
        </div>
    </div>
</div>