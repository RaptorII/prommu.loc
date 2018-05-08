<?php if( $IS_TMPL ): ?>
    <div class="location-view-tpl tmpl row">
<?php else: ?>
    <div class="location-view row">
<?php endif; ?>
        <div class='btn-edit-block ph-editbtn control-btn <?= !$IS_TMPL ?: 'tmpl' ?>'>
            <a href='<?= MainConfig::$PAGE_VACANCY_EDIT . DS . $viData['vac']['id'] ?>?bl=6'
               title="Редактировать точку &laquo;<?= $locdata['name'] ?: '#PH_LOCNAME_NAME#' ?>&raquo;"><i></i></a>
        </div>
        <div class='col-xs-12 col-sm-6'>
            <div class="field">
              <label class="block">
                <b>Название локации</b>
                <span class="ph-locname"><?= $locdata['name'] ?></span>
              </label>
            </div>
            <div class="field">
              <label class="block">
                <b>Адрес локации</b>
                <span class="ph-locaddr"><?= $locdata['addr'] ?></span>
              </label>
            </div>

            <?php if( $IS_TMPL || $locdata['metro'][1] ): ?>
                <div class="field ph-mblock">
                    <div class='block label-box clearfix'>
                        <b>Метро </b>
                        <span class="ph-metro"><?= $locdata['metro'][1] ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php if( $IS_TMPL || $viData['vac']['loctime'][$locdata['id']] ): ?>
            <div class='col-xs-12 col-sm-6'>
                <div class='periods periods-wrapp-bind'>
                    <b>Периоды работы на локации</b>
                    <div class="ph-periods">
                        <?php foreach ( $IS_TMPL ? array() : $viData['vac']['loctime'][$locdata['id']] as $val3): ?>
                            <div>
                                <b class="ph-bdate"><?= $val3[0] ?></b> - <b class="ph-edate"><?= $val3[1] ?></b>
                                с <b class="ph-btime"><?= $val3[2] ?></b> по <b class="ph-etime"><?= $val3[3] ?></b>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php if( $IS_TMPL ): ?>
<div class="period-line-tpl tmpl">
    <b class="ph-bdate"></b> - <b class="ph-edate"></b>
    с <b class="ph-btime"></b> по <b class="ph-etime"></b>
</div>
<?php endif; ?>
