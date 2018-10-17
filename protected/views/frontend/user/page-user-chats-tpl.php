<?php
Yii::app()->getClientScript()->registerCssFile('/theme/css/private/chats.css');
Yii::app()->getClientScript()->registerScriptFile("/theme/js/private/chats.js", CClientScript::POS_END);
?>
    <div class='row'>
        <div class='col-xs-12'>
            <?php if (!$viData['count']): ?>
                <?php if (Share::$UserProfile->type == 2): // соискатель ?>
                    <div class="without-chats">У Вас еще нет диалога ни с одним из Работодателей.<br> Для начала общения
                        попробуйте отозваться на понравившуюся вакансию
                    </div>
                <?php else: ?>
                    <div class="without-chats">У Вас еще нет диалога ни с одним из Соискателей.<br> Для начала общения
                        создайте вакансию, найдите в списке анкет соискателей подходящего кандидата и напишите ему
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class='header-021'>
                    <b>Мои диалоги</b>
                    <b class='-green'><?= $viData['count'] ?></b>
                </div>
                <?php foreach ($viData['chats'] as $key => $val): ?>
                    <a class="chat__list-item" href='<?= MainConfig::$PAGE_IM . DS . $val['id'] ?>'>
                        <div class='chat-list-block clearfix'>
                            <table class='stats'>
                                <tr class='hidden-xs'>
                                    <td class='hidden-xs'>
                                        Кол-во сообщений
                                    </td>
                                    <td class='hidden-xs'>
                                        Дата первого сообщения
                                    </td>
                                    <td>
                                        Дата последнего сообщения
                                    </td>
                                </tr>
                                <tr>
                                    <td class='center hidden-xs'>
                                        <?= $val['count'] ?>
                                        <?php if ($val['countn']): ?>
                                            <b class="js-g-hashint" title="Новые сообещния">(<?= $val['countn'] ?>)</b>
                                        <?php endif; ?>
                                    </td>
                                    <td class='center hidden-xs'><?= $val['crfdate'] ?></td>
                                    <td class='center'><?= $val['crldate'] ?></td>
                                </tr>
                            </table>
                            <div class='img-block'>
                                <div class='img' <? /*href='<?= MainConfig::$PAGE_IM . DS . $val['id'] ?>'*/ ?>>
                                    <?php if (Share::$UserProfile->type == 2): ?>
                                        <img src='<?= DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$val['logo'] ? MainConfig::$DEF_LOGO_EMPL : ($val['logo']) . '100.jpg'); ?>'>
                                    <?php else: ?>
                                        <img src='<?= DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$val['logo'] ? MainConfig::$DEF_LOGO : ($val['logo']) . '100.jpg'); ?>'>
                                    <?php endif; ?>
                                </div>
                                <div class='title'>
                                    <? /*<a href='<?= MainConfig::$PAGE_IM . DS . $val['id'] ?>'>*/ ?>
                                    <?= $val['title'] ?: $val['etitle'] ?>
                                    <? /*</a>*/ ?>
                                </div>
                                <div class='fio'>
                                    <?php if (Share::$UserProfile->type == 2): ?>
                                        <?= $val['name'] ?> (
                                        <span class='login'><?= $val['nnn'] . ' ' . $val['fff'] ?> )</span>
                                    <?php else: ?>
                                        <?= $val['nnn'] . ' ' . $val['fff'] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>

                <br/>
                <br/>

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
            <?php endif; ?>
        </div>
    </div>
    </div>
<?php
$mess = Yii::app()->user->getFlash('Message');
if ($mess):
    Yii::app()->user->setFlash('Message', '');
    ?>
    <form class="complete-popup tmpl"><?= $mess['mess'] ?></form>
<?php endif; ?>