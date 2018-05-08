<?php
/*
foreach($items as $r) {
    echo $r["id"];
    echo "&nbsp;&nbsp;".$r["cmd"]."<br>";
}
*/

/*
function writeDocumentProperties()
{
    fwrite($this->file, '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">');
        fwrite($this->file, '<Author>prommu.com</Author>');
        fwrite($this->file, '<LastAuthor></LastAuthor>');

    $dt = new Datetime();
    $dt_string = $dt->format('Y-m-d\TH:i:s\Z');
    fwrite($this->file, '<Created>'.$dt_string.'</Created>');
    fwrite($this->file, '<LastSaved>'.$dt_string.'</LastSaved>');

    fwrite($this->file, '<Company>prommu.com</Company>');

    fwrite($this->file, '<Version>12.00</Version>');
    fwrite($this->file, '</DocumentProperties>');
}


function writeStyles()
{
    fwrite($this->file, '<Styles>');
    //default style
    fwrite($this->file, '<Style ss:ID="Default" ss:Name="Normal"><Font ss:Color="#000000"/></Style>');
    //Datetime style
    fwrite($this->file, '<Style ss:ID="DateTime"><NumberFormat ss:Format="General Date"/></Style>');
    fwrite($this->file, '<Style ss:ID="Date"><NumberFormat ss:Format="Short Date"/></Style>');
    fwrite($this->file, '<Style ss:ID="Time"><NumberFormat ss:Format="h:mm:ss"/></Style>');
    //Hyperlink style
    fwrite($this->file, '<Style ss:ID="Hyperlink" ss:Name="Hyperlink"><Font ss:Color="#0000FF" ss:Underline="Single"/></Style>');
    //Bold
    fwrite($this->file, '<Style ss:ID="Bold"><Font ss:Bold="1"/></Style>');
    fwrite($this->file, '</Styles>');
}


function openWorksheet()
{
    fwrite($this->file, '<Worksheet ss:Name="Export">');
    fwrite($this->file, strtr('<Table ss:ExpandedColumnCount="{col_count}" ss:ExpandedRowCount="{row_count}" x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="15">', array('{col_count}'=>$this->colCount, '{row_count}'=>$this->rowCount)));
}


function resetRow()
{
    $this->currentRow = array();
}

function flushRow()
{
    fwrite($this->file, implode('', $this->currentRow));
    unset($this->currentRow);
}

function appendCellNum($value)
{
    $this->currentRow[] = '<Cell><Data ss:Type="Number">'.$value.'</Data></Cell>';
}
*/


function exportExcelXML(&$items, &$object) {

$cc = '<?xml version="1.0"?>'.
'<?mso-application progid="Excel.Sheet"?>'.
'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">'.
'    <Styles>
        <Style ss:ID="bold">
            <Font ss:Bold="1"/>
            <Font ss:Color="#FF0000"/>
            <Borders>
	            <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"
	            ss:Color="#902020"/>
	        </Borders>

        </Style>
        <Style ss:ID="blue">
            <Font ss:Color="#0000C0"/>
        </Style>
    </Styles>
    <Worksheet ss:Name="WorksheetName">
        <Table>
        <Column ss:Width="90"/>';
    $cc .='<Row><Cell><Data ss:Type="String">Логин</Data></Cell><Cell><Data ss:Type="String">'.$items[0]["login"].'</Data></Cell></Row>';
    $cc .='<Row><Cell><Data ss:Type="String">Email</Data></Cell><Cell><Data ss:Type="String">'.$items[0]["email"].'</Data></Cell></Row>';
    $cc .='<Row>';
    $cc .='<Cell ss:StyleID="bold"><Data ss:Type="String">ID</Data></Cell>'.
        '<Cell ss:StyleID="bold"><Data ss:Type="String">CMD</Data></Cell>'.
        '<Cell ss:StyleID="bold"><Data ss:Type="String">DATE</Data></Cell>';


    /*
                {foreach from=$data.header item=caption}
                <Cell ss:StyleID="bold"><Data ss:Type="String">{$caption.columnName}</Data></Cell>
                {/foreach}
    */
    $cc .='</Row>';

    foreach($items as $r) {
        $cc .='<Row>';
        $cc .='<Cell><Data ss:Type="String">'.$r["id"].'</Data></Cell>';
        $cc .='<Cell ss:StyleID="blue"><Data ss:Type="String">'.$r["cmd"].'</Data></Cell>';
        $cc .='<Cell><Data ss:Type="String">'.$r["dtcreate"].'</Data></Cell>';
        $cc .='</Row>';
    }
    $cc .='    </Table>';
    $cc .='</Worksheet>';
    $cc .='</Workbook>';

    $object["content"] = $cc;
}

/*
public function exportExcelXML(&$items, &$object)
{
    Yii::import('ext.AlxdExportExcelXML.AlxdExportExcelXML');
    $export = new AlxdExportExcelXML($object["fileName"], count($this->_attributes), $this->_provider->getTotalItemCount() + 1);

    $export->openWriter();
    $export->openWorkbook();

    $export->writeDocumentProperties();
    $export->writeStyles();
    $export->openWorksheet();

    //title row
    $export->resetRow();
    $export->openRow(true);
    foreach ($items as $code => $format)
        $export->appendCellString($this->_objectref->getAttributeLabel($code));
    $export->closeRow();
    $export->flushRow();

    //data rows
    //$rows = new CDataProviderIterator($this->_provider, 100);
    foreach ($items as $row)
    {
        $export->resetRow();
        $export->openRow();

        foreach ($this->_attributes as $code => $format)
        {
            switch ($format->type)
            {
                case 'Num':
                    $export->appendCellNum($row[$code]);

                default:
                    $export->appendCellString('');
            }

        }
        $export->closeRow();
        $export->flushRow();
    }

    //close all
    $export->closeWorksheet();
    $export->closeWorkbook();
    $export->closeWriter();

    $object["content"] = $export->

    //zip file
    //$export->zip();

    //$filename = $export->getZipFullFileName();
}
*/

/*
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
	<Styles>
  		<Style ss:ID="bold">
			<Font ss:Bold="1"/>
		</Style>
 	</Styles>
	<Worksheet ss:Name="WorksheetName">
		<Table>
			<Row>
				{foreach from=$data.header item=caption}
					<Cell ss:StyleID="bold"><Data ss:Type="String">{$caption.columnName}</Data></Cell>
				{/foreach}
			</Row>
			{foreach from=$data.content item=row}
				<Row>
					{foreach from=$row item=col}
						<Cell><Data ss:Type="String">{$col}</Data></Cell>
					{/foreach}
				</Row>
			{/foreach}
		</Table>
	</Worksheet>
</Workbook>
 */
?>