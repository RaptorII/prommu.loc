<?php if(count($viData['location'])>0): ?>
	<table class="project__program" id="ajax-content">
		<tbody>
			<? foreach ($viData['location'] as $id => $arCity): ?>
				<tr class="program__item" data-city="<?=$id?>">
					<td colspan="5">
						<div class="program__city border">
							<b><?=$arCity['name']?></b>
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
											<a href="<? echo $arPer['project'] . '/users-select/' . $idper ?>" class="program-select-user__title">
												<span>Выбрать персонал </span>
												<b>&#9660</b>
											</a>
										</div>
									<?php else: ?>
										<a href="<? echo $arPer['project'] . '/users-select/' . $idper ?>"><span>Изменить</span></a>
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
												<li><a href="<? echo $arPer['project'] . '/address-edit?city=' . $id . '&per=' . $idper ?>">изменить</a></li>
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
			<? endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<br><p class="center">По заданым параметрам данных не найдено</p>
<?php endif; ?>