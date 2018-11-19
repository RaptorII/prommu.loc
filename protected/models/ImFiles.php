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
                if( $file[1] == $chatId )
                {
                    // отдаём картинки
                    if( in_array($pathinfo['extension'], ['jpg','png','jpeg','gif']) )
                    {
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
                if( in_array($pathinfo['extension'], ['jpg','png','jpeg','gif']) )
                {
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