<?php
/**
 * Date: 09.06.2016
 *
 * Модель загрузки файлов на сайт
 */

class Uploaduni extends Model
{
    private $imgPath;
    private $scope;
    private $sizes;
    private $defOptions;


    function __construct()
    {
        parent::__construct();

        $this->defOptions = array('type' => array('images' => 'image/jpeg,image/png', 'files' => 'word,excel,spreadsheetml'),     // типы файлов
            'scope' => 'content',                                   // file scope
            'maxFS' => 5242880,                                     // max filesize
            'maxImgDim' => array(2500, 2500),                       // max размеры изображения
            'removeProtected' => true,                              // удалять каталог protected из пути файла
            'sizes' => array('isorig' => true, 'dims' => [], 'thumb' => 300 ),  // размеры для картинок
            'tmpDir' => '/images/tmp',                              // временный каталог для загрузки файлов
        );


        $scope = Yii::app()->getRequest()->getParam('sc');
        $this->scope = $scope;
        // параметры закачки картинок для различных разделов
        if( $scope == 'services' )
        {
            $this->imgPath = "services";
            // оставлять оригинал, и какие размеры делать
            $this->sizes = array('isorig' => true, 'dims' => [], 'thumb' => 300 );
        }
        else $this->scope = false;
    }



    public function init()
    {
//                                        unset($_SESSION['uploaduniFiles']);
        // проверяем на реальное наличие файлов
        $data = array();
        // загрузка с опциями
        if( Yii::app()->session['uploaduniFiles'] )
        {
            $options = $this->getCustomOptions();
            if( $options['scope'] && $files = $_SESSION['uploaduniFiles'][$options['scope']] )
            {
                $path = $options['tmpDir'];

                // убираем несущ. файлы
                $this->removeUnExistedFiles(array('scope' => $options['scope']));

                $data = $_SESSION['uploaduniFiles'][$options['scope']];
            } // endif


        }



        // упрощенная загрузка @deprecated
        if( $files = $_SESSION['uploaduni'] )
        {
            $path = "/images/{$this->imgPath}/tmp/";
            $_SESSION['uploaduni'] = array();

            foreach ($files as $key => $val2)
            {
                if( $val2[array_keys($val2)[0]] && file_exists(MainConfig::$DOC_ROOT . $val2[array_keys($val2)[0]]) ) $_SESSION['uploaduni'][$key] = $val2;
            } // end foreach

            $data = $_SESSION['uploaduni'];
        }


        return array('files' => $data);
    }



