<?php
/**
 * Date: 31.08.2016
 *
 * Получение файлов из protected для Im
 */
class ImFiles extends Model
{
    public function getFile()
    {
        $file = filter_var(
                        Yii::app()->getRequest()->getParam('f'), 
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    );

        $file = explode(',', $file, 2);
        $pathinfo = pathinfo($file[0]);
        $arAccess = Yii::app()->session['imdata'];
        $imagesMime = array('jpg'=>'image/jpeg','png'=>'image/png');
        $fullPath = MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED;
        
        try
        {
            if( // загруженные файлы
                strpos($file[0], 'tmp/')
                &&
                (in_array($file[1], $arAccess['chatId']) 
                || 
                in_array($file[1], $arAccess['vacancy']))
            )
            {
                $fullPath .= '/im/tmp/' . $pathinfo['basename'];
            } 
            else // файлы соощения
            {
                $fullPath .= '/im/' . $pathinfo['basename'];
            }


            if( in_array($pathinfo['extension'], ['jpg','png','jpeg','gif']) )
            { // отдаём картинки
                header("Content-type: " . $imagesMime[$pathinfo['extension']]);
                readfile($fullPath);
            }
            elseif ( in_array($pathinfo['extension'], ['doc', 'docx', 'xls', 'xlsx']) )
            { // отдаём файлы для скачивания
                $quoted = sprintf('"%s"', addcslashes($pathinfo['basename'], '"\\'));
                $size   = filesize($fullPath);

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $quoted);
                header('Content-Transfer-Encoding: binary');
                header('Connection: Keep-Alive');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . $size);

                readfile($fullPath);
            }
        }
        catch (Exception $e)
        {
            $e->getMessage();
        } // endtry
    }
}