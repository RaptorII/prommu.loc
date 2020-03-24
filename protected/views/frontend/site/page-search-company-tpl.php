<?php
  Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-empl-search.min.js', CClientScript::POS_END);
  $this->renderPartial('../site/page-search-company-tpl_css');
?>
<div class='row page-search-empl'>
  <script type="text/javascript">var arAllData = <?=json_encode($viData['filter'])?></script>
  <div class="pse__veil"></div>
  <div class="col-xs-12">
      <?php if(Share::$UserProfile->type == 3): ?>
          <div class="pse__header">
              <h1 class="pse__header-name"><?=Share::$UserProfile->exInfo->name?></h1>
              <a class='pse__btn pse__header-btn btn__orange' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
          </div>
      <?php endif; ?>
  </div>
  <div class='col-xs-12 col-sm-3'> 
<?
/*
*   FILTER
*/
?>
	<div class="pse__filter-vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
    <form action="" id="F1Filter" method="get">
      <div class='filter'>
        <div class='pse__filter-block filter-surname'>
          <div class='pse__filter-name opened'>Название компании</div>
          <div class='pse__filter-content opened'>
            <input name='qs' type='text' title="Введите название компании" value="<?=$_GET['qs']?>" class="pse__input pse__input--width">
            <div class="pse__filter-btn btn__orange">ОК</div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class='pse__filter-block filter-cities'>
          <div class='pse__filter-name opened'>Город</div>
			<div class='pse__filter-content opened'>
				<?
					if(in_array(Share::$UserProfile->type, [2,3])) {
						$arRes = Yii::app()->db->createCommand()
							->select('c.id_co country')
							->from('user_city uc')
							->join('city c', 'uc.id_city=c.id_city')
							->where('id_user=:id_user', array(':id_user' => Share::$UserProfile->id))
							->queryRow();
					}
					else {
						$geo = new Geo();
						$arRes = $geo->getUserGeo();
					}
				?>
				<div class="fav__select-cities" id="filter-city" data-city="<?=$arRes['country']?>">
					<ul class="filter-city-select">
						<? if(isset($_GET['cities'])): ?>
							<? foreach ($_GET['cities'] as $id): ?>
							<li>
								<?=$viData['filter']['cities'][$id]['name']?>
								<b></b>
								<input type="hidden" name="cities[]" value="<?=$id?>">
							</li>
							<? endforeach; ?>
						<? endif; ?>
						<li data-id="0">
							<input type="text" name="fc" class="city-inp" autocomplete="off">
						</li>
					</ul>
					<ul class="select-list"></ul>
				</div>
			</div>
        </div>
        <div class='pse__filter-block filter-type'>
            <div class='pse__filter-name opened'>Тип работодателя</div>
              <div class='pse__filter-content opened'>
                <div class='right-box'>
                    <input name='cotype-all' type='checkbox' id="pse-cotype-all" class="pse__checkbox-input">
                    <label class='pse__checkbox-label pse__checkbox-label-ct-all' for="pse-cotype-all">Выбрать все / снять все</label>
                    <?php foreach($viData['filter']['cotype'] as $id => $name): ?>
                      <input name='cotype[]' value="<?=$id?>" type='checkbox' id="pse-cotype-<?=$id?>" class="pse__checkbox-input" <?=(in_array($id, $_GET['cotype']) ? 'checked' : '')?>>
                      <label class='pse__checkbox-label' for="pse-cotype-<?=$id?>"><?=$name?></label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
      </div>
    </form>
  </div>
