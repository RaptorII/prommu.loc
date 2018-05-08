<?php

/**
 * Работа с токенами и UID пользователя
 */
class UserTokens extends CActiveRecord
{
    static public $SCOPE_ACTIVE = 1;


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_work';
	}


    /**
     * Готовые условия для ручных запросов по пользователям
     * @param string $inName
     * @param string $alias
     * @return string
     */
    static public function getScopesCustom($inName, $alias = 'u')
    {
        // Если удаляем условия убивать и $SCOPE_XXXXXXX чтобы сразу выявить использование условия
        $aliasPlh = '{{alias}}';
        switch ( (int)$inName )
        {
           case self::$SCOPE_ACTIVE : $condition = 'isblocked = 0'; break;
           default : $condition = "";
        }

        return $condition ? str_replace($aliasPlh, $alias . '.', $aliasPlh . $condition) : '';
    }



    /**
     * Получения данных по токену пользователя
     * @param $inProps
     * @return array
     * @throws ExceptionApi
     */
    public function getUserTokens($inProps)
    {
        $usData = [];

        if( $inProps['token'] )
        {
            $ut = UserTokens::model()->find('token=:token', [':token' => $inProps['token']]);
            if( !$ut ) throw new ExceptionApi('Token invalid', -102);

            foreach (array_keys($ut->getMetaData()->columns) as $key => $val) $usData[$val] = $ut->$val;
            if( strtotime($usData['date_login']) + 86400 < time() ) throw new ExceptionApi('Token expired', -103);
        } else {
            throw new ExceptionApi('Token invalid', -102);
        } // endif

        return ['tokens' => $usData];
    }
}