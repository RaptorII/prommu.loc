<?php

function imageresize($outfile,$infile,$percents,$quality) {
    $im=imagecreatefromjpeg($infile);

    $h=48;
    $percents = $h / imagesy($im) * 100;
    $w=imagesx($im)*$percents/100;
    //imagesy($im)*$percents/100;
    $im1=imagecreatetruecolor($w,$h);
    imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

    imagejpeg($im1,$outfile,$quality);
    imagedestroy($im);
    imagedestroy($im1);
}

	$error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';

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
			move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/content/'.$apend);
      if($ext=='jpg' || $ext=='jpeg') {
        imageresize($_SERVER['DOCUMENT_ROOT']."/content/thumbs/".$apend,$_SERVER['DOCUMENT_ROOT']."/content/".$apend,30,75);
      }
  		$msg .= " <b>Имя файла:</b> /content/$apend</p>";
  		$msg .= '</div>';
	}
	echo "{";
	echo			 "error: '" . $error . "',\n";
	echo			 "msg: '" . $msg . "',\n";
	echo      "name: '".$apend."'\n";
	echo "}";
?>