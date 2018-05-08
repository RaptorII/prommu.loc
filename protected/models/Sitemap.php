<?php
class Sitemap extends CActiveRecord
{
    private $dom;
    private $indexDom;

    private $dburls;


	public function tableName()
	{
		return 'sitemap';
	}



    /**
     * Сгенерировать карту XML и HTML
     */
    public function actionGenerate()
    {
        // Фиксируем дату последней генерации
        (new Status)->setStatus("sitemaplastgen", '');


        $this->dburls = [];

        //Чистим старые
        if(file_exists(Yii::app()->basePath . '/../sitemap1.xml')) unlink(Yii::app()->basePath . '/../sitemap1.xml');
        for($i = 0; $i < 10; $i++)
        {
            if(file_exists(Yii::app()->basePath . '/../sitemap' . $i . '.xml')) unlink(Yii::app()->basePath . '/../sitemap' . $i . '.xml');
        }

        $this->indexDom = new DOMDocument('1.0', 'utf-8');

        // указываем кодировку и версию xml файла
        // $this->indexDom->formatOutput = true;
        $indexurlset = $this->indexDom->createElement('sitemapindex');
        $indexurlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');

        $urls = array();

        //Главная
        $url = array();
        $url['loc'] = Yii::app()->createAbsoluteUrl('/');
        $url['lastmod'] = date('c');
        $url['priority'] = 1.0;
        $url['changefreq'] = 'weekly';
        $urls[] = $url;

        $sql = "SELECT pc.page_id, pc.name, pc.mdate
            FROM pages_content pc
            INNER JOIN pages p ON p.id = pc.page_id AND p.group_id = 1 
            WHERE pc.lang = '" . Yii::app()->session['lang'] . "' ";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        foreach ($res as $key => $val) { $pagesStatic[$val['page_id']] = $val; } // end foreach

        // частота
        $freq = (object)array('a' => 'always', 'd' => 'daily', 'w' => 'weekly', 'm' => 'monthly');
        $this->dburls[] = array('name' => "Главная страница", 'link' => Yii::app()->createAbsoluteUrl(), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
         $pages['vacs'] = array('loc' => Yii::app()->createAbsoluteUrl('/vacancy'), 'lastmod' => date('c'), 'priority' => 0.8, 'changefreq' => $freq->w);
          $this->dburls[] = array('name' => 'Вакансии', 'link' => Yii::app()->createAbsoluteUrl('/vacancy'), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

         $pages['searchpromo'] = array('loc' => Yii::app()->createAbsoluteUrl('/ankety'), 'lastmod' => date('c'), 'priority' => 0.8, 'changefreq' => $freq->w);
          $this->dburls[] = array('name' => 'Соискатели', 'link' => Yii::app()->createAbsoluteUrl('/ankety'), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

         $pages['searchempl'] = array('loc' => Yii::app()->createAbsoluteUrl('/searchempl'), 'lastmod' => date('c'), 'priority' => 0.8, 'changefreq' => $freq->w);
          $this->dburls[] = array('name' => 'Работодатели', 'link' => Yii::app()->createAbsoluteUrl('/searchempl'), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

         foreach ($pages as $key => $val)
        {
            $urls[] = $val;
        }

                
        /// Должности
        $this->dburls[] = array('name' => 'Вакансии', 'link' => Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_SEARCH_VAC), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

        $sql = "SELECT d.id, d.type, d.comment, d.name FROM user_attr_dict d WHERE d.id_par = 110 ORDER BY npp, name";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
            $s1 = '/vacancy/' . $val['comment'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['mdate'])), 'priority' => 0.8, 'changefreq' => $freq->w);
            $this->dburls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        } // end foreach

        /// Должности
        $this->dburls[] = array('name' => 'Анкеты', 'link' => Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_SEARCH_PROMO), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

        $sql = "SELECT d.id, d.type, d.comment, d.name FROM user_attr_dict d WHERE d.id_par = 110 ORDER BY npp, name";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
            $s1 = '/ankety/' . $val['comment'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->w);
            $this->dburls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        }


        // Выбираем даты всех стат. страниц

        // Страницы сайта
        if( $pagesStatic[7] )
        {
            $s1 = DS . MainConfig::$PAGE_ABOUT;
            $pages['about'] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($pagesStatic[7]['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->m);
            $this->dburls[] = array('name' => 'О сервисе', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        }

        // ПРАВИЛА САЙТА
        if( $pagesStatic[13] )
        {
            $s1 = '/regulations';
            $mages['regulations'] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($pagesStatic[13]['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->m);
            $this->dburls[] = array('name' => 'Правила сайта', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        }
         if( $pagesStatic[18] )
        {
            $s1 = '/empl';
            $mages['empl'] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($pagesStatic[18]['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->m);
            $this->dburls[] = array('name' => 'Информация для работодателей', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        }

        if( $pagesStatic[19] )
        {
            $s1 = '/prom';
            $mages['prom'] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($pagesStatic[19]['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->m);
            $this->dburls[] = array('name' => 'Информация для соискателей', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
       }

        if( $pagesStatic[35] )
        {
            $s1 = '/faqv';
            $mages['faqv'] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($pagesStatic[35]['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->m);
            $this->dburls[] = array('name' => 'FAQ: вопросы и ответы', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        }

        if( $pagesStatic[7] )
        {
            $s1 = '/feedback';
            $mages['feedback'] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($pagesStatic[7]['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->m);
            $this->dburls[] = array('name' => 'Обратная связь', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        }
        $this->dburls[] = array('name' => 'Обратная связь', 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

        $mages['articles'] = array('loc' => Yii::app()->createAbsoluteUrl('/articles'), 'lastmod' => date('c'), 'priority' => 0.64, 'changefreq' => $freq->w);
        

         $mages['services'] = array('loc' => Yii::app()->createAbsoluteUrl('/services'), 'lastmod' => date('c', $maxDate), 'priority' => 0.64, 'changefreq' => $freq->w);

         $mages['work-for-students'] = array('loc' => Yii::app()->createAbsoluteUrl('/work-for-students'), 'lastmod' => date('c', $maxDate), 'priority' => 0.64, 'changefreq' => $freq->w);

        foreach ($mages as $key => $val)
        {
            $urls[] = $val;
        }
        ///услуги 
        $bdUrls[] = array('name' => 'Услуги', 'link' => Yii::app()->createAbsoluteUrl(DS . MainConfig::$PAGE_SERVICES), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
         $this->dburls[] = array('name' => "Наши услуги", 'link' => Yii::app()->createAbsoluteUrl('/services'), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
         $this->dburls[] = array('name' => "Работа для студентов", 'link' => Yii::app()->createAbsoluteUrl('/work-for-students'), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
        // Выбираем даты всех услуг
        $sql = "SELECT pc.page_id, pc.name, p.link, pc.mdate
            FROM pages_content pc
            INNER JOIN pages p ON p.id = pc.page_id AND p.group_id = 3
            GROUP BY p.link";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        $maxDate = strtotime('1970-0-0');
        foreach ($res as $key => $val)
        {
            $s1 = '/services/' . $val['link'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['mdate'])), 'priority' => 0.64, 'changefreq' => $freq->w);
            if( $maxDate < strtotime($val['mdate']) ) $maxDate = strtotime($val['mdate']);

            $bdUrls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
            $this->dburls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        } // end foreach

        ///статьи
        // $bdUrls[] = array('name' => 'Полезные статьи', 'link' => Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_ARTICLES), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

        // Выбираем даты всех новостей
        $sql = "SELECT pc.page_id, pc.name, p.link, pc.pubdate
            FROM pages_content pc
            INNER JOIN pages p ON p.id = pc.page_id AND p.group_id = 99
            GROUP BY p.link
            ORDER BY pc.crdate DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        $maxDate = strtotime('1970-0-0');

        $this->dburls[] = array('name' => "Полезные статьи портала Prommu", 'link' => Yii::app()->createAbsoluteUrl('/articles'), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        foreach ($res as $key => $val)
        {
            $s1 = '/articles/' . $val['link'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['pubdate'])), 'priority' => 0.64, 'changefreq' => $freq->w);
            if( $maxDate < strtotime($val['pubdate']) ) $maxDate = strtotime($val['pubdate']);

            $bdUrls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'pubdate' => date("Y-m-d H:i:s"));
            $this->dburls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        } // end foreach

        ///Новости
         $sql = "SELECT pc.page_id, pc.name, p.link, pc.pubdate
            FROM pages_content pc
            INNER JOIN pages p ON p.id = pc.page_id AND p.group_id =2
            GROUP BY p.link
            ORDER BY pc.crdate DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        $maxDate = strtotime('1970-0-0');

        $this->dburls[] = array('name' => "Новости портала Prommu", 'link' => Yii::app()->createAbsoluteUrl('/about/news'), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));
        foreach ($res as $key => $val)
        {
            $s1 = '/about/news/' . $val['link'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['pubdate'])), 'priority' => 0.64, 'changefreq' => $freq->w);
            if( $maxDate < strtotime($val['pubdate']) ) $maxDate = strtotime($val['pubdate']);

            $bdUrls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'pubdate' => date("Y-m-d H:i:s"));
            $this->dburls[] = array('name' => $val['name'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        } // end foreach


        
        // ВАКАНСИИ
        // Выбираем все вакансии
        // $this->dburls[] = array('name' => 'Вакансии', 'link' => Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_SEARCH_VAC), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

        $sql = "SELECT e.id, e.title, e.mdate
            FROM empl_vacations e
            WHERE e.remdate > NOW()
              AND e.status = 1
              AND e.ismoder = 100
            ORDER BY e.crdate DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        foreach ($res as $key => $val)
        {
            $s1 = '/vacancy/' . $val['id'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['mdate'])), 'priority' => 0.5, 'changefreq' => $freq->w);
            // $this->dburls[] = array('name' => $val['title'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        } // end foreach




        // АНКЕТЫ
        // $this->dburls[] = array('name' => 'Анкеты', 'link' => Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_SEARCH_PROMO), 'level' => 0, 'crdate' => date("Y-m-d H:i:s"));

        $res = (new Promo())->getApplicantsQueries(array('page' => 'sitemap'));
        foreach ($res as $key => $val)
        {
            $s1 = '/ankety/' . $val['id'];
            $urls[] = array('loc' => Yii::app()->createAbsoluteUrl($s1), 'lastmod' => date('c', strtotime($val['mdate'])), 'priority' => 0.5, 'changefreq' => $freq->w);
            // $this->dburls[] = array('name' => $val['fio'], 'link' => Yii::app()->createAbsoluteUrl($s1), 'level' => 1, 'crdate' => date("Y-m-d H:i:s"));
            $this->writeDBUrl();
        } // end foreach
    


    

        // записуем остаток
        $this->writeDBUrl(['min' => 0]);



        //Статика // end foreach



        $sitemaps = array();
        for($i = 0; $i < sizeof($urls); $i += 35000)
        {
            $urlsSliced = array_slice($urls, $i, 35000);
            $this->dom = new DOMDocument('1.0', 'utf-8');
            // $this->doc->loadHTML('<?xml encoding="UTF-8">');
            // $this->dom->formatOutput = true;
            $urlset = $this->dom->createElement('urlset');
            // создаем корневой элемент
            $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
            
            foreach($urlsSliced as $urlData)
            {
                $url = $this->dom->createElement('url');
                $urlset->appendChild($url);
                $url->appendChild($this->addElem('loc', $urlData['loc']));
                // $url->appendChild($this->addElem('lastmod', $urlData['lastmod']));
                $url->appendChild($this->addElem('changefreq', $urlData['changefreq']));
                $url->appendChild($this->addElem('priority', $urlData['priority']));
            }
            $this->dom->appendChild($urlset);
            $filename = Yii::app()->basePath . '/../sitemap.xml';
            if(file_exists($filename)) unlink($filename);
            file_put_contents($filename, $this->dom->saveXML());
            $sitemaps[] = array('lastmod'=>date('c'), 'loc'=>Yii::app()->baseUrl . '/sitemap.xml');
        }

        foreach($sitemaps as $map)
        {
            $domElem =   $this->indexDom->createElement('sitemap');
            $domElem->appendChild($this->addElem('loc', Yii::app()->createAbsoluteUrl($map['loc']), $this->indexDom));
            // $domElem->appendChild($this->addElem('lastmod', $map['lastmod'], $this->indexDom));
            $indexurlset->appendChild($domElem);
        }

        $this->indexDom->appendChild($indexurlset);

        if(file_exists(Yii::app()->basePath . '/../sitemap1.xml')) unlink(Yii::app()->basePath . '/../sitemap1.xml');
        file_put_contents(Yii::app()->basePath . '/../sitemap1.xml', $this->indexDom->saveXML());
    }



    /**
     * Читаем данные для HTML карты
     */
    public function getHtmlMapData()
    {
        $status = (new Status())->getStatus('sitemaplastgen');
        $Sitemap = new Sitemap();

        $criteria = new CDbCriteria();
        $criteria->condition = "crdate >= STR_TO_DATE('" . date("Y-m-d H:i:s", strtotime($status->mdate)) . "','%Y-%m-%d %H:%i:%s')";
//        $criteria->params = array (':id'=>$id);

        // $count = $Sitemap->count($criteria);
        // $pages = new CPagination($count);
        // $pages->setPageSize(MainConfig::$DEF_PAGE_LIMIT);
        // $pages->applyLimit($criteria);

        $data = $Sitemap->findAll($criteria);

        return ['pages' => $pages, 'data' => $data, 'count' => $count];
    }


    private function addElem($name, $text, $dom = null)
    {
        if($dom == null)
            $dom = $this->dom;

        $name = $dom->createElement($name);
        $text = $dom->createTextNode($text);
        $name->appendChild($text);
        return $name;
    }

    private function datetimeToCTime($datetime)
    {
        $a = strptime($datetime, '%Y-%m-%d %H:%M:%S');
        $timestamp = mktime($a['tm_hour'], $a['tm_min'], $a['tm_sec'], $a['tm_mday'], $a['tm_mon'], 1899 + $a['tm_year']);
        $ctime = date('c', $timestamp);
        return $ctime;
    }



    /**
     * Записываем URL в базу, если их колл-во достигло оптимального
     * @param array $props
     */
    private function writeDBUrl($props = [])
    {
        $min = isset($props['min']) ? 0 : 1500;
        if( count($this->dburls) > $min )
        {
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('sitemap', $this->dburls);
            $command->execute();

            $this->dburls = [];
        } // endif
    }

}