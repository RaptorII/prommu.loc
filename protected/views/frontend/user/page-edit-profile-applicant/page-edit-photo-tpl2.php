<?php 
    Yii::app()->getClientScript()->registerCssFile('/theme/css/phone-codes/style.css'); 
    Yii::app()->getClientScript()->registerScriptFile("/theme/js/phone-codes/script.js", CClientScript::POS_END);
?>
<?php

    

/*
*       Определяем неиндексированных пользователей
*/
/*
    $arRes = Yii::app()->db->createCommand()
    ->select('u.id_user')
    ->from('user u')
    ->where('u.status=2 AND u.ismoder=1 AND u.isblocked=0')
    ->queryAll();

    $arIdies = array();
    $i=0;
    $k=0;
    foreach ($arRes as $p){
        $i++;
        if($i%1000==0){
            $k++;
        }
        $arIdies[$k][] = $p['id_user'];
    }



    $arResult = '';

    foreach ($arIdies[4] as $id){
        $profileFillMax = 24;
        $profileFill = 0;

        $profileFill = getUserInfo($id);

        $profileEffect = floor($profileFill / $profileFillMax * 100);
        $profileEffect = $profileEffect>100 ? 100 : $profileEffect;
        if($profileEffect<50 && $profileEffect>0)
        //if($profileEffect>=50)
            $arResult .= 'https://prommu.com/ankety/'.$id.'<br>';
    }

//echo sizeof($arIdies[4]) . ' ' . sizeof($arResult);

echo "<pre>";
print_r($arResult); 
echo "</pre>";

    function getUserInfo($id)
    {
        $profileFill = 0;
        // считываем характеристики пользователя
        $sql = "SELECT DATE_FORMAT(r.birthday,'%d.%m.%Y') as bday, r.id
              , r.id_user, r.isman , r.ismed , r.smart,  r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo
              , a.val , a.id_attr
              , d.name , d.type , d.id_par idpar , d.key
              , u.email, card, cardPrommu
            FROM resume r
            LEFT JOIN user u ON u.id_user = r.id_user
            LEFT JOIN user_attribs a ON r.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE r.id_user = {$id}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val){
            $attr[$val['id_attr']] = $val;
        }

        foreach ($attr as $k => $attrib){
            if( 
                ($attrib['id_attr'] <> 0 // без общего 
                && $attrib['key'] <> 'icq' // без ICQ 
                && $attrib['idpar'] <> 40 // без языков
                && strpos($attrib['key'],'dmob')===false // без доп телефонов
                && !empty($attrib['val'])) // и чтобы значение было заполнено
                ||
                in_array($attrib['idpar'], [11,12,13,14,15,16,69]) // для параметров с выбором
            )
                $profileFill++;
        }

        // read cities
        $sql = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro, uc.street, uc.addinfo
                FROM user_city uc
                LEFT JOIN city ci ON uc.id_city = ci.id_city
                LEFT JOIN country co ON co.id_co = ci.id_co
                WHERE uc.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val):
            $cityPrint[$val['id']] = $val['name'];
            $city[$val['id']] = array('id' => $val['id'], 'name' => $val['name'], 'ismetro' => $val['ismetro'], 'street' => $val['street'], 'addinfo' => $val['addinfo'], );
        endforeach;

        if( count($city) ) $profileFill++;

        // должности, отработанные и желаемые
        $sql = "SELECT r.id
              , um.isshow, um.pay, um.pay_type pt, um.pay_type, um.id_attr, um.mech
              , d1.name pname
              , d.name val, d.id idpost
            FROM resume r
            INNER JOIN user_mech um ON um.id_us = r.id_user
            LEFT JOIN user_attr_dict d1 ON d1.id = um.id_attr
            INNER JOIN user_attr_dict d ON d.id = um.id_mech 
            WHERE r.id_user = {$id}
            ORDER BY um.isshow, val";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        $exp = array();
        $flagPF = 0;
        foreach ($res as $key => $val)
        {
            if( $val['pay_type'] == 1 ) $res[$key]['pay_type'] ='руб/неделю';
            elseif( $val['pay_type'] == 2 ) $res[$key]['pay_type'] ='руб/месяц';
            else $res[$key]['pay_type'] ='руб/час';

            if( $val['isshow'] ) $exp[] = $val['val'];

            if( !$val['isshow'] ) $flagPF || $flagPF = 1;
        } // end foreach
        $data['userDolj'] = array($res, join(', ', $exp));
        if( $flagPF ) $profileFill++;
        if( count($exp) ) $profileFill++;

        return $profileFill;
    }*/
/*
*
*       Добавлены города
*
*/

/*$arRes = Yii::app()->db->createCommand()
    ->select('m.id, m.name')
    ->from('metro m')
    ->where('m.id_city=1307')
    ->queryAll();

  echo "<pre>";
  print_r($arRes); 
  echo "</pre>";
  echo sizeof($arRes);*/

  //Москва 5
?>
