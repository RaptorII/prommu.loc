<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 16.01.2020
 * Time: 11:24
 */

class yiiCrawlerDetect extends CApplicationComponent
{
  public function init()
  {
    if (!Yii::getPathOfAlias('yiiCrawlerDetect')) {
      Yii::setPathOfAlias('yiiCrawlerDetect', dirname(__FILE__));
    }

    Yii::import('yiiCrawlerDetect.*');
    Yii::import('yiiCrawlerDetect.libs.*');
  }

  public function isBot()
  {
    $CrawlerDetect = new CrawlerDetect;
    return $CrawlerDetect->isCrawler();
  }
}