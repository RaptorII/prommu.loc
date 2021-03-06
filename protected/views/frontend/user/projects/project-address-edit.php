<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/address-edit.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/address-edit.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
?>
<script type="text/javascript">var getParams = <?=json_encode($_GET)?></script>
<div class="filter__veil"></div>
<div class="row project">
	<div class="col-xs-12"><? require 'project-nav.php'; // Меню вкладок ?></div>
</div>
<div class="row project">
	<div class="col-xs-12">
		<form action="" method="POST" id="new-project">
			<?php
			//
			?>
			<div id="index" class="project__module" data-country="1">
				<h2 class="project__title">РЕДАКТИРОВАТЬ АДРЕСНУЮ ПРОГРАММУ<span></span></h2>
				<div class="project__index">
					<?php foreach ($viData['location'] as $idCity => $arCity): ?>
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
										<div class="project__index-pen lindex">
											<label class="project__index-lbl">Улица</label>
											<input type="text" name="<?='lindex[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" value="<?=$arLoc['index']?>">
										</div>
										<div class="project__index-pen lname">
											<label class="project__index-lbl">Название ТТ</label>
											<input type="text" name="<?='lname[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" value="<?=$arLoc['name']?>">
										</div>
										<?php if(!empty($arCity['metro'])): ?>
											<div class="metro-item">
												<label class="project__index-lbl">Метро</label>
												<div class="metro-field project__index-arrow">
													<?php if(is_array($arLoc['metro'])): ?>
														<?php foreach($arLoc['metro'] as $mId => $mName): ?>
															<span class="metro-select"<?=($mId ? '' : 'style="display:none"')?>><?=($mId ? $mName.'<b></b>' : '')?></span>
															<input data-checker="metro" type="text" name="m" class="metro-inp" autocomplete="off" value="<?=$mName?>">
															<ul class="select-list"></ul>
															<input data-checker="metro" type="hidden" name="<?='metro[' . $idCity . '][' . $idLoc . ']'?>" value="<?=$mId?>">
														<?php endforeach; ?>
													<?php else: ?>
														<span class="metro-select" style="display: none"></span>
														<input data-checker="metro" type="text" name="m" class="metro-inp" autocomplete="off" value="">
														<ul class="select-list"></ul>
														<input data-checker="metro" type="hidden" name="<?='metro[' . $idCity . '][' . $idLoc . ']'?>" value="<?=$mId?>">
													<?php endif; ?>
												</div>
											</div>
										<?php endif; ?>
										<div class="project__index-pen lhouse">
											<label class="project__index-lbl">Дом</label>
											<input type="text" name="<?='lhouse[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" data-checker="house" value="<?=$arLoc['house']?>">
										</div>
										<div class="project__index-pen lbuilding">
											<label class="project__index-lbl">Здание</label>
											<input type="text" name="<?='lbuilding[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" data-checker="house" value="<?=$arLoc['building']?>">
										</div>
										<div class="project__index-pen lconstruction">
											<label class="project__index-lbl">Строение</label>
											<input type="text" name="<?='lconstruction[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" data-checker="house" value="<?=$arLoc['construction']?>">
										</div>
										<div class="project__index-pen lcorps">
											<label class="project__index-lbl">Корпус</label>
											<input type="text" name="<?='lcorps[' . $idCity . '][' . $idLoc . ']'?>" autocomplete="off" data-checker="house" value="<?=$arLoc['corps']?>">
										</div>
										<div class="project__index-row comment">
											<label class="project__index-lbl">Комментарий</label>
											<textarea name="<?='comment[' . $idCity . '][' . $idLoc . ']'?>"><?=$arLoc['comment']?></textarea>
										</div>
									</div>
									<?php foreach ($arLoc['periods'] as $idPer => $arPer): ?>
										<div class="period-item" data-id="<?=$idPer?>">
											<span class="project__index-name">Период</span>
											<span class="period-del">&#10006</span>
											<div class="post-item">
												<label class="project__index-lbl">Должность</label>
												<div class="post-field project__index-arrow">
													<span class="post-select"><?=$viData['posts'][$arPer['post']]?><b></b></span>
													<input type="text" name="p" class="post-inp" autocomplete="off" value="<?=$viData['posts'][$arPer['post']]?>">
													<ul class="select-list">
														<li data-id="0" class="emp">Список пуст</li>
														<? foreach ($viData['posts'] as $key => $post): ?>
															<li data-id="<?=$key?>"><?=$post?></li>
														<? endforeach; ?>
													</ul>
													<input type="hidden" name="<?='post[' . $idCity . '][' . $idLoc . '][' . $idPer . ']'?>" value="<?=$arPer['post']?>">
												</div>
											</div>
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
					<a class="save-btn" href="<?=MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/index'?>">НАЗАД</a>
				</div>
			</div>
			<input type="hidden" name="project" value="<?=$project?>">
		</form>
	</div>
</div>
<?php
	$arPosts = $viData['posts'];
	require 'project-index-blocks.php'; // Блоки адресной программы для добавления
?>