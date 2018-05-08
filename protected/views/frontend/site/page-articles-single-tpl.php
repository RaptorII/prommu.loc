<?php $image = getimagesize('https://prommu.com/images/articles/'.$viData['data']['img']); ?>
<div class="row" itemscope itemtype="http://schema.org/BlogPosting">
    <link itemprop="mainEntityOfPage" itemscope href="<?echo 'https://prommu.com' . $_SERVER['REQUEST_URI']?>"/>
    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <img alt="logo" itemprop="image url" src="https://prommu.com/theme/pic/logo-sm.png"/>
            <meta itemprop="width" content="142">
            <meta itemprop="height" content="39">
        </div>
        <meta itemprop="telephone" content="-">
        <meta itemprop="address" content="г. Москва, пр-т Рязанский, д. 46, корп. 3, офис 102, этаж 1">
        <meta itemprop="name" content="https://prommu.com/">
    </div>
    <meta itemprop="datePublished" content="<?=$viData['data']['pubdate']?>">
    <meta itemprop="dateModified" content="<?=$viData['data']['pubdate']?>">
    <span itemprop="author" itemscope itemtype="http://schema.org/Person" style="display: none;"><span itemprop="name">Prommu</span></span>
    <div class="col-xs-12 col-sm-8 news-single" itemprop="articleBody">
        <span itemprop="headline" style="display: none;"><?= $viData['data']['name'] ?></span>
        <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject" class="img">
            <img            
                itemprop="image url" 
                title="<?=$viData['data']['name']?>" 
                alt="<?=$viData['data']['name']?>"
                width="<?=$image[0]?>"
                height="<?=$image[1]?>"
                src="https://prommu.com/images/articles/<?= $viData['data']['img'] ?>"/>
            <meta itemprop="width" content="<?=$image[0]?>">
            <meta itemprop="height" content="<?=$image[1]?>">
        </span>
        <div class="text"><?= $viData['data']['html'] ?></div>
        <div class="date"><?= $viData['data']['crdate'] ?></div>
    </div>




    <div class="col-xs-12 col-sm-4">
        <div class="news-popular-header big">Популярное</div>
        <?php foreach ($viData['last'] as $key => $val): ?>
            <?php if($viData['data']['id'] == $val['id']):?>
            <? else:?>
            <div class="news-popular"> 
                <div class="big"><a href="<?= MainConfig::$PAGE_ARTICLES . DS . $val['link'] ?>"><?= $val['name'] ?></a></div>
                <a href="<?= MainConfig::$PAGE_ARTICLES . DS . $val['link'] ?>" class="img"><img src="/images/articles/<?= $val['imgSM'] ?>" alt=""><i></i></a>
               <!--  <div class="text"><?= $val['anons'] ?></div> -->
                <div class="date"><?= $val['crdate'] ?></div>
            </div>
            <? endif;?>
        <?php endforeach; ?>
    </div>
</div>