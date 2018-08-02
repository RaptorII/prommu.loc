<?
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/address-edit.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/address-edit.js', CClientScript::POS_END);

	$arProgram = array(
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
	            'bdate' => '07.02.2018',
	            'edate' => '08.02.2018',
	            'btime' => '14:00',
	            'etime' => '16:00'
	          ),
	          2 => array(
	            'id' => 2,
	            'bdate' => '20.02.2018',
	            'edate' => '22.02.2018',
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
	            'bdate' => '01.08.2018',
	            'edate' => '01.08.2018',
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
	            'bdate' => '07.02.2018',
	            'edate' => '08.02.2018',
	            'btime' => '14:00',
	            'etime' => '16:00'
	          ),
	          5 => array(
	            'id' => 5,
	            'bdate' => '20.02.2018',
	            'edate' => '22.02.2018',
	            'btime' => '09:00',
	            'etime' => '18:00'
	          )
	        )
	      ),      
	    )
	  )
	);
?>
<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($arProgram); ?>
</pre>
<script type="text/javascript">var getParams = <?=json_encode($_GET)?></script>
<div class="row project">
	<div class="col-xs-12">
		<form action="" method="POST" id="new-project">
			<?php
			//
			?>
			<div id="index" class="project__module" data-country="1">
				<h2 class="project__title">РЕДАКТИРОВАТЬ АДРЕСНУЮ ПРОГРАММУ<span></span></h2>
				<div class="project__index">
					<?php foreach ($arProgram as $idCity => $arCity): ?>
						<div class="city-item" data-city="<?=$idCity?>">
							<span class="project__index-name">Город</span>
							<span class="city-del">&#10006</span>
							<span class="add-loc-btn">Добавить еще ТТ</span>
							<div class="project__index-row">
								<label class="project__index-lbl">Город</label>
								<div class="city-field project__index-arrow">
									<span class="city-select"><?=$arCity['name']?><b></b></span>
									<input type="text" name="c" class="city-inp" autocomplete="off" value="<?=$arCity['name']?>">
									<ul class="select-list"></ul>
									<input type="hidden" name="city[]" value="<?=$idCity?>">
								</div>
							</div>
							<?php foreach ($arCity['locations'] as $idLoc => $arLoc): ?>
								<div class="loc-item" data-id="<?=$idLoc?>">
									<span class="project__index-name">Локация</span>
									<span class="loc-del">&#10006</span>
									<span class="add-period-btn">Добавить период</span>
									<div class="project__index-row loc-field">
										<?php if(!empty($arCity['metro'])): ?>
											<div class="metro-item">
												<label class="project__index-lbl">Метро</label>
												<div class="metro-field project__index-arrow">
													<ul class="metro-select">
														<?php foreach($arLoc['metro'] as $mId => $mName): ?>
															<li data-id="<?=$mId?>"><?=$mName?><b></b></li>
														<?php endforeach; ?>
														<li data-id="0">
															<input type="text" name="m" class="metro-inp" autocomplete="off">
														</li>
													</ul>
													<ul class="select-list"></ul>
													<?php $arIdMetros = array_keys($arLoc['metro']); ?>
													<input type="hidden" name="<?='metro[' . $idCity . '][' . $idLoc . ']'?>" value="<?=join(',',$arIdMetros)?>">
												</div>
											</div>
										<?php endif; ?>
										<div class="project__index-pen">
											<label class="project__index-lbl">Адрес ТТ</label>
											<input type="text" name="<?='lindex[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" value="<?=$arLoc['index']?>">
										</div>
										<div class="project__index-pen">
											<label class="project__index-lbl">Название ТТ</label>
											<input type="text" name="<?='lname[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" value="<?=$arLoc['name']?>">
										</div>
									</div>
									<?php foreach ($arLoc['periods'] as $idPer => $arPer): ?>
										<div class="period-item" data-id="<?=$idPer?>">
											<span class="project__index-name">Период</span>
											<span class="period-del">&#10006</span>
											<div class="period-field">
												<label class="project__index-lbl">Дата</label>
												<span><?=$arPer['bdate']?></span>
												<div class="calendar" data-type="bdate">
													<table>
														<thead>
														<tr>
															<td class="mleft">‹
															<td colspan="5" class="mname">
															<td class="mright">›
														</tr>
														<tr>
															<td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
														</tr>
														<tbody></tbody>
													</table>
												</div>
												<input type="hidden" name="<?='bdate[' . $idCity . '][' . $idLoc . '][' . $idPer . ']'?>" value="<?=$arPer['bdate']?>">
											</div>
											<div class="period-field">
												<label class="project__index-lbl">по</label>
												<span><?=$arPer['edate']?></span>
												<div class="calendar" data-type="edate">
													<table>
														<thead>
														<tr>
															<td class="mleft">‹
															<td colspan="5" class="mname">
															<td class="mright">›
														</tr>
														<tr>
															<td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
														</tr>
														<tbody></tbody>
													</table>
												</div>
												<input type="hidden" name="<?='edate[' . $idCity . '][' . $idLoc . '][' . $idPer . ']'?>" value="<?=$arPer['edate']?>">
											</div>
											<div class="project__index-pen time-item">
												<label class="project__index-lbl">Время работы</label>
												<input type="text" name="<?='btime[' . $idCity . '][' . $idLoc . '][' . $idPer . ']'?>" class="time-inp" value="<?=$arPer['btime']?>">
											</div>
											<div class="project__index-pen time-item">
												<label class="project__index-lbl">по</label>
												<input type="text" name="<?='etime[' . $idCity . '][' . $idLoc . '][' . $idPer . ']'?>" class="time-inp" value="<?=$arPer['etime']?>">
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="project__all-btns">
					<span class="project__btn-white" id="add-city-btn">ДОБАВИТЬ ГОРОД</span>
					<span class="save-btn" id="save-index" data-event="main">СОХРАНИТЬ</span>
				</div>
			</div>
			<input type="hidden" name="project" value="<?=Yii::app()->getRequest()->getParam('id')?>">
		</form>
	</div>
