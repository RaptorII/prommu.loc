<h3><?=$this->pageTitle?></h3>
<div class="row">
	<div class="col-xs-12">
		<?
			$this->widget(
					'ext.elFinder.ElFinderWidget', 
					array('connectorRoute' => '/elfinder/connector')
				);
		?>	
	</div>
</div>
<style type="text/css">
	.el-finder-dialog{
		z-index: 900;
		margin-left: 50%;
		top: 100px!important;
		left: -300px!important;	
	}
	.el-finder-dialog.el-finder-dialog-info{
		left: -190px!important;	
	}
</style>
<?
/*
	$this->widget(
		'YiiUploadWidget',
		[
			'fileLimit' => 5,
			'fileFormat' => ['jpg','jpeg','png'],
			'callButtonText' => 'Upload',
			'useWebcam' => true,
			'minImageSize' => 400,
			'maxImageSize' => 4500,
			'imageEditor' => true,
			'maxFileSize' => 10, // в мегабайтах(по умолчанию 5)
			'imageSignature' => true,
			'showTags' => true, // отобразить урл и теги для добавления в статьи итд(для админа)
			'imgDimensions' => ['100'=>220,'169'=>169,'400'=>400], // для измененных изображений ['суфикс к заголовку' => 'размер']
			'imgOrigSuFFix' => '000', // суфикс к заголовку измененного, возможно повернутого но не измененного в размере изображения
			'filePath' => Settings::getFilesRoot() . 'test1',
			'fileUrl' => Settings::getFilesUrl() . 'test1'
		]
	);

	$this->widget(
		'YiiUploadWidget',
		[
			'fileLimit' => 5,
			'fileFormat' => ['jpg','jpeg','png'],
			'callButtonText' => 'Upload',
			'useWebcam' => true,
			'minImageSize' => 400,
			'maxImageSize' => 4500,
			'imageEditor' => true,
			'maxFileSize' => 10, // в мегабайтах(по умолчанию 5)
			'imageSignature' => true,
			'showTags' => false, // отобразить урл и теги для добавления в статьи итд(для админа)
			'imgDimensions' => ['100'=>220,'169'=>169,'400'=>400], // для измененных изображений ['суфикс к заголовку' => 'размер']
			'imgOrigSuFFix' => '000', // суфикс к заголовку измененного, возможно повернутого но не измененного в размере изображения
			'filePath' => Settings::getFilesRoot() . 'test2',
			'fileUrl' => Settings::getFilesUrl() . 'test2'
		]
	);
*/
?>