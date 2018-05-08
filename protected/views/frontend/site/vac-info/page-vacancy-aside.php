<?php 
    //
    // Установка метаданных и заголовка
    //
    $vacancies = strtolower(join(', ', $viData['vac']['post'])); // вакансия(и)
    $city = ' в ' . current($viData['vac']['city'])[0] . '(е) '; // город для заголовка
    $employ = $viData['vac']['istemp'] ? 'Постоянная' : 'Временная';// вид занятости
    if( $viData['vac']['shour'] > 0 ) $wage = $viData['vac']['shour'] . ' руб/час' ;
    elseif( $viData['vac']['sweek'] > 0 ) $wage = $viData['vac']['sweek'] . ' руб/неделю' ;
    elseif( $viData['vac']['smonth'] > 0 ) $wage = $viData['vac']['smonth'] . ' руб/мес' ;
    else $wage = 'по договоренности';   // зп
    $sex = ($viData['vac']['isman'] ? 'юноши' : '')
        . ($viData['vac']['isman'] && $viData['vac']['iswoman'] ? ', ' : '')
        . ($viData['vac']['iswoman'] ? 'девушки' : ''); // пол
    $years = '';
    if($viData['vac']['agefrom'] || $viData['vac']['ageto']){
        $years = ($viData['vac']['agefrom'] ? 'от ' . $viData['vac']['agefrom'] : '')
            . ($viData['vac']['ageto'] ? ' до ' . $viData['vac']['ageto'] : '') 
            . 'лет';    // возраст
    }
    $strBreadcrumb = 'Вакансия - ' . $vacancies . ' - оплата ' . $wage;
    //
    // SET META, TITLE, BREADCRUMBS
    //
    $title = $this->pageTitle = "Отложенные заявки";
    $this->setBreadcrumbsEx(array($strBreadcrumb, $_SERVER['REDIRECT_URL']));
    $this->setBreadcrumbsEx(array($title, $_SERVER['REQUEST_URI']));
?>
<h2><?=$title?></h2>
<div class="tabs-panel">
	<?php $tab = Yii::app()->getRequest()->getParam('info'); ?>
    <div class="content">
        <?php if( $tab == 'dialog' ): ?>
            <div class="message">
                <?php if( $mess = Yii::app()->user->getFlash('data') ): Yii::app()->user->setFlash('data', null) ?>
                    <div class="mess-box <?= $mess['error'] ? 'error' : '' ?> -center"><?= $mess['message'] ?></div>
                <?php endif; ?>
            </div>
            <div class="send-mess-block">
                <b>Оставить сообщение:</b>
                <form method="post">
                    <textarea name="mess"></textarea>
                    <input type="hidden" name="id" value="<?= $id ?>" />
                    <div class="btn-white-green-wr">
                        <button type="submit">Отправить</button>
                    </div>
                </form>
            </div>
            <div class="discuss-block">
                <?php foreach ($viData['vacResponses']['discuss'] as $key => $val): ?>
                    <?php if( $val['name'] ): ?>
                        <div class="message-wrapp empl">
                            <div class="fio"><?= $val['name'] ?></div>
                            <div class="date"><?= $val['crdate'] ?></div>
                            <div class="message"><?= $val['mess'] ?></div>
                        </div>
                    <?php else: ?>
                        <div class="message-wrapp promo">
                            <div class="fio"><?= $val['firstname'] . ' ' . $val['lastname'] ?></div>
                            <div class="date"><?= $val['crdate'] ?></div>
                            <div class="message"><?= $val['mess'] ?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php
              // display pagination
              $this->widget('CLinkPager', array(
                'pages' => $viData['vacResponses']['pages'],
                'htmlOptions' => array('class' => 'paging-wrapp'),
                'firstPageLabel' => '1',
                'prevPageLabel' => 'Назад',
                'nextPageLabel' => 'Вперед',
                'header' => '',
            )) ?>
        <?php else:
            if( $tab == 'resp' )
                $respInd = 4;
            elseif( $tab == 'refuse' )
                $respInd = 3;
            elseif( $tab == 'aside' )
                $respInd = 1;
        ?>
            <?php if( $viData['vacResponses']['counts'][$respInd] ): ?>
                <table class="vacs">
                    <?php foreach ($viData['vacResponses']['responses'][$respInd] as $key => $val): ?>
                        <tr class="r1 <?= $val['status'] == '0' ? '-new' : '' ?>">
                          <td class='fio'>
                            <a class='black-orange' href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['idusr'] ?>' title="номер заявки: <?= $val['sid'] ?>"><?= $val['firstname'] ?> <?= $val['lastname'] ?></a>
                            <span class='rdate' title="Дата заявки">( <?= $val['rdate'] ?> )</span>
                          </td>
                          <td>
                            <div class="controls" data-sid="<?= $val['sid'] ?>">
                              <?php if( in_array($val['status'], [0, 3]) ): ?>
                                <div class="btn-white-green-sm-02"><a href="#" class="view" title="Отметить как отложенная">Отложить</a></div>
                              <?php endif; ?>
                              <?php if( in_array($val['status'], [6,7]) && !$val['id_vac'] ): ?>
                                <div class="btn-white-green-sm-02"><a href="<?= MainConfig::$PAGE_SETRATE . DS . $val['id'] . DS . $val['idusr'] ?>" class="comment">Оставить отзыв</a></div>&nbsp;&nbsp;
                              <?php endif; ?>
                              <?php if( in_array($val['status'], [0,1,3]) ): ?>
                                <div class="btn-white-green-sm-02"><a href="#" class="apply" title="Подтвердить заявку на вакансию">Утвердить</a></div>
                              <?php endif; ?>
                              <?php if( in_array($val['status'], [0,1]) ): ?>
                                <div class="btn-white-green-sm-02"><a href="#" class="cancel" title="Отклонить заявку на вакансию">Отклонить</a></div>
                              <?php endif; ?>
                              <?php if( $val['status'] != '4' && $val['status'] != 5 ): ?>
                                <span class="status hide hint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена, ожидайте ответа</span>
                              <?php endif; ?>
                              <?php if( $val['isresponse'] == 1 && in_array($val['status'], [4]) ): ?>
                                <div class="status hint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена, ожидайте ответа</div>
                              <?php elseif( $val['isresponse'] == 2 && in_array($val['status'], [2,4]) ): ?>
                                <div class="status hint js-hashint" title="Вы отправили заяв">Приглашение на вакансию отправлено, ожидайте ответа</div>
                              <?php endif; ?>
                              <?php if( $val['isresponse'] == 1 && in_array($val['status'], [5,6,7]) ): ?>
                                <div class="status">Заявка на вакансию подтверждена</div>
                              <?php elseif( $val['isresponse'] == 2 && in_array($val['status'], [5,6,7]) ): ?>
                                <div class="status">Приглашение на вакансию принято</div>
                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br />

                <?php
                  // display pagination
                  $this->widget('CLinkPager', array(
                    'pages' => $viData['vacResponses']['pages'],
                    'htmlOptions' => array('class' => 'paging-wrapp'),
                    'firstPageLabel' => '1',
                    'prevPageLabel' => 'Назад',
                    'nextPageLabel' => 'Вперед',
                    'header' => '',
                )) ?>

            <?php else: ?>
                <p>Нет заявок</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>