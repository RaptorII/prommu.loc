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
						<b title="Адресная программа обязательна" class="js-g-hashint">*</b>
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
				<script type="text/javascript">var maxUsersInProject = <?=json_encode($viData['users-limit'])?>;</script>
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
								<div class="project__index-pen lindex">
									<label class="project__index-lbl">Улица</label>
									<input type="text" name="lindex" autocomplete="off">
								</div>
								<div class="project__index-pen lname">
									<label class="project__index-lbl">Название ТТ</label>
									<input type="text" name="lname" autocomplete="off">
								</div>
								<div class="project__index-pen lhouse">
									<label class="project__index-lbl">Дом</label>
									<input type="text" name="lhouse" autocomplete="off" data-checker="house">
								</div>
								<div class="project__index-pen lbuilding">
									<label class="project__index-lbl">Здание</label>
									<input type="text" name="lbuilding" autocomplete="off" data-checker="house">
								</div>
								<div class="project__index-pen lconstruction">
									<label class="project__index-lbl">Строение</label>
									<input type="text" name="lconstruction" autocomplete="off" data-checker="house">
								</div>
								<div class="project__index-pen lcorps">
									<label class="project__index-lbl">Корпус</label>
									<input type="text" name="lcorps" autocomplete="off" data-checker="house">
								</div>
							</div>
							<div class="project__index-row comment">
								<label class="project__index-lbl">Комментарий</label>
								<textarea name="comment"></textarea>
							</div>
							<div class="period-item" data-id="0">
								<span class="project__index-name">Период</span>
								<span class="period-del">&#10006</span>
								<div class="post-item">
									<label class="project__index-lbl">Должность</label>
									<div class="post-field project__index-arrow">
										<span class="post-select"></span>
										<input type="text" name="p" class="post-inp" autocomplete="off">
										<ul class="select-list">
											<li data-id="0" class="emp">Список пуст</li>
											<? foreach ($viData['workers']['posts'] as $post): ?>
												<li data-id="<?=$post['id']?>"><?=$post['name']?></li>
											<? endforeach; ?>
										</ul>
										<input type="hidden" name="post" value="">
									</div>
								</div>
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
								<?php require 'ankety-ajax.php'; ?>
							</div>
				    </div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
	$arPosts = array();
	for ($i=0, $n=sizeof($viData['workers']['posts']); $i < $n; $i++) 
		$arPosts[$viData['workers']['posts'][$i]['id']] = $viData['workers']['posts'][$i]['name'];
	require 'project-index-blocks.php'; // Блоки адресной программы для добавления
	require 'project-staff-blocks.php'; // Блоки персонала для приглашения
?>