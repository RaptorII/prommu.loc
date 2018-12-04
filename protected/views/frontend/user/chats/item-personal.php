<?
echo "<pre>";
print_r($viData); 
echo "</pre>";
	$sectionList = MainConfig::$PAGE_CHATS_LIST_VACANCIES;
	$this->setBreadcrumbsEx(array($viData['vacancy']['title'], $sectionList . DS . $vacancy . DS . $id));
	$this->setPageTitle($viData['vacancy']['title']);
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/item.css');
	Yii::app()->getClientScript()->registerScriptFile('/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/chats/item-emp.js', CClientScript::POS_END);
?>
<div class="message"><? echo ($viData['error'] ? $viData['message'] : '')?></div>
<?php if( !$viData['error'] ): ?>
  <br />
  <a href="<?= MainConfig::$PAGE_IM ?>">&lt; Назад к диалогам</a>
  <br />
  <br />
  <div class='row'>
    <div class='col-xs-12'>
      <?php if( $isNew ): ?>
        <div class="theme-input">
            <?php if( $viData['themes'] ): ?>
              <label id="LCBtheme">
                <b>Выберите тему диалога из заявок этого пользователя на ваши вакансии</b>
                <select name="theme" id="CBTheme">
                  <option value="aa">- Выберите тему -</option>
                  <?php foreach ($viData['themes'] as $key => $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['title'] ?></option>
                  <?php endforeach; ?>
                </select>
              </label>
              <br />
              <br />
              или
              <br />
              <br />
            <?php endif; ?>
              <label id="LEdTheme">
                <b>Введите новую тему</b>
                <input type="text" name="theme-cust" id="EdTheme"/>
              </label>
              <br />
              <br />
        </div>
      <?php endif; ?>

      <div class='header-021'>
        <b>
          <?php if( !$isNew ): ?>
              <?= $viData['title']['title'] ?: $viData['title']['vactitle'] ?>
          <?php endif; ?>
        </b>
      </div>
      <br>

        <div id="DiChatWrapp">
            <div class="mess-block">
                <div class="mess-block-inner">
                  <div id="DiMessagesWrapp" style="border:1px solid transparent;">
                    <?php if( $isNew ): ?>
                      <div class="nomess">Нет сообщений в этой теме. Выберите тему и введите сообщение...</div>
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
                      <?php foreach ($viData['files'] ?: array() as $key => $val): ?>
                          <?php if( $val['meta']['type'] == 'images' ): ?>
                              <div class="attached-image uni-img-block">
                                  <span class="uni-delete js-g-hashint" data-id="<?= $key ?>" title="удалить изображение"></span>
                                  <a href="<?= $val['files']['orig'].",{$val['extmeta']->idTheme}" ?>" class="uni-img-link" target="_blank">
                                      <img src="<?= $val['files']['tb'].",{$val['extmeta']->idTheme}" ?>" alt="" class="uni-img">
                                  </a>
                              </div>
                          <?php endif; ?>
                      <?php endforeach; ?>
                  </div>
                  <div id="DiFiles">
                      <?php foreach ($viData['files'] ?: array() as $key => $val): ?>
                          <?php if( $val['meta']['type'] == 'files' ): ?>
                              <div class="attached-file <?= $val['meta']['ext'] ?> uni-img-block">
                                  <span class="uni-delete js-g-hashint" data-id="<?= $key ?>" title="удалить файл"></span>
                                  <a href="<?= $val['files']['orig'].",{$val['extmeta']->idTheme}" ?>" class="uni-link" target="_blank">
                                      <?= $val['meta']['name'] ?>
                                  </a>
                              </div>
                          <?php endif; ?>
                      <?php endforeach; ?>
                  </div>
                  <div class="clear"></div>
              </form>
          </div>

          <div class="mess-box-end"></div>
        </div>


      <div class="prev-mess-tpl tmpl">
        <a href='#prev-mess' class="green-orange">показать предыдущие сообщения</a>
      </div>

      <div class="new-mess-tpl tmpl"><div><b>Новые сообщения</b></div></div>

      <div class='mess-box mess-from tmpl'>
        <b class='fio'><!-- fio --></b>
        <span class='date'><!-- date --></span>
        <div class='mess'><!-- mess --></div>
        <div class='files'>
            <div class="js-container"></div>
            <a href="" class="black-orange"><img src="" alt=""></a>
        </div>
      </div>
      <div class='mess-box mess-to tmpl'>
        <div class='right'>
          <b class='fio'><!-- fio --></b>
          <span class='date'><!-- date --></span>
        </div>
        <div class='mess'><!-- mess --></div>
            <div class='files'>
                <div class="js-container"></div>
                <a href="" class="black-orange"><img src="" alt=""></a>
            </div>
      </div>

      <script type="text/javascript">
      <!--
          G_VARS.idTm = '<?= $idTm ?>';
          <?php if( $isNew ): ?>
            G_VARS.isNew = '<?= $isNew ?>';
          <?php endif; ?>
      //-->
      </script>
              <script type="text/javascript">
                  <!--
                  G_VARS.uniFiles = <?= json_encode($viData['files']) ?>;
                  //-->
              </script>



        <script type="text/template" id="TmplF2upload">
          <form method="post" enctype="multipart/form-data" id="F2upload">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
            <h2>Добавить файл к сообщению</h2>
            <input type="file" name="img" id="UplImg">
            <div class="message -red"></div>
            <div class="btn-white-green-wr btn-upload">
                <button type="button">Выбрать и загрузить</button>
                <div class="loading-block"><span class="loading-ico"><img src="/theme/pic/loading2.gif" alt=""></span></div>
              </div>
            <p>Файл загружаемый на сайт не должен превышать размер 5 Мб, максимальный размер изображения 2500х2500 пикселей.<br />Типы файла для загрузки: JPG, PNG, DOC, XLS</p>
          </form>
        </script>

          <div class="attached-image attached-image-tpl tmpl uni-img-block">
              <span class="uni-delete js-hashint" title="удалить файл"></span>
              <a href="" class="uni-img-link" target="_blank">
                  <img src="" alt="" class="uni-img">
              </a>
          </div>
          <div class="attached-file attached-file-tpl tmpl uni-img-block">
              <span class="uni-delete js-hashint" title="удалить изображение"></span>
              <a href="" class="uni-link" target="_blank"></a>
      </div>
    </div>
  </div>
<?php endif; ?>