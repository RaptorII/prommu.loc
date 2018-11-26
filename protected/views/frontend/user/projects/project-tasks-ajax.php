<?php if(sizeof($viData['items'])>0): ?>
	<?php
		foreach ($viData['items'] as $d => $date):
			foreach ($date as $city):
	?>
		<div class="task__item">
			<h2 class="task__item-title"><?=$city['city']?> <span><?=$city['date']?></span></h2>
			<table class="task__table">
				<thead>
					<tr>
						<th class="user">ФИО</th>
						<th class="name">Название ТТ</th>
						<th class="index">Адрес ТТ</th>
						<th class="task">Кол-во заданий</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($city['users'] as $idus => $arPoints): ?>
						<?php $user = $viData['users'][$idus]; ?>


						<tr>
							<td rowspan="<?=sizeof($arPoints)?>" class="user">
								<div class="task__table-cell task__table-user">
									<img src="<?=$user['src']?>">
									<span><?=$user['name']?></span>
								</div>
							</td>
							<?php $cnt = 0; ?>
							<?php foreach ($arPoints as $p): ?>
								<?php $point = $viData['points'][$p]; ?>



								<td class="name">
									<div class="task__table-cell border">
                                        <?=$point['name']?>
                                    </div>
								</td>
								<td class="index">
									<div class="task__table-cell border task__table-index">

										<span><?=$point['adres']?>


                                        <?if(!empty($point['metro'])):?>
                                            <span>
                                                <img title="<?=$point['metro']?>" class="point__metro js-g-hashint" src="/theme/pic/projects/metro.png"/>
                                            </span>
                                        <?endif;?>
                                            </span>

										<b data-map-project="<?=$project?>"
                                           data-map-user="<?=$user['id_user']?>"
                                           data-map-point="<?=$point['point']?>"
                                           data-map-date="<?=$d?>"
                                           class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
									</div>
								</td>
								<?/*php if(!empty($city['ismetro'])): ?>
									<td class="metro">
										<div class="task__table-cell border task__table-index">
											<span><?=$point['metro']?></span>
										</div>
									</td>					
								<?php endif; */?>
								<td class="task">
									<div class="task__table-cell border task__table-cnt">
										<? $tasks = sizeof($viData['tasks'][$d][$p][$idus]); ?>
										<span
                                            class="tasks__count"
                                            data-popup-project="<?=$project?>"
                                            data-popup-user="<?=$user['id_user']?>"
                                            data-popup-point="<?=$point['point']?>"
                                            data-popup-date="<?=$d?>"


                                        ><?=$tasks?></span>
										<span 
											class="task__table-watch" 
											data-user="<?=$idus?>"
											data-date="<?=$city['date']?>"
											data-point="<?=$p?>"
											><?=($tasks?'изменить':'добавить')?></span>
									</div>
								</td>
								<?php $cnt++; ?>
								<? if($cnt<sizeof($viData['points'])) echo '</tr><tr>'; ?>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php
			endforeach;
		endforeach;
	?>
<?php else: ?>
	<br><p class="center">Не найдено локаций с выбранным персоналом</p>
<?php endif; ?>