<?
class MailingLetter extends Mailing
{
	function __construct()
	{
		$this->view = 'notifications/letter';
		$this->pageTitle = 'письма';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'admin_mailing_letter';
	}
	/**
	*		Чтение данных	
	*/
	public function getData($id)
	{
		$template = new MailingTemplate;
		$arRes['template'] = $template->getActiveTemplate();
		$arRes['item'] = $this::model()->findByPk($id);
        $dataModelSV = new SearchVac;
        $arRes['positions'] = $dataModelSV->getOccupations(); // select position list of employers
        $params = unserialize($arRes['item']['params']);
        $arRes['selected_cities'] = [];
        if(count($params['cities'])) {
            $arRes['selected_cities'] = Yii::app()->db
                ->createCommand()
                ->select('id_city, name')
                ->from('city')
                ->where('id_city in ('.implode(',', $params['cities']).')')
                ->queryAll();
        }

        //print_r($arRes['cities']);

        // select cotype of Applications
        $arRes['cotypes'] = Yii::app()->
            db->createCommand("
                SELECT d.id, d.type, d.name 
                FROM user_attr_dict d 
                WHERE d.id_par = 101 
                ORDER BY id
        ")->queryAll();

		return $arRes;
	}
	/**
	*		Запись данных
	*/
	public function setData($obj)
	{
		$arParams = array();
		$arRes = array('error'=>false);
		// status
		$arParams['status'] = $obj->getParam('user_status');
		// ismoder
		$arParams['moder'] = $obj->getParam('user_moder');
		// subscribe
		$arParams['subscribe'] = $obj->getParam('user_subscribe');
        // position
        $arParams['position'] = $obj->getParam('position');
        //cities
        $arParams['cities'] = $obj->getParam('cities');
        // cotype
        $arParams['cotype'] = $obj->getParam('cotype');
        // gender
        $arParams['sex'] = $obj->getParam('sex');

        // age
        $arParams['age-start'] = filter_var(
            $obj->getParam('age-start'),
            FILTER_SANITIZE_NUMBER_INT
        );
        $arParams['age-stop'] = filter_var(
            $obj->getParam('age-stop'),
            FILTER_SANITIZE_NUMBER_INT
        );

		// emails
		$this->receiver = filter_var(
		                $obj->getParam('receiver'), // email
		                FILTER_SANITIZE_EMAIL
		            );
		$arReceiver = $this->getEmailArray($this->receiver);
		// title
		$this->title = filter_var(
		                trim($obj->getParam('title')),
		                FILTER_SANITIZE_FULL_SPECIAL_CHARS
		            	);
		// text
		$this->text = $obj->getParam('text');
		// in_template
		$this->in_template = $obj->getParam('in_template');
		
		if(
			(!count($arParams['status']) && !count($arParams['moder']) && empty($arParams['subscribe']))
			&& 
			!count($arReceiver)
		)
			$arRes['messages'][] = 'необходимо задать параметры для выборки пользователей или ввести корректный Email';
		if(empty($this->title) || empty($this->text))
			$arRes['messages'][] = 'поля "Заголовок" и "Текст письма" должны быть заполнены';

		$this->params = serialize($arParams);
		$template = new MailingTemplate;
		$arRes['template'] = $template->getActiveTemplate();

		if(count($arRes['messages'])) // error
		{
			$arRes['error'] = true;
			$arRes['item'] = $this;
			return $arRes;
		}
		
		$event = $obj->getParam('event_type');

		// Сохраняем в любом случае
		//if($event=='save') // save data
		//{
			$time = time();
			$this->mdate = $time;
			$id = $obj->getParam('id');

			if(!intval($id)) // insert
			{
				$this->cdate = $time;
				$this->setIsNewRecord(true);
			}
			else // update
			{
				$this->id = $id;
			}

			if($this->save())
			{
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
			}
			else
			{
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
			}

		//}
		if($event=='send') // send data
		{
			$arCond = [];
			// continue
			if(count($arParams['status']))
				$arCond[] = 'u.status IN(' . implode(',',$arParams['status']) . ')';
			if(count($arParams['moder']))
				$arCond[] = 'u.ismoder IN(' . implode(',',$arParams['moder']) . ')';
			if(!empty($arParams['subscribe']))
				$arCond[] = "ua.key='isnews' AND ua.val=1";

            if(count($arParams['cities']))
                $arCond[] = 'uc.id_city IN(' . implode(',',$arParams['cities']) . ')';

            if(count($arParams['position']))
                $arCond[] = 'um.id_mech IN(' . implode(',',$arParams['position']) . ')';
            if(count($arParams['cotype']))
                $arCond[] = 'uad.id_par IN(' . implode(',',$arParams['cotype']) . ')';
            if(count($arParams['sex']))
                $arCond[] = 'r.isman IN(' . implode(',',$arParams['sex']) . ')';

            if(!empty($arParams['age-start']) && !empty($arParams['age-stop'])) {
                $sql = "( ( ( YEAR(CURRENT_DATE) - YEAR(r.birthday) ) - ( DATE_FORMAT(CURRENT_DATE, '%m%d') < DATE_FORMAT(r.birthday, '%m%d') )  ) BETWEEN "
                    . $arParams['age-start']
                    . " AND "
                    .  $arParams['age-stop']
                    . ")";

                $arCond[] = $sql ;
            }

			if(count($arCond))
			{
				$strCond = implode(' AND ', $arCond);

				$sql = Yii::app()->db->createCommand()
                    ->select("u.id_user, u.email, r.birthday ")
                    ->from('user u')
                    ->leftjoin('user_attribs ua','ua.id_us=u.id_user')
                    ->leftjoin('user_mech um','um.id_us=u.id_user')
                    ->leftjoin('user_attr_dict uad','uad.id_par=u.id_user')
                    ->leftjoin('resume r','r.id_user=u.id_user')
                    ->leftjoin('user_city uc','uc.id_user=u.id_user')
                    ->where($strCond)
                    ->order('u.id_user desc')
                    //->group('u.id_user')
                    ->queryAll();

//                echo('<pre>');
//                    print_r($strCond);
//                    print_r($sql);
//                echo('</pre>');
//                die();

				foreach ($sql as $v)
				{
					if(filter_var($v['email'],FILTER_VALIDATE_EMAIL))
					{
						$arReceiver[] = $v['email'];
						$arRes['items'][] = $v; // на будущее логирование
					}
				}			
			}

			$arReceiver = array_unique($arReceiver);

			if(count($arReceiver))
			{
				// помещаем письмо в шаблон	
				$this->in_template && $this->text = str_replace(
                    MailingTemplate::$CONTENT,
                    $this->text,
                    $arRes['template']->body
                );

				$this->setToMailing($arReceiver, $this->title, $this->text);
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('success', 'Данные сохранены поставлены в очередь отправки');
			}
			else
			{
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('danger', 'Постановка в очередь не удалась');
			}
		}

		return $arRes;
	}
}
?>
<?php
//SELECT
//u.id_user, u.email, r.birthday
//                    from user u
//                    left join user_attribs ua ON ua.id_us=u.id_user
//                    left join user_mech um ON um.id_us=u.id_user
//                    left join user_attr_dict uad ON uad.id_par=u.id_user
//                    left join resume r ON r.id_user=u.id_user
//                    left join user_city uc ON uc.id_user=u.id_user
//                    where
//                    u.status IN(2) AND u.ismoder IN(1) AND r.id_user IN(0)
?>