<?php
    $title = 'Создание нового проекта';
    $this->setBreadcrumbsEx(
        array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
        array($title, MainConfig::$PAGE_PROJECT_NEW)
    );
    $this->setPageTitle($title);

	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/phone-codes/style.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/phone-codes/projects.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/new.js', CClientScript::POS_END);
?>

<div class="row project">
	<div class="col-xs-12">
		<form action="/user/procreate" method="POST" id="new-project">
			<div id="main" class="project__module">
				<div class="project__name">
					<input type="text" name="name" placeholder="*Введите название проекта" autocomplete="off" id="project-name">
				</div>
				<div class="project__block">
					<div class="project__opt">
						<div class="project__opt-row" data-btn="index">
							<span>Добавить адресную программу</span>
							<span class="project__opt-btn" data-event="index">Выбрать</span>
						</div>
						<div class="project__opt-row" data-btn="xls">
							<span>Добавить адресную программу через XLS</span>
							<span class="project__opt-btn" id="add-xls">Выбрать</span>

							<input type="file" name="xls" id="add-xls-inp">
						</div>
						<div id="add-xls-name"></div>
						<div class="project__opt-row" data-btn="xls">
							<a href="/uploads/prommu_example.xls" download class="project__opt-xls">Скачать пример адресной программы</a>
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
			<?php
			//
			?>
			<div id="index" class="project__module" data-country="1">
				<h2 class="project__title">ДОБАВИТЬ АДРЕСНУЮ ПРОГРАММУ В ПРОЕКТ <span></span></h2>
				<div class="project__index">
					<div class="city-item" data-city="">
						<span class="project__index-name">Город</span>
						<span class="city-del">&#10006</span>
						<span class="add-loc-btn">Добавить еще ТТ</span>
						<div class="project__index-row">
							<label class="project__index-lbl">Город</label>
							<div class="city-field project__index-arrow">
								<span class="city-select"></span>
								<input type="text" name="c" class="city-inp" autocomplete="off">
								<ul class="select-list"></ul>
								<input type="hidden" name="city[]" value="">
							</div>
						</div>
						<div class="loc-item" data-id="0">
							<span class="project__index-name">Локация</span>
							<span class="loc-del">&#10006</span>
							<span class="add-period-btn">Добавить период</span>
							<div class="project__index-row loc-field">
								<div class="project__index-pen">
									<label class="project__index-lbl">Адрес ТТ</label>
									<input type="text" name="lindex" autocomplete="off">
								</div>
								<div class="project__index-pen">
									<label class="project__index-lbl">Название ТТ</label>
									<input type="text" name="lname" autocomplete="off">
								</div>
							</div>
							<div class="period-item" data-id="0">
								<span class="project__index-name">Период</span>
								<span class="period-del">&#10006</span>
								<div class="period-field">
									<label class="project__index-lbl">Дата</label>
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
									<input type="hidden" name="bdate">
								</div>
								<div class="period-field">
									<label class="project__index-lbl">по</label>
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
									<input type="hidden" name="edate">
								</div>
								<div class="project__index-pen time-item">
									<label class="project__index-lbl">Время работы</label>
									<input type="text" name="btime" class="time-inp">
								</div>
								<div class="project__index-pen time-item">
									<label class="project__index-lbl">по</label>
									<input type="text" name="etime" class="time-inp">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="project__all-btns">
					<span class="project__btn-white" id="add-city-btn">ДОБАВИТЬ ГОРОД</span>
					<span class="save-btn" id="save-index" data-event="main">СОХРАНИТЬ</span>
				</div>
			</div>
			<?php
			//
			?>
			<div id="invitation" class="project__module">
				<h2 class="project__title">ПРИГЛАСИТЬ В ПРОЕКТ <span></span></h2>
				<div class="project__body project__body-invite invitation-item" data-id="0">
					<span class="invitation-del">&#10006</span>
					<div>
						<input type="text" name="inv-name[0]" placeholder="Имя" class="invite-inp name">
					</div>
					<div>
						<input type="text" name="inv-sname[0]" placeholder="Фамилия" class="invite-inp sname">
					</div>
					<div>
						<input type="text" name="inv-phone[0]" placeholder="Телефон" class="invite-inp phone">
					</div>
					<div>
						<input type="text" name="inv-email[0]" placeholder="E-mail" class="invite-inp email">
					</div>
				</div>
				<div class="project__all-btns">
					<div class="project__invite-btns">
						<span class="project__btn-white" id="add-prsnl-btn" data-event="main">+ДОБАВИТЬ ЕЩЕ ПЕРСОНАЛ</span>
						<span class="save-btn" id="save-prsnl-btn" data-event="main">СОХРАНИТЬ</span>
					</div>
				</div>
			</div>
			<?php
			//
			?>
			<div id="addition" class="project__module">
				<h2 class="project__title">ДОБАВИТЬ НОВЫЙ ПЕРСОНАЛ НА ПРОЕКТ <span></span></h2>
				  <?php
				    $viData['app_idies'] = array();
				    foreach ($viData['workers']['promos'] as $key => $idus)
				    	$viData['app_idies'][] = intval($idus['id_user']);
				  ?>
				  <script type="text/javascript">
				    var arIdies = <?=json_encode($viData['app_idies'])?>;
				    var AJAX_GET_PROMO = "<?=MainConfig::$PAGE_SERVICES_PUSH?>";
				  </script>
				  <div class='row'>
				    <? //		FILTER 		?>
				    <div class="filter__veil"></div>
				    <div class='col-xs-12 col-sm-4 col-md-3'>
				      <div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
				      <div id="promo-filter">
				        <div class='filter'>
				          <div class='filter__item filter-cities'>
				            <div class='filter__item-name opened'>Город</div>
				            <div class='filter__item-content opened'>
				                <div class="fav__select-cities" id="filter-city">
									<ul class="filter-city-select">
										<li data-id="0">
											<input type="text" name="fc" class="city-inp" autocomplete="off">
										</li>
									</ul>
									<ul class="select-list"></ul>
				                </div>
				            </div>
				          </div>
				          <div class='filter__item filter-posts'>
				            <div class='filter__item-name opened'>Должность</div>
				            <div class='filter__item-content opened'>
				              <div class='right-box'>
				                <?php
				                $sel = 0;
				                foreach($viData['workers']['posts'] as $p)
				                  if($p['selected']) $sel++;
				                ?>
				                <input name='posts-all' type='checkbox' id="f-all-posts" class="filter__chbox-inp"<?=sizeof($viData['workers']['posts'])==$sel ?' checked':''?>>
				                <label class='filter__chbox-lab' for="f-all-posts">Выбрать все / снять все</label>
				                <?php foreach($viData['workers']['posts'] as $p): ?>
				                  <input name='posts[]' value="<?=$p['id']?>" type='checkbox' id="f-post-<?=$p['id']?>" class="filter__chbox-inp" <?=$p['selected'] ? 'checked' : ''?>>
				                  <label class='filter__chbox-lab' for="f-post-<?=$p['id']?>"><?=$p['name']?></label>
				                <?php endforeach; ?>
				              </div>
				              <span class="more-posts">Показать все</span>
				            </div>
				          </div>
				          <div class='filter__item filter-age'>
				            <div class='filter__item-name opened'>Возраст</div>
				            <div class='filter__item-content opened'>
				              <div class="filter__age">
				                <label>
				                  <span>от</span>
				                  <input name=af type='text' value="<?=$_POST['af']?>">
				                </label>
				                <label>
				                  <span>до</span>
				                  <input name='at' type='text' value="<?=$_POST['at']?>">
				                </label>
				                <div class="filter__age-btn">ОК</div>
				              </div>
				            </div>
				          </div>
				          <div class='filter__item filter-sex'>
				            <div class='filter__item-name opened'>Пол</div>
				            <div class='filter__item-content opened'>
				              <div class='right-box'>
				                <input name='sm' type='checkbox' value='1' class="filter__chbox-inp" id="f-male"<?=($_POST['sm']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-male">Мужской</label>
				                <input name='sf' type='checkbox' value='1' class="filter__chbox-inp" id="f-female"<?=($_POST['sf']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-female">Женский</label>
				              </div>
				            </div>
				          </div>
				          <div class='filter__item filter-additional'>
				            <div class='filter__item-name opened'>Дополнительно</div>
				            <div class='filter__item-content opened'>
				              <div class='right-box'>
				                <input name='mb' type='checkbox' value='1' class="filter__chbox-inp" id="f-med"<?=($_POST['mb']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-med">Наличие медкнижки</label>
				                <input name='avto' type='checkbox' value='1' class="filter__chbox-inp" id="f-auto"<?=($_POST['avto']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-auto">Наличие автомобиля</label>
				                <input name='smart' type='checkbox' value='1' class="filter__chbox-inp" id="f-smart"<?=($_POST['smart']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-smart">Наличие смартфона</label>
				                <input name='cardPrommu' type='checkbox' value='1' class="filter__chbox-inp" id="f-pcard"<?=($_POST['cardPrommu']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-pcard">Банковская карта Prommu</label>
				                <input name='card' type='checkbox' value='1' class="filter__chbox-inp" id="f-card"<?=($_POST['card']?' checked':'')?>>
				                <label class="filter__chbox-lab" for="f-card">Банковская карта</label>
				              </div>
				            </div>
				          </div>
				          </div>
				      </div>
				    </div>
				    <?php //    CONTENT 		?>
				    <div class='col-xs-12 col-sm-8 col-md-9'>
						<div id="workers-form">
							<span class="workers-form__cnt">Выбрано соискателей: <span id="mess-wcount">0</span></span>
							<div class="service__switch">
								<span class="service__switch-name">Выбрать всех</span>
								<input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
								<label for="all-workers">
									<span data-enable="вкл." data-disable="выкл."></span>
								</label>
							</div>
							<span class="workers-form-btn off" id="workers-btn" data-event="main">СОХРАНИТЬ</span>
							<input type="hidden" name="users" id="mess-workers">
							<input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
						</div>
				      <div id="promo-content">
				        <div class='questionnaire'>
				          <div>
				            <?=$this->ViewModel->declOfNum($viData['app_count'], array('Найдена', 'Найдено', 'Найдено'))?>
				            <b><?=$viData['app_count']?></b>
				            <?=$this->ViewModel->declOfNum($viData['app_count'], array('Анкета', 'Анкеты', 'Анкет'))?>
				          </div>
				        </div>
				        <div class='row vacancy table-view'>
				          <?if( $viData['workers']['promo'] ):?>
				            <?$i=1;?>
				            <?foreach ($viData['workers']['promo'] as $item):?>
				              <div class='col-xs-12 col-sm-6 col-md-4'>
				                <?
				                  $G_NOLIKES = 1;
				                  $G_ALT = 'Соискатель ' . $item['firstname'] . ' ' . $item['lastname'] . ' prommu.com';
				                  $G_LOGO_LINK = MainConfig::$PAGE_PROFILE_COMMON . DS . $item['id_user'];
				                  if($item['sex'] === '1'){
				                    $G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO : $item['photo'] . '400.jpg');
				                  }
				                  else
				                    $G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO_F : $item['photo'] . '400.jpg');
				                  $G_COMP_FIO = $item['firstname'] . ' ' . $item['lastname'] . ', ' . $item['age'];
				                  $G_RATE_POS = $item['rate'];
				                  $G_RATE_NEG = $item['rate_neg'];
				                  $G_COMMENTS_POS = $item['comm'];
				                  $G_COMMENTS_NEG = $item['commneg'];
				                  $G_TMPL_PH1 = '';
				                  if( $item['ishasavto'] === '1' ) $G_TMPL_PH1 = "<div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>";
				                  if( $item['ismed'] === '1' ) $G_TMPL_PH1 .= '<div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>';
				                  $G_TMPL_PH1 = "<div class='med-avto'>{$G_TMPL_PH1}</div>";
				                  include $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user' . DS . MainConfig::$VIEWS_COMM_LOGO_TPL . ".php";
				                ?>
				                <input type="checkbox" name="promo[]" value="<?=$item['id_user']?>" class="promo_inp" id="promo<?=$item['id_user']?>">
				                <label class="smss-promo__label" for="promo<?=$item['id_user']?>"></label>
				              </div>
				              <?if($i % 2 == 0):?>
				                <div class="clear visible-sm"></div>
				              <?endif?>
				              <?if( $i % 3 == 0 ):?>
				                <div class="clear visible-md visible-lg"></div>
				              <?endif?>
				              <?$i++;?>
				            <?endforeach?>
				          <?else:?>
				            <div class="col-xs-12">Нет подходящих соискателей</div>
				          <?endif;?>
				        </div>
				        <br>
				        <br>
				        <div class='paging-wrapp hidden-xs'>
				        <?php
				          $this->widget('CLinkPager', array(
				              'pages' => $viData['pages'],
				              'htmlOptions' => array('class' => 'paging-wrapp'),
				              'firstPageLabel' => '1',
				              'prevPageLabel' => 'Назад',
				              'nextPageLabel' => 'Вперед',
				              'header' => ''
				            )
				          )?>
				        </div>
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
	<div class="city-item" data-city="">
		<span class="project__index-name">Город</span>
		<span class="city-del">&#10006</span>
		<span class="add-loc-btn">Добавить еще ТТ</span>
		<div class="project__index-row">
			<label class="project__index-lbl">Город</label>
			<div class="city-field project__index-arrow">
				<span class="city-select"></span>
				<input type="text" name="c" class="city-inp" autocomplete="off">
				<ul class="select-list"></ul>
				<input type="hidden" name="city[]" value="">
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="loc-content">
	<div class="loc-item" data-id="0">
		<span class="loc-del">&#10006</span>
		<span class="project__index-name">Локация</span>
		<span class="add-period-btn">Добавить период</span>
		<div class="project__index-row loc-field">
			<div class="project__index-pen">
				<label class="project__index-lbl">Адрес ТТ</label>
				<input type="text" name="lindex" autocomplete="off">
			</div>
			<div class="project__index-pen">
				<label class="project__index-lbl">Название ТТ</label>
				<input type="text" name="lname" autocomplete="off">
			</div>
		</div>
	</div>
</div>
<div class="hidden" id="period-content">
	<div class="period-item" data-id="0">
		<span class="period-del">&#10006</span>
		<span class="project__index-name">Период</span>
		<div class="period-field">
			<label class="project__index-lbl">Дата</label>
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
			<input type="hidden" name="bdate">
		</div>
		<div class="period-field">
			<label class="project__index-lbl">по</label>
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
			<input type="hidden" name="edate">
		</div>
		<div class="project__index-pen time-item">
			<label class="project__index-lbl">Время работы</label>
			<input type="text" name="btime" class="time-inp">
		</div>
		<div class="project__index-pen time-item">
			<label class="project__index-lbl">по</label>
			<input type="text" name="etime" class="time-inp">
		</div>
	</div>
</div>
<div class="hidden" id="metro-content">
	<div class="metro-item">
		<label class="project__index-lbl">Метро</label>
		<div class="metro-field project__index-arrow">
			<ul class="metro-select">
				<li data-id="0">
					<input type="text" name="m" class="metro-inp" autocomplete="off">
				</li>
			</ul>
			<ul class="select-list"></ul>
			<input type="hidden" name="metro" value="">
		</div>
	</div>
</div>
<div class="hidden" id="invitation-content">
	<div class="project__body project__body-invite invitation-item" data-id="">
		<span class="invitation-del">&#10006</span>
		<div>
			<input type="text" name="" placeholder="Имя" class="invite-inp name">
		</div>
		<div>
			<input type="text" name="" placeholder="Фамилия" class="invite-inp sname">
		</div>
		<div>
			<input type="text" name="" placeholder="Телефон" class="invite-inp phone">
		</div>
		<div>
			<input type="text" name="" placeholder="E-mail" class="invite-inp email">
		</div>
	</div>
</div>