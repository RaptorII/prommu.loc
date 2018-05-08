<?php
/**
 * Date: 16.02.2016
 * Time: 10:02
 */

class Debug
{
    public static function logDB($inData)
    {
        $s1 = '';
        if( !is_array($inData) ) $inData = array($inData);
        foreach ($inData as $key => $val)
        {
              $s1 .= var_export($val, 1)."\n";
        } // end foreach

        $res = Yii::app()->db->createCommand()
            ->insert('debuglog', array(
                'mess' => $s1,
                'crdate' => date('Y-m-d H:i:s'),
            ));
    }
}
