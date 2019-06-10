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
	public $imageSignature; // boolean - added signature for the file
	public $objSave; // object - save photos object
	public $objSaveMethod; // string - name of save method in object $objSave
	public $cssClassButton; // string - custom styles for main button
	public $cssClassForm; // string - custom styles for popup
	public $reloadAfterUpload; // boolean - reload page after upload and close popup
	public $arEditImage; // array - array with data for edit image

	public  $defaultController='/site';

	private $_action;
	private $_baseUrl;
	public  $instanceId;
	private $dirPermission = 0700; // permission for dir in creating
	private $defaultImgSize = 1600; // image size in defaukt
	
	public function init()
	{
		parent::init();
		$this->_prepareAssets();
		//
		// default values
		//
		$this->fileLimit = intval($this->fileLimit)>1 ? intval($this->fileLimit) : 1;
		!is_array($this->fileFormat) && $this->fileFormat = [];
		!isset($this->callButtonText) && $this->callButtonText = 'Upload';
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
		!isset($this->imageSignature) && $this->imageSignature = false;
		!is_object($this->objSave) && $this->objSave = false;
		!isset($this->objSaveMethod) && $this->objSaveMethod = false;
		!isset($this->cssClassButton) && $this->cssClassButton = false;
		!isset($this->cssClassForm) && $this->cssClassForm = false;
		!isset($this->reloadAfterUpload) && $this->reloadAfterUpload = false;
		!isset($this->arEditImage) && $this->arEditImage = false;
		!isset($GLOBALS['yiiUploadCnt']) ? $GLOBALS['yiiUploadCnt']=1 : $GLOBALS['yiiUploadCnt']++;
	}

	public function run()
	{
		$this->instanceId = $GLOBALS['yiiUploadCnt']; // unique ID
		// this 'dummy' argument is required to provide urlmanager support...
		//$this->_action = array($this->defaultController.'/yiiupload','dummy'=>'@');
		$this->_action = $this->defaultController.'/yiiupload?dummy=%40&id=' . $this->instanceId;

		$options = CJavaScript::encode(array(
				'fileLimit'=>$this->fileLimit,
				'callButtonText'=>$this->callButtonText,
				//'action'=>CHtml::normalizeUrl($this->_action),
				'action'=>$this->_action,
				'showTags'=>$this->showTags,
				'fileFormat' => $this->fileFormat,
				'imageEditor' => $this->imageEditor,
				'useWebcam' => $this->useWebcam,
				'imageSignature' => $this->imageSignature,
				'cssClassForm' => $this->cssClassForm,
				'reloadAfterUpload' => $this->reloadAfterUpload,
				'instanceId' => $this->instanceId,
				'arEditImage' => $this->arEditImage
			));
		$s = Yii::app()->session['yiiUpload'];
		$s[$this->instanceId] = [
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
				'useWebcam' => $this->useWebcam,
				'objSave' => $this->objSave,
				'objSaveMethod' => $this->objSaveMethod
			];
		Yii::app()->session['yiiUpload'] = $s;
		
		Yii::app()->getClientScript()->registerScript("yii_upload_script_".$this->instanceId,
			"	var upload_{$this->instanceId} = new YiiUpload({$options});");

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
		$s = Yii::app()->session['yiiUpload'];
		$instanceId = Yii::app()->getRequest()->getParam('id');
		$arRes = [];

		if(isset($_FILES['upload']))
		{
			$arRes = $this->checkImages($_FILES['upload'], $s[$instanceId]);
			if($s[$instanceId]['imageEditor']==true)
			{
				$this->saveData(['files'=>$arRes['success']], $s[$instanceId]);
			}
		}
		elseif($_POST['state']=='full')
		{
			$arRes = $this->editImages($_POST['data'], $s[$instanceId]);
			$this->saveData(['files'=>$arRes['success']], $s[$instanceId]);
		}
		elseif($_POST['state']=='edit')
		{
			$arRes = $this->onlyEditImages($_POST['data'], $s[$instanceId]);
			$this->saveData(['files'=>$arRes['success']], $s[$instanceId]);
		}
		elseif($_POST['state']=='delete')
		{
			$this->deleteImages($_POST['data'], $s[$instanceId]);
		}

		echo CJSON::encode($arRes);
		Yii::app()->end();
	}
	/**
	 * @param $arData - array()
	 * @param $arParams - array()
	 */
	private function checkImages($arData, $arParams)
	{
		$arRes = ['error'=>[],'items'=>[],'success'=>[]];

		$result = $this->existenceDir($arParams['filePath']);
		if(!$result)
		{
			$arRes['error'][] = '- ошибка сохранения, обратитесь к администратору';
			return $arRes;
		}

		$n = count($arData['error']);
		$mSize = $arParams['maxFileSize'] * 1024 * 1024; // переводим в байты
		for($i=0; $i<$n; $i++)
		{
			if(!$arData['size'][$i]) // наш пустой инпут
				continue;

			$fName = $arData["name"][$i];
			$info = new SplFileInfo($fName);
			$type = mb_strtolower($info->getExtension());
			$arRes['items'][] = $fName;

			if($arData['error'][$i]) // ошибка передачи файла на сервер
			{
				$arRes['error'][] = "- ошибка передачи файла '{$fName}' на сервер";
				continue;
			}
			if($arData['size'][$i]>$mSize) // ошибка передачи файла на сервер
			{
				$arRes['error'][] = "Размер файла '{$fName}' больше допустимого значения ({$mSize}Мб)";
				continue;
			}

			if(!in_array($type,$arParams['fileFormat'])) // проверяем формат на корректность
			{
				$arRes['error'][] = "- у файла '{$fName}' некорректный формат";
				continue;
			}

			$newName = date('YmdHis') . rand(1000,9999) . '.' . $type;
			$filePath = $arParams['filePath'] . $newName;
			$src = $arParams['fileUrl'] . $newName;
			$result = move_uploaded_file($arData["tmp_name"][$i], $filePath);
			if($result) // файл успешно перемещен
			{
				$fSize = getimagesize($filePath);
				if($fSize)
				{
					$size = $arParams['minImageSize']; // проверяем на минимальную ширину/высоту
					if($size>0 && ($fSize[0]<$size || $fSize[1]<$size))
					{
						$arRes['error'][] = "- файл '{$fName}' меньше допустимого значения ({$size}x{$size})";
						unlink($filePath);
						continue;
					}
					$size = $arParams['maxImageSize']; // проверяем на максимальную ширину/высоту
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
		return $arRes;
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
					$imgProps,
					$arData[$i]['signature']
				);

			imagedestroy($image);
			imagedestroy($dstImage);
			unlink($filePath);
			unlink($inOutFile);
		}
		return $arRes;
	}
	/**
	 * @param $arData - array()
	 * @param $arParams - array()
	 */
	private function onlyEditImages($arData, $arParams)
	{
		$arFile = reset($arData);
		$arFile['name'] = date('YmdHis') . rand(1000,9999) . '.jpg';
		$filePathWithoutExt = $arParams['filePath'] . $arFile['oldName'];
		$filePathFull = $arParams['filePath'] 
			. $arFile['oldName'] . $arParams['imgOrigSuFFix'] . '.jpg';
		$filePath = $arParams['filePath'] . $arFile['name'];
		$image = imagecreatefromjpeg($filePathFull);
		if(!$image){ $this->log('onlyEditImages():01'); return; }
		$result = imagejpeg($image, $filePath, 100);
		if(!$result){ $this->log('onlyEditImages():02'); return; }
		unlink($filePathFull);
		foreach ($arParams['imgDimensions'] as $suffix => $size)
		{
			unlink($filePathWithoutExt . $suffix . '.jpg');
		}
		$arFile['oldName'] .= '.jpg';

		return $this->editImages([0=>$arFile], $arParams);;
	}
	/**
	 * @param $arData - array()
	 * @param $arParams - array()
	 */
	private function deleteImages($arData, $arParams)
	{
		for ($i=0, $n=count($arData); $i<$n; $i++)
		{
			$path = $arParams['filePath'] . $arData[$i];
			if(file_exists($path))
			{
				unlink($path);
			}
		}
		return [];
	}
	/**
	 * @param $arData - array ['files'=>[0=>['name','oldname','path','linkTag','isImg','imgTag','signature']...]]
	 * @param $arParams - array()
	 */
	private function saveData($arData, $arParams)
	{
		if($arParams['objSave']==false || $arParams['objSaveMethod']==false)
			return false;

		$object = $arParams['objSave'];
		$method = $arParams['objSaveMethod'];
		return $object->$method($arData);
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
	 * 
	 */
	private function getSuccess($newName, $oldName, $path, $bImg, $signature='')
	{
		return [
				'name' => $newName, 
				'oldname' => $oldName, 
				'path' => $path,
				'linkTag' => '<a href="' . $path . '" target="_blank">' . $oldName . '</a>',
				'isImg' => (boolean) $bImg,
				'imgTag' => $bImg ? '<img src="' . $path . '" alt="' . $oldName . '" data-name="' . $newName . '"/>' : '',
				'signature' => $signature
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
			$user = (isset($user) ? ' user:' : ' user:guest') . $user . ' ';
		}

		$result = date('Y-m-d H:i:s') . $user . $log . PHP_EOL;
		file_put_contents(__DIR__ . "/_YiiUpload_log.txt", $result, FILE_APPEND);
	}
}