<?php
/**
 * Date: 21.12.2016
 * GreskoD
 * Модель обратной связи
 */

class Feedback extends Model
{
    /**
     * получаем данные для формы
     */

     public function getDatas($id)
    {

        // $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT f.id, f.type, f.theme,f.chat, f.email, f.text, f.name, DATE_FORMAT(f.crdate, '%d.%m.%Y') crdate
                FROM feedback f WHERE f.id = {$id}";
        $res = Yii::app()->db->createCommand($sql);
        $data = $res->queryAll();
        return  $data;
    }

    public function getUserFeedbacks($id)
    {
        $sql = "SELECT
                  f.id,
                  f.theme,
                  f.direct,
                  f.chat,
                  f.type,
                  DATE_FORMAT(f.crdate, '%d.%m.%Y') crdate
                FROM feedback f
                WHERE f.pid = {$id} 
                ORDER BY f.crdate DESC";

        $res = Yii::app()->db->createCommand($sql);
        $data = $res->queryAll();
        return  $data;
    }

    public function getDatAdmin()
		{
			$arRes = ['cnt'=>0,'items'=>[]];
			$arCId = array();
			$arMess = Yii::app()->db->createCommand()
								->select("id, id_theme")
								->from('chat')
								->where('(id_usp=2054 
								    AND is_read=0)
								    OR' // 2054 - ID админстратора для Р,
                                    .'(id_use=1766
                                    AND is_resp=0
                                    AND is_read=0)') // 1766 - ID админстратора для С,
								->order('id desc')
								->queryAll();

			$arRes['cnt'] = sizeof($arMess);
			foreach ($arMess as $v)
				!in_array($v['id_theme'], $arCId) && $arCId[] = $v['id_theme'];
			$strCId = implode(',', $arCId);
			$where = 'is_smotr=0' . (strlen($strCId) ? ' OR chat IN(' . $strCId . ')' : '');

			$arFeedback = Yii::app()->db->createCommand()
								->select("id, theme, chat, is_smotr, type")
								->from('feedback')
								->where($where)
								->order('id desc')
								->queryAll();

			foreach ($arFeedback as $v) {
				$arF = $arRes['items'][$v['id']];
				$arF['title'] = $v['theme'];
				$arF['type'] = $v['type'];
				$arF['chat'] = $v['chat'];
				!isset($arF['cnt']) && $arF['cnt'] = 0;
				if(!$v['is_smotr']) {
					//$arRes['cnt']++;
					$arF['cnt']++;
				}

				foreach ($arMess as $m)
					$m['id_theme']==$v['chat'] && $arF['cnt']++;
						
				$arRes['items'][$v['id']] = $arF;
			}
            $arRes['cnt'] = count($arRes['items']);

