<?php
function saveFile($filename, $binary)
{
$err = 0;
if(!$file = fopen($filename, 'wb')){
    //echo 'Image upload Fail!'."\n";
    return $err=1;
}
else
{
    fwrite($file, $binary);
    fclose($file);
}    
return $err;
}

$file = $_POST["file"];
$binary=base64_decode($file);
$fname = date('YmdHis').rand(100,1000).".jpg";
$err = saveFile('/var/www/main/content/'.$fname, $binary);
echo '{"err":"'.$err.'", "name":"'.$fname.'"}';
?>