    /**
     * загружаем файлы, нарезаем изображения
     */
    public function processUploadedFile($inFn)
    {
        $message = "Шибка";
        $img = $inFn;
        // if( $_FILES[$img]['size'] > 1048576 || $_FILES[$img]['size'] == 0 ) $ret = array('error' => 1, 'message' => 'Неправильный размер файла!');
        // elseif( $_FILES[$img]['type'] != "image/jpeg" && $_FILES[$img]['type'] != "image/png" ) { $ret = array('error' => 1, 'message' => 'Тип файла не соответствует JPG или PNG'); }
        if(false){
            
        }
        else
        {
            $ext = substr($_FILES[$img]['name'], 1 + strrpos($_FILES[$img]['name'], "."));
            $fiId = date('YmdHis') . rand(100, 1000);
            $fn = $fiId . ".{$ext}";
            $path = "/images/{$this->imgPath}/tmp/";

            // проверяем на реальное наличие файлов
            if( $files = $_SESSION['uploaduni'] )
            {
                $_SESSION['uploaduni'] = array();

                foreach ($files as $key => $val2)
                {
                    if( file_exists(MainConfig::$DOC_ROOT . $val2[0]) ) $_SESSION['uploaduni'][$key] = $val2;
                } // end foreach
            }

//            if( $this->scope && true || move_uploaded_file($_FILES[$img]["tmp_name"], MainConfig::$DOC_ROOT . $path . $fn) )
            if( $this->scope )
            {
                $imgProps = getimagesize($_FILES[$img]["tmp_name"]);
                if( $imgProps[0] > 2500 || $imgProps[1] > 2500 )
                {
                    $flag = 1;
                    $message = "Изображение превышает размер в 2500х2500 пикселей";
                }
                else
                {
                    foreach ($this->sizes['dims'] as $key => $val)
                    {
                        if( (int) ($ret = $this->imgResizeToRect($_FILES[$img]["tmp_name"], MainConfig::$DOC_ROOT . $path . $fiId . $val . ".{$ext}", "image/jpeg", $val)) > 0 )
                        {
                            $_SESSION['uploaduni'][$fiId][$val] = $path . $fiId. $val . ".{$ext}";
                        }
                        else
                        {
                        } // endif;
                    } // end foreach


                    // оставляем оригинал
                    if( $this->sizes['isorig'] )
                    {
                        if( copy($_FILES[$img]["tmp_name"], MainConfig::$DOC_ROOT . $path . $fiId . ".{$ext}") )
                                $_SESSION['uploaduni'][$fiId]['orig'] = $path . $fiId . ".{$ext}";
                    }


                    // делаем миниатюру
                    if( $this->sizes['thumb'] )
                    {
                        if( $ret = $this->imgResizeToRect($_FILES[$img]["tmp_name"]
                                , MainConfig::$DOC_ROOT . $path . $fiId . "tb.{$ext}", "image/jpeg", $this->sizes['thumb']) > 0
                            )
                                $_SESSION['uploaduni'][$fiId]['tb'] = $path . $fiId . "tb.{$ext}";
                    }
                } // endif
            }
            else
            {
                $flag = 1;
            } // endif


            if( $flag )
            {
                $ret = array('error' => 1, 'message' => $message, 'ret' => $ret);
            }
            else
            {
//                Yii::app()->session['uplLogo'] = array('path' => "/images/{$this->imgPath}/tmp/", 'file' => $fn);
                $ret = array('error' => 0, 'id' => $fiId, 'file' => $_SESSION['uploaduni'][$fiId], 'files' => $_SESSION['uploaduni']);
            } // endif
        } // endif
//        $sql = "SELECT s.id, s.name, DATE_FORMAT(s.crdate, '%d.%m.%Y') crdate
//                FROM services s WHERE s.id = {$id}";
//        Yii::app()->db->createCommand($sql)->queryRow();
        return $ret;
    }



