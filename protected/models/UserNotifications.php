<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 29.07.2019
 * Time: 9:58
 */

class UserNotifications
{
  private static $table = 'user_notifications';

  public static $EMP_RESPONSES = 'emp_responses';
  public static $EMP_APPROVAL = 'emp_approval';
  public static $EMP_REFUSALS = 'emp_refusals';
  public static $EMP_START_VACANCY = 'emp_start_today';
  public static $EMP_END_VACANCY = 'emp_end_today';
  public static $EMP_SET_RATING = 'emp_set_rating';
  public static $EMP_NEW_RATING = 'emp_new_rating';

  public static $APP_INVITATIONS = 'app_invitations';
  public static $APP_NEW_VACANCIES = 'app_new_vacancies';
  public static $APP_CONFIRMATIONS = 'app_confirmations';
  public static $APP_REFUSALS = 'app_refusals';
  public static $APP_START_VACANCY_TOMORROW = 'app_start_tomorrow';
  public static $APP_START_VACANCY = 'app_start_today';
  public static $APP_SET_RATING = 'app_set_rating';
  public static $APP_NEW_RATING = 'app_new_rating';
  /**
   * @return array
   * Получаем счетчик уведомлений со ссылками для авторизованных пользователей в шапке сайта
   */
  public static function getNotifications()
  {
    $arRes = ['items'=>[], 'cnt'=>0];

    $query = Yii::app()->db->createCommand()
      ->select('un.*, ev.title')
      ->from(self::$table . ' un')
      ->join('empl_vacations ev','ev.id=un.id_vacancy')
      ->where(
        'un.id_user=:id_user',
        ['id_user'=>Share::$UserProfile->id]
      )
      ->queryAll();

    if(!count($query))
      return $arRes;

    // добираем данные для С
    if(Share::isApplicant())
    {
      $arIdVac = [];
      foreach ($query as $v)
      {
        $arIdVac[] = $v['id_vacancy']; // собираем ID акансий
      }

      $query2 = Yii::app()->db->createCommand()
        ->select('e.name, ev.id')
        ->from('empl_vacations ev')
        ->join('employer e','e.id_user=ev.id_user')
        ->where(['in','ev.id',$arIdVac])
        ->queryAll();

      foreach ($query as $key => $v)
      {
        foreach ($query2 as $e)
        {
          $v['id_vacancy']==$e['id'] && $query[$key]['employer']=$e['name'];
        }
      }
    }
    // добираем данные для Р
    if(Share::isEmployer())
    {
      $arIdUser = [];
      foreach ($query as $key => $v) // собираем ID оискателей
      {
        $query[$key][self::$EMP_SET_RATING] = explode(',',$v[self::$EMP_SET_RATING]);
        if(!empty(reset($query[$key][self::$EMP_SET_RATING])))
        {
          $arIdUser = array_merge($arIdUser, $query[$key][self::$EMP_SET_RATING]);
        }
        else
        {
          $query[$key][self::$EMP_SET_RATING] = 0;
        }
      }
      // добываем данные о соискателях
      $arUsers = Share::getUsers($arIdUser);

      foreach ($query as $key => $v)
      {
        if(count($v[self::$EMP_SET_RATING]))
        {
          foreach ($v[self::$EMP_SET_RATING] as $id_user)
          {
            $query[$key][self::$EMP_SET_RATING . '_items'][] = [
              'applicant' => $arUsers[$id_user]['name'],
              'applicant_id' => $id_user
            ];
            $arr[self::$EMP_SET_RATING] = 1;
          }
        }
      }
    }

    foreach ($query as $v)
    {
      if (Share::isEmployer())
      {
        self::buildArray($arRes, $v, self::$EMP_RESPONSES);
        self::buildArray($arRes, $v, self::$EMP_APPROVAL);
        self::buildArray($arRes, $v, self::$EMP_REFUSALS);
        self::buildArray($arRes, $v, self::$EMP_START_VACANCY);
        self::buildArray($arRes, $v, self::$EMP_END_VACANCY);
        self::buildArray($arRes, $v, self::$EMP_SET_RATING);
        self::buildArray($arRes, $v, self::$EMP_NEW_RATING);
      }
      if (Share::isApplicant())
      {
        self::buildArray($arRes, $v, self::$APP_INVITATIONS);
        self::buildArray($arRes, $v, self::$APP_NEW_VACANCIES);
        self::buildArray($arRes, $v, self::$APP_CONFIRMATIONS);
        self::buildArray($arRes, $v, self::$APP_REFUSALS);
        self::buildArray($arRes, $v, self::$APP_START_VACANCY_TOMORROW);
        self::buildArray($arRes, $v, self::$APP_START_VACANCY);
        self::buildArray($arRes, $v, self::$APP_SET_RATING);
        self::buildArray($arRes, $v, self::$APP_NEW_RATING);
      }
    }

    return $arRes;
  }

