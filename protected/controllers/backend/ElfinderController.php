<?
class ElfinderController extends CController
{
    public function actions()
    {
        $model = new Settings;
        
        return array(
            'connector' => array(
                'class' => 'ext.elFinder.ElFinderConnectorAction',
                'settings' => array(
                    'root' => $model->getDataByCode('files_root'),
                    'URL' => $model->getDataByCode('files_url'),
                    'rootAlias' => 'Files',
                    'mimeDetect' => 'none'
                )
            ),
        );
    }
}
?>