    /**
     * загружаем файлы, нарезаем изображения
     */
    public function processUploadedFileEx($inFn)
    {
        $scope = filter_var(Yii::app()->getRequest()->getParam('sc'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $meta = json_decode(base64_decode(Yii::app()->getRequest()->getParam('meta')));

        $options = $this->getCustomOptions();

        $message = "Ошибка загрузки файла, обновите страницу и попробуйте еще раз";
        $img = $inFn;


        try
        {
            if( $_FILES[$img]['size'] > $options['maxFS'] || $_FILES[$img]['size'] == 0 ) throw new Exception('Неправильный размер файла!', -101);

            // получаем тип файла
            $type = false;
            foreach ($options['type'] as $key => $val)
            {
                foreach ($val as $val2)
                {
                    if( strpos($_FILES[$img]['type'], $val2) !== false ) { $type = [$key, $val2]; break; }
                } // end foreach
            } // end foreach


            if( !$type ) {
                throw new Exception("Тип файла {$_FILES[$img]['type']} не допустим", -102);
            }
            else
            {
                $ext = substr($_FILES[$img]['name'], 1 + strrpos($_FILES[$img]['name'], "."));
                $fiId = date('YmdHis') . rand(100, 1000);
                $fn = $fiId . ".{$ext}";
                $path = $options['tmpDir'];


                // проверяем на реальное наличие файлов
                if( $files = $_SESSION['uploaduniFiles'] )
                {
                    $_SESSION['uploaduniFiles'] = array();

                    foreach ($files as $key => $val2)
                    {
                        if( file_exists(MainConfig::$DOC_ROOT . $val2[0]) ) $_SESSION['uploaduniFiles'][$key] = $val2;
                    } // end foreach
                }


                // Обрабатываем озображения
                if( $type[0] == 'images' )
                {
                    $imgProps = getimagesize($_FILES[$img]["tmp_name"]);
                    if( $imgProps[0] > 2500 || $imgProps[1] > 2500 )
                    {
                        $flag = 1;
                        $message = "Изображение превышает размер в 2500х2500 пикселей";
                    }
                    else
                    {
                        foreach ($options['sizes']['dims'] as $key => $val)
                        {
                            if( (int) ($ret = $this->imgResizeToRect($_FILES[$img]["tmp_name"], MainConfig::$DOC_ROOT . $path . DS . $fiId . $val . ".{$ext}", "image/jpeg", $val)) > 0 )
                            {
                                $_SESSION['uploaduniFiles'][$options['scope']][$fiId]['files'][$val] = $path . DS . $fiId. $val . ".{$ext}";
                            }
                            else
                            {
                            } // endif
                        } // end foreach


                        // оставляем оригинал
                        if( $options['sizes']['isorig'] )
                        {
                            if( copy($_FILES[$img]["tmp_name"], MainConfig::$DOC_ROOT . $path . DS . $fiId . ".{$ext}") )
                                    $_SESSION['uploaduniFiles'][$options['scope']][$fiId]['files']['orig'] = $path . DS . $fiId . ".{$ext}";
                        }


                        // делаем миниатюру
                        if( $options['sizes']['thumb'] )
                        {
                            if( $ret = $this->imgResizeToRect($_FILES[$img]["tmp_name"]
                                    , MainConfig::$DOC_ROOT . $path . DS . $fiId . "tb.{$ext}", "image/jpeg", $options['sizes']['thumb']) > 0
                                )
                                    $_SESSION['uploaduniFiles'][$options['scope']][$fiId]['files']['tb'] = $path . DS . $fiId . "tb.{$ext}";
                        }

                        $_SESSION['uploaduniFiles'][$options['scope']][$fiId] = array_merge($_SESSION['uploaduniFiles'][$options['scope']][$fiId], array(
                            'meta' => array(
                                'name' => $_FILES[$img]["name"],
                                'type' => 'images',
                                'ext' => $ext,
                            ),
                            'extmeta' => $meta,
                        ));
                    } // endif


                // Обрабатываем файлы
                } elseif( $type[0] == 'files' )
                {
                    if( copy($_FILES[$img]["tmp_name"], MainConfig::$DOC_ROOT . $path . DS . $fiId . ".{$ext}") )
                    {
                        if( $options['removeProtected'] ) $file = str_replace('protected/', '', $path . DS . $fiId . ".{$ext}");
                        else $file = $path . DS . $fiId . ".{$ext}";
                        $_SESSION['uploaduniFiles'][$options['scope']][$fiId] = array(
                            'files' => array('orig' => $file, 'origProtected' => $path . DS . $fiId . ".{$ext}"),
                            'meta' => array(
                                'name' => $_FILES[$img]["name"],
                                'type' => 'files',
                                'ext' => $ext,
                            ),
                            'extmeta' => $meta,
                        );
                    }
                }
                else
                {
                    throw new Exception('Неправильный тип файла', -103);
                } // endif
            } // endif
        }
        catch (Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $flag = 1;
        } // endtry




        if( $flag )
        {
            $ret = array('error' => $code, 'message' => $message, 'ret' => $ret);
        }
        else
        {
//            if( $options['removeProtected'] ) foreach ($_SESSION['uploaduniFiles'][$options['scope']] as $key => &$val) unset($val['origProtected']);
            $ret = array('error' => 100, 'id' => $fiId, 'file' => $_SESSION['uploaduniFiles'][$options['scope']][$fiId], 'files' => $_SESSION['uploaduniFiles'][$options['scope']]);
        } // endif
        return $ret;
    }


    /**
     * удаление файлов
     * @deprecated
     */
    public function deleteFile()
    {
        $id = Yii::app()->getRequest()->getParam('id');

        foreach ($_SESSION['uploaduni'] as $key => $val)
        {
            if( $key == $id )
            {
                foreach ($val as $val2)
                {
                    unlink(MainConfig::$DOC_ROOT . $val2);
                } // end foreach
            }
            else
            {
            } // endif
        } // end foreach

        return $ret = array('error' => 100, 'id' => 1, 'files' => $_SESSION['uploaduni']);
    }



    /**
     * удаление файлов для нового загрузчика
     */
    public function deleteFileEx()
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $options = $this->getCustomOptions();

        foreach ($_SESSION['uploaduniFiles'][$options['scope']] as $key => $val)
        {
            if( $key == $id )
            {
                foreach ($val['files'] as $val2)
                {
                    if( file_exists(MainConfig::$DOC_ROOT . $val2) )
                    {
                        unlink(MainConfig::$DOC_ROOT . $val2);
                    }
                } // end foreach

                unset($_SESSION['uploaduniFiles'][$options['scope']][$key]);
            }
            else
            {
            } // endif
        } // end foreach

        return $ret = array('error' => 100, 'id' => 1, 'files' => $_SESSION['uploaduniFiles'][$options['scope']]);
    }


    /**
     * Обрезка лого
     */
    public function processCropLogo()
    {
        $x1 = filter_var(Yii::app()->getRequest()->getParam('x1'), FILTER_SANITIZE_NUMBER_INT);
        $y1 = filter_var(Yii::app()->getRequest()->getParam('y1'), FILTER_SANITIZE_NUMBER_INT);
        $width = filter_var(Yii::app()->getRequest()->getParam('width'), FILTER_SANITIZE_NUMBER_INT);
        $height = filter_var(Yii::app()->getRequest()->getParam('height'), FILTER_SANITIZE_NUMBER_INT);

        $message = "Ошибка обработки файла";
        $pathTmp = Yii::app()->session['uplLogo']['path'];
        $file = Yii::app()->session['uplLogo']['file'];
        if( file_exists(MainConfig::$DOC_ROOT . $pathTmp . $file) )
        {
            $path = "/images/{$this->imgPath}/";
            $res = $this->imgCrop(MainConfig::$DOC_ROOT . $pathTmp . $file, MainConfig::$DOC_ROOT . $path . $file,
                    array('x1' => $x1,
                        'y1' => $y1,
                        'width' => $width,
                        'height' => $height,
                        )
                );

            // создаем thumb
            $this->imgResizeToRect(MainConfig::$DOC_ROOT . $path . $file, MainConfig::$DOC_ROOT . $path . 'thumbs/' . $file, "image/jpeg", 70);
            // copy orig
            $pathinfo = pathinfo(MainConfig::$DOC_ROOT . $pathTmp . $file);
            copy(MainConfig::$DOC_ROOT . $pathTmp . $file, MainConfig::$DOC_ROOT . $path . $pathinfo['filename'] . 'o.' . $pathinfo['extension']);
        }
        else
        {
            $flag = 1;
        } // endif


        if( $flag )
        {
            return array('error' => 1, 'message' => $message);
        }
        else
        {
            return array('error' => 0, 'file' => $file, 'res' => $res);
        } // endif
    }



    /**
     * Получаем установленный опции загрузчика
     */
    public function getCustomOptions()
    {
        $options = array_merge($this->defOptions, Yii::app()->session['uploaduni_opts']);
        $options['type'] = array_map(function($v) { return explode(',', $v); }, $options['type']);
        return $options;
    }


    /**
     * Устанавливаем опции загрузчика
     */
    public function setCustomOptions($inOpts)
    {
        Yii::app()->session['uploaduni_opts'] = $inOpts;
    }


    /**
     * Отдаем загруженные файлы
     */
    public function getUploadedFiles($props)
    {
        return Yii::app()->session['uploaduniFiles'] ? Yii::app()->session['uploaduniFiles'][$props['scope']] : '';
    }



    /**
     * Убираем несуществующие файлы из хранилища
     */
    public function removeUnExistedFiles($inProps)
    {
        $files = $_SESSION['uploaduniFiles'][$inProps['scope']];

        $_SESSION['uploaduniFiles'][$inProps['scope']] = array();

        foreach ($files as $key => $val2)
        {
            if( $val2['files'][array_keys($val2['files'])[0]]
                && $val2['meta']['type'] == 'files'
                && file_exists(MainConfig::$DOC_ROOT . $val2['files']['origProtected'])
                || $val2['files'][array_keys($val2['files'])[0]]
                && $val2['meta']['type'] == 'images'
                && file_exists(MainConfig::$DOC_ROOT . $val2['files'][array_keys($val2['files'])[0]]) )
                    $_SESSION['uploaduniFiles'][$inProps['scope']][$key] = $val2;
        } // end foreach
    }



    private function imgResizeToRect($inFile, $inOutFile, $inOutType, $inSize = 1600 )
    {
        // *** настройки ***
//        $width = $inSize;
//        $height = $height_def;
        $quality = 90;

        // Get new dimensions
        $imgProps = getimagesize($inFile);
        list($widthOrig, $heightOrig) = $imgProps;
        $type = $imgProps['mime'];

        $ratioOrig = $widthOrig/$heightOrig;

        // альбомный
        if( $ratioOrig > 1 )
        {
            if( $widthOrig > $inSize )
            {
                $width = $inSize;
                $height = $width / $ratioOrig;
            }
            else
            {
                $width = $widthOrig;
                $height = $heightOrig;
            } // endif

        // портретный
        } else {
            if( $heightOrig > $inSize )
            {
                $height = $inSize;
                $width = $height * $ratioOrig;
            }
            else
            {
                $width = $widthOrig;
                $height = $heightOrig;
            } // endif
        } // endif


        // Resample
        if($image_p = imagecreatetruecolor($width, $height)) $error = false; else return -101;

            switch($type) {
               case "image/jpeg" : if($image = imagecreatefromjpeg($inFile)) $error = false; else return -102; break;

               case "image/pjpeg" : if($image = imagecreatefromjpeg($inFile)) $error = false; else return -103; break;

               case "image/png": if($image = imagecreatefrompng($inFile)) $error = false; else return -104; $pngflag = 1; break;
               case "image/x-png": if($image = imagecreatefrompng($inFile)) $error = false; else return -105; $pngflag = 1; break;

               case "image/gif": if($image = imagecreatefromgif($inFile)) $error = false; else return -106; break;

               default: return "bad switch type=$type"; break;
            }

//        if( ($widthOrig > $width) || ($heightOrig > $height) )
//        else $image_p = $image;
        if(imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig)) $error = false; else return -107;

        // Output
        switch($inOutType) {
           case "image/jpeg" : if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return -108; break;

           case "image/pjpeg" : if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return -109; break;

           case "image/png":
           case "image/x-png": if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return -110; break;

           case "image/gif": if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return -111; break;
        }

        imagedestroy($image_p);

        imagedestroy($image);

        return 1;
    }



