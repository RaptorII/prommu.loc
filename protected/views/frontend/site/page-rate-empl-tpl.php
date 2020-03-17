<?
$userId = Share::$UserProfile->id;
$arUser = Share::getUsers(array($userId));
$arUser = $arUser[$userId];
?>

<meta name="robots" content="noindex">
<script src="https://www.google.com/jsapi"></script>

<style>
    .user__info {
        display: flex;
    }

    .user__info-part2 {
        width: 100%;
        padding-left: 30px;
    }

    .user__rating .upp__table {
        width: 100%;
    }

    .user__rating .user__rating-line {
        margin: 15px 0 20px;
        width: 100%;
        border-top: 1px solid #D6D6D6;
    }

    .user__rating .upp__table-cnt {
        width: 30px;
        text-align: right;
    }

    .user__info-logo {
        border-radius: 50%;
        border: 2px solid #CBD880;
        width: 176px;
        position: relative;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .user__info-title:before {
        content: '';
        display: inline-block;
        width: 27px;
        height: 27px;
        background: url(/theme/pic/private/vac-list-user-icon.png) no-repeat;
        vertical-align: middle;
        margin-right: 5px;
    }

    .user__rating .upp__table td {
        vertical-align: top;
    }

    .user__rating .upp__table-name {
        position: relative;
        padding-bottom: 20px;
    }

    .user__rating .upp__table-name:after {
        content: '';
        display: block;
        height: 15px;
        position: absolute;
        top: 0;
        right: 0;
        left: 25px;
        border-bottom: 1px dotted #343434;
        z-index: 0;
    }

    .user__rating .upp__table-name span {
        font-size: 15px;
        padding: 0 3px 20px;
        background-color: #FFFFFF;
        position: relative;
        z-index: 1;
    }

    .user__rating .upp__table-cnt-plus {
        background-color: #ABB837;
    }

    .user__rating .upp__table-cnt-zero {
        background-color: #BCBCBC;
    }

    .user__rating .upp__table-cnt-minus {
        background-color: #ED2036;
    }

    .user__rating .upp__table-cnt-plus, .user__rating .upp__table-cnt-zero, .user__rating .upp__table-cnt-minus {
        width: 22px;
        height: 22px;
        position: relative;
        display: inline-block;
        text-align: center;
        line-height: 22px;
        color: #FFFFFF;
        border-radius: 50%;
        font-weight: bold;
        cursor: default;
    }

    .user__rating .user__reviews-item {
        padding: 15px 12px;
        margin-bottom: 10px;
    }

    .user__rating .good{
        border: 5px solid #ABB837;
    }
    .user__rating .bad{
        border: 5px solid #ED2036;
    }

    .user__rating .user__reviews-text{
        margin-top:10px;
    }

    #DiContent.page-rate h2{
        text-align: left;
    }
    /*
    *
    */
    .page-rate_star{
        width: 15px;
        height: 15px;
        display: inline-block;
        background: url(/theme/pic/reviews-page/reviews_sprite.png) 0 -16px no-repeat;
        background-position: 0 0;
    }
</style>


<?php if( $viData['error'] ): ?>
  <div class="comm-mess-box"><?= $viData['message'] ?></div>
