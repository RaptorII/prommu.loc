<?php
class VacanciesController extends AppController
{
    public function actionIndex()
    {
        $SearchVac = (new SearchVac());
        
        if(isset($_GET['sphf']) && !isset($_GET['seo_builded']))
        {
            // then filter page, create seo friendly url

            $url = $SearchVac->buildPrettyUrl($_GET);
            $this->redirect($url);
            exit();
        }

        // results per page
        $count = $SearchVac->searchVacationsCount();
        $pages=new CPagination($count);
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($SearchVac);

        $data = $SearchVac->getVacations();

        $this->setBreadcrumbs($title = "Поиск вакансий", MainConfig::$PAGE_SEARCH_VAC);

        $this->render($this->ViewModel->pageSearchVac,
            array('viData' => $data
            , 'pages' => $pages
            , 'count' => $count),
            array(
                'pageTitle' => $title,
                'pageKind' => 'vacancy',
                'pageAction' => 'vacancy'
            ));
    }
}
