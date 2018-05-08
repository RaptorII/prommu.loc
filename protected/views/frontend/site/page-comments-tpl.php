
<?php if( $viData['error'] ): ?>
  <div class="comm-mess-box"><?= $viData['message'] ?></div>
<?php else: ?>
  <div class='row'>
    <div class='col-xs-12 col-sm-4 col-lg-3 no-md-relat'>
      <?php $G_NOLIKES = 1;
            $G_LOGO_LINK = MainConfig::$PAGE_PROFILE_COMMON . DS . $viData['profile']['data']['idus'];
            $G_LOGO_SRC = $viData['profile']['data']['avatar'];
            $G_COMP_FIO = $viData['profile']['data']['fio'];
            $G_RATE_POS = $viData['profile']['rate'][0];
            $G_RATE_NEG = abs($viData['profile']['rate'][1]);
            $G_COMMENTS_POS = $viData['profile']['commcount'][0];
            $G_COMMENTS_NEG = $viData['profile']['commcount'][1];
//            $G_PROFILE_VIEWS = $viData['userInfo']['viewCount'];

            include DOCROOT . '/protected/views/frontend/user/' . MainConfig::$VIEWS_COMM_LOGO_TPL . ".php"; ?>
        <?php /*
        <div class='btn-comment btn-white-green-wr'>
          <a href='#'>Оставить отзыв</a>
        </div>
        <div class='btn-update btn-orange-sm-wr'>
          <a class='hvr-sweep-to-right' href='#'>Написать сообщение</a>
        </div>
        */ ?>
    </div>
    <div class='col-xs-12 col-sm-8 col-lg-9'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='header-021 comments'>
            ОТЗЫВЫ
            <span class='-green'> <?= $viData['profile']['commcount'][0] ?> | </span> <?= $viData['profile']['commcount'][1] ?>
          </div>
        </div>
      </div>

        <div class='filter'>
          <a class="all <?= (int)$activeFilterLink == 0 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', '') ?>'>Все</a>
          <a class="pos <?= (int)$activeFilterLink == 1 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'p') ?>'>Положительные</a>
          <a class="neg <?= (int)$activeFilterLink == 2 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'n') ?>'>Отрицательные</a>
        </div>


      <?php foreach ($viData['comments'] as $key => $val): ?>
          <?php /*$debug && ($debug++); !$debug && $debug = 1;  */?>
        <div class='comment-box <?= $val['processed'] ? '' : '-new' ?> <?= $val['isneg'] ? '-neg' : '-pos' ?>'>
          <div class="inner">
            <?php if( !$val['processed'] ): ?>
              <div class="new-labl">Новый</div>
            <?php endif; ?>
            <div class="logo">
              <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>">
                <?php if( $profType == 2 ): ?>
                  <img src="<?= $this->ViewModel->getHtmlLogo($val['logo'], ViewModel::$LOGO_TYPE_EMPL) ?>" alt="">
                <?php else: ?>
                  <img src="<?= $this->ViewModel->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC) ?>" alt="">
                <?php endif; ?>
              </a>
            </div>
            <div class="user">
              <div class='name'><span><?= $val['fio'] ?></span><?= $val['crdate'] ?></div>
              <div class='text-wrapp'>
                <div class='text'><?= $val['message'] ?></div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <div class='row'>
        <div class='col-xs-12'>
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
  </div>


  <template id='TPLAddComment'>
    <div class='add-comment-block' id='DiAddComment'>
      <form id='FaddComment' method='post'>
        <label for='EdName'>
          <b>Имя</b>
          <br>
          <input data-field-check='name:Имя,empty,max:150' id='EdName' name='name' type='text'>
        </label>
        <br>
        <label>
          <div class='radio-box'>
            <label class='radio-box checked' for='RB1posi' id='LPosi'>
              <b>Положительный</b>
              <input checked id='RB1posi' name='type' type='radio' value='1'>
              <span></span>
            </label>
            <label class='radio-box' for='RB2neg' id='LNeg'>
              <b>Отрицательный</b>
              <input id='RB2neg' name='type' type='radio' value='2'>
              <span></span>
            </label>
          </div>
        </label>
        <br>
        <div class='memo-with-counter'>
          <label>
            Отзыв
            <b class='memo-counter'></b>
            <br>
            <textarea data-counter='2000' data-field-check='name:Отзыв,empty,max:2000' id='Maboutmself' name='comment' placeholder='Текст до 2000 знаков'></textarea>
          </label>
        </div>
        <br>
        <div id='BtnAddComment'>
          <button class='btn-white-green' type='submit'>Оставить отзыв</button>
        </div>
      </form>
    </div>
  </template>
<?php endif; ?>
