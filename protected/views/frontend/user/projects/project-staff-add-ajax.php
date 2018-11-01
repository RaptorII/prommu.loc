<h2 class="project__title personal__title">ДОБАВИТЬ НОВЫЙ ПЕРСОНАЛ НА ПРОЕКТ </h2>
<div class='row'>
    <? //       FILTER      ?>

    <div class='col-xs-12 col-sm-4 col-md-3'>
        <div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
        <div id="promo-filter">
            <div class='filter'>
                <div class='filter__item filter-cities'>
                    <div class='filter__item-name opened'>Город</div>
                    <div class='filter__item-content opened'>
                        <div class="fav__select-cities" id="filter-city">
                            <ul class="filter-city-select">
                                <li data-id="0">
                                    <input type="text" name="fc" class="city-inp" autocomplete="off">
                                </li>
                            </ul>
                            <ul class="select-list"></ul>
                        </div>
                    </div>
                </div>
                <div class='filter__item filter-posts'>
                    <div class='filter__item-name opened'>Должность</div>
                    <div class='filter__item-content opened'>
                        <div class='right-box'>
                            <?php
                            $sel = 0;
                            foreach ($viData['workers']['posts'] as $p)
                                if ($p['selected']) $sel++;
                            ?>
                            <input name='posts-all' type='checkbox' id="f-all-posts"
                                   class="filter__chbox-inp"<?= sizeof($viData['workers']['posts']) == $sel ? ' checked' : '' ?>>
                            <label class='filter__chbox-lab' for="f-all-posts">Выбрать все / снять все</label>
                            <?php foreach ($viData['workers']['posts'] as $p): ?>
                                <input name='posts[]' value="<?= $p['id'] ?>" type='checkbox'
                                       id="f-post-<?= $p['id'] ?>"
                                       class="filter__chbox-inp" <?= $p['selected'] ? 'checked' : '' ?>>
                                <label class='filter__chbox-lab'
                                       for="f-post-<?= $p['id'] ?>"><?= $p['name'] ?></label>
                            <?php endforeach; ?>
                        </div>
                        <span class="more-posts">Показать все</span>
                    </div>
                </div>
                <div class='filter__item filter-age'>
                    <div class='filter__item-name opened'>Возраст</div>
                    <div class='filter__item-content opened'>
                        <div class="filter__age">
                            <label>
                                <span>от</span>
                                <input name=af type='text' value="<?= $_POST['af'] ?>">
                            </label>
                            <label>
                                <span>до</span>
                                <input name='at' type='text' value="<?= $_POST['at'] ?>">
                            </label>
                            <div class="filter__age-btn">ОК</div>
                        </div>
                    </div>
                </div>
                <div class='filter__item filter-sex'>
                    <div class='filter__item-name opened'>Пол</div>
                    <div class='filter__item-content opened'>
                        <div class='right-box'>
                            <input name='sm' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-male"<?= ($_POST['sm'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-male">Мужской</label>
                            <input name='sf' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-female"<?= ($_POST['sf'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-female">Женский</label>
                        </div>
                    </div>
                </div>
                <div class='filter__item filter-additional'>
                    <div class='filter__item-name opened'>Дополнительно</div>
                    <div class='filter__item-content opened'>
                        <div class='right-box'>
                            <input name='mb' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-med"<?= ($_POST['mb'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-med">Наличие медкнижки</label>
                            <input name='avto' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-auto"<?= ($_POST['avto'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-auto">Наличие автомобиля</label>
                            <input name='smart' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-smart"<?= ($_POST['smart'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-smart">Наличие смартфона</label>
                            <input name='cardPrommu' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-pcard"<?= ($_POST['cardPrommu'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-pcard">Банковская карта Prommu</label>
                            <input name='card' type='checkbox' value='1' class="filter__chbox-inp"
                                   id="f-card"<?= ($_POST['card'] ? ' checked' : '') ?>>
                            <label class="filter__chbox-lab" for="f-card">Банковская карта</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php //    CONTENT         ?>
    <div class='col-xs-12 col-sm-8 col-md-9'>
        <div id="workers-form">
            <span class="workers-form__cnt">Выбрано соискателей: <span id="mess-wcount">0</span></span>
            <div class="service__switch">
                <span class="service__switch-name">Выбрать всех</span>
                <input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
                <label for="all-workers">
                    <span data-enable="вкл." data-disable="выкл."></span>
                </label>
            </div>
            <span class="workers-form-btn off" id="workers-btn">СОХРАНИТЬ</span>
            <input type="hidden" name="users" id="mess-workers">
            <input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
        </div>
        <div id="promo-content">
            <?php require 'ankety-ajax.php'; ?>
        </div>
    </div>
</div>