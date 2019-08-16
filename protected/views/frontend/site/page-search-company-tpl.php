<?php //if(!(MOBILE_DEVICE && !SHOW_APP_MESS)): // optimization ?>
  <?php Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-empl-search.min.js', CClientScript::POS_END); ?>
  <style type="text/css">
    /*   /theme/css/page-empl-search.css   */
    #DiContent.page-searchempl .filter{display:none}@media (min-width:768px){#DiContent.page-searchempl .filter{display:block}}#DiContent.page-searchempl .filter h3{font-size:14px;font-family:Roboto-Regular,verdana,arial;font-weight:700}#DiContent.page-searchempl .filter .filter-label{padding-top:15px}#DiContent.page-searchempl .filter .filter-label:first-of-type{padding-top:10px}#DiContent.page-searchempl .filter .filter-label>label:first-child{font-size:14px}#DiContent.page-searchempl .filter .filter-name{display:block;margin-bottom:10px;padding-bottom:3px;border-bottom:1px solid #e3e3e3}#DiContent.page-searchempl .filter .filter-content{text-align:center}#DiContent.page-searchempl .filter .filter-content label{text-transform:uppercase}#DiContent.page-searchempl .filter .filter-title{display:block;margin-bottom:10px;font-size:12px;text-transform:none!important;font-family:RobotoCondensed-Regular,verdana,arial}#DiContent.page-searchempl .filter .right-box{display:inline-block;text-align:right}#DiContent.page-searchempl .filter select.multiple{width:100%}#DiContent.page-searchempl .filter .filter-type .ms-parent{width:100%!important}#DiContent.page-searchempl .filter .filter-type .ms-parent label{text-transform:none}#DiContent.page-searchempl .filter .filter-type .ms-parent input[type=text]{width:100%}#DiContent.page-searchempl .filter-cities .filter-content,#DiContent.page-searchempl .filter-type .filter-content{text-align:left}#DiContent.page-searchempl .filter #CBcities{width:285px}@media (min-width:768px){#DiContent.page-searchempl .filter #CBcities{width:122px}}@media (min-width:992px){#DiContent.page-searchempl .filter #CBcities{width:177px}}@media (min-width:1200px){#DiContent.page-searchempl .filter #CBcities{width:227px}}#DiContent.page-searchempl .filter #LMetroMosk,#DiContent.page-searchempl .filter #LMetroPiter,#DiContent.page-searchempl .filter .self-dolj{display:none}#DiContent.page-searchempl .filter .btn-apply{margin-top:30px}#DiContent.page-searchempl .filter .btn-apply button{width:100%}#DiContent.page-searchempl .filter-open{margin-top:20px;border-bottom:3px solid #000;text-align:center}#DiContent.page-searchempl .filter-open.opened{border-top:3px solid #000;border-bottom:0}#DiContent.page-searchempl .filter-open.opened .ico{background:url(/theme/pic/ico-form.png) 0 -348px no-repeat}#DiContent.page-searchempl .filter-open .box{display:inline-block;position:relative;width:141px;height:26px;line-height:26px;background:#e3e3e3;color:#212121;font-family:RobotoCondensed-Regular,verdana,arial;text-transform:uppercase}#DiContent.page-searchempl .filter-open .ico{display:block;position:absolute;width:26px;height:26px;top:0;background:url(/theme/pic/ico-form.png) 0 -376px no-repeat}#DiContent.page-searchempl .filter-open .left{left:0}#DiContent.page-searchempl .filter-open .right{right:0}#DiContent.page-searchempl .quick-search{margin-bottom:40px}#DiContent.page-searchempl .quick-search label{display:block}#DiContent.page-searchempl .quick-search b{display:inline-block;margin-right:10px}#DiContent.page-searchempl .quick-search input{width:100%}@media (min-width:768px){#DiContent.page-searchempl .quick-search input{width:430px}}@media (min-width:992px){#DiContent.page-searchempl .quick-search input{width:500px}}#DiContent.page-searchempl .questionnaire{position:relative;margin:20px 0 10px;border-top:1px solid #abb820;text-align:center;text-transform:uppercase;font-family:RobotoCondensed-Regular,verdana,arial}#DiContent.page-searchempl .questionnaire div{display:inline-block;position:relative;top:-11px;padding:0 40px;background:#fff}#DiContent.page-searchempl .questionnaire b{color:#abb820}#DiContent.page-searchempl .empl-item-tpl{display:none}#DiContent.page-searchempl .list-view{font-size:12px}#DiContent.page-searchempl .list-view .company-list-item-box{position:relative;margin-bottom:-1px;padding:20px 25px 15px;border:1px solid #fff;border-top:1px solid #dadada}#DiContent.page-searchempl .list-view .company-list-item-box:first-child{border-top:1px solid #fff}#DiContent.page-searchempl .list-view .company-list-item-box:hover{border:1px solid #dadada}#DiContent.page-searchempl .list-view .row>div{margin-top:0}#DiContent.page-searchempl .list-view h2,#DiContent.page-searchempl .list-view h3{margin-top:0;text-transform:uppercase}#DiContent.page-searchempl .list-view h2 i,#DiContent.page-searchempl .list-view h3 i{font-style:normal}#DiContent.page-searchempl .list-view h3{margin:0 0 5px;font-size:12px;font-weight:700}#DiContent.page-searchempl .list-view h3 small{font-size:12px;font-weight:400;text-transform:none}#DiContent.page-searchempl .list-view .company-logo-wrapp{position:relative;text-align:center}#DiContent.page-searchempl .list-view .company-logo-wrapp img{width:100%}#DiContent.page-searchempl .list-view .title-block h2,#DiContent.page-searchempl .list-view .title-block h3{margin-right:90px}#DiContent.page-searchempl .list-view .expirience{float:right}#DiContent.page-searchempl .list-view .com-rate .pos{color:#abb820;font-weight:700}#DiContent.page-searchempl .list-view .com-rate .neg{color:#ff462d}#DiContent.page-searchempl .list-view .btn-rate-details{display:inline-block}#DiContent.page-searchempl .list-view .btn-rate-details a{margin:0 0 0 10px;padding:0 5px;font-size:10px}#DiContent.page-searchempl .list-view table.rate{margin-top:10px;width:100%;border-collapse:separate;border-spacing:2px}#DiContent.page-searchempl .list-view table.rate.hide-rate{display:none}#DiContent.page-searchempl .list-view table.rate td:first-child{font-weight:700}#DiContent.page-searchempl .list-view table.rate td.val{padding-right:10px;white-space:nowrap}#DiContent.page-searchempl .list-view table.rate td.val .good{color:#abb820}#DiContent.page-searchempl .list-view table.rate td.val .bad{color:#ff462d}#DiContent.page-searchempl .list-view .btn-invite a:hover,#DiContent.page-searchempl .list-view table.rate td.progress.-red .text{color:#fff}#DiContent.page-searchempl .list-view table.rate td.progress{position:relative;width:99%;background:#e3e3e3;border-radius:0}#DiContent.page-searchempl .list-view table.rate td.progress.-red .progr-line{height:21px;background:red}#DiContent.page-searchempl .list-view table.rate td.progress .text{display:block;position:absolute;left:10px;top:1px;text-transform:uppercase}#DiContent.page-searchempl .list-view table.rate .rate-tpl{display:none}#DiContent.page-searchempl .list-view table.rate .progress-green{width:80%;background:#abb820}#DiContent.page-searchempl .list-view table.rate .progress-red{width:80%;background:#ff462d}#DiContent.page-searchempl .list-view table.rate .progress01{width:80%;background:#abb820}#DiContent.page-searchempl .list-view table.rate .progress02{width:30%;height:21px;background:#fff200}#DiContent.page-searchempl .list-view table.rate .progress03{width:69%;height:21px;background:#d0dc54}#DiContent.page-searchempl .list-view table.rate .progress04{width:20%;height:21px;background:#ff462d}#DiContent.page-searchempl .list-view .vacancies{display:inline-block;margin-bottom:10px}#DiContent.page-searchempl .list-view .vacancies .price{float:right;margin-left:25px}#DiContent.page-searchempl .list-view .btn-invite{float:left;margin-top:15px;text-align:center}@media (min-width:768px){#DiContent.page-searchempl .list-view .btn-invite{float:right;margin-top:0}}@media (min-width:1200px){#DiContent.page-searchempl .list-view .btn-invite{float:left}}#DiContent.page-searchempl .list-view .btn-invite a{display:inline-block;color:#fff;background:#ff6500;text-transform:uppercase;text-align:center;border:0;padding:5px 35px;width:auto}#DiContent.page-searchempl .list-view .btn-more a{padding:5px 85px}#DiContent.page-searchempl .list-view table.rate .progress05{width:10%;height:21px;background:#ff462d}#DiContent.page-searchempl .list-view .vacarhive{display:inline-block;margin-bottom:10px}#DiContent.page-searchempl .list-view .vacarhive .price{float:right;margin-left:25px}#DiContent.page-searchempl .list-view .place{display:inline-block;margin:0 30px 10px 0}#pse-additional,.pse__veil{display:none}.page-search-empl .select2-container--default .select2-selection--multiple{width:100%;min-height:35px;padding:0 15px;background-color:transparent;border:1px solid #EBEBEB;font-size:14px;color:#646464;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;border-radius:0}#DiContent .page-search-empl .pse__input,#DiContent .pse__checkbox-label,.pse__content{font-family:RobotoCondensed-Regular,verdana,arial}.page-search-empl .select2-container--default.select2-container--focus .select2-selection--multiple{border:1px solid #EBEBEB;outline:0}.page-search-empl .select2-container--default .select2-selection--multiple .select2-selection__choice{background-color:rgba(52,52,52,.6);color:#FFF;border-color:#858585}#DiContent.page-vacancy .page-search-empl .filter .filter-name,.pse__filter-name{border:1px solid #e3e3e3}#DiContent.page-vacancy .page-search-empl .filter .filter-content label{font-size:14px;color:#A0A0A0;font-family:RobotoCondensed-Regular,verdana,arial;text-transform:none}.pse__filter-btn,.pse__filter-name{text-transform:uppercase;cursor:pointer}.page-search-empl{position:relative}.pse__veil{position:absolute;top:0;right:0;bottom:0;left:0;z-index:3;background-color:rgba(255,255,255,.7)}.pse__veil:before{content:'';width:130px;height:130px;position:absolute;top:150px;left:-65px;background:url(/theme/pic/vacancy/loading.gif) no-repeat;margin-left:50%}.pse__nothing{color:#343434;font-size:18px}.pse__view-block{margin-bottom:15px}.pse__view-list,.pse__view-table{width:19px;height:15px;display:block;margin-left:8px;float:right;background:url(/theme/pic/vacancy/srch-vac-sprite.png) no-repeat}.pse__view-list{background-position:0 0}.pse__view-table{background-position:0 -30px}.pse__view-list.active{background-position:0 -15px}.pse__view-table.active{background-position:0 -45px}.pse__filter-name{display:block;position:relative;margin-bottom:10px;padding:5px 15px;font-size:14px}.pse__filter-name.opened{background:#e3e3e3;border-color:#e3e3e3}.pse__filter-name:not(.opened):hover{border-color:#abb820}.pse__filter-name:after{content:' ';position:absolute;right:15px;top:0;border:10px solid transparent;border-bottom:9px solid #e3e3e3}.pse__filter-name.opened:after{bottom:0;top:auto;border:10px solid transparent;border-top:9px solid #fff}.pse__btn:before,.pse__checkbox-label:after,.pse__filter-btn:before{content:'';top:0;right:0}.pse__filter-content{display:none;padding-bottom:35px;text-align:center}.pse__filter-content.opened{display:block}#DiContent .page-search-empl .pse__input{height:35px;border:1px solid #EBEBEB;background-color:transparent;padding:0 15px;color:#343434;font-size:14px}.pse__input:focus{outline:0}.pse__filter-btn{display:block;margin:10px 0 0;width:100px;max-width:220px;line-height:30px;text-align:center;background:#FF8300;color:#FFF;position:relative;z-index:1;float:right;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}#DiContent .page-search-empl .pse__btn,.pse__filter-btn:before{-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out}.pse__filter-btn:before{position:absolute;z-index:-1;left:0;bottom:0;background:#BBC823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;transition:all .3s ease-out}.pse__filter-btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .pse__checkbox-input{display:none}.pse__content{margin-top:0;font-size:16px}#DiContent.page-searchempl .page-search-empl .filter{display:block}.pse__header{padding:20px 0 15px;border-bottom:1px solid #D6D6D6;margin-bottom:20px}.pse__header-name{margin:0 0 20px;display:block;color:#343434;font-size:18px;text-decoration:underline;vertical-align:middle;text-align:center}.pse__header-name:before{content:'';display:inline-block;width:27px;height:27px;background:url(/theme/pic/private/vac-list-user-icon.png) no-repeat;vertical-align:middle;margin-right:5px}#DiContent .page-search-empl .pse__btn{line-height:30px;display:block;margin:0 auto;padding:0;background:#ff8300;color:#FFF;text-align:center;text-transform:uppercase;font-size:14px;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;position:relative;z-index:1;border:none;transition:all .3s ease-out}.pse__btn:before{position:absolute;z-index:-1;left:0;bottom:0;background:#ABB837;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.pse__btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .page-search-empl .pse__header-btn{width:195px}.pse__header-title{min-height:19px;margin:0 0 20px;display:block;color:#343434;font-size:18px}.filter-type .pse__checkbox-label:nth-child(2){margin-bottom:20px}@media (min-width:768px){.pse__header{padding:20px 0 15px 80px}.pse__header-name{margin:0;display:inline-block;text-align:left}#DiContent .page-search-empl .pse__header-btn{margin:0 30px;display:inline-block}}.pse__filter-vis{text-align:center;margin-bottom:20px;border:3px solid #abb820;cursor:pointer;color:#616161;line-height:35px;position:relative}.pse__filter-vis:before,.pse__filter-vis:after{content:'';width:0;height:0;display:block;position:absolute;top:10px;border-left:20px solid transparent;border-right:20px solid transparent;border-bottom:15px solid #abb820;}.pse__filter-vis:before{left:10px}.pse__filter-vis:after{right:10px}.pse__filter-vis.active:before,.pse__filter-vis.active:after{border-bottom:initial;border-top:15px solid #abb820;}.empl-list__item-onl{width:80%;height:50px;position:absolute;left:10%;bottom:-24px;background-color:#fff;border-radius:50%;border:1px solid #abb820}.empl-list__item-onl span,.empl-list__item-onl span:hover{color:#abb820;font-size:12px;font-weight:700;position:relative;padding-right:13px}.empl-list__item-onl span:after{content:'';width:10px;height:10px;position:absolute;right:0;top:4px;display:block;border-radius:50%;background-color:#abb820}#DiContent .company-logo>a{border-radius:50%;border:2px solid #abb820;overflow:hidden;display:block;position:relative}
    .select-list{max-height:300px;overflow-y:auto;padding:0;margin:0;border-top:none;list-style:none;background-color:rgba(52,52,52,.6);position:absolute;top:100%;left:-1px;right:-1px;z-index:2;font-family:RobotoCondensed-Regular,verdana,arial;font-size:14px;color:#FFF}.select-list li{width:100%;line-height:30px;padding:3px 6px;cursor:pointer;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.select-list li:hover{background-color:rgba(255,255,255,.2)}#filter-city{position:relative}#filter-city .select-list li{padding:0 15px;text-align:left}#DiContent #filter-city .filter-city-select input{padding:0;border:none;background:0 0;margin:2px 0 2px 6px;height:29px}#DiContent #filter-city .filter-city-select input:focus{outline:0}.filter-city-select.load:after{content:'';width:20px;height:20px;right:3px;background:url(/theme/pic/loading1.gif) no-repeat;background-size:cover;top:7px;position:absolute}.city-select,.filter-city-select li:not([data-id="0"]){font-family:RobotoCondensed-Regular,verdana,arial;display:inline-block;padding:3px 20px 3px 5px;margin:2px 0 2px 6px;background-color:rgba(52,52,52,.6);color:#fff;line-height:18px;border-radius:5px;position:relative}.filter-city-select li:not([data-id="0"]){line-height:23px}.filter-city-select li[data-id="0"]{width:10px}.filter-city-select{display:flex;flex-direction:row;justify-content:start;flex-wrap:wrap;margin:0;list-style:none;border:1px solid #EBEBEB;position:relative;padding:0 25px 0 15px}.city-select b,.filter-city-select b{width:19px;height:19px;display:block;position:absolute;top:2px;right:0;font-style:normal;text-align:center;cursor:pointer}.filter-city-select b{top:5px}.city-select b:before,.filter-city-select b:before{content:'\2716';display:block;position:absolute;top:0;right:0;bottom:0;left:0;line-height:20px}#DiContent .project__index-time input{text-align:center;padding:0 16px 0 6px}
    #DiContent .pse__checkbox-label {
        display: block;
        position: relative;
        height: 33px;
        line-height: 29px;
        padding-right: 35px;
        cursor: pointer;
        font-size: 13.33px;
        color: #a0a0a0;
        text-transform: none
    }
    .pse__checkbox-label:after {
        display: block;
        position: absolute;
        width: 30px;
        height: 29px;
        right: 0;
        top: 0;
        font-size: 28px;
        font-family: 'PrommuFont' !important;
        content: '\e98a';
        color: var(--color-ll-grey);
    }
    .pse__checkbox-label:hover:after {
        content: '\e98a';
        color: var(--color-ll-grey);
    }
    input:checked + .pse__checkbox-label:after {
        content: '\e989';
        color: var(--color-green);
    }
  </style>
