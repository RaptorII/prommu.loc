<!DOCTYPE html>
<html lang="ru">
  <head>
    <? $this->renderPartial('../layouts/header_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
    <?/*?><meta http-equiv="refresh" content="1;<?=Subdomain::site() . MainConfig::$PAGE_PROFILE?>"><?*/?>
    <script type="text/javascript">
      window.onload = function() {
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
            yaCounter23945542.reachGoal(goal);
            location.href=link;
          }
          else
          {
            setTimeout(function(){ setGoal() },100);
          }
        }
      }
    </script>
  </head>
  <body>
    <? $this->renderPartial('../layouts/body_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
  </body>
</html>