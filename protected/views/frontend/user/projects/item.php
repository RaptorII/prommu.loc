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
			<a href="?s=geo" class="<?=$s=='geo'?'active':''?>">
				<b>ГЕОЛОКАЦИЯ</b>
			</a>
			<a href="?s=route" class="<?=$s=='route'?'active':''?>">
				<b>МАРШРУТ ГЕО</b>
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
			<div class="project__module">???</div>
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
						<div class="addr__header-city">
							<label>Город</label>
							<input type="text" name="city">
						</div>
						<div class="addr__header-date">
							<div>
								<label>Дата с</label>
								<input type="text" name="bdate">
							</div>
							<div>
								<label>По</label>
								<input type="text" name="edate">
							</div>
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
			<?php elseif($s=='geo'): ?>
			<div class="project__module">
				<div class="project__geo-filter">
					<div class="geo__header-city">
						<label>Город</label>
						<input type="text" name="city">
					</div>
					<div class="geo__header-date">
						<div>
							<label>Дата с</label>
							<input type="text" name="bdate">
						</div>
						<div>
							<label>По</label>
							<input type="text" name="edate">
						</div>
					</div>
				</div>
				<div class="project__geo-list">
					<div class="project__geo-item">
						<h2 class="geo__item-title">город: <span>Москва</span></h2>
						<table class="geo__item-table">
							<thead>
								<tr>
									<th>Сотрудник</th>
									<th>Статус</th>
									<th>Кол-во ТТ</th>
									<th>Старт работы</th>
									<th>Последнее место</th>
									<th>Дата</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="geo__table-cell geo__table-user">
											<img src="/images/applic/20180503073112204100.jpg">
											<span>Ибадулаев<br/>Павел</span>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<span class="geo__green">&#9679 активен</span>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">5</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<span class="geo__green">начал</span>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<div class="geo__table-cell geo__table-loc">
												<span>АТБ1</span>
												<b class="js-g-hashint" title="Посмотреть на карте"></b>
											</div>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">06.02.2018</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<a href="#">подробнее</a>
										</div>
									</td>
								</tr>
								<?
								//
								?>
								<tr>
									<td>
										<div class="geo__table-cell geo__table-user">
											<img src="/images/applic/20180428142455264100.jpg">
											<span>Бондаренко<br/>Наталья</span>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<span class="geo__grey">нет</span>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">5</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<span class="geo__red">просрочил</span>
										</div>
									</td>
									<td>
										<div class="geo__table-cell geo__table-loc">
											<span>АТБ1</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="geo__table-cell">06.02.2018</div>
									</td>
									<td>
										<div class="geo__table-cell">
											<a href="#">подробнее</a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?
				//
				?>
				<div class="geo__item-cart">
					<div class="geo-item__cart-data">
						<img src="/images/applic/20180503073112204100.jpg">
						<div class="geo-item__cart-info">
							<div class="geo-item__cart-filter">
								<div class="geo-item__filter-date">
									<label>Дата с</label>
									<input type="text" name="bdate" placeholder="<?=date('d.m.y')?>">
								</div>
								<div class="geo-item__filter-date">
									<label>По</label>
									<input type="text" name="edate" placeholder="<?=date('d.m.y')?>">
								</div>
							</div>

							<div class="geo-item__cart-bl1">
								<div class="geo-item__cart-name">Ибадулаев Павел</div>
								<div class="geo-item__cart-border">
									<div>
										<span class="geo__green">&#9679 активен</span> / <span class="geo__red">&#9679 неактивен</span>
									</div>
									<div>Дата: 06.02.2018</div>
								</div>
							</div>

							<div class="geo-item__cart-bl2">
								<div>
									<div>
										<span>Старт работ: </span>
										<span class="geo__green">начал в 9:30</span>
										<span> / </span>
										<span class="geo__red">опоздание на 20 мин.</span>
									</div>
									<div>
										<span>Последнее место: АТБ1 </span>
										<b></b>
									</div>
								</div>
								<div class="geo-item__cart-cur">
									<div><span>Сейчас в: АТБ1 </span><b></b></div>
									<a href="#" class="geo-item__route">Показаь маршрут передвижения</a>
								</div>
							</div>
						</div>
					</div>
					<table class="geo__item-table geo-item__table-single">
						<thead>
							<tr>
								<th>Название</th>
								<th>Адрес</th>
								<th>План работ</th>
								<th>Факт работ</th>
								<th>Задачи по ТТ</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="geo__table-cell">АТБ1</div>
								</td>
								<td>
									<div class="geo__table-cell geo__table-loc">
										<span>ул. Пирогова 23</span>
										<b class="js-g-hashint" title="Посмотреть на карте"></b>
									</div>
								</td>
								<td>
									<div class="geo__table-cell">07.02.2018  14:00</div>
								</td>
								<td>
									<div class="geo__table-cell">07.02.2018 с 9:00 до 18:00</div>
								</td>
								<td>
									<div class="geo__table-cell">12</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>	
			</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='route'): ?>
			<div class="project__module">
				<div class="project__route-header">
					<div class="project__addr-xls">
						<a href="#">Изменить адресную программу</a>
						<a href="#">Скачать существующую</a>
						<a href="#">Добавить адресную программу</a>
						<input type="file" name="xls" class="hide" accept="xls">
					</div>
					<div class="project__route-filter">
						<div class="route__header-city">
							<label>Город</label>
							<input type="text" name="city">
						</div>
						<div class="route__header-date">
							<div>
								<label>Дата с</label>
								<input type="text" name="bdate" class="route__filter-date">
							</div>
							<div>
								<label>По</label>
								<input type="text" name="edate" class="route__filter-date">
							</div>
						</div>
					</div>
				</div>
				<div class="routes">
					<div class="route__item">
						<h2 class="route__item-title">Харьков</h2>
						<table class="route__table">
							<thead>
								<tr>
									<th>ФИО</th>
									<th>Название ТТ</th>
									<th>Адрес ТТ</th>
									<th>Статус посещения</th>
									<th>Дата</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td rowspan="3">
										<div class="route__table-cell route__table-user">
											<img src="/images/applic/20180503073112204100.jpg">
											<span>Дмитриев<br/>Николай</span>
										</div>
									</td>
									<td>
										<div class="route__table-cell border">АТБ1</div>
									</td>
									<td>
										<div class="route__table-cell border route__table-index">
											<span>ул. Пирогова 23</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="route__table-cell border route__table-status">
											<span>2</span>
											<a href="#">изменить</a>
										</div>
									</td>
									<td>
										<div class="route__table-cell border text-center">14.02.2018</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="route__table-cell border">ВАРУС</div>
									</td>
									<td>
										<div class="route__table-cell border route__table-index">
											<span>пр. Кирова 18</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="route__table-cell border route__table-status">
											<span>1</span>
											<a href="#">изменить</a>
										</div>
									</td>
									<td>
										<div class="route__table-cell border text-center">14.02.2018</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="route__table-cell border">СЕЛЬПО</div>
									</td>
									<td>
										<div class="route__table-cell border route__table-index">
											<span>ул. Строителей 4</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="route__table-cell border route__table-status">
											<span>3</span>
											<a href="#">изменить</a>
										</div>
									</td>
									<td>
										<div class="route__table-cell border text-center">14.02.2018</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="routes__map">
					<div class="routes__map-city">Харьков</div>
					<div class="routes__map-map">
						<img src="/theme/pic/projects/temp-map-2.jpg">
					</div>
				</div>
				<div class="routes__btns">
					<a href="#" class="route__watch-btn">ИЗМЕНИТЬ</a>
					<a href="#" class="route__watch-btn">СМОТРЕТЬ МАРШРУТ</a>
				</div>
			</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='tasks'): ?>
			<div class="project__module">
				<div class="project__tasks-filter">
					<div class="tasks__header-city">
						<label>Город</label>
						<input type="text" name="city">
					</div>
					<div class="tasks__header-date">
						<div>
							<label>Дата с</label>
							<input type="text" name="bdate">
						</div>
						<div>
							<label>По</label>
							<input type="text" name="edate">
						</div>
					</div>
				</div>
				<div class="tasks">
					<div class="task__item">
						<h2 class="task__item-title">Харьков <span>14.02.2018</span></h2>
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
								<tr>
									<td rowspan="3">
										<div class="task__table-cell task__table-user">
											<img src="/images/applic/20180503073112204100.jpg">
											<span>Дмитриев<br/>Николай</span>
										</div>
									</td>
									<td>
										<div class="task__table-cell border">АТБ1</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-index">
											<span>ул. Пирогова 23</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="task__table-cell border text-center">14.02.2018</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-cnt">
											<span>3</span>
											<a href="#" class="task__table-watch">посмотреть</a>
											<a href="#">добавить</a>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="task__table-cell border">ВАРУС</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-index">
											<span>пр. Кирова 18</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="task__table-cell border text-center">14.02.2018</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-cnt">
											<span>3</span>
											<a href="#" class="task__table-watch">посмотреть</a>
											<a href="#">добавить</a>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="task__table-cell border">СЕЛЬПО</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-index">
											<span>ул. Строителей 4</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="task__table-cell border text-center">14.02.2018</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-cnt">
											<span>3</span>
											<a href="#" class="task__table-watch">посмотреть</a>
											<a href="#">добавить</a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?
					//
					?>
					<div class="task__item">
						<h2 class="task__item-title">Москва <span>15.02.2018</span></h2>
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
								<tr>
									<td rowspan="3">
										<div class="task__table-cell task__table-user">
											<img src="/images/applic/20180428142455264100.jpg">
											<span>Наталья<br/>Гуторова</span>
										</div>
									</td>
									<td>
										<div class="task__table-cell border">АТБ1</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-index">
											<span>ул. Пирогова 23</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="task__table-cell border text-center">15.02.2018</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-cnt">
											<span>3</span>
											<a href="#" class="task__table-watch">посмотреть</a>
											<a href="#">добавить</a>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="task__table-cell border">ВАРУС</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-index">
											<span>пр. Кирова 18</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="task__table-cell border text-center">15.02.2018</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-cnt">
											<span>3</span>
											<a href="#" class="task__table-watch">посмотреть</a>
											<a href="#">добавить</a>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="task__table-cell border">СЕЛЬПО</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-index">
											<span>ул. Строителей 4</span>
											<b class="js-g-hashint" title="Посмотреть на карте"></b>
										</div>
									</td>
									<td>
										<div class="task__table-cell border text-center">15.02.2018</div>
									</td>
									<td>
										<div class="task__table-cell border task__table-cnt">
											<span>3</span>
											<a href="#" class="task__table-watch">посмотреть</a>
											<a href="#">добавить</a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?
				//
				?>
				<div class="task__single">
					<div class="task__single-logo">
						<img src="/images/applic/20180503073112204100.jpg">
					</div>
					<div class="task__single-info">
						<h2 class="task__single-title">АТБ1</h2>
						<table class="task__single-table">
							<tr>
								<td rowspan="3">
									<div class="task__single-user">
										<div class="task__user-name">Дмитриев Николай</div>
										<div class="task__user-index"><b>ул. Пирогова 147</b></div>
										<div class="task__user-date">14.08.2018</div>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<input type="radio" name="task" id="task_1" checked>
										<label for="task_1">Видеть заказ</label>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">изменить</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#" class="task-single__double">Дублироать на все даты</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">Дублироать задачу всем</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="task-single__cell">
										<input type="radio" name="task" id="task_2">
										<label for="task_2">Проверить ценник</label>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">изменить</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#" class="task-single__double">Дублироать на все даты</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">Дублироать задачу всем</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="task-single__cell">
										<input type="radio" name="task" id="task_3">
										<label for="task_3">Сдать форму</label>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">изменить</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#" class="task-single__double">Дублироать на все даты</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">Дублироать задачу всем</a>
									</div>
								</td>
							</tr>
						</table>
						<textarea placeholder="Опишите задание..."></textarea>
						<div class="task__single-info-btn">
							<a href="#">ДОБАВИТЬ ЗАДАЧУ</a>
						</div>
						<?
						//
						?>
						<h2 class="task__single-title">АТБ1</h2>
						<table class="task__single-table">
							<tr>
								<td rowspan="3">
									<div class="task__single-user">
										<div class="task__user-name">Дмитриев Николай</div>
										<div class="task__user-index"><b>ул. Пирогова 147</b></div>
										<div class="task__user-date">14.08.2018</div>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<input type="radio" name="task" id="task_1" checked>
										<label for="task_1">Видеть заказ</label>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">изменить</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#" class="task-single__double">Дублироать на все даты</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">Дублироать задачу всем</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="task-single__cell">
										<input type="radio" name="task" id="task_2">
										<label for="task_2">Проверить ценник</label>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">изменить</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#" class="task-single__double">Дублироать на все даты</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">Дублироать задачу всем</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="task-single__cell">
										<input type="radio" name="task" id="task_3">
										<label for="task_3">Сдать форму</label>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">изменить</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#" class="task-single__double">Дублироать на все даты</a>
									</div>
								</td>
								<td>
									<div class="task-single__cell">
										<a href="#">Дублироать задачу всем</a>
									</div>
								</td>
							</tr>
						</table>
						<textarea placeholder="Опишите задание..."></textarea>
						<div class="task__single-info-btn">
							<a href="#">ДОБАВИТЬ ЗАДАЧУ</a>
						</div>
					</div>
				</div>
			</div>
			<?php 
			//
			//
			//
			?>
			<?php elseif($s=='report'): ?>
			<div class="project__module">???</div>
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