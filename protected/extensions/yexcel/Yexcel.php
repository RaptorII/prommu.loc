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
            ->setCellValue('B1', 'Название ТТ')
            ->setCellValue('C1', 'Улица')
            ->setCellValue('D1', 'Дом')
            ->setCellValue('E1', 'Здание')
            ->setCellValue('F1', 'Строение')
            ->setCellValue('G1', 'Корпус')
            ->setCellValue('H1', 'Дата работы Старт')
            ->setCellValue('I1', 'Дата работы Финал')
            ->setCellValue('J1', 'Время работы')
            ->setCellValue('K1', 'Комментарий')
            ->setCellValue('L1', 'Идентификатор');
        $j = 2;
        for($i = 0; $i < sizeof($data); $i ++){

            $city = Yii::app()->db->createCommand()
                ->select('c.name')
                ->from('city c')
                ->where('c.id_city = :id_city', array(':id_city' =>$data[$i]['id_city']))
                ->queryRow();

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$j}", $city['name'])
                ->setCellValue("B{$j}", $data[$i]['name'])
                ->setCellValue("C{$j}", $data[$i]['adres'])
                ->setCellValue("D{$j}", $data[$i]['house'])
                ->setCellValue("E{$j}", $data[$i]['building'])
                ->setCellValue("F{$j}", $data[$i]['construction'])
                ->setCellValue("G{$j}", $data[$i]['corps'])
                ->setCellValue("H{$j}", $data[$i]['bdate'])
                ->setCellValue("I{$j}", $data[$i]['edate'])
                ->setCellValue("J{$j}", $data[$i]['btime'].'-'.$data[$i]['etime'])
                ->setCellValue("K{$j}", $data[$i]['comment'])
                ->setCellValue("L{$j}", $data[$i]['point']);
            $j++;
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

    public function setActiveSheetUsers( $data ){
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
            ->setCellValue('A1', 'Имя')
            ->setCellValue('B1', 'Фамилия')
            ->setCellValue('C1', 'Электронная почта')
            ->setCellValue('D1', 'Телефон')
            ->setCellValue('E1', 'Локации');
        $j = 2;
        for($i = 0; $i < sizeof($data); $i ++){

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A{$j}", $data[$i]['firstname'])
                ->setCellValue("B{$j}", $data[$i]['lastname'])
                ->setCellValue("C{$j}", $data[$i]['email'])
                ->setCellValue("D{$j}", $data[$i]['phone'])
                ->setCellValue("E{$j}", $data[$i]['point']);
            $j++;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('PROMMU');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Save a xls file
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/users_export.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="users_export.xls"');
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