<?php
class Yandex //extends CActiveRecord
{
	public $filename = 'yandex_job.yvl';
	public function generateFile()
	{
		$fullPath = Subdomain::domainRoot() . DS . $this->filename;
		if(!file_exists($fullPath))
			return false;
	
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
		foreach ($data['main'] as $id => $v)
		{
			$vacancy = $dom->createElement('vacancy');
			$e = $dom->createElement('url',$v['link']); 							// url
			$vacancy->appendChild($e);
			$e = $dom->createElement('creation-date',$v['crdate']); 	// creation-date
			$vacancy->appendChild($e);
			$e = $dom->createElement('update-date',$v['mdate']); 			// update-date
			$vacancy->appendChild($e);
			$e = $dom->createElement('salary',$v['salary']); 					// salary
			$vacancy->appendChild($e);
			$e = $dom->createElement('currency','руб'); 							// currency
			$vacancy->appendChild($e);
			foreach ($v['category'] as $arCat)
			{
				$category = $dom->createElement('category'); 						//category
				$e = $dom->createElement('industry',$arCat['industry']);// industry 
				$category->appendChild($e);
				$e = $dom->createElement('specialization',$arCat['specialization']);// specialization
				$category->appendChild($e);
				$vacancy->appendChild($category);
			}
			$e = $dom->createElement('job-name',$v['title']); 				// job-name
			$vacancy->appendChild($e);
			$e = $dom->createElement('employment',$v['istemp']); 			// employment
			$vacancy->appendChild($e);
			/*$e = $dom->createElement('schedule','TEST'); 						// schedule !!!!!!!!!!!!!!!!!!
			$vacancy->appendChild($e);*/
			$e = $dom->createElement('description',$v['requirements']);// description
			$vacancy->appendChild($e);
			$e = $dom->createElement('duty',$v['duties']); 						// duty
			$vacancy->appendChild($e);
			$e = $dom->createElement('term',$v['conditions']); 				// term
			$vacancy->appendChild($e);
			/*$e = $dom->createElement('contract','TEST'); 						// contract !!!!!!!!!!!!!!!!!!
			$vacancy->appendChild($e);
			$e = $dom->createElement('text','TEST'); 									// text !!!!!!!!!!!!!!!!!!
			$vacancy->appendChild($e);*/
			$requirement = $dom->createElement('requirement'); 				// requirement
			if(!empty($v['аge']))
			{
				$e = $dom->createElement('аge',$v['аge']); 							// аge
				$requirement->appendChild($e);
			}
			if(!empty($v['sex']))
			{
				$e = $dom->createElement('sex',$v['sex']); 							// sex
				$requirement->appendChild($e);				
			}
			if(!empty($v['experience']))
			{
				$e = $dom->createElement('experience',$v['experience']); // experience
				$requirement->appendChild($e);				
			}
			$vacancy->appendChild($requirement);
			$addresses = $dom->createElement('addresses');						// addresses
			foreach ($v['adresses'] as $arAddress)
			{
				$address = $dom->createElement('address'); 							// address
				$e = $dom->createElement('location',$arAddress['location']);// location
				$address->appendChild($e);
				if(is_array($arAddress['metro']))
				{
					foreach ($arAddress['metro'] as $m)
					{
						$e = $dom->createElement('metro',$m);								// metro
						$address->appendChild($e);	
					}
				}
				$addresses->appendChild($address);
			}
			$vacancy->appendChild($addresses);

			$employer = $data['employer'][$v['id_user']];

			$company = $dom->createElement('company'); 								// company
			$e = $dom->createElement('name',$employer['company']); 		// name
			$company->appendChild($e);
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
			if(isset($employer['hr-agency']))
			{
				$e = $dom->createElement('hr-agency',$employer['hr-agency']);// hr-agency
				$company->appendChild($e);
			}
			if(!empty($employer['name']))
			{
				$e = $dom->createElement('contact-name',$employer['name']); // contact-name
				$company->appendChild($e);
			}
			$vacancy->appendChild($company);
			$vacancies->appendChild($vacancy);
		}
		$source->appendChild($vacancies);
		$dom->appendChild($source);
		$result = file_put_contents($this->filename, $dom->saveXML());
		return $result!=='false';
	}
}
?>