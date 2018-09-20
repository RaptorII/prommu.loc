<?php 
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/services/list.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/jquery.maskedinput.min.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/services/list.js', CClientScript::POS_END);
	$type = Share::$UserProfile->type;
	$arApp = ['geolocation-staff','prommu_card','medical-record']; // то, что доступно соискателю
 
?>
<div class="row">
	<div class="col-xs-12 services">
		<? if($type!=2): // только не для соискателя ?>
			<? $link = $type==3 ? MainConfig::$PAGE_VACPUB : MainConfig::$PAGE_LOGIN ?>
			<div class="row services__item">
				<div class="col-xs-12 col-sm-6 services__prev">
					<div class="services__item-icon"></div>
					<div class="services__item-label">Размещение вакансий – БЕСПЛАТНО</div>
					<div class="services__item-descr">Prommu предоставляет возможность в сжатые сроки найти подходящий персонал. Это процедура займет менее минуты</div>
				</div>
				<div class="col-xs-12 col-sm-6 services__detail">
					<a href="<?=$link?>">Подробнее</a>
				</div>
				<div class="services__price-order">
					<div>
						<div class="services__price">
							<div class="services__price-item">БЕСПЛАТНО!</div>
						</div>
						<div class="services__order">
							<a href="<?=$link?>">Разместить Вакансию</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		<? endif; ?>
		<? foreach ($viData['menu'][0] as $m): ?>
			<?
				if(!in_array($m['icon'], $arApp) && $type==2)
					continue;
			?>
			<? if($m['parent_id']==0 && !is_array($viData['menu'][$m['id']])): ?>
				<div class="row services__item">
					<div class="col-xs-12 col-sm-6 services__prev">
						<div class="services__item-icon <?=$m['icon']?>"></div>
						<? $cnt = iconv_strlen($m['name'],'UTF-8'); ?>
						<div class="services__item-label upper<?=($cnt>30?' small':'')?>"><?=$m['name']?></div>
						<div class="services__item-descr"><?=$m['anons']?></div>
					</div>
					<div class="col-xs-12 col-sm-6 services__detail">
						<a href="<?=$m['link']?>">Подробнее</a>
					</div>
					<div class="services__price-order">
						<? foreach ($prices['prices'][$m['icon']] as $price): ?>
							<div>
								<div class="services__price">
									<div class="services__price-item"><?=$price['price']?$price['price']:'Бесплатно' ?> <?=$price['comment']?></div>
								</div>
								<div 
									class="services__order order-service" 
									data-id="<?=$m['id']?>" 
									data-type="<?=$m['icon']?>"
								>
									<? if(in_array($type,[2,3]) && $m['icon']!='geolocation-staff'): ?>
										<? 
											sizeof($prices['service'][$m['icon']]) > 1
											? $link = '/user' . $m['link'] . '?type=' . $price['id']
											: $link = '/user' . $m['link'];
										?>
										<a href="<?=$link?>" class="user">Заказать</a>
									<? else: ?>
										<a href="javascript:void(0)">Заказать</a>
									<? endif; ?>
								</div>
								<div class="clearfix"></div>
							</div>
						<? endforeach; ?>
					</div>
				</div>
			<? else: ?>
				<? // вывод второго уровня  ?>
				<div class="row services__item-sub">
					<div class="col-xs-12 col-sm-6 services__parent">
						<div class="services__item-icon <?=$m['icon']?>"></div>
						<? $cnt = iconv_strlen($m['name'],'UTF-8'); ?>
						<div class="services__item-label upper<?=($cnt>30?' subsmall':'')?>"><?=$m['name']?></div>
					</div>
					<div class="clearfix"></div>
					<? foreach ($viData['menu'][$m['id']] as $s): ?>
						<div class="services__sublevel">
							<div class="col-xs-12 col-sm-6 services__item-descr">
								<div class="services__sub">
									<div class="services__sub-label <?=$s['icon']?>"><?=$s['name']?></div>
									<div class="services__sub-descr"><?=$s['anons']?></div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-6 services__detail">
								<a href="<?=$s['link']?>">Подробнее</a>
							</div>
							<div class="services__price-order">
								<? foreach ($prices['prices'][$s['icon']] as $price): ?>
									<div>
										<div class="services__price">
											<div class="services__price-item"><?=$price['price']?$price['price']:'Бесплатно'?> <?=$price['comment']?></div>		
										</div>
										<div 
											class="services__order order-service" 
											data-id="<?=$s['id']?>" 
											data-type="<?=$s['icon']?>"
										>
											<? if(in_array($type,[2,3])): ?>
												<?
													sizeof($viData['prices'][$s['icon']]) > 1
													? $link = '/user' . $s['link'] . '?type=' . $price['id']
													: $link = '/user' . $s['link'];
												?>
												<a href="<?=$link?>" class="user">Заказать</a>
											<? else: ?>
												<a href="javascript:void(0)">Заказать</a>
											<? endif; ?>
										</div>
										<div class="clearfix"></div>
									</div>
								<? endforeach; ?>
							</div>
							<div class="clearfix"></div>
						</div>
					<? endforeach; ?>
				</div>
			<? endif; ?>
		<? endforeach; ?>
	</div>
</div>
<script type="text/javascript">
	var arSuccessMess = <?=json_encode(Yii::app()->user->getFlash('success'))?>;
</script>
<? require __DIR__ . '/popups.php'; ?>