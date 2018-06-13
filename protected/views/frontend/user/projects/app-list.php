<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/app-list.css');
?>
<div class="row projects">
	<div class="col-xs-12">
		<table class="projects__table">
			<thead>
				<tr>
					<th rowspan="2">Название и адрес ТТ</th>
					<th rowspan="2">Факт прибытия</th>
					<th rowspan="2">Факт убытия</th>
					<th rowspan="2">Факт пребывания в ТТ</th>
					<th colspan="2">Задачи</th>
					<th rowspan="2">
						<span>Выбрать дату:</span>
						<div id="filter-date">
							<span>20.02.2018</span>
						</div>
					</th>
				</tr>
				<tr>
					<th>план</th>
					<th>факт</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div class="prj-table__cell">
							<span>ул. Пирогова 23</span> <span class="green-t text-uppercase">атб1</span>
						</div>
					</td>
					<td>
						<div class="prj-table__cell">в 9:00</div>
					</td>
					<td>
						<div class="prj-table__cell">в 13:00</div>
					</td>
					<td>
						<div class="prj-table__cell green-t">4 часа</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">
							<a href="#" class="prj-table__btn active">СМОТРЕТЬ</a>
						</div>
					</td>
				</tr>
				<tr class="prj-table__item-head">
					<td>Задачи</td>
					<td colspan="2">Статус выполнения</td>
					<td colspan="4">Примечание</td>
				</tr>
				<tr class="prj-table__item-body">
					<td>выносная торговля возле супермаркета Сильпо</td>
					<td colspan="2" class="text-center"><span class="green-t">ВЫПОЛНЕН</span></td>
					<td colspan="4" class="text-center">на улице мороз, взять чай в термосе</td>
				</tr>
				<tr class="prj-table__item-body">
					<td>уборка территории парка «Юность»</td>
					<td colspan="2" class="text-center"><span class="red-t">НЕ ВЫПОЛНЕН</span></td>
					<td colspan="4" class="text-center">весь парк в листьях, никто не убрал!!!</td>
				</tr>
				<tr>
					<td>
						<div class="prj-table__cell">
							<span>ул. Пирогова 23</span> <span class="green-t text-uppercase">атб1</span>
						</div>
					</td>
					<td>
						<div class="prj-table__cell">в 9:00</div>
					</td>
					<td>
						<div class="prj-table__cell">в 13:00</div>
					</td>
					<td>
						<div class="prj-table__cell green-t">4 часа</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">
							<a href="#" class="prj-table__btn">СМОТРЕТЬ</a>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="prj-table__cell">
							<span>ул. Пирогова 23</span> <span class="green-t text-uppercase">атб1</span>
						</div>
					</td>
					<td>
						<div class="prj-table__cell">в 9:00</div>
					</td>
					<td>
						<div class="prj-table__cell">в 13:00</div>
					</td>
					<td>
						<div class="prj-table__cell green-t">4 часа</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">
							<a href="#" class="prj-table__btn">СМОТРЕТЬ</a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>