			return  $arRes;
    }

    public function getData()
    {
			$arRes = array(
					'type' => filter_var(
						Yii::app()->getRequest()->getParam('type', 0), 
						FILTER_SANITIZE_NUMBER_INT
					),
					'name' => filter_var(
						Yii::app()->getRequest()->getParam('name'), 
						FILTER_SANITIZE_FULL_SPECIAL_CHARS
					),
					'theme' => filter_var(
						Yii::app()->getRequest()->getParam('theme'),
						FILTER_SANITIZE_FULL_SPECIAL_CHARS
					),
					'email' => filter_var(
						Yii::app()->getRequest()->getParam('email'), 
						FILTER_SANITIZE_EMAIL
					),
					'text' => filter_var(
						Yii::app()->getRequest()->getParam('text'), 
						FILTER_SANITIZE_FULL_SPECIAL_CHARS
					),
            );

			$arRes['directs'] = $this->getDirects();

			if (Share::$UserProfile->id) {
                $arRes['feedbacks'] = $this->getUserFeedbacks(Share::$UserProfile->id);
            }

			return $arRes;
    }

    public function setFeedback($cloud){

        Yii::app()->db->createCommand()
            ->update('feedback', array(
                'chat' => $cloud['chat'],
            ), 'id=:id', array(':id' => $cloud['id']));

    }

    /**
     * Сохраняем отзыв
     */
    public function saveData()
    {
        $rq = Yii::app()->getRequest();
        $autotype = filter_var($rq->getParam('autotype'), FILTER_SANITIZE_NUMBER_INT);
        $app = filter_var($rq->getParam('app'), FILTER_SANITIZE_NUMBER_INT);
        $type = filter_var($rq->getParam('type', 0), FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($rq->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $theme = filter_var($rq->getParam('theme'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $emails = filter_var($rq->getParam('email'), FILTER_SANITIZE_EMAIL);
        $text = filter_var($rq->getParam('text'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $referer = filter_var($rq->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transition = filter_var($rq->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canal = filter_var($rq->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $campaign = filter_var($rq->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_var($rq->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = Share::$UserProfile->exInfo->id ?: 0;
        $keywords = filter_var($rq->getParam('keywords'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $point = filter_var($rq->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_referer = filter_var($rq->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $direct = filter_var($rq->getParam('direct'), FILTER_SANITIZE_NUMBER_INT);
        $text = substr($text, 0, 5000);
        $roistat = (isset($_COOKIE['roistat_visit'])) ? $_COOKIE['roistat_visit'] : "(none)";

        /** $fdbk - feedback to exist talk
         *  from view page-feebback-tpl.php
         *  @integer
         */
        $fdbk = filter_var($rq->getParam('feedback'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $arErrors = false;
        if(empty($id) && $app == 0){ // CAPTCHA
            $recaptcha = $rq->getParam('g-recaptcha-response');
            if(!empty($recaptcha))
            {
                $google_url="https://www.google.com/recaptcha/api/siteverify";
                $secret='6Lf2oE0UAAAAAPkKWuPxJl0cuH7tOM2OoVW5k6yH';
                $ip=$_SERVER['REMOTE_ADDR'];
                $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
                //
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
                $res = curl_exec($curl);
                curl_close($curl);
                //
                $res = json_decode($res, true);//reCaptcha введена
                if(!$res['success']) // wrong captcha
                {
                    $arErrors = array('ERROR'=>'captcha','MESSAGE'=>'Вы допустили ошибку при прохождении проверки "Я не робот"');
                }
            }
            else
            {
                $arErrors = array('ERROR'=>'captcha','MESSAGE'=>'Необходимо пройти проверку "Я не робот"');
            }
        }

        if(!$arErrors)
        {
            $arFeedback = array(
                'type'   => $autotype,
                'name'   => $name,
                'theme'  => $theme,
                'text'   => $text,
                'email'  => $emails,
                'pid'    => $id,
                'direct' => $direct
            );

            $arFeedbackNew = array(
                'crdate'   => date("Y-m-d H:i:s"),
                'content'  => $content,
                'referer'  => $referer,
                'last_referer' => $last_referer,
                'point'    => $point,
                'keywords' => $keywords,
                'canal'    => $canal,
                'campaign' => $campaign,
                'transition' => $transition,
            );

            $Im = (Share::isEmployer($autotype) ? (new ImApplic()) : (new ImEmpl()));

            if((Share::isEmployer($autotype) || Share::isApplicant($autotype)) && ($fdbk==0))
            { // только зареганым // не обращался

                $arChat = array(
                    'message' => "Добрый день, $name. Ваш вопрос '$text' на рассмотрении",
                    'new' => $id,
                    'idus' => (Share::isEmployer($autotype) ? 2054 : 1766 ),
                    'idTm' => $theme,
                    'theme' => $theme,
                    'original' => $text
                );

                $arFeedback = array_merge($arFeedback, $arFeedbackNew);
                $idTheme = $Im->sendUserMessages($arChat)['idtm'];
                $arFeedback['chat'] = $idTheme;
                $res = Yii::app()->db->createCommand()
                    ->insert('feedback', $arFeedback);
            }
            elseif((Share::isEmployer($autotype) || Share::isApplicant($autotype)) && ($fdbk>0))
            { // если зареганый юзер уже общался с админом

                $arChat = array(
                    'message' => $text,
                    'new' => $id,
                    'idus' => (Share::isEmployer($autotype) ? 2054 : 1766 ),
                    'idTm' => $theme,
                    'theme' => $theme,
                    'original' => $text
                );

                $feedback = Yii::app()->db->createCommand()
                    ->select('id, chat, theme')
                    ->from('feedback f')
                    ->where('pid=:id AND id=:fbdk AND chat>0',
                        [
                            ':id'  => $id,
                            ':fbdk'=> $fdbk
                        ]
                    )
                    ->queryRow();

                $arChat['new'] = 0;
                $arChat['idTm'] = $feedback['chat'];
                $arChat['theme'] = $feedback['theme'];
                $arFeedback['chat'] = $feedback['chat'];
                $arFeedback['theme'] = $feedback['theme'];

                $res = Yii::app()->db->createCommand()->update(
                    'feedback',
                    $arFeedback,
                    'id='.$fdbk
                );

                $is_resp = (Share::isEmployer($autotype) ? 1 : 0);
                $Im->sendUserMessages($arChat, $is_resp);
            }
            else
            { // ветка для гостей
                $arFeedback = array_merge($arFeedback, $arFeedbackNew);
                $res = Yii::app()->db->createCommand()
                    ->insert('feedback', $arFeedback);
                $idFeedback = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
                $mess = '"' . $text . '"';
                $texs = "Пользователь $name оставил обращение по обратной связи: $mess. Необходима модерация https://prommu.com/admin/site/mail/$idFeedback";
                $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$texs";
                file_get_contents($sendto);
            }

            $name = trim($name);
            empty($name) && $name = 'пользователь';
            // письмо юзеру
            Mailing::set(7, ['email_user'=>$emails, 'name_user'=>$name]);
            // письмо админам
            Mailing::set(8,
              [
                'name_user'      => $name,
                'theme_message'  => $theme,
                'email_user'     => $emails,
                'text_message'   => $text,
                'referer_seo'    => $referer,
                'transition_seo' => $transition,
                'canal_seo'      => $canal,
                'campaign_seo'   => $campaign,
                'content_seo'    => $content,
                'keywords_seo'   => $keywords,
                'point_seo'      => $point,
                'l_referer_seo'  => $last_referer,
                'roistat_seo'    => $roistat
              ]
            );

            Yii::app()->user->setFlash('prommu_flash', 'Ваше обращение принято в обработку. После обработки вашего письма нашими менеджерами, мы свяжемся с вами');

            return array('res' => $res);
        }
        else{
            return $arErrors;
        }
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('theme',$this->theme,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('text',$this->text,true);
        $criteria->compare('crdate',$this->crdate,true);
        $criteria->compare('pid',$this->pid);
        $criteria->compare('chat',$this->chat);
        $criteria->compare('is_smotr',$this->is_smotr);
        $criteria->compare('date_smotr',$this->date_smotr,true);

        return new CActiveDataProvider('Feedback', array(
            'criteria'=>$criteria,
        ));
    }

     public function deleteFeedback($id){
        Yii::app()->db->createCommand()
            ->delete('feedback', 'id=:id', array(':id' => $id));
    }

    public function ChangeModer($id, $status)
    {
      $result = Yii::app()->db->createCommand()
        ->update('feedback',['status'=>$status],'id=:id',[':id'=>$id]);
      return ($result ? 'Данные успешно обновлены' : 'Ошибка изменения данных');
    }
	/**
	 *	@param 	number - feedback ID 
	 *	@return array [items,user,chat]
	 *  собираем данные для 
	 */
	public function getAdminData($id) {
		$arRes['items'] = Yii::app()->db->createCommand()
												->select("
													c.id,
													c.message,
													c.is_resp isresp,
													c.is_read isread,
													c.id_usp idusp,
													c.id_use iduse,
													c.files,
													e.name nameto,
													CONCAT(r.firstname, ' ', r.lastname) namefrom,
													DATE_FORMAT(c.crdate, '%d.%m.%Y') crdate,
													DATE_FORMAT(c.crdate, '%H:%i:%s') crtime")
												->from('chat c')
												->leftjoin('employer e', 'e.id_user = c.id_use')
												->leftjoin('resume r', 'r.id_user = c.id_usp')
												->where('c.id_theme=:id', array(':id'=>$id))
												->queryAll();

		$item = $arRes['items'][0];
		if(!in_array($item['idusp'], [2054,1766])) {
			$arRes['user'] = array( // applicant
				'id' => $item['idusp'],
				'name' => $item['namefrom'],
				'type' => 2,
				'link' => '/admin/site/Promoedit/' . $item['idusp'],
			);
		}
		else{
			$arRes['user'] = array( // employer
				'id' => $item['iduse'],
				'name' => $item['nameto'],
				'type' => 3,
				'link' => '/admin/site/Empledit/' . $item['iduse'],
			);
		}

		$arRes['chat'] = Yii::app()->db->createCommand()
											->select("id, theme, name, email, text")
											->from('feedback')
											->where('chat=:id', array(':id'=>$id))
											->queryRow();

		return $arRes;
	}
	/**
	 *	@param 	number - feedback ID 
	 *  установка статуса прочитано админом
	 */
	public function setStatusReaded($id, $type) {

		$arRes['feedback'] = Yii::app()->db->createCommand()
													->update(
														'feedback', 
														array('is_smotr'=>1),
														($type==='chat' ? 'chat' : 'id') . '=:id', 
														array(':id'=>$id)
													);

		if($type==='feedback')
			return $arRes;

		$arRes['chat'] = Yii::app()->db->createCommand()
											->update(
												'chat', 
												array('is_read'=>1),
												'id_theme=:chat AND '
												. '((id_usp=2054 AND is_resp=1 AND is_read=0) OR' // 2054 - ID админстратора для Р
												. '(id_use=1766 AND is_resp=0 AND is_read=0))', // 1766 - ID админстратора для С
												array(':chat'=>$id)
											);
		return $arRes;
	}
	/**
	 * 
	 */
	public function getDirects() {
		$arRes = array();
		$sql = Yii::app()->db->createCommand()
						->select("*")
						->from('feedback_direct')
						->queryAll();		

		foreach ($sql as $v)
			$arRes[$v['id']] = $v;

		return $arRes;
	}
  /**
   * @param int $id
   * @return array|string
   */
	public static function getAdminDirects($id=-1)
  {
    $cacheId = 'Feedback_directs';
    $arRes = Cache::getData($cacheId);
    if($arRes['data']===false)
    {
      $arRes = Cache::getData($cacheId);
      $arRes['data'] = ['' => 'Все'];
      $query = Yii::app()->db->createCommand()
        ->select('id, name')
        ->from('feedback_direct')
        ->queryAll();

      foreach ($query as $v)
      {
        $arRes['data'][$v['id']] = $v['name'];
      }
      Cache::setData($arRes);
    }

    return ($id<0 ? $arRes['data'] : ($id==0 ? '-' : $arRes['data'][$id]));
  }
  /**
   * @return mixed
   */
  public function getStatus() {
      $sql = Yii::app()->db->createCommand()
          ->select('chat, status')
          ->from('feedback')
          ->queryAll();
      foreach ($sql as $item)
          $status[$item['chat']] = $item;
      return $status;
  }
  /**
   * @param bool $key
   * @return array|mixed
   */
  public static function getAdminStatus($all=true, $key=false)
  {
    $arRes = ($all ? ['' => 'Все'] : []);

    $arRes[0] = 'Ожидание модератора';
    $arRes[1] = 'Дубль';
    $arRes[2] = 'Не решено';
    $arRes[3] = 'Ожидание пользователя';
    $arRes[4] = 'Спам';
    $arRes[5] = 'Решено';

    return ($key!==false ? $arRes[$key] : $arRes);
  }
}