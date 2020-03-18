<?
	Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/analytics.css');
	Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/analytics.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile('https://www.gstatic.com/charts/loader.js');
?>
<div class="page-analytics">
	<div id="analytics-veil"></div>
    <?/*?>
	<div class="btn-white-green-wr -left">
		<a href="<?=MainConfig::$PAGE_EDIT_PROFILE?>" class="photo-pages__btn-back">< вернуться к редактированию профиля</a>
	</div>
    <?*/?>
	<div class="col-xs-12">
		<div class="row">
			<form action="POST" id="analytics-form" class="pa__filter">
				<div class="pa-filter__field">
					<span class="pa-filter__name">Выводить события с:</span>
					<div class="pa-filter__bdate">
						<span class="pa-filter__date" id="pa-begin-str"><?=$viData['dates']['bdate']?></span>
						<input type="hidden" name="pa-bdate" value="<?=$viData['dates']['bdate']?>" id="pa-begin">
						<div class="pa__calendar">
							<table id="pa-cal-begin" class="pa__cal-table">
								<thead>
									<tr>
										<td class="m-left">‹</td>
										<td colspan="5" class="m-name"></td>
										<td class="m-right">›</td>
									</tr>
									<tr>
										<td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
									</tr>
								<tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="pa-filter__field">
					<span class="pa-filter__name">по:</span>
					<div class="pa-filter__edate">
						<span class="pa-filter__date" id="pa-end-str"><?=$viData['dates']['edate']?></span>
						<input type="hidden" name="pa-edate" value="<?=$viData['dates']['edate']?>" id="pa-end">
						<div class="pa__calendar">
							<table id="pa-cal-end" class="pa__cal-table">
								<thead>
									<tr>
										<td class="m-left">‹</td>
										<td colspan="5" class="m-name"></td>
										<td class="m-right">›</td>
									</tr>
									<tr>
										<td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
									</tr>
								<tbody>
							</table>
						</div>
					</div>
				</div>
				<label class="pa-filter__field">
					<span class="pa-filter__name">Тип событий:</span>
					<select name="pa-type" class="pa-filter__select" id="pa-event">
						<option value="-1">Все</option>
						<? if(Share::$UserProfile->type==3): ?>
							<option value="0">Публикация вакансий</option>
							<option value="1">Использование Услуг</option>
							<option value="2">Просмотр аккаунта</option>
						<? else: ?>
							<option value="0">Просмотренных вакансий</option>
							<option value="1">Приглашения на вакансию от работодателя</option>
							<option value="2">Самостоятельных кликов на размещенные вакансии</option>				
							<option value="3">Отработанных (утвержденных) вакансий</option>				
						<? endif; ?>
					</select>
				</label>
				<div class="pa-filter__btn"><button type="submit" id="analytics-submit">ПРИМЕНИТЬ</button></div>
			</form>
		</div>
	</div>
	<div class="clearfix"></div>	
	<div class="pa__content" id="analytics-content">
		<? require_once 'page-analytics-view-ajax.php'; // ajax обновляемый контент ?>
	</div>
</div>