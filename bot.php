<? 

$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatIDs = $update["message"]["chat"]["id"];
$order = 0;
file_put_contents('/var/www/html/telegram.txt', date('d.m.Y H:i')."\t".$update["message"]["chat"]."\t".$update."\t".$chatIDs."\n", FILE_APPEND | LOCK_EX);

  $chatID = -1001193706849;
                        $botkey = "525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8";
                       $msg = "awd";
                        $replyMarkup['keyboard'][] = array("awd");
                
                       $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$msg";
                       file_get_contents($sendto);

?>