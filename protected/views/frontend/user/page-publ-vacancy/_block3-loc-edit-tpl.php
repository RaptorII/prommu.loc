<?php if( IS_TMPL ): ?>
    <div class="location-edit-tpl tmpl row">
<?php else: ?>
    <div class="location-edit row">
<?php endif; ?>
        <div class="message ph-message"></div>
        <form method="post" class="block-form-bind">
            <div class="btn-close btn-close-bind"><a href="#" title="Удалить локацию" tabindex="-1"></a></div>
            <div class='col-xs-12 col-sm-4'>
                <div class="field">
                  <label class="block">
                    <b>Название локации</b>
                    <input class='ph-locname' name="name" type='text' data-field-check='name:Название локации,empty'>

                    <input type="hidden" name="idloc" class="ph-idloc" />
                    <input type="hidden" name="idcity" class="ph-idcity" />
                  </label>
                </div>
            </div>
            <div class='col-xs-12 col-sm-4'>
                <div class="field">
                  <label class="block">
                    <b>Адрес локации</b>
                    <input class='ph-locaddr' type='text' name="addr" data-field-check='name:Адрес локации,empty'>
                  </label>
                </div>
            </div>
            <div class='col-xs-12 col-sm-4'>
                <div class="field">
                    <div class='block label-box ph-metro-block clearfix'>
                        <b>Метро </b>
                        <select name='metro[]' data-defitm="- метро -">
                            <?php if( is_array($viData['metro'][$locdata['idcity']]) ): ?>
                                <?php foreach ($viData['metro'][$locdata['idcity']] as $key3 => $val3): ?>
                                    <?php if( $locdata[3][0] == $key3 ): ?>
                                        <?= $val3['name'] ?>
                                        <?php break ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class='col-xs-12'>
                <div class='periods'>
                    <b>Периоды работы на локации</b>
                    <div class="ph-periods"></div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="btn-white-orange-wr btn-save-location-bind">
                <button type="submit" class="save">Сохранить</button>
                <button type="button" class="cancel">отменить</button>
            </div>
        </form>
    </div>

<?php if( $IS_TMPL ): ?>
<div class="period-line-edit-tpl tmpl">
    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <label class="block">
                <b>Начало периода</b>
                <input class="ph-bdate" name="bdate[]" type='text' data-field-check='name:Начало периода,empty'>
            </label>
        </div>
        <div class="col-xs-12 col-sm-3">
            <label class="block">
                <b>Окончание периода</b>
                <input class="ph-edate" name="edate[]" type='text' data-field-check='name:Начало периода,empty' >
            </label>
        </div>
        <div class="col-xs-12 col-sm-2">
            <label class="block">
                <b>время начала</b>
                <input name="btime[]" title="время начала работы" data-field-filter='digits:\:;max:5' class="ph-btime time has-hint" type='text' data-field-check='name:Время начала работы,empty' placeholder="8:00">
            </label>
        </div>
        <div class="col-xs-12 col-sm-2">
            <label class="block" >
                <b>время окон.</b>
                <input name="etime[]" title="время окончания работы" data-field-filter='digits:\:;max:5' class="ph-etime time has-hint" type='text' data-field-check='name:Время окончания работы,empty' placeholder="17:00">
            </label>
        </div>
        <div class="col-xs-12 col-sm-2">
            <label class="block edit-period-btns">
                <b></b><br />
                <a href="#" class="add add-per-bind has-hint" title="добавить период"></a>
                <a href="#" class="del del-per-bind has-hint" title="удалить период"></a>
            </label>
        </div>
    </div>
</div>
<?php endif; ?>