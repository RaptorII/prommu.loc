<?for ($i=count($viData['items'])-1; $i>=0; $i--):?>
	<? 
		$item = $viData['items'][$i];
		$user = $viData['users'][$item['id_user']];
	?>
	<div class="mess-box <?=($item['author']==$item['id_user']?'mess-to':'mess-from')?>" data-id="<?=$item['id']?>">
		<div class='author'>
			<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
			<?if($viData['vacancy']['id_user']==$viData['items'][$i]['id_user']):?>
				<b class='fio'><?=$user['name']?></b>
			<?else:?>
				<a href="<?=$user['profile']?>" target="_blank"><b class='fio'><?=$user['name']?></b></a>
			<?endif;?>
			
			<span class='date'><?=$item['date']?></span>
		</div>
		<div class='mess'><?=html_entity_decode($item['mess'])?></div>
	</div>
<?endfor;?>