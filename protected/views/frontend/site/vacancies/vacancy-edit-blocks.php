<?/*  ADD CITY */?>
<div id="add-city-content">
    <div class="erv-city__item" 
        data-id="new" 
        data-idcity="" 
        data-bdate="" 
        data-edate="">
        <span class="erv-city__close"></span>
        <div class="erv-city__item-veil"></div>
        <div class="erv-city__label erv-city__label-city">
            <span class="erv-city__label-name"><span>Город <i></i>:</span></span>
            <div class="erv-city__label-input">
                <span class="city-select"><b></b></span>
                <input type="text" name="city[name][]" value="" class="erv__input city-input" autocomplete="off">
                <ul class="city-list"></ul>
            </div>
        </div>
        <div class="city-date-block">
            <div class="erv-city__label erv-city__label-ltime">
                <span class="erv-city__label-name"><span>Начало работ:</span></span>
                <div class="erv-city__label-input city-bdate">
                    <span></span>
                    <div class="city-calendar" data-type="bdate">
                        <b>Выбранная дата некорректная</b>
                        <table>
                            <thead>
                                <tr>
                                    <td class="mleft">‹</td>
                                    <td colspan="5" class="mname"></td>
                                    <td class="mright">›</td>
                                </tr>
                                <tr>
                                    <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                                </tr>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="erv-city__label erv-city__label-ltime">
                <span class="erv-city__label-name"><span>Окончание работ:</span></span>
                <div class="erv-city__label-input city-edate">
                    <span></span>
                    <div class="city-calendar" data-type="edate">
                        <b>Выбранная дата некорректная</b>
                        <table>
                            <thead>
                                <tr>
                                    <td class="mleft">‹</td>
                                    <td colspan="5" class="mname"></td>
                                    <td class="mright">›</td>
                                </tr>
                                <tr>
                                    <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                                </tr>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <span class="erv-city__button erv-city__btn">Сохранить город</span>
        </div>
        <span class="erv-city__button add-loc-btn" style="display: none;">Добавить локацию</span>
        <div class="clearfix"></div>
    </div>    
</div>
<?/*  ADD LOCATION */?>
<div id='add-loc-content'>
    <div class='erv-city__location'
        data-idloc='new'
        data-idcity=''
        data-name=''
        data-index=''
        data-bdate=''
        data-edate=''
        data-btime=''
        data-etime=''
        data-metro='null'>
        <span class="erv-city__close"></span>
        <div class="erv-city__item-veil"></div>
        <label class="erv-city__label erv-city__label-lname">
            <span class="erv-city__label-name"><span>Название локации:</span></span>
            <span class="erv-city__label-input">
                <input type="text" name="city[lname][]" value="" class="erv__input locname-input">
            </span>
        </label>
        <label class="erv-city__label erv-city__label-lindex">
            <span class="erv-city__label-name"><span>Адрес локации:</span></span>
            <span class="erv-city__label-input">
                <input type="text" name="city[lindex][]" value="" class="erv__input index-input">
            </span>
        </label>
        <div class="loc-date-block">
            <div class="erv-city__label erv-city__label-ltime">
                <span class="erv-city__label-name"><span>Дата начала работ:</span></span>
                <div class="erv-city__label-input city-bdate">
                    <span></span>
                    <div class="city-calendar" data-type="bdate">
                        <b>Выбранная дата некорректная</b>
                        <table>
                            <thead>
                                <tr>
                                    <td class="mleft">‹</td>
                                    <td colspan="5" class="mname"></td>
                                    <td class="mright">›</td>
                                </tr>
                                <tr>
                                    <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                                </tr>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="erv-city__label erv-city__label-ltime">
                <span class="erv-city__label-name"><span>Дата окончания работ:</span></span>
                <div class="erv-city__label-input city-edate">
                    <span></span>
                    <div class="city-calendar" data-type="edate">
                        <b>Выбранная дата некорректная</b>
                        <table>
                            <thead>
                                <tr>
                                    <td class="mleft">‹</td>
                                    <td colspan="5" class="mname"></td>
                                    <td class="mright">›</td>
                                </tr>
                                <tr>
                                    <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                                </tr>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="erv-city__label erv-city__label-ltime">
                <span class="erv-city__label-name"><span>Время работы:</span></span>
                <div class="erv-city__label-input erv-city__label-period">
                    <input type="text" name="city[btime][]" value="" class="erv__input btime-input">
                    <span>-</span>
                    <input type="text" name="city[etime][]" value="" class="erv__input etime-input">
                    <div class="clearfix"></div>
                </div>
            </div>
            <span class="erv-city__button erv-loc__btn">Сохранить локацию</span>
        </div>
        <span class="erv-city__button add-per-btn" style="display: none">Добавить период</span>
        <div class="clearfix"></div>
    </div>
</div>
<?// PERIOD CONTENT ?>
<div id='period-content'>
    <div class="erv-city__time"
        data-bdate=""
        data-edate=""
        data-btime=""
        data-etime="">
        <span class="erv-city__close"></span>
        <div class="erv-city__item-veil"></div>
        <div class="erv-city__label erv-city__label-ltime">
            <span class="erv-city__label-name"><span>Дата работы:</span></span>
            <span class="erv-city__label-input city-period">
                <table></table>
            </span>
        </div>
    </div>
</div>
<?// ADD PERIOD ?>
<div id='add-period-content'>
    <div class="loc-date-block">
        <div class="erv-city__label erv-city__label-ltime">
            <span class="erv-city__label-name"><span>Дата начала работ:</span></span>
            <div class="erv-city__label-input city-bdate">
                <span></span>
                <div class="city-calendar" data-type="bdate">
                    <b>Выбранная дата некорректная</b>
                    <table>
                        <thead>
                            <tr>
                                <td class="mleft">‹</td>
                                <td colspan="5" class="mname"></td>
                                <td class="mright">›</td>
                            </tr>
                            <tr>
                                <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                            </tr>
                        <tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="erv-city__label erv-city__label-ltime">
            <span class="erv-city__label-name"><span>Дата окончания работ:</span></span>
            <div class="erv-city__label-input city-edate">
                <span></span>
                <div class="city-calendar" data-type="edate">
                    <b>Выбранная дата некорректная</b>
                    <table>
                        <thead>
                            <tr>
                                <td class="mleft">‹</td>
                                <td colspan="5" class="mname"></td>
                                <td class="mright">›</td>
                            </tr>
                            <tr>
                                <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                            </tr>
                        <tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="erv-city__label erv-city__label-ltime">
            <span class="erv-city__label-name"><span>Время работы:</span></span>
            <div class="erv-city__label-input erv-city__label-period">
                <input type="text" name="city[btime][]" value="" class="erv__input btime-input">
                <span>-</span>
                <input type="text" name="city[etime][]" value="" class="erv__input etime-input">
                <div class="clearfix"></div>
            </div>
        </div>
        <span class="erv-city__button rst-per-btn">Отменить</span>
        <span class="erv-city__button save-per-btn">Сохранить период</span>   
        <div class="clearfix"></div>
    </div>
</div>
<?/*  ADD METRO */?>
<div id='add-metro-content'>
    <div class="erv-city__label erv-city__label-lmetro">
        <span class="erv-city__label-name"><span>Метро:</span></span>
        <div class="erv-city__label-input">
            <ul class="ev-metro-select" data-idcity=""><li data-id="0"><input type="text" name="m"></li></ul>
            <ul class="metro-list"></ul>
        </div>
    </div>
</div>