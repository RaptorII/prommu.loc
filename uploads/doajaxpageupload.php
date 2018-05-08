<?php

function imageresize($outfile,$infile,$percents,$quality) {
    $im=imagecreatefromjpeg($infile);

    $h=48;
    $percents = $h / imagesy($im) * 100;
    $w=imagesx($im)*$percents/100;
    $im1=imagecreatetruecolor($w,$h);
    imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

    imagejpeg($im1,$outfile,$quality);
    imagedestroy($im);
    imagedestroy($im1);
}


function resize($image, $w_o = false, $h_o = false, $pagetype) {
    if (($w_o < 0) || ($h_o < 0)) {
        echo "Некорректные входные параметры";
        return false;
    }
    list($w_i, $h_i, $type) = getimagesize($_SERVER['DOCUMENT_ROOT']."/images/".$pagetype."/".$image); // Получаем размеры и тип изображения (число)
    $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
    $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
        $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
        $img_i = $func($_SERVER['DOCUMENT_ROOT']."/images/$pagetype/$image"); // Создаём дескриптор для работы с исходным изображением
    } else {
        echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
        return false;
    }
    /* Если указать только 1 параметр, то второй подстроится пропорционально */
    if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
    if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
    imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    return $func($img_o, $_SERVER['DOCUMENT_ROOT']."/images/$pagetype/thumbs/$image"); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
}


function resize_origin($image, $w_o = false, $h_o = false) {
    if (($w_o < 0) || ($h_o < 0)) {
        echo "Некорректные входные параметры";
        return false;
    }
    list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
    $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
    $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
        $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
        $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
    } else {
        echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
        return false;
    }
    /* Если указать только 1 параметр, то второй подстроится пропорционально */
    if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
    if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
    imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
}

$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
$pagetype = empty($_GET['pagetype']) ? 'pages' : $_GET['pagetype'];

if(!empty($_FILES[$fileElementName]['error']))
{
    switch($_FILES[$fileElementName]['error'])
    {

        case '1':
            $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            break;
        case '2':
            $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            break;
        case '3':
            $error = 'The uploaded file was only partially uploaded';
            break;
        case '4':
            $error = 'No file was uploaded.';
            break;

        case '6':
            $error = 'Missing a temporary folder';
            break;
        case '7':
            $error = 'Failed to write file to disk';
            break;
        case '8':
            $error = 'File upload stopped by extension';
            break;
        case '999':
        default:
            $error = 'No error code avaiable';
    }
}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
{
    $error = 'No file was uploaded..';
}else
{
    //$msg .= " <span> File Name: " . $_FILES['fileToUpload']['name'] . "</span><br/> ";
    $msg .= " <div class='imgcontent'><p><b>Размер файла:</b> " . @filesize($_FILES['fileToUpload']['tmp_name']);
    //формируем имя уникальное файла
    $fname =  $_FILES['fileToUpload']['name'];
    $ext = substr($_FILES['fileToUpload']['name'], 1 + strrpos($_FILES['fileToUpload']['name'], "."));
    //$ext = '.jpg';
    $apend=date('YmdHis').rand(100,1000).".$ext";
    //for security reason, we force to remove all uploaded file
    //@unlink($_FILES['fileToUpload']);
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/images/'.$pagetype.'/'.$apend);

    if($ext=='jpg' || $ext=='jpeg') {
        //imageresize($_SERVER['DOCUMENT_ROOT']."/content/thumbs/".$apend,$_SERVER['DOCUMENT_ROOT']."/content/".$apend,30,75);
        resize($apend, 300, false, $pagetype);

        // сжимаем оригинал до 300
        resize_origin($_SERVER['DOCUMENT_ROOT']."/images/".$pagetype."/".$apend, 600);

        // $this->imgResizeToRect($_SERVER['DOCUMENT_ROOT']."/images/".$pagetype."/".$apend, $_SERVER['DOCUMENT_ROOT']."/images/".$pagetype."/".$apend.'100.jpg', "image/jpeg", 100);
            // $this->imgResizeToRect(MainConfig::$DOC_ROOT . $pathTmp . "{$pathinfo['filename']}tt.jpg", MainConfig::$DOC_ROOT . $pathTmp . $pathinfo['filename'] . '400.jpg', "image/jpeg", 400);
            // // copy orig
            // $this->saveAsJpeg(MainConfig::$DOC_ROOT . $pathTmp . $file, MainConfig::$DOC_ROOT . $pathTmp . $pathinfo['filename'] . '000.jpg');

            // unlink($_SERVER['DOCUMENT_ROOT']."/images/".$pagetype."/".$apend);
    }

    $msg .= " <b>Имя файла:</b> /images/$pagetype/$apend</p>";
    $msg .= '</div>';
}

function imgResizeToRect($inFile, $inOutFile, $inOutType = "image/jpeg", $inSize = 1600 )
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

   
    }
echo "{";
echo			 "error: '" . $error . "',\n";
echo			 "msg: '" . $msg . "',\n";
echo      "name: '".$apend."'\n";
echo "}";
?>