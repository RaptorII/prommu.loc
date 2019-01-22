<?php if( $viData['error'] ): ?>
    <div class='wrapper'>
        <div class='row'>
            <div class='col-xs-12 col-sm-4 col-lg-3'>
                <br />
                <?= $viData['message'] ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php 
        if($viData['block'])
            $html = $this->renderPartial('page-publ-vacancy/_block' . $viData['block'] . '-tpl', array('viData' => $viData), true);
        elseif(!$viData['block'])
            $this->renderPartial('page-publ-vacancy/_block-publ-tpl', array('viData' => $viData, 'FLAG_PUB_VAC' => true));
    ?>
    <?php if($viData['block']): ?>
        <?
            $bUrl = Yii::app()->baseUrl;
            Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/vacedit/blocks.css');
            Yii::app()->getClientScript()->registerScriptFile($bUrl.'/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
            Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/vacedit/blocks.js', CClientScript::POS_END);
            $name = Share::$UserProfile->exInfo->name;
        ?>
        <div class='row'>
        <div class='employer-reg-vacansy'>
            <form method="post" id="F1save">
                <div class="col-xs-12">
                    <div class="erv__header erv__header-backtovac">
                        <a class='erv__header-btn prmu-btn' href='<?=MainConfig::$PAGE_VACANCY . DS . $viData['idvac']?>'><span>Назад к общей странице вакансии</span></a>  
                    </div>
                </div>
                <div class='col-xs-12 col-sm-2 col-md-3'></div>
                <div class='col-xs-12 col-sm-10 col-md-9 reg-block-<?=$viData["block"]?>'>
                    <?= $html ?>
                    <div class="erv__button-block">
                        <? if($viData['block']!=3): ?> 
                            <button class="erv__button-block-btn erv__button prmu-btn" type="submit" id="BtnSubmit"><span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span></button>
                        <? else: ?>
                            <a href="<?=MainConfig::$PAGE_VACANCY . DS . $viData['idvac']?>" class="erv__button-block-btn erv__button prmu-btn"><span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span></a>
                        <? endif; ?>
                    </div>
                    <input type="hidden" name="save" value="1"/>
                </div>
            </form>
        </div>
        </div>
    <?php endif; ?>
<?php endif; ?>