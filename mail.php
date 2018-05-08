<?php
  function sendEmail($to, $uid)
  {
    $subject = "Подтверждение регистрации на сайте promo";
    
    $message = ' 
<html> 
    <head> 
        <title>'.$subject.'</title> 
    </head> 
    <body> 
        <p>Для активации вашего аккаунта на сайте <b>Временная работа для студентов</b> перейдите по ссылке <a href="http://prommu.com/?r=site/Activate&uid='.$uid.'">ключ активации '.$uid.'</a></p> 
    </body> 
</html>';  
    
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    $headers .= "From: administrator <noreplay@prommu.com>\r\n"; 

    $res = mail($to, $subject, $message, $headers); 
    return $res;
  }
?>