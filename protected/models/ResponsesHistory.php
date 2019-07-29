<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 24.07.2019
 * Time: 17:17
 */

class ResponsesHistory
{
  public static $table = 'vacation_stat_history';
  /**
   * @param $id_response
   * @param $id_user
   * @param $statusBefore
   * @param $statusAfter
   */
  public static function setData($id_response, $id_user, $statusBefore, $statusAfter)
  {
    Yii::app()->db->createCommand()
      ->insert(
        self::$table,
        [
          'id_response' => $id_response,
          'id_user' => $id_user,
          'status_before' => $statusBefore,
          'status_after' => $statusAfter,
          'date' => time()
        ]
      );
  }
  /**
   * @param $arResponses - array (ID from vacanction_stat)
   */
  public function getAllData($arResponses)
  {
    $arRes = ['items'=>[],'cnt'=>0];
    if(!count($arResponses))
      return $arRes;

    $arId = [];
    foreach ($arResponses as $v)
    {
      $arId[] = $v['id'];
      $arRes['items'][$v['id']] = $v;
      $arRes['items'][$v['id']]['items'] = [];
    }

    $query = Yii::app()->db->createCommand()
      ->select('*')
      ->from(self::$table)
      ->where(['in','id_response',$arId])
      ->order('id desc')
      ->queryAll();

    if(!count($query))
      return $arRes;

    foreach ($query as $v)
    {
      $arRes['items'][$v['id_response']]['items'][$v['id']] = $v;
      $arRes['cnt']++;
    }

    return $arRes;
  }

  /**
   * @param $status1 - vacation_stat => status
   * @param $status2 - vacation_stat => status
   * @param $response1 - vacation_stat => isresponse
   * @param $response2 - vacation_stat => isresponse
   * @return string
   */
  public static function getState($status1, $status2, $response1, $response2)
  {
    $s1 = self::getStatusState($status1, $response1);
    $s2 = self::getStatusState($status2, $response1);

    if($response2==1 && $status1==0)
    {
      $s1 = 'Соискатель отозвался(повторно)';
    }
    if($response2==1 && $status2==0 && !empty($s1))
    {
      $s2 = 'Соискатель отозвался(повторно)';
    }

    return '<b>' . $s1 . '</b> <span>=></span> <b>' . $s2 . '</b>';
  }
  /**
   * @param $status - vacation_stat => status
   * @param $response - vacation_stat => isresponse
   * @return string
   */
  public static function getStatusState($status, $response)
  {
    $result = '';
    if($response==2) // Р
    {
      switch ($status)
      {
        case 0: $result='Соискатель отозвался'; break;
        case 1: $result='Работодатель просмотрел(отложил)'; break;
        case 2: $result='Соискатель просмотрел'; break;
        case 3: $result='Соискатель отклонил'; break;
        case 4: $result='Работодатель пригласил'; break;
        case 5: $result='Утверждено обоими'; break;
        case 6: $result='Проект завершен(ожидание рейтинга)'; break;
        case 7: $result='Работодатель выставил рейтинг(отзыв)'; break;
        case 8: $result='Соискатель выставил рейтинг(отзыв)'; break;
        case 9: $result='Все выставили рейтинг(отзыв)'; break;
        default: break;
      }
    }
    else // С
    {
      switch ($status)
      {
        case 0: $result='Соискатель отозвался'; break;
        case 1: $result='Работодатель просмотрел(отложил)'; break;
        case 2: $result='Соискатель просмотрел'; break;
        case 3: $result='Работодатель отклонил'; break;
        case 4: $result='Работодатель пригласил'; break;
        case 5: $result='Утверждено обоими'; break;
        case 6: $result='Проект завершен(ожидание рейтинга)'; break;
        case 7: $result='Работодатель выставил рейтинг(отзыв)'; break;
        case 8: $result='Соискатель выставил рейтинг(отзыв)'; break;
        case 9: $result='Все выставили рейтинг(отзыв)'; break;
        default: break;
      }
    }

    return $result;
  }
}