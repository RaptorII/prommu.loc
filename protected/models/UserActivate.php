<?php

/**
 * This is the model class for table "user_status".
 *
 * The followings are the available columns in table 'user_status':
 * @property integer $id
 * @property string $key
 */
class UserActivate extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_activate';
	}


    public function scopes()
    {
        return array(
            'lastByDate' => array(
                'order' => 'dt_create DESC',
                'limit' => 1,
            ),
        );
    }



    public function getById($idus)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'id_user = ' . $idus,
        ));
        return $this;
    }

  public function updateData($id_user, $arData)
  {
    $data = Yii::app()->db->createCommand()
      ->select('data')
      ->from($this::tableName())
      ->where('id_user=:id',[':id'=>$id_user])
      ->queryScalar();

    if(!strlen($data) || !count($arData))
      return false;

    $arJson = json_decode($data, true);
    foreach ($arData as $k => $v)
    {
      $arJson[$k] = $v;
    }
    $arJson = json_encode($arJson);

    return Yii::app()->db->createCommand()
      ->update(
        $this::tableName(),
        ['data'=>$arJson],
        'id_user=:id',
        [':id'=>$id_user]
      );
  }
}