<?php 

class YandexTranslate {

public function translate($cloud, $lang){

		$yt_api_key = "trnsl.1.1.20170904T130938Z.132da9d87b6e179a.026d3f74d94b54f300971b233b3e7243779547d8"; 
		$yt_lang = $lang;
		$yt_text = $cloud;
		$yt_link = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=".$yt_api_key."&text=".$yt_text."&lang=".$yt_lang;
		$result = file_get_contents($yt_link); 
		$result = json_decode($result, true); 
		 return $en_test = $result['text'][0]; 
	}
}

?>