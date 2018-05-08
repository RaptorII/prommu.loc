<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/ideas/page-idea.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/ideas/page-idea.js', CClientScript::POS_END);
	$arTypes = array(
		1 => array('class' => 'idea', 'name' => 'Идея'),
		2 => array('class' => 'error', 'name' => 'Ошибка'),
		3 => array('class' => 'question', 'name' => 'Вопрос'),
	);
?>
<div class="row">
	<div class="col-xs-12">
		<div class="idea__module">
			<div id="ideas-content">
				<div class="idea__item">
					<div class="idea__item-logo">
						<?php if(true): ?>
							<b class="js-g-hashint" title="В сети"></b>
						<?php endif; ?>
						<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180417134811177100.jpg"></a>
					</div>
					<div class="idea__item-info">
						<div class="idea__item-top">
							<span class="idea__item-type js-g-hashint <?=$arTypes[1]['class']?>" title="<?=$arTypes[1]['name']?>"></span>
							<span class="idea__item-name">Стабилизировать рейтинг, чтоб опускался в случае отрицательного отзыва</span>
						</div>
						<div class="idea__item-bottom">
							<a href="/ankety/7000" class="idea__item-author">Владимир Прищепа</a>
							<b>•</b>
							<span>05.04.2018</span>
							<b>•</b>
							<span class="idea__item-comments">12</span>
						</div>
					</div>
					<div class="idea__item-rating active">
						<div class="idea__item-rpos js-g-hashint" title="Поддерживаю">10</div>
						<div class="idea__item-rneg js-g-hashint" title="Не поддерживаю">2</div>
					</div>
				</div>
				<div class="idea__description">
					Вот ситуация. Плохой дядя пришел на сайтик обманул. Его там прикрыли. Но сайтиков же много!!! 
					<br><br>
					Вы на стороне своего сервера делаете службу которая принимает тревожные звонки от других таких сайтиков. И при этом предоставляет уже сформированный список тёмных 
					товарищей другим сайтикам. Вы выигрываете так как на вас будут равняться другие. Вам респект и уважуха. Другим завись и зависиммость от вас. :) А после лет эдак через дцать 
					Делайте это платным сервисом.
				</div>
				<div class="idea__comment">
					<div class="idea__set-rating active">
						<span class="idea__set-r-name">Проголосовать:</span>
						<div class="idea__set-rpos js-g-hashint" title="Поддерживаю">10</div>
						<div class="idea__set-rneg js-g-hashint" title="Не поддерживаю">2</div>						
					</div>
					<div class="idea__set-comment">Написать комментарий</div>
					<form id="comment-form">
						<textarea placeholder="Текст комментария"></textarea>
						<button type="submit" class="new-idea__btn" id="add-comment">Отправить</button>
					</form>
				</div>
				<div class="idea__comment-sort">
					<div class="idea__comment-cnt">Комментарии (36)</div>
					<div class="ideas__select" id="sort-params">
						<span>Сортировка</span>
						<ul>
							<li data-id="1" class="active">По дате <b class="glyphicon glyphicon-sort-by-attributes-alt"></b></li>
							<li data-id="2">По дате <b class="glyphicon glyphicon-sort-by-attributes"></b></li>
						</ul>
						<input type="hidden" name="type" value="1">
						<b class="glyphicon glyphicon-triangle-bottom"></b>
					</div>
				</div>
				<div id="comment-list">
					<div class="idea__comment-item">
						<div class="idea__item-logo">
							<?php if(true): ?>
								<b class="js-g-hashint" title="В сети"></b>
							<?php endif; ?>
							<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180417134811177100.jpg"></a>
						</div>
						<div class="idea__comment-info">
							<div class="idea__comment-name">Артем Маркович 03.02.2018</div>
							<div class="idea__comment-text">У меня уже клавиатура стерлась, при отметке сообщений этого MaksimKostukk как спам. И писал администрации об этом. Но складывается ощущение, что администрация просто уже совсем "устала" от администрирования freelancehunt.com :(</div>
						</div>
					</div>
					<div class="idea__comment-item">
						<div class="idea__item-logo">
							<?php if(false): ?>
								<b class="js-g-hashint" title="В сети"></b>
							<?php endif; ?>
							<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180417134811177100.jpg"></a>
						</div>
						<div class="idea__comment-info">
							<div class="idea__comment-name">Артем Маркович 04.02.2018</div>
							<div class="idea__comment-text">Тут все на заказчиков ругаются. А я хочу предупредить всех заказчиков о недобросовестном исполнителе. Имя его -Денис Иванов (ghostsoul). Он обычный мошенник.Встретились мы на фрилансджобе, я его выбрала, сделала 50% предоплату и этот гад протянув целый месяц так ничего и не сделал. Потом мне пару-тройку месяцев рассказывал о проблемах в личной жизни, обещал вернуть мои несчастные 100 баксов, лишь бы я отзыв плохой не писала про него. А потом просто пропал. Вот так бывает. На том сайте его уже давно нет, а здесь нашла его. Но возможности оставить честный отзыв о нем в его аккаунте -нет и это плохо!</div>
						</div>
					</div>
					<div class="idea__comment-item">
						<div class="idea__item-logo">
							<?php if(true): ?>
								<b class="js-g-hashint" title="В сети"></b>
							<?php endif; ?>
							<a href="/ankety/7000"><img src="https://prommu.com/images/company/tmp/20180417134811177100.jpg"></a>
						</div>
						<div class="idea__comment-info">
							<div class="idea__comment-name">Максимус 05.02.2018</div>
							<div class="idea__comment-text">Не пожалею 1000 грн - посажу студента чтобы каждый день по 20 левых проектов вешал - и все ставки в отказ!!!!!! Наверное тогда примите меры!</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<form action="<?=MainConfig::$PAGE_IDEAS_LIST?>" method="POST" id="new-idea">
		</form>
	</div>
</div>