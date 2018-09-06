<?php if(sizeof($viData['items'])>0): ?>
	<?php
		$city = reset($viData['filter']['cities']);
		$date = $viData['filter']['bdate'];

		foreach ($viData['items'] as $arDate):
			foreach ($arDate as $id_city => $arCity):
	?>
		<div class="task__item">
			<h2 class="task__item-title"><?=$arCity['city']?> <span><?=$arCity['date']?></span></h2>
			<table class="task__table">
				<thead>
					<tr>
						<th class="user">ФИО</th>
						<th class="name">Название ТТ</th>
						<th class="index">Адрес ТТ</th>
						<? if(!empty($arCity['ismetro'])): ?><th class="metro">Метро</th><? endif; ?>
						<?//<th>Дата</th>?>
						<th class="task">Кол-во заданий</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($arCity['points'] as $point => $arUsers): 
							foreach ($arUsers as $id_user => $user): 
					?>
						<tr>
							<td <?//rowspan="3"?> class="user">
								<div class="task__table-cell task__table-user">
									<img src="<?=$user['src']?>">
									<span><?=$user['user']?></span>
								</div>
							</td>
							<td class="name">
								<div class="task__table-cell border"><?=$user['name']?></div>
							</td>
							<td class="index">
								<div class="task__table-cell border task__table-index">
									<span><?=$user['index']?></span>
									<b class="js-g-hashint" title="Посмотреть на карте"></b>
								</div>
							</td>
							<?php if(!empty($arCity['ismetro'])): ?>
								<td class="metro">
									<div class="task__table-cell border task__table-index">
										<span><?=$user['metro']?></span>
									</div>
								</td>					
							<?php endif; ?>
							<?/*<td>
								<div class="task__table-cell border text-center"><?=$date?></div>
							</td>*/?>
							<td class="task">
								<div class="task__table-cell border task__table-cnt">
									<? $tasks = sizeof($user['tasks']); ?>
									<span><?=$tasks?></span>
									<span 
										class="task__table-watch" 
										data-user="<?=$id_user?>"
										data-date="<?=$date?>"
										data-point="<?=$point?>"
										><?=($tasks?'изменить':'добавить')?></span>
								</div>
							</td>
						</tr>
					<?php
							endforeach;
						endforeach;
					?>
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