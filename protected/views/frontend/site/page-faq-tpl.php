<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link media="screen" href="/css/faq/demo.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="/css/faq/style.css">
    <script src="js/faq/modernizr.js"></script>
<div id="controls">
<section class="cd-faq">

 <ul class="cd-faq-categories">
 <?php if( Share::$UserProfile->exInfo->status != 3 || Share::$UserProfile->exInfo->status == 0  ): ?>
        <li><a class="selected" href="#promo">Соискатель</a></li>
         <? endif;?>
        <?php if( Share::$UserProfile->exInfo->status != 2 || Share::$UserProfile->exInfo->status == 0  ): ?>
        <li><a href="#empl">Работодатель</a></li>
    <? endif;?>
    </ul> 
    <div class="cd-faq-items">
     <?php if( Share::$UserProfile->exInfo->status != 3 || Share::$UserProfile->exInfo->status == 0  ): ?>
        <ul id="promo" class="cd-faq-group">
        <li class="cd-faq-title"><h2>Соискатель</h2></li>
    <? foreach ($viData as $key => $value):?>
            <? if($value['type'] == 1):?>
            <li itemscope itemtype="http://schema.org/Question">
                <h2 class="cd-faq-trigger cd-faq-trigger-title" itemprop="name"><? echo $value['question']?></h2>
                <div class="cd-faq-content" itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                    <p itemprop="text"><? echo $value['answer']?></p>
                </div>
            </li>
            <?endif?>

    <? endforeach; ?>
           </ul>
            <? endif;?>
        <?php if( Share::$UserProfile->exInfo->status != 2 || Share::$UserProfile->exInfo->status == 0  ): ?>
        <ul id="empl" class="cd-faq-group">
        <li class="cd-faq-title"><h2>Работодатель</h2></li>
           <? foreach ($viData as $key => $value):?>
            <? if($value['type'] == 2):?>
            <li itemscope itemtype="http://schema.org/Question">
                <h2 class="cd-faq-trigger cd-faq-trigger-title" itemprop="name"><? echo $value['question']?></h2>
                <div class="cd-faq-content" itemprop="acceptedAnswer" itemscope itemtype="http://schema.org/Answer">
                    <p itemprop="text"><? echo $value['answer']?></p>
                </div>
            </li>
            <?endif?>
<? endforeach; ?>
        </ul> 
        <? endif;?>

        
    </div>
    <a href="#0" class="cd-close-panel">Закрыть</a>
</section>
<script src="/js/faq/jquery.mobile.custom.min.js"></script>
<script src="/js/faq/main.js"></script>