<?php 
	// если не моб устройство
	// endif; 
?>
<div class='row page-search-empl'>
  <script type="text/javascript">var arAllData = <?=json_encode($viData['filter'])?></script>
  <div class="pse__veil"></div>
  <div class="col-xs-12">
      <?php if(Share::$UserProfile->type == 3): ?>
          <div class="pse__header">
              <h1 class="pse__header-name"><?=Share::$UserProfile->exInfo->name?></h1>
              <a class='pse__btn pse__header-btn btn__orange' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
          </div>
      <?php endif; ?>
  </div>
  <div class='col-xs-12 col-sm-3'> 
<?
/*
*   FILTER
*/
?>
	<div class="pse__filter-vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
    <form action="" id="F1Filter" method="get">
      <div class='filter'>
        <div class='pse__filter-block filter-surname'>
          <div class='pse__filter-name opened'>Название компании</div>
          <div class='pse__filter-content opened'>
            <input name='qs' type='text' title="Введите фамилию" value="<?=$_GET['qs']?>" class="pse__input">
            <div class="pse__filter-btn btn__orange">ОК</div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class='pse__filter-block filter-cities'>
          <div class='pse__filter-name opened'>Город</div>
			<div class='pse__filter-content opened'>
				<?
					if(in_array(Share::$UserProfile->type, [2,3])) {
						$arRes = Yii::app()->db->createCommand()
							->select('c.id_co country')
							->from('user_city uc')
							->join('city c', 'uc.id_city=c.id_city')
							->where('id_user=:id_user', array(':id_user' => Share::$UserProfile->id))
							->queryRow();
					}
					else {
						$geo = new Geo();
						$arRes = $geo->getUserGeo();
					}
				?>
				<div class="fav__select-cities" id="filter-city" data-city="<?=$arRes['country']?>">
					<ul class="filter-city-select">
						<? if(isset($_GET['cities'])): ?>
							<? foreach ($_GET['cities'] as $id): ?>
							<li>
								<?=$viData['filter']['cities'][$id]['name']?>
								<b></b>
								<input type="hidden" name="cities[]" value="<?=$id?>">
							</li>
							<? endforeach; ?>
						<? endif; ?>
						<li data-id="0">
							<input type="text" name="fc" class="city-inp" autocomplete="off">
						</li>
					</ul>
					<ul class="select-list"></ul>
				</div>
			</div>
        </div>
        <div class='pse__filter-block filter-type'>
            <div class='pse__filter-name opened'>Тип работодателя</div>
              <div class='pse__filter-content opened'>
                <div class='right-box'>
                    <input name='cotype-all' type='checkbox' id="pse-cotype-all" class="pse__checkbox-input">
                    <label class='pse__checkbox-label pse__checkbox-label-ct-all' for="pse-cotype-all">Выбрать все / снять все</label>
                    <?php foreach($viData['filter']['cotype'] as $id => $name): ?>
                      <input name='cotype[]' value="<?=$id?>" type='checkbox' id="pse-cotype-<?=$id?>" class="pse__checkbox-input" <?=(in_array($id, $_GET['cotype']) ? 'checked' : '')?>>
                      <label class='pse__checkbox-label' for="pse-cotype-<?=$id?>"><?=$name?></label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
      </div>
    </form>
  </div>
