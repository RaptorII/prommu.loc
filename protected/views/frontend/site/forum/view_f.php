<h3 style="text-align:center;">Отзывы</h3>
<form method="POST" id="form">
<div class="fr">
  <div class="fr-title">
    <a href="/site/Forum/&theme=0">Рекламные агентства</a>
    <a href="/site/Forum/&theme=1">Работодатели</a>
  </div>

<ol>
<?php
  $theme = 0;
  $topic = 0;
  if(isset($_GET['theme'])) {
    $theme = intval($_GET['theme']); 
  }
  
  if(isset($_GET['topic'])) {
    $topic = intval($_GET['topic']);  
  }
  
  if($topic>0)
  {
    // ******** LEVEL 2 **************
    if($theme==0) {
      $res = Yii::app()->db->createCommand()
      ->select('t.id_user as id, t.name, t.avg_rating')
      ->from('ra t')
      ->where('t.id_user=:id', array(':id'=>$topic))
      ->queryRow(); 
    
      echo '<h3>Рекламные агентства :: '.$res['name'].'</h3>';
    
    } else {
      $res = Yii::app()->db->createCommand()
      ->select('id_user as id, lastname,firstname,surname, avg_rating')
      ->from('employer')
      ->where('id_user=:id', array(':id'=>$topic))
      ->queryRow();
    
      echo '<h3>Работодатели :: '.$res['lastname'].' '.$res['firstname'].' '.$res['surname'].'</h3>';
    }
    
    
    //-------------- CHECK EDIT ---------------
        $cookie = Yii::app()->request->cookies['uid']->value;
        $res = Yii::app()->db->createCommand()
        ->select("u.id_user, a.status")
        ->from('user_work u')
        ->rightJoin('user a','a.id_user=u.id_user')
        //->rightJoin('ra r','r.id_user=u.id_user')
        //->rightJoin('employer e','e.id_user=u.id_user')
        ->where('uid = :uid', array(':uid'=>$cookie))
        ->queryRow();
        
        $id_user=$res['id_user'];
        $status =$res['status'];
        $checkLine = 0;
        
        if($status==2)
        {
          // дополнительная проверка на косвен. принадлежность промо к РА
          
          if($theme==0)
          {
          $res = Yii::app()->db->createCommand()
            ->select("count(*) as cnt")
            ->from('projects')
            ->where('id_promo = :id_promo AND id_ra IN (SELECT id_ra FROM ra WHERE id_user=:id)', array(':id_promo'=>$id_user, ':id'=>$topic))
            ->queryRow();
           $checkLine = $res['cnt'];
           } else {
          $res = Yii::app()->db->createCommand()
            ->select("count(*) as cnt")
            ->from('projects')
            ->where('id_promo = :id_promo AND id_empl IN (SELECT id FROM employer WHERE id_user=:id)', array(':id_promo'=>$id_user, ':id'=>$topic))
            ->queryRow();
           $checkLine = $res['cnt'];           
           }
        }
        
        //echo $checkLine; die;
        if($id_user == $topic || ($status==2 && $checkLine>0))
        {
          // ------- CHECK SAVE --------
          $parent = 0;
          if(isset($_POST['resp_id']))
          {
            $parent = intval($_POST['resp_id']); 
          }
          
          if(isset($_POST['mess']))
          {
            $mess = $_POST['mess'];
            Yii::app()->db->createCommand()
            ->insert('messages', array(
            'id_topic'=>$topic,
            'id_user'=>$id_user,
            'status'=>$status,
            'parent'=>$parent,
            'mess'=>Share::setProtected($mess)
            )); 
          }
          
        }
    $res = Yii::app()->db->createCommand()
      ->select('m.id, m.id_user, m.status, m.parent, m.mess, m.dt_create, '.
      'p.mess as resp, r.logo, r.name as name_ra, u.photo as photo_promo, u.firstname, u.lastname, u.surname, '.
      'concat(e.firstname, \' \', e.lastname, \' \', e.surname) as name_empl, e.photo as photo_empl, '.            
      'r2.name as resp_name, p.dt_create as resp_dt')
      ->from('messages m')
      ->leftJoin('messages p','p.parent=m.id')
      ->leftJoin('ra r','r.id_user=m.id_user')
      ->leftJoin('ra r2','r2.id_user=p.id_user')
      ->leftJoin('resume u','u.id_user=m.id_user')
      ->leftJoin('employer e', 'e.id_user=m.id_user')
      ->where('m.id_topic=:id AND m.parent=0', array(':id'=>$topic))
      ->queryAll();
    
    foreach($res as $row)
    {
        //$photo = $res['photo'];
        if($row['status']==3) {
          $photo = $row['photo_empl'];
          $name = $row['name_empl']; 
        }
        
        if($row['status']==4) {
          $photo = $row['logo'];
          $name = $row['name_ra']; 
        }
        if($row['status']==2) {
          $photo = $row['photo_promo'];
          $name = $row['lastname'].' '.$row['firstname'].' '.$row['surname']; 
        }
      
      echo '<li><table><tr><td class="mt"><img src="/content/'.$photo.'" width="64" class="floatleft"/><b>'.
      $name.'</b><br/>'.$row['dt_create'].'</td><td class="mf">'.$row['mess'];
      if(($status==4 || $status==3) && $id_user == $topic && $row['resp']==null)
      {
        // Добавить как ответ
        //echo 'ОТВЕТИТЬ';
          echo '<hr/>ответить (512 знаков)<br/>';
          echo '<textarea id="mess'.$row['id'].'" name="mess'.$row['id'].'" style="height:60px"></textarea></br>';
          echo '<a href="#" onclick="Resp('.$row['id'].')" class="btn small">ответить</a>';

      } else {
          echo '<table><tr><td class="mt"><b>'.
          $row['resp_name'].'</b><br/>'.$row['resp_dt'].'</td><td class="mf">'.$row['resp'].'</td></tr></table>';      
      }
      echo '</td></tr></table></li>';
    }
    if(($status==4 && $id_user == $topic) || ($status==2 && $checkLine>0))
    {
          // Разрешить пост
          echo '<hr/>сообщение (512 знаков)<br/>';
          echo '<textarea id="mess" name="mess" style="height:60px"></textarea></br>';
          echo '<a href="#" onclick="Save()" class="btn small">добавить</a>';
    }
    //-------------- ---------- ---------------
  }
  else
  {
  // ******** LEVEL 1 **************
  if($theme==0) {
    $res = Yii::app()->db->createCommand()
    ->select('t.id_user as id, t.name, t.avg_rating')
    ->from('ra t')
    ->order('name')
    ->queryAll(); 
  } else {
    $res = Yii::app()->db->createCommand()
    //->select('id_user as id, CONCAT(lastname,' ',firstname,' ',surname) as name, avg_rating')
    ->select('id_user as id, lastname,firstname,surname, avg_rating')
    ->from('employer')
    ->order('lastname')
    ->queryAll();
  } 
    foreach($res as $row)
    {
      if($theme!=0)
        $name = $row['lastname'].' '.$row['firstname'].' '.$row['surname'];
      else
        $name = $row['name']; 
      echo '<li><div class="productRate"><div style="width: '.
        $row['avg_rating'].'%"></div></div><a href="/site/Forum/&theme='.
        $theme.'&topic='.$row['id'].'">'.$name.'</a></li>';
    }
   }
?>
</ol>
</div>
<input type="hidden" id="resp_id" name="resp_id"/>
<input type="submit" id="btn_submit" value="submit" style="display:none"/>
</form>

<script>
function Save()
{
  var t = $("#mess").val();
  if(t.length==0){
    alert('Введите текст');
  }
  else {
    $("#btn_submit").click();
  } 
}

function Resp(id)
{
  $("#resp_id").val(id);
  var t = $("#mess"+id).val();
  if(t.length==0){
    alert('Введите текст');
  }
  else {
    $("#mess").val(t);
    $("#btn_submit").click();
  } 
  
}
</script>