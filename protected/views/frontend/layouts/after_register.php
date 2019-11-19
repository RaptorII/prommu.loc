<!DOCTYPE html>
<html lang="ru">
  <head>
    <? $this->renderPartial('../layouts/header_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
    <meta http-equiv="refresh" content="1;<?=Subdomain::site() . MainConfig::$PAGE_PROFILE?>">
  </head>
  <body>
    <? $this->renderPartial('../layouts/body_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
  </body>
</html>