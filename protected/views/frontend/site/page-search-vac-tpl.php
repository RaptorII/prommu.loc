<?php
	$cookieView = Yii::app()->request->cookies['vacancies_page_view']->value; // вид, сохраненный в куках 

	foreach ($viData['posts'] as $p)
		if($p['postself'] && array_key_exists($p['id'], $_GET['post'])) {
			Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
			break;
		}

	//if(!(MOBILE_DEVICE && !SHOW_APP_MESS)):
?>
    <?php
        Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-vac-search.min.js', CClientScript::POS_END);
    ?>
    <style type="text/css">
        /*    /theme/css/page-vac-search.css     */ 
        #DiContent.page-vacancy div.sub-menu{margin-top:20px}#DiContent.page-vacancy .filter-busy label,#DiContent.page-vacancy .filter-sex label{margin-top:3px}#DiContent.page-vacancy .filter h3{font-size:14px;font-family:Roboto-Regular,verdana,arial;font-weight:700}#DiContent.page-vacancy .filter .row>div,#DiContent.page-vacancy .filter .row>div:first-child{padding-left:0;padding-right:5px}#DiContent.page-vacancy .filter .filter-label:first-of-type{padding-top:10px}#DiContent.page-vacancy .filter .filter-name{display:block;position:relative;margin-bottom:10px;padding:5px 15px;border:3px solid #e3e3e3;text-transform:uppercase;font-size:14px;cursor:pointer}#DiContent.page-vacancy .filter .filter-name.opened{background:#e3e3e3;border-color:#e3e3e3}#DiContent.page-vacancy .filter .filter-name.opened:after{bottom:0;top:auto;border:10px solid transparent;border-top:9px solid #fff}#DiContent.page-vacancy .filter .filter-name:not(.opened):hover{border-color:#abb820}#DiContent.page-vacancy .filter .filter-name:not(.opened):hover:after{border-bottom:9px solid #abb820}#DiContent.page-vacancy .filter .filter-name:after{content:' ';position:absolute;right:15px;top:0;border:10px solid transparent;border-bottom:9px solid #e3e3e3}#DiContent.page-vacancy .btn-edit-vac a::before,.psv__btn:before,.psv__header-name:before,.psv__veil:before{content:''}#DiContent.page-vacancy .filter .filter-content{display:none;padding-bottom:35px;text-align:center}#DiContent.page-vacancy .filter .filter-content.opened{display:block}#DiContent.page-vacancy .filter .filter-content label{text-transform:uppercase}#DiContent.page-vacancy .filter .right-box{display:inline-block;text-align:right}#DiContent.page-vacancy .filter-cities .filter-content,#DiContent.page-vacancy .filter-dolj .filter-content{text-align:left}#DiContent.page-vacancy .filter-actual label{margin-right:10px}#DiContent.page-vacancy .filter #CBcities{width:122px}@media (min-width:768px){#DiContent.page-vacancy .filter #CBcities{width:122px}}@media (min-width:992px){#DiContent.page-vacancy .filter #CBcities{width:177px}}@media (min-width:1200px){#DiContent.page-vacancy .filter #CBcities{width:227px}}#DiContent.page-vacancy .filter .self-dolj label{text-transform:uppercase}#DiContent.page-vacancy .filter .salary-box{margin-bottom:5px}#DiContent.page-vacancy .filter .salary-box label{text-transform:uppercase}#DiContent.page-vacancy .filter .salary-box input{margin-left:5px;width:50px}#DiContent.page-vacancy .filter .salary-box i{font-size:12px;font-style:normal}#DiContent.page-vacancy .filter .salary-box .radio-box{float:right}#DiContent.page-vacancy .filter-salary .filter-content label{display:block;text-align:left}#DiContent.page-vacancy .list-view .hh2 i,#DiContent.page-vacancy .list-view .vac-list-item-box .iconp{display:none}#DiContent.page-vacancy .filter-age input{margin-right:10px;width:35px;text-align:center}#DiContent.page-vacancy .filter .btn-apply{margin-top:10px}#DiContent.page-vacancy .filter .btn-apply button{padding:5px;width:100%}#DiContent.page-vacancy .list-view .row>div{margin-top:0}#DiContent.page-vacancy .list-view .vac-list-item-box{position:relative;margin-bottom:5px;font-family:RobotoCondensed-Regular,verdana,arial}#DiContent.page-vacancy .list-view .vac-list-item-box.premium .border{border-color:#abb820}#DiContent.page-vacancy .list-view .vac-list-item-box ._head{padding:10px 20px;background:#F7F7F7}#DiContent.page-vacancy .list-view .vac-list-item-box ._body{padding:10px 20px 15px}@media (min-width:992px){#DiContent.page-vacancy .list-view .vac-list-item-box ._body{padding-right:10px}}#DiContent.page-vacancy .list-view .vac-list-item-box .border{border:3px solid #e3e3e3}#DiContent.page-vacancy .list-view .hh2{font-size:14px;text-transform:uppercase;font-weight:700}#DiContent.page-vacancy .list-view .hh2 .payment{position:relative;white-space:nowrap}@media (min-width:768px){#DiContent.page-vacancy .list-view .hh2{font-size:18px}#DiContent.page-vacancy .list-view .hh2 .payment{padding-left:25px}#DiContent.page-vacancy .list-view .hh2 i{display:block;position:absolute;width:21px;height:16px;left:2px;top:5px;background:url(/theme/pic/ico-icons-03.png) 0 -192px no-repeat}}#DiContent.page-vacancy .list-view .vac-num{float:right;position:relative;margin:-13px 0 0;padding-top:10px;border-top:6px solid #abb820;text-align:right;font-size:13px}@media (min-width:768px){#DiContent.page-vacancy .list-view .vac-num{padding:10px 10px 0;font-size:14px}}#DiContent.page-vacancy .list-view .hh3{margin:0 0 10px;padding-bottom:10px;font-size:13px;font-weight:700;border-bottom:1px solid #e3e3e3;vertical-align:top}@media (min-width:992px){#DiContent.page-vacancy .list-view .hh3{display:inline-block;width:70%;font-size:14px}}#DiContent.page-vacancy .list-view .company-logo-wrapp{text-align:center;vertical-align:top}#DiContent.page-vacancy .list-view .company-logo-wrapp .company-logo{display:block;padding:10px}#DiContent.page-vacancy .list-view .company-logo-wrapp img{width:100%;max-width:200px}#DiContent.page-vacancy .list-view .company-logo-wrapp .name{font-size:12px}@media (min-width:992px){#DiContent.page-vacancy .list-view .company-logo-wrapp{display:inline-block;float:right;width:29%}#DiContent.page-vacancy .list-view .company-logo-wrapp .name{font-size:14px}}#DiContent.page-vacancy .list-view .info{margin-bottom:10px;width:100%;font-size:12px;border-bottom:1px solid #e3e3e3}#DiContent.page-vacancy .list-view .info tr td:first-of-type{padding-left:0;font-weight:700}#DiContent.page-vacancy .list-view .info tr:last-of-type td{padding-bottom:10px}#DiContent.page-vacancy .list-view .info .sex b,#DiContent.page-vacancy .list-view .info .sex i{display:inline-block;width:21px;height:16px;margin-left:3px;background:url(/theme/pic/ico-icons-03.png) 0 -224px no-repeat}#DiContent.page-vacancy .list-view .info .sex b{margin-left:0;background-position:0 -176px}#DiContent.page-vacancy .list-view .info .busy td{position:relative}#DiContent.page-vacancy .list-view .info .busy td i{display:inline-block;position:absolute;width:21px;height:16px;margin:2px 0 0 5px;background:url(/theme/pic/ico-icons-03.png) 0 -207px no-repeat}#DiContent.page-vacancy .list-view-tpl,#DiContent.page-vacancy .table-view .iconp{display:none}@media (min-width:992px){#DiContent.page-vacancy .list-view .info{width:70%;font-size:14px}}#DiContent.page-vacancy .list-view .dates-start-end{margin:10px 0;font-size:12px}#DiContent.page-vacancy .list-view table td{padding:5px;vertical-align:top}#DiContent.page-vacancy .list-view .btn-reply{margin-top:15px}@media (min-width:768px){#DiContent.page-vacancy .list-view .btn-reply{margin-top:0;text-align:right}}#DiContent.page-vacancy .list-view .btn-go-vacancy-02{text-align:center}#DiContent.page-vacancy .list-view .btn-go-vacancy-02 a{margin:0 auto;padding:5px 90px}#DiContent.page-vacancy .table-view>div{margin-top:0}#DiContent.page-vacancy .table-view .sex-block{text-align:right}#DiContent.page-vacancy .table-view .sex-block .ico{display:inline-block;width:24px;height:24px;margin-left:10px;background:url(/theme/pic/ico-form.png) -1px -493px no-repeat}#DiContent.page-vacancy .table-view .sex-block .ico.ico-woman{background-position:-1px -522px}#DiContent.page-vacancy .table-view .normal-o .border{border:3px solid #fff}#DiContent.page-vacancy .table-view .premium{position:relative;padding:0 1px 0 0}#DiContent.page-vacancy .table-view .premium .border{position:relative;padding:0 15px;border:3px solid #ccdb30}#DiContent.page-vacancy .table-view .premium .iconp{display:block;position:absolute;left:0;top:0;padding:0 10px;background:#ccdb30;font-size:10px;color:#fff;text-transform:uppercase}#DiContent.page-vacancy .table-view h3{margin-top:25px}#DiContent.page-vacancy .table-view .nodata{font-size:14px}#DiContent.page-vacancy .tab-view-tpl{display:none}#DiContent.page-vacancy .vacancy{display:none;margin:0 -15px;font-size:12px}#DiContent.page-vacancy .vacancy:nth-of-type(1),#DiContent.page-vacancy .vacancy:nth-of-type(2),#DiContent.page-vacancy .vacancy:nth-of-type(3),#DiContent.page-vacancy .vacancy:nth-of-type(4){display:block}#DiContent.page-vacancy .vacancy h3{min-height:44px;font-size:14px;font-family:Roboto-Regular,verdana,arial;font-weight:700}#DiContent.page-vacancy .vacancy a{text-decoration:none;color:#212121}#DiContent.page-vacancy .location .addr,.psv__header-name{text-decoration:underline}#DiContent.page-vacancy .vacancy a:hover{color:#abb820}#DiContent.page-vacancy .vacancy .btn-apply:hover,#DiContent.page-vacancy .vacancy .go-vacancy a:hover{color:#fff}#DiContent.page-vacancy .vacancy .hr{margin:10px 0 3px;border-top:1px solid #777}#DiContent.page-vacancy .vacancy .btn-apply{display:block;margin:10px auto;width:95px}#DiContent.page-vacancy .vacancy .img{float:left;margin:10px 10px 10px 0}#DiContent.page-vacancy .vacancy .img img{max-width:43px;max-height:43px}#DiContent.page-vacancy .vacancy .company{display:block;margin-top:10px}#DiContent.page-vacancy .vacancy .date{display:block}@media (min-width:768px){#DiContent.page-vacancy .vacancy{margin:0}#DiContent.page-vacancy .vacancy:nth-of-type(1),#DiContent.page-vacancy .vacancy:nth-of-type(2),#DiContent.page-vacancy .vacancy:nth-of-type(3),#DiContent.page-vacancy .vacancy:nth-of-type(4),#DiContent.page-vacancy .vacancy:nth-of-type(5),#DiContent.page-vacancy .vacancy:nth-of-type(6){display:block}}@media (min-width:992px){#DiContent.page-vacancy .vacancy{display:block}}#DiContent.page-vacancy{font-family:RobotoCondensed-Regular,verdana,arial}#DiContent .page-search-vacancy .psv__btn,#DiContent .psv__header-title{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;display:block}#DiContent.page-vacancy .mess-box{margin:20px 0;text-align:center;color:#abb820}#DiContent.page-vacancy .row .row>div{margin-top:0}#DiContent.page-vacancy .wrapper{position:relative;padding-top:20px}#DiContent.page-vacancy .header{width:100%}#DiContent.page-vacancy .header h2,#DiContent.page-vacancy .header h3{font-size:13px;margin:-13px 0 3px}#DiContent.page-vacancy .unpubl{color:#ff6500;text-align:right}#DiContent.page-vacancy .has-ico{position:relative;padding-left:25px}#DiContent.page-vacancy .has-ico i{display:block;position:absolute;width:21px;height:16px;left:0;top:3px;background:url(/theme/pic/ico-icons-03.png) no-repeat}#DiContent.page-vacancy .comm-logo-wrapp img{width:150px}#DiContent.page-vacancy .comm-logo-wrapp .name{font-size:13px;text-align:center}#DiContent.page-vacancy .comm-logo-wrapp .hr{display:none}@media (min-width:768px){#DiContent.page-vacancy .comm-logo-wrapp img{width:100%}#DiContent.page-vacancy .comm-logo-wrapp .name{font-size:14px;text-align:left}#DiContent.page-vacancy .comm-logo-wrapp .hr{display:block}}#DiContent.page-vacancy .vac-num{display:none;margin-bottom:10px;font-size:24px;font-weight:700}@media (min-width:768px){#DiContent.page-vacancy .vac-num{display:block}}#DiContent.page-vacancy .contacts-block{display:none}#DiContent.page-vacancy ._head{margin-bottom:20px;padding:10px;background:#f2f2f2}#DiContent.page-vacancy ._head *{margin-bottom:5px}#DiContent.page-vacancy ._head .hh1{color:#abb820;font-size:18px}#DiContent.page-vacancy ._head small{font-weight:700;font-size:14px}#DiContent.page-vacancy ._head .salary{font-weight:700}#DiContent.page-vacancy ._head .salary i{background-position:0 -192px}#DiContent.page-vacancy ._head .dates-start-end{color:#abb820}#DiContent.page-vacancy ._head .dates-start-end b{color:#212121}@media (min-width:768px){#DiContent.page-vacancy ._head .title-block{display:inline-block;width:62%}#DiContent.page-vacancy ._head .date-block{display:inline-block;width:37%;float:right;text-align:right}#DiContent.page-vacancy ._head .hh1{text-transform:uppercase}#DiContent.page-vacancy ._head .salary{display:inline-block}}@media (min-width:1200px){#DiContent.page-vacancy ._head .title-block{display:inline-block;width:70%}#DiContent.page-vacancy ._head .date-block{display:inline-block;width:29%}.psv__separator3{clear:both}}#DiContent.page-vacancy .sex i{background-position:0 -240px}#DiContent.page-vacancy .age i{background-position:0 -257px}#DiContent.page-vacancy .salary-list i{background-position:0 -272px}#DiContent.page-vacancy .city-addr-block{margin-bottom:10px;border-bottom:1px solid #e3e3e3}#DiContent.page-vacancy .city-addr-block .period-line td{position:relative;padding:5px 10px;white-space:nowrap}#DiContent.page-vacancy .city-addr-block .period-line td.times{background:#f2f2f2;color:#abb820}#DiContent.page-vacancy .city-block{margin-bottom:10px;padding:4px 10px;width:100%;background:#f2f2f2}#DiContent.page-vacancy .city-block .city{margin-left:5px}#DiContent.page-vacancy .city-block .city i{background-position:0 -288px}#DiContent.page-vacancy .city-block .dates{padding-left:5px}@media (min-width:768px){#DiContent.page-vacancy .city-block{display:flex;align-items:center}#DiContent.page-vacancy .city-block .dates{padding:inherit}}#DiContent.page-vacancy .location .point{padding:10px}#DiContent.page-vacancy .location .addr i{background-position:0 -304px}#DiContent.page-vacancy .location .days{padding:0}@media (min-width:768px){#DiContent.page-vacancy .location .days{padding:0 15px}}#DiContent.page-vacancy .ismed i{background:url(/theme/pic/ico-form-small.png) 0 -134px no-repeat}#DiContent.page-vacancy .isavto i{background:url(/theme/pic/ico-form-small.png) 0 -112px no-repeat}#DiContent.page-vacancy .contacts i{background-position:0 -320px}#DiContent.page-vacancy .btn-register,#DiContent.page-vacancy .btn-response{margin-top:20px;text-align:center}#DiContent.page-vacancy .btn-edit-vac{position:absolute;margin-top:-36px;right:15px;text-align:center}#DiContent.page-vacancy .btn-edit-vac a{position:relative;width:24px;height:24px;padding:0}#DiContent.page-vacancy .btn-edit-vac a:hover::before{background-position:0 -480px}#DiContent.page-vacancy .btn-edit-vac a::before{display:block;position:absolute;width:21px;height:16px;left:3px;top:3px;background:url(/theme/pic/ico-icons-03.png) 0 -464px no-repeat}#DiContent.page-vacancy .btn-edit-vac a span{display:none;background:inherit}@media (max-width:767px){#DiContent.page-vacancy .btn-edit-vac{right:30px!important}}#DiContent.page-vacancy .resp-message{margin-top:20px;text-align:center;font-style:italic}#DiContent.page-vacancy .tabs-panel{margin-top:40px}#DiContent.page-vacancy .tabs-panel .tab a{padding-left:28px}#DiContent.page-vacancy .tabs-panel .tab a::before{content:'';display:block;position:absolute;width:21px;height:16px;left:6px;top:6px;background:url(/theme/pic/ico-icons-03.png) 0 -352px no-repeat}#DiContent.page-vacancy .tabs-panel .tab1.active a::before{background-position:0 -352px}#DiContent.page-vacancy .tabs-panel .tab1 a::before{background-position:0 -336px}#DiContent.page-vacancy .tabs-panel .tab2.active a::before{background-position:0 -384px}#DiContent.page-vacancy .tabs-panel .tab2 a::before{background-position:0 -368px}#DiContent.page-vacancy .tabs-panel .tab3.active a::before{background-position:0 -416px}#DiContent.page-vacancy .tabs-panel .tab3 a::before{background-position:0 -400px}#DiContent.page-vacancy .tabs-panel .tab4.active a::before{background-position:0 -448px}#DiContent.page-vacancy .tabs-panel .tab4 a::before{background-position:0 -432px}#DiContent.page-vacancy .vacs{width:100%}#DiContent.page-vacancy .vacs tr.-new a{font-weight:700}#DiContent.page-vacancy .vacs tr a{font-weight:400}#DiContent.page-vacancy .vacs td{padding:5px 10px;vertical-align:middle}#DiContent.page-vacancy .vacs td.fio{white-space:nowrap}#DiContent.page-vacancy .vacs td.checks{width:55px}#DiContent.page-vacancy .vacs .r1 td{border-bottom:1px solid #e3e3e3}#DiContent.page-vacancy .vacs .rdate{margin-right:10px}#DiContent.page-vacancy .vacs .controls,#DiContent.page-vacancy .vacs .controls div{display:inline-block}#DiContent.page-vacancy .vacs .status{font-size:12px;color:#777}#DiContent.page-vacancy .vacs .status.hint{border-bottom:1px dashed #777}#DiContent.page-vacancy .vacs .-bold{font-weight:700}#DiContent.page-vacancy .label-text{float:left}#DiContent.page-vacancy .label-info{padding-left:85px;font-weight:700}#DiContent.page-vacancy .send-mess-block{margin:20px 0}#DiContent.page-vacancy .send-mess-block textarea{display:inline-block;width:100%;height:100px}#DiContent.page-vacancy .message-wrapp{margin-bottom:30px}#DiContent.page-vacancy .message-wrapp.empl{text-align:right}#DiContent.page-vacancy .message-wrapp.empl .message{background:#fff;border:1px solid #e3e3e3;color:#000}#DiContent.page-vacancy .message-wrapp .fio{display:inline-block;font-weight:700}#DiContent.page-vacancy .message-wrapp .date{display:inline-block}#DiContent.page-vacancy .message-wrapp .message{display:inline-block;padding:6px 15px;width:90%;text-align:left;background:#abb820;color:#fff}.page-search-vacancy{position:relative}.psv__veil{position:absolute;top:0;right:0;bottom:0;left:0;z-index:3;background-color:rgba(255,255,255,.7);display:none}.psv__veil:before{width:130px;height:130px;position:absolute;top:150px;left:-65px;background:url(/theme/pic/vacancy/loading.gif) no-repeat;margin-left:50%}.psv__header{padding:20px 0 15px;border-bottom:1px solid #D6D6D6;margin-bottom:20px}.psv__header-name{margin:0 0 20px;display:block;color:#343434;font-size:18px;vertical-align:middle;text-align:center}.psv__header-name:before{display:inline-block;width:27px;height:27px;background:url(/theme/pic/private/vac-list-user-icon.png) no-repeat;vertical-align:middle;margin-right:5px}#DiContent .page-search-vacancy .psv__btn{line-height:30px;margin:0 auto;padding:0;background:#ff8300;color:#FFF;text-align:center;text-transform:uppercase;font-size:14px;position:relative;z-index:1;border:none;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psv__btn:before{position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#ABB837;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psv__btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .page-search-vacancy .psv__header-btn{width:195px}#DiContent .psv__header-title{min-height:19px;margin:0 0 20px;color:#343434;font-size:18px;text-transform:uppercase;font-weight:400;line-height:19px}.psv__view-block{margin-bottom:15px}.psv__view-list,.psv__view-table{width:19px;height:15px;display:block;margin-left:8px;float:right;background:url(/theme/pic/vacancy/srch-vac-sprite.png) no-repeat}.psv__view-list{background-position:0 0}.psv__view-table{background-position:0 -30px}.psv__view-list.active{background-position:0 -15px}.psv__view-table.active{background-position:0 -45px}.psv__nothing{color:#343434;font-size:18px}.psv__list-list{margin-bottom:30px}.psv__list-item{padding:10px 0;margin-bottom:8px;border:1px solid #949494;position:relative}.psv__list-item-premium{border:1px solid #ABB837}.psv-list__logo{width:195px;padding:0 5px 0 10px;float:left}#DiContent .page-search-vacancy .psv-list__logo-name,.psv-list__logo-crdate{padding:10px;border:1px solid #ABB837;color:#444;text-align:center;width:100%;display:block}.psv-list__logo img{display:block;width:100%;border:1px solid #ABB837;border-radius:50%}.psv-list__logo-link{display:block;margin-bottom:7px;min-height:180px;vertical-align:middle;text-align:center}#DiContent .page-search-vacancy .psv-list__logo-name{margin-bottom:7px}.psv-list__logo-crdate{margin-bottom:15px}.psv-list__content{width:100%;padding:0 10px 0 200px}#DiContent .page-search-vacancy .psv-list__title{width:100%;display:block;padding:10px 15px;background-color:#EBEBEB;color:#616161;font-size:16px;text-transform:uppercase}#DiContent .psv__list-item-premium .psv-list__title{padding:10px 125px 10px 15px;background-color:#abb820;color:#FFF;position:relative}.psv__list-item-premium .psv-list__title:after{content:'ПРЕМИУМ';display:block;position:absolute;top:10px;right:13px;padding-left:35px;color:#E5EF90;background:url(/theme/pic/vacancy/srch-vac-sprite.png) 0 -60px no-repeat}.ico1 .psv-list__param-val:after,.psv-list__link:before{right:0;content:'';bottom:0}#DiContent .page-search-vacancy .psv-list__link{display:block;margin:16px auto;width:100%;max-width:220px;line-height:30px;text-align:center;border:1px solid #616161;color:#616160;text-transform:uppercase;position:relative;z-index:1;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}#DiContent .psv__list-item-premium .psv-list__link{border:1px solid #ABB837}.psv-list__link:before{position:absolute;z-index:-1;top:0;left:0;background:#EBEBEB;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psv__list-item-premium .psv-list__link:before{background:#ABB837}#DiContent .page-search-vacancy .psv-list__link:hover{border:1px solid #EBEBEB}#DiContent .psv__list-item-premium .psv-list__link:hover{color:#FFF}.psv-list__link:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}.psv-list__param-block{padding:10px 0;border-bottom:1px solid #D6D6D6}.psv__param{width:100%;min-height:24px;padding-left:40px;margin-bottom:5px;position:relative;color:#343434;line-height:24px;overflow:hidden}.psv__param:before{content:'';width:30px;height:20px;display:block;position:absolute;top:3px;left:5px;background:url(/theme/pic/vacancy/srch-vac-sprite.png) 0 -80px no-repeat}.psv__param.ico1:before{background-position:7px -100px}.psv__param.ico2:before{background-position:0 -140px}.psv__param.ico3:before{background-position:7px -180px}.psv__param.ico4:before{background-position:7px -220px}.psv__param.ico5:before{background-position:7px -260px}.psv__param.ico6:before{background-position:7px -300px}.psv__param.ico7:before{background-position:7px -340px}.psv__list-item-premium .psv__param.ico1:before{background-position:7px -80px}.psv__list-item-premium .psv__param.ico2:before{background-position:0 -120px}.psv__list-item-premium .psv__param.ico3:before{background-position:7px -160px}.psv__list-item-premium .psv__param.ico4:before{background-position:7px -200px}.psv__list-item-premium .psv__param.ico5:before{background-position:7px -240px}.psv__list-item-premium .psv__param.ico6:before{background-position:7px -280px}.psv__list-item-premium .psv__param.ico7:before{background-position:7px -320px}.psv-list__param-name{width:40%;float:left;position:relative}.psv-list__param-name b{display:inline-block;position:relative;background-color:#FFF;padding-right:5px}.psv-list__param-name:before{content:'';width:97%;display:block;position:absolute;top:15px;left:0;border-bottom:1px dotted #343434}.psv-list__param-val{width:60%;float:left;text-align:justify}.ico1 .psv-list__param-val{height:48px;overflow:hidden;position:relative}.ico1 .psv-list__param-val:after{height:24px;position:absolute;left:0;background:0 0;background:-moz-linear-gradient(top,transparent 0,#FFF 100%);background:-webkit-linear-gradient(top,transparent 0,#FFF 100%);background:linear-gradient(to bottom,transparent 0,#FFF 100%)}#DiContent .psv__table-list{margin:0 -4px 30px}#DiContent .psv__table-item{padding:0 4px 8px}.psv__table-block{padding:10px;border:1px solid #949494;border-top:none}.psv__table-premium{border:1px solid #ABB837;border-top:none}#DiContent .page-search-vacancy .psv-table__title{width:100%;min-height:64px;display:block;padding:10px 35px 10px 10px;background-color:#EBEBEB;color:#616161;font-size:16px;text-transform:uppercase;border:1px solid #949494;border-bottom:none}#DiContent .page-search-vacancy .psv-table__title-premium{background-color:#abb820;color:#FFF;border:none}.psv-table__title-premium:after{content:'';width:24px;height:20px;display:block;position:absolute;top:10px;right:10px;color:#E5EF90;background:url(/theme/pic/vacancy/srch-vac-sprite.png) 0 -60px no-repeat}.ico1 .psv-table__param-val:after,.psv-table__link:before,.psv__filter-btn:before{content:'';right:0}.psv-table__param-block{border-bottom:1px solid #D6D6D6}.psv__table-premium .psv__param.ico1:before{background-position:7px -80px}.psv__table-premium .psv__param.ico2:before{background-position:0 -120px}.psv__table-premium .psv__param.ico3:before{background-position:7px -160px}.psv__table-premium .psv__param.ico4:before{background-position:7px -200px}.psv__table-premium .psv__param.ico5:before{background-position:7px -240px}.psv__table-premium .psv__param.ico6:before{background-position:7px -280px}.psv__table-premium .psv__param.ico7:before{background-position:7px -320px}.psv-table__param-block .psv__param{display:block;line-height:normal;padding-left:35px;margin-bottom:10px}.psv-table__param-block .psv__param:before{top:3px;left:0}.psv-table__param-val{text-align:justify}.ico1 .psv-table__param-val{height:57px;overflow:hidden;position:relative}.ico1 .psv-table__param-val:after{height:19px;position:absolute;left:0;bottom:0;background:0 0;background:-moz-linear-gradient(top,transparent 0,#FFF 100%);background:-webkit-linear-gradient(top,transparent 0,#FFF 100%);background:linear-gradient(to bottom,transparent 0,#FFF 100%)}#DiContent .page-search-vacancy .psv-table__link{display:block;width:100%;line-height:30px;text-align:center;border:1px solid #616161;color:#616160;text-transform:uppercase;position:relative;z-index:1;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psv-table__link:before,.psv__filter-btn{-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out}#DiContent .psv__table-premium .psv-table__link{border:1px solid #ABB837}.psv-table__link:before{position:absolute;z-index:-1;top:0;left:0;bottom:0;background:#EBEBEB;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;transition:all .3s ease-out}.psv__table-premium .psv-table__link:before{background:#ABB837}#DiContent .page-search-vacancy .psv-table__link:hover{border:1px solid #EBEBEB}#DiContent .psv__table-premium .psv-table__link:hover{color:#FFF}.psv-table__link:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}.psv-table__logo{width:100%;padding:10px 0;display:table;vertical-align:middle}.psv-table__logo-link{display:table-cell;width:80px}.psv-table__logo-link img{width:70px;min-height:70px;display:block;font-size:8px;text-align:center;vertical-align:middle;border:1px solid #ABB837;border-radius:50%}#DiContent .page-search-vacancy .psv-table__logo-name{display:table-cell;border:1px solid #ABB837;text-align:center;color:#444;vertical-align:middle}.ico2 .psv-table__param-name,.ico2 .psv-table__param-val,.ico3 .psv-table__param-name,.ico3 .psv-table__param-val,.ico4 .psv-table__param-name,.ico4 .psv-table__param-val,.ico5 .psv-table__param-name,.ico5 .psv-table__param-val,.ico6 .psv-table__param-name,.ico6 .psv-table__param-val,.ico7 .psv-table__param-name,.ico7 .psv-table__param-val{display:inline-block}#DiContent.page-vacancy .page-search-vacancy .filter .filter-label.filter-cities{padding-top:0}#DiContent .psv__checkbox-input,#DiContent .psv__radio-input,#psv-additional{display:none}.psv__checkbox-label,.psv__radio-label{display:block;position:relative;height:29px;padding:6px 35px 0 0;cursor:pointer}.psv__checkbox-label:after,.psv__radio-label:after{content:'';display:block;position:absolute;width:30px;height:29px;right:0;top:0;background:url(/theme/pic/ico-form.png) 0 -116px no-repeat}.psv__checkbox-label:hover:after,.psv__radio-label:hover:after{background:url(/theme/pic/ico-form.png) 0 -435px no-repeat}input:checked+.psv__checkbox-label:after,input:checked+.psv__radio-label:after{background-position:0 -87px}.filter-dolj .psv__checkbox-label:nth-child(2){margin-bottom:20px}.page-search-vacancy .select2-container--default .select2-selection--multiple{width:100%;min-height:35px;padding:0 15px;background-color:transparent;border:1px solid #EBEBEB;font-size:14px;color:#646464;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;border-radius:0}#DiContent .page-search-vacancy .psv__input,.psv__content,.psv__salary-name{font-family:RobotoCondensed-Regular,verdana,arial}.page-search-vacancy .select2-container--default.select2-container--focus .select2-selection--multiple{border:1px solid #EBEBEB;outline:0}.page-search-vacancy .select2-container--default .select2-selection--multiple .select2-selection__choice{background-color:rgba(52,52,52,.6);color:#FFF;border-color:#858585}#DiContent .page-search-vacancy .psv__input{height:35px;border:1px solid #EBEBEB;background-color:transparent;padding:0 15px;color:#343434;font-size:14px}.psv__input:focus{outline:0}#DiContent.page-vacancy .page-search-vacancy .filter .filter-content label{font-size:14px;color:#A0A0A0;font-family:RobotoCondensed-Regular,verdana,arial;text-transform:none}.psv__filter-btn{display:block;margin:10px 0 0;width:100px;max-width:220px;line-height:30px;text-align:center;background:#FF8300;color:#FFF;text-transform:uppercase;position:relative;z-index:1;cursor:pointer;float:right;-webkit-transition:all .3s ease-out;transition:all .3s ease-out}.psv__filter-btn:before{position:absolute;z-index:-1;top:0;left:0;bottom:0;background:#BBC823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psv__filter-btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}.psv__age,.psv__salary{width:100%;display:table;margin-bottom:5px}.psv__salary-name{width:90px;display:table-cell;font-size:14px;color:#A0A0A0;vertical-align:middle;line-height:35px;text-align:left}.psv__salary-block{width:calc(100% - 90px);display:table-cell}#DiContent.page-vacancy .page-search-vacancy .psv__age-label,#DiContent.page-vacancy .page-search-vacancy .psv__salary-label{width:50%;float:left;padding-left:35px;position:relative}#DiContent .page-search-vacancy .psv__age-label .psv__input,#DiContent .page-search-vacancy .psv__salary-block .psv__input{width:100%;padding:0 5px}.psv__age-label span,.psv__salary-label span{width:30px;text-align:right;position:absolute;left:0;top:0;line-height:35px}#DiContent.page-vacancy .filter-age .psv__age-label .psv__input{width:100%;text-align:left;padding:0 15px}.psv__content{margin-top:0;font-size:16px}.filter-cities .filter-content.active{opacity:1}#DiContent.page-vacancy .page-search-vacancy .filter .filter-name{border:1px solid #e3e3e3}#DiContent.page-vacancy .filter-dolj .filter-content{height:170px;position:relative;overflow:hidden}.more-posts{width:100%;position:absolute;bottom:0;color:#abb820;cursor:pointer;text-align:right;padding-right:55px;line-height:45px;font-size:16px;background:0 0;background:-moz-linear-gradient(top,transparent 0,#FFF 50%,#FFF 100%);background:-webkit-linear-gradient(top,transparent 0,#FFF 50%,#FFF 100%);background:linear-gradient(to bottom,transparent 0,#FFF 50%,#FFF 100%)}.more-posts:hover{font-weight:700}@media (min-width:768px){.psv__header{padding:20px 0 15px 80px}.psv__header-name{margin:0;display:inline-block;text-align:left}#DiContent .page-search-vacancy .psv__header-btn{margin:0 30px;display:inline-block}}@media (min-width:768px) and (max-width:991px){.psv-list__logo{width:135px}.psv-list__content{padding:0 10px 0 140px}}@media (min-width:768px) and (max-width:1199px){.psv__separator2{clear:both}}.psv__filter-vis{text-align:center;margin-bottom:20px;border:3px solid #abb820;cursor:pointer;color:#616161;line-height:35px;position:relative}.psv__filter-vis:before,.psv__filter-vis:after{content:'';width:0;height:0;display:block;position:absolute;top:10px;border-left:20px solid transparent;border-right:20px solid transparent;border-bottom:15px solid #abb820;}.psv__filter-vis:before{left:10px}.psv__filter-vis:after{right:10px}.psv__filter-vis.active:before,.psv__filter-vis.active:after{border-bottom:initial;border-top:15px solid #abb820;}
		.select-list{max-height:300px;overflow-y:auto;padding:0;margin:0;border-top:none;list-style:none;background-color:rgba(52,52,52,.6);position:absolute;top:100%;left:-1px;right:-1px;z-index:2;font-family:RobotoCondensed-Regular,verdana,arial;font-size:14px;color:#FFF}.select-list li{width:100%;line-height:30px;padding:3px 6px;cursor:pointer;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.select-list li:hover{background-color:rgba(255,255,255,.2)}#filter-city{position:relative}#filter-city .select-list li{padding:0 15px;text-align:left}#DiContent #filter-city .filter-city-select input{padding:0;border:none;background:0 0;margin:2px 0 2px 6px;height:29px}#DiContent #filter-city .filter-city-select input:focus{outline:0}.filter-city-select.load:after{content:'';width:20px;height:20px;right:3px;background:url(/theme/pic/loading1.gif) no-repeat;background-size:cover;top:7px;position:absolute}.city-select,.filter-city-select li:not([data-id="0"]){display:inline-block;padding:3px 20px 3px 5px;margin:2px 0 2px 6px;background-color:rgba(52,52,52,.6);color:#fff;line-height:18px;border-radius:5px;position:relative}.filter-city-select li:not([data-id="0"]){line-height:23px}.filter-city-select li[data-id="0"]{width:10px}.filter-city-select{display:flex;flex-direction:row;justify-content:start;flex-wrap:wrap;margin:0;list-style:none;border:1px solid #EBEBEB;position:relative;padding:0 25px 0 15px}.city-select b,.filter-city-select b{width:19px;height:19px;display:block;position:absolute;top:2px;right:0;font-style:normal;text-align:center;cursor:pointer}.filter-city-select b{top:5px}.city-select b:before,.filter-city-select b:before{content:'\2716';display:block;position:absolute;top:0;right:0;bottom:0;left:0;line-height:20px}#DiContent .project__index-time input{text-align:center;padding:0 16px 0 6px}
			.psv-list__content-btns{display: flex;}.psv-table__link.psv-list__responce-btn{ margin-top: 15px }

/* fix size 22.04.2019*/
#DiContent .psv__table-list {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: flex-start;
    align-content: stretch;
    align-items: stretch;
}

#DiContent .page-search-vacancy .psv-table__title {
    min-height: auto;
    position: relative;
    overflow: hidden;
    flex: 1 1 auto;
}

#DiContent .psv__table-item {padding: 0 4px 8px}

#DiContent .psv__table-item-wrap {
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

#DiContent .psv-table__city {
    display: block;
    position: relative;
    z-index: 2;
}

#DiContent .psv-table__city:after {
    content: attr(data-city);
    display: block;
    position: absolute;
    width: 100%;
    height: auto;
    top: 10px;
    opacity: 0;
    transition: all .1s cubic-bezier(0.47, 0, 0.745, 0.715);
    background: white;
    padding: 5px;
    border: 1px solid grey;
}

.psv__table-block {
    display: flex;
    flex-direction: column;
}

.psv-table__param-block {
    flex: 1 1 auto;
}

.psv-table__param-block .psv__param.ico3:hover {
    overflow: visible;
}

#DiContent .psv__param.ico3:hover .psv-table__city:after {
    content: attr(data-city);
    position: absolute;
    width: 100%;
    height: auto;
    top: 0;
    opacity: 1;
    transition: all .1s cubic-bezier(0.47, 0, 0.745, 0.715);
    z-index: 10;

}

/* end fix */
</style>
<?php 
	// если не моб устройство
	//endif; 
?>
<div class='row page-search-vacancy'>
    <div class="psv__veil"></div>
    <div class="col-xs-12">        
        <?php if(Share::$UserProfile->type == 3): ?>
            <div class="psv__header">
                <h1 class="psv__header-name"><?=Share::$UserProfile->exInfo->name?></h1>
                <a class='psv__btn psv__header-btn' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
            </div>
        <?php endif; ?>       
    </div>
    <div class="col-xs-12 col-sm-4 col-md-3">
    	<div class="psv__filter-vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
        <form action="" id="F1Filter" method="get">
            <div class='filter'>
                <div class='filter-label filter-cities clearfix'>
                    <label class='filter-name opened'>Город</label>
                    <div class='filter-content opened'>
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
									<? foreach ($_GET['cities'] as $key => $id): ?>
									<li>
										<?=$_GET['template_url_params']['cities'][$key]?>
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
                <div class='filter-label filter-dolj'>
                    <label class='filter-name opened<?//= $viData['selected']['posts'] || $viData['poself'] ? 'opened' : '' ?>'>Должность</label>
                    <div class='filter-content opened<?//= $viData['selected']['posts'] || $viData['poself']? 'opened' : '' ?>'>
                        <div class='right-box'>
                        	<?php
                        		$sel = 0;
                        		foreach($viData['posts'] as $p) 
                        			if($p['selected']) $sel++;
                        	?>
                            <input id='psv-posts-all' name='poall' type='checkbox' class="psv__checkbox-input"<?=sizeof($viData['posts'])==$sel ?' checked':''?>>
                            <label class='psv__checkbox-label psv__posts-all' for="psv-posts-all">Выбрать все / снять все</label>
                            <?php foreach($viData['posts'] as $val): ?>
                                <input name='post[<?= $val['id'] ?>]' type='checkbox' id="psv-posts-<?=$val['id']?>" class="psv__checkbox-input"<?= $val['selected'] ? ' checked' : '' ?>>
                                <label class='psv__checkbox-label' for="psv-posts-<?=$val['id']?>"><?= $val['name'] ?></label>
                            <?php endforeach; ?>
                        </div>
                        <div class='self-dolj'>
                            <label>Свой вариант</label>
                            <input name='poself' type='text' value="<?= $viData['poself'] ?>" class="psv__input">
                            <div class="psv__filter-btn">ОК</div>
                            <div class="clearfix"></div>
                        </div>
                        <span class="more-posts">Показать все</span>
                    </div>
                </div>

                <div class='filter-label filter-busy'>
                    <label class='filter-name opened<?//= $viData['bt'] == '1' || $viData['bt'] == '2' ? 'opened' : '' ?>'>Вид занятости</label>
                    <div class='filter-content opened<?//= $viData['bt'] == '1' || $viData['bt'] == '2' ? 'opened' : '' ?>'>
                        <div class='radio-box right-box'>
                            <input id='RB1busy' name='bt' type='radio' value='1' <?= $viData['bt'] == '1' ? 'checked' : '' ?> class="psv__radio-input">
                            <label for='RB1busy' class='psv__radio-label'>Временная</label>
                            <input id='RB2busy' name='bt' type='radio' value='2' <?= $viData['bt'] == '2' ? 'checked' : '' ?> class="psv__radio-input">
                            <label for='RB2busy' class='psv__radio-label'>Постоянная</label>
                            <input id='RB3busy' name='bt' type='radio' value='3' <?= $viData['bt'] == '3' || empty($viData['bt']) ? 'checked' : '' ?> class="psv__radio-input">
                            <label for='RB3busy' class='psv__radio-label'>Не важно</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-salary'>
                    <?php //$flag = $viData['sphf'] || $viData['spht'] || $viData['spwf'] || $viData['spwt'] || $viData['spmf'] || $viData['spmt'] ?>
                    <label class='filter-name opened<?//= $flag ? 'opened' : '' ?>'>Заработная плата</label>
                    <div class='filter-content opened<?//= $flag ? 'opened' : '' ?>'>
                        <div class="psv__salary">
                            <span class="psv__salary-name">В час</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=sphf type='text' value="<?=($viData['sr']==1 ? $viData['sphf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spht' type='text' value="<?=($viData['sr']==1 ? $viData['spht'] : '')?>" class="psv__input">
                                </label> 
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psv__salary">
                            <span class="psv__salary-name">В неделю</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=spwf type='text' value="<?=($viData['sr']==2 ? $viData['spwf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spwt' type='text' value="<?=($viData['sr']==2 ? $viData['spwt'] : '')?>" class="psv__input">
                                </label> 
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psv__salary">
                            <span class="psv__salary-name">В месяц</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=spmf type='text' value="<?=($viData['sr']==3 ? $viData['spmf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spmt' type='text' value="<?=($viData['sr']==3 ? $viData['spmt'] : '')?>" class="psv__input">
                                </label> 
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psv__salary">
                            <span class="psv__salary-name">За посещение</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=spvf type='text' value="<?=($viData['sr']==4 ? $viData['spvf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spvt' type='text' value="<?=($viData['sr']==4 ? $viData['spvt'] : '')?>" class="psv__input">
                                </label> 
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <input id='psv-salary-type' name='sr' type='hidden' value="<?=($viData['sr'] ? $viData['sr'] : 1)?>">
                        <div class="psv__filter-btn">ОК</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class='filter-label filter-sex'>
                    <label class='filter-name opened<?//= $viData['sex'] == '1' || $viData['sex'] == '2' ? 'opened' : '' ?>'>Пол</label>
                    <div class='filter-content opened<?//= $viData['sex'] == '1' || $viData['sex'] == '2' ? 'opened' : '' ?>'>
                        <div class='radio-box right-box'>
                            <input name='sex' type='radio' value='1' class="psv__radio-input" id="psv-sex-m" <?= $viData['sex'] == '1' ? 'checked' : '' ?>>
                            <label class="psv__radio-label" for="psv-sex-m">Мужской</label>

                            <input name='sex' type='radio' value='2' class="psv__radio-input" id="psv-sex-w" <?= $viData['sex'] == '2' ? 'checked' : '' ?>>
                            <label class="psv__radio-label" for="psv-sex-w">Женский </label>

                            <input name='sex' type='radio' value='3' class="psv__radio-input" id="psv-sex-n" <?= $viData['sex'] == '3' || empty($viData['sex']) ? 'checked' : '' ?>>
                            <label class="psv__radio-label" for="psv-sex-n">Не важно</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-age'>
                    <label class='filter-name opened<?//= $viData['af'] || $viData['at'] ? 'opened' : '' ?>'>Возраст</label>
                    <div class='filter-content opened<?//= $viData['af'] || $viData['at'] ? 'opened' : '' ?>'>
                        <div class="psv__age">
                            <label class="psv__age-label">
                                <span>от</span>
                                <input name=af type='text' value="<?= $viData['af'] ?>" class="psv__input">
                            </label>
                            <label class="psv__age-label">
                                <span>до</span>
                                <input name='at' type='text' value="<?= $viData['at'] ?>" class="psv__input">
                            </label> 
                            <div class="clearfix"></div>
                            <div class="psv__filter-btn">ОК</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-smart'>
                    <label class='filter-name opened'>Смартфон</label>
                    <div class='filter-content opened'>          
                        <div class='radio-box right-box'>
                            <input id='psv-smart' name='smart' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('smart') ? 'checked' : '' ?> class="psv__checkbox-input">
                            <label class='psv__checkbox-label' for="psv-smart">Наличие смартфона</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-card'>
                    <label class='filter-name opened'>Карта</label>
                    <div class='filter-content opened'>
                        <div class='radio-box right-box'>
                            <input id='psv-pcard' name='pcard' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('pcard') ? 'checked' : '' ?> class="psv__checkbox-input">
                            <label class='psv__checkbox-label' for="psv-pcard">Банковская карта Prommu</label>
                            <input id='psv-card' name='bcard' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('bcard') ? 'checked' : '' ?> class="psv__checkbox-input">
                            <label class='psv__checkbox-label' for="psv-card">Банковская карта</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>        
    </div>
    <div class="col-xs-12 col-sm-8 col-md-9" id="content">
        <?php if( !count($viData['vacs']) ): ?>
            <div class="psv__nothing">Нет подходящих вакансий</div>
        <?php else: ?>
            <div class='psv__view-block hidden-xs'>
                <a class='psv__view-table <?=($cookieView=='table'?'active':'')?> js-g-hashint' href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'table') ?>' title='Отображать таблицей'></a>
                <a class="psv__view-list <?=($cookieView=='list'?'active':'')?> js-g-hashint" href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'list') ?>' title='Отображать списком'></a>
                <div class="clearfix"></div>
            </div>
            <?php // BM: list view ?>
            <?php if ($cookieView == 'list'): ?>
                <div class='psv__list-list hidden-xs hidden-sm'>
                    <?php foreach ($viData['vacs'] as $key => $vac): ?>
                        <div class="psv__list-item <?=($vac['ispremium']?'psv__list-item-premium':'')?>">
                            <div class="psv-list__logo">
                                <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-list__logo-link">
                                    <img 
                                        alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                        src="<?=Share::getPhoto(3, $vac['logo'], 'medium')?>">
                                </a>
                                <a class="psv-list__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>

                                <span class="psv-list__logo-crdate js-g-hashint" title="Дата публикации">Дата публикации: <?=$vac['crdate']?></span>
                            </div>
                            <div class="psv-list__content">
                                <a href="<?= MainConfig::$PAGE_VACANCY . DS . $vac['id'] ?>" class="psv-list__title">
                                    <?= $vac['posts'] ? join(', ', $vac['posts']) : '' ?>
                                    <?php if( $vac['shour'] > 0 || $vac['sweek'] > 0 || $vac['smonth'] > 0 || $vac['svisit'] > 0 ): ?>
                                        <?php if($vac['shour'] > 0): ?>
                                            - <span class="payment"><?=$vac['shour'] . ' руб/час'?></span>
                                        <?php elseif($vac['sweek'] > 0): ?>
                                            - <span class="payment"><?=$vac['sweek'] . ' руб/неделю'?></span>
                                        <?php elseif($vac['smonth'] > 0): ?>
                                            - <span class="payment"><?=$vac['smonth'] . ' руб/мес'?></span>
		                                <?php elseif($vac['svisit'] > 0): ?>
		                                    - <span class="payment"><?=$vac['svisit'] . ' руб/посещение'?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </a>
                                <div class="psv-list__param-block">
                                    <div class="psv__param ico1">
                                        <div class="psv-list__param-name">
                                            <b>Краткое описание вакансии:</b>
                                        </div>
                                        <div class="psv-list__param-val"><?=$vac['title']?></div>
                                    </div>
                                    <?  // эти константы взяты наобум по стилям   
                                        if($vac['isman']) $sex = 6;
                                        if($vac['iswoman']) $sex = 7; 
                                        if($vac['isman'] && $vac['iswoman']) $sex = 2;
                                    ?>
                                    <div class="psv__param ico<?=$sex?>">
                                        <div class="psv-list__param-name">
                                            <b>Пол:</b>
                                        </div>
                                        <div class="psv-list__param-val"><?
                                        if($sex==2) echo "Мужчины и женщины";
                                        elseif($sex==6) echo "Мужчины";
                                        else echo "Женщины";
                                        ?></div>
                                    </div>
                                    <div class="psv__param ico3">
                                        <div class="psv-list__param-name">
                                            <b>Город:</b>
                                        </div>
                                        <div class="psv-list__param-val"><?=join(', ', $vac['city'])?></div>
                                    </div>
                                    <div class="psv__param ico4">
                                        <div class="psv-list__param-name">
                                            <b>Вид занятости:</b>
                                        </div>
                                        <div class="psv-list__param-val"><?=$vac['istemp'] ? 'Постоянная' : 'Временная'?></div>
                                    </div>
                                    <div class="psv__param ico5">
                                        <div class="psv-list__param-name">
                                            <b>Открыта по:</b>
                                        </div>
                                        <div class="psv-list__param-val"><?=$vac['remdate']?></div>
                                    </div>                                    
                                </div>
                                <div class="psv-list__content-btns">
	                                <a href="<?=MainConfig::$PAGE_VACANCY . DS . $vac['id']?>" class="psv-list__link">Подробнее</a>
	                                <? if(Share::isApplicant()): ?>
	                                	<a href="javascript:void(0)" class="psv-list__link psv-list__responce-btn" data-id="<?=$vac['id']?>">ОТКЛИКНУТЬСЯ</a>
	                                <? endif; ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endforeach;?>
                    <script type="text/javascript">
                        var $G_PAGE_VIEW = 2;
                        var G_DEF_LOGO = '<?= MainConfig::$DEF_LOGO_EMPL ?>';
                    </script>
                </div>
                <? // mob version ?>
                <div class="row psv__table-list hidden-md hidden-lg hidden-xl">
                    <?php $cnt=1; ?>
                    <?php foreach($viData['vacs'] as $key => $vac): ?>
                        <div class="col-xs-12 col-sm-6 psv__table-item">
                            <a href="<?= MainConfig::$PAGE_VACANCY . DS . $vac['id'] ?>" class="psv-table__title<?=($vac['ispremium']?' psv-table__title-premium js-g-hashint" title="Премиум вакансия"':'"')?>><?php 
                                if( $vac['shour'] > 0 || $vac['sweek'] > 0 || $vac['smonth'] > 0 || $vac['svisit'] > 0 ){
                                    if($vac['shour'] > 0)
                                        echo join(', ', $vac['posts']) . " - " . $vac['shour'] . ' руб/час';
                                    elseif($vac['sweek'] > 0)
                                        echo join(', ', $vac['posts']) . " - " . $vac['sweek'] . ' руб/неделю';
                                    elseif($vac['smonth'] > 0)
                                        echo join(', ', $vac['posts']) . " - " . $vac['smonth'] . ' руб/мес';
                                    elseif($vac['svisit'] > 0)
                                        echo join(', ', $vac['posts']) . " - " . $vac['svisit'] . ' руб/посещение';
                                }?>
                            </a>
                            <div class="psv__table-block <?=($vac['ispremium']?'psv__table-premium':'')?>">
                                <div class="psv-table__param-block">

                                    <?  // эти константы взяты наобум по стилям   
                                        if($vac['isman']) $sex = 6;
                                        if($vac['iswoman']) $sex = 7; 
                                        if($vac['isman'] && $vac['iswoman']) $sex = 2;
                                    ?>
                                    <div class="psv__param ico<?=$sex?>">
                                        <div class="psv-table__param-name">
                                            <b>Пол:</b>
                                        </div>
                                        <div class="psv-table__param-val"><?
                                        if($sex==2) echo "Мужчины и женщины";
                                        elseif($sex==6) echo "Мужчины";
                                        else echo "Женщины";
                                        ?></div>
                                    </div>
                                    <div class="psv__param ico3">
                                        <div class="psv-table__param-name">
                                            <b>Город:</b>
                                        </div>
                                        <div class="psv-table__param-val"><?=join(', ', $vac['city'])?></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="psv__param ico4">
                                        <div class="psv-table__param-name">
                                            <b>Вид занятости:</b>
                                        </div>
                                        <div class="psv-table__param-val"><?=$vac['istemp'] ? 'Постоянная' : 'Временная'?></div>
                                    </div>
                                    <div class="psv__param ico5">
                                        <div class="psv-table__param-name">
                                            <b>Открыта по:</b>
                                        </div>
                                        <div class="psv-table__param-val"><?=$vac['remdate']?></div>
                                    </div>
                                    <div class="psv__param ico1">
                                        <div class="psv-table__param-name">
                                            <b>Краткое описание вакансии:</b>
                                        </div>
                                        <div class="psv-table__param-val"><?=$vac['title']?></div>
                                    </div>
                                </div>
                                <div class="psv-table__logo">
                                    <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-table__logo-link">
                                        <img 
                                            alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                            src="<?=Share::getPhoto(3, $vac['logo'], 'medium')?>">
                                    </a>
                                    <a class="psv-table__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                                <a href="<?=MainConfig::$PAGE_VACANCY . DS . $vac['id']?>" class="psv-table__link">Подробнее</a>
                                <a href="javascript:void(0)" class="psv-table__link psv-list__responce-btn" data-id="<?=$vac['id']?>">ОТКЛИКНУТЬСЯ</a>
                            </div>                            
                        </div>
                        <?php if( $cnt % 2 == 0 ): ?>
                            <div class="psv__separator2"></div>
                        <?php endif; ?>
                        <?php if( $cnt % 3 == 0 ): ?>
                            <div class="psv__separator3"></div>
                        <?php endif; ?>
                        <?php $cnt++; ?>
                    <?php endforeach; ?>
                </div>
            <?php // BM: table view  ?>
            <?php else: ?>
                <div class="row psv__table-list">
                    <?php $cnt=1; ?>
                    <?php foreach($viData['vacs'] as $key => $vac): ?>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 psv__table-item">
                            <div class="psv__table-item-wrap">
                                <a href="<?= MainConfig::$PAGE_VACANCY . DS . $vac['id'] ?>" class="psv-table__title<?=($vac['ispremium']?' psv-table__title-premium js-g-hashint" title="Премиум вакансия"':'"')?>><?php
                                    if( $vac['shour'] > 0 || $vac['sweek'] > 0 || $vac['smonth'] > 0 || $vac['svisit'] > 0 ){
                                        if($vac['shour'] > 0)
                                            echo join(', ', $vac['posts']) . " - " . $vac['shour'] . ' руб/час';
                                        elseif($vac['sweek'] > 0)
                                            echo join(', ', $vac['posts']) . " - " . $vac['sweek'] . ' руб/неделю';
                                        elseif($vac['smonth'] > 0)
                                            echo join(', ', $vac['posts']) . " - " . $vac['smonth'] . ' руб/мес';
                                        elseif($vac['svisit'] > 0)
                                            echo join(', ', $vac['posts']) . " - " . $vac['svisit'] . ' руб/посещение';
                                    }?>
                                </a>
                                <div class="psv__table-block <?=($vac['ispremium']?'psv__table-premium':'')?>">
                                    <div class="psv-table__param-block">
                                        <div class="psv__param ico1">
                                            <div class="psv-table__param-name">
                                                <b>Краткое описание вакансии:</b>
                                            </div>
                                            <div class="psv-table__param-val"><?=$vac['title']?></div>
                                        </div>
                                        <?  // эти константы взяты наобум по стилям
                                            if($vac['isman']) $sex = 6;
                                            if($vac['iswoman']) $sex = 7;
                                            if($vac['isman'] && $vac['iswoman']) $sex = 2;
                                        ?>
                                        <div class="psv__param ico<?=$sex?>">
                                            <div class="psv-table__param-name">
                                                <b>Пол:</b>
                                            </div>
                                            <div class="psv-table__param-val"><?
                                            if($sex==2) echo "Мужчины и женщины";
                                            elseif($sex==6) echo "Мужчины";
                                            else echo "Женщины";
                                            ?></div>
                                        </div>
                                        <div class="psv__param ico3">
                                            <div class="psv-table__param-name">
                                                <b>Город:</b>
                                            </div>
                                            <div class="psv-table__param-val psv-table__city" data-city="<?=join(', ', $vac['city'])?>">
                                                <?php
                                                    echo join(', ', array_slice($vac['city'], 0, 3));
                                                ?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="psv__param ico4">
                                            <div class="psv-table__param-name">
                                                <b>Вид занятости:</b>
                                            </div>
                                            <div class="psv-table__param-val"><?=$vac['istemp'] ? 'Постоянная' : 'Временная'?></div>
                                        </div>
                                        <div class="psv__param ico5">
                                            <div class="psv-table__param-name">
                                                <b>Открыта по:</b>
                                            </div>
                                            <div class="psv-table__param-val"><?=$vac['remdate']?></div>
                                        </div>
                                    </div>
                                    <div class="psv-table__logo">
                                        <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-table__logo-link">
                                            <img
                                                alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                                src="<?=Share::getPhoto(3, $vac['logo'], 'medium')?>">
                                        </a>
                                        <a class="psv-table__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <a href="<?=MainConfig::$PAGE_VACANCY . DS . $vac['id']?>" class="psv-table__link">Подробнее</a>
                                    <a href="javascript:void(0)" class="psv-table__link psv-list__responce-btn" data-id="<?=$vac['id']?>">ОТКЛИКНУТЬСЯ</a>
                                </div>
                            </div>
                        </div>
                        <?php if( $cnt % 2 == 0 ): ?>
                            <div class="psv__separator2"></div>
                        <?php endif; ?>
                        <?php if( $cnt % 3 == 0 ): ?>
                            <div class="psv__separator3"></div>
                        <?php endif; ?>
                        <?php $cnt++; ?>
                    <?php endforeach; ?>
                </div>
                <script type="text/javascript"> var $G_PAGE_VIEW = 1;</script>
            <?php endif; ?>
            <div class='paging-wrapp'>
                <?php
                    // display pagination
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
            </div>      
        <?php endif; ?>
    </div>
    <div class='col-xs-12' id="psv-seo-text" class="psv__content"><?php 
        if($this->ViewModel->getViewData()->pageH1)
            echo $this->ViewModel->getViewData()->pageMetaKeywords;
        elseif(isset($seo['meta_keywords']))
            echo $seo['meta_keywords'];
    ?></div>
</div>