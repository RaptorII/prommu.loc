<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/new.js', CClientScript::POS_END);
?>
<div class="row project">
	<div class="col-xs-12">
		<form action="" method="POST" id="new-project">
			<div class="project__tabs">
				<?/*
				<a href="#" data-href="project-1">ПЕРСОНАЛ</a>
				<a href="#" data-href="index">АДРЕСНАЯ ПРОГРАММА</a>
				<a href="#" data-href="project-2">КОНТРОЛЬ ПЕРСОНАЛА</a>
				<a href="#" data-href="project-3">ЗАДАЧИ</a>
				*/?>
			</div>
			<div id="content">
				<div id="main" class="project__module">
					<div class="project__name">
						<input type="text" name="name" placeholder="*Введите название проекта" autocomplete="off" id="project-name">
					</div>
					<div class="project__block">
						<div class="project__opt">
							<div class="project__opt-row">
								<span>Добавить адресную программу</span>
								<span class="project__opt-btn" data-event="index">Выбрать</span>
							</div>
							<div class="project__opt-row">
								<span>Добавить адресную программу через XLS</span>
								<span class="project__opt-btn" id="add-xls">Выбрать</span>

								<input type="file" name="xls" id="add-xls-inp">
							</div>
							<div id="add-xls-name"></div>
							<div class="project__opt-row">
								<a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_PROJECT_NEW . '?get_xls=1')?>" class="project__opt-xls">Скачать пример адреной программы</a>
							</div>
						</div>
						<div class="project__opt">
							<div class="project__opt-row">
								<span>Добавить новый персонал на проект</span>
								<span class="project__opt-btn" data-event="addition">Выбрать</span>
							</div>
							<div class="project__opt-row">
								<span>Пригласить персонал на проект</span>
								<span class="project__opt-btn" data-event="invitation">Выбрать</span>
							</div>
						</div>
					</div>
					<div class="project__all-btns">
						<div class="project__main-btn" data-page="main">
							<span class="save-btn" id="save-project">СОХРАНИТЬ</span>
						</div>
					</div>
				</div>
				<div id="index" class="project__module" data-country="1">
					<h2 class="project__title"><b>добавить адресную программу</b></h2>
					<div class="project__body" data-city="">
						<div class="project__index-col">
							<label class="project__lbl-sm"><b>Город</b></label>
							<div class="city-field project__index-arrow">
								<span class="city-select"></span>
								<input type="text" name="c" class="city-inp" autocomplete="off">
								<ul class="select-list"></ul>
								<input type="hidden" name="city[]" value="">
							</div>
						</div>
						<div class="project__index-col project__index-pen loc-part">
							<label class="project__lbl-lg"><b>Адрес ТТ</b></label>
							<input type="text" name="tt-index[]">
						</div>
						<div class="project__index-col project__index-pen loc-part">
							<label><b>Название ТТ</b></label>
							<input type="text" name="tt-name[]">
						</div>

						<div class="project__index-col">
							<div class="project__index-period">
								<div class="project__index-arrow period-item">
									<label class="project__lbl-sm"><b>Дата</b></label>
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
									<input type="hidden" name="date-from[]">
								</div>
								<div class="project__index-arrow period-item">
									<label class="project__lbl-xs"><b>по</b></label>
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
									<input type="hidden" name="date-to[]">
								</div>
							</div>
						</div>
						<div class="project__index-col">
							<div class="project__index-period project__index-time">
								<div class="project__index-pen">
									<label class="project__lbl-lg"><b>Время работы</b></label>
									<input type="text" name="time-from[]" class="time-inp">
								</div>
								<div class="project__index-pen">
									<label><b>по</b></label>
									<input type="text" name="time-to[]" class="time-inp">
								</div>
							</div>
						</div>
						<div class="project__index-col">
							<div class="project__period-btn">
								<span class="add-period-btn">Добавить период</span>
							</div>
						</div>
					</div>
					<div class="project__all-btns">
						<div class="project__index-btns">
							<span class="project__btn-green add-loc-btn">ДОБАВИТЬ ЕЩЕ ТТ</span>
							<span class="project__btn-white" id="add-city-btn">ДОБАВИТЬ ГОРОД</span>
						</div>
						<span class="save-btn" id="save-index">СОХРАНИТЬ</span>
					</div>
				</div>
				<div id="invitation" class="project__module">
					<h2 class="project__title">ПРИГЛАСИТЬ В ПРОЕКТ <span>«мерчендайзинг»</span></h2>
					<div class="project__body project__body-invite">
						<div class="invitation-item">
							<label>
								<input type="text" name="invite-name[]" placeholder="Имя">
							</label>
							<label>
								<input type="text" name="invite-surname[]" placeholder="Фамилия">
							</label>
							<label>
								<input type="text" name="invite-phone[]" placeholder="Телефон">
							</label>
							<label>
								<input type="text" name="invite-email[]" placeholder="E-mail">
							</label>
						</div>
					</div>
					<div class="project__all-btns">
						<div class="project__invite-btns">
							<a href="#" class="project__btn-white">+ДОБАВИТЬ ЕЩЕ ПЕРСОНАЛ</a>
							<a href="#" class="save-btn">СОХРАНИТЬ</a>
						</div>
					</div>
				</div>
			</div>
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
	<div class="project__all-btns">
		<div class="project__index-btns">
			<span class="project__btn-green add-loc-btn">ДОБАВИТЬ ЕЩЕ ТТ</span>
		</div>
	</div>
	<div class="project__body" data-city="">
		<div class="project__index-col">
			<label class="project__lbl-sm"><b>Город</b></label>
			<div class="city-field project__index-arrow">
				<span class="city-select"></span>
				<input type="text" name="c" class="city-inp" autocomplete="off">
				<ul class="select-list"></ul>
				<input type="hidden" name="city[]" value="">
			</div>
		</div>
		<div class="project__index-col project__index-pen loc-part">
			<label class="project__lbl-lg"><b>Адрес ТТ</b></label>
			<input type="text" name="tt-index[]">
		</div>
		<div class="project__index-col project__index-pen loc-part">
			<label><b>Название ТТ</b></label>
			<input type="text" name="tt-name[]">
		</div>

		<div class="project__index-col">
			<div class="project__index-period">
				<div class="project__index-arrow period-item">
					<label class="project__lbl-sm"><b>Дата</b></label>
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
					<input type="hidden" name="date-from[]">
				</div>
				<div class="project__index-arrow period-item">
					<label class="project__lbl-xs"><b>по</b></label>
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
					<input type="hidden" name="date-to[]">
				</div>
			</div>
		</div>
		<div class="project__index-col">
			<div class="project__index-period project__index-time">
				<div class="project__index-pen">
					<label class="project__lbl-lg"><b>Время работы</b></label>
					<input type="text" name="time-from[]" class="time-inp">
				</div>
				<div class="project__index-pen">
					<label><b>по</b></label>
					<input type="text" name="time-to[]" class="time-inp">
				</div>
			</div>
		</div>
		<div class="project__index-col">
			<div class="project__period-btn">
				<span class="add-period-btn">Добавить период</span>
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="loc-content">
	<div class="project__index-col project__index-pen loc-part">
		<label class="project__lbl-lg"><b>Адрес ТТ</b></label>
		<input type="text" name="tt-index[]">
	</div>
	<div class="project__index-col project__index-pen loc-part">
		<label><b>Название ТТ</b></label>
		<input type="text" name="tt-name[]">
	</div>
