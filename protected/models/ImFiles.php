<?php
/**
 * Date: 31.08.2016
 *
 * Получение файлов из protected для Im
 */

class ImFiles extends Model
{
    /**
     * Получаем чаты пользователя
     */
    public function getFile()
    {
        $file = filter_var(Yii::app()->getRequest()->getParam('f'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $file = explode(',', $file, 2);

        $chatId = Yii::app()->session['imdata']['chatId'];
        $imagesMime = array('jpg' => 'image/jpeg',
            'png' => 'image/png',);

        try
        {
            // загруженные файлы
            if( strpos($file[0], 'tmp/') )
            {
                $pathinfo = pathinfo($file[0]);

//$s1 = 'file_exists='.var_export(file_exists(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/tmp/' . $pathinfo['basename']), 1)."\n";
//$s1 .= 'file_exists='.var_export((MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/tmp/' . $pathinfo['basename']), 1)."\n";
//$s1 .= '$file[1] == $chatId='.var_export([$file[1], $chatId], 1)."\n";
////0||$notpr||file_put_contents('/var/www/html/11/file', "\n--------------------\n".date("H:i:s")."\n".$s1, 0);//FILE_APPEND
//0||$notpr||file_put_contents('/var/www/dev.prommu.com/11/file1', "\n--------------------\n".date("H:i:s")."\n".$s1, 0);//FILE_APPEND
                if( $file[1] == $chatId )
                {
                    // отдаём картинки
                    if( in_array($pathinfo['extension'], ['jpg', 'png']) )
                    {
    //                    print "<p>hello</p>\n";

                        header("Content-type: " . $imagesMime[$pathinfo['extension']]);
                        readfile(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/tmp/' . $pathinfo['basename']);


                    // отдаём файлы для скачивания
                    } elseif (in_array($pathinfo['extension'], ['doc', 'docx', 'xls', 'xlsx']) )
                    {
                        $quoted = sprintf('"%s"', addcslashes($pathinfo['basename'], '"\\'));
                        $size   = filesize(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/tmp/' . $pathinfo['basename']);

                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename=' . $quoted);
                        header('Content-Transfer-Encoding: binary');
                        header('Connection: Keep-Alive');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . $size);

                        readfile(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/tmp/' . $pathinfo['basename']);
                    } // endif
                }
                else
                {
                } // endif


            // файлы соощения
            } else {
                $pathinfo = pathinfo($file[0]);

                // отдаём картинки
                if( in_array($pathinfo['extension'], ['jpg', 'png']) )
                {
//                    print "<p>hello</p>\n";

                    header("Content-type: " . $imagesMime[$pathinfo['extension']]);
                    readfile(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/' . $pathinfo['basename']);


                // отдаём файлы для скачивания
                } elseif (in_array($pathinfo['extension'], ['doc', 'docx', 'xls', 'xlsx']) )
                {
                    $quoted = sprintf('"%s"', addcslashes($pathinfo['basename'], '"\\'));
                    $size   = filesize(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/' . $pathinfo['basename']);

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $quoted);
                    header('Content-Transfer-Encoding: binary');
                    header('Connection: Keep-Alive');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . $size);

                    readfile(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_CONTENT_PROTECTED . '/im/' . $pathinfo['basename']);
                } // endif
            } // endif

        }
        catch (Exception $e) {
            $e->getMessage();
        } // endtry
    }
}