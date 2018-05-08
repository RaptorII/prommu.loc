<?php
    $this->pageTitle = 'Редактирование описания вакансии';
    Yii::app()->getClientScript()->registerScriptFile("/jslib/nicedit/nicEdit.js", CClientScript::POS_BEGIN); 
?>
<input type="hidden" name="block" value="4"/>
<div class="erv__subtitle"><h2>ОПИСАНИЕ ВАКАНСИИ</h2></div>
<div class="erv__module">
    <div class="erv__label erv__label-tbl">
        <div>
            <input type="radio" name="busyType" class="erv__input erv__hidden" id="rv-busy-temp" value="0" <?=(!$viData['vac']['istemp'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-busy-temp">Временная работа</label>
        </div>
        <div>
            <input type="radio" name="busyType" class="erv__input erv__hidden" id="rv-busy-full" value="1" <?=($viData['vac']['istemp'] ? 'checked' : '')?>>
            <label class="erv__label-checkbox" for="rv-busy-full">Постоянная работа</label>
        </div>
    </div>
    <label class="erv__label erv__label-textarea">
        <div id="rv-requirements-panel">
            <span class="erv__input-name">Описание вакансии (требования) *</span>
        </div>
        <textarea name="requirements" class="erv__input" id="rv-requirements"><?=$viData['vac']['requirements']?></textarea>
    </label>
    <label class="erv__label erv__label-textarea"> 
        <div id="rv-duties-panel">
            <span class="erv__input-name">Обязанности</span>
        </div>
        <textarea name="duties" class="erv__input" id="rv-duties"><?=$viData['vac']['duties']?></textarea>
    </label>
    <label class="erv__label erv__label-textarea">
        <div id="rv-conditions-panel">
            <span class="erv__input-name">Условия</span>
        </div>
        <textarea name="conditions" class="erv__input" id="rv-conditions"><?=$viData['vac']['conditions']?></textarea>
    </label>
</div>