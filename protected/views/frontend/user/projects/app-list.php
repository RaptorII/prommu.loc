<?php
$title = 'Мои проекты';
$this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
$this->setPageTitle($title);
$bUrl = Yii::app()->baseUrl;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/app-list.css');
?>
    <pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>


    <div class="row projects">
        <div class="col-xs-12">

            <? if (count($viData['new-items']) > 0): ?>
                <h1 class="projects__title">ПРИГЛАШЕНИЯ НА ПРОЕКТЫ</h1>
                <div class="projects__list">
                    <? foreach ($viData['new-items'] as $key => $value): ?>
                        <div class="projects__item">
                            <div class="projects__item-image">
                                <a href="<?=$value['profile']?>" target="_blank"><img src="<?=$value['src']?>"/></a>
                            </div>
                            <div class="projects__item-name">
                               <?=$value['name']?>
                            </div>
                            <div class="projects__item-control">
                                <a href="/user/projects/?project=<?=$value['project']?>&status=1" class="projects__item-button item__submit">Принять</a>
                                <a href="/user/projects/?project=<?=$value['project']?>&status=-1" class="projects__item-button item__cancel">Отказаться</a>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            <? endif; ?>


            <? if (count($viData['items']) > 0): ?>
                <h1 class="projects__title">ПРОЕКТЫ</h1>
                <div class="projects__list">
                    <? foreach ($viData['items'] as $key => $value): ?>
                        <div class="projects__item">
                            <div class="projects__item-image">
                                <a target="_blank" href="<?=$value['profile']?>" target="_blank"><img src="<?=$value['src']?>"/></a>
                            </div>
                            <div class="projects__item-name projects__item-full">
                                <a href="<?=$value['link']?>"><?=$value['name']?></a>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            <? else: ?>

                <h1 class="projects__title">НЕТ ПРИНЯТЫХ ПРОЕКТОВ</h1>

            <? endif; ?>

        </div>
    </div>
<? /*
<div class="row projects">
	<div class="col-xs-12">
		<table class="projects__table">
			<thead>
				<tr>
					<th rowspan="2">Название и адрес ТТ</th>
					<th rowspan="2">Факт прибытия</th>
					<th rowspan="2">Факт убытия</th>
					<th rowspan="2">Факт пребывания в ТТ</th>
					<th colspan="2">Задачи</th>
					<th rowspan="2">
						<span>Выбрать дату:</span>
						<div id="filter-date">
							<span>20.02.2018</span>
						</div>
					</th>
				</tr>
				<tr>
					<th>план</th>
					<th>факт</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div class="prj-table__cell">
							<span>ул. Пирогова 23</span> <span class="green-t text-uppercase">атб1</span>
						</div>
					</td>
					<td>
						<div class="prj-table__cell">в 9:00</div>
					</td>
					<td>
						<div class="prj-table__cell">в 13:00</div>
					</td>
					<td>
						<div class="prj-table__cell green-t">4 часа</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">
							<a href="#" class="prj-table__btn active">СМОТРЕТЬ</a>
						</div>
					</td>
				</tr>
				<tr class="prj-table__item-head">
					<td>Задачи</td>
					<td colspan="2">Статус выполнения</td>
					<td colspan="4">Примечание</td>
				</tr>
				<tr class="prj-table__item-body">
					<td>выносная торговля возле супермаркета Сильпо</td>
					<td colspan="2" class="text-center"><span class="green-t">ВЫПОЛНЕН</span></td>
					<td colspan="4" class="text-center">на улице мороз, взять чай в термосе</td>
				</tr>
				<tr class="prj-table__item-body">
					<td>уборка территории парка «Юность»</td>
					<td colspan="2" class="text-center"><span class="red-t">НЕ ВЫПОЛНЕН</span></td>
					<td colspan="4" class="text-center">весь парк в листьях, никто не убрал!!!</td>
				</tr>
				<tr>
					<td>
						<div class="prj-table__cell">
							<span>ул. Пирогова 23</span> <span class="green-t text-uppercase">атб1</span>
						</div>
					</td>
					<td>
						<div class="prj-table__cell">в 9:00</div>
					</td>
					<td>
						<div class="prj-table__cell">в 13:00</div>
					</td>
					<td>
						<div class="prj-table__cell green-t">4 часа</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">
							<a href="#" class="prj-table__btn">СМОТРЕТЬ</a>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="prj-table__cell">
							<span>ул. Пирогова 23</span> <span class="green-t text-uppercase">атб1</span>
						</div>
					</td>
					<td>
						<div class="prj-table__cell">в 9:00</div>
					</td>
					<td>
						<div class="prj-table__cell">в 13:00</div>
					</td>
					<td>
						<div class="prj-table__cell green-t">4 часа</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">4</div>
					</td>
					<td>
						<div class="prj-table__cell">
							<a href="#" class="prj-table__btn">СМОТРЕТЬ</a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

*/ ?>