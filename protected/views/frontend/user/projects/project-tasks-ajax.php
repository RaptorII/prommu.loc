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
						<th>ФИО</th>
						<th>Название ТТ</th>
						<th>Адрес ТТ</th>
						<th>Дата</th>
						<th>Кол-во заданий</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach ($arCity['points'] as $point => $arUsers): 
							foreach ($arUsers as $id_user => $user): 
					?>
						<tr>
							<td <?//rowspan="3"?>>
								<div class="task__table-cell task__table-user">
									<img src="<?=$user['src']?>">
									<span><?=$user['user']?></span>
								</div>
							</td>
							<td>
								<div class="task__table-cell border"><?=$user['name']?></div>
							</td>
							<td>
								<div class="task__table-cell border task__table-index">
									<span><?=$user['index']?></span>
									<b class="js-g-hashint" title="Посмотреть на карте"></b>
								</div>
							</td>
							<td>
								<div class="task__table-cell border text-center"><?=$date?></div>
							</td>
							<td>
								<div class="task__table-cell border task__table-cnt">
									<span>!!!</span>
									<a href="#" class="task__table-watch">посмотреть</a>
									<a href="#">добавить</a>
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
	<p class="center">Не найдено локаций с выбранным персоналом</p>
<?php endif; ?>