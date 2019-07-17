<div class="row">
    <div class="col-xs-12 col-sm-8 news-single">
        <span class="img"><img src="<?=Settings::getFilesUrl() . 'news/' . $viData['data']['img'] . News::$BIG_IMG?>" title="<?=$viData['data']['name']?>" alt="<?=$viData['data']['name']?>"></span>
        <div class="text"><?= $viData['data']['html'] ?></div>
        <div class="social">
        <?php /*
            <a href="#" class="social__fb" title="Facebook"></a>
            <a href="#" class="social__vk" title="Vkontakte"></a>
            <a href="#" class="social__twitter" title="Twitter"></a>
        */ ?>
        </div>
        <div class="date"><?= $viData['data']['crdate'] ?></div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="news-popular-header big">Популярное</div>
        <?php foreach ($viData['last'] as $key => $val): ?>
             <?php if($viData['data']['id'] == $val['id']):?>
                 <? else:?>
            <div class="news-popular">
                <div class="big"><a href="<?= MainConfig::$PAGE_NEWS . DS . $val['link'] ?>"><?= $val['name'] ?></a></div>
                <a href="<?= MainConfig::$PAGE_NEWS . DS . $val['link'] ?>" class="img"><img src="<?=Settings::getFilesUrl() . 'news/' . $val['img'] . News::$SMALL_IMG?>" alt=""><i></i></a>
               <!--  <div class="text"><?= $val['anons'] ?></div> -->
                <div class="date"><?= $val['crdate'] ?></div>
            </div>
             <? endif;?>
        <?php endforeach; ?>
    </div>
</div>


