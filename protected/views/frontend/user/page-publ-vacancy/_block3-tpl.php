<?php
    $this->pageTitle = 'Редактирование адреса, места и времени работы вакансии';
    //
    $Q1 = Yii::app()->db->createCommand()
      ->select('m.id, m.id_city, m.name')
      ->from('metro m')
      ->limit(10000);
    $arMetroes = $Q1->queryAll();
    // оптимизируем массив метро для JS
    $arTemp = array();
    foreach ($arMetroes as $m){
      $arTemp[$m['id_city']][$m['id']] = $m['name'];
    }
    $arMetroes = $arTemp;
?>
<script type="text/javascript">var arMetroes = <?=json_encode($arMetroes);?></script>
<input type="hidden" name="block" value="3">
<div class="erv__subtitle"><h2>АДРЕС, МЕСТО И ВРЕМЯ РАБОТЫ</h2></div>
<div class="erv__module" id="city-module" data-co="<?=$viData['vac']['id_co']?>">
    <?$count=1?>
    <?php foreach ($viData['vac']['city'] as $city): ?>
        <div class="erv-city__item" 
            data-id="<?=$city[3]?>" 
            data-idcity="<?=$city[4]?>"
            data-bdate="<?=$city[1]?>"
            data-edate="<?=$city[2]?>"
            >   
            <div class="erv-city__calendar"></div>
            <span class="erv-city__close"></span>
            <div class="erv-city__item-veil"></div>
            <div class="erv-city__label erv-city__label-city">
                <span class="erv-city__label-name"><span>Город <i><?=$count?></i>:</span></span>
                <div class="erv-city__label-input">
                    <span class="city-select"><?=$city[0]?><b></b></span>
                    <input type="text" name="city[name][]" value="<?=$city[0]?>" class="erv__input city-input" autocomplete="off">
                    <ul class="city-list"></ul>
                </div>
            </div> 
            <?php if(isset($viData['vac']['location'][$city[3]])): ?>
                <?php foreach ($viData['vac']['location'][$city[3]] as $loc): ?>
                    <div class="erv-city__location"
                        data-idloc="<?=$loc['id']?>"
                        data-idcity="<?=$loc['idcity']?>"
                        data-name="<?=$loc['name']?>"
                        data-index="<?=$loc['addr']?>"
                        data-metro="<?=(is_array($loc['metro']) ? implode(',', array_keys($loc['metro'])) : 'null')?>"
                        >
                        <span class="erv-city__close"></span>
                        <div class="erv-city__item-veil"></div>
                        <label class="erv-city__label erv-city__label-lname">
                            <span class="erv-city__label-name"><span>Название локации:</span></span>
                            <span class="erv-city__label-input">
                                <input type="text" name="city[lname][]" value="<?=$loc['name']?>" class="erv__input locname-input" placholder="Название локации">
                            </span>
                        </label>
                        <label class="erv-city__label erv-city__label-lindex">
                            <span class="erv-city__label-name"><span>Адрес локации:</span></span>
                            <span class="erv-city__label-input">
                                <input type="text" name="city[lindex][]" value="<?=$loc['addr']?>" class="erv__input index-input" placholder="Адрес локации">
                            </span>
                        </label>
                        <?php if(is_array($loc['metro'])): ?>
                            <div class="erv-city__label erv-city__label-lmetro">
                                <span class="erv-city__label-name"><span>Метро:</span></span>
                                <span class="erv-city__label-input">
                                    <ul class="ev-metro-select" data-idcity="<?=$loc['idcity']?>">
                                        <?php foreach ($loc['metro'] as $id => $name): ?>
                                            <li data-id="<?=$id?>"><?=$name?><b></b><input type="hidden" name="city[metro][]" value="<?=$id?>"></li>
                                        <?php endforeach; ?>
                                        <li data-id="0"><input type="text" name="m"></li>
                                    </ul>
                                    <ul class="metro-list"></ul>
                                </span>
                            </div>    
                        <?php endif; ?>
                        <?php if(isset($viData['vac']['loctime'][$loc['id']])): ?>
                            <? $day = 60*60*24; ?>
                            <?php foreach ($viData['vac']['loctime'][$loc['id']] as $time): ?>
                                <div class="erv-city__time"
                                    data-bdate="<?=$time[0]?>"
                                    data-edate="<?=$time[1]?>"
                                    data-btime="<?=$time[2]?>"
                                    data-etime="<?=$time[3]?>">
                                    <span class="erv-city__close"></span>
                                    <div class="erv-city__item-veil"></div>
                                    <div class="erv-city__label erv-city__label-ltime">
                                        <span class="erv-city__label-name"><span>Дата работы:</span></span>
                                        <span class="erv-city__label-input city-period">
                                            <table>
                                                <? 
                                                    if(strtotime($time[0])!=strtotime($time[1]))
                                                        echo 'c ' . date('d.m.y', strtotime($time[0])) . ' по ' . date('d.m.y', strtotime($time[1])) . ' ' . $time[2] . '-' . $time[3]; 
                                                    else
                                                        echo date('d.m.y', strtotime($time[0])) . ' ' . $time[2] . '-' . $time[3]; 
                                                ?>
                                                <?/*
                                                    $temp = strtotime($time[0]) - $day;
                                                    do{ $temp += $day;
                                                ?>  
                                                    <tr>
                                                        <td><?=date("d.m.Y", $temp)?></td>
                                                        <td><?=$time[2]?></td>
                                                        <td>-</td>
                                                        <td><?=$time[3]?></td>
                                                    </tr>
                                                <?php } while ($temp != strtotime($time[1])); */?>
                                            </table>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <span class="erv-city__button add-per-btn">Добавить период</span>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?> 
            <span class="erv-city__button add-loc-btn">Добавить локацию</span>
            <div class="clearfix"></div>  
        </div>
        <?$count++?>
    <?php endforeach; ?>
    <span class="erv-city__button add-city-btn">Добавить город</span>
    <div class="clearfix"></div>
</div>
<? // блоки для управления локациями
    require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/vacancies/vacancy-edit-blocks.php';
?>