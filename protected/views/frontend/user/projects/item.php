<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);

	$s = $_GET['s'];
/*
echo "<pre>";
print_r($_SERVER); 
echo "</pre>";
*/
?>
<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
			<a href="<?=$_SERVER['REDIRECT_URL']?>" class="<?=(!isset($s) || empty($s))?'active':''?>">
				<b>ОСНОВНОЕ</b>
			</a>
			<a href="?s=staff" class="<?=$s=='staff'?'active':''?>">
				<b>ПЕРСОНАЛ</b>
			</a>
			<a href="?s=time" class="<?=$s=='time'?'active':''?>">
				<b>ДАТА И ВРЕМЯ РАБОТЫ</b>
			</a>
			<a href="?s=index" class="<?=$s=='index'?'active':''?>">
				<b>АДРЕСНАЯ ПРОГРАММА</b>
			</a>
			<a href="?s=control" class="<?=$s=='control'?'active':''?>">
				<b>КОНТРОЛЬ РАБОТЫ</b>
			</a>
			<a href="?s=route" class="<?=$s=='route'?'active':''?>">
				<b>МАРШРУТ</b>
			</a>
			<a href="?s=tasks" class="<?=$s=='tasks'?'active':''?>">
				<b>ЗАДАНИЯ</b>
			</a>
			<a href="?s=report" class="<?=$s=='report'?'active':''?>">
				<b>ОТЧЕТЫ</b>
			</a>
		</div>
		<div id="content">
			<?php 
			//
			//
			//
			?>
			<?php if(!isset($s) || empty($s)): ?>
			<div class="project__module">
				<div class="project__xls">
					<a href="#" id="add-program">Добавить адресную программу</a>
					<input type="file" name="xls" class="hide" accept="xls">
					<a href="#">Скачать пример для добавления</a>
				</div>
				<h1 class="project__title">ПРОЕКТ: <span>МЕРЧЕНДАЙЗИНГ</span></h1>
				<table class="project__program">
					<tbody>
						<tr class="program__item">
							<td colspan="4">
								<div class="program__city border">МОСКВА</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="program__cell green-name border">АТБ1</div>
							</td>
							<td>
								<div class="program__cell border">ул. Пирогова 23</div>
							</td>
							<td>
								<div class="program__cell border user">
									<div class="program__cell-user">
										<img src="/theme/pic/projects/user-logo.png">
										<span>Александр Примак</span>
									</div>
									<a href="#"><span>Изменить</span></a>
								</div>
							</td>
							<td>
								<div class="program__cell program__cell-period">
									<span>06.02.2018 до 07.02.2018</span>
									<span class="program__cell-tiem">12:00 - 14:00</span>
									<a href="#"><span>Изменить</span></a>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="program__cell green-name border">АТБ1</div>
							</td>
							<td>
								<div class="program__cell border">ул. Паторжинского 23/4</div>
							</td>
							<td>
								<div class="program__cell border user">
									<a href="#"><span>Выбрать персонал </span><b>&#9660</b></a>
								</div>
							</td>
							<td>
								<div class="program__cell program__form">
									<div class="program__input">
										<input type="text" name="bdate[]" placeholder="Дата начала работ">
									</div>
									<div class="program__input">
										<input type="text" name="edate[]" placeholder="Дата окончания работ">
									</div>
									<div class="program__time">
										<div class="program__input">
											<input type="text" name="btime[]" placeholder="Время с">
										</div>
										<div class="program__input">
											<input type="text" name="etime[]" placeholder="Время до">
										</div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<div class="program__btns">
									<a href="#" class="program__add-btn">+ ДОБАВИТЬ ПЕРИОД</a>
									<a href="#" class="program__save-btn">СОХРАНИТЬ</a>
								</div>
							</td>
						</tr>
						<?
						//
						?>
						<tr class="program__item">
							<td colspan="4">
								<div class="program__city border">РОСТОВ</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="program__cell green-name border">АТБ1</div>
							</td>
							<td>
								<div class="program__cell border">ул. Пирогова 23</div>
							</td>
							<td>
								<div class="program__cell border user">
									<div class="program__cell-user">
										<img src="/theme/pic/projects/user-logo.png">
										<span>Александр Примак</span>
									</div>
									<a href="#"><span>Изменить</span></a>
								</div>
							</td>
							<td>
								<div class="program__cell program__cell-period">
									<span>06.02.2018 до 07.02.2018</span>
									<span class="program__cell-tiem">12:00 - 14:00</span>
									<a href="#"><span>Изменить</span></a>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="program__cell green-name border">АТБ1</div>
							</td>
							<td>
								<div class="program__cell border">ул. Паторжинского 23/4</div>
							</td>
							<td>
								<div class="program__cell border user">
									<a href="#"><span>Выбрать персонал </span><b>&#9660</b></a>
								</div>
							</td>
							<td>
								<div class="program__cell program__form">
									<div class="program__input">
										<input type="text" name="bdate[]" placeholder="Дата начала работ">
									</div>
									<div class="program__input">
										<input type="text" name="edate[]" placeholder="Дата окончания работ">
									</div>
									<div class="program__time">
										<div class="program__input">
											<input type="text" name="btime[]" placeholder="Время с">
										</div>
										<div class="program__input">
											<input type="text" name="etime[]" placeholder="Время до">
										</div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<div class="program__btns">
									<a href="#" class="program__add-btn">+ ДОБАВИТЬ ПЕРИОД</a>
									<a href="#" class="program__save-btn">СОХРАНИТЬ</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='staff'): ?>
			<div class="project__module">
				<h1 class="project__title personal__title">ПЕРСОНАЛ</h1>
				<div class="row">
					<div class="col-xs-12 col-sm-4 col-md-3">
						<div class="personal__item">
							<img src="/images/applic/20180503160015590100.jpg">
							<div class="personal__item-name">Шишкина Виктория</div>
							<div class="personal__item-add">
								<a href="#">Закрепленные адреса</a>
							</div>
							<div class="personal__item-city">Ростов</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-3">
						<div class="personal__item">
							<img src="/images/applic/20180503073112204100.jpg">
							<div class="personal__item-name">Ибадулаев Павел</div>
							<div class="personal__item-add">
								<a href="#">Закрепленные адреса</a>
							</div>
							<div class="personal__item-city">Москва</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-3">
						<div class="personal__item">
							<img src="/images/applic/20180430140946442100.jpg">
							<div class="personal__item-name">Немич Константин</div>
							<div class="personal__item-add">
								<a href="#">Закрепленные адреса</a>
							</div>
							<div class="personal__item-city">Новгород</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-3">
						<div class="personal__item">
							<img src="/images/applic/20180428142455264100.jpg">
							<div class="personal__item-name">Простова Ольга</div>
							<div class="personal__item-add">
								<a href="#">Закрепленные адреса</a>
							</div>
							<div class="personal__item-city">Рыбинск</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='time'): ?>
			<div class="project__module">3</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='index'): ?>
			<div class="project__module">
				<div class="project__addr-header">
					<div class="project__addr-xls">
						<a href="#">Изменить адресную программу</a>
						<a href="#">Скачать существующую</a>
						<a href="#">Добавить адресную программу</a>
						<input type="file" name="xls" class="hide" accept="xls">
					</div>
					<div class="project__addr-filter">
						<div>
							<label>Город</label>
							<input type="text" name="city">
						</div>
						<div>
							<label>Дата с</label>
							<input type="text" name="bdate" class="addr__filter-date">
						</div>
						<div>
							<label>По</label>
							<input type="text" name="edate" class="addr__filter-date">
						</div>
					</div>
				</div>
				<div class="addresses">
					<div class="address__item">
						<h2 class="address__item-title">москва</h2>
						<table class="addr__table">
							<thead>
								<tr>
									<th>Название</th>
									<th>Адрес</th>
									<th>Дата</th>
									<th>Время</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="addr__table-cell border">АТБ1</div>
									</td>
									<td>
										<div class="addr__table-cell border">ул. Исполкомовская 123</div>
									</td>
									<td>
										<div class="addr__table-cell border text-center">07.02.2018 – 08.02.2018</div>
									</td>
									<td>
										<div class="addr__table-cell border text-center">14:00 – 16:00</div>
									</td>
									<td>
										<div class="addr__table-cell text-center">
											<a href="#">изменить</a>
										</div>
									</td>
								</tr>



								<tr>
									<td>
										<div class="addr__table-cell border">АТБ1</div>
									</td>
									<td>
										<div class="addr__table-cell border">ул. Исполкомовская 123</div>
									</td>
									<td>
										<div class="addr__table-cell border text-center">07.02.2018 – 08.02.2018</div>
									</td>
									<td>
										<div class="addr__table-cell border text-center">14:00 – 16:00</div>
									</td>
									<td>
										<div class="addr__table-cell text-center">
											<a href="#">изменить</a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="addresses__btns">
					<a href="#" class="addr__save-btn">Добавить</a>
				</div>	
			</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='control'): ?>
			<div class="project__module">5</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='route'): ?>
			<div class="project__module">6</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='tasks'): ?>
			<div class="project__module">7</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='report'): ?>
			<div class="project__module">8</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<div class="bg_veil"></div>
<div class="personal__map">
	<div class="personal__map-header">
		<span>Простова Ольга</span>
		<b></b>
	</div>
	<div class="personal__map-map">
		<img src="/theme/pic/projects/temp-map.jpg">
	</div>
</div>