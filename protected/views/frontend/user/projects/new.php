<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/new.js', CClientScript::POS_END);
?>
<div class="row project">
	<div class="col-xs-12">
		<form action="" method="POST">
			<div class="project__tabs">
				<a href="#" data-href="project-1">ПЕРСОНАЛ</a>
				<a href="#" data-href="index">АДРЕСНАЯ ПРОГРАММА</a>
				<a href="#" data-href="project-2">КОНТРОЛЬ ПЕРСОНАЛА</a>
				<a href="#" data-href="project-3">ЗАДАЧИ</a>
			</div>
			<div id="content">
				<div id="main" class="project__module">
					<div class="project__name">
						<input type="text" name="name" placeholder="*Введите название проекта" autocomplete="off">
					</div>
					<div class="project__block">
						<div class="project__opt">
							<div class="project__opt-row">
								<span>Добавить адресную программу</span>
								<a href="#" class="project__opt-btn">Выбрать</a>
							</div>
							<div class="project__opt-row">
								<span>Добавить адресную программу через XLS</span>
								<a href="#" class="project__opt-btn">Выбрать</a>
								<input type="file" name="xls" accept="xls">
							</div>
							<div class="project__opt-row">
								<a href="#" class="project__opt-xls">Скачать пример адреной программы</a>
							</div>
						</div>
						<div class="project__opt">
							<div class="project__opt-row">
								<span>Добавить новый персонал на проект</span>
								<a href="#" class="project__opt-btn">Выбрать</a>
							</div>
							<div class="project__opt-row">
								<span>Пригласить персонал на проект</span>
								<a href="#" class="project__opt-btn">Выбрать</a>
							</div>
						</div>
					</div>
				</div>
				<div id="index" class="project__module">
					<h2 class="project__title"><b>добавить адресную программу</b></h2>
					<div class="project__body">
						<div class="city-item">
							<div class="project__index-row">
								<div class="project__index-col">
									<label class="project__lbl-sm"><b>Город</b></label>
									<input type="text" name="city[]">
								</div>
								<div class="project__index-col">
									<label class="project__lbl-lg"><b>Адрес ТТ</b></label>
									<input type="text" name="tt-index[]">
								</div>
								<div class="project__index-col">
									<label><b>Название ТТ</b></label>
									<input type="text" name="tt-name[]">
								</div>
							</div>
							<div class="project__index-row">
								<div class="project__index-col">
									<div class="project__index-period">
										<div>
											<label class="project__lbl-sm"><b>Дата</b></label>
											<input type="text" name="date-from[]">
										</div>
										<div>
											<label class="project__lbl-xs"><b>по</b></label>
											<input type="text" name="date-to[]">
										</div>
									</div>
								</div>
								<div class="project__index-col">
									<div class="project__index-period project__index-time">
										<div>
											<label class="project__lbl-lg"><b>Время работы</b></label>
											<input type="text" name="time-from[]">
										</div>
										<div>
											<label><b>по</b></label>
											<input type="text" name="time-to[]">
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
				</div>
			</div>
			<div class="project__all-btns">
				<div class="project__main-btn" data-page="main">
					<a href="#" class="save-btn">СОХРАНИТЬ</a>
				</div>
				<div class="project__index-btns hide" data-page="invitation">
					<a href="#" class="project__btn-green">ДОБАВИТЬ ЕЩЕ ТТ</a>
					<a href="#" class="project__btn-white">ДОБАВИТЬ ГОРОД</a>
					<a href="#" class="save-btn">СОХРАНИТЬ</a>
				</div>
				<div class="project__invite-btns hide" data-page="incite">
					<a href="#" class="project__btn-white">+ДОБАВИТЬ ЕЩЕ ПЕРСОНАЛ</a>
					<a href="#" class="save-btn">СОХРАНИТЬ</a>
				</div>
			</div>
		</form>
	</div>
</div>