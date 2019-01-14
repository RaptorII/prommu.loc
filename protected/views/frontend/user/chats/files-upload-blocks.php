<div id="TmplF2upload">
  <form method="post" enctype="multipart/form-data" id="F2upload">
    <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
    <h2>Добавить файл к сообщению</h2>
    <input type="file" name="img" id="UplImg">
    <div class="message -red"></div>
    <div class="btn-white-green-wr btn-upload">
      <button type="button">Выбрать и загрузить</button>
      <div class="loading-block">
        <span class="loading-ico">
          <img src="/theme/pic/loading2.gif" alt="">
        </span>
      </div>
    </div>
    <p>Файл загружаемый на сайт не должен превышать размер 5 Мб, максимальный размер изображения 2500х2500 пикселей.<br />Типы файла для загрузки: JPG, PNG, DOC, XLS</p>
  </form>
</div>
<?
//
?>
<div class="attached-image attached-image-tpl tmpl uni-img-block">
  <span class="uni-delete js-hashint" title="удалить файл"></span>
  <a href="" class="uni-img-link" target="_blank">
    <img src="" alt="" class="uni-img">
  </a>
</div>
<?
//
?>
<div class="attached-file attached-file-tpl tmpl uni-img-block">
  <span class="uni-delete file js-hashint" title="удалить изображение"></span>
  <a href="" class="uni-link" target="_blank"></a>
</div>
<div class="prev-mess-tpl tmpl">
	<a href='#prev-mess' class="green-orange">показать предыдущие сообщения</a>
</div>
<?
//
?>
<div class="new-mess-tpl tmpl"><div><b>Новые сообщения</b></div></div>
<?
//
?>
<div class='mess-box mess-from tmpl'>
	<div class='author'>
		<img src="" alt="">
		<b class='fio'><!-- fio --></b>
		<span class='date'><!-- date --></span>
		<span class='viewed'><!-- viewed --></span>
	</div>
	<div class='mess'><!-- mess --></div>
	<div class='files'>
		<div class="js-container"></div>
		<a href="" class="black-orange"><img src="" alt=""></a>
	</div>
</div>
<?
//
?>
<div class='mess-box mess-to tmpl'>
	<div class='author'>
		<img src="" alt="">
		<b class='fio'><!-- fio --></b>
		<span class='date'><!-- date --></span>
		<span class='viewed'><!-- viewed --></span>
	</div>
	<div class='mess'><!-- mess --></div>
	<div class='files'>
		<div class="js-container"></div>
		<a href="" class="black-orange"><img src="" alt=""></a>
	</div>
</div>