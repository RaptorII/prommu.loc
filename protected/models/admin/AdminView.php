<?
class AdminView
{
	/**
	 * @param $obj - object gridView
	 * @param $n1 - string name input
	 * @param $n2 - string name input
	 */
	public static function filterDateRange($obj,$n1,$n2)
	{
		$arr = ['class' => 'grid_date','autocomplete'=>'off'];
		$html = '<div class="filter_date_range">'
			. $obj->widget('zii.widgets.jui.CJuiDatePicker',
				[
					'name'=>$n1,
					'value'=>Yii::app()->getRequest()->getParam($n1),
					'options'=>['changeMonth'=>true],
					'htmlOptions'=>$arr
				],
				true)
			. '<div class="separator">-</div>'
			. $obj->widget('zii.widgets.jui.CJuiDatePicker',
				[
					'name'=>$n2,
					'value'=>Yii::app()->getRequest()->getParam($n2),
					'options'=>['changeMonth'=>true],
					'htmlOptions'=>$arr
				],
				true)
			. '</div>';

		return $html;
	}
	/**
	 * @param $link - string
	 * @param $name - string
	 * @param $is_btn - bool
	 */
	public static function getLink($link, $name, $is_btn=false)
	{
		if(!$name)
			return ' - ';

		return  "<a href='$link' " . ($is_btn ? 'class="btn btn-default"' : '') . ">$name</a> ";
	}
  /**
   * @param $value - string
   * @return string
   */
  public static function getStr($value)
  {
    return (!empty($value) ? $value : '-');
  }
  /**
   * @param $type - integer ( 2 || 3 )
   * @return string - html
   */
  public static function getUserType($type)
  {
    if(Share::isApplicant($type))
    {
      return '<span class="glyphicon glyphicon-user" title="соискатель"></span>';
    }
    elseif(Share::isEmployer($type))
    {
      return '<span class="glyphicon glyphicon-briefcase" title="работодатель"></span>';
    }
    else
    {
      return '<span class="glyphicon glyphicon-baby-formula" title="гость"></span>';
    }
  }
  /**
   * @param $id_user - integer (user => id_user)
   * @param $type - integer ( 2 | 3 )
   * @return string
   */
  public static function getUserProfileLink($id_user, $type)
  {
    if(!$id_user)
    {
      return '-';
    }
    else
    {
      return '<a href="/admin/' . (Share::isApplicant($type) ? 'PromoEdit' : 'EmplEdit')
        . '/' . $id_user . '" class="glyphicon glyphicon-edit" title="редактировать"></a>';
    }
  }
  /**
   * @param $id - integer (subdomains => id)
   * @return string
   */
  public static function getSubdomain($id)
  {
    if(!$id)
      return ' - ';

    return Subdomain::getCacheData()->data_list[$id];
  }

  public static function getRegisterLogin($login, $type)
  {
    if(!$login)
      return ' - ';

    if($type==UserRegister::$LOGIN_TYPE_EMAIL)
    {
      return $login;
    }
    if($type==UserRegister::$LOGIN_TYPE_PHONE)
    {
      $arPhone = Share::getPrettyPhone($login);
      return $arPhone['code'] . $arPhone['phone'];
    }
  }
  /**
   * @param $value - integer (user => isblocked | user => ismoder)
   * @param $id_user - integer (user => id_user)
   * @param $field - string ('isblocked' | 'ismoder')
   */
  public static function getUserStatus($value, $id_user, $field)
  {
    if($field=='isblocked')
    {
      $arr = User::getAdminArrIsblocked();
      $arIcons = [
        User::$ISBLOCKED_FULL_ACTIVE => 'success',
        User::$ISBLOCKED_BLOCKED => 'important',
        User::$ISBLOCKED_NEW_USER => 'warning',
        User::$ISBLOCKED_EXPECT => 'info'
      ];
    }
    else
    {
      $arr = User::getAdminArrIsmoder();
      $arIcons = [
        User::$ISMODER_INACTIVE => 'primary',
        User::$ISMODER_ACTIVE => 'success',
        User::$ISMODER_PROCESSING => 'warning'
      ];
    }

    $html = '<div class="dropdown select_update">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >
        <span class="label label-' . $arIcons[$value] . '">' . $arr[$value] . '</span>
        <span class="caret"></span>
      </button>';

    $html .= '<ul class="dropdown-menu ' . $field . '" aria-labelledby="dropdownMenu1">';
    foreach ($arr as $key => $v)
    {
      $html .= '<li onclick = "' . ($field=='isblocked' ? 'doStatus' : 'doStatusModer') . '(' . $id_user . ', ' . $key
        . ')" class="label label-' . $arIcons[$key] . '"> ' . $v . '</li >';
    }
    $html .= '</ul></div>';
    return $html;
  }
  /**
   * @param $id_user - user => id_user
   * @return string
   * достаем список городов для списка юзеров
   */
  public static function getUserCities($id_user)
  {
    $query = Yii::app()->db->createCommand()
      ->select('c.name')
      ->from('user_city uc')
      ->join('city c','uc.id_city=c.id_city')
      ->where('uc.id_user=:id', [':id'=>$id_user])
      ->queryColumn();

    return (count($query) ? implode(', ',$query) : '-');
  }
}
?>