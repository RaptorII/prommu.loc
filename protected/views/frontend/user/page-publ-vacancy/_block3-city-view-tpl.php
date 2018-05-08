<?php if( $IS_TMPL ): ?>
    <div class="row city-info-block-tpl tmpl">
<?php else: ?>
    <div class="row">
<?php endif; ?>
        <div class="col-xs-12">
            <div class='btn-edit-block btn-editcity-bind control-btn ph-editbtn <?= $IS_TMPL ? 'tmpl' : '' ?>' data-id="<?= $id ?>">
                <a href="#" title="Редактировать информацию города &laquo;<?= $cidata[0] ?: '#PH_CITY_NAME#' ?>&raquo;"><i></i></a>
            </div>
            <div class="err-msg-block ph-message -nblock"></div>
            <div class="row">
                <div class='col-xs-12 col-sm-6'>
                  <div class='field'>
                      <label class="block">
                        <b>Город</b>
                        <span class="ph-city"><?= $cidata[0] ?></span>
                      </label>
                  </div>
                </div>

                <div class='col-xs-12 col-sm-6'>
                    <div class="field">
                        <label class='block date-start-block'>
                            <b>Дата начала работ</b>
                            <span class="ph-bdate"><?= $cidata[1] ?></span>
                        </label>
                    </div>
                    <div class="field">
                        <label class='block date-end-block'>
                            <b>Дата окончания работ</b>
                            <span class="ph-edate"><?= $cidata[2] ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

