<? 
	$bNewMess = false;
	$idus = Share::$UserProfile->id;
?>
<?for ($i=count($viData['items'])-1; $i>=0; $i--):?>
	<? 
		$item = $viData['items'][$i];
		$user = $viData['users'][$item['id_user']];
	?>
	<? if(!$item['readed'] && !$bNewMess && $item['id_user']!=$idus): ?>
		<div class="new-mess"><div>Новые сообщения</div></div>
		<? $bNewMess = true; ?>
	<? endif; ?>
	<div class="mess-box <?=($idus==$item['id_user']?'mess-to':'mess-from')?>" data-id="<?=$item['id']?>">
		<div class='author'>
			<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
			<?if($idus==$viData['items'][$i]['id_user']):?>
				<b class='fio'><?=$user['name']?></b>
			<?else:?>
				<a href="<?=$user['profile']?>" target="_blank"><b class='fio'><?=$user['name']?></b></a>
			<?endif;?>
			<span class='date'><?=$item['date']?></span>
		</div>
		<div class='mess'><?=html_entity_decode($item['mess'])?></div>
	</div>
<?endfor;?>