<div class="api__title"><hr><h2><span>Команды</span></h2></div>
<?php foreach ($viData['data'] as $key => $val): ?>
    <?php 
        $name = strtoupper($val['name']);
        $example = strip_tags($val['retExamples']);
        $example = str_replace('Пример:', '', $example);
        $example = str_replace('развернуть', '', $example);
        $example = trim($example);
    ?>
    <div id="<?=$name?>" class="api__code-name"><b><?=$name?></b> - <?= $val['comment'] ?></div>
    <div class="api__code-descr"><?= $val['paramComment'] ?></div>

    <table class="api__table api__command">
        <tr>
            <td>Описание возвращаемых параметров</td>
            <td><?= $val['retParams'] ?></td>
        </tr>
        <tr>
            <td>Пример запроса</td>
            <td><code><?= $val['example'] ?></code></td>
        </tr>
        <tr>
            <td>Пример ответа</td>
            <td>
                <span class="api__exp-link">Показать</span>
                <pre class="api__exp-col"><?=$example?></pre>
            </td>
        </tr>
    </table>
    <br>
<?php endforeach; ?>

<div class="api__title"><hr><h2><span>Ошибки</span></h2></div>
<table class="api__table api__codes">
    <tr>
        <th>Код</th>
        <th>Описание</th>
    </tr>
    <?php foreach ($viData['codes'] as $key => $val): ?>
        <tr>
            <td><?= $key ?></td>
            <td><?= $val ?></td>
        </tr>
    <?php endforeach; ?>
</table>



<?/*<br />
<?php foreach ($viData['data'] as $key => $val): ?>
    <a name="<?= strtoupper($val['name']) ?>"></a>
    <table class="api-cards">
        <tr>
            <th>Комманда <span><?= strtoupper($val['name']) ?></span></th>
            <th>Возвращаемый результат</th>
        </tr>
        <tr>
            <td>
                <div class="example"><?= $val['example'] ?></div>
                <div class="paramComment"><?= $val['paramComment'] ?></div>
            </td>
            <td><?= $val['retParams'] ?></td>
        </tr>
        <tr>
            <td class="comment"><?= $val['comment'] ?></td>
            <td class="ret-examples"><?= $val['retExamples'] ?></td>
        </tr>
    </table>
    <br />
<?php endforeach; ?>

<h2>Коды ошибок</h2>
<?php foreach ($viData['codes'] as $key => $val): ?>
    <div class="error-code"><span><?= $key ?></span> <?= $val ?></div>
<?php endforeach; */?>