<?
/*
*   CONTENT
*/
?>
  <div class='col-xs-12 col-sm-9' id="content">
    <?php if( !count($viData['empls']) ): ?>
      <div class="pse__nothing">Нет подходящих компаний</div>
    <?php else: ?>
        <div class='questionnaire'>
          <div>
            Найдено
            <b><?= $count ?></b>
            <span class='hidden-xs'>зарегистрированных</span>
            работодателей
          </div>
        </div>
        <?php /* BM: list view */ ?>
        <div class='list-view'>
          <?php foreach ($viData['empls'] as $key => $val): ?>
              <div class='company-list-item-box'>
                  <div class='row'>
                      <div class='col-xs-12 col-sm-3 col-lg-2'>
                          <div class='company-logo-wrapp'>
                              <div class='company-logo'>
                                  <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>
                                      <img
                                      	alt='Работодатель <?= $val['name'] ?> prommu.com'
                                      	src="<?=Share::getPhoto($val['id_user'],3,$val['logo'])?>">
                                      <?php if($val['is_online']): ?>
                                        <span class="empl-list__item-onl"><span>В сети</span></span>
                                      <?php endif; ?>
                                  </a>
                              </div>
                          </div>
                      </div>
                      <div class='col-xs-12 col-sm-9 col-lg-10'>
                          <div class='title-block'>
                              <div class='expirience'><?php /* $val['exp'] */ ?></div>
                              <h2>
                                  <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'><?= $val['name'] ?></a>
                                  <small>(№ <?= $val['id'] ?>)</small>
                              </h2>
                          </div>
                          <div class="rate-block">
                              <div class="com-rate">
                                Рейтинг: 
                                <span class="js-g-hashint" title="Всего"><?=($val['rate'] + $val['rate_neg'])?></span>
                                (<b class="-green js-g-hashint" title="Положительный"><?=$val['rate']?></b> 
                                / <b class="-red js-g-hashint" title="Отрицательный"><?=$val['rate_neg']?></b>)
                              </div>
                              <table class='rate hide-rate'>
                                  <thead>
                                  <tr class="rate-tpl">
                                      <td class='val'>
                                          <span class="num"></span> (
                                          <span class='good' title='отлично'></span>
                                          /
                                          <span class='bad' title='плохо'></span>
                                          )
                                      </td>
                                      <td class='progress'>
                                          <div class='progr-line' style="">&nbsp;</div>
                                          <div class='text'><!-- ratename--></div>
                                      </td>
                                  </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                          <br>
                          <div class='place'>
                              <h3>
                                  Город:
                                  <small><?= join(', ', array_values($val['city'])) ?></small>
                              </h3>
                          </div>
                          <?php if ($val['metroes']): ?>
                              <div class='place'>
                                  <h3>
                                      Метро:
                                      <small><?= join(', ', array_values($val['metroes'])) ?></small>
                                  </h3>
                              </div>
                          <?php endif; ?>
                          <div class='type'>
                            <?php if(isset($val['tname'])): ?>
                              <h3>Работодатель: <small><?= $val['tname'] ?></small></h3>
                            <?php endif; ?>
                          </div>
                      </div>
                  </div>
                  <div class='row'>
                      <div class='col-xs-12 col-md-8 col-md-push-4'>
                          <div class='btn-more btn-white-green-wr'>
                              <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>Подробнее</a>
                          </div>
                      </div>
                  </div>
              </div>
          <?php endforeach; ?>
        </div>
        <script type="text/javascript">
        	$(function(){ G_VARS.DEF_LOGO_EMPL = '<?= MainConfig::$DEF_LOGO_EMPL ?>' })
        </script>
        <br>
        <br>
        <?php // display pagination
            $this->widget('CLinkPager', array(
              'pages' => $pages,
              'htmlOptions' => array('class' => 'paging-wrapp'),
              'firstPageLabel' => '1',
              'prevPageLabel' => 'Назад',
              'nextPageLabel' => 'Вперед',
              'header' => '',
              'cssFile' => false
          ));
        ?>
  <?php endif; ?>
  </div>
  <div class="col-xs-12 pse__content" id="pse-seo-text"><?php 
    echo $this->ViewModel->getViewData()->pageMetaKeywords;
  ?></div>
</div>