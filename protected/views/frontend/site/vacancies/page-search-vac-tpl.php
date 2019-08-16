<div class='row'>
    <form action="" id="F1Filter" method="get">
        <input type="hidden" name="view" value="<?= Yii::app()->getRequest()->getParam('view') == 't' ? 't' : '' ?>"/>
        <div class='col-xs-12 col-sm-4 col-md-3 hidden-xs filter'>
<!--            <h3>Фильтр</h3>-->
            <div class='btn-apply btn-white-green-wr'>
                <button type='submit'>Применить</button>
            </div>
            <?php /*
            <div class='filter-label filter-qs'>
              <label for='ChkQS'><b>Название должности</b></label>
              <div class='filter-content'>
                <div class='filter-title clearfix'>
                  <input id='ChkQS' name='qs' type='text' title="Введите название" value="<?= $viData['qs'] ?>">
                  <br>
                </div>
              </div>
            </div>
            */ ?>
            <div class='filter-label filter-cities clearfix'>
                <label class='filter-name <?= $viData['selected']['city'] ? 'opened' : '' ?>'>Город</label>
                <div class='filter-content <?= $viData['selected']['city'] ? 'opened' : '' ?>'>
                    <select class='multiple' id='CBcities' multiple='multiple' name='cities[]'>
                        <?php foreach ($viData['city'] as $key => $val): ?>
                            <option value='<?= $val['id'] ?>' selected><?= $val['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class='filter-label filter-dolj'>
                <label class='filter-name <?= $viData['selected']['posts'] || $viData['poself'] ? 'opened' : '' ?>'>Должность</label>
                <div class='filter-content <?= $viData['selected']['posts'] || $viData['poself']? 'opened' : '' ?>'>
                    <div class='right-box'>
                        <label class='checkbox-box checked' for='ChkAllContacts'>
                            Выбрать все / снять все
                            <input <?= $viData['postsAll'] ? 'checked' : '' ?> id='ChkAllContacts' name='poall' type='checkbox'>
                            <span></span>
                        </label>
                        <br>
                        <?php foreach($viData['posts'] as $val): ?>
                            <label class='checkbox-box' for='ChkShowContacts<?= $val['id'] ?>'>
                                <?= $val['name'] ?>
                                <input <?= $val['selected'] ? 'checked' : '' ?> class='dolj' id='ChkShowContacts<?= $val['id'] ?>' name='post[<?= $val['id'] ?>]' type='checkbox'>
                                <span></span>
                            </label>
                            <br>
                        <?php endforeach; ?>
                    </div>
                    <div class='self-dolj'>
                        <label>
                            Свой вариант
                        </label>
                        <input name='poself' type='text' value="<?= $viData['poself'] ?>">
                        <br />
                        <br />
                    </div>
                </div>
            </div>

            <div class='filter-label filter-busy'>
                <label class='filter-name <?= $viData['bt'] == '1' || $viData['bt'] == '2' ? 'opened' : '' ?>'>Вид занятости</label>
                <div class='filter-content <?= $viData['bt'] == '1' || $viData['bt'] == '2' ? 'opened' : '' ?>'>
                    <div class='radio-box right-box'>
                        <label class='radio-box' for='RB1busy'>
                            Временная
                            <input id='RB1busy' name='bt' type='radio' value='1' <?= $viData['bt'] == '1' ? 'checked' : '' ?>>
                            <span></span>
                        </label>
                        <br>
                        <label class='radio-box' for='RB2busy'>
                            Постоянная
                            <input id='RB2busy' name='bt' type='radio' value='2' <?= $viData['bt'] == '2' ? 'checked' : '' ?>>
                            <span></span>
                        </label>
                        <br>
                        <label class='radio-box' for='RB3busy'>
                            Не важно
                            <input id='RB3busy' name='bt' type='radio' value='3' <?= $viData['bt'] == '3' || empty($viData['bt']) ? 'checked' : '' ?>>
                            <span></span>
                        </label>
                        <br>
                    </div>
                </div>
            </div>
            <div class='filter-label filter-salary'>
                <?php $flag = $viData['sphf'] || $viData['spht'] || $viData['spwf'] || $viData['spwt'] || $viData['spmf'] || $viData['spmt'] ?>
                <label class='filter-name <?= $flag ? 'opened' : '' ?>'>Заработная плата</label>
                <div class='filter-content <?= $flag ? 'opened' : '' ?>'>
                    <div class='radio-box right-box'>
                        <div class='salary-box'>
                            <label for='EdSalPerHF'>В час&nbsp;&nbsp;</label>
                            <i>от</i>
                            <input id='EdSalPerHF' name=sphf type='text' value="<?= $viData['sphf'] ?>">
                            <i>до</i>
                            <input id='EdSalPerHT' name='spht' type='text' value="<?= $viData['spht'] ?>">
                            <label class='radio-box' for='RBShour'>
                                <input <?= $viData['sr'] == '1' || empty($viData['sr']) ? 'checked' : '' ?> id='RBShour' name='sr' type='radio' value='1'>
                                <span></span>
                            </label>
                        </div>
                        <div class='salary-box'>
                            <label for='EdSalPerWF'>В неделю</label>
                            <i>от</i>
                            <input id='EdSalPerWF' name='spwf' type='text' value="<?= $viData['spwf'] ?>">
                            <i>до</i>
                            <input id='EdSalPerWT' name='spwt' type='text' value="<?= $viData['spwt'] ?>">
                            <label class='radio-box' for='RBSweek'>
                                <input id='RBSweek' name='sr' type='radio' value='2' <?= $viData['sr'] == '2' ? 'checked' : '' ?>>
                                <span></span>
                            </label>
                        </div>
                        <div class='salary-box'>
                            <label for='EdSalPerMF'>В месяц</label>
                            <i>от</i>
                            <input id='EdSalPerMF' name='spmf' type='text' value="<?= $viData['spmf'] ?>">
                            <i>до</i>
                            <input id='EdSalPerMT' name='spmt' type='text' value="<?= $viData['spmt'] ?>">
                            <label class='radio-box' for='RBSmonth'>
                                <input id='RBSmonth' name='sr' type='radio' value='3' <?= $viData['sr'] == '3' ? 'checked' : '' ?>>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class='filter-label filter-sex'>
                <label class='filter-name <?= $viData['sex'] == '1' || $viData['sex'] == '2' ? 'opened' : '' ?>'>Пол</label>
                <div class='filter-content <?= $viData['sex'] == '1' || $viData['sex'] == '2' ? 'opened' : '' ?>'>
                    <div class='radio-box right-box'>
                        <label class='radio-box' for='RB1sex'>
                            Мужской
                            <input id='RB1sex' name='sex' type='radio' value='1' <?= $viData['sex'] == '1' ? 'checked' : '' ?>>
                            <span></span>
                        </label>
                        <br>
                        <label class='radio-box' for='RB2sex'>
                            Женский
                            <input id='RB2sex' name='sex' type='radio' value='2' <?= $viData['sex'] == '2' ? 'checked' : '' ?>>
                            <span></span>
                        </label>
                        <br>
                        <label class='radio-box' for='RB3sex'>
                            Не важно
                            <input id='RB3sex' name='sex' type='radio' value='3' <?= $viData['sex'] == '3' || empty($viData['sex']) ? 'checked' : '' ?>>
                            <span></span>
                        </label>
                        <br>
                    </div>
                </div>
            </div>
            <div class='filter-label filter-age'>
                <label class='filter-name <?= $viData['af'] || $viData['at'] ? 'opened' : '' ?>'>Возраст</label>
                <div class='filter-content <?= $viData['af'] || $viData['at'] ? 'opened' : '' ?>'>
                    <div class='right-box'>
                        <label for='EdAgeF'>От</label>
                        <input id='EdAgeF' name='af' type='text' value="<?= $viData['af'] ?>">
                        <label for='EdAgeT'>До</label>
                        <input id='EdAgeT' name='at' type='text' value="<?= $viData['at'] ?>">
                    </div>
                </div>
            </div>
            <div class='btn-apply btn-white-green-wr'>
                <button type='submit'>Применить</button>
            </div>
        </div>
    </form>

    <div class='col-xs-12 col-sm-8 col-md-9'>
        <div class='view-radio clearfix'>
            Отобразить вакансии &nbsp;
            <a class='list <?= $G_LIST_VIEW ?>' href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', '') ?>' title='списком'>
                <b></b>
                <i></i>
            </a>
            <a class='table <?= $G_TAB_VIEW ?>' href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 't') ?>' title='таблицей'>
                <b></b>
                <i></i>
            </a>
        </div>
        <br />

        <?php if( !count($viData['vacs']) ): ?>
            <div class="novacs">Нет подходящих вакансий</div>
        <?php endif; ?>

