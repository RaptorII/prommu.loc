<?php

class Yexcel
{
	/**
	 * @param $arHead - array
	 * @param $arRows - array
	 * @param $fName - string
	 */
	public static function makeExcel($arHead, $arRows, $fName='xls_file', $arAutoSize=false)
	{
		if(!count($arHead)	|| empty(trim($fName)))
			return false;

		$phpExcelPath = Yii::getPathOfAlias('ext.yexcel.Classes');
		// making use of our reference, include the main class
		// when we do this, phpExcel has its own autoload registration
		// procedure (PHPExcel_Autoloader::Register();)
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		$arCols = self::getExcelCols();

		$yexcel = new PHPExcel();
		$yexcel->getProperties()->setCreator("Prommu")
						->setLastModifiedBy("Prommu")
						->setTitle($fName);

		$yexcel->setActiveSheetIndex(0);
		$yexcel->getActiveSheet()->setTitle('Prommu');
		$objSheet = $yexcel->getActiveSheet(0);
		
		// head
		$row = 1;
		reset($arCols);
		foreach ($arHead as $k => $v)
		{
			$cell = current($arCols).$row;
			$objSheet->setCellValue($cell, $v);
			self::setCellBackground($yexcel, $cell, 'ABB837');
			self::setCellFontColor($yexcel, $cell, 'FFFFFF');
			if(!$arAutoSize || (is_array($arAutoSize) && in_array($k, $arAutoSize)))
				$objSheet->getColumnDimension(current($arCols))->setAutoSize(true);
			next($arCols);
		}
		// body
		foreach ($arRows as $arRow)
		{
			$row++;
			reset($arCols);
			foreach ($arRow as $v)
			{
				$cell = current($arCols).$row;
				$objSheet->setCellValue($cell, $v);
				//$objSheet->getColumnDimension(current($arCols))->setAutoSize(true);
				next($arCols);
			}
		}

		header('Content-Disposition: attachment; filename="' . $fName . '.xlsx"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

    $objWriter = PHPExcel_IOFactory::createWriter($yexcel, 'Excel2007');
    $objWriter->save('php://output');
    Yii::app()->end();
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
	 *
	 */
	public static function getExcelCols()
	{
		$alphas = range('A', 'Z');
		$arResult = $alphas;

		foreach ($alphas as $v)
		{
			$arResult[] = "A$v";
		}

		return $arResult;
	}
	/**
	 * Background
	 */
	public static function setCellBackground(&$obj, $cells, $color)
	{
		$obj->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(
				array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => array('rgb' => $color)
			));
	}
	/**
	 * Font color
	 */
	public static function setCellFontColor(&$obj, $cells, $color)
	{
		$obj->getActiveSheet()->getStyle($cells)->applyFromArray(
				['font' => ['color' => ['rgb' => $color] ] ]
			);
	}
}
?>