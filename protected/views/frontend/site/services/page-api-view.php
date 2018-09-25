<?
  Yii::app()->getClientScript()->registerCssFile('/theme/css/services/services-api-page.css');
  Yii::app()->getClientScript()->registerScriptFile('/theme/js/services/services-api-page.js', CClientScript::POS_END);
?>
<div class="row">
	<?php if(Yii::app()->getRequest()->getParam('f-api')): ?>
		<? $fapi = Yii::app()->getRequest()->getParam('f-api'); ?>
		<div class="col-xs-12 api-service">
			<?php if($fapi==1): ?>
				<h2 class="api-service__title">ВЫБЕРИТЕ ДАННЫЕ ПЕРСОНАЛА, КОТОРЫЕ ВЫ ХОТИТЕ ОТОБРАЖАТЬ НА СВОЕМ РЕСУРСЕ</h2>
			<?php elseif($fapi==2): ?>
				<h2 class="api-service__title">ВЫБЕРИТЕ ПАРАМЕТРЫ, ПО КОТОРЫМ НЕОБХОДИМО ОТБИРАТЬ ПЕРСОНАЛ ДЛЯ ВАШЕГО РЕСУРСА</h2>
			<?php elseif($fapi==3): ?>
				<h2 class="api-service__title">УСЛУГА В РАЗРАБОТКЕ</h2>
			<?php endif ?>
			<form action="/user/api/?api=<?=$fapi?>" method="POST">
				<?php if($fapi==1): ?>
					<div class="col-xs-12 col-sm-6">
						<div class="api-chbox">
							<div class="api-srvc__chbox">
								<input id="api-surname" name="surname" type="checkbox" value="1">
								<label for="api-surname" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Фамилия</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-name" name="name" type="checkbox" value="1">
								<label for="api-name" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Имя</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-photo" name="photo" type="checkbox" value="1">
								<label for="api-photo" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Фото</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-birthday" name="birthday" type="checkbox" value="1">
								<label for="api-birthday" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Дата рождения</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-age" name="age" type="checkbox" value="1">
								<label for="api-age" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Возраст</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-gender" name="gender" type="checkbox" value="1">
								<label for="api-gender" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Пол (М/Ж)</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-med" name="med" type="checkbox" value="1">
								<label for="api-med" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Наличие Медкнижки</span>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6">
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-auto" name="auto" type="checkbox" value="1">
								<label for="api-auto" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Наличие Автомобиля</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-city" name="city" type="checkbox" value="1">
								<label for="api-city" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Город</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-location" name="location" type="checkbox" value="1">
								<label for="api-location" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Удобное место работы</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-pos" name="position" type="checkbox" value="1">
								<label for="api-pos" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Должность</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-exp" name="experience" type="checkbox" value="1">
								<label for="api-exp" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Опыт работы</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-about" name="about" type="checkbox" value="1">
								<label for="api-about" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">О себе</span>
						</div>
					</div>
				<?php elseif($fapi==2): ?>
					<div class="col-xs-12">
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-city" name="city" type="checkbox" value="1">
								<label for="api-city" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Город</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-pos" name="position" type="checkbox" value="1">
								<label for="api-pos" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Должность</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-gender" name="gender" type="checkbox" value="1">
								<label for="api-gender" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Пол (М/Ж)</span>
						</div>
						<div class="api-chbox">	
							<div class="api-srvc__chbox">
								<input id="api-age" name="age" type="checkbox" value="1">
								<label for="api-age" class="api-srvc__chbox-label">
									<div class="api-srvc__chbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
							<span class="api-chbox__name">Возраст</span>
						</div>
					</div>
				<?php elseif($fapi==3): ?>
				<?php endif ?>
				<div class="col-xs-12">
					<button type="submit" class="api-service__btn">Продолжить</button>
				</div>
				<input type="hidden" name="f-api" value="<?=$fapi?>">
			</form>
		</div>
	<?php else: ?>
		<div class="col-xs-12 api-service">
			<h2 class="api-service__title">ВЫБЕРИТЕ ФУНКЦИОНАЛ</h2>
			<form action="" method="GET" id="api-form">
				<div class="os-vacancies__list">
					<div class="os-services__item">
						<input type="radio" name="f-api" id="display" value="1">
						<label class="os-services__label" for="display">
							<span class="os-services__circle">
								<span class="os-services__name">Отображение Анкет<br>персонала на своем сайте</span>
								<span class="os-services__img ico1"></span>
							</span>
						</label>
					</div>
					<div class="os-services__item">
						<input type="radio" name="f-api" id="update" value="2">
						<label class="os-services__label" for="update">
							<span class="os-services__circle">
								<span class="os-services__name">Автоматическое<br>обновление списка анкет</span>
								<span class="os-services__img ico2"></span>
							</span>
						</label>
					</div>
				<?/*	<div class="os-services__item">
						<input type="radio" name="f-api" id="synch" value="3">
						<label class="os-services__label" for="synch">
							<span class="os-services__circle">
								<span class="os-services__name">Синхронизация<br>публикуемых данных</span>
								<span class="os-services__img ico3"></span>
							</span>
						</label>
					</div>	*/?>				
					<div class="clearfix"></div>
				</div>
				<button class="api-service__btn">ПОЛУЧИТЬ ДОСТУП</button>
			</form>
			<div class="clearfix"></div>
			<?
			// DOCS
			?>
			<?/*
			<div class="api__title"><hr><h2><span>Команды</span></h2></div>
			<?php foreach ($viData['commands'] as $key => $val): ?>
				<?php 
					$name = strtoupper($val['name']);
					$example = strip_tags($val['retExamples']);
					$example = str_replace('Пример:', '', $example);
					$example = str_replace('развернуть', '', $example);
					$example = trim($example);
				?>
				<div id="<?=$name?>" class="api__code-name"><b><?=$name?></b> - <?= $val['comment'] ?></div>
				<div class="api__code-descr"><?= $val['paramComment'] ?></div>

				<table class="api__table api__command">
					<tr>
						<td>Описание возвращаемых параметров</td>
						<td><?= $val['retParams'] ?></td>
					</tr>
					<tr>
						<td>Пример запроса</td>
						<td><code><?= $val['example'] ?></code></td>
					</tr>
						<tr>
						<td>Пример ответа</td>
						<td>
							<span class="api__exp-link">Показать</span>
							<pre class="api__exp-col"><?=$example?></pre>
						</td>
					</tr>
				</table>
				<br>
			<?php endforeach; ?>

			<div class="api__title"><hr><h2><span>Ошибки</span></h2></div>
			<table class="api__table api__codes">
				<tr>
					<th>Код</th>
					<th>Описание</th>
				</tr>
				<?php foreach ($viData['err-codes'] as $key => $val): ?>
					<tr>
						<td><?= $key ?></td>
						<td><?= $val ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
			*/?>
		</div>
	<?php endif; ?>
</div>