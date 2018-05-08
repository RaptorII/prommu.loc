<?php if( $IS_TMPL ): ?>
    <div class="location-wrapper-tpl tmpl" data-id="">
<?php else: ?>
    <div class="location-wrapper" data-id="<?= $locdata['id'] ?>" data-idcity="<?= $locdata['idcity'] ?>">
<?php endif; ?>
        <div class="blind"></div>
        <div class="error-edit-bind error-message">Неудалось загрузить информацию для редактирования, &nbsp;<a href="#">обновить блок</a></div>
        <div class="error-save-bind error-message">Неудалось сохранить информацию, &nbsp;<a href="#">обновить блок</a></div>
        <div class="error-save-new-bind error-message">Неудалось сохранить информацию, обновите страницу (F5)</a></div>
        <div class="error-refresh-bind error-message">Неудалось загрузить информацию блока, обновите страницу (F5)</div>
        <div class="error-del-bind error-message">Неудалось удалить локацию, &nbsp;<a href="#">обновить блок</a></div>
        <div class="ph-info-block">
            <?php $IS_TMPL || $this->renderPartial('page-publ-vacancy/' . MainConfig::$VIEWS_PUBVAC_BLOCK3_LOCATION_VIEW_TPL, array('viData' => $viData, 'idcity' => $idcity, 'locdata' => $locdata)); ?>
        </div>
    </div>