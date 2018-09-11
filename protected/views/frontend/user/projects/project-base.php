<?php
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item-base.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/item-base.js', CClientScript::POS_END);
?>
<div class="row project">
	<div class="col-xs-12">
		<? require __DIR__ . '/project-nav.php'; // Меню вкладок ?>
		<div id="content">
			<div class="project__module">
				<div class="project__xls">
					<a href="/user/uploadprojectxls?id=<?=$project?>&type=index" id="add-xls">Изменить адресную программу</a>
					<a href="/uploads/prommu_example.xls" download>Скачать пример для добавления</a>
				</div>
				<h1 class="project__title">ПРОЕКТ: <span><?=$viData['title']?></span></h1>
				<table class="project__program">
					<tbody>
						<? foreach ($viData['location'] as $id => $arCity): ?>
							<tr class="program__item" data-city="<?=$id?>">
								<td colspan="5">
									<div class="program__city border">
										<b><?=$arCity['name']?></b>
										<span class="address__item-change">
											<span>изменить</span>
											<ul>
												<li><a href="<? echo $project . '/address-edit?city=' . $id ?>">изменить</a></li>
												<li data-id="<?=$id?>" class="delcity">удалить</li>
											</ul>
										</span>
									</div>
								</td>
							</tr>
							<? foreach ($arCity['locations'] as $idloc => $arLoc): ?>
								<tr class="loc-item" data-city="<?=$id?>">
									<td>
										<div class="program__cell green-name"><?=$arLoc['name']?></div>
									</td>
									<td <?=(empty($arCity['metro'])?'colspan="2"':'')?>>
										<div class="program__cell border"><?=$arLoc['index']?></div>
									</td>
									<? if(!empty($arCity['metro'])): ?>
										<td>
											<div class="program__cell border"><? echo join(',</br>', $arLoc['metro']) ?></div>
										</td>
									<? endif; ?>
									<td>
										<div class="program__cell border user">
											<?php 
											$arUsers = array();
											foreach ($arLoc['periods'] as $idper => $arPer):
												foreach ($viData['users'] as $id_user => $user):
													if(in_array($idper, $user['points'])):
														$arUsers[$idper][] = $id_user;
														?>
														<div class="program__cell-users">
															<div class="program__cell-user">
																<img src="<?=$user['src']?>">
																<span><?=$user['name']?></span>
																
															</div>
														</div>									
													<?php endif; ?>
												<?php endforeach; ?>
												<?php if(!sizeof($arUsers[$idper])): ?>
													<div class="program__select-user" data-period="<?=$idper?>">
														<a href="<? echo $project . '/users-select/' . $idper ?>" class="program-select-user__title">
															<span>Выбрать персонал </span>
															<b>&#9660</b>
														</a>
													</div>
												<?php else: ?>
													<a href="<? echo $project . '/users-select/' . $idper ?>"><span>Изменить</span></a>
												<?php endif; ?>
											<?php endforeach; ?>
										</div>
									</td>
									<td class="period-data">
										<div class="program__cell border">
											<? foreach ($arLoc['periods'] as $idper => $arPer): ?>
												<div class="program__cell-period" data-period="<?=$idper?>">
													<span><? echo $arPer['bdate'] . ' до ' . $arPer['edate'] ?></span>
													<span class="program__cell-tiem"><? echo $arPer['btime'] . ' - ' . $arPer['etime'] ?></span>
													<span class="address__item-change period">
														<span>изменить</span>
														<ul>
															<li><a href="<? echo $project . '/address-edit?city=' . $id . '&per=' . $idper ?>">изменить</a></li>
															<li data-id="<?=$idper?>" class="delperiod">удалить</li>
														</ul>
													</span>
												</div>
												<?php foreach ($arUsers[$idper] as $u){ echo "<br>"; } ?>
											<? endforeach; ?>
										</div>
									</td>
								</tr>
							<? endforeach; ?>
							<?
							/*
							?>
							<tr data-city="<?=$id?>">
								<td colspan="5">
									<div class="program__btns">
										<a href="#" class="program__add-btn">+ ДОБАВИТЬ ПЕРИОД</a>
										<a href="#" class="program__save-btn">СОХРАНИТЬ</a>
									</div>
								</td>
							</tr>
							<?
							*/
							?>
						<? endforeach; ?>
					</tbody>
				</table>
				<form enctype="multipart/form-data" action="" method="POST" id="base-form">
					<input type="hidden" name="project" class="project-inp" value="<?=$project?>">
					<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
					<input type="file" name="xls" id="add-xls-inp" class="hide">
					<input type="hidden" name="xls-index" value="1">
				</form>
			</div>
		</div>
	</div>
</div>