<?php else: ?>
  <div class='row'>
    <div class='col-xs-12'>
        <?php if( $IS_OWN ): ?>
        <?php else: ?>
            <h2>Рейтинг пользователя <?= $Profile->exInfo->name ?></h2>
        <?php endif; ?>
        <div class="user__rating">
            <div class="user__info">
                <div class="user__info-part1">
                    <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>" class="user__info-logo"/>
                </div>
                <div class="user__info-part2">
                    <h2 class="user__info-title"><?=$arUser['name']?></h2>

                    <div class="">
                        <span>ОБЩИЙ РЕЙТИНГ </span>
                        <span class="page-rate_star"></span>
                        <?=Share::getRating($viData['main_rating']['rate'],$viData['main_rating']['rate_neg'])?>
                    </div>
                    <hr class="user__rating-line"/>

                    <p>Рейтинг Работодателя показывает его порядочность и отношение к работникам, которых Работодатель набирал на свои проекты. Рейтинг выставляется Соискателем после завершения работы по вакансии, и вычисляется по всем вакансиям, на которые Работодатель набирал персонал</p>

                </div>

            </div>


            <h2>Рейтинг:</h2>
            <div class="user__diagram" id="rating_r" style="width: 100%; height: 400px;">



            </div>

            <?php if( $IS_OWN && $viData['rateByUser'] ): ?>
                <?/*<div class='header-021 -green'>
            Рейтинг выставленный соискателями
          </div>*/?>
                <h2>Рейтинг выставленный соискателями</h2>
                <br />
                <div class='row'>
                  <?php foreach ($viData['rateByUser'] as $key => $val): ?>
                    <?php
                    $debug && ($debug++);
                    !$debug && $debug = 1;
                    $arItem = reset($val);
                    ?>
                    <div class="rate-wrapper col-xs-12 col-sm-6 col-lg-4">
                      <div
                        class="rate-block clearfix <?= $arItem['new'] < 0 ? '-new' : '' ?> <?= in_array($debug, [1, 2]) ? '-new' : '' ?>">
                        <div class="inner">
                          <?php if ($arItem['new'] < 0 || in_array($debug, [1, 2])): ?>
                            <div class="new-labl">Новый</div>
                          <?php endif; ?>
                          <div class="logo">
                            <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $arItem['idus'] ?>">
                              <img
                                src="<?=Share::getPhoto($arItem['idus'],UserProfile::$APPLICANT,$arItem['photo'],'small',$arItem['isman'])?>"
                                alt="<?=$arItem['fio']?>">
                            </a>
                          </div>
                          <div class="company">
                            <div class="fio">
                              <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $arItem['idus'] ?>"
                                 class="black-green">
                                <?= $arItem['fio'] ?>
                              </a>
                            </div>
                            <div class="rates">
                              <?php foreach ($val as $key2 => $val2): ?>
                                <div class="point <?= "p" . $val2['point'] ?> js-g-hashint -js-g-hintleft"
                                     title="Оценка <?= (int)$val2['point'] === 1 ? 'положительная' : ((int)$val2['point'] === 0 ? 'нейтральная' : 'отрицательная') ?>"><?= $viData['rating']['rateNames'][$key2] ?></div>
                                <br/>
                              <?php endforeach; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="user__reviews">
                <h2>Отзывы:</h2>
                <div class="user__reviews-comments">
                    <div class="user__reviews-item good">
                        <div class="user__reviews-name"><a href="/ankety/7000">Дмитрий Деревянко 100</a> 14/01/2019</div>
                        <div class="user__reviews-text">
                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean
                            massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec
                            quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                            Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut,
                            imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.
                            Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula,
                            porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis,
                            feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean
                            imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam
                            rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet
                            adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem.
                            Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam
                            quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet
                            nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus
                            nunc,
                        </div>
                    </div>

                    <div class="user__reviews-item bad">
                        <div class="user__reviews-name"><a href="/ankety/7000">Дмитрий Деревянко 100</a> 14/01/2019</div>
                        <div class="user__reviews-text">
                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean
                            massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec
                            quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim.
                            Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut,
                            imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.
                            Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula,
                            porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis,
                            feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean
                            imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam
                            rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet
                            adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem.
                            Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam
                            quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet
                            nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus
                            nunc,
                        </div>
                    </div>

                </div>


            </div>




        </div>


      <?/*<div class='header-021 -green'>
        Общий рейтинг
        <span class='star'></span>
        <?= $viData['rating']['countRate'] ?>
      </div>
      <br />
      <p>Рейтинг Работодателя показывает его порядочность и отношение к работникам, которых Работодатель набирал на свои проекты. Рейтинг выставляется Соискателем после завершения работы по вакансии, и вычисляется по всем вакансиям, на которые Работодатель набирал персонал</p>
      <br />*/?>
      <div class='row'>
        <div class='col-xs-12'>

            <?
                $ratingJson = [];
                $ratingCount = 0;
                $ratingJson[$ratingCount] = ['Название', 'хорошо', 'плохо'];
            ?>

          <?/*<table class='rate'>
            <?php foreach ($viData['rating']['pointRate'] as $key => $val): ?>
              <tr>
                <td class='val'>
                  <?= $val[0] - $val[1] ?> (
                  <span class='good' title='отлично'><?= $val[0] ?></span>
                  /
                  <span class='bad' title='плохо'><?= $val[1] ?></span>
                  )
                </td>
                <td class='progress'>
                  <div class='progr-line <?= $val[0] > $val[1] ? 'progress-green' : 'progress-red' ?>' style="width: <?= $val[0] - $val[1] == 0 ? 0 : abs($val[0] - $val[1]) * 100 / $viData['rating']['maxPointRate'] ?>%;">&nbsp;</div>
                  <div class='text'><?= $viData['rating']['rateNames'][$key] ?></div>

                    <?
                        if($viData['rating']['rateNames'][$key]):
                            $ratingCount++;
                            $ratingJson[$ratingCount]['0'] = $viData['rating']['rateNames'][$key];
                            $ratingJson[$ratingCount]['1'] = $val[0];
                            $ratingJson[$ratingCount]['2'] = $val[1];
                        endif;
                    ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>*/?>

            <?php foreach ($viData['rating']['pointRate'] as $key => $val): ?>
                <?
                if($viData['rating']['rateNames'][$key]):
                    $ratingCount++;
                    $ratingJson[$ratingCount]['0'] = $viData['rating']['rateNames'][$key];
                    $ratingJson[$ratingCount]['1'] = $val[0];
                    $ratingJson[$ratingCount]['2'] = $val[1];
                endif;
                ?>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?
if(count($ratingJson)>1):
    $ratingJson = json_encode($ratingJson);
else:
    $ratingJson = [];
    $ratingJson[0] = ['Название', 'хорошо', 'плохо'];
    $ratingJson[1] = ['Нет данных',0,0];
    $ratingJson = json_encode($ratingJson);
endif;
?>
<script>

    var arArray = [];
    arArray = <?=$ratingJson?>;
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Год', 'Рейтинг'],
            ['2019-03-20', 9,1],
            ['2019-03-23', 1,4],
        ]
);

        var options = {
            title: '',
            hAxis: {title: 'Название'},
            colors: ['#ABB837','red'],
            vAxis: {title: '%'}
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('rating_r'));
        chart.draw(data, options);
    }
</script>