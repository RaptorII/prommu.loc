<div class="row">
	<div class="col-xs-12 payment">
		<h1 class="payment__title">Оплата услуг PROMMU</h1>
		<form 
			action="/user/payment" 
			class="payment-form" 
			method="POST" 
			id="payment-form" 
			data-leg="<?=MainConfig::$PAGE_PAYMENT?>" 
			data-ind="/user/payment">
			<?php if($viData['service']=='premium'): ?>
				<span class="payment-form__type-name">ПЕРИОД РАБОТЫ ПРЕМИУМ УСЛУГИ</span>
				<?php foreach ($viData['vacancies'] as $id): ?>
					<div class="payment-date">	
						<div class="payment__date-id">Для вакансии <?=$id?></div>
						<div class="payment__date-calendar begin">
							<div class="payment__date-error">Дата начала не корректна</div>
							<div class="payment__date-border">
								<span class="payment__date-name">ДАТА НАЧАЛА</span>
								<table id="calendar-begin" class="payment__calendar">
									<thead>
										<tr>
											<td class="month-left">‹</td>
											<td colspan="5" class="month-name"></td>
											<td class="month-right">›</td>
										</tr>
										<tr>
											<td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
										</tr>
									<tbody>
								</table>
							</div>
						</div>
						<div class="payment__date-calendar end">
							<div class="payment__date-error">Дата окончания не корректна</div>
							<div class="payment__date-border">
								<span class="payment__date-name">ДАТА ОКОНЧАНИЯ</span>
								<table id="calendar-end" class="payment__calendar">
									<thead>
										<tr>
											<td class="month-left">‹</td>
											<td colspan="5" class="month-name"></td>
											<td class="month-right">›</td>
										</tr>
										<tr>
											<td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
										</tr>
									<tbody>
								</table>
							</div>
						</div>
						<div class="clearfix"></div>
						<table class="payment-form__table">
							<tbody>
								<tr><td>Дата начала</td><td class="payment-begin"></td></tr>
								<tr><td>Дата окончания</td><td class="payment-end"></td></tr>
								<tr><td>Период работы услуги</td><td class="payment-period">0 дней</td></tr>
							</tbody>
						</table>
						<input type="hidden" name="vacanc[]" value="<?=$id?>">					
						<input type="hidden" name="from[]" class="payment-begin-inp">
						<input type="hidden" name="to[]" class="payment-end-inp">
						<input type="hidden" name="period[]" class="payment-period-inp">
					</div>
				<?php endforeach; ?>
				<span class="payment-form__type-name">РЕЗУЛЬТАТ</span>		
				<table class="payment-form__table">
					<tbody>
						<tr><td>Стоимость услуги</td><td><span id="payment-price"><?=$viData['price']?></span> руб/день</td>
						</tr>
						<tr><td>Выбраных вакансий</td><td id="payment-count"><?=count($viData['vacancies'])?></td></tr>
						<tr><td>Итоговый период работы услуги </td><td id="payment-period">0 дней</td></tr>
						<tr><td colspan="2" id="payment-result"></td></tr>
					</tbody>
				</table>
			<?php endif; ?>
			<?if($viData['service']=='sms'):?>
				<?php $result = $viData['app_count'] * $viData['mes_count'] * $viData['price']; ?>
				<span id="payment-result"><?=$result?>рублей</span>			
			<?endif;?>
			<span class="payment-form__type-name">тип плательщика</span>
			<label class="payment-form__radio-label">
				<input name="personal" type="radio" value="individual" class="payment-form__radio-input" checked>
				<span class="payment-form__radio-block"></span>
				<span class="payment-form__radio-name">Физическое лицо</span>
			</label>
			<br>
			<label class="payment-form__radio-label">
				<input name="personal" type="radio" value="legal" class="payment-form__radio-input">
				<span class="payment-form__radio-block"></span>
				<span class="payment-form__radio-name">Юридическое лицо</span>
			</label>
			<div class="payment-form__legal" id="payment-legal">
				<span class="payment-form__type-name">ДАННЫЕ ЮРИДИЧЕСКОГО ЛИЦА</span>
				<label class="payment__label" title="Название предприятия">
					<input type="text" name="name" placeholder="Название предприятия" value="<?=Share::$UserProfile->exInfo->name?>" class="payment__input" id="legal-name"/>
				</label>
				<label class="payment__label" title="ИНН">
					<input type="text" name="inn" placeholder="ИНН" class="payment__input" id="legal-inn" />
				</label>
				<label class="payment__label" title="КПП">
					<input type="text" name="kpp" placeholder="КПП" class="payment__input" id="legal-kpp" />
				</label>
			</div>
			<button type="submit" class="payment-form__btn" id="payment-btn">СФОРМИРОВАТЬ СЧЕТ</button>
			<input type="hidden" name="account" value="<?= Share::$UserProfile->id?>">
		</form>
	</div>
</div>