<?
/*
*   CONTENT
*/
?>
  <div class='col-xs-12 col-sm-9' id="content">
    <?php if( !count($viData['empls']) ): ?>
      <div class="pse__nothing">Нет подходящих компаний</div>
    <?php else: ?>
        <div class='questionnaire'>
          <div>
            Найдено
            <b><?= $count ?></b>
            <span class='hidden-xs'>зарегистрированных</span>
            работодателей
          </div>
        </div>
        <?php /* BM: list view */ ?>
        <div class='list-view'>
          <?php foreach ($viData['empls'] as $key => $val): ?>
              <div class='company-list-item-box'>
                  <div class='row'>
                      <div class='col-xs-12 col-sm-3 col-lg-2'>
                          <div class='company-logo-wrapp'>
                              <div class='company-logo'>
                                  <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>
                                      <img 
                                      	alt='Работодатель <?= $val['name'] ?> prommu.com' 
                                      	src="<?=Share::getPhoto($val['id_user'],3,$val['logo'])?>">
										<?php if($val['is_online']): ?>
											<span class="empl-list__item-onl"><span>В сети</span></span>
										<?php endif; ?>
                                  </a>
                              </div>

                          </div>


                          <div style="text-align: center;margin-top: 10px;margin-bottom: 10px;">
                              <? if ($val['is_online']): ?>
                                  <span style="color:#abb820"><i style="
                display: inline-block;
                width: 8px;
                height: 8px;
                background: #abb820;
                border-radius: 50%;
                margin-right: 8px;
            "></i>В сети</span>
                              <? else: ?>

                                  <span style="color:#D6D6D6"><i style="
                display: inline-block;
                width: 8px;
                height: 8px;
                background: #D6D6D6;
                border-radius: 50%;
                margin-right: 8px;
            "></i>Был(а) на сервисе: <?= date_format(date_create($val['mdate']), 'd.m.Y'); ?></span>
                              <? endif; ?>
                          </div>


                      </div>
                      <div class='col-xs-12 col-sm-9 col-lg-10'>
                          <div class='title-block'>
                              <div class='expirience'><?php /* $val['exp'] */ ?></div>
                              <h2>
                                  <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'><?= $val['name'] ?></a>
                                  <small>(№ <?= $val['id'] ?>)</small>
                              </h2>
                          </div>
                          <div class="rate-block">
                              <div class="com-rate">
                                Рейтинг: 
                                <span class="js-g-hashint" title="Всего"><?=($val['rate'] + $val['rate_neg'])?></span>
                                (<b class="-green js-g-hashint" title="Положительный"><?=$val['rate']?></b> 
                                / <b class="-red js-g-hashint" title="Отрицательный"><?=$val['rate_neg']?></b>)
                              </div>
                              <table class='rate hide-rate'>
                                  <thead>
                                  <tr class="rate-tpl">
                                      <td class='val'>
                                          <span class="num"></span> (
                                          <span class='good' title='отлично'></span>
                                          /
                                          <span class='bad' title='плохо'></span>
                                          )
                                      </td>
                                      <td class='progress'>
                                          <div class='progr-line' style="">&nbsp;</div>
                                          <div class='text'><!-- ratename--></div>
                                      </td>
                                  </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                          <br>
                          <div class='place'>
                              <h3>
                                  Город:
                                  <small><?= join(', ', array_values($val['city'])) ?></small>
                              </h3>
                          </div>
                          <?php if ($val['metroes']): ?>
                              <div class='place'>
                                  <h3>
                                      Метро:
                                      <small><?= join(', ', array_values($val['metroes'])) ?></small>
                                  </h3>
                              </div>
                          <?php endif; ?>
                          <div class='type'>
                            <?php if(isset($val['tname'])): ?>
                              <h3>Работодатель: <small><?= $val['tname'] ?></small></h3>
                            <?php endif; ?>
                          </div>
                      </div>
                  </div>
                  <div class='row'>
                      <div class='col-xs-12 col-md-8 col-md-push-4'>
                          <div class='btn-more btn-white-green-wr'>
                              <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>Подробнее</a>
                          </div>
                      </div>
                  </div>
              </div>
          <?php endforeach; ?>
        </div>
        <script type="text/javascript">
        	$(function(){ G_VARS.DEF_LOGO_EMPL = '<?= MainConfig::$DEF_LOGO_EMPL ?>' })
        </script>
        <br>
        <br>
        <?php // display pagination
            $this->widget('CLinkPager', array(
              'pages' => $pages,
              'htmlOptions' => array('class' => 'paging-wrapp'),
              'firstPageLabel' => '1',
              'prevPageLabel' => 'Назад',
              'nextPageLabel' => 'Вперед',
              'header' => '',
              'cssFile' => false
          ));
        ?>
  <?php endif; ?>
  </div>
  <div class="col-xs-12 pse__content" id="pse-seo-text"><?php 
    echo $this->ViewModel->getViewData()->pageMetaKeywords;
  ?></div>
</div>