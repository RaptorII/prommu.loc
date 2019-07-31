<?php Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array()); ?>
<?php
//	premium
?>
<?php if($viData['service']=='premium'): ?>
	<form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" id="order-form">
		<input type="hidden" name="vacancy[]" value="<?=Yii::app()->getRequest()->getParam('id')?>">
		<input type="hidden" name="service" value="premium-vacancy">
	</form>
<?php endif; ?>
<?php
//	sms
?>
<?php if($viData['service']=='sms'): ?>
	<form action="<?='/user' . MainConfig::$PAGE_SERVICES_SMS?>" method="POST" id="order-form">
		<input type="hidden" name="vacancy" value="<?=Yii::app()->getRequest()->getParam('id')?>">
	</form>
<?php endif; ?>
<?php
//	outsourcing
?>
<?php if($viData['service']=='outsourcing'): ?>
	<form action="<?='/user' . MainConfig::$PAGE_SERVICES_OUTSOURCING?>" method="POST" id="order-form">
		<input type="hidden" name="vacancy[]" value="<?=Yii::app()->getRequest()->getParam('id')?>">
	</form>
<?php endif; ?>
<?php
//	outstaffing
?>
<?php if($viData['service']=='outstaffing'): ?>
	<form action="<?='/user' . MainConfig::$PAGE_SERVICES_OUTSTAFFING?>" method="POST" id="order-form">
		<input type="hidden" name="vacancy[]" value="<?=Yii::app()->getRequest()->getParam('id')?>">
	</form>
<?php endif; ?>
<?php
//	email invitation
?>
<?php if($viData['service']=='email'): ?>
	<form action="<?='/user' . MainConfig::$PAGE_SERVICES_EMAIL?>" method="POST" id="order-form">
		<input type="hidden" name="vacancy" value="<?=Yii::app()->getRequest()->getParam('id')?>">
	</form>
<?php endif; ?>
<?php
//	push invitation
?>
<?php if($viData['service']=='push'): ?>
	<form action="<?='/user' . MainConfig::$PAGE_SERVICES_PUSH?>" method="POST" id="order-form">
		<input type="hidden" name="vacancy" value="<?=Yii::app()->getRequest()->getParam('id')?>">
	</form>
<?php endif; ?>
<script type="text/javascript">$(function(){ $('#order-form').submit() })</script>