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
  /**
   * Generate PDF
   * https://github.com/cystbear/PHPExcel/blob/master/Tests/01simple-download-pdf.php
   */
	public static function makePDF()
  {
    $phpExcelPath = Yii::getPathOfAlias('ext.yexcel.Classes');
    // making use of our reference, include the main class
    // when we do this, phpExcel has its own autoload registration
    // procedure (PHPExcel_Autoloader::Register();)
    include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

    PHPExcel_Settings::PDF_RENDERER_MPDF;

    // Create new PHPExcel object
    $yexcel = new PHPExcel();

    // Set properties
    $yexcel->getProperties()->setCreator("Maarten Balliauw")
      ->setLastModifiedBy("Maarten Balliauw")
      ->setTitle("PDF Test Document")
      ->setSubject("PDF Test Document")
      ->setDescription("Test document for PDF, generated using PHP classes.")
      ->setKeywords("pdf php")
      ->setCategory("Test result file");

    // Add some data
    $yexcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Hello')
      ->setCellValue('B2', 'world!')
      ->setCellValue('C1', 'Hello')
      ->setCellValue('D2', 'world!');

    // Miscellaneous glyphs, UTF-8
    $yexcel->setActiveSheetIndex(0)
      ->setCellValue('A4', 'Miscellaneous glyphs')
      ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

    // Rename sheet
    $yexcel->getActiveSheet()->setTitle('Simple');

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $yexcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="01simple.pdf"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($yexcel, 'PDF');
    $objWriter->save('php://output');
    Yii::app()->end();
  }
}
?>