    private function imgCrop($inFile, $inOutFile, $inCropOpts)
    {
        // *** настройки ***
//        $width = $inSize;
//        $height = $height_def;
        $quality = 90;

        // Get new dimensions
        $imgProps = getimagesize($inFile);
        list($widthOrig, $heightOrig) = $imgProps;

        $ratioOrig = $widthOrig/$heightOrig;

        $x1 = $inCropOpts['x1'] ?: 0;
        $y1 = $inCropOpts['y1'] ?: 0;

        $width = $inCropOpts['width'] ?: $widthOrig;
        $height = $inCropOpts['height'] ?: $heightOrig;

        if( $x1 > $widthOrig ) $x1 = 0;
        if( $y1 > $heightOrig ) $y1 = 0;
        if( $inCropOpts['x1'] + $width < $widthOrig ) $x2 = $x1 + $width;
        else $x2 = $widthOrig;
        if( $inCropOpts['y1'] + $height < $heightOrig ) $y2 = $y1 + $height;
        else $y2 = $heightOrig;

        $width = $x2 - $x1;
        $height = $y2 - $y1;
        $width = $width < $height ? $width : $height;


        // Resample
        if($image_p = imagecreatetruecolor($width, $width)) $error = false; else return -101;
        if($image = imagecreatefromjpeg($inFile)) $error = false; else return -103;

//        if(imagecopyresampled($image_p, $image, 0, 0, $x1, $y1, $width, $width, $width, $width)) $error = false; else return -107;
        imagecopy($image_p, $image, 0, 0, $x1, $y1, $width, $width);

        // Output
        if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return -108;

        imagedestroy($image_p);
        imagedestroy($image);

        return 1;
    }
}