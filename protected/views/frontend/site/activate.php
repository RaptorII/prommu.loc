<div style="width:300px; height:390px; 
color:#A0A0A0;
background: url(/images/bg-note.png);  
font-family:Georgia;
font-size:18pt; font-style:italic;
text-align:center; 
 margin: 0 auto;
    padding:70px 20px 20px 20px;
">
<?php
if($vmode==0) {
echo "Пользователь <br/><b>$fio;</b>, <br/>поздравляем вас. Активация прошла успешно. <br/><br/>Для продолжения работы введите <br/>логин | пароль";
} else {
echo "Ключ активации <b style='font-size:12pt;'>$uid</b> не действителен";
}
?>
</div>