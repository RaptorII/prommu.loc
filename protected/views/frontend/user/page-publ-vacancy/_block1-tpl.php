<?php
    $this->pageTitle = 'Редактирование параметров необходимого специалиста для вакансии';
?>
<script type="text/javascript">var arPosts = <?=json_encode($viData['posts']);?></script>
<input type="hidden" name="block" value="1">
<div class="erv__subtitle erv__subtitle-who"><h2>Какой специалист нужен и с какими параметрами?</h2></div>
<div class="erv__module">
    <label class="erv__label" data-info="Заголовок вакансии * (не более 70 символов)">
        <input type="text" name="vacancy-title" class="erv__input erv__required" placeholder="Заголовок вакансии *" id="rv-vac-title" value="<?=$viData['vac']['title']?>">
    </label>
    <?
    //  Должности
    ?>
    <div class="erv__label">
        <div class="fav__select-posts">
            <span<? if(sizeof($viData['vac']['post'])) echo " style='display:none'";?>>Должность *</span>
            <ul id="ev-posts-select">
                <?php foreach($viData['vac']['post'] as $k => $v): ?>
                    <li data-id="<?=$k?>"><?=$v?><i></i><input type="hidden" name="posts[]" value="<?=$k?>"></li>
                <?php endforeach; ?>   
            </ul>
            <ul id="ev-posts-list"><li data-id="0"><input type="text" name="p"></li></ul>
        </div>
    </div>
    <?
    //  Опыт работы
    ?>
    <div class="erv__label erv__select" data-info="Опыт работы *">
        <?php $arExp = array(1=>'Без опыта',2=>'До 1 месяца',3=>'От 1 до 3 месяцев',4=>'От 3 до 6 месяцев',5=>'От 6 до 12 месяцев',6=>'От 1 года до 2-х',7=>'Более 2-х лет') ?>
        <input type="text" name="str-expirience" class="erv__input erv__required" placeholder="Опыт работы *" id="rv-expirience" value="<?=$arExp[$viData['vac']['exp']]?>" disabled>
        <div class="erv__veil" id="rv-expirience-veil"></div>
        <ul class="erv__select-list" id="rv-expirience-list">
        <i class="erv__select-list-icon">OK</i>
            <?php foreach ($arExp as $k => $v): ?>
                <li>
                    <input type="radio" name="expirience" value="<?=$k?>" id="expirience-<?=$k?>" data-name="<?=$v?>" <?=($viData['vac']['exp']==$k ?'checked':'')?>>
                    <label for="expirience-<?=$k?>"><table><td><p><?=$v?></p><td><b></b></table></label>
                </li>  
            <?php endforeach; ?>
        </ul>
    </div>
    <?
    //  Возраст
    ?>
    <div class="erv__label erv__label-tbl" data-focus="Значение 'от' должно быть больше 14 и меньше значения 'до'">
        <div>
            <span class="erv__input-name">Возраст</span>
            <label class="erv__label-age">
                <span class="erv__input-name">от*</span>
                <input type="text" name="age-from" class="erv__input erv__required erv__input-mini" id="rv-age-from" value="<?=$viData['vac']['agefrom']?>">
            </label>
            <label class="erv__label-age">
                <span class="erv__input-name">до</span>
                <input type="text" name="age-to" class="erv__input erv__required erv__input-mini" id="rv-age-to" value="<?=($viData['vac']['ageto']>0?$viData['vac']['ageto']:'')?>">
            </label>    
        </div>
        <?
        //  ПОЛ
        ?>
        <div>
            <span class="erv__input-name">Пол *</span>
            <input type="checkbox" name="mans" class="erv__input erv__required erv__hidden" id="rv-sex-man" value="1" <?=($viData['vac']['isman'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-sex-man">Мужчина</label>   
            <input type="checkbox" name="wonem" class="erv__input erv__required erv__hidden" id="rv-sex-woman" value="1" <?=($viData['vac']['iswoman'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-sex-woman">Женщина</label>
        </div>
    </div>
    <?
    //  Прочее
    ?>
    <div class="erv__label erv__label-tbl">
        <div>
            <input type="checkbox" name="ismed" class="erv__input erv__hidden" id="rv-med-note" value="1" <?=($viData['vac']['ismed'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-med-note">Медкнижка</label>    
        </div>
        <div>
            <input type="checkbox" name="isavto" class="erv__input erv__hidden" id="rv-auto" value="1" <?=($viData['vac']['isavto'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-auto">Автомобиль</label>    
        </div>
        <div>
            <input type="checkbox" name="smart" class="erv__input erv__hidden" id="rv-smart" value="1" <?=($viData['vac']['smart'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-smart">Смартфон</label>    
        </div>
    </div>
    <?
    //  Характеристики
    ?>
    <div class="erv__label">
        <label class='erv__label-half'>
            <input type="text" name="user-attribs[manh]" class="erv__input" placeholder="Рост от (см)" value="<?=$viData['vacAttribs'][9]['val']?>" id="rv-user-height">
        </label>
        <label class='erv__label-half'>
            <input type="text" name="user-attribs[weig]" class="erv__input" placeholder="Вес от (кг)" value="<?=$viData['vacAttribs'][10]['val']?>" id="rv-user-weight">
        </label>
        <div class="clearfix"></div>
    </div>

    <div class="erv__label">
        <label class='erv__label-half erv__select' data-info="Цвет волос">
            <?
                $propName = '';
                foreach ($viData['vacAttribs'] as $prop)
                    if($prop['idpar']==11)
                        $propName = $prop['name'];
            ?>
            <input type="text" name="user-attribs-hcolor" class="erv__input" id="rv-hcolor" placeholder="Цвет волос" value="<?=$propName?>" disabled>        
            <div class="erv__veil" id="rv-hcolor-veil"></div>
            <ul class="erv__select-list" id="rv-hcolor-list">
                <i class="erv__select-list-icon">OK</i>
                <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                    <?php if( $prop['idpar'] == 11 ): ?>
                        <li>
                            <input type="radio" name="user-attribs[hcolor]" value="<?=$prop['id']?>" id="hcolor-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                            <label for="hcolor-<?=$prop['id']?>">
                                <table><td><p><?=$prop['name']?></p><td><b></b></table>
                            </label>           
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </label>
        <label class='erv__label-half erv__select' data-info="Длина волос">
            <?
                $propName = '';
                foreach ($viData['vacAttribs'] as $prop)
                    if($prop['idpar']==12)
                        $propName = $prop['name'];
            ?>
            <input type="text" name="user-attribs-hlen" class="erv__input" id="rv-hlen" placeholder="Длина волос" value="<?=$propName?>" disabled>        
            <div class="erv__veil" id="rv-hlen-veil"></div>
            <ul class="erv__select-list" id="rv-hlen-list">
                <i class="erv__select-list-icon">OK</i>
                <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                    <?php if( $prop['idpar'] == 12 ): ?>
                        <li>
                            <input type="radio" name="user-attribs[hlen]" value="<?=$prop['id']?>" id="hlen-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                            <label for="hlen-<?=$prop['id']?>">
                                <table><td><p><?=$prop['name']?></p><td><b></b></table>
                            </label>           
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </label>
        <div class="clearfix"></div>
    </div>

    <div class="erv__label">
        <label class='erv__label-half erv__select' data-info="Цвет глаз">
            <?
                $propName = '';
                foreach ($viData['vacAttribs'] as $prop)
                    if($prop['idpar']==13)
                        $propName = $prop['name'];
            ?>
            <input type="text" name="user-attribs-ycolor" class="erv__input" id="rv-ycolor" placeholder="Цвет глаз" value="<?=$propName?>" disabled>
            <div class="erv__veil" id="rv-ycolor-veil"></div>
            <ul class="erv__select-list" id="rv-ycolor-list">
                <i class="erv__select-list-icon">OK</i>
                <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                    <?php if( $prop['idpar'] == 13 ): ?>
                        <li>
                            <input type="radio" name="user-attribs[ycolor]" value="<?=$prop['id']?>" id="ycolor-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                            <label for="ycolor-<?=$prop['id']?>">
                                <table><td><p><?=$prop['name']?></p><td><b></b></table>
                            </label>           
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </label>
        <label class='erv__label-half erv__select' data-info="Размер груди">
            <?
                $propName = '';
                foreach ($viData['vacAttribs'] as $prop)
                    if($prop['idpar']==14)
                        $propName = $prop['name'];
            ?>
            <input type="text" name="user-attribs-chest" class="erv__input" id="rv-chest" placeholder="Размер груди" value="<?=$propName?>" disabled>
            <div class="erv__veil" id="rv-chest-veil"></div>
            <ul class="erv__select-list" id="rv-chest-list">
                <i class="erv__select-list-icon">OK</i>
                <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                    <?php if( $prop['idpar'] == 14 ): ?>
                        <li>
                            <input type="radio" name="user-attribs[chest]" value="<?=$prop['id']?>" id="chest-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                            <label for="chest-<?=$prop['id']?>">
                                <table><td><p><?=$prop['name']?></p><td><b></b></table>
                            </label>           
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </label>
        <div class="clearfix"></div>
    </div>

    <div class="erv__label">
        <label class='erv__label-half erv__select' data-info="Объем талии">
            <?
                $propName = '';
                foreach ($viData['vacAttribs'] as $prop)
                    if($prop['idpar']==15)
                        $propName = $prop['name'];
            ?>
            <input type="text" name="user-attribs-waist" class="erv__input" id="rv-waist" placeholder="Объем талии" value="<?=$propName?>" disabled>    
            <div class="erv__veil" id="rv-waist-veil"></div>
            <ul class="erv__select-list" id="rv-waist-list">
                <i class="erv__select-list-icon">OK</i>
                <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                    <?php if( $prop['idpar'] == 15 ): ?>
                        <li>
                            <input type="radio" name="user-attribs[waist]" value="<?=$prop['id']?>" id="waist-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                            <label for="waist-<?=$prop['id']?>">
                                <table><td><p><?=$prop['name']?></p><td><b></b></table>
                            </label>           
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </label>
        <label class='erv__label-half erv__select' data-info="Объем бедер">
            <?
                $propName = '';
                foreach ($viData['vacAttribs'] as $prop)
                    if($prop['idpar']==16)
                        $propName = $prop['name'];
            ?>
            <input type="text" name="user-attribs-thigh" class="erv__input" id="rv-thigh" placeholder="Объем бедер" value="<?=$propName?>" disabled>        
            <div class="erv__veil" id="rv-thigh-veil"></div>
            <ul class="erv__select-list" id="rv-thigh-list">
                <i class="erv__select-list-icon">OK</i>
                <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                    <?php if( $prop['idpar'] == 16 ): ?>
                        <li>
                            <input type="radio" name="user-attribs[thigh]" value="<?=$prop['id']?>" id="thigh-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                            <label for="thigh-<?=$prop['id']?>">
                                <table><td><p><?=$prop['name']?></p><td><b></b></table>
                            </label>           
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </label>
        <div class="clearfix"></div>
    </div>
</div>