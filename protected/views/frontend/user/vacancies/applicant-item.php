<?
	$url = Yii::app()->request->requestUri;
	$archive = MainConfig::$PAGE_APPLICANT_VACS_LIST_ARCHIVE;
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'vacancies/app-item.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'vacancies/app-item.js', CClientScript::POS_END);
  $link = Yii::app()->getRequest()->getParam('section')==='archive' 
      ? MainConfig::$PAGE_APPLICANT_VACS_LIST_ARCHIVE 
      : MainConfig::$PAGE_APPLICANT_VACS_LIST;
  $vacancy = $viData['item'];
  $employer = $viData['user'][$vacancy['employer']];
?>
<div class="row applicant_project">
	<div class="col-xs-12">
		<div class="app_project__head">
			<a href="<?=$link?>">Назад</a>
			<div class="app_project__head-title">
				<h1><?=$vacancy['title']?></h1>
			</div>
			<div class="app_project__head-salary">
				<?
					$salary = '';
					if($vacancy['svisit']>0)
						$salary .= '<span>' . $vacancy['svisit'] . " руб/посещение</span><br>";
					if($vacancy['smonth']>0)
						$salary .= '<span>' . $vacancy['smonth'] . " руб/месяц</span><br>";
					if($vacancy['sweek']>0)
						$salary .= '<span>' . $vacancy['sweek'] . " руб/неделю</span><br>";
					if($vacancy['shour']>0)
						$salary .= '<span>' . $vacancy['shour'] . " руб/час</span><br>";

					echo $salary;
				?>
			</div>
		</div>
		<?
		//
		?>
		<div class="app_project__body row">
			<div class="app_project__body-flex">
				<div class="col-xs-12 col-sm-5">
					<div class="row">
						<div class="col-xs-12 app_project__body-item">
							<div class="app_project__body-border">
								<div class="app_project__body-title">
									<h2>Работодатель</h2>
								</div>
								<div class="app_project__company">
									<a href="<?=$employer['profile']?>">
										<img src="<?=$employer['src']?>" alt="<?=$employer['name']?>">
										<span><?=$employer['name']?></span>
									</a>
								</div>
							</div>
						</div>
						<?
						//
						?>
						<div class="col-xs-12 app_project__body-item app_project__body-status">
							<div class="app_project__body-border">
								<div class="app_project__body-title">
									<h2>Состояние</h2>
								</div>
								<div class="app-projects__item-replace">
									<div class="app_project__body-flex">
										<b><?=$vacancy['condition']?></b>
									</div>
									<? if($vacancy['second_response']): ?>
										<div class="app_project__body-flex">
											<span 
												class="app-projects__body-btn second_response" 
												data-id="<?=$vacancy['id']?>"
												data-sresponse="<?=Share::$UserProfile->exInfo->id_resume?>"
												>Отозваться повторно</span>
										</div>
									<? elseif($vacancy['access_to_answer']): ?>
										<div class="app_project__body-flex">
											<span 
												class="app-projects__body-btn change_status status_accept" 
												data-id="<?=$vacancy['vstatus_id']?>"
												data-status="<?=Responses::$STATUS_APPLICANT_ACCEPT?>" <?// татус принятия заявки ?>
												>Принять</span>
											<span 
												class="app-projects__body-btn change_status status_reject" 
												data-id="<?=$vacancy['vstatus_id']?>"
												data-status="<?=Responses::$STATUS_REJECT?>" <?// татус отклонения заявки ?>
												>Отклонить</span>
										</div>
									<? elseif($vacancy['access_to_chat']): ?>
										<? if($vacancy['status']==Responses::$STATUS_BEFORE_RATING): ?>
											<div class="app_project__body-flex">
												<b><a 
													href="<?=MainConfig::$PAGE_SETRATE . DS . $vacancy['id']?>" 
													class="app-projects__body-btn">Оценить работодателя</a></b>
											</div>
										<? endif; ?>
										<? $link = MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $vacancy['id']; ?>
										<div class="app_project__body-flex">
											<b><a 
											href="<?=$link?>"
											class="app-projects__body-btn">Общий чат</a></b>
											<b><a 
											href="<?=$link . DS . $vacancy['employer']?>"
											class="app-projects__body-btn">Личный чат</a></b>
										</div>
									<? endif; ?>
								</div>
								<?// блоки для подмены после аякса ?>
								<div class="status_accept-content tmpl">
									<div class="app_project__body-flex">
										<b>Приглашение принято</b>
									</div>
									<? $link = MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $vacancy['id']; ?>
									<div class="app_project__body-flex">
										<b><a 
										href="<?=$link?>"
										class="app-projects__body-btn">Общий чат</a></b>
										<b><a 
										href="<?=$link . DS . $vacancy['employer']?>"
										class="app-projects__body-btn">Личный чат</a></b>
									</div>
								</div>
								<div class="status_reject-content tmpl">
									<div class="app_project__body-flex">
										<b>Приглашение отклонено</b>
									</div>
									<div class="app_project__body-flex">
										<? if(!$vacancy['sresponse']): ?>
											<span 
												class="app-projects__body-btn second_response" 
												data-id="<?=$vacancy['id']?>"
												data-sresponse="<?=Share::$UserProfile->exInfo->id_resume?>"
												>Отозваться повторно</span>
										<? endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?
				//
				?>
				<div class="col-xs-12 col-sm-7 app_project__body-table app_project__body-item">
					<div class="app_project__body-border">
						<div class="app_project__body-title">
							<h2>Основная информация</h2>
						</div>
						<table>
							<tr>
								<td colspan="2">
									<div class="app_project__posts"><?=implode(', ',$viData['posts'])?></div>
								</td>
							</tr>
							<tr>
								<td>Дата публикация:</td>
								<td>
									<span><b><?=$vacancy['pubdate']?></b></span>
								</td>
							</tr>
							<tr>
								<td>Сроки оплаты:</td>
								<td><?
									foreach ($viData['attribs'] as $v)
										echo $v['key']=='paylims' ? '<b>' . $v['pname'] . '</b><br>' : '';
								?></td>
							</tr>
							<?
							// ищем самую позднюю дату
							$bWDate = reset($viData['city'])['bdate']; // дата начала первого города
							$eWDate = $vacancy['remdate'];
							foreach ($viData['city'] as $c)
							{
								strtotime($c['bdate'])<strtotime($bWDate) && $bWDate=$c['bdate'];
								strtotime($c['edate'])>strtotime($eWDate) && $eWDate=$c['edate'];

								foreach ($viData['periods'] as $p)
								{
									strtotime($p['bdate'])<strtotime($bWDate) && $bWDate=$p['bdate'];
									strtotime($p['edate'])>strtotime($eWDate) && $eWDate=$p['edate'];
								}
							}
							?>
							<tr>
								<td>Дата начала работы по проекту:</td>
								<td><b><?=$bWDate?></b></td>
							</tr>
							<tr>
								<td>Дата завершение работы по проекту:</td>
								<td><b><?=$eWDate?></b></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<?
			//
			?>
			<div class="app_project__body-flex">
				<div class="col-xs-12 col-sm-6 app_project__body-item">
					<div class="app_project__body-border">
						<div class="app_project__body-title">
							<h2>Адрес и время работы</h2>
						</div>
						<div>
							<ul>
								<? $count = 1; ?>
								<? foreach ($viData['city'] as $c): ?>
									<li>
										<div>Город <?=$count?>: <b><?=$c['city']?></b></div>
										<? // цикл по локациям ?>
										<ul class="app_project__city">
											<? foreach ($viData['locations'] as $l): ?>
												<? if($l['id_city']==$c['id']): ?>
													<li>
														<table>
															<tr>
																<td>Название локации: </td>
																<td><b><?=$l['name']?></b></td>
															</tr>
															<tr>
																<td>Адрес локации: </td>
																<td><b><?=$l['addr']?></b></td>
															</tr>
															<? if (count($l['metro'])): ?>
																<tr>
																	<td>Метро: </td>
																	<td><b><?=implode(', <br>', $l['metro'])?></b></td>
																</tr>
															<? endif; ?>
														</table>
														<? // цикл по периодам ?>
														<ul class="app_project__location">
															<? foreach ($viData['periods'] as $p): ?>
																<? if($p['id_loc']==$l['id']): ?>
																	<li>
																		<?
																			$sDate = '';
																			$curY = date('Y');
																			$uBDate = strtotime($p['bdate']);
																			$uEDate = strtotime($p['edate']);
																			$bdate = (date('Y',$uBDate)==date('Y') ? date('d.m',$uBDate) : $p['bdate']);
																			$edate = (date('Y',$uEDate)==date('Y') ? date('d.m',$uEDate) : $p['edate']);
																			$sDate = ($uBDate!=$uEDate ? "c $bdate по $edate" : $bdate);
																			$sDate .= ' ' . $p['btime'] . '-' . $p['etime'];
																		?>
																		<div>Дата работы на проекте: <b><?=$sDate?></b></div>
																	</li>
																<? endif; ?>
															<? endforeach; ?>
														</ul>
													</li>
												<? endif; ?>
											<? endforeach; ?>
										</ul>
									</li>
									<? $count++ ?>
								<? endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<?
				//
				?>
				<div class="col-xs-12 col-sm-6 app_project__body-item">
					<div class="app_project__body-border">
						<div class="app_project__body-title">
							<h2>Описание проекта</h2>
						</div>
						<div class="app_project__body-info">
							<div><b>Требования:</b></div>
							<div><?=html_entity_decode($vacancy['requirements'])?></div>
						</div>
						<?if($vacancy['conditions']):?>
							<div class="app_project__body-info">
								<div><b>Условия:</b></div>
								<div><?=html_entity_decode($vacancy['conditions'])?></div>
							</div>
						<?endif;?>
						<?if($vacancy['duties']):?>
							<div class="app_project__body-info">
								<div><b>Обязанности:</b></div>
								<div><?=html_entity_decode($vacancy['duties'])?></div>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?
/*
echo '<pre>';
print_r($viData); 
echo '</pre>'; 
*/
?>