</div>
<div class="hidden" id="period-content">
	<div class="project__index-col">
		<div class="project__index-period">
			<div class="project__index-arrow period-item">
				<label class="project__lbl-sm"><b>Дата</b></label>
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
				<input type="hidden" name="date-from[]">
			</div>
			<div class="project__index-arrow period-item">
				<label class="project__lbl-xs"><b>по</b></label>
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
				<input type="hidden" name="date-to[]">
			</div>
		</div>
	</div>
	<div class="project__index-col">
		<div class="project__index-period project__index-time">
			<div class="project__index-pen">
				<label class="project__lbl-lg"><b>Время работы</b></label>
				<input type="text" name="time-from[]" class="time-inp">
			</div>
			<div class="project__index-pen">
				<label><b>по</b></label>
				<input type="text" name="time-to[]" class="time-inp">
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="metro-content">
	<div class="project__index-col metro-item">
		<label class="project__lbl-sm"><b>Метро</b></label>
		<div class="metro-field project__index-arrow">
			<ul class="metro-select">
				<li data-id="0">
					<input type="text" name="m" class="metro-inp" autocomplete="off">
				</li>
			</ul>
			<ul class="select-list"></ul>
			<input type="hidden" name="metro[]" value="">
		</div>
	</div>
</div>
<div class="hidden" id="item-col">
	<div class="project__index-col empty"></div>
</div>