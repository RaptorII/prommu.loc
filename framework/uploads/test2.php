<?php
//SERVER SIDE
$fname = $_GET['cmd'];
//header("Content-Type: application/json");
echo $_GET['callback'] . '(' . "{'msg' : 'Jeff Hansen'}" . ')';
?>
