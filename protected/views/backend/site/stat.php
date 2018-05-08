
<?php
 $data = array(
	'token'=> "26ea993ded58b4a2b55c15ad2268a467",
	);
 $data = array('data'=>$data);
$data = json_encode($data);
'data'=>$data;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,'http://dev.prommu.com/api.chat_theme_get');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,  array());
$server_output = curl_exec ($ch);
$result = curl_exec($ch );
$info = curl_getinfo($ch );
$error = curl_error($ch );
$data = json_decode($result, true);

echo $result;

?>