  /**
   * @param $arRes
   * @param $item
   * @param $field
   * вспомогательный метод для getNotifications()
   */
  private static function buildArray(&$arRes, $item, $field)
  {
    $arNames = [
      self::$EMP_RESPONSES => 'Отлик на вакансию',
      self::$EMP_APPROVAL => 'Подтверждение заявки на участие в вакансии',
      self::$EMP_REFUSALS => 'Отказ в участии на вакансию',
      self::$EMP_START_VACANCY => 'Начало проекта сегодня',
      self::$EMP_END_VACANCY => 'Завершение проекта сегодня',
      self::$EMP_SET_RATING => 'Оцените соискателя',
      self::$EMP_NEW_RATING => 'Вас оценили',

      self::$APP_INVITATIONS => 'Приглашение на вакансию Работодателем',
      self::$APP_NEW_VACANCIES => 'Появление новой вакансии',
      self::$APP_CONFIRMATIONS => 'Подтверждение заявки на участие в вакансии',
      self::$APP_REFUSALS => 'Отказ в участии на вакансию',
      self::$APP_START_VACANCY_TOMORROW => 'Старт работы по утвержденной вакансии за 1 день',
      self::$APP_START_VACANCY => 'Старт работы по утвержденной вакансии сегодня',
      self::$APP_SET_RATING => 'Оцените Работодателя',
      self::$APP_NEW_RATING => 'Вас оценили',
    ];

    $l = MainConfig::$PAGE_VACANCY . DS . $item['id_vacancy'];
    $arLinks = [
      self::$EMP_RESPONSES => $l . DS . MainConfig::$VACANCY_RESPONDED,
      self::$EMP_APPROVAL => $l . DS . MainConfig::$VACANCY_APPROVED,
      self::$EMP_REFUSALS => $l . DS . MainConfig::$VACANCY_REFUSED,
      self::$EMP_START_VACANCY => $l,
      self::$EMP_END_VACANCY => $l,
      self::$EMP_SET_RATING => MainConfig::$PAGE_SETRATE . DS . $item['id_vacancy'] . DS . $item['applicant_id'],
      self::$EMP_NEW_RATING => DS . MainConfig::$PAGE_RATE,

      self::$APP_INVITATIONS => Yii::app()->createUrl(MainConfig::$PAGE_RESPONSES,['tab'=>'invites']),
      self::$APP_NEW_VACANCIES => $l,
      self::$APP_CONFIRMATIONS => Yii::app()->createUrl(MainConfig::$PAGE_RESPONSES),
      self::$APP_REFUSALS => Yii::app()->createUrl(MainConfig::$PAGE_RESPONSES),
      self::$APP_START_VACANCY_TOMORROW => MainConfig::PAGE_USER_VACANCIES_LIST . DS . $item['id_vacancy'],
      self::$APP_START_VACANCY => MainConfig::PAGE_USER_VACANCIES_LIST . DS . $item['id_vacancy'],
      self::$APP_SET_RATING => MainConfig::$PAGE_SETRATE . DS . $item['id_vacancy'],
      self::$APP_NEW_RATING => DS . MainConfig::$PAGE_RATE
    ];

    if($field==self::$EMP_SET_RATING && count($item[$field]))
    {
      foreach ($item[self::$EMP_SET_RATING . '_items'] as $v)
      {
        $arRes['items'][$field]['name'] = $arNames[$field];
        !isset($arRes['items'][$field]['cnt']) && $arRes['items'][$field]['cnt']=0;
        $arRes['items'][$field]['cnt'] +=1;
        $arRes['items'][$field]['items'][] = [
          'vacancy' => $v['applicant'] . ' (' . $item['title'] . ')',
          'link' => $arLinks[$field] . $v['applicant_id'],
          'cnt' => 1//$item[$field]
        ];
        $arRes['cnt'] += 1;//$item[$field];
      }
    }
    elseif($item[$field]>0)
    {
      $arRes['items'][$field]['name'] = $arNames[$field];
      !isset($arRes['items'][$field]['cnt']) && $arRes['items'][$field]['cnt']=0;
      $arRes['items'][$field]['cnt'] += $item[$field];
      $arRes['cnt'] += $item[$field];

      if($field==self::$APP_SET_RATING || $field==self::$APP_NEW_RATING)
      {
        $arRes['items'][$field]['items'][] = [
          'vacancy' => $item['title'] . ' (' . $item['employer'] . ')',
          'link' => $arLinks[$field],
          'cnt' => $item[$field]
        ];
      }
      else
      {
        $arRes['items'][$field]['items'][] = [
          'vacancy' => $item['title'],
          'link' => $arLinks[$field],
          'cnt' => $item[$field]
        ];
      }
    }
  }
  /**
   * @param $id_user
   * @param $id_vacancy
   * @return CDbDataReader|mixed
   * Выборка данных по пользователю(пользователям) и вакансии
   */
  private static function getData($id_user, $id_vacancy)
  {
    if(is_array($id_user))
    {
      return Yii::app()->db->createCommand()
        ->select('*')
        ->from(self::$table)
        ->where(
          [
            'and',
            'id_vacancy=:id_vacancy',
            ['in','id_user',$id_user]
          ],
          [':id_vacancy'=>$id_vacancy]
        )
        ->queryAll();
    }
    else
    {
      return Yii::app()->db->createCommand()
        ->select('*')
        ->from(self::$table)
        ->where(
          'id_user=:id_user and id_vacancy=:id_vacancy',
          [':id_user'=>$id_user,':id_vacancy'=>$id_vacancy]
        )
        ->queryRow();
    }
  }
  /**
   * @param $id_user
   * @param $id_vacancy
   * @param $cnt
   * @return int
   * Установка уведомления для пользователя(пользователей) по вакансии
   * (используется в основном в методах действий пользователя оппонента по вакансии или при завершении вакансии)
   */
  public static function setDataByVac($id_user, $id_vacancy, $cnt, $value=0)
  {
    $data = self::getData($id_user, $id_vacancy);

    if(is_array($id_user)) // массив пользователей
    {
      if(count($data)) // меняем много записей
      {
        $arId = $arInsert = [];
        foreach ($data as $key => $v)
        {
          $arId[] = $v['id'];
          $arInsert[$key] = $v;
          $arInsert[$key][$cnt] = ($value ? implode(',',$value) : ($v[$cnt]+1));
        }
        Yii::app()->db->createCommand() // удаляем записи, чтоб уложиться в 2 запроса
          ->delete(self::$table,['in','id',$arId]);
        $result = Share::multipleInsert([self::$table => $arInsert]);
      }
      if(count($data) != count($id_user)) // добавляем записи, если кол-во не совпадает(или 0)
      {
        $arId = $arInsert = [];
        if(count($data))
        {
          foreach ($data as $v)
          {
            $arId[] = $v['id_user'];
          }
        }

        foreach ($id_user as $v)
        {
          if(!in_array($v,$arId))
          {
            $arInsert[] = [
              'id_user' => $v,
              'id_vacancy' => $id_vacancy,
              $cnt => ($value ? implode(',',$value) : 1),
              'date_created' => time()
            ];
          }
        }
        $result = Share::multipleInsert([self::$table => $arInsert]);
      }
    }
    else // один пользователь
    {
      if(isset($data['id'])) // меняем одну запись
      {
        $data[$cnt] = ($value ? implode(',',$value) : ($data[$cnt]+1));
        $result = Yii::app()->db->createCommand()
          ->update(
            self::$table,
            $data,
            'id=:id',
            [':id'=>$data['id']]
          );
      }
      else // добавляем одну запись
      {
        $result = Yii::app()->db->createCommand()
          ->insert(
            self::$table,
            [
              'id_user' => $id_user,
              'id_vacancy' => $id_vacancy,
              $cnt => ($value ? implode(',',$value) : 1),
              'date_created' => time()
            ]
          );
      }
    }

    return $result;
  }
  /**
   * @param $arFields
   * @param int $vacancy
   * @return int
   * Сброс счетчика(или счетчиков) уведомлений для текущего юзера
   */
  public static function resetCounters($arFields, $vacancy=0, $id_applicant=0)
  {
    $arUpdate = ['date_readed'=>time()];
    $conditions = 'id_user=:id_user';
    $params = [':id_user'=>Share::$UserProfile->id];

    foreach ($arFields as $v)
    {
      $arUpdate[$v] = 0;
    }

    if($vacancy>0)
    {
      $conditions .= ' and id_vacancy=:id_vacancy';
      $params[':id_vacancy'] = $vacancy;
    }
    if($id_applicant>0) // замануха, чтобы удалять отдельного С из списка
    {
      $query = Yii::app()->db->createCommand()
        ->select('*')
        ->from(self::$table)
        ->where($conditions, $params)
        ->queryRow();

      if(isset($query['id']))
      {
        foreach ($arFields as $v)
        {
          $arUsers = explode(',',$query[$v]);
          if (($key = array_search($id_applicant, $arUsers)) !== false)
          {
            unset($arUsers[$key]);
          }
          $query[$v] = implode(',',$arUsers);
        }
        unset($query['id']);
      }
      $arUpdate = $query;
    }
    if($arUpdate)
    {
      return Yii::app()->db->createCommand()
        ->update(self::$table, $arUpdate, $conditions, $params);
    }
    return false;
  }
  /**
   * Метод для крона, который устанавливает временные уведомления и сбрасывает ненужнае при наличии
   */
  public static function setVacancyDateNotificetions()
  {
    $db = Yii::app()->db;
    $yesterday = strtotime('-1 days');
    $tomorrow = strtotime('+1 days');

    $query = $db->createCommand()
      ->select('ev.id, ev.id_user, ec.bdate, ev.remdate')
      ->from('empl_vacations ev')
      ->join('empl_city ec','ec.id_vac=ev.id')
      ->where(
        '(DATE(ec.bdate) BETWEEN :bdate AND :edate) '
        . 'or (ev.remdate=:rdate1) or (ev.remdate=:rdate2)',
        [
          ':bdate'=>date('Y-m-d 00:00:00',$yesterday),
          ':edate'=>date('Y-m-d 23:59:59',$tomorrow),
          ':rdate1'=>date('Y-m-d',$yesterday),
          ':rdate2'=>date('Y-m-d'),
        ]
      )
      ->queryAll();

    if(!count($query))
      return;

    $arCnt = ['disable_all'=>[], 'already_start'=>[], 'today'=>[], 'tomorrow'=>[], 'today_end'=>[]];
    $arDisable = $arToday = $arTomorrow = $arTodayLast = [];
    foreach ($query as $v)
    {
      $bdate = date('Y-m-d',strtotime($v['bdate']));
      // убираем все уведомления, если дата окончания прошла
      $v['remdate']==date('Y-m-d',$yesterday) && $arCnt['disable_all']=$v['id'];
      // убираем ненужные уведомления если дата начала прошла
      $bdate==date('Y-m-d',$yesterday) && $arCnt['already_start'] = $v['id'];
      // ставим уведомление "Проект сегодня завершается"
      $v['remdate']==date('Y-m-d') && $arCnt['today_end']=$v['id'];
      // ставим уведомление "Начало проекта сегодня"(Р) или "Старт работы по утвержденной вакансии сегодня"(С)
      $bdate==date('Y-m-d') && $arCnt['today']=$v['id'];
      // ставим "Старт работы по утвержденной вакансии за 1 день"(С)
      $bdate==date('Y-m-d',$tomorrow) && $arCnt['tomorrow']=$v['id'];
    }
    // убираем ненужные уведомления
    if(count($arCnt['disable_all']))
    {
      $db->createCommand()->update(
        self::$table,
        [
          self::$EMP_RESPONSES => 0,
          self::$EMP_APPROVAL => 0,
          self::$EMP_REFUSALS => 0,
          self::$EMP_START_VACANCY => 0,
          self::$EMP_END_VACANCY => 0,
          self::$APP_INVITATIONS => 0,
          self::$APP_NEW_VACANCIES => 0,
          self::$APP_CONFIRMATIONS => 0,
          self::$APP_REFUSALS => 0,
          self::$APP_START_VACANCY_TOMORROW => 0,
          self::$APP_START_VACANCY => 0,
        ],
        ['in','id_vacancy',$arCnt['disable_all']]
      );
    }
    // убираем ненужные уведомления
    if(count($arCnt['already_start']))
    {
      $db->createCommand()->update(
        self::$table,
        [self::$EMP_START_VACANCY => 0, self::$APP_START_VACANCY => 0],
        ['in','id_vacancy',$arCnt['already_start']]
      );
    }
    // ставим уведомление о завершении
    if(count($arCnt['today_end']))
    {
      $db->createCommand()->update(
        self::$table,
        [self::$EMP_END_VACANCY => 1],
        ['in','id_vacancy',$arCnt['today_end']]
      );
    }
    // ставим уведомление о начале
    if(count($arCnt['today']))
    {
      $db->createCommand()->update(
        self::$table,
        [
          self::$EMP_START_VACANCY => 1,
          self::$APP_START_VACANCY => 1,
          self::$APP_START_VACANCY_TOMORROW => 0
        ],
        ['in','id_vacancy',$arCnt['today']]
      );
    }
    // ставим уведомление о начале завтра
    if(count($arCnt['tomorrow']))
    {
      $db->createCommand()->update(
        self::$table,
        [self::$APP_START_VACANCY_TOMORROW => 1],
        ['in','id_vacancy',$arCnt['tomorrow']]
      );
    }
  }
  /**
   * @param $arIdUser
   * @param $id_vacancy
   * @return bool
   * При модерации вакансии происходит уведомление всех подходящих юзеров
   */
  public static function setNewVacanciesNotifications($arIdUser, $id_vacancy)
  {
    if(!count($arIdUser) || !$id_vacancy)
      return false;

    $arInsert = $arId = [];
    $query = self::getData($arIdUser,$id_vacancy);
    if(count($query))
    {
      foreach ($query as $v)
      {
        $arId[] = $v['id_user'];
      }
    }

    foreach ($arIdUser as $id_user)
    {
      if(count($arId)) // выбираем по каким вакансиям уже есть записи
      {
        if(!in_array($id_user,$arId)) // собираем юзеров, у которых нет записей
        {
          $arInsert[] = [
            'id_vacancy' => $id_vacancy,
            'id_user' => $id_user,
            self::$APP_NEW_VACANCIES => 1,
            'date_created' => time()
          ];
        }
      }
      else // добавляем в массив для создания записи
      {
        $arInsert[] = [
          'id_vacancy' => $id_vacancy,
          'id_user' => $id_user,
          self::$APP_NEW_VACANCIES => 1,
          'date_created' => time()
        ];
      }
    }

    if(count($arInsert)) // создаем новые записи
    {
      Share::multipleInsert([self::$table => $arInsert]);
    }
  }

