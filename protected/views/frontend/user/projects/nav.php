<?php
	$s = $_GET['s'];
?>

			<a href="<?=$_SERVER['REDIRECT_URL']?>" class="<?=(!isset($s) || empty($s))?'active':''?>">
				<b>ОСНОВНОЕ</b>
			</a>
			<a href="?s=staff" class="<?=$s=='staff'?'active':''?>">
				<b>ПЕРСОНАЛ</b>
			</a>
			<a href="?s=index" class="<?=$s=='index'?'active':''?>">
				<b>АДРЕСНАЯ ПРОГРАММА</b>
			</a>
			<a href="?s=geo" class="<?=$s=='geo'?'active':''?>">
				<b>ГЕОЛОКАЦИЯ</b>
			</a>
			<a href="?s=route" class="<?=$s=='route'?'active':''?>">
				<b>МАРШРУТ ГЕО</b>
			</a>
			<a href="?s=tasks" class="<?=$s=='tasks'?'active':''?>">
				<b>ЗАДАНИЯ</b>
			</a>
			<a href="?s=report" class="<?=$s=='report'?'active':''?>">
				<b>ОТЧЕТЫ</b>
			</a>
