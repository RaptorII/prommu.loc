<script src="https://www.google.com/jsapi"></script>
<?
$userId = Share::$UserProfile->id;
$arUser = Share::getUsers(array($userId));
$arUser = $arUser[$userId];
?>
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
    


    <h2>Рейтинг:</h2>
   <div class="user__diagram" id="oil" style="width: 100%; height: 400px;">
</div>
<?
var_dump($viData);
                $ratingJson = [];
                $ratingCount = 0;
                $ratingJson[$ratingCount] = ['Дата', 'хорошо', 'плохо'];
            ?>
<?php foreach ($viData['rating']['pointRate'] as $key => $val): ?>
                <?
               
                    $ratingCount++;
                    $ratingJson[$ratingCount]['0'] = $viData['rating']['pointRate'][$key];
                    $ratingJson[$ratingCount]['1'] = $val[0];
                    $ratingJson[$ratingCount]['2'] = $val[1];
                
                ?>
            <?php endforeach; ?>
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
        var data = google.visualization.arrayToDataTable([
            ['Год', 'Рейтинг'],
            ['2019-03-20', 9,1],
            ['2019-03-23', 1,4]]
);
        
       
        var options = {
            title: '',
            hAxis: {title: 'Дата'},
            colors: ['#ABB837','red'],
            vAxis: {title: 'Dynamic Rate'}
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('oil'));
        chart.draw(data, options);
    }
</script>