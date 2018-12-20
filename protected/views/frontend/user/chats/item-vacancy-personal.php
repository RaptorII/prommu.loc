<?
	$sectionList = MainConfig::$PAGE_CHATS_LIST_VACANCIES;
	$this->setBreadcrumbsEx(array($viData['vacancy']['title'], $sectionList . DS . $vacancy . DS . $id));
	$this->setPageTitle($viData['vacancy']['title']);
  $isNew = $viData['items']['new'];
  $type = Share::$UserProfile->type;
	Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'chats/item.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/magnific-popup-min.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/nicEdit.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'chats/item-' . ($type==3?'emp.js':'app.js'), CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/jquery.magnific-popup.min.js', CClientScript::POS_END);
?>
<div class="message"><? echo ($viData['error'] ? $viData['message'] : '')?></div>
<div class="chat-item">
	<? if(!$viData['error']): ?>
		<a href="<?=$sectionList?>" class="chat-item__btn-link"><span><</span> Назад</a>
      <?php if( $isNew ): ?>
        <div class="theme-input">
            <?php if( $viData['themes'] ): ?>
              <label id="LCBtheme">
                <b>Выберите тему диалога из заявок этого пользователя на ваши вакансии</b>
                <input name="theme" id="CBTheme" value="<?=$viData['vacancy']['id']?>"\>
              </label>
              <br />
              <br />
              или
              <br />
              <br />
            <?php endif; ?>
              <label id="LEdTheme">
                <b>Введите новую тему</b>
                <input type="text" name="theme-cust" id="EdTheme" value="<?=$viData['vacancy']['title']?>"/>
              </label>
              <br />
              <br />
        </div>
      <?php endif; ?>
      <input type="hidden" name="theme" id="CBTheme" value="<?=$viData['vacancy']['id']?>"\>

        <div class="chat-item__title">
            <h2>
                <?=$viData['vacancy']['title']?>
            </h2>
        </div>


        <div id="DiChatWrapp">
            <div class="mess-block">
                <div class="mess-block-inner">

                    <?if($isNew): ?>
                        <div id="DiMessagesWrapp">
                    <?else:?>
                        <div id="DiMessagesWrapp">
                    <?endif;?>

                    <?php if( $isNew ): ?>
                      <div class="nomess">Сообщений нет</div>
                    <?php endif; ?>
                    <div id="DiMessagesInner">
                      <div id="DiMessages"></div>
                    </div>
                  </div>
                </div>
            </div>




          <div class='message-box'>
              <form enctype='multipart/form-data'>
                  <div class='message'>
                      <textarea name='sendmess' id="Mmessage"></textarea>
                      <div class='go clearfix'>
                          <div class="btn-white-green-wr">
                              <button type='button'>Отправить</button>
                          </div>
                          <div id="DiButtonPanel">
                              <div class="add-panel">
                                  <div class="divider"></div>
                                  <a href="#" class="js-attach-file attach-file black-green -icon-before-16"><span>прикрепить файл</span></a>
                              </div>
                          </div>
                      </div>
                  </div>
              </form>

              <form method="post" enctype="multipart/form-data" id="F3uploaded" <?= !count($viData['files']) ?: 'style="display: block"' ?>>
                <div class="message -red"></div>
                <h3>Прикрепленные файлы</h3>
                  <div id="DiImgs">
                    <? foreach ($viData['files'] ?: array() as $key => $val): ?>
                      <? if( $val['meta']['type'] == 'images' ): ?>
                        <div class="attached-image uni-img-block">
                          <span class="uni-delete js-g-hashint" data-id="<?= $key ?>" title="удалить изображение"></span>
                          <a href="<?= $val['files']['orig'].",{$val['extmeta']->idTheme}" ?>" class="uni-img-link" target="_blank">
                            <img src="<?= $val['files']['tb'].",{$val['extmeta']->idTheme}" ?>" alt="" class="uni-img">
                          </a>
                        </div>
                      <? endif; ?>
                    <? endforeach; ?>
                  </div>
                  <div id="DiFiles">
                    <? foreach ($viData['files'] ?: array() as $key => $val): ?>
                      <? if( $val['meta']['type'] == 'files' ): ?>
                        <div class="attached-file <?= $val['meta']['ext'] ?> uni-img-block">
                          <span class="uni-delete file js-g-hashint" data-id="<?= $key ?>" title="удалить файл"></span>
                          <a href="<?= $val['files']['orig'].",{$val['extmeta']->idTheme}" ?>" class="uni-link" target="_blank">
                            <?= $val['meta']['name'] ?>
                          </a>
                        </div>
                      <? endif; ?>
                    <? endforeach; ?>
                  </div>
                <div class="clear"></div>
              </form>
          </div>

          <div class="mess-box-end"></div>
        </div>
      <script type="text/javascript">
      <!--
          G_VARS.idTm = '<?=$viData['theme']?>';
          <?php if( $isNew ): ?>
            G_VARS.isNew = '<?= $isNew ?>';
          <?php endif; ?>
          G_VARS.uniFiles = <?= json_encode($viData['files']) ?>;
          //-->
      </script>
    <? require_once 'files-upload-blocks.php'; // форма загрузки фото и вывод файлов из сессии, верстка для новых сообщений ?>
	<?php endif; ?>
</div>