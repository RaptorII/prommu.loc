<?php
/**
 * Date: 16.05.2016
 *
 * Модель загрузки лого на сайт
 */

class UploadLogo extends Model
{
    private $imgPath;


    function __construct()
    {
        parent::__construct();

        if( Share::$UserProfile->type == 2 ) $this->imgPath = "applic";
        else $this->imgPath = "company";
    }



    /**
     * загрузка фото
     */
    public function processUploadedLogoFile()
    {
        $message = "Ошибка загрузки файла, обновите страницу и попробуйте еще раз";
        if( $_FILES['photo']['size'] > 5242880 || $_FILES['photo']['size'] == 0 ) $ret = array('error' => 1, 'message' => 'Неправильный размер файла!');
        else
        {
            $ext = substr($_FILES['photo']['name'], 1 + strrpos($_FILES['photo']['name'], "."));
            $fn = date('YmdHis').rand(100,1000) . ".jpg";
            $path = "/images/company/tmp/";
            $newFullFn = Subdomain::domainRoot() . $path . $fn;

            if( move_uploaded_file($_FILES["photo"]["tmp_name"], $newFullFn) )
            {
                $imgProps = getimagesize($newFullFn);
                if( $imgProps[0] > 4500 || $imgProps[1] > 4500 )
                {
                    $flag = 1;
                    $message = "Изображение превышает размер в 4500х4500 пикселей";
                }
                elseif( $imgProps[0] < 400 || $imgProps[1] < 400 ){
                    $flag = 1;
                    $message = "Минимальное разрешение изображения - 400x400 пикселей";
                }
                else
                {
                    $defSize = 1600;
                    if( (int) ($ret = $this->imgResizeToRect($newFullFn, $newFullFn, "image/jpeg", $defSize)) > 0 )
                    {

                    }
                    else
                    {
                    } // endif;
                } // endif
            }
            else
            {
                $flag = 1;
                $code = -101;
            } // endif


            if( $flag )
            {
                $ret = array('error' => 1, 'message' => $message, 'ret' => $ret, 'code' => $code);
            }
            else
            {
                Yii::app()->session['uplLogo'] = array('path' => "/images/{$this->imgPath}/tmp/", 'file' => $fn);
                    $arRes = array('error' => 0, 'file' => "/images/{$this->imgPath}/tmp/" . $fn);
            } // endif
        } // endif
//        $sql = "SELECT s.id, s.name, DATE_FORMAT(s.crdate, '%d.%m.%Y') crdate
//                FROM services s WHERE s.id = {$id}";
//        Yii::app()->db->createCommand($sql)->queryRow();
        return $ret;
    }



    /**
     * Обрезка лого
     */
    public function processCropLogo()
    {
        if( Share::$UserProfile->type == 2 ) return $this->processCropLogoApplicant();
        else return $this->processCropLogoEmpl();
    }

    public function processUploadedLogoEmpl()
    {
        
        $message = "Ошибка загрузки файла, обновите страницу и попробуйте еще раз";
        if( $_FILES['photo']['size'] > 5242880 || $_FILES['photo']['size'] == 0 ) $ret = array('error' => 1, 'message' => 'Неправильный размер файла!');
        else
        {
            $ext = substr($_FILES['photo']['name'], 1 + strrpos($_FILES['photo']['name'], "."));
            $fn = date('YmdHis').rand(100,1000) . ".jpg";
            $path = "/images/company/tmp/";
            $newFullFn = Subdomain::domainRoot() . $path . $fn;
            if( move_uploaded_file($_FILES["photo"]["tmp_name"], $newFullFn) )
            {
                $imgProps = getimagesize($newFullFn);
                if( $imgProps[0] > 4500 || $imgProps[1] > 4500 )
                {
                    $flag = 1;
                    $message = "Изображение превышает размер в 4500х4500 пикселей";
                }
                elseif( $imgProps[0] < 400 || $imgProps[1] < 400 ){
                    $flag = 1;
                    $message = "Минимальное разрешение изображения - 400x400 пикселей";
                }
                else
                {
                    $defSize = 1600;
                    if( (int) ($ret = $this->imgResizeToRect($newFullFn, $newFullFn, "image/jpeg", $defSize)) > 0 )
                    {
                    }
                    else
                    {
                    } // endif;
                } // endif
            }
            else
            {
                $flag = 1;
                $code = -101;
            } // endif
            if( $flag )
            {
                $ret = array('error' => 1, 'message' => $message, 'ret' => $ret, 'code' => $code);
            }
            else
            {
                Yii::app()->session['uplLogo'] = array('path' => "/images/company/tmp/", 'file' => $fn);
                $ret = array('error' => 0, 'file' => "/images/company/tmp/" . $fn);
            } // endif
        } // endif

        $message = "WARNING!";
        $pathTmp = $path;
        $file =  $fn;
        if( file_exists(Subdomain::domainRoot() . $pathTmp . $file) )
        {
            $path = "/images/company/";
            $res = $this->imgCrop(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $file,
                    array('x1' => 0,
                        'y1' => 0,
                        'width' => 400,
                        'height' => 400,
                        'rotate' => 0
                        )
                );
            $pathinfo = pathinfo(Subdomain::domainRoot() . $pathTmp . $file);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename']  . '100.jpg', "image/jpeg", 220);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename']  . '400.jpg', "image/jpeg", 400);
            $this->saveAsJpeg(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '000.jpg');

            unlink(Subdomain::domainRoot() . $path . $file);
        }
        else
        {
            $flag = 1;
        } 


        return $pathinfo['filename'];
    }


    public function processUploadedLogoPromo()
    {
        
        $message = "Ошибка загрузки файла, обновите страницу и попробуйте еще раз";
        if( $_FILES['photo']['size'] > 5242880 || $_FILES['photo']['size'] == 0 ) $ret = array('error' => 1, 'message' => 'Неправильный размер файла!');
        else
        {
            $ext = substr($_FILES['photo']['name'], 1 + strrpos($_FILES['photo']['name'], "."));
            $fn = date('YmdHis').rand(100,1000) . ".jpg";
            $path = "/images/applic/tmp/";
            $newFullFn = Subdomain::domainRoot() . $path . $fn;
            if( move_uploaded_file($_FILES["photo"]["tmp_name"], $newFullFn) )
            {
                $imgProps = getimagesize($newFullFn);
                if( $imgProps[0] > 4500 || $imgProps[1] > 4500 )
                {
                    $flag = 1;
                    $message = "Изображение превышает размер в 4500х4500 пикселей";
                }
                elseif( $imgProps[0] < 400 || $imgProps[1] < 400 ){
                    $flag = 1;
                    $message = "Минимальное разрешение изображения - 400x400 пикселей";
                }
                else
                {
                    $defSize = 1600;
                    if( (int) ($ret = $this->imgResizeToRect($newFullFn, $newFullFn, "image/jpeg", $defSize)) > 0 )
                    {
                    }
                    else
                    {
                    } // endif;
                } // endif
            }
            else
            {
                $flag = 1;
                $code = -101;
            } // endif
            if( $flag )
            {
                $ret = array('error' => 1, 'message' => $message, 'ret' => $ret, 'code' => $code);
            }
            else
            {
                Yii::app()->session['uplLogo'] = array('path' => "/images/applic/tmp/", 'file' => $fn);
                $ret = array('error' => 0, 'file' => "/images/applic/tmp/" . $fn);
            } // endif
        } // endif

        $message = "WARNING!";
        $pathTmp = $path;
        $file =  $fn;
        if( file_exists(Subdomain::domainRoot() . $pathTmp . $file) )
        {
            $path = "/images/applic/";
            $res = $this->imgCrop(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $file,
                    array('x1' => 0,
                        'y1' => 0,
                        'width' => 400,
                        'height' => 400,
                        'rotate' => 0
                        )
                );
            $pathinfo = pathinfo(Subdomain::domainRoot() . $pathTmp . $file);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename']  . '100.jpg', "image/jpeg", 220);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename']  . '225.jpg', "image/jpeg", 225);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename']  . '400.jpg', "image/jpeg", 400);
            $this->saveAsJpeg(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '000.jpg');

            unlink(Subdomain::domainRoot() . $path . $file);
        }
        else
        {
            $flag = 1;
        } 


        return $pathinfo['filename'];
    }

    /**
     * Обрезка лого для соискателя
     */
    private function processCropLogoApplicant()
    {
        $x1 = (int)filter_var(Yii::app()->getRequest()->getParam('x'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $y1 = (int)filter_var(Yii::app()->getRequest()->getParam('y'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $width = (int)filter_var(Yii::app()->getRequest()->getParam('width'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $height = (int)filter_var(Yii::app()->getRequest()->getParam('height'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $rotate = (int)filter_var(Yii::app()->getRequest()->getParam('rotate'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $message = "Ошибка обработки файла";
        $pathTmp = Yii::app()->session['uplLogo']['path'];
        $file = Yii::app()->session['uplLogo']['file'];
        if( file_exists(Subdomain::domainRoot() . $pathTmp . $file) )
        {
            $path = "/images/{$this->imgPath}/";
            $res = $this->imgCrop(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $file,
                    array('x1' => $x1,
                        'y1' => $y1,
                        'width' => $width,
                        'height' => $height,
                        'rotate' => $rotate
                        )
                );

            // создаем thumb
            $pathinfo = pathinfo(Subdomain::domainRoot() . $pathTmp . $file);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '100.jpg', "image/jpeg", 220);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '169.jpg', "image/jpeg", 169);
            $this->imgResizeToRect(Subdomain::domainRoot() . $path . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '400.jpg', "image/jpeg", 400);
            // copy orig
//            copy(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '000.' . $pathinfo['extension']);
            $this->saveAsJpeg(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $path . $pathinfo['filename'] . '000.jpg', $rotate);

            unlink(Subdomain::domainRoot() . $path . $file);
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
     * Обрезка лого для работодателя
     */
    private function processCropLogoEmpl()
    {
        $x1 = (int)filter_var(Yii::app()->getRequest()->getParam('x'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $y1 = (int)filter_var(Yii::app()->getRequest()->getParam('y'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $width = (int)filter_var(Yii::app()->getRequest()->getParam('width'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $height = (int)filter_var(Yii::app()->getRequest()->getParam('height'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $rotate = (int)filter_var(Yii::app()->getRequest()->getParam('rotate'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $message = "Ошибка обработки файла";
        $pathTmp = Yii::app()->session['uplLogo']['path'];
        $file = Yii::app()->session['uplLogo']['file'];

        if( file_exists(Subdomain::domainRoot() . $pathTmp . $file) )
        {
            $pathinfo = pathinfo(Subdomain::domainRoot() . $pathTmp . $file);
            $res = $this->imgCrop(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $pathTmp . "{$pathinfo['filename']}tt.jpg",
                    array('x1' => $x1,
                        'y1' => $y1,
                        'width' => $width,
                        'height' => $height,
                        'rotate' => $rotate
                        )
                );

            // создаем thumb
            $this->imgResizeToRect(Subdomain::domainRoot() . $pathTmp . "{$pathinfo['filename']}tt.jpg", Subdomain::domainRoot() . $pathTmp . $pathinfo['filename'] . '100.jpg', "image/jpeg", 220);
            $this->imgResizeToRect(Subdomain::domainRoot() . $pathTmp . "{$pathinfo['filename']}tt.jpg", Subdomain::domainRoot() . $pathTmp . $pathinfo['filename'] . '400.jpg', "image/jpeg", 400);
            $this->imgResizeToRect(Subdomain::domainRoot() . $pathTmp . "{$pathinfo['filename']}tt.jpg", Subdomain::domainRoot() . $pathTmp . $pathinfo['filename'] . '169.jpg', "image/jpeg", 169);
            $this->imgResizeToRect(Subdomain::domainRoot() . $pathTmp . "{$pathinfo['filename']}tt.jpg", Subdomain::domainRoot() . $pathTmp . $pathinfo['filename'] . '30.jpg', "image/jpeg", 30);
            // copy orig
            $this->saveAsJpeg(Subdomain::domainRoot() . $pathTmp . $file, Subdomain::domainRoot() . $pathTmp . $pathinfo['filename'] . '000.jpg', $rotate);

            unlink(Subdomain::domainRoot() . $pathTmp . "{$pathinfo['filename']}tt.jpg");
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

            return array('error' => 0, 'file' => $pathinfo['filename'] . '.jpg', 'idfile' => $pathinfo['filename'], 'res' => $res);
        } // endif
    }



    /**
     * Удалить все файлы определенного фото соискателя
     */
    public function delPhoto($inId)
    {
        if(Share::$UserProfile->type == 2) // applicant
            $path = "/images/{$this->imgPath}/";
        else
            $path = "/images/{$this->imgPath}/tmp/";

        file_exists(Subdomain::domainRoot() . $path . $inId . '.jpg') && unlink(Subdomain::domainRoot() . $path . $inId . '.jpg');
        file_exists(Subdomain::domainRoot() . $path . $inId . '30.jpg') && unlink(Subdomain::domainRoot() . $path . $inId . '30.jpg');
        file_exists(Subdomain::domainRoot() . $path . $inId . '100.jpg') && unlink(Subdomain::domainRoot() . $path . $inId . '100.jpg');
        file_exists(Subdomain::domainRoot() . $path . $inId . '169.jpg') && unlink(Subdomain::domainRoot() . $path . $inId . '169.jpg');
        file_exists(Subdomain::domainRoot() . $path . $inId . '400.jpg') &&  unlink(Subdomain::domainRoot() . $path . $inId . '400.jpg');
        file_exists(Subdomain::domainRoot() . $path . $inId . '000.jpg') &&  unlink(Subdomain::domainRoot() . $path . $inId . '000.jpg');
    }



    public function imgResizeToRect($inFile, $inOutFile, $inOutType = "image/jpeg", $inSize = 1600 )
    {
        // *** настройки ***
//        $width = $inSize;
//        $height = $height_def;
        $quality = 50;

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
        if($image_p = imagecreatetruecolor($width, $height)) $error = false; else return "-101";

            switch($type) {
               case "image/jpeg" : if($image = imagecreatefromjpeg($inFile)) $error = false; else return "-102"; break;

               case "image/pjpeg" : if($image = imagecreatefromjpeg($inFile)) $error = false; else return "-103"; break;

               case "image/png": if($image = imagecreatefrompng($inFile)) $error = false; else return "-104"; $pngflag = 1; break;
               case "image/x-png": if($image = imagecreatefrompng($inFile)) $error = false; else return "-105"; $pngflag = 1; break;

               case "image/gif": if($image = imagecreatefromgif($inFile)) $error = false; else return "-106"; break;

               default: return "bad switch type=$type"; break;
            }

//        if( ($widthOrig > $width) || ($heightOrig > $height) )
//        else $image_p = $image;
        if(imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig)) $error = false; else return "-107";

        // Output
        switch($inOutType) {
           case "image/jpeg" : if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return "-108"; break;

           case "image/pjpeg" : if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return "-109"; break;

           case "image/png":
           case "image/x-png": if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return "-110"; break;

           case "image/gif": if(imagejpeg($image_p, $inOutFile, $quality)) $error = false; else return "-111"; break;
        }

        imagedestroy($image_p);

        imagedestroy($image);

        return "1";
    }



    private function imgCrop($inFile, $inOutFile, $inCropOpts)
    {
        $quality = 90;
        // Get new dimensions
        $imgProps = getimagesize($inFile);

        switch($imgProps['mime']){
           case "image/jpeg" : $src_img=imagecreatefromjpeg($inFile); break;
           case "image/pjpeg" : $src_img=imagecreatefromjpeg($inFile); break;
           case "image/png": $src_img=imagecreatefrompng($inFile); break;
           case "image/x-png": $src_img=imagecreatefrompng($inFile); break;
           default: return "bad switch type=".$imgProps['mime'];
        }
        if(!$src_img){ return "Failed to read the image file"; }
        else{ $error = false; }

        list($size_w, $size_h) = $imgProps;// natural width and height
        $src_img_w = $size_w;
        $src_img_h = $size_h;
        $tmp_img_w = $inCropOpts['width'];
        $tmp_img_h = $inCropOpts['height'];
        $degrees = $inCropOpts['rotate'];
        $src_x = $inCropOpts['x1'];
        $src_y = $inCropOpts['y1'];  

        // Rotate the source image
        if(is_numeric($degrees) && $degrees != 0){
            // PHP's degrees is opposite to CSS's degrees
            $new_img = imagerotate( $src_img, -$degrees, 0);
            imagedestroy($src_img);
            $src_img = $new_img;
            $deg = abs($degrees) % 180;
            $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
            $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
            $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);
            // Fix rotated image miss 1px issue when degrees < 0
            $src_img_w -= 1;
            $src_img_h -= 1;
        }

        if($src_x <= -$tmp_img_w || $src_x > $src_img_w){
            $src_x = $src_w = $dst_x = $dst_w = 0;
        } 
        elseif($src_x <= 0){
            $dst_x = -$src_x;
            $src_x = 0;
            $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
        } 
        elseif($src_x <= $src_img_w){
            $dst_x = 0;
            $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
        }
        if($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h){
            $src_y = $src_h = $dst_y = $dst_h = 0;
        }
        elseif($src_y <= 0){
            $dst_y = -$src_y;
            $src_y = 0;
            $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
        }
        elseif($src_y <= $src_img_h){
            $dst_y = 0;
            $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
        }
        // Scale to destination position and size
        $ratio = $tmp_img_w / $dst_w;
        $dst_x /= $ratio;
        $dst_y /= $ratio;
        $dst_w /= $ratio;
        $dst_h /= $ratio;

        $dst_img = imagecreatetruecolor($dst_w, $dst_h);
        // Add transparent background to destination image
        imagefill($dst_img, 0, 0, imagecolorallocate($dst_img, 255, 255, 255));

        $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
        if($result){
            if(!imagejpeg($dst_img, $inOutFile, $quality)){
                return "Failed to save the cropped image file";
            }
        }
        else{
            return "Failed to crop the image file";
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return "1";
    }



    /**
     * Сохраняем в JPEG формате
     */
    private function saveAsJpeg($inFile, $inOutFile, $rotate=0)
    {
        $quality = 90;
        // Get new dimensions
        $imgProps = getimagesize($inFile);
        list($widthOrig, $heightOrig) = $imgProps;
        $type = $imgProps['mime'];

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

        // open source file
        switch($type) {
           case "image/jpeg" : if($image = imagecreatefromjpeg($inFile)) $error = false; else return "-102"; break;
           case "image/pjpeg" : if($image = imagecreatefromjpeg($inFile)) $error = false; else return "-103"; break;
           case "image/png": if($image = imagecreatefrompng($inFile)) $error = false; else return "-104"; $pngflag = 1; break;
           case "image/x-png": if($image = imagecreatefrompng($inFile)) $error = false; else return "-105"; $pngflag = 1; break;
           default: return "bad switch type=$type";
        }

        if(is_numeric($rotate) && $rotate != 0){
            // PHP's degrees is opposite to CSS's degrees
            if($image = imagerotate($image, -$rotate, 0)) $error = false; else return "-109";
        }

        // Output
        if(imagejpeg($image, $inOutFile, $quality)) $error = false; else return "-108";

        imagedestroy($image);

        return "1";
    }



    /**
     * загрузка фото с вебкамеры
     */
    public function processUploadedLogoSnapshot()
    { 
        $message = "Ошибка загрузки файла, обновите страницу и попробуйте еще раз";

        $fn = date('YmdHis').rand(100,1000) . ".jpg";
        $path = "/images/{Share::$UserProfile->id}/{$this->imgPath}/tmp/";
        $newFullFn = '/var/www/file_prommu' . $path . $fn;
        var_dump($newFullFn);
        $d = str_replace('data:image/png;base64,', '', $_POST['data']);
        $d = str_replace(' ', '+', $d);
        $fileData = base64_decode($d);

        $res = file_put_contents($newFullFn, $fileData);

        if($res===false){
            $arRes = array('error' => 1, 'message' => $message);
        }
        else{
            if($res>5242880 || $res==0){
                $arRes = array('error' => 1, 'message' => 'Неправильный размер файла!');
            }
            else{
                $imgProps = getimagesize($newFullFn);
                if( $imgProps[0] > 4500 || $imgProps[1] > 4500 ){
                    $arRes = array('error' => 1, 'message' => 'Изображение превышает размер в 4500х4500 пикселей');
                }
                elseif( $imgProps[0] < 400 || $imgProps[1] < 400 ){
                    $arRes = array('error' => 1, 'message' => 'Минимальное разрешение изображения - 400x400 пикселей');
                }
                else{
                    Yii::app()->session['uplLogo'] = array('path' => "/images/{$this->imgPath}/tmp/", 'file' => $fn);
                    $arRes = array('error' => 0, 'file' => "/images/{$this->imgPath}/tmp/" . $fn);
                }
            }
        }
        return $arRes;
    }
    /*
    *   отправка файла на prommu.com
    */
    private function sendToDomain($sPath, $rPath){
        $post = http_build_query(
            array(
                'path'=> $rPath, // путь на втором сервере
                'img' => base64_encode(file_get_contents($sPath)) // отпровляемый файл
            )
        );
        //опции контекста
        $options = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post
            )
        );
        //отправляем файл на второй сервер и получаем его ответ
        $url = Subdomain::domainSite() . Subdomain::$MAIN_SEND_FILE_URL;
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}