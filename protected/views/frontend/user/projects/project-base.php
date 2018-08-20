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
			<? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
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
					<a href="/uploads/example.xls" download>Скачать пример для добавления</a>
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
			
					</tbody>
				</table>
			</div>
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
