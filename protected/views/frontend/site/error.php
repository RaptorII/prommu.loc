<h1>Error <?php echo $viData['code']; ?></h1>

<div class="img">
    <div>
        <img src="/theme/pic/error.png" alt="error <?= $viData['code'] ?>">
        <span><?= $viData['code'] ?></span>
    </div>
</div>
<div class="error">
    <?php if( $viData['code'] == 500 ): ?>
        <div class="message">Возникла непредвиденная ошибка. Мы уже занимаемся этим вопросом, пожалуйста посетите эту страницу позже.</div>
        <br />
    <?php elseif( $viData['code'] == 404 ): ?>
        <div class="message">Этой страницы не существует</div>
        <br />
        <p>Найти вакансию</p>
        <form action="<?= MainConfig::$PAGE_SEARCH_VAC ?>">
            <input type="text" name="poself" id=""><div class="search btn-orange-sm-wr"><button type="submit">Найти</button></div>
        </form>
        <br />
    <?php else: ?>
        <div class="message"><?= $viData['message'] ?></div>
    <?php endif; ?>

    <?= YII_DEBUG ? "<script type=\"text/javascript\">\nconsole.info( '".preg_replace("/[\"']/", "", "")."', JSON.parse('".json_encode(array('message' => $viData['message'], 'file' => str_replace("\\", "\\\\", $viData['file']), 'trace' => str_replace("\\", "\\\\", preg_replace("/\r|\n/", " ", $viData['trace'])), 'line' => $viData['line']))."') );</script>\n" : '' ?>
    <br />
    <p>Перейти в раздел</p>
    <div class="nav btn-orange-sm-wr">
        <a href="<?= MainConfig::$PAGE_SEARCH_VAC ?>">Вакансии</a>
        <a href="<?= MainConfig::$PAGE_SEARCH_PROMO ?>">Анкеты</a>
    </div>
</div>

