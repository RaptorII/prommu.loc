<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/users-select.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/users-select.js', CClientScript::POS_END);

	$viData = array(
		'index' => array(
	    1307 => array(
	      'name' => 'Москва',
	      'id' => 1307,
	      'metro' => 1,
	      'locations' => array(
	        1 => array(
	          'id' => 1,
	          'name' => 'АТБ1',
	          'index' => 'ул. Исполкомовская 123',
	          'metro' => array(
	            1 => 'Авиамоторная',
	            2 => 'Автозаводская (Замоскворецкая линия)',
	            4 => 'Алексеевская'
	          ),
	          'periods' => array(
	            1 => array(
	              'id' => 1,
	              'bdate' => '07.02.18',
	              'edate' => '08.02.18',
	              'btime' => '14:00',
	              'etime' => '16:00'
	            ),
	            2 => array(
	              'id' => 2,
	              'bdate' => '20.02.18',
	              'edate' => '22.02.18',
	              'btime' => '09:00',
	              'etime' => '18:00'
	            )
	          )
	        ),
	        2 => array(
	          'id' => 2,
	          'name' => 'АТБ2',
	          'index' => 'ул. Исполкомовская 777',
	          'metro' => array(
	            4 => 'Алексеевская'
	          ),
	          'periods' => array(
	            3 => array(
	              'id' => 3,
	              'bdate' => '01.08.18',
	              'edate' => '01.08.18',
	              'btime' => '12:00',
	              'etime' => '13:00'
	            )
	          )
	        )
	      )
	    ),
	    2582 => array(
	      'name' => 'Донецк',
	      'id' => 2582,
	      'metro' => 0,
	      'locations' => array(
	        3 => array(
	          'id' => 3,
	          'name' => 'АТБ3',
	          'index' => 'ул. Исполкомовская 999',
	          'metro' => array(),
	          'periods' => array(
	            4 => array(
	              'id' => 4,
	              'bdate' => '07.02.18',
	              'edate' => '08.02.18',
	              'btime' => '14:00',
	              'etime' => '16:00'
	            ),
	            5 => array(
	              'id' => 5,
	              'bdate' => '20.02.18',
	              'edate' => '22.02.18',
	              'btime' => '09:00',
	              'etime' => '18:00'
	            )
	          )
	        ),      
	      )
	    )
	  ),
		'users' => array(
			1 => array(
				'id' => 1,
				'name' => 'Шишкина Виктория',
				'logo' => '/images/applic/20180503073112204100.jpg'
			),
			2 => array(
				'id' => 2,
				'name' => 'Ибадулаев Павел',
				'logo' => '/images/applic/20180428142455264100.jpg'
			),
			3 => array(
				'id' => 3,
				'name' => 'Немич Константин',
				'logo' => '/images/applic/20180503073112204100.jpg'
			),
			4 => array(
				'id' => 4,
				'name' => 'Простова Ольга',
				'logo' => '/images/applic/20180428142455264100.jpg'
			),
			5 => array(
				'id' => 5,
				'name' => 'Александр Примак',
				'logo' => '/images/applic/20180503073112204100.jpg'
			)
		)
	);
	$arIndex = array();
	foreach ($viData['index'] as $id => $arCity)
		foreach ($arCity['locations'] as $idloc => $arLoc)
			foreach ($arLoc['periods'] as $idper => $arPer)
				if($_GET['period']==$idper) {
					$arIndex = array(
						'id_city' => $id,
						'id_loc' => $idloc,
						'id_period' => $idper,
						'city' => $arCity['name'],
						'ismetro' => $arCity['metro'],
						'locname' => $arLoc['name'],
						'locindex' => $arLoc['index'],
						'metro' => join(',<br>',$arLoc['metro']),
						'date' => $arPer['bdate']==$arPer['edate'] 
							? $arPer['bdate'] 
							: ('с ' . $arPer['bdate'] . ' по ' . $arPer['edate']),
						'time' => $arPer['btime'] . '-' . $arPer['etime']
					);
					break;
				}
?>
<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>
<form action="" method="POST" id="select-form">
	
	<table class="index-table">
		<tr>
			<td><b><?=$arIndex['city'] ?></b></td>
		</tr>
		<? if(isset($arIndex['ismetro'])): ?>
			<tr>
				<td><?=$arIndex['metro'] ?></td>
			</tr>
		<? endif; ?>
		<tr>
			<td><?=$arIndex['locname'] ?></td>
		</tr>
		<tr>
			<td><?=$arIndex['locindex'] ?></td>
		</tr>
		<tr>
			<td><?=$arIndex['date'] . ' ' . $arIndex['time']?></td>
		</tr>
	</table>
	<br>
	<br>
	<div class="row">
		<div class="col-xs-12 users-select__list">
			<div class="row">
				<? foreach ($viData['users'] as $user): ?>
					<div class="col-xs-12 col-sm-4 col-md-3">
						<input type="checkbox" name="user" value="<?=$user['id']?>" id="user-<?=$user['id']?>">
						<label for="user-<?=$user['id']?>"></label>
						<div class="users-select__item">
							<img src="<?=$user['logo']?>">
							<span><?=$user['name']?></span>
						</div>
					</div>
				<? endforeach; ?>
			</div>
		</div>
	</div>
	<div class="project__all-btns">
		<span class="save-btn" id="save-btn">СОХРАНИТЬ</span>
		<a class="save-btn" href="<?=MainConfig::$PAGE_PROJECT_LIST . '/' . Yii::app()->getRequest()->getParam('id')?>">НАЗАД</a>
	</div>
	<input type="hidden" name="project" value="<?=Yii::app()->getRequest()->getParam('id')?>">
	<input type="hidden" name="period" value="<?=Yii::app()->getRequest()->getParam('period')?>">
</form>