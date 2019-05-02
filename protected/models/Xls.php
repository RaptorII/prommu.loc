<?php

class Xls
{
	/**
	 * @param $arHead - array
	 * @param $arRows - array
	 * @param $fName - string
	 */
	public static function makeFile($arHead, $arRows, $fName='xls_file')
	{
		if(!count($arHead) || !count($arRows)	|| empty(trim($fName)))
			return false;

		$sFile = '<table border="1"><tr>';

		foreach ($arHead as $v)
		{
			$sFile .= '<td style="background:#E0E0E0;text-align:center"><b>'
								. self::convertValue($v) . '</b></td>';
		}
		$sFile .= '</tr>';

		foreach ($arRows as $arRow)
		{
			$sFile .= '<tr>';
			foreach ($arRow as $v)
			{
				$sFile .= '<td style="text-align:center;vertical-align:middle">' 
								. self::convertValue($v) . '</td>';
			}
			$sFile .= '</tr>';
		}

		$sFile .= '</table>';

		$fPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $fName . '.xls';
		$result = file_put_contents($fPath, $sFile);

		self::uploadFile($fPath);

		return $result!=='false';
	}
	/**
	* @param $file_path - string
	* @param $file_name - string
	*/
	public static function uploadFile($file)
	{
		if(empty(trim($file)))
			return false;

		Yii::app()->request->sendFile(basename($file), file_get_contents($file));
	}
	/**
	 * @param $value - string
	 */
	public static function convertValue($value)
	{
		return htmlentities(
							iconv("utf-8","windows-1251",$value),
							ENT_QUOTES,
							"cp1251"
						);
	}
}
?>