</div>
<?php
/*
*
*
*
*/
?>
<div class="hidden" id="city-content">
	<div class="city-item" data-city="">
		<span class="project__index-name">Город</span>
		<span class="city-del">&#10006</span>
		<span class="add-loc-btn">Добавить еще ТТ</span>
		<div class="project__index-row">
			<label class="project__index-lbl">Город</label>
			<div class="city-field project__index-arrow">
				<span class="city-select"></span>
				<input type="text" name="c" class="city-inp" autocomplete="off">
				<ul class="select-list"></ul>
				<input type="hidden" name="city[]" value="">
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="loc-content">
	<div class="loc-item" data-id="0">
		<span class="loc-del">&#10006</span>
		<span class="project__index-name">Локация</span>
		<span class="add-period-btn">Добавить период</span>
		<div class="project__index-row loc-field">
			<div class="project__index-pen">
				<label class="project__index-lbl">Адрес ТТ</label>
				<input type="text" name="lindex" autocomplete="off">
			</div>
			<div class="project__index-pen">
				<label class="project__index-lbl">Название ТТ</label>
				<input type="text" name="lname" autocomplete="off">
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="period-content">
	<div class="period-item" data-id="0">
		<span class="period-del">&#10006</span>
		<span class="project__index-name">Период</span>
		<div class="period-field">
			<label class="project__index-lbl">Дата</label>
			<span></span>
			<div class="calendar" data-type="bdate">
				<table>
					<thead>
					<tr>
						<td class="mleft">‹
						<td colspan="5" class="mname">
						<td class="mright">›
					</tr>
					<tr>
						<td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
					</tr>
					<tbody></tbody>
				</table>
			</div>
			<input type="hidden" name="bdate">
		</div>
		<div class="period-field">
			<label class="project__index-lbl">по</label>
			<span></span>
			<div class="calendar" data-type="edate">
				<table>
					<thead>
					<tr>
						<td class="mleft">‹
						<td colspan="5" class="mname">
						<td class="mright">›
					</tr>
					<tr>
						<td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
					</tr>
					<tbody></tbody>
				</table>
			</div>
			<input type="hidden" name="edate">
		</div>
		<div class="project__index-pen time-item">
			<label class="project__index-lbl">Время работы</label>
			<input type="text" name="btime" class="time-inp">
		</div>
		<div class="project__index-pen time-item">
			<label class="project__index-lbl">по</label>
			<input type="text" name="etime" class="time-inp">
		</div>
	</div>
</div>
<div class="hidden" id="metro-content">
	<div class="metro-item">
		<label class="project__index-lbl">Метро</label>
		<div class="metro-field project__index-arrow">
			<ul class="metro-select">
				<li data-id="0">
					<input type="text" name="m" class="metro-inp" autocomplete="off">
				</li>
			</ul>
			<ul class="select-list"></ul>
			<input type="hidden" name="metro" value="">
		</div>
	</div>
</div>