<?php /* BM: list view */ ?>
        <?php if ($_GET['view'] != 't'): ?>
            <div class='list-view'>
                <?php foreach ($viData['vacs'] as $key => $val): ?>
                    <div class='vac-list-item-box <?= $val['ispremium'] ? 'premium' : '' ?>'>
                        <div class='border'>
                            <div class="_head" <?= $val['ispremium'] ? "title='Премиум вакансия'" : '' ?>>
                                <div class='iconp' title='Премиум вакансия'>*&nbsp;&nbsp;Премиум&nbsp;&nbsp;*</div>

                                <div class='vac-num'>
                                    <span title='дата публикации'><?= $val['crdate'] ?></span>
                                </div>
                                <div class="hh2">
                                    <a href="<?= MainConfig::$PAGE_VACANCY . DS . $val['id'] ?>" class="black-green">
                                        <?= $val['posts'] ? join(', ', $val['posts']) : '' ?>
                                        <?php if( $val['shour'] > 0 || $val['sweek'] > 0 || $val['smonth'] > 0 ): ?>
                                            <?php if( $val['shour'] > 0 ): ?>
                                                - <span class="payment"><i></i><?= $val['shour'] > 0 ? $val['shour'] . ' руб/час' : '' ?></span>
                                            <?php elseif( $val['sweek'] > 0 ): ?>
                                                - <span class="payment"><i></i><?= $val['sweek'] . ' руб/неделю' ?></span>
                                            <?php elseif( $val['smonth'] > 0 ): ?>
                                                - <span class="payment"><i></i><?= $val['smonth'] . ' руб/мес' ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                            <div class="_body">
                                <div class="roww">
                                    <div class="hh3"><?= $val['title'] ?></div>
                                    <div class='company-logo-wrapp'>
                                        <div class='company-logo'>
                                            <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['uid'] ?>"><img alt='Работодатель <?= $val['coname'] ?> prommu.com'
                                                 src='<?= DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$val['logo'] ?  'logo.png' : ($val['logo']) . '400.jpg') ?>'></a>
                                            <br>
                                            <b class='name2'><?= $val['coname'] ?></b>
                                            <?php /* <br>
                                                                                <i class='compname'>U-Company</i> */ ?>
                                        </div>
                                    </div>
                                    <table class="info">
                                        <?php if( $val['isman'] || $val['iswoman'] ): ?>
                                            <tr class="sex">
                                                <td>Пол:</td>
                                                <td>
                                                    <?= $val['isman'] ? 'Мужчины' : '' ?><?= $val['isman'] && $val['iswoman'] ? ', ' : '' ?>
                                                    <?= $val['iswoman'] ? 'Женщины' : '' ?>
                                                    <?= $val['isman'] ? '<i></i>' : "" ?>
                                                    <?= $val['iswoman'] ? '<b></b>' : '' ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if( $val['city'] ): ?>
                                            <tr>
                                                <td>Город:</td>
                                                <td> <?= join(', ', $val['city']) ?> </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if( $val['metro'] ): ?>
                                            <tr>
                                                <td>Метро:</td>
                                                <td> <?= join(', ', $val['metro']) ?> </td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td>Краткое описание вакансии:</td>
                                            <td><?= $val['duties'] ?></td>
                                        </tr>
                                        <tr class="busy">
                                            <td>Вид занятости:</td>
                                            <td><?= $val['istemp'] ? 'Постоянная' : 'Временная' ?><i></i></td>
                                        </tr>
                                        <tr>
                                            <td>Открыта по:</td>
                                            <td><?= $val['remdate'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <br />


                                <div class='row'>
                                    <div class='col-xs-12'>
                                        <div class='btn-go-vacancy-02 btn-white-green-wr'>
                                            <a href='/site/vacancy/<?= $val['id'] ?>'>Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                                <b style='display:none'>Премиум</b>
                            </div>
                        </div>
                    </div>
                <?php endforeach ; ?>
            </div>
            <script type="text/javascript">
            <!--
                var $G_PAGE_VIEW = 2;
                var G_DEF_LOGO = '<?= MainConfig::$DEF_LOGO_EMPL ?>';
            //-->
            </script>
                    <div class='vac-list-item-box list-view-tpl'>
                        <div class='border'>
                            <div class='iconp' title='Премиум вакансия'>*&nbsp;&nbsp;Премиум&nbsp;&nbsp;*</div>
                            <div class='row vac-header'>
                                <div class='col-xs-12'>
                                    <div class='vac-num'>
                                        № <span class="num"></span><br><span title='дата публикации' class="crdate"><!--crdate--></span>
                                    </div>
                                    <h2><!--posts--></h2>
                                    <small class="title"><!--title--></small>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-xs-12 col-sm-3'>
                                    <div class='company-logo-wrapp'>
                                        <div class='company-logo'>
                                            <img alt='' src='<?= DS . MainConfig::$PATH_EMPL_LOGO . DS ?>'>
                                            <br>
                                            <br>
                                            <b class='name'><!--coname--></b>
                                            <?php /* <br>
                                            <i class='compname'>U-Company</i> */ ?>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-xs-12 col-sm-9'>
                                    <table>
                                        <tr class="sex">
                                            <td>Пол:</td>
                                            <td class="sexval"></td>
                                        </tr>
                                        <tr class="payment">
                                            <td>Стоимость работы:</td>
                                            <td class="paymentval"></td>
                                        </tr>
                                        <tr>
                                            <td>Город:</td>
                                            <td class="city"><!--city--></td>
                                        </tr>
                                        <tr class="metro">
                                            <td>Метро:</td>
                                            <td class="metroval"><!--metro--></td>
                                        </tr>
                                        <tr>
                                            <td>Краткое описание вакансии:</td>
                                            <td class="duties"><!--duties--></td>
                                        </tr>
                                        <tr>
                                            <td>Вид занятости:</td>
                                            <td class="istemp"></td>
                                        </tr>
                                        <tr>
                                            <td>Период активности:</td>
                                            <td>С <span class="bdate"></span> <span class="edate">по <span></span></span></td>
                                        </tr>
                                    </table>
                                    <div class='row'>
                                        <div class='col-xs-12 col-sm-6'>
                                            <div class='btn-go-vacancy-02 btn-white-green-wr'>
                                                <a href='/site/vacancy/<?= $val['id'] ?>'>Подробнее</a>
                                            </div>
                                        </div>
                                        <div class='col-xs-12 col-sm-6'>
                                            <div class='btn-reply btn-orange-sm-wr'>
                                                <a class='hvr-sweep-to-right btn__orange' href='/site/vacancy/<?= $val['id'] ?>'>Откликнуться <?= $type ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <b style='display:none'>Премиум</b>
                            </div>
                        </div>
                    </div>

        <?php else: ?>
<?php /* // BM: table view */ ?>
            <div class='row vacancy table-view'>
                <?php
                    $i = 1;
                    foreach ($viData['vacs'] as $key => $val): ?>
                    <div class='col-xs-12 col-sm-6 col-md-4 <?= $val['ispremium'] ? 'premium' : 'normal-o' ?>'>
                        <div class='border'>
                            <div class='iconp js-g-hashint' title='Премиум вакансия'>*&nbsp;&nbsp;Премиум&nbsp;&nbsp;*</div>
                            <h3>
                                <a href='/site/vacancy/<?= $val['id'] ?>'>
                                    <?= join(', ', $val['posts']) ?>
                                    <?php if( $val['shour'] > 0 || $val['sweek'] > 0 || $val['smonth'] > 0 ): ?>
                                        <?php if( $val['shour'] > 0 ): ?>
                                            - <span class="nowrap"><?= $val['shour'] > 0 ? $val['shour'] . ' руб/час' : '' ?></span>
                                        <?php elseif( $val['sweek'] > 0 ): ?>
                                            - <span class="nowrap"><?= $val['sweek'] . ' руб/нед' ?></span>
                                        <?php elseif( $val['smonth'] > 0 ): ?>
                                            - <span class="nowrap"><?= $val['smonth'] . ' руб/мес' ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </a>
                            </h3>

                            <?php $flag = 0; if( $val['isman'] === '0' && $val['iswoman'] === '0' ) $flag = 1; ?>
                            <?php if( $val['isman'] == '1' || $val['iswoman'] == '1' || $flag ): ?>
                                <div class="sex-block">
                                    <?php if( $flag || $val['isman'] === '1' ): ?>
                                        <div class="ico ico-man js-g-hashint" title="Мужчины"></div>
                                    <?php endif; ?>
                                    <?php if( $flag || $val['iswoman'] === '1' ): ?>
                                        <div class="ico ico-woman js-g-hashint" title="Женщины"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if( $val['city'] ): ?>
                                <b>Город:</b> <?= join(', ', $val['city']) ?>
                                <br>
                            <?php endif; ?>
                            <b>Вид работы:</b> <?= $val['istemp'] ? 'Постоянная' : 'Временная' ?>
                            <br>
                            <b>Период:</b> с <?= $val['crdate'] ?>
                            <?php if( $val['remdate'] ): ?>
                                по <?= $val['remdate'] ?>
                            <?php endif; ?>
                            <div class='hr'></div>
                            <span class="img"><img src="<?= DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$val['logo'] ?  'logo.png' : ($val['logo']) . '100.jpg') ?>" alt='Работодатель <?= $val['coname'] ?> prommu.com'></span>
                            <span class='company'><?= $val['coname'] ?></span>
                            <span class='date'>от <?= $val['crdate'] ?></span>
                            <div class='clearfix'></div>
                            <div class='go-vacancy btn-white-green-wr'>
                                <a href='/site/vacancy/<?= $val['id'] ?>'>Просмотреть</a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <?php if( $i % 2 == 0 ): ?>
                        <div class="clear visible-sm"></div>
                    <?php endif; ?>
                    <?php if( $i % 3 == 0 ): ?>
                        <div class="clear visible-md visible-lg"></div>
                    <?php endif; ?>
                <?php
                    $i++;
                    endforeach; ?>

            </div>
            <script type="text/javascript">
            <!--
                var $G_PAGE_VIEW = 1;
            //-->
            </script>
                <?php /*
                <div class='col-xs-12 col-sm-6 col-md-4 tab-view-tpl'>
                    <div class='border'>
                        <div class='iconp' title='Премиум вакансия'>*&nbsp;&nbsp;Премиум&nbsp;&nbsp;*</div>
                        <h3>
                            <a href='/site/vacancy/'><!--title--></a>
                        </h3>
                        <div class="city">Город: </div>
                        Вид работы: <span class="istemp"></span>
                        <br>
                        <div class="payment">Оплата: </div>
                        Период: с <span class="bdate"><!--bdate--></span> <span class="edate">по <span><!--edate--></span></span>
                        <div class='hr'></div>
                        <span class='date'>от <?= $val['crdate'] ?></span>
                        <span class='company'></span>
                        <div class='clearfix'></div>
                        <?php if( Share::$UserProfile->exInfo->status > 1 ): ?>
                            <div class='go-vacancy btn-white-green-sm-wr'>
                                <a href='?p=vacancy-view'>Откликнуться</a>
                            </div>
                        <?php endif; ?>
                        <br>
                    </div>
                </div>
                */ ?>

        <?php endif; ?>
        <br />
        <br />
        <div class='paging-wrapp hidden-xs'>
          <?php
            // display pagination
            $this->widget('CLinkPager', array(
              'pages' => $pages,
              'htmlOptions' => array('class' => 'paging-wrapp'),
              'firstPageLabel' => '1',
              'prevPageLabel' => 'Назад',
              'nextPageLabel' => 'Вперед',
              'header' => '',
          )) ?>
        </div>

    </div>
</div>

