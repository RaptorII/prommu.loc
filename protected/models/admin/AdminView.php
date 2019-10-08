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
}
?>