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
</style>

<div class="user__rating">
    <div class="user__info">


        <div class="user__info-part1">
            <img src="/images/applic/20180416165041951400.jpg" alt="" class="user__info-logo"/>
        </div>
        <div class="user__info-part2">
            <h2 class="user__info-title">Dmitry Derevyanko</h2>

            <div class="">

                <span>ОБЩИЙ РЕЙТИНГ  </span><b>31.7</b> ИЗ 100 БАЛЛОВ

            </div>
            <hr class="user__rating-line"/>
            <table class="upp__table">
                <tbody>
                <tr>
                    <td class="upp__table-name">
                        <span>Качество выполненной работы</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-plus js-g-hashint tooltipstered">1</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-zero js-g-hashint tooltipstered">0</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-minus js-g-hashint tooltipstered">0</span>
                    </td>
                </tr>
                <tr>
                    <td class="upp__table-name">
                        <span>Контактность</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-plus js-g-hashint tooltipstered">0</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-zero js-g-hashint tooltipstered">0</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-minus js-g-hashint tooltipstered">0</span>
                    </td>
                </tr>
                <tr>
                    <td class="upp__table-name">
                        <span>Пунктуальность</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-plus js-g-hashint tooltipstered">0</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-zero js-g-hashint tooltipstered">0</span>
                    </td>
                    <td class="upp__table-cnt">
                        <span class="upp__table-cnt-minus js-g-hashint tooltipstered">1</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>


    <h2>Рейтинг:</h2>
    <div class="user__diagram" id="oil" style="width: 100%; height: 400px;">



    </div>

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
<?
if(count($ratingJson)>1):
    $ratingJson = json_encode($ratingJson);
else:
    $ratingJson = [];
    $ratingJson[0] = ['Дата', 'хорошо', 'плохо'];
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
        var data = google.visualization.arrayToDataTable(<?=$ratingJson?>);
        
       
        var options = {
            title: '',
            hAxis: {title: 'Дата'},
            colors: ['#ABB837','red'],
            vAxis: {title: '%'}
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('oil'));
        chart.draw(data, options);
    }
</script>