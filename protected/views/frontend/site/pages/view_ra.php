<?php
$id=0; 
if(isset($_GET['agency'])) {
    $id = intval($_GET['agency']);
} else {
    $cookie = Yii::app()->request->cookies['uid']->value;
        $res = Yii::app()->db->createCommand()
        ->select("u.id_user, r.id_ra")
        ->rightJoin('ra r','r.id_user = u.id_user')                
        ->from('user_work u')
        ->where('uid = :uid', array(':uid'=>$cookie))
        ->queryRow();
    $id=$res['id_ra'];
}
    
$model = RaContent::model()->findByPk($id);
if(isset($model->content))
  echo $model->content;
else
  echo '<div class="mess_line">Информация временно отсутствует</div>';
?>