<?php
$url_files = isset($_GET['url_file']) ? $_GET['url_file'] : '';
$url_sm_files = isset($_GET['url_sm_file']) ? $_GET['url_sm_file'] : '';
$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : 0;
$path = $_SERVER['DOCUMENT_ROOT']."/images/applic/";
$path_sm = $_SERVER['DOCUMENT_ROOT']."/images/applic/thumbs/";


function save_file($pth, $url_files, $nm)
{
if (preg_match("/http/",$url_files)){  
    $ch = curl_init($url_files);  
    curl_setopt($ch, CURLOPT_HEADER, 0);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);  
    $out = curl_exec($ch);  
    $image_sv = $pth.$nm.'.jpg';  
    $img_sc = file_put_contents($image_sv, $out);  
    curl_close($ch);    
}
}


save_file($path, $url_files, md5($id_user));
save_file($path_sm, $url_sm_files, md5($id_user));
echo "OK";
?>