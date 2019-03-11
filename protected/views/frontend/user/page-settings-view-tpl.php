<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/private/settings.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/phone-codes/style.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/private/settings.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/phone-codes/script.js', CClientScript::POS_END);
	$set = $viData['setting'];

	$time = '00:00';
	if($set->analytic=='on'){
		$arTime = explode(':', $set->time);
		for($i=0; $n=sizeof($arTime), $i<$n; $i++)
			if(strlen($arTime[$i])!=2)
				$arTime[$i] = str_pad($arTime[$i], 2, "0", STR_PAD_LEFT);

		$time = implode(':', $arTime);
	}
	if(isset($viData['phone'])){
		$viData['phone'] = str_replace('+','',$viData['phone']);
		$pos = strpos($viData['phone'], '(');
		$viData['phone-code'] = substr($viData['phone'], 0,$pos);
		$viData['phone'] = substr($viData['phone'], $pos);       
  }
?>
<script type="text/javascript">
	var selectPhoneCode = <?=json_encode($viData['phone-code'])?>;
</script>
<div class="row settings-page">
	<form action="/user/settings" method="POST" id="settings-form">
		<input type="hidden" name="save" value="1" >
		<?
		//
		?>
		<div class="col-xs-12 settings__main">
			<div class="settings-main__wrap">
				<div class="settings-main__title"><span>ОСНОВНЫЕ</span></div>
				<div class="settings-main__prop">
					<div class="settings-main__select">
						<span class="set-main__select-name">Язык</span>
						<div class="set-main__select-val"><?
							if($set->lang=='ru') echo "Русский";
							elseif($set->lang=='ua') echo "Украинский";
							elseif($set->lang=='by') echo "Белорусский";
							else echo "Русский";
						?></div>	
						<ul class="set-main__select-list">
							<li>
								<input type="radio" name="lang" value="ru" id="lang-ru"<?=($set->lang=='ru' || ($set->lang!=='ru' && $set->lang!=='ua' && $set->lang!=='by') ? ' checked' : '')?>>
								<label for="lang-ru"><span>Русский</span></label>
							</li>
						</ul>
					</div>
					<?php if(Share::$UserProfile->type==2): ?>
						<div class="settings-main__select">
							<span class="set-main__select-name">Пол</span>
							<div class="set-main__select-val"><?=($set->sex ? 'Мужской' : 'Женский')?></div>	
							<ul class="set-main__select-list">
								<li>
									<input type="radio" name="sex" value="1" id="gender-man"<?=($set->sex ? ' checked' : '')?>>
									<label for="gender-man"><span>Мужской</span></label>
								</li>
								<li>
									<input type="radio" name="sex" id="gender-woman" value="0"<?=(!$set->sex ? ' checked' : '')?>>
									<label for="gender-woman"><span>Женский</span></label>
								</li>
							</ul>
						</div>
					<? endif; ?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<?
		//
		?>
		<div class="col-xs-12 col-sm-6 col-md-4 settings__private">
			<div class="settings-priv__title"><span>ПРИВАТНОСТЬ</span></div>
			<div class="settings-priv__wrap">
				<div class="settings-priv__field<?=$viData['confirmEmail']?' confirm':''?>" id="email-inp">
					<b>
						<?php if(!$viData['confirmEmail']): ?>
							<p>Почта не подтверждена. <em>Подтвердить</em></p>
						<?php else: ?>
							<p>Почта подтверждена.</p>
						<?php endif; ?>
					</b>
					<label for="s-p-email" class="set-priv__label">E-mail</label>
					<input type="text" class="set-priv__input" id="s-p-email" name="email" value="<?=$viData['email']?>" autocomplete="off" maxlength="30" />
					<span class="set-priv__veil"></span>
					<div class="set-priv__btn">сохранить</div>
				</div>
				<div class="settings-priv__field settings-priv__ecode" id="email-code">
					<input type="text" class="set-priv__input" name="ecode" value="" autocomplete="off" maxlength="6" placeholder="Проверочный код"/>
					<div class="set-priv__cod-btn">проверить</div>
				</div>
				<div class="settings-priv__field" id="psw-inp">
					<label for="s-p-password" class="set-priv__label">Пароль</label>
					<input type="password" class="set-priv__input" id="s-p-password" name="old-psw" value="password" autocomplete="off" placeholder="Введите старый пароль" />
					<span class="set-priv__veil"></span>
					<div class="set-priv__psw"></div>
				</div>
				<div id="new-psw-inp">
					<div class="settings-priv__field">
						<input type="password" class="set-priv__input" name="new-psw" autocomplete="off" placeholder="Введите новый пароль"/>
					</div>
					<div class="settings-priv__field">
						<input type="password" class="set-priv__input" name="vrf-psw" autocomplete="off" placeholder="Повторите пароль"/>
						<div class="set-priv__btn">сохранить</div>
					</div>
				</div>
				<div class="settings-priv__field<?=$viData['confirmPhone']?' confirm':''?>" id="phone-inp">
					<b>
						<?php if(!$viData['confirmPhone']): ?>
							<p>Телефон не подтвержден. <em>Подтвердить</em></p>
						<?php else: ?>
							<p>Телефон подтвержден.</p>
						<?php endif; ?>
					</b>
					<label for="s-p-phone" class="set-priv__label">Телефон</label>
					<div class="set-priv__input">
						<input id='phone-code' type="text" name="phone" value="<?=$viData['phone']?>" placeholder="(___) __-__-__" autocomplete="off">
					</div>
					
<?/*
					<input type="text" class="set-priv__input" id="s-p-phone" name="phone" value="<?=$viData['phone']?>" placeholder="+7(___) __-__-__" /> */?>
					<span class="set-priv__veil"></span>
					<div class="set-priv__btn">сохранить</div>
				</div>
				<div class="settings-priv__field settings-priv__ecode" id="phone-code-inp">
					<input type="text" class="set-priv__input" name="pcode" value="" autocomplete="off" maxlength="6" placeholder="Проверочный код"/>
					<div class="set-priv__cod-btn">проверить</div>
				</div>
			</div>
		</div>
		<?
		//
		?>
		<div class="col-xs-12 col-sm-6 col-md-8 settings__notifications">
			<div class="settings-notif__title"><span>УВЕДОМЛЕНИЯ</span></div>
			<div class="settings-notif__wrap">
				<div class="settings-notif__switch">
					<span class="set-ntf__sw-name">E-mail уведомления</span>
					<input type="checkbox" name="ntf-email" class="set-ntf__sw-input" id="s-n-email" value="1"<?=($set->{'ntf-email'} ? ' checked' : '')?>/>
					<label for="s-n-email" class="set-ntf__sw-label">
						<span data-enable="вкл." data-disable="выкл."></span>
					</label>
				</div>
				<div class="settings-notif__switch">
					<span class="set-ntf__sw-name">Push уведомления</span>
					<input type="checkbox" name="ntf-push" class="set-ntf__sw-input" id="s-n-push" value="1"<?=($set->{'ntf-push'} ? ' checked' : '')?>/>
					<label for="s-n-push" class="set-ntf__sw-label">
						<span data-enable="вкл." data-disable="выкл."></span>
					</label>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-8 settings__notifications">
			<div class="settings-notif__wrap settings-notif__list">
				<?php if(Share::$UserProfile->type==2): ?>
					<div class="settings-notif__point">
						<div class="set-ntf__push js-g-hashint" title="Десктоп уведомления"></div>
						<div class="set-ntf__email js-g-hashint" title="Уведомления на почту"></div>
						<div class="clearfix"></div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf1" id="e-create-vac"<?=($set->entf1=='on'?' checked':'')?>/>
						<label for="e-create-vac"></label>
						<input type="checkbox" name="pntf1" id="p-create-vac"<?=($set->pntf1=='on'?' checked':'')?>/>
						<label for="p-create-vac"></label>
						<div class="set-ntf__pnt-name">Создание вакансии (подходящей под параметры город и должность)</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf2" id="e-invitation"<?=($set->entf2=='on'?' checked':'')?>/>
						<label for="e-invitation"></label>
						<input type="checkbox" name="pntf2" id="p-invitation"<?=($set->pntf2=='on'?' checked':'')?>/>
						<label for="p-invitation"></label>
						<div class="set-ntf__pnt-name">Приглашение на вакансию от Работодателя</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf3" id="e-statement"<?=($set->entf3=='on'?' checked':'')?>/>
						<label for="e-statement"></label>
						<input type="checkbox" name="pntf3" id="p-statement"<?=($set->pntf3=='on'?' checked':'')?>/>
						<label for="p-statement"></label>
						<div class="set-ntf__pnt-name">Утверждение на вакансии</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf4" id="e-deviation"<?=($set->entf4=='on'?' checked':'')?>/>
						<label for="e-deviation"></label>
						<input type="checkbox" name="pntf4" id="p-deviation"<?=($set->pntf4=='on'?' checked':'')?>/>
						<label for="p-deviation"></label>
						<div class="set-ntf__pnt-name">Отклонение Работодателем из вакансии</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf5" id="e-vac-mess-answer"<?=($set->entf5=='on'?' checked':'')?>/>
						<label for="e-vac-mess-answer"></label>
						<input type="checkbox" name="pntf5" id="p-vac-mess-answer"<?=($set->pntf5=='on'?' checked':'')?>/>
						<label for="p-vac-mess-answer"></label>
						<div class="set-ntf__pnt-name">Ответ работодателя по вакансии сообщением</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf6" id="e-vac-mess-chat"<?=($set->entf6=='on'?' checked':'')?>/>
						<label for="e-vac-mess-chat"></label>
						<input type="checkbox" name="pntf6" id="p-vac-mess-chat"<?=($set->pntf6=='on'?' checked':'')?>/>
						<label for="p-vac-mess-chat"></label>
						<div class="set-ntf__pnt-name">Ответ работодателя по вакансии из чата</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf7" id="e-vac-change"<?=($set->entf7=='on'?' checked':'')?>/>
						<label for="e-vac-change"></label>
						<input type="checkbox" name="pntf7" id="p-vac-change"<?=($set->pntf7=='on'?' checked':'')?>/>
						<label for="p-vac-change"></label>
						<div class="set-ntf__pnt-name">Изменение вакансии работодателем</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf8" id="e-tomorrow"<?=($set->entf8=='on'?' checked':'')?>/>
						<label for="e-tomorrow"></label>
						<input type="checkbox" name="pntf8" id="p-tomorrow"<?=($set->pntf8=='on'?' checked':'')?>/>
						<label for="p-tomorrow"></label>
						<div class="set-ntf__pnt-name">Начало работы по проекту завтра в такое то время</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf9" id="e-new-review"<?=($set->entf9=='on'?' checked':'')?>/>
						<label for="e-new-review"></label>
						<input type="checkbox" name="pntf9" id="p-new-review"<?=($set->pntf9=='on'?' checked':'')?>/>
						<label for="p-new-review"></label>
						<div class="set-ntf__pnt-name">Получение нового отзыва</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf12" id="e-services"<?=($set->entf12=='on'?' checked':'')?>/>
						<label for="e-services"></label>
						<input type="checkbox" name="pntf12" id="p-services"<?=($set->pntf12=='on'?' checked':'')?>/>
						<label for="p-services"></label>
						<div class="set-ntf__pnt-name">Уведомление по Услугам (по действиям)</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="analytic" id="e-analytic"<?=($set->analytic=='on'?' checked':'')?>/>
						<label for="e-analytic"></label>
						<div class="set-ntf__pnt-name">Подписка на аналитику своего профиля</div>
						<?/*<div class="settings-notif__analytic" id="analytic" style="<?=($set->analytic=='on'?'':'display:none')?>">
							<div class="sn__analytic-days">
								<input type="radio" name="day" value="1" id="day-mon"<?=(in_array($set->day, [0,1])?' checked':'')?>/>
								<label for="day-mon">Пн</label>
								<input type="radio" name="day" value="2" id="day-tue"<?=($set->day==2 ? ' checked' : '')?>/>
								<label for="day-tue">Вт</label>
								<input type="radio" name="day" value="3" id="day-wen"<?=($set->day==3 ? ' checked' : '')?>/>
								<label for="day-wen">Ср</label>
								<input type="radio" name="day" value="4" id="day-thu"<?=($set->day==4 ? ' checked' : '')?>/>
								<label for="day-thu">Чв</label>
								<input type="radio" name="day" value="5" id="day-fri"<?=($set->day==5 ? ' checked' : '')?>/>
								<label for="day-fri">Пт</label>
								<input type="radio" name="day" value="6" id="day-sat"<?=($set->day==6 ? ' checked' : '')?>/>
								<label for="day-sat">Сб</label>
								<input type="radio" name="day" value="7" id="day-san"<?=($set->day==7 ? ' checked' : '')?>/>
								<label for="day-san">Вс</label>
							</div>
							<div class="sn__analytic-time">
								<label for="time">Время</label>
								<input type="text" name="time" id="time" value="<? echo $time?>" maxlenth="5">
							</div>?>
						</div>*/?>
					</div>
				<?php else: ?>
					<div class="settings-notif__point">
						<div class="set-ntf__push js-g-hashint" title="Десктоп уведомления"></div>
						<div class="set-ntf__email js-g-hashint" title="Уведомления на почту"></div>
						<div class="clearfix"></div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf1" id="e-app-response"<?=($set->entf1=='on'?' checked':'')?>/>
						<label for="e-app-response"></label>
						<input type="checkbox" name="pntf1" id="p-app-response"<?=($set->pntf1=='on'?' checked':'')?>/>
						<label for="p-app-response"></label>
						<div class="set-ntf__pnt-name">Отклик Соискателя на вакансию</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf2" id="e-app-confirm"<?=($set->entf2=='on'?' checked':'')?>/>
						<label for="e-app-confirm"></label>
						<input type="checkbox" name="pntf2" id="p-app-confirm"<?=($set->pntf2=='on'?' checked':'')?>/>
						<label for="p-app-confirm"></label>
						<div class="set-ntf__pnt-name">Соискатель подтвердил участие на предложенной вакансии</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf3" id="e-app-refused"<?=($set->entf3=='on'?' checked':'')?>/>
						<label for="e-app-refused"></label>
						<input type="checkbox" name="pntf3" id="p-app-refused"<?=($set->pntf3=='on'?' checked':'')?>/>
						<label for="p-app-refused"></label>
						<div class="set-ntf__pnt-name">Соискатель отказался от участия в вакансии</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf4" id="e-vac-mess-chat"<?=($set->entf4=='on'?' checked':'')?>/>
						<label for="e-vac-mess-chat"></label>
						<input type="checkbox" name="pntf4" id="p-vac-mess-chat"<?=($set->pntf4=='on'?' checked':'')?>/>
						<label for="p-vac-mess-chat"></label>
						<div class="set-ntf__pnt-name">Ответ соискателя по вакансии сообщением</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf5" id="e-tomorrow"<?=($set->entf5=='on'?' checked':'')?>/>
						<label for="e-tomorrow"></label>
						<input type="checkbox" name="pntf5" id="p-tomorrow"<?=($set->pntf5=='on'?' checked':'')?>/>
						<label for="p-tomorrow"></label>
						<div class="set-ntf__pnt-name">Старт проекта завтра</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf6" id="e-today"<?=($set->entf6=='on'?' checked':'')?>/>
						<label for="e-today"></label>
						<input type="checkbox" name="pntf6" id="p-today"<?=($set->pntf6=='on'?' checked':'')?>/>
						<label for="p-today"></label>
						<div class="set-ntf__pnt-name">Старт проекта сегодня (оповещение за час до начала)</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf7" id="e-concluding"<?=($set->entf7=='on'?' checked':'')?>/>
						<label for="e-concluding"></label>
						<input type="checkbox" name="pntf7" id="p-concluding"<?=($set->pntf7=='on'?' checked':'')?>/>
						<label for="p-concluding"></label>
						<div class="set-ntf__pnt-name">Проект завершается (оповещение за час до завершения)</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf8" id="e-new-review"<?=($set->entf8=='on'?' checked':'')?>/>
						<label for="e-new-review"></label>
						<input type="checkbox" name="pntf8" id="p-new-review"<?=($set->pntf8=='on'?' checked':'')?>/>
						<label for="p-new-review"></label>
						<div class="set-ntf__pnt-name">Получение нового отзыва</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="entf11" id="e-services"<?=($set->entf11=='on'?' checked':'')?>/>
						<label for="e-services"></label>
						<input type="checkbox" name="pntf11" id="p-services"<?=($set->pntf11=='on'?' checked':'')?>/>
						<label for="p-services"></label>
						<div class="set-ntf__pnt-name">Уведомление по Услугам (по действиям)</div>
					</div>
					<div class="settings-notif__point">
						<input type="checkbox" name="analytic" id="e-analytic"<?=($set->analytic=='on'?' checked':'')?>/>
						<label for="e-analytic"></label>
						<div class="set-ntf__pnt-name">Подписка на аналитику своего профиля</div>
						<?/*<div class="settings-notif__analytic" id="analytic" style="<?=($set->analytic=='on'?'':'display:none')?>">
							<div class="sn__analytic-days">
								<input type="radio" name="day" value="1" id="day-mon"<?=(in_array($set->day, [0,1])?' checked':'')?>/>
								<label for="day-mon">Пн</label>
								<input type="radio" name="day" value="2" id="day-tue"<?=($set->day==2 ? ' checked' : '')?>/>
								<label for="day-tue">Вт</label>
								<input type="radio" name="day" value="3" id="day-wen"<?=($set->day==3 ? ' checked' : '')?>/>
								<label for="day-wen">Ср</label>
								<input type="radio" name="day" value="4" id="day-thu"<?=($set->day==4 ? ' checked' : '')?>/>
								<label for="day-thu">Чв</label>
								<input type="radio" name="day" value="5" id="day-fri"<?=($set->day==5 ? ' checked' : '')?>/>
								<label for="day-fri">Пт</label>
								<input type="radio" name="day" value="6" id="day-sat"<?=($set->day==6 ? ' checked' : '')?>/>
								<label for="day-sat">Сб</label>
								<input type="radio" name="day" value="7" id="day-san"<?=($set->day==7 ? ' checked' : '')?>/>
								<label for="day-san">Вс</label>
							</div>
							<div class="sn__analytic-time">
								<label for="time">Время</label>
								<input type="text" name="time" id="time" value="<? echo $time?>" maxlenth="5">
							</div>
						</div>*/?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-xs-12 btn-orange-sm-wr">
			<button type="submit" class="hvr-sweep-to-right" id="settings-save">СОХРАНИТЬ</button>
		</div>
	</form>
</div>