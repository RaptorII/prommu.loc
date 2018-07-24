<?php
	$title = 'Работа в других городах';
	$this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_OTHERCITIES));
	$this->pageTitle = $title;
	// устанавливаем h1
	//$this->ViewModel->setViewData('pageTitle', '<h1>' . $title . '</h1>');
	// breadcrumbs
	$this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_OTHERCITIES));
?>
<style type="text/css">
	#develop{
		padding: 30px 15px 50px;
	}
	#develop h2{
		text-align: center;
		font-size: 36px;
		font-weight: normal;
		line-height: normal;
		margin-top: 0;
	}
	#develop img{
		width: 100%;
		max-width: 250px;
		margin: 0 auto 30px;
		display: block;
	}
	#develop a{
		display: block;
		margin: 0 auto;
		width: 100%;
		max-width: 300px;
		line-height: 30px;
		text-align: center;
		background: #FF8300;
		color: #fff;
		text-transform: uppercase;
		position: relative;
		z-index: 1;
		cursor: pointer;
		-webkit-transition: all .3s ease-out;
		transition: all .3s ease-out;
	}
	#develop a:hover{ color: #fff }
	#develop a:before{
		content: '';
		position: absolute;
		z-index: -1;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: #BBC823;
		-webkit-transform: scaleX(0);
		transform: scaleX(0);
		-webkit-transform-origin: 0 50%;
		transform-origin: 0 50%;
		-webkit-transition: all .3s ease-out;
		-moz-transition: all .3s ease-out;
		-o-transition: all .3s ease-out;
		transition: all .3s ease-out;
	}
	#develop a:hover:before{
		-webkit-transform: scaleX(1);
		transform: scaleX(1);
	}
</style>
<div id="develop">
	<h2>Страница в разработке.</h2>
	<h2>Приносим свои извинения</h2>
	<img src="/images/page-in-dev-icon.png">
	<a href="/">Вернуться на главную</a>
</div>