    /**
     * Set message notifications for not have email users
     * Work once at 24 hours.
     *
     * @return array|void
     */
    public static function setMSGForHaveNTEmailUsers() {

        // not have email end moderation job seekers
        $queryMail = "
            SELECT
                u.id_user,
                u.email,
                u.crdate,
                u.mdate,
                u.status,
                r.ismoder
            FROM
                user u
            INNER JOIN 
                resume r ON r.id_user=u.id_user    
            WHERE
                DATE(u.crdate) >= NOW() - INTERVAL 1 DAY 
                or 
                DATE(u.mdate) >= NOW() - INTERVAL 1 DAY 
            GROUP BY u.id_user
        ";
        $queryMail = Yii::app()->db->createCommand($queryMail)->queryAll();

        if(count($queryMail)){

            foreach ($queryMail as $user){
                $email = $user['email'];
                if ((!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email))
                    || ( $email = '' )){
                    /**
                     * Send messages to users who did not enter the email
                     */
                    $resultm['users'][] = $user['id_user'];
                    $resultm['title'] = 'Администрация';
                    $resultm['text'] = 'Уважаемый пользователь, будьте добры заполнить 
                                       поле e-mail в вашем профиле.
                                       Заранее спасибо!';
                }
            }
            $admMsgM = new AdminMessage();

            // send notifications
            if (isset($resultm)) {
                $admMsgM->sendDataByCron($resultm);
            }

            foreach ($queryMail as $user){
                $status = $user['status'];
                $ismoder = $user['ismoder'];
                if ( ($status == 2) && ($ismoder != 1)){
                    /**
                     * Send messages to users who did not enter moderation information
                     */
                    $resultjs['users'][] = $user['id_user'];
                    $resultjs['title'] = 'Администрация';
                    $resultjs['text'] = 'Уважаемый пользователь, заполните '.
                                       'поля, необходимые для модерации:'.
                                       '<ul style="text-align:left">'.
                                           '<li>имя и фамилия</li>'.
                                           '<li>год рождения</li>'.
                                           '<li>Фото(допускается и без него, на фото не природа, '.
                                           'не больше одной личности, не анимация и т.д)</li>'.
                                           '<li>номер телефона и эмейл</li>'.
                                           '<li>целевая вакансия</li>'.
                                           '<li>раздел "О себе"</li>'.
                                       '<ul>';
                }
            }
            $admMsgJS = new AdminMessage();
            if (isset($resultjs)) {
                $admMsgJS->sendDataByCron($resultjs);
            }

        }

        $queryEmpl = "
            SELECT
                u.id_user,
                u.email,
                u.crdate,
                u.mdate,
                u.status,
                e.ismoder
            FROM
                user u
            INNER JOIN 
                employer e ON e.id_user=u.id_user    
            WHERE
                (
                DATE(u.crdate) >= NOW() - INTERVAL 1 DAY 
                or 
                DATE(u.mdate) >= NOW() - INTERVAL 1 DAY
                ) and u.status = 3 
                
            GROUP BY u.id_user
        ";
        $queryEmpl = Yii::app()->db->createCommand($queryEmpl)->queryAll();

        if(count($queryEmpl)) {

            foreach ($queryEmpl as $user){
                $ismoder = $user['ismoder'];
                if ( $ismoder != 1 ){
                    /**
                     * Send messages to employer who did not enter moderation information
                     */
                    $resulte['users'][] = $user['id_user'];
                    $resulte['title'] = 'Администрация';
                    $resulte['text'] = 'Уважаемый пользователь, заполните '.
                                       'поля в вашем профиле, необходимые для модерации:'.
                                       '<ul style="text-align:left">'.
                                           '<li>название компании</li>'.
                                           '<li>логотип - желаельно</li>'.
                                           '<li>номер телефона и эмейл</li>'.
                                           '<li>имя контактного лица</li>'.
                                           '<li>контактный номер телефона</li>'.
                                       '<ul>';
                }
            }
            $admMsgE = new AdminMessage();
            if (isset($resulte)) {
                $admMsgE->sendDataByCron($resulte);
            }
        }

    }
}