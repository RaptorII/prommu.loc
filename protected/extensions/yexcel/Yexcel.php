<?
	/**
	 * Yii Excel File Reader (Yexcel) Class
	 * by: Michel Kogan
	 * --------------------
	 * LICENSE
	 * --------------------
	 * This program is open source product; you can redistribute it and/or
	 * modify it under the terms of the GNU General Public License (GPL)
	 * as published by the Free Software Foundation; either version 2
	 * of the License, or (at your option) any later version.
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
	 *
	 * --------------------
	 * @package    Yexcel
	 * @author     Michel Kogan <kogan.michel@gmail.com
	 * @copyright  2012 Michel Kogan
	 * @license    http://www.gnu.org/copyleft/gpl.html  GNU General Public License (GPL)
	 * @link       http://www.sparta.ir
	 * @see        FileSystem
	 * @version    1.0.0
	 *
	 *
	 */
	/** Include path **/
	set_include_path(get_include_path() . PATH_SEPARATOR . Yii::app()->basePath.'/extensions/yexcel/Classes/');

	/** PHPExcel_IOFactory */
	include 'PHPExcel/IOFactory.php';
	include 'PHPExcel.php';

	class Yexcel
	{
		function __construct()
		{
		}

		public function init()
		{
		}

		public function readActiveSheet( $file )
		{
			$objPHPExcel = PHPExcel_IOFactory::load($file);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			return $sheetData;
		}

		public function setActiveSheet( $data ){
			 $objPHPExcel = new PHPExcel();
	        

	        $objPHPExcel->getProperties()->setCreator("PROMMU")
	             ->setLastModifiedBy("PROMMU")
	             ->setTitle("PROMMU")
	             ->setSubject("PROMMU")
	             ->setDescription("PROMMU EXPORT")
	             ->setKeywords("PROMMU")
	             ->setCategory("PROMMU");        
	        
	        // Add some data
	        $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', 'Город')
	            ->setCellValue('B1', 'Локация')
	            ->setCellValue('C1', 'Улица')
	            ->setCellValue('D1', 'Дом');
	            ->setCellValue('E1', 'Здание');
	            ->setCellValue('F1', 'Строение');
	            ->setCellValue('H1', 'Дата работы');
	            ->setCellValue('G1', 'Время работы');
	            ->setCellValue('I1', 'Идентификатор');
	        
	        for($i = 0; $i < sizeof($data); $i ++){
	        	 $city = Yii::app()->db->createCommand()
                    ->select('c.name')
                    ->from('city c')
                    ->where('c.id_city = :id_city', array(':id_city' =>$data[$i]['id_city']))
                    ->queryRow();

                $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue("A{$i}", $city['name'])
	            ->setCellValue("B{$i}", $data[$i]['name'])
	            ->setCellValue("C{$i}", $data[$i]['adres'])
	            ->setCellValue("D{$i}", $data[$i]['adres']);
	            ->setCellValue("E{$i}", $data[$i]['adres']);
	            ->setCellValue("F{$i}", $data[$i]['adres']);
	            ->setCellValue("H{$i}", $data[$i]['bdate'].'-'.$data[$i]['edate']);
	            ->setCellValue("G{$i}", $data[$i]['btime'].'-'.$data[$i]['etime']);
	            ->setCellValue("I{$i}", $data[$i]['point']);

	        }
	        
	        // Rename worksheet
	        $objPHPExcel->getActiveSheet()->setTitle('PROMMU');
	        
	        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	        $objPHPExcel->setActiveSheetIndex(0);
	        
	        // Save a xls file
	        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/project_export.xls';
	        header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="project_export.xls"');
	        header('Cache-Control: max-age=0');
	        
	        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	        $objWriter->save('php://output');
	        unset($this->objWriter);
	        unset($this->objWorksheet);
	        unset($this->objReader);
	        unset($this->objPHPExcel);
	        readfile($file_name); // считываем файл
	        exit();
		}
	}

?>