<?
  UserRegisterPageCounter::setByIdUser(
    Share::$UserProfile->id,
    Yii::app()->request->url===MainConfig::$PAGE_AFTER_REGISTER
      ? UserRegister::$PAGE_USER_LEAD
      : UserRegister::$PAGE_ACTIVE_PROFILE
    );
  $sType = Share::isApplicant() ? 'applicant' : 'employer';
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <? $this->renderPartial('../layouts/header_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
    <script type="text/javascript">
      document.addEventListener("DOMContentLoaded", function(){
        var yaParams = [{id_user:<?=Share::$UserProfile->id?>,type:"<?=$sType?>"}];
        var link = "<?=MainConfig::$PAGE_PROFILE?>";
        var goal = location.pathname==="<?=MainConfig::$PAGE_AFTER_REGISTER?>" ? 5 : 6;
        var cnt = 0;
        setGoal();
        function setGoal()
        {
          cnt++;
          if(cnt>30)
          {
            location.href=link;
            return;
          }
          if(typeof yaCounter23945542 === 'object')
          {
            yaCounter23945542.reachGoal(goal,{params:yaParams});
            location.href=link;
          }
          else
          {
            setTimeout(function(){ setGoal() },100);
          }
        }
      });
    </script>
  </head>
  <body>
    <? $this->renderPartial('../layouts/body_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
  </body>
</html>