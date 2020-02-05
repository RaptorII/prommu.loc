<?php
class Yandex //extends CActiveRecord
{
  public $filename = 'yandex_job.yvl';
  public $csvMetricFile = 'uploads/yandex_metric.csv';

	public function generateFile()
	{
		/*
		$fullPath = Subdomain::domainRoot() . DS . $this->filename;
		if(!file_exists($fullPath))
			return false;
		*/
		$model = new Vacancy;
		$data = $model->getVacsForYandex();
		if(!$data)
			return false;

		(new Status)->setStatus("yandex_yvl_gen", '');
		//
		$dom = new DOMDocument('1.0', 'utf-8');
		// source
		$source = $dom->createElement('source');
		$source->setAttribute('creation-time', date('c'));
		$source->setAttribute('host', Subdomain::getSiteName());
		// vacancies
		$vacancies = $dom->createElement('vacancies');
		// vacancy items

        echo count($data['main']);
        //display($data['main']);

		foreach ($data['main'] as $id => $v)
		{
            display($v);

			$vacancy = $dom->createElement('vacancy');
			$e = $dom->createElement('url',$v['link']); 				// url
			$vacancy->appendChild($e);
			$e = $dom->createElement('creation-date',$v['crdate']); 	// creation-date
			$vacancy->appendChild($e);
			$e = $dom->createElement('update-date',$v['mdate']); 		// update-date
			$vacancy->appendChild($e);
			$e = $dom->createElement('salary',$v['salary']); 			// salary
			$vacancy->appendChild($e);
			$e = $dom->createElement('currency','руб'); 		// currency
			$vacancy->appendChild($e);
			foreach ($v['category'] as $arCat)
			{
				$category = $dom->createElement('category'); 			   //category
				$e = $dom->createElement('industry',$arCat['industry']);// industry 
				$category->appendChild($e);
				//$e = $dom->createElement('specialization',$arCat['specialization']);// specialization
				//$category->appendChild($e);
				$vacancy->appendChild($category);
			}
			$e = $dom->createElement('job-name',$v['title']); 			// job-name
			$vacancy->appendChild($e);
			$e = $dom->createElement('employment',$v['istemp']); 			// employment
			$vacancy->appendChild($e);
			$e = $dom->createElement('schedule','гибкий'); 			// schedule
			$vacancy->appendChild($e);
            $strClearChar = $this->clearSpclChars($v['requirements']);
			$e = $dom->createElement('description', $strClearChar );      // description
			$vacancy->appendChild($e);
            $strClearChar = $this->clearSpclChars($v['duties']);
			$e = $dom->createElement('duty', $strClearChar ); 			// duty
			$vacancy->appendChild($e);
			$term = $dom->createElement('term');							// term
			/*$e = $dom->createElement('contract','TEST'); 						// contract !!!!!!!!!!!!!!!!!!
			$vacancy->appendChild($e);*/
			$conditions = $v['conditions1'] . $v['conditions2'] . $v['conditions3'];
			$conditions = htmlspecialchars($conditions,ENT_XML1);
            $conditions = $this->clearSpclChars($conditions);
			$e = $dom->createElement('text', $conditions ); 				 // text
			$term->appendChild($e);
			$vacancy->appendChild($term);
			$requirement = $dom->createElement('requirement'); 			 // requirement
			if(!empty($v['age']))
			{
				$e = $dom->createElement('age',$v['age']); 				 // аge
				$requirement->appendChild($e);
			}
			if(!empty($v['sex']))
			{
				$e = $dom->createElement('sex',$v['sex']); 				 // sex
				$requirement->appendChild($e);				
			}
			if(!empty($v['experience']))
			{
				$e = $dom->createElement('experience',$v['experience']);   // experience
				$requirement->appendChild($e);				
			}
			$vacancy->appendChild($requirement);


            if(!empty($v['adresses']))
            {
                $addresses = $dom->createElement('addresses');                        // addresses
                foreach ($v['adresses'] as $arAddress) {
                    $address = $dom->createElement('address');                        // address

                    $e = $dom->createElement('location', $arAddress['location']);// location
                    $address->appendChild($e);

                    if (is_array($arAddress['metro'])) {
                        foreach ($arAddress['metro'] as $m) {
                            $e = $dom->createElement('metro', $m);                   // metro
                            $address->appendChild($e);
                        }
                    }
                    $addresses->appendChild($address);
                }
                $vacancy->appendChild($addresses);
            }


			$employer = $data['employer'][$v['id_user']];

			$company = $dom->createElement('company'); 								// company
			$e = $dom->createElement('name',$employer['company']); 		// name
			$company->appendChild($e);
			if(strlen($employer['aboutcompany']))
			{
				$e = $dom->createElement('description',$employer['aboutcompany']);// description
				$company->appendChild($e);		
			}

			if(isset($employer['logo']))
			{
				$e = $dom->createElement('logo',$employer['logo']); 		// logo
				$company->appendChild($e);		
			}
			if(!empty($employer['site']))
			{
				$e = $dom->createElement('site',$employer['site']); 		// site
				$company->appendChild($e);
			}




			$e = $dom->createElement('hr-agency',$employer['hr-agency']);// hr-agency
			$company->appendChild($e);
			/*if(!empty($employer['name']))
			{
				$e = $dom->createElement('contact-name',$employer['name']); // contact-name
				$company->appendChild($e);
			}*/
			$vacancy->appendChild($company);
			$vacancies->appendChild($vacancy);
		}
		$source->appendChild($vacancies);
		$dom->appendChild($source);
		$result = file_put_contents($this->filename, $dom->saveXML());
		return $result!=='false';
	}

    /**
     * @param $str
     * @return mixed|string
     */
	public function clearSpclChars ($str, $rep = ' ')
    {
        $str = strip_tags(html_entity_decode($str));

        $mask = [
            '&#13;', '&ndash;', '&nbsp;',
            '&lt;br&gt;', '&amp;', 'quot;',
            '&amp;quot;',
            '@', '#', '$',
            '%', '^', '&',
            '*', '(', ')',
            '_', '{', '}',
            '|', ':', '"',
            '<', '>', '?',
            '[', ']', ';',
            "'", '', '~',
            '`', '=',
        ];
        $str = str_replace($mask, '', $str);

        $str = preg_replace("#(</?\w+)(?:\s(?:[^<>/]|/[^<>])*)?(/?>)#ui", '$1$2', $str);

        // remove control characters
//        $str = str_replace("\r", '', $str);    // --- replace with empty space
//        $str = str_replace("\n", $rep, $str);   // --- replace with space
//        $str = str_replace("\t", $rep, $str);   // --- replace with space

        // remove multiple spaces
//        $str = trim(preg_replace('/ {2,}/', $rep, $str));


        return $str;
    }
  /*
   * @param $beginDate - date (format - 'Y-m-d')
   * @param $endDate - date (format - 'Y-m-d')
   * Создает файл для отправки в метрику. Если период не указан - тащит данные за вчера
   */
  public function generateCSVForMetric($beginDate=false, $endDate=false)
  {
    $model = new UserRegisterPageCounter();
    $arRes = $model->getYandexGoals($beginDate, $endDate);

    if(!count($arRes))
    {
      file_put_contents(__DIR__ . "/_YM_offline_conversions_log.txt", date('Y.m.d H:i:s') . ' result_error' . PHP_EOL, FILE_APPEND);
      return false;
    }

    $content = "ClientId,Target,DateTime" . PHP_EOL;
    $bResult = false;
    foreach ($arRes as $v)
    {
      if($v['page']!=UserRegister::$PAGE_USER_LEAD || intval($v['time'])<1580738300)
      {
        continue;
      }

      $goal='offline';
      $bResult = true;
      /*switch ($v['page'])
      {
        case UserRegister::$STEP_TYPE: $goal=1; break;
        case UserRegister::$STEP_LOGIN: $goal=20; break;
        case UserRegister::$STEP_CODE: $goal=2; break;
        case UserRegister::$STEP_PASSWORD: $goal=3; break;
        case UserRegister::$STEP_AVATAR: $goal=4; break;
        case UserRegister::$PAGE_USER_LEAD: $goal=5; break;
        default: $goal=6; break;
      }*/
      $content .= $v['ym_client'] . "," . $goal . "," . $v['time'] . PHP_EOL;
    }

    if(!$bResult)
    {
      file_put_contents(__DIR__ . "/_YM_offline_conversions_log.txt", date('Y.m.d H:i:s') . ' filter_error' . PHP_EOL . PHP_EOL, FILE_APPEND);
      return false;
    }

    $result = file_put_contents($this->csvMetricFile, $content);
    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . DS . $this->csvMetricFile) || !$result)
    {
      file_put_contents(__DIR__ . "/_YM_offline_conversions_log.txt", date('Y.m.d H:i:s') . ' file_error' . PHP_EOL . PHP_EOL, FILE_APPEND);
      return false;
    }

    $curl = curl_init(MainConfig::$YANDEX_METRIC_OFFLINE_CONVERSIONS);

    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, ['file' => new CurlFile(realpath($this->csvMetricFile))]);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
      $curl,
      CURLOPT_HTTPHEADER,
      ["Content-Type: multipart/form-data", "Authorization: OAuth " . MainConfig::$YANDEX_METRIC_OAUTH_TOKEN]
    );

    $result = curl_exec($curl);
    curl_close($curl);
    file_put_contents(__DIR__ . "/_YM_offline_conversions_log.txt", date('Y.m.d H:i:s') . ' ' . print_r($result, true) . PHP_EOL . PHP_EOL, FILE_APPEND);
    return $result;
  }
}

?>