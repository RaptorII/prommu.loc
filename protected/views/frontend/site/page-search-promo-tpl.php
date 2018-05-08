<?php 
	$cookieView = Yii::app()->request->cookies['srch_a_view']->value; // вид, сохраненный в куках
	if(!(MOBILE_DEVICE && !SHOW_APP_MESS)): 
?>
	<?php
		Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-promo-search.min.js', CClientScript::POS_END);
		Yii::app()->getClientScript()->registerScriptFile('/theme/js/select2.min.js', CClientScript::POS_END);
	?>
	<style type="text/css">
		/*	/theme/css/page-promo-search.css	*/
		.psa__btn:before,.psa__checkbox-label:after,.psa__filter-btn:before,.psa__header-name:before,.psa__veil:before{content:''}#psa-additional,.psa__veil{display:none}.page-search-ankety .select2-container--default .select2-selection--multiple{width:100%;min-height:35px;padding:0 15px;background-color:transparent;border:1px solid #EBEBEB;font-size:14px;color:#646464;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;border-radius:0}#DiContent .page-search-ankety .psa__input,#DiContent .psa__checkbox-label,.psa__content{font-family:RobotoCondensed-Regular,verdana,arial}.page-search-ankety .select2-container--default.select2-container--focus .select2-selection--multiple{border:1px solid #EBEBEB;outline:0}.page-search-ankety .select2-container--default .select2-selection--multiple .select2-selection__choice{background-color:rgba(52,52,52,.6);color:#FFF;border-color:#858585}#DiContent.page-vacancy .page-search-ankety .filter .filter-name,.psa__filter-name{border:1px solid #e3e3e3}#DiContent.page-vacancy .page-search-ankety .filter .filter-content label{font-size:14px;color:#A0A0A0;font-family:RobotoCondensed-Regular,verdana,arial;text-transform:none}.psa__filter-btn,.psa__filter-name{text-transform:uppercase;cursor:pointer}.page-search-ankety{position:relative}.psa__veil{position:absolute;top:0;right:0;bottom:0;left:0;z-index:3;background-color:rgba(255,255,255,.7)}.psa__veil:before{width:130px;height:130px;position:absolute;top:150px;left:-65px;background:url(/theme/pic/vacancy/loading.gif) no-repeat;margin-left:50%}.psa__nothing{color:#343434;font-size:18px}.psa__view-block{margin-bottom:15px}.psa__view-list,.psa__view-table{width:19px;height:15px;display:block;margin-left:8px;float:right;background:url(/theme/pic/vacancy/srch-vac-sprite.png) no-repeat}.psa__view-list{background-position:0 0}.psa__view-table{background-position:0 -30px}.psa__view-list.active{background-position:0 -15px}.psa__view-table.active{background-position:0 -45px}.psa__filter-name{display:block;position:relative;margin-bottom:10px;padding:5px 15px;font-size:14px}.psa__filter-name.opened{background:#e3e3e3;border-color:#e3e3e3}.psa__filter-name:not(.opened):hover{border-color:#abb820}.psa__filter-name:after{content:' ';position:absolute;right:15px;top:0;border:10px solid transparent;border-bottom:9px solid #e3e3e3}.psa__filter-name.opened:after{bottom:0;top:auto;border:10px solid transparent;border-top:9px solid #fff}.psa__filter-content{display:none;padding-bottom:35px;text-align:center}.psa__filter-content.opened{display:block}.filter-cities .psa__filter-content{opacity:0;padding-right:1px}.filter-cities .psa__filter-content.active{opacity:1}#DiContent .page-search-ankety .psa__input{height:35px;border:1px solid #EBEBEB;background-color:transparent;padding:0 15px;color:#343434;font-size:14px}.psa__input:focus{outline:0}.psa__filter-btn{display:block;margin:10px 0 0;width:100px;max-width:220px;line-height:30px;text-align:center;background:#FF8300;color:#FFF;position:relative;z-index:1;float:right;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}#DiContent .page-search-ankety .psa__btn,.psa__filter-btn:before{-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out}.psa__filter-btn:before{position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#BBC823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;transition:all .3s ease-out}.psa__filter-btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .psa__checkbox-input{display:none}#DiContent .psa__checkbox-label{display:block;position:relative;height:29px;line-height:29px;padding-right:35px;cursor:pointer;font-size:14px;color:#A0A0A0;text-transform:none}.psa__checkbox-label:after{display:block;position:absolute;width:30px;height:29px;right:0;top:0;background:url(/theme/pic/ico-form.png) 0 -116px no-repeat}.psa__checkbox-label:hover:after{background:url(/theme/pic/ico-form.png) 0 -435px no-repeat}input:checked+.psa__checkbox-label:after{background-position:0 -87px}.psa__content{margin-top:0;font-size:16px}#DiContent.page-ankety .page-search-ankety .filter{display:block}.filter-positions .psa__filter-content.opened{height:170px;position:relative;overflow:hidden}.more-posts{width:100%;position:absolute;bottom:0;left:0;color:#abb820;cursor:pointer;text-align:right;padding-right:55px;line-height:45px;font-size:16px;background:0 0;background:-moz-linear-gradient(top,transparent 0,#FFF 50%,#FFF 100%);background:-webkit-linear-gradient(top,transparent 0,#FFF 50%,#FFF 100%);background:linear-gradient(to bottom,transparent 0,#FFF 50%,#FFF 100%)}.more-posts:hover{font-weight:700}.psa__header{padding:20px 0 15px;border-bottom:1px solid #D6D6D6;margin-bottom:20px}.psa__header-name{margin:0 0 20px;display:block;color:#343434;font-size:18px;text-decoration:underline;vertical-align:middle;text-align:center}.psa__header-name:before{display:inline-block;width:27px;height:27px;background:url(/theme/pic/private/vac-list-user-icon.png) no-repeat;vertical-align:middle;margin-right:5px}#DiContent .page-search-ankety .psa__btn{line-height:30px;display:block;margin:0 auto;padding:0;background:#ff8300;color:#FFF;text-align:center;text-transform:uppercase;font-size:14px;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;position:relative;z-index:1;border:none;transition:all .3s ease-out}.psa__btn:before{position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#ABB837;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psa__btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .page-search-ankety .psa__header-btn{width:195px}.psa__header-title{min-height:19px;margin:0 0 20px;display:block;color:#343434;font-size:18px}.filter-positions .psa__checkbox-label:nth-child(2){margin-bottom:20px}@media (min-width:768px){.psa__header{padding:20px 0 15px 80px}.psa__header-name{margin:0;display:inline-block;text-align:left}#DiContent .page-search-ankety .psa__header-btn{margin:0 30px;display:inline-block}}.psa__filter-vis{text-align:center;margin-bottom:20px;border:3px solid #abb820;cursor:pointer;color:#616161;line-height:35px;position:relative}.psa__filter-vis:before,.psa__filter-vis:after{content:'';width:0;height:0;display:block;position:absolute;top:10px;border-left:20px solid transparent;border-right:20px solid transparent;border-bottom:15px solid #abb820;}.psa__filter-vis:before{left:10px}.psa__filter-vis:after{right:10px}.psa__filter-vis.active:before,.psa__filter-vis.active:after{border-bottom:initial;border-top:15px solid #abb820;}#DiContent .table-view .comm-logo>a:first-child{display:block;border-radius:50%;border:2px solid #CBD880;position:relative;overflow:hidden}.promo-list__item-onl{width:80%;height:50px;position:absolute;left:10%;bottom:-24px;background-color:#fff;border-radius:50%;border:1px solid #abb820}.promo-list__item-onl span,.promo-list__item-onl span:hover{color:#abb820;font-size:12px;font-weight:700;position:relative;padding-right:13px}.promo-list__item-onl span:after{content:'';width:10px;height:10px;position:absolute;right:0;top:4px;display:block;border-radius:50%;background-color:#abb820}#DiContent .company-logo>a{border-radius:50%;border:2px solid #abb820;overflow:hidden;display:block;position:relative}
			/*      /theme/css/select2.min.css      */
        	.select2-container{box-sizing:border-box;display:inline-block;margin:0;position:relative;vertical-align:middle;}.select2-container .select2-selection--single{box-sizing:border-box;cursor:pointer;display:block;height:28px;user-select:none;-webkit-user-select:none;}.select2-container .select2-selection--single .select2-selection__rendered{display:block;padding-left:8px;padding-right:20px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}.select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered{padding-right:8px;padding-left:20px;}.select2-container .select2-selection--multiple{box-sizing:border-box;cursor:pointer;display:block;min-height:32px;user-select:none;-webkit-user-select:none;}.select2-container .select2-selection--multiple .select2-selection__rendered{display:inline-block;overflow:hidden;padding-left:8px;text-overflow:ellipsis;white-space:nowrap;}.select2-container .select2-search--inline{float:left;}.select2-container .select2-search--inline .select2-search__field{box-sizing:border-box;border:none;font-size:100%;margin-top:5px;}.select2-container .select2-search--inline .select2-search__field::-webkit-search-cancel-button{-webkit-appearance:none;}.select2-dropdown{background-color:white;border:1px solid #aaa;border-radius:4px;box-sizing:border-box;display:block;position:absolute;left:-100000px;width:100%;z-index:1051;}.select2-results{display:block;}.select2-results__options{list-style:none;margin:0;padding:0;}.select2-results__option{padding:6px;user-select:none;-webkit-user-select:none;}.select2-results__option[aria-selected]{cursor:pointer;}.select2-container--open .select2-dropdown{left:0;}.select2-container--open .select2-dropdown--above{border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;}.select2-container--open .select2-dropdown--below{border-top:none;border-top-left-radius:0;border-top-right-radius:0;}.select2-search--dropdown{display:block;padding:4px;}.select2-search--dropdown .select2-search__field{padding:4px;width:100%;box-sizing:border-box;}.select2-search--dropdown .select2-search__field::-webkit-search-cancel-button{-webkit-appearance:none;}.select2-search--dropdown.select2-search--hide{display:none;}.select2-close-mask{border:0;margin:0;padding:0;display:block;position:fixed;left:0;top:0;min-height:100%;min-width:100%;height:auto;width:auto;opacity:0;z-index:99;background-color:#fff;filter:alpha(opacity=0);}.select2-hidden-accessible{border:0;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px;}.select2-container--default .select2-selection--single{background-color:#fff;border:1px solid #aaa;border-radius:4px;}.select2-container--default .select2-selection--single .select2-selection__rendered{color:#444;line-height:28px;}.select2-container--default .select2-selection--single .select2-selection__clear{cursor:pointer;float:right;font-weight:bold;}.select2-container--default .select2-selection--single .select2-selection__placeholder{color:#999;}.select2-container--default .select2-selection--single .select2-selection__arrow{height:26px;position:absolute;top:1px;right:1px;width:20px;}.select2-container--default .select2-selection--single .select2-selection__arrow b{border-color:#888 transparent transparent transparent;border-style:solid;border-width:5px 4px 0 4px;height:0;left:50%;margin-left:-4px;margin-top:-2px;position:absolute;top:50%;width:0;}.select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__clear{float:left;}.select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow{left:1px;right:auto;}.select2-container--default.select2-container--disabled .select2-selection--single{background-color:#eee;cursor:default;}.select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__clear{display:none;}.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{border-color:transparent transparent #888 transparent;border-width:0 4px 5px 4px;}.select2-container--default .select2-selection--multiple{background-color:white;border:1px solid #aaa;border-radius:4px;cursor:text;}.select2-container--default .select2-selection--multiple .select2-selection__rendered{box-sizing:border-box;list-style:none;margin:0;padding:0 5px;width:100%;}.select2-container--default .select2-selection--multiple .select2-selection__placeholder{color:#999;margin-top:5px;float:left;}.select2-container--default .select2-selection--multiple .select2-selection__clear{cursor:pointer;float:right;font-weight:bold;margin-top:5px;margin-right:10px;}.select2-container--default .select2-selection--multiple .select2-selection__choice{background-color:#e4e4e4;border:1px solid #aaa;border-radius:4px;cursor:default;float:left;margin-right:5px;margin-top:5px;padding:0 5px;}.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{color:#999;cursor:pointer;display:inline-block;font-weight:bold;margin-right:2px;}.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover{color:#333;}.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice,.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__placeholder{float:right;}.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice{margin-left:5px;margin-right:auto;}.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove{margin-left:2px;margin-right:auto;}.select2-container--default.select2-container--focus .select2-selection--multiple{border:solid black 1px;outline:0;}.select2-container--default.select2-container--disabled .select2-selection--multiple{background-color:#eee;cursor:default;}.select2-container--default.select2-container--disabled .select2-selection__choice__remove{display:none;}.select2-container--default.select2-container--open.select2-container--above .select2-selection--single,.select2-container--default.select2-container--open.select2-container--above .select2-selection--multiple{border-top-left-radius:0;border-top-right-radius:0;}.select2-container--default.select2-container--open.select2-container--below .select2-selection--single,.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple{border-bottom-left-radius:0;border-bottom-right-radius:0;}.select2-container--default .select2-search--dropdown .select2-search__field{border:1px solid #aaa;}.select2-container--default .select2-search--inline .select2-search__field{background:transparent;border:none;outline:0;}.select2-container--default .select2-results>.select2-results__options{max-height:200px;overflow-y:auto;}.select2-container--default .select2-results__option[role=group]{padding:0;}.select2-container--default .select2-results__option[aria-disabled=true]{color:#999;}.select2-container--default .select2-results__option[aria-selected=true]{background-color:#ddd;}.select2-container--default .select2-results__option .select2-results__option{padding-left:1em;}.select2-container--default .select2-results__option .select2-results__option .select2-results__group{padding-left:0;}.select2-container--default .select2-results__option .select2-results__option .select2-results__option{margin-left:-1em;padding-left:2em;}.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option{margin-left:-2em;padding-left:3em;}.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option{margin-left:-3em;padding-left:4em;}.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option{margin-left:-4em;padding-left:5em;}.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option{margin-left:-5em;padding-left:6em;}.select2-container--default .select2-results__option--highlighted[aria-selected]{background-color:#5897fb;color:white;}.select2-container--default .select2-results__group{cursor:default;display:block;padding:6px;}.select2-container--classic .select2-selection--single{background-color:#f6f6f6;border:1px solid #aaa;border-radius:4px;outline:0;background-image:-webkit-linear-gradient(top, #ffffff 50%, #eeeeee 100%);background-image:-o-linear-gradient(top, #ffffff 50%, #eeeeee 100%);background-image:linear-gradient(to bottom, #ffffff 50%, #eeeeee 100%);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee', GradientType=0);}.select2-container--classic .select2-selection--single:focus{border:1px solid #5897fb;}.select2-container--classic .select2-selection--single .select2-selection__rendered{color:#444;line-height:28px;}.select2-container--classic .select2-selection--single .select2-selection__clear{cursor:pointer;float:right;font-weight:bold;margin-right:10px;}.select2-container--classic .select2-selection--single .select2-selection__placeholder{color:#999;}.select2-container--classic .select2-selection--single .select2-selection__arrow{background-color:#ddd;border:none;border-left:1px solid #aaa;border-top-right-radius:4px;border-bottom-right-radius:4px;height:26px;position:absolute;top:1px;right:1px;width:20px;background-image:-webkit-linear-gradient(top, #eeeeee 50%, #cccccc 100%);background-image:-o-linear-gradient(top, #eeeeee 50%, #cccccc 100%);background-image:linear-gradient(to bottom, #eeeeee 50%, #cccccc 100%);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#cccccc', GradientType=0);}.select2-container--classic .select2-selection--single .select2-selection__arrow b{border-color:#888 transparent transparent transparent;border-style:solid;border-width:5px 4px 0 4px;height:0;left:50%;margin-left:-4px;margin-top:-2px;position:absolute;top:50%;width:0;}.select2-container--classic[dir="rtl"] .select2-selection--single .select2-selection__clear{float:left;}.select2-container--classic[dir="rtl"] .select2-selection--single .select2-selection__arrow{border:none;border-right:1px solid #aaa;border-radius:0;border-top-left-radius:4px;border-bottom-left-radius:4px;left:1px;right:auto;}.select2-container--classic.select2-container--open .select2-selection--single{border:1px solid #5897fb;}.select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow{background:transparent;border:none;}.select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow b{border-color:transparent transparent #888 transparent;border-width:0 4px 5px 4px;}.select2-container--classic.select2-container--open.select2-container--above .select2-selection--single{border-top:none;border-top-left-radius:0;border-top-right-radius:0;background-image:-webkit-linear-gradient(top, #ffffff 0%, #eeeeee 50%);background-image:-o-linear-gradient(top, #ffffff 0%, #eeeeee 50%);background-image:linear-gradient(to bottom, #ffffff 0%, #eeeeee 50%);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee', GradientType=0);}.select2-container--classic.select2-container--open.select2-container--below .select2-selection--single{border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;background-image:-webkit-linear-gradient(top, #eeeeee 50%, #ffffff 100%);background-image:-o-linear-gradient(top, #eeeeee 50%, #ffffff 100%);background-image:linear-gradient(to bottom, #eeeeee 50%, #ffffff 100%);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#ffffff', GradientType=0);}.select2-container--classic .select2-selection--multiple{background-color:white;border:1px solid #aaa;border-radius:4px;cursor:text;outline:0;}.select2-container--classic .select2-selection--multiple:focus{border:1px solid #5897fb;}.select2-container--classic .select2-selection--multiple .select2-selection__rendered{list-style:none;margin:0;padding:0 5px;}.select2-container--classic .select2-selection--multiple .select2-selection__clear{display:none;}.select2-container--classic .select2-selection--multiple .select2-selection__choice{background-color:#e4e4e4;border:1px solid #aaa;border-radius:4px;cursor:default;float:left;margin-right:5px;margin-top:5px;padding:0 5px;}.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove{color:#888;cursor:pointer;display:inline-block;font-weight:bold;margin-right:2px;}.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover{color:#555;}.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice{float:right;}.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice{margin-left:5px;margin-right:auto;}.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove{margin-left:2px;margin-right:auto;}.select2-container--classic.select2-container--open .select2-selection--multiple{border:1px solid #5897fb;}.select2-container--classic.select2-container--open.select2-container--above .select2-selection--multiple{border-top:none;border-top-left-radius:0;border-top-right-radius:0;}.select2-container--classic.select2-container--open.select2-container--below .select2-selection--multiple{border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;}.select2-container--classic .select2-search--dropdown .select2-search__field{border:1px solid #aaa;outline:0;}.select2-container--classic .select2-search--inline .select2-search__field{outline:0;}.select2-container--classic .select2-dropdown{background-color:white;border:1px solid transparent;}.select2-container--classic .select2-dropdown--above{border-bottom:none;}.select2-container--classic .select2-dropdown--below{border-top:none;}.select2-container--classic .select2-results>.select2-results__options{max-height:200px;overflow-y:auto;}.select2-container--classic .select2-results__option[role=group]{padding:0;}.select2-container--classic .select2-results__option[aria-disabled=true]{color:grey;}.select2-container--classic .select2-results__option--highlighted[aria-selected]{background-color:#3875d7;color:white;}.select2-container--classic .select2-results__group{cursor:default;display:block;padding:6px;}.select2-container--classic.select2-container--open .select2-dropdown{border-color:#5897fb;}
	</style>
<?php endif; ?>
<div class='row page-search-ankety'>
    <div class="psa__veil"></div>
    <div class="col-xs-12">
        <?php if(Share::$UserProfile->type == 3): ?>
            <div class="psa__header">
                <h1 class="psa__header-name"><?=Share::$UserProfile->exInfo->name?></h1>
                <a class='psa__btn psa__header-btn' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
            </div>
        <?php endif; ?>
    </div>
    <?
    /*
    *		FILTER
    */
    ?>
  	<div class='col-xs-12 col-sm-4 col-md-3'>
  		<div class="psa__filter-vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
		<form action="/ankety" id="F1Filter" method="get">
			<div class='filter'>
				<div class='psa__filter-block filter-surname'>
					<div class='psa__filter-name opened'>Фамилия</div>
					<div class='psa__filter-content opened'>
						<input name='qs' type='text' title="Введите фамилию" value="<?=$viData['qs']?>" class="psa__input">
						<div class="psa__filter-btn">ОК</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class='psa__filter-block filter-cities'>
					<div class='psa__filter-name opened'>Город</div>
					<div class='psa__filter-content opened'>
						<!--noindex-->
						<select class='templatingSelect2'  multiple='multiple' name='cities[]' id="ank-srch-cities">
							<?php foreach ($datas['city'] as $key => $val): ?>
								<option value='<?= $key ?>' <?=(in_array($key, $_GET['cities']) ? 'selected' : '')?>><?= $val ?></option>
							<?php endforeach;?>
							<?php /*foreach ($arCities as $city): ?>
								<option value="<?=$city['id']?>" <?=(in_array($city['id'], $_GET['cities']) ? 'selected' : '')?>><?=$city['name']?></option>
							<?php endforeach; */?>
						</select>
						<!--/noindex-->
					</div>
				</div>
                <div class='psa__filter-block filter-positions'>
                    <div class='psa__filter-name opened'>Должность</div>
					<div class='psa__filter-content opened'>
                        <div class='right-box'>
                        	<?php
                        		$sel = 0;
                        		foreach($viData['posts'] as $p) 
                        			if($p['selected']) $sel++;
                        	?>
                            <input name='posts-all' type='checkbox' id="psa-posts-all" class="psa__checkbox-input"<?=sizeof($viData['posts'])==$sel ?' checked':''?>>
                            <label class='psa__checkbox-label' for="psa-posts-all">Выбрать все / снять все</label>
                            <?php foreach($viData['posts'] as $val): ?>
                                <input name='posts[]' value="<?=$val['id']?>" type='checkbox' id="psa-posts-<?=$val['id']?>" class="psa__checkbox-input" <?=$val['selected'] ? 'checked' : ''?>>
                                <label class='psa__checkbox-label' for="psa-posts-<?=$val['id']?>"><?=$val['name']?></label>
                            <?php endforeach; ?>
                        </div>
                        <span class="more-posts">Показать все</span>
                    </div>
                </div>
				<div class='psa__filter-block filter-sex'>
					<div class='psa__filter-name opened'>Пол</div>
					<div class='psa__filter-content opened'>
						<div class='right-box'>
							<input name='sm' type='checkbox' value='1' class="psa__checkbox-input" id="psa-sex-m" <?=Yii::app()->getRequest()->getParam('sm') ? 'checked' : ''?>>
							<label class="psa__checkbox-label" for="psa-sex-m">Мужской</label>
							<input name='sf' type='checkbox' value='1' class="psa__checkbox-input" id="psa-sex-w" <?=Yii::app()->getRequest()->getParam('sf') ? 'checked' : ''?>>
							<label class="psa__checkbox-label" for="psa-sex-w">Женский</label>
						</div>
					</div>
				</div>
				<div class='psa__filter-block filter-additional'>
					<div class='psa__filter-name opened'>Дополнительно</div>
					<div class='psa__filter-content opened'>
						<div class='right-box'>
							<input name='mb' type='checkbox' value='1' class="psa__checkbox-input" id="psa-med" <?=Yii::app()->getRequest()->getParam('mb') ? 'checked' : ''?>>
							<label class="psa__checkbox-label" for="psa-med">Наличие медкнижки</label>
							<input name='avto' type='checkbox' value='1' class="psa__checkbox-input" id="psa-auto" <?=Yii::app()->getRequest()->getParam('avto') ? 'checked' : ''?>>
							<label class="psa__checkbox-label" for="psa-auto">Наличие автомобиля</label>
							<input name='smart' type='checkbox' value='1' class="psa__checkbox-input" id="psa-smart" <?=Yii::app()->getRequest()->getParam('smart') ? 'checked' : ''?>>
							<label class="psa__checkbox-label" for="psa-smart">Наличие смартфона</label>
						</div>
					</div>
				</div>
                <div class='psa__filter-block filter-card'>
                    <div class='psa__filter-name opened'>Карта</div>
                    <div class='psa__filter-content opened'>          
                        <div class='right-box'>
                            <input id='psa-pcard' name='cardPrommu' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('cardPrommu') ? 'checked' : '' ?> class="psa__checkbox-input">
                            <label class='psa__checkbox-label' for="psa-pcard">Банковская карта Prommu</label>
                            <input id='psa-card' name='card' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('card') ? 'checked' : '' ?> class="psa__checkbox-input">
                            <label class='psa__checkbox-label' for="psa-card">Банковская карта</label>
                        </div>
                    </div>
                </div>
      		</div>
    	</form>
  	</div>
  	<?
  	/*
  	*		CONTENT
  	*/
  	?>
  	<div class="col-xs-12 col-sm-8 col-md-9" id="content">
		<?php if( !count($viData['promo']) ): ?>
		    <div class="psa__nothing">Нет подходящих соискателей</div>
		<?php else: ?>
		    <div class='psa__view-block hidden-xs'>
		        <a class='psa__view-table <?=($cookieView=='table'?'active':'')?> js-g-hashint' href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'table') ?>' title='Отображать таблицей'></a>
		        <a class="psa__view-list <?=($cookieView=='list'?'active':'')?> js-g-hashint" href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'list') ?>' title='Отображать списком'></a>
		        <div class="clearfix"></div>
		    </div>
		    <div class='questionnaire'>
		        <div>
		            <?= $this->ViewModel->declOfNum($count, array('Найдена', 'Найдено', 'Найдено')) ?>
		            <b><?= $count ?></b>
		            <?= $this->ViewModel->declOfNum($count, array('Анкета', 'Анкеты', 'Анкет'))?>
		        </div>
		    </div>
		    <?php
		    /*
		    *   BM: list-view
		    */
		    ?>
		    <?php if($cookieView== 'list'): ?>
		        <div class='list-view'>
		            <?php foreach ($viData['promo'] as $key => $val): ?>
		                <div class='appl-list-item-box'>
		                    <div class='row'>
		                        <div class='col-xs-12 col-sm-4'>
		                            <div class='company-logo-wrapp'>
		                                <div class='company-logo'>
		                                    <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>
		                                        <?php if($val['sex']):?>
		                                            <img alt='Соискатель <?= $val['firstname'] . ' ' . $val['lastname'] ?> prommu.com' src='<?= DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$val['photo'] ? MainConfig::$DEF_LOGO : $val['photo'] . '100.jpg') ?>'>
		                                        <?php else: ?>
		                                            <img alt='Соискатель <?= $val['firstname'] . ' ' . $val['lastname'] ?> prommu.com' src='<?= DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$val['photo'] ? MainConfig::$DEF_LOGO_F : $val['photo'] . '100.jpg') ?>'>
		                                        <?php endif; ?>
												<?php if($val['is_online']): ?>
													<span class="promo-list__item-onl"><span>В сети</span></span>
												<?php endif; ?>
		                                    </a>
		                                </div>
		                            </div>
		                        </div>
		                        <div class='col-xs-12 col-sm-8'>
		                            <h2>
		                                <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'><?= $val['firstname'] . ' ' . $val['lastname'] . ', ' . $val['age'] ?></a>
		                            </h2>
		                            <div class='charac clearfix'>
		                                <div class='rate' title="положительный / отрицательный">
		                                    Рейтинг: <b class="green"><?= $val['rate'] ?></b> / <b class="red"><?= $val['rate_neg'] ?></b>
		                                </div>
		                                <div class='comments' title="положительные / отрицательные">
		                                    Отзывы:
		                                    <b class='green'><?= $val['comm'] ?></b> / <b class='red'><?= $val['commneg'] ?></b>
		                                </div>
		                            </div>
		                            <br>
		                            <?php if( $val['ismed'] === '1' || $val['ishasavto'] === '1' ): ?>
		                                <div class="med-avto">
		                                    <?php  if( $val['ismed'] === '1' ): ?>
		                                        <div class="ico ico-avto js-g-hashint" title="Есть автомобиль"></div>
		                                    <?php endif; ?>
		                                    <?php if( $val['ishasavto'] === '1' ): ?>
		                                        <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
		                                    <?php endif; ?>
		                                </div>
		                            <?php endif; ?>
		                            <div class='vacancies'>
		                                <h3>Целевые вакансии:</h3>
		                                <?php
		                                    $curr = array('руб/час', 'руб/неделю', 'руб/мес', );
		                                    foreach ($val['post'] as $key2 => $val2):
		                                ?>
		                                    <?= $val2[0] ?>
		                                    <div class='price'><?= $val2[1] . ' ' . $curr[$val2[2]] ?></div><br>
		                                <?php endforeach; ?>
		                            </div>
		                            <div class='place'>
		                                <h3>Город: <small><?=join(', ',$val['city'])?></small></h3>
		                            </div>
		                            <?php if( $val['metroes'] ): ?>
		                                <div class='place'>
		                                    <h3>Метро: <small><?=join(', ',$val['metroes'])?></small></h3>
		                                </div>
		                            <?php endif; ?>
		                        </div>
		                    </div>
		                    <div class='row'>
		                        <div class='col-xs-12 col-md-8 col-md-push-4 no-margin'>
		                            <?php/* if( Share::$UserProfile->type == 3 ): ?>
		                                <div class='btn-message'>
		                                    <a href='<?= MainConfig::$PAGE_IM . '?new=' . $val['id_user'] ?>'>Написать сообщение</a>
		                                </div>
		                            <?php endif;*/ ?>
		                        </div>
		                    </div>
		                </div>
		            <?php endforeach; ?>
		        </div>
		    <?php else: ?>
		    <?php
		    /*
		    *   BM: table-view
		    */
		    ?>
		        <div class='row vacancy table-view'><?php
		            $i = 1;
		            foreach ($viData['promo'] as $key => $val): ?>
		                <div class='col-xs-12 col-sm-6 col-md-4'>    
			                <div class='comm-logo-wrapp'>
			                    <div class='comm-logo'>
			                        <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user']?>">
			                            <img alt='<?="Соискатель {$val['firstname']} {$val['lastname']} prommu.com "?>' src="<?
			                                if($val['sex'])
			                                    echo DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$val['photo'] ? MainConfig::$DEF_LOGO : $val['photo'] . '100.jpg');
			                                else 
			                                    echo DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$val['photo'] ? MainConfig::$DEF_LOGO_F : $val['photo'] . '100.jpg');
			                            ?>">
										<?php if($val['is_online']): ?>
											<span class="promo-list__item-onl"><span>В сети</span></span>
										<?php endif; ?>
			                        </a>
			                        <br>
			                        <br>
			                        <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user']?>">
			                            <b class="name"><?=$val['firstname'] . ' ' . $val['lastname'] . ', ' . $val['age']?></b>
			                        </a>
			                        <div class='tmpl-ph1'>
			                            <div class='med-avto'>
			                                <?if($val['ishasavto'] === '1'):?>
			                                    <div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>
			                                <?endif;?>
			                                <?if($val['ismed'] === '1'):?>
			                                    <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
			                                <?endif;?>
			                            </div>
			                        </div>
			                        <div class='hr'>
			                            <?php if( is_numeric($val['comm']) ): ?>
			                                <div class='comments js-g-hashint' title='Отзывы положительные | отрицательные'>
			                                    <span class='r1'><?=$val['comm']?></span> | <?=$val['commneg']?>
			                                </div>
			                            <?php endif; ?>
			                            <?php if( is_numeric($val['rate']) ): ?>
			                            <div class='rate js-g-hashint' title='Рейтинг положительный | отрицательный'>
			                              <span class='r1'><?=$val['rate']?></span> | <?=$val['rate_neg']?>
			                            </div>
			                            <?php endif; ?>
			                        </div>
			                    </div>
			                </div>
		                </div>
		                <?php if( $i % 2 == 0 ): ?>
		                    <div class="clear visible-sm"></div>
		                <?php endif; ?>
		                <?php if( $i % 3 == 0 ): ?>
		                    <div class="clear visible-md visible-lg"></div>
		                <?php endif; ?>
		                <?php
		                    $i++;
		                    endforeach;
		        ?></div>
		    <?php endif; ?> 
		    <div class='paging-wrapp'>
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
		    </div>
		<?php endif; ?>
  	</div>

    <div class='col-xs-12' id="psa-seo-text" class="psa__content"><?php 
        if($this->ViewModel->getViewData()->pageH1)
            echo $this->ViewModel->getViewData()->pageMetaKeywords;
        elseif(isset($seo['meta_keywords']))
            echo $seo['meta_keywords'];
    ?></div>
</div>