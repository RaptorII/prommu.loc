<?php
/**
 *
 * @uses CWidget
 * @version 1.0 
 * @author Dmitry Derevyanko <derevyanko977@gmail.com>
 * 
 */
class YiiUploadWidget extends CWidget
{
	public $fileLimit; // integer - file limit
	public $fileFormat; // array - valid file`s format
	public $callButtonText; // string - text in call popup button
	public $filePath; // string - path to files
	public $fileUrl; // string - url to files
	public $showTags; // boolean - show tags after load
	public $minImageSize; // integer - maximum image size(width,height)
	public $maxImageSize; // integer - maximum image size(width,height)
	public $maxFileSize; // integer - maximum file size (MBytes)
	public $imageEditor; // boolean - show image editor after loadding page
	public $imgDimensions; // array - for edit images ['title suffix' => 'size']
	public $imgOrigSuFFix; // string - for edit images, rotated but not resized images
	public $useWebcam; // boolean - get images from camera of user device

	public  $defaultController='/site';

	private $_action;
	private $_baseUrl;
	private $dirPermission = 0700; // permission for dir in creating
	private $defaultImgSize = 1600; // image size in defaukt
	
	public function init()
	{
		parent::init();
		$this->_prepareAssets();
		//
		// default values
		//
		/*
		$this->minFileLimit = intval($this->minFileLimit)>=1 ? intval($this->minFileLimit) : 1;
		$this->maxFileLimit = intval($this->maxFileLimit)>1 ? intval($this->maxFileLimit) : 1;
		$this->minFileLimit>$this->maxFileLimit && $this->maxFileLimit = $this->minFileLimit;
		*/
		$this->fileLimit = intval($this->fileLimit)>1 ? intval($this->fileLimit) : 1;
		!is_array($this->fileFormat) && $this->fileFormat = [];
		empty($this->callButtonText) && $this->callButtonText = 'Upload';
		!isset($this->showTags) && $this->showTags = false;
		!isset($this->minImageSize) && $this->minImageSize = 0;
		!isset($this->maxImageSize) && $this->maxImageSize = 0;
		!is_array(($this->imgDimensions)) && $this->imgDimensions = ['400' => 400];
		empty($this->imgOrigSuFFix) && $this->imgOrigSuFFix = '000';
		if($this->minImageSize>0 && $this->maxImageSize>0 && $this->minImageSize>$this->maxImageSize)
		{
			$this->maxImageSize=$this->minImageSize;
		}
		$this->maxFileSize = intval($this->maxFileSize) ? $this->maxFileSize : 5;
		!isset($this->imageEditor) && $this->imageEditor = false;
		!isset($this->useWebcam) && $this->useWebcam = false;
	}

	public function run()
	{
		// this 'dummy' argument is required to provide urlmanager support...
		$this->_action = array($this->defaultController.'/yiiupload','dummy'=>'@');

		$options = CJavaScript::encode(array(
				'fileLimit'=>$this->fileLimit,
				'callButtonText'=>$this->callButtonText,
				'action'=>CHtml::normalizeUrl($this->_action),
				'showTags'=>$this->showTags,
				'fileFormat' => $this->fileFormat,
				'imageEditor' => $this->imageEditor,
				'useWebcam' => $this->useWebcam
			));
		$s = Yii::app()->session;
		$s['yiiUpload'] = [
				'filePath' => substr($this->filePath,-1)!="/" 
					? $this->filePath."/" 
					: $this->filePath,
				'fileUrl' => substr($this->fileUrl,-1)!="/" 
					? $this->fileUrl."/" 
					: $this->fileUrl,
				'fileLimit' => $this->fileLimit,
				'fileFormat' => $this->fileFormat,
				'minImageSize' => $this->minImageSize,
				'maxImageSize' => $this->maxImageSize,
				'maxFileSize' => $this->maxFileSize,
				'imgDimensions' => $this->imgDimensions,
				'imgOrigSuFFix' => $this->imgOrigSuFFix,
				'useWebcam' => $this->useWebcam
			];
		$var_id = rand(1000,9999);
		Yii::app()->getClientScript()->registerScript("yii_upload_script_".$var_id,
			"	var upload_{$var_id} = new YiiUpload({$options});");

		$this->render('view');
	}

	public function _prepareAssets()
	{
		$localAssetsDir = dirname(__FILE__) . '/assets';
		$this->_baseUrl = Yii::app()->getAssetManager()->publish($localAssetsDir);
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		foreach(scandir($localAssetsDir) as $f)
		{
			$_f = strtolower($f);
			if(strstr($_f,".js"))
				$cs->registerScriptFile($this->_baseUrl."/".$_f);
			if(strstr($_f,".css"))
				$cs->registerCssFile($this->_baseUrl."/".$_f);
		}
	}

	public function runAction()
	{
		$s = Yii::app()->session;
		$arRes = ['error'=>[],'items'=>[],'success'=>[]];

		if($_POST['state']=='edit')
		{
			$arRes = $this->editImages($_POST['data'], $s['yiiUpload']);
			echo CJSON::encode($arRes);
			Yii::app()->end();		
		}

		$arFiles = $_FILES['upload'];
		$url = $s['yiiUpload']['fileUrl'];
		$result = $this->existenceDir($s['yiiUpload']['filePath']);
		if(!$result)
		{
			$arRes['error'][] = '- ошибка сохранения, обратитесь к администратору';
			echo CJSON::encode($arRes);
			Yii::app()->end();
		}

		$n = count($arFiles['error']);
		$mSize = $s['yiiUpload']['maxFileSize'] * 1024 * 1024;
		for($i=0; $i<$n; $i++)
		{
			if(!$arFiles['size'][$i]) // наш пустой инпут
				continue;

			$fName = $arFiles["name"][$i];
			$info = new SplFileInfo($fName);
			$type = mb_strtolower($info->getExtension());
			$arRes['items'][] = $fName;

			if($arFiles['error'][$i]) // ошибка передачи файла на сервер
			{
				$arRes['error'][] = "- ошибка передачи файла '{$fName}' на сервер";
				continue;
			}
			if($arFiles['size'][$i]>$mSize) // ошибка передачи файла на сервер
			{
				$arRes['error'][] = "Размер файла '{$fName}' больше допустимого значения ({$mSize}Мб)";
				continue;
			}

			if(!in_array($type,$s['yiiUpload']['fileFormat'])) // проверяем формат на корректность
			{
				$arRes['error'][] = "- у файла '{$fName}' некорректный формат";
				continue;
			}

			$newName = date('YmdHis') . rand(1000,9999) . '.' . $type;
			$filePath = $s['yiiUpload']['filePath'] . $newName;
			$src = $url . $newName;
			$result = move_uploaded_file($arFiles["tmp_name"][$i], $filePath);
			if($result) // файл успешно перемещен
			{
				$fSize = getimagesize($filePath);
				if($fSize)
				{
					$size = $s['yiiUpload']['minImageSize']; // проверяем на минимальную ширину/высоту
					if($size>0 && ($fSize[0]<$size || $fSize[1]<$size))
					{
						$arRes['error'][] = "- файл '{$fName}' меньше допустимого значения ({$size}x{$size})";
						unlink($filePath);
						continue;
					}
					$size = $s['yiiUpload']['maxImageSize']; // проверяем на максимальную ширину/высоту
					if($size>0 && ($fSize[0]>$size || $fSize[1]>$size))
					{
						$arRes['error'][] = "- файл '{$fName}' больше допустимого значения ({$size}x{$size})";
						unlink($filePath);
						continue;
					}
					$this->resizeImage($filePath, $filePath, $this->defaultImgSize); // сжимаем до допустимых размеров
				}
				$arRes['success'][] = $this->getSuccess($newName, $fName, $src, $fSize);
			}
			else
			{
				$arRes['error'][] = "- ошибка загрузки файла '{$fName}' на сервер";
			}
		}
		
		echo CJSON::encode($arRes);
		Yii::app()->end();
	}
	/**
	 * @param $path - string
	 */
	private function existenceDir($path)
	{
		$arPath = explode('/',$path);
		$dirPath = '';
		$arRes = true;
		for ($i=0, $n=count($arPath); $i<$n; $i++)
		{
			$dirPath .= $arPath[$i] . '/';
			if(!is_dir($dirPath))
			{
				$arRes = mkdir($dirPath, $this->dirPermission);
			}
			if(!$arRes)
				break;
		}
		return $arRes;
	}
	/**
	* @param $inPath - string, path to file input
	* @param $outPath - string, path to file output
	* @param $size - integer, maximum size
	*/
	private function resizeImage($inPath, $outPath, $size)
	{
		$quality = 90;
		$imgProps = getimagesize($inPath); // Get dimensions
		list($oldW, $oldH) = $imgProps;
		$ratioOrig = $oldW / $oldH;

		if( $ratioOrig > 1 ) // альбомный
		{
			$newW = ($oldW>$size) ? $size : $oldW;
			$newH = ($oldW>$size) ? ($newW/$ratioOrig) : $oldH;
		}
		else // портретный
		{
			$newH = ($oldH>$size) ? $size : $oldH;
			$newW = ($oldH>$size) ? ($newH*$ratioOrig) : $oldW;
		}
		//  Создание нового полноцветного изображения
		$image_p = imagecreatetruecolor($newW, $newH);
		if(!$image_p){ $this->log('resizeImage():01'); return; }

		switch($imgProps['mime'])
		{
			case "image/jpeg": 
				$image = imagecreatefromjpeg($inPath);
				if(!$image){ $this->log('resizeImage():02'); return; }
				break;
			case "image/pjpeg": 
				$image = imagecreatefromjpeg($inPath);
				if(!$image){ $this->log('resizeImage():03'); return; }
				break;
			case "image/png":
				$image = imagecreatefrompng($inPath);
				if(!$image){ $this->log('resizeImage():04'); return; }
				break;
			case "image/x-png":
				$image = imagecreatefrompng($inPath);
				if(!$image){ $this->log('resizeImage():05'); return; }			
				break;
			case "image/gif":
				$image = imagecreatefromgif($inPath);
				if(!$image){ $this->log('resizeImage():06'); return; }
				break;
			default:
				$this->log('resizeImage():07');
				return;
				break;
		}
		// Копирование и изменение размера изображения с ресемплированием
		$result = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH); 
		if(!$result){ $this->log('resizeImage():08'); return; }
		$result = imagejpeg($image_p, $outPath, $quality); // записываем изображение в файл
		if(!$result){ $this->log('resizeImage():09'); return; }
		imagedestroy($image_p);
		imagedestroy($image);
	}
	/**
	 * @param $arData - array()
	 * @param $arParams - array()
	 */
	private function editImages($arData, $arParams)
	{
		$quality = 90;
		$arRes = ['error'=>[],'items'=>[],'success'=>[]];

		for ($i=0, $n=count($arData); $i<$n; $i++)
		{ 
			$arRes['items'][] = $arData[$i]['oldName'];
			$filePath = $arParams['filePath'] . $arData[$i]['name'];
			$filePathWithoutExt = substr($filePath, 0, (strlen($filePath)-4)); // without '.jpg'
			$inOutFile = $filePathWithoutExt . 'tmp.jpg';

			if(!file_exists($filePath))
			{	$this->log('editImages():01'); return; }

			$image = imagecreatefromjpeg($filePath);
			if(!$image){	$this->log('editImages():02'); return; }

			$imgProps = getimagesize($filePath);
			list($oldW, $oldH) = $imgProps; // get old width and old height
			$srcImgW = $oldW;
			$srcImgH = $oldH;
			$tmpImgW = $arData[$i]['width'];
			$tmpImgH = $arData[$i]['height'];
			$degrees = $arData[$i]['rotate'];
			$srcX = $arData[$i]['x'];
			$srcY = $arData[$i]['y'];  

			// Rotate the source image
			if(is_numeric($degrees) && $degrees != 0)
			{
				// PHP's degrees is opposite to CSS's degrees
				$image = imagerotate( $image, -$degrees, 0);
				$deg = abs($degrees) % 180;
				$arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
				$srcImgW = $oldW * cos($arc) + $oldH * sin($arc);
				$srcImgH = $oldW * sin($arc) + $oldH * cos($arc);
				// Fix rotated image miss 1px issue when degrees < 0
				$srcImgW -= 1;
				$srcImgH -= 1;
			}

			if($srcX <= -$tmpImgW || $srcX > $srcImgW)
			{
				$srcX = $srcW = $dstX = $dstW = 0;
			} 
			elseif($srcX <= 0)
			{
				$dstX = -$srcX;
				$srcX = 0;
				$srcW = $dstW = min($srcImgW, $tmpImgW + $srcX);
			} 
			elseif($srcX <= $srcImgW)
			{
				$dstX = 0;
				$srcW = $dstW = min($tmpImgW, $srcImgW - $srcX);
			}

			if($srcW <= 0 || $srcY <= -$tmpImgH || $srcY > $srcImgH)
			{
				$srcY = $srcH = $dstY = $dstH = 0;
			}
			elseif($srcY <= 0)
			{
				$dstY = -$srcY;
				$srcY = 0;
				$srcH = $dstH = min($srcImgH, $tmpImgH + $srcY);
			}
			elseif($srcY <= $srcImgH)
			{
				$dstY = 0;
				$srcH = $dstH = min($tmpImgH, $srcImgH - $srcY);
			}
			// Scale to destination position and size
			$ratio = $tmpImgW / $dstW;
			$dstX /= $ratio;
			$dstY /= $ratio;
			$dstW /= $ratio;
			$dstH /= $ratio;
			//  Создание нового полноцветного изображения
			$dstImage = imagecreatetruecolor($dstW, $dstH);
			// Add transparent background to destination image
			imagefill($dstImage, 0, 0, imagecolorallocate($dstImage, 255, 255, 255));
			// Копирование и изменение размера изображения с ресемплированием
			$result = imagecopyresampled($dstImage, $image, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
			if(!$result){	$this->log('editImages():03'); return; }
			$result = imagejpeg($dstImage, $inOutFile, $quality); // записываем изображение в файл
			if(!$result){ $this->log('editImages():04'); return; }
			foreach ($arParams['imgDimensions'] as $suffix => $size)
			{
				$this->resizeImage($inOutFile, "{$filePathWithoutExt}{$suffix}.jpg", $size);
			}
			$result = imagejpeg($image, "{$filePathWithoutExt}{$arParams['imgOrigSuFFix']}.jpg");
			if(!$result){	$this->log('editImages():03'); return; }

			$arRes['success'][] = $this->getSuccess(
					$arData[$i]['name'], 
					$arData[$i]['oldName'], 
					$arParams['fileUrl'] . $arData[$i]['name'], 
					$imgProps
				);

			imagedestroy($image);
			imagedestroy($dstImage);
			unlink($filePath);
			unlink($inOutFile);
		}
		return $arRes;
	}
	/**
	 * 
	 */
	private function getSuccess($newName, $oldName, $path, $bImg)
	{
		return [
				'name' => $newName, 
				'oldname' => $oldName, 
				'path' => $path,
				'linkTag' => '<a href="' . $path . '" target="_blank">' . $oldName . '</a>',
				'isImg' => (boolean) $bImg,
				'imgTag' => $bImg ? '<img src="' . $path . '" alt="' . $oldName . '" data-name="' . $newName . '"/>' : ''
			];
	}
	/**
	 * 
	 */
	private function log($log)
	{
		if(strpos(Yii::app()->request->url, '/admin/')!==false)
		{
			$user = ' admin:' . Yii::app()->user->getId() . ' ';
		}
		else
		{
			$user = Share::$UserProfile->id;
			$user = (empty($user) ? ' user:' : ' user:guest') . $user . ' ';
		}

		$result = date('Y-m-d H:i:s') . $user . $log . PHP_EOL;
		file_put_contents(__DIR__ . "/_YiiUpload_log.txt", $result, FILE_APPEND);
	}
}