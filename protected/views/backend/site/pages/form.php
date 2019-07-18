<?php
$bUrl = Yii::app()->request->baseUrl;
Yii::app()->getClientScript()
  ->registerCssFile($bUrl . '/css/template.css');
Yii::app()->getClientScript()
  ->registerScriptFile($bUrl . '/js/ckeditor/ckeditor.js', CClientScript::POS_HEAD);
$smallImgWidth = 350; // ширина маленького изображения для статей
if($pagetype=='news')
{
	$title = 'Редактирование новости ' . $id;
	$this->setPageTitle($title);
	$this->breadcrumbs = array(
		'Дополнительно'=>array('sect?p=add'),
		'Управление новостями'=>array('newspages'),
		'1'=>$title
	); 
    echo '<h3><i>'.$title.'</i></h3>';
  $filePath = Settings::getFilesRoot() . 'news/';
  $fileUrl = Settings::getFilesUrl() . 'news/';
} 
elseif($pagetype=='articles')
{
	$title = 'Редактирование статьи '.$id;
	$this->setPageTitle($title);
	$this->breadcrumbs = array(
		'СЕО'=>array('sect?p=seo'),
		'Управление статьями'=>array('articlespages'),
		'1'=>$title
	); 
	echo "<h3>$title</h3>";
  $filePath = Settings::getFilesRoot() . 'articles/';
  $fileUrl = Settings::getFilesUrl() . 'articles/';
} 
elseif($pagetype=='about')
{
	$this->setPageTitle('Редактирование страницы "О нас"');
	$this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>'О нас'); 
	echo '<h3><i>Редактирование страницы "О нас"</i></h3>';
} 
elseif($pagetype=='empl')
{
	$this->setPageTitle('Редактирование страницы "Работодателям"');
	$this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>'Работодателям'); 
	echo '<h3><i>Редактирование страницы "Работодателям"</i></h3>';
} 
elseif($pagetype=='prom')
{
	$this->setPageTitle('Редактирование страницы "Соискателям"');
	$this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>'Соискателям'); 
	echo '<h3><i>Редактирование страницы "Соискателям"</i></h3>';
}
else 
{
    echo '<h3>Настройка страницы сайта</h3>';
}

$share = new Share;
$lang = $share->getLang();

$pg = new PagesContent;
$result = $pg->getContent($lang, $id);

$result2 = new Pages;
if($id>0)
{
  $result2 = Pages::model()->findByPk($id);
  $model = $result;
}
?>
<div class="row">
  <div class="col-xs-12">
    <?
      echo CHtml::form('','post');
      echo CHtml::hiddenField('field_lang', $lang);
      echo CHtml::hiddenField('pagetype', $pagetype);
      echo CHtml::hiddenField('PagesContent[img]', $result->img);
    ?>
    <div class="row">
      <div class="hidden-xs col-sm-1 col-md-3"></div>
      <div class="col-xs-12 col-sm-10 col-md-6">
        <div class="row">
          <div class="col-xs-12">
            <label class="d-label">
              <span>Язык: <?=$lang?></span>
            </label>
            <label class="d-label">
              <span>Скрыть:</span>
              <input type="checkbox" name="PagesContent[hidden]" value="1" <?=($result->hidden ? 'checked="checked"' : '')?>>
            </label>
            <label class="d-label">
              <span>Ссылка</span>
              <input type="text" name="PagesContent[link]" class="form-control" value="<?=$result2->link?>">
            </label>
            <label class="d-label">
              <span>Название</span>
              <input type="text" name="PagesContent[name]" class="form-control" value="<?=$result->name?>">
            </label>
            <? if(in_array($pagetype,['articles','news'])): ?>
              <label class="d-label">
                <span>Анонс</span>
                <textarea name="PagesContent[anons]" class="form-control"><?=$result->anons?></textarea>
              </label>
            <? endif; ?>
            <div class="row">
              <? if($pagetype=='articles'): ?>
                <div class="col-xs-12 col-sm-6">
                  <label class="d-label" id="preview_img">
                    <span>Изображение анонса</span>
                    <?
                      $imgPath = Settings::getFilesRoot() . 'articles/' . $result->img . $smallImgWidth . '.jpg';
                      $imgSrc = Settings::getFilesUrl() . 'articles/' . $result->img . $smallImgWidth . '.jpg';
                      if(file_exists($imgPath))
                      {
                        echo '<img src="' . $imgSrc . '">';
                      }
                    ?>
                    <input type="hidden" name="PagesContent[img]" class="form-control" value="<?=$result->img?>">
                  </label>
                  <?
                    $this->widget(
                      'YiiUploadWidget',
                      [
                        'fileFormat' => ['jpg','jpeg','png'],
                        'imgDimensions' => [(string)$smallImgWidth => $smallImgWidth],
                        'callButtonText' => 'Загрузить',
                        'cssClassButton' => 'preview_btn',
                        'maxFileSize' => 10,
                        'jsMethodAfterUpload' => 'afterUploadPreviewImage',
                        'filePath' => $filePath,
                        'fileUrl' => $fileUrl
                      ]
                    );
                  ?>
                </div>
              <? elseif ($pagetype=='news'): ?>
                <div class="col-xs-12 col-sm-6">
                  <label class="d-label" id="preview_img">
                    <span>Изображение анонса</span>
                    <?
                    $imgPath = Settings::getFilesRoot() . 'news/' . $result->img . $smallImgWidth . '.jpg';
                    $imgSrc = Settings::getFilesUrl() . 'news/' . $result->img . $smallImgWidth . '.jpg';
                    if(file_exists($imgPath))
                    {
                      echo '<img src="' . $imgSrc . '">';
                    }
                    ?>
                    <input type="hidden" name="PagesContent[img]" class="form-control" value="<?=$result->img?>">
                  </label>
                  <?
                  $this->widget(
                    'YiiUploadWidget',
                    [
                      'fileFormat' => ['jpg','jpeg','png'],
                      'imgDimensions' => [(string)$smallImgWidth => $smallImgWidth],
                      'callButtonText' => 'Загрузить',
                      'cssClassButton' => 'preview_btn',
                      'maxFileSize' => 10,
                      'jsMethodAfterUpload' => 'afterUploadPreviewImage',
                      'filePath' => $filePath,
                      'fileUrl' => $fileUrl
                    ]
                  );
                  ?>
                </div>
              <? endif; ?>
              <div class="col-xs-12 <?=in_array($pagetype,['articles','news']) ? 'col-sm-6' : ''?>">
                <label class="d-label">
                  <span>Дата публикации</span>
                  <?
                  echo $this->widget(
                    'zii.widgets.jui.CJuiDatePicker',
                    [
                      'model'=>$result,
                      'attribute'=>'pubdate',
                      'options'=>
                        [
                          'dateFormat'=>'yy-mm-dd 00:00:00',
                          'timeFormat'=>'hh:mm:ss',
                          'minDate'=>'Today'
                        ],
                      'htmlOptions'=>['class'=>'form-control']
                    ],
                    true
                  );
                  ?>
                </label>
              </div>
            </div>
            <?
            //
            ?>
            <label class="d-label">
              <span>Титл страницы(мета данные)</span>
              <input type="text" name="PagesContent[meta_title]" class="form-control" value="<?=$result->meta_title?>">
            </label>
            <label class="d-label">
              <span>Description(мета данные)</span>
              <textarea name="PagesContent[meta_description]" class="form-control"><?=$result->meta_description?></textarea>
            </label>
            <label class="d-label">
              <span>Ключевые слова(мета данные)</span>
              <input type="text" name="PagesContent[meta_keywords]" class="form-control" value="<?=$result->meta_keywords?>">
            </label>
          </div>
        </div>
      </div>
      <div class="hidden-xs col-sm-1 col-md-3"></div>
      <div class="col-xs-12">
        <?
          if(in_array($pagetype,['articles','news']))
          {?>
            <div class="bs-callout bs-callout-warning">Для загрузки изображения в тело текста необходимо:<br>
              <ol>
                <li>Загрузить изображение на сервер используя кнопку "Загрузить фото в текст статьи"(есть возможность загрузить несколько фото сразу). По завершению процедуры появится окно со ссылкой на картинку и заготовками html тегов</li>
                <li>Копируем ссылку на картинку(1е поле)</li>
                <li>Устанавливаем в текстовом редакторе курсор, где необходимо расположить картинку</li>
                <li>В панели инструментов текстового редактора выбираем функцию "Изображение"</li>
                <li>Во вкладке "Данные об изображении" в поле "Ссылка" вставляем ссылку на нашу картинку</li>
                <li>Во вкладке "Дополнительно" в поле "Класс CSS" добавляем текст "single_article_img". Это необходимо для выравнивания изображения</li>
                <li>Во вкладке "Дополнительно" оставить пустым поле "Стиль"</li>
              </ol>
            </div>
            <?
            $this->widget(
              'YiiUploadWidget',
              [
                'fileLimit' => 10,
                'fileFormat' => ['jpg','jpeg','png'],
                'imgDimensions' => [],
                'maxFileSize' => 10,
                'callButtonText' => 'Загрузить фото в текст статьи',
                'showTags' => true,
                'filePath' => $filePath,
                'fileUrl' => $fileUrl
              ]
            );
          }
        ?>
        <label class="d-label">
          <span>Текст</span>
        </label>
        <textarea name="PagesContent[html]" class="form-control" id="detail_area"><?=$result->html?></textarea>
        <div class="pull-right">
          <? if($pagetype=='articles'): ?>
            <a href="<?=$this->createUrl('/articlespages')?>" class="btn btn-success d-indent">Назад</a>
          <? elseif($pagetype=='news'): ?>
            <a href="<?=$this->createUrl('/newspages')?>" class="btn btn-success d-indent">Назад</a>
          <? else: ?>
            <a href="<?=$this->createUrl('/pages')?>" class="btn btn-success d-indent">Назад</a>
          <? endif; ?>
          <button type="submit" class="btn btn-success d-indent">Сохранить</button>
        </div>
      </div>
    </div>
    <? echo CHtml::endForm(); ?>
  </div>
</div>
<script>
  'use strict'
  jQuery(function($){
    CKEDITOR.replace('detail_area');
    CKEDITOR.config.height = '750px';
  });
  var fileUrl = '<?=$fileUrl?>';
  var filSuffix = '<?=$smallImgWidth?>';
  var afterUploadPreviewImage = function()
  {
    var objImage = arguments[0][0],
        nameWithoutSuffix = objImage.name.split('.').slice(0, -1).join('.'),
        src = fileUrl + nameWithoutSuffix + filSuffix + '.jpg';

    $('#preview_img input').val(nameWithoutSuffix);
    $('#preview_img img').remove();
    $('#preview_img').append('<img src="' + src + '">');
  }
</script>
<style>
  .YiiUpload__call-btn.preview_btn{ margin: -5px 0 5px 0; }
  #preview_img img{
    width: 100%;
    padding-bottom: 5px;
  }
</style>