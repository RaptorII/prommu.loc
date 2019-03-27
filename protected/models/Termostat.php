<?php
	
	class Termostat{

		public static $PATH_TO_SCHEDULE = '/uploads/mail/analytics/';
		/**
		*	@param $id_user integer id_user
		*	@param $bDate string bdate
		*	@param $eDate string edate
		* @return integer
		*/
		public function getPromoView($id_user, $bDate, $eDate)
		{
			$sql = "SELECT COUNT(id)
								FROM termostat_analytic
								WHERE user = {$id_user} 
									AND date between '{$bDate}' 
									AND '{$eDate}'";
			$query = Yii::app()->db->createCommand($sql)->queryScalar();

			return intval($query);
		}
		/**
		 * 
		 */
		public function setUserDataTime($id_user, $arDates, $analytic)
		{
			Yii::app()->db->createCommand()
				->update(
					'user', 
					['analytday' => $analytic],
					'id_user=:id_user', 
					[':id_user' => $id_user]
				);

			/*$day = 0;
			$time = '0000-00-00 00:00:00';
			if(isset($analytic)){
				$day = $arDates['day'];
				$arTime = explode(':', $arDates['time']);
				for($i=0; $n=sizeof($arTime), $i<$n; $i++)
					$arTime[$i] = str_pad($arTime[$i], 2, "0", STR_PAD_LEFT);

				$time = '0000-00-00 ' . implode(':', $arTime) . ':00';
			}

			$res = Yii::app()->db->createCommand()
				->update(
					'user', 
					array('analytday' => $day, 'analyttime' => $time),
					'id_user=:id_us', 
					array(':id_us' => $id_user)
				);

			return $res;*/
		}
		/**
		*	@param $id_user integer id_user
		*	@param $bDate string bdate
		*	@param $eDate string edate
		* @return array [cnt_invitations,cnt_requests,cnt_approved]
		*/
		public function getPromoResponse($id_user, $bDate, $eDate)
		{
			$arRes = array_fill_keys(['cnt_invitations','cnt_requests','cnt_approved'],0);

			$sql = "SELECT vs.isresponse, vs.status
								FROM vacation_stat vs
								RIGHT JOIN resume r ON r.id=vs.id_promo
								WHERE r.id_user = {$id_user} 
									AND vs.date between '{$bDate}'
									AND '{$eDate}'";
			$query = Yii::app()->db->createCommand($sql)->queryAll();

			for($i=0, $n=count($query); $i<$n; $i++)
			{
				if($query[$i]['status']!=7)
				{
					$query[$i]['isresponse']==2 && $arRes['cnt_invitations']++;
					$query[$i]['isresponse']==1 && $arRes['cnt_requests']++;
				}
				else
				{
					$query[$i]['isresponse']>0 && $arRes['cnt_approved']++;
				}
			}

			return $arRes;
		}
		/**
		*	@param integer id_user
		*	@param array [bdate,edate,db_bdate,db_edate]
		* @return array [outsourcing, outstaffing,vacancy,sms,push,email,api,repost,cnt]
		 */
		public function getTermostatServices($id_user, $arDates)
		{
			$arRes = array_fill_keys(
				['outsourcing','outstaffing','vacancy','sms','push','email','api','repost'],
				0
			);
			// vacancy, sms, push, email, api, repost
			$sql = "SELECT type
								FROM service_cloud
								WHERE id_user = {$id_user} AND status = 1 
									AND date between '{$arDates['db_bdate']}'
									AND '{$arDates['db_edate']}'";
			$query = Yii::app()->db->createCommand($sql)->queryColumn();

			for($i=0, $n=count($query); $i<$n; $i++) $arRes[$query[$i]]++;

			// outsourcing, outstaffing
			$sql = "SELECT type
								FROM outstaffing
								WHERE id = {$id_user}
									AND date between '{$arDates['db_bdate']}'
									AND '{$arDates['db_edate']}'";
			$query = Yii::app()->db->createCommand($sql)->queryColumn();	 

			for($i=0, $n=count($query); $i<$n; $i++) $arRes[$query[$i]]++;
			
			$arRes['cnt'] = array_sum($arRes);

			return $arRes;
		}
		/**
		 * 
		 */
		public function getTermostatCount($idvac)
		{
			$sql = "SELECT COUNT(*)
	            FROM termostat_analytic t
	            WHERE t.id = {$idvac}";
			$query = Yii::app()->db->createCommand($sql)->queryScalar();

			return intval($query);
		}
		/**
		*	@param integer id_user
		*	@param array [bdate,edate,db_bdate,db_edate]
		* @return array [date,count]
		 */
		public function getTermostatEmplCount($id_user, $arDates)
		{
			$t1 = strtotime($arDates['bdate']);
			$t2 = strtotime($arDates['edate']);
			$day = 60 * 60 * 24;
			$days = ($t2 - $t1) / $day;
			$curDay = $t1;
			$mainCnt = 0;
			$arView = array();

			$sql = "SELECT date
								FROM termostat_analytic
								WHERE id = {$id_user} 
									AND date between '{$arDates['db_bdate']}' 
									AND '{$arDates['db_edate']}'";
			$query = Yii::app()->db->createCommand($sql)->queryColumn();

			for($c=0; $c<=$days; $c++)
			{
				$cnt = 0;
				for($i=0, $n=count($query); $i<$n; $i++)
				{
					$dbDate = strtotime($query[$i]);
					$curDay<$dbDate && $dbDate<($curDay+$day) && $cnt++;
				}
				$arView[$c] = array(date('d.m.y',$curDay), $cnt);
				$mainCnt += $cnt;
				$curDay += $day;
			}

			return array('schedule'=>$arView, 'count'=>$mainCnt);
		}

		public function setTermostat($idvac, $user, $type){
			if($type == "vacancy"){
				 $vacancy = Yii::app()->db->createCommand()
                ->select("ismoder")
                ->from('empl_vacations')
                ->where('id=:id', array(':id' => $idvac))
                ->queryRow();
           		if($vacancy['ismoder'] == 100)
           		{
           			 $flag = 1;
           		} else $flag = 0;
			} else $flag = 1;
			if($user != 0 && $flag){
				$sql = "SELECT t.id
	            FROM termostat_analytic t
	            WHERE t.user = {$user} AND t.date between  '12.04.2018' AND  '26.12.2018' AND t.id = {$idvac}
	            ";
	        	$res = Yii::app()->db->createCommand($sql)->queryAll();

	        	if(!$res){
	        		$res = Yii::app()->db->createCommand()
                ->insert('termostat_analytic', array(
                        'id'   =>   $idvac,
                        'user' =>   $user,
                        'type' =>   $type,
                        'date' =>   date("Y-m-d h-i"),
                    ));

	        	}
        	}
        	elseif($flag){
        		$res = Yii::app()->db->createCommand()
                ->insert('termostat_analytic', array(
                        'id'   =>   $idvac,
                        'user' =>   $user,
                        'type' =>   $type,
                        'date' =>   date("Y-m-d h-i"),
                    ));
        	}

		}
		/**
		 * @return array [bdate,edate,db_bdate,db_edate]
		 */
		public function getDates()
		{
			$bdate = date('d.m.Y', mktime(0,0,0,date('m'),1,date('Y')));
			$edate = date('d.m.Y');

			if(Yii::app()->getRequest()->isPostRequest)
			{
				$bdate = Yii::app()->getRequest()->getParam('pa-bdate');
				$edate = Yii::app()->getRequest()->getParam('pa-edate');
			}

			return array(
					'bdate' => $bdate,
					'edate' => $edate,				
					'db_bdate' => DateTime::createFromFormat('d.m.Y', $bdate)
													->format('Y-m-d 00:00:00'),
					'db_edate' => DateTime::createFromFormat('d.m.Y', $edate)
													->format('Y-m-d 23:59:59')
				);
		}
		/**
		 * @param $id_user - integer
		 * @param $arDates - array[bdate, edate, db_bdate, db_edate]
		 * @return array()
		 */
		public function getAppAnalytics($id_user, $arDates)
		{
			if(!$id_user || !is_array($arDates))
				return false;

			$arRes = array();
			$arRes = $this->getPromoResponse(
									$id_user,
									$arDates['db_bdate'],
									$arDates['db_edate']
								);
			$arRes['cnt_views'] = $this->getPromoView(
									$id_user,
									$arDates['db_bdate'],
									$arDates['db_edate']
								);
			$arRes['dates'] = $arDates;

			return $arRes;
		}
		/**
		 * @param $id_user - integer
		 * @param $arDates - array[bdate, edate, db_bdate, db_edate]
		 * @return array() 
		 */
		public function getEmpAnalytics($id_user, $arDates)
		{
			if(!$id_user || !is_array($arDates))
				return false;

			$arRes = array();
			$arRes['dates'] = $arDates;
			// vacancies
			$arVac = array_fill_keys(['cnt_views','cnt_responses','cnt_invitations'],0);
			$model = new Vacancy();
			$query = $model->getVacsForTermostat($id_user, $arDates);

			$arVac['cnt'] = count($query);
			$arI = array();

			if($arVac['cnt'])
			{
				$arId = array();
				foreach ($query as $v)
				{
					$arId[] = $v['id'];
					$arI[$v['id']] = array_fill_keys(['views','responses','invitations'],0);
					$arI[$v['id']]['title'] = $v['title'];
				}
				//
				$query = Yii::app()->db->createCommand()
									->select("id_vac,isresponse,status")
									->from('vacation_stat')
									->where(['in','id_vac',$arId])
									->queryAll();

				for ($i=0, $n=count($query); $i<$n; $i++)
				{ 
					if($query[$i]['isresponse']==2) // считаем приглашения
					{
						$arI[$query[$i]['id_vac']]['invitations']++;
						$arVac['cnt_invitations']++;
					}
					if($query[$i]['isresponse']==1) // считаем отклики
					{
						$arI[$query[$i]['id_vac']]['responses']++;
						$arVac['cnt_responses']++;
					}
				}
				//
				$query = Yii::app()->db->createCommand()
									->select('COUNT(id) cnt, id')
									->from('termostat_analytic')
									->where(['in','id',$arId])
									->group('id')
									->queryAll();

				for ($i=0, $n=count($query); $i<$n; $i++)
				{ 
					$arI[$query[$i]['id']]['views'] = $query[$i]['cnt'];
					$arVac['cnt_views'] += $query[$i]['cnt'];
				}
			}
			$arVac['items'] = $arI;
			$arRes['vacancies'] = $arVac;
			// services
			$arRes['services'] = $this->getTermostatServices($id_user, $arDates);
			// schedule
			$schedule = $this->getTermostatEmplCount($id_user, $arDates);
			$arRes['schedule'] = json_encode($schedule['schedule']);
			$arRes['cnt_profile_views'] = $schedule['count'];
			
			return $arRes;
		}
		/**
		 * 
		 */
		public function getDateForEmail()
		{
			$bTime = strtotime('first day of last month');
			$arRes = array(
					'bdate' => date('d.m.Y', $bTime),
					'edate' => date('d.m.Y'),
					'db_bdate' => date('Y-m-d 00:00:00', $bTime),
					'db_edate' => date('Y-m-d 00:00:00')
				);
			return $arRes;
		}
		/**
		 * 		Рассылка аналитики
		 */
		public function sendEmailNotifications()
		{
			if(date('j')!=1) // отправляем 1го числа каждого месяца
				return false;

			$arId = Yii::app()->db->createCommand()
								->select('id_user')
								->from('user')
								->where(['like','setting','%"analytic":"on"%'])
								->queryColumn();
			//
			//
			//
			$arId = [7000];
			//
			//
			//

			$arUsers = Share::getUsers($arId);

			if(!count($arUsers))
				return false;

			$arDates = $this->getDateForEmail();
			$arParams['analytic_period'] = $arDates['bdate'] . ' - ' . $arDates['edate'];

			foreach ($arUsers as $id => $v)
			{
				// set email
				$email = trim($v['email']);
				if(filter_var($email,FILTER_VALIDATE_EMAIL))
					$arParams['email_user'] = $email;
				if(!isset($arParams['email_user']))
					continue;
				// set name
				$arParams['name_user'] = trim($v['name']);
				if(empty($arParams['name_user']))
					$arParams['name_user'] = 'Пользователь';

				if(Share::isApplicant($v['status'])) // applicant
				{
					$arRes = $this->getAppAnalytics($id, $arDates);
					$arParams['cnt_invitations'] = $arRes['cnt_invitations'];
					$arParams['cnt_requests'] = $arRes['cnt_requests'];
					$arParams['cnt_approved'] = $arRes['cnt_approved'];
					$arParams['cnt_views'] = $arRes['cnt_views'];
					Mailing::set(11,$arParams);
				}
				if(Share::isEmployer($v['status'])) // employer
				{
					$arRes = $this->getEmpAnalytics($id, $arDates);
					$arParams['cnt_vacancy_public'] = $arRes['vacancies']['cnt'];
					$arParams['cnt_vacancy_views'] = $arRes['vacancies']['cnt_views'];
					$arParams['cnt_vacancy_responce'] = $arRes['vacancies']['cnt_responses'];
					$arParams['cnt_vacancy_invite'] = $arRes['vacancies']['cnt_invitations'];
					$arParams['vacancy_list'] = $arParams['service_list'] = '';
					if($arRes['vacancies']['cnt']>0)
					{
						$str = '<b style="display:block; color:#abb820; padding-bottom:12px; font-size:14px">#TITLE</b>
							<span style="display:block; color:#5b5b5b; font-size:14px; padding-bottom:7px">Просмотров: #VIEW</span>
							<span style="display:block; color:#5b5b5b; font-size:14px; padding-bottom:7px">Откликов: #RESP</span>
							<span style="display:block; color:#5b5b5b; font-size:14px; padding-bottom:7px">Приглашений: #INVITE</span>
							<span style="display:block; margin: 7px 0 20px; border-bottom:1px solid #efefef"></span>';

						foreach ($arRes['vacancies']['items'] as $vac)
						{
							$arParams['vacancy_list'] .= preg_replace(
									['/#TITLE/','/#VIEW/','/#RESP/','/#INVITE/'], 
									[$vac['title'],$vac['views'],$vac['responses'],$vac['invitations']], 
									$str
								);
						}
					}
					if(count($arRes['services']))
					{
						$str = '<span style="display:block; padding-bottom:20px">#NAME: 
							<span style="color:#abb820; white-space:nowrap">Количество использований: </span>
							<span style="color:#ff6500">#CNT</span></span>';
						foreach ($arRes['services'] as $code => $val)
						{
							if($val>0)
							{
								switch ($code)
								{
									case 'outsourcing':
										$name = 'Личный менеджер и аутсорсинг персонала';
										break;
									case 'outstaffing':
										$name = 'Аутстаффинг персонала';
										break;
									case 'vacancy':
										$name = 'Премиум-вакансии';
										break;
									case 'sms':
										$name = 'SMS информирование';
										break;
									case 'push':
										$name = 'PUSH уведомления';
										break;
									case 'email':
										$name = 'Электронная почта';
										break;
									case 'api':
										$name = 'Получение API ключа';
										break;
									case 'repost':
										$name = 'Группы социальных сетей PROMMU';
										break;
								};
								$arParams['service_list'] .= preg_replace(
										['/#NAME/', '/#CNT/'], 
										[$name, $val], 
										$str
									);
							}
						}
					}
					$arParams['cnt_services'] = $arRes['services']['cnt'];
					$arParams['cnt_views'] = $arRes['cnt_profile_views'];
					// draw graph
					$name = date('YmdHis') . $id;
					$arSchedule = $this->getTermostatEmplCount($id, $arDates);
					$arParams['analytic_schedule_src'] = '';
					if($arSchedule['count']>0)
					{
						Yii::import('application.extensions.pchartlib.pChart4Yii');
						Yii::import('application.extensions.pchartlib.pData4Yii');
						$DataSet = new pData4Yii;
						$DataSet->Data = array();
						$pChart = new pChart4Yii(700,230);
						foreach ($arSchedule['schedule'] as $v)
						{
						$date = explode('.',$v[0]);
						$DataSet->AddPoint($v[1], 'Serie1', $date[0]);
						}
						// Adding data
						$DataSet->AddAllSeries();
						$DataSet->SetAbsciseLabelSerie();
						// Chart Presentation
						$pChart->setFontProperties("fonts/tahoma.ttf",8);
						$pChart->setGraphArea(30,30,690,200);
						$pChart->drawFilledRoundedRectangle(0,0,700,230,1,254,254,254);
						$pChart->drawGraphArea(254,254,254);
						$pChart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,1,1,1);   
						$pChart->drawGrid(4,TRUE,222,222,222,50);
						$pChart->setFontProperties("fonts/tahoma.ttf",6);
						$pChart->drawTreshold(0,143,55,72,TRUE,TRUE);
						$pChart->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1);
						// output as file
						$src = self::$PATH_TO_SCHEDULE . $name . '.png';
						$pChart->Render(Subdomain::domainRoot() . $src);
						$arParams['analytic_schedule_src'] = '<span style="border:1px solid #c9c9c9; display:block; height:110px">
								<img src="' . Subdomain::site() . $src . '" alt="Prommu.com" width="305" height="92" border="0" style="display:block;max-width:285px;padding-top:10px">
							</span>';
						// output as text using base64 encode
						//$pChart->toBase64();
						//header("Content-Type: image/png");
					}

					Mailing::set(12,$arParams);
				}
			}
		}
	}
?>