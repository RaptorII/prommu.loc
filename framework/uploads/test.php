<?php
$p = $_GET['cmd'];
header("Content-type: application/json");
echo '{"msg":"'.$p.'"}';
?>