<input type="hidden" name="block" value="6"/>

<div class="header"><h2 class="s16"><span>Контактная инфо</span></h2> </div>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-push-2">
        <div class="field">
            <label for="McontactInfo"><b>Контактная информация</b></label>
            <textarea name="ContactInfo" id="McontactInfo"><?= $viData['vac']['contacts'] ?></textarea><br /><br />
        </div>

        <div class="field">
            <label for="ChkShowContacts" class="checkbox-box -left <?= $viData['vac']['iscontshow'] ? 'checked' : '' ?>">
                <b>Публиковать контактные данные (номер телефона и эл почта работодателя, которые были указаны при регистрации)</b>
                <input type="checkbox" name="isShowContacts" id="ChkShowContacts" <?= $viData['vac']['iscontshow'] ? 'checked' : '' ?>/>
                <span></span>
            </label>
        </div>
    </div>
</div>