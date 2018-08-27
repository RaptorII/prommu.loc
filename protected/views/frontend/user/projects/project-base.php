<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-base.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-base.js', CClientScript::POS_END);

  $projectName = 'Мерчендайзинг';
  $arProgram = array(
    1307 => array(
      'name' => 'Москва',
      'id' => 1307,
      'metro' => 1,
      'locations' => array(
        1 => array(
          'id' => 1,
          'name' => 'АТБ1',
          'index' => 'ул. Исполкомовская 123',
          'metro' => array(
            1 => 'Авиамоторная',
            2 => 'Автозаводская (Замоскворецкая линия)',
            4 => 'Алексеевская'
          ),
          'periods' => array(
            1 => array(
              'id' => 1,
              'bdate' => '07.02.18',
              'edate' => '08.02.18',
              'btime' => '14:00',
              'etime' => '16:00'
            ),
            2 => array(
              'id' => 2,
              'bdate' => '20.02.18',
              'edate' => '22.02.18',
              'btime' => '09:00',
              'etime' => '18:00'
            )
          )
        ),
        2 => array(
          'id' => 2,
          'name' => 'АТБ2',
          'index' => 'ул. Исполкомовская 777',
          'metro' => array(
            4 => 'Алексеевская'
          ),
          'periods' => array(
            3 => array(
              'id' => 3,
              'bdate' => '01.08.18',
              'edate' => '01.08.18',
              'btime' => '12:00',
              'etime' => '13:00'
            )
          )
        )
      )
    ),
    2582 => array(
      'name' => 'Донецк',
      'id' => 2582,
      'metro' => 0,
      'locations' => array(
        3 => array(
          'id' => 3,
          'name' => 'АТБ3',
          'index' => 'ул. Исполкомовская 999',
          'metro' => array(),
          'periods' => array(
            4 => array(
              'id' => 4,
              'bdate' => '07.02.18',
              'edate' => '08.02.18',
              'btime' => '14:00',
              'etime' => '16:00'
            ),
            5 => array(
              'id' => 5,
              'bdate' => '20.02.18',
              'edate' => '22.02.18',
              'btime' => '09:00',
              'etime' => '18:00'
            )
          )
        ),      
      )
    )
  );
?>
<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>
<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
			<? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
		</div>
		<div id="content">
			<div class="project__module">
				<div class="project__xls">
					<a href="javascript:void(0)" id="add-xls">Изменить адресную программу</a>
					<a href="/uploads/prommu_example.xls" download>Скачать пример для добавления</a>
				</div>
				<h1 class="project__title">ПРОЕКТ: <span><?=$projectName?></span></h1>
				<table class="project__program">
					<tbody>
						<? foreach ($arProgram as $id => $arCity): ?>
							<tr class="program__item" data-city="<?=$id?>">
								<td colspan="5">
									<div class="program__city border">
										<b><?=$arCity['name']?></b>
										<span class="address__item-change">
											<span>изменить</span>
											<ul>
												<li><a href="<? echo $_GET['id'] . '/address-edit?city=' . $id ?>">изменить</a></li>
												<li data-id="<?=$id?>" class="delcity">удалить</li>
											</ul>
										</span>
									</div>
								</td>
							</tr>
							<? foreach ($arCity['locations'] as $idloc => $arLoc): ?>
								<tr class="loc-item" data-city="<?=$id?>">
									<td>
										<div class="program__cell green-name"><?=$arLoc['name']?></div>
									</td>
									<td <?=(empty($arCity['metro'])?'colspan="2"':'')?>>
										<div class="program__cell border"><?=$arLoc['index']?></div>
									</td>
									<? if(!empty($arCity['metro'])): ?>
										<td>
											<div class="program__cell border"><? echo join(',</br>', $arLoc['metro']) ?></div>
										</td>
									<? endif; ?>
									<td>
										<div class="program__cell border user">
											<? /* ?>
											<div class="program__cell-users">
												<div class="program__cell-user">
													<img src="/theme/pic/projects/user-logo.png">
													<span>Александр Примак</span>
													<a href="#"><span>Изменить</span></a>
												</div>
											</div>
											<? */ ?>

											<? foreach ($arLoc['periods'] as $idper => $arPer): ?>
												<div class="program__select-user" data-period="<?=$idper?>">
													<a href="<? echo $_GET['id'] . '/users-select?period=' . $idper ?>" class="program-select-user__title">
														<span>Выбрать персонал </span>
														<b>&#9660</b>
													</a>
												</div>
											<? endforeach; ?>
										</div>
									</td>
									<td class="period-data">
										<div class="program__cell border">
											<? foreach ($arLoc['periods'] as $idper => $arPer): ?>
												<div class="program__cell-period" data-period="<?=$idper?>">
													<span><? echo $arPer['bdate'] . ' до ' . $arPer['edate'] ?></span>
													<span class="program__cell-tiem"><? echo $arPer['btime'] . ' - ' . $arPer['etime'] ?></span>
													<span class="address__item-change period">
														<span>изменить</span>
														<ul>
															<li><a href="<? echo $_GET['id'] . '/address-edit?city=' . $id . '&per=' . $idper ?>">изменить</a></li>
															<li data-id="<?=$idper?>" class="delperiod">удалить</li>
														</ul>
													</span>
												</div>
											<? endforeach; ?>
										</div>
									</td>
								</tr>
							<? endforeach; ?>
							<?
							/*
							?>
							<tr data-city="<?=$id?>">
								<td colspan="5">
									<div class="program__btns">
										<a href="#" class="program__add-btn">+ ДОБАВИТЬ ПЕРИОД</a>
										<a href="#" class="program__save-btn">СОХРАНИТЬ</a>
									</div>
								</td>
							</tr>
							<?
							*/
							?>
						<? endforeach; ?>
					</tbody>
				</table>
				<form action="" method="POST" id="base-form">
					<input type="hidden" name="project" class="project-inp" value="<?=$_GET['id']?>">
					<input type="file" name="xls" id="add-xls-inp" class="hide">
				</form>
			</div>
		</div>
	</div>
</div>
<?
/*
?>
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
<?
*/
?>