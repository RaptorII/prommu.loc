<?
Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/css/template.css');
$model = new VacancyCostAdmin();
$params = Yii::app()->getRequest()->getParam('VacancyCostAdmin');
?>
<div class="row">
  <div class="col-xs-12">
    <h3><?=$this->pageTitle?></h3>
    <div class="bs-callout bs-callout-info">Здесь выводятся все вакансии. Этот список можно отфильтровать по платежам услуги "РАЗМЕЩЕНИЕ ВАКАНСИЙ"</div>
    <div class="clearfix"></div>
    <? $this->widget(
      'zii.widgets.grid.CGridView',
      array(
        'dataProvider' => $model->search(),
        'itemsCssClass' => 'table table-bordered table-hover custom-table',
        'filter' => $model,
        'enablePagination' => true,
        'columns' => [
          [
            'header' => 'ID',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'html',
            'htmlOptions' => ['style'=>'width:2%']
          ],
          [
            'header' => 'Название',
            'name' => 'title',
            'value' => 'AdminView::getLink("/admin/VacancyEdit/{$data->id}",$data->title)' ,
            'type' => 'html',
            'htmlOptions' => ['style'=>'width:10%']
          ],
          [
            'header' => 'Статус',
            'filter' => CHtml::dropDownList(
              'VacancyCostAdmin[status]',
              isset($params['status']) ? $params['status'] : '',
              [''=>'Все','0'=>'Неактивные', '1'=>'Активные']
            ),
            'name' => 'status',
            'value' => 'getStatusLabel($data->status)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:3%']
          ],
          [
            'header' => 'Модерация',
            'filter' => CHtml::dropDownList(
              'VacancyCostAdmin[ismoder]',
              isset($params['ismoder']) ? $params['ismoder'] : '',
              [''=>'Все','0'=>'В работе','100'=>'Просмотреные']
            ),
            'name' => 'ismoder',
            'value' => 'getIsmoderLabel($data->ismoder)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:3%']
          ],
          [
            'header' => 'Компания',
            'name' => 'company_search',
            'type' => 'html',
            'value' => 'AdminView::getLink("/admin/EmplEdit/{$data->id_user}",$data->company_search)',
            'htmlOptions' => ['style'=>'width:10%']
          ],
          [
            'header' => 'ID_USER',
            'name' => 'id_user',
            'value' => '$data->id_user',
            'type' => 'html',
            'htmlOptions' => ['style'=>'width:2%']
          ],
          [
            'header' => 'Оплаты',
            'filter' => CHtml::dropDownList(
              'VacancyCostAdmin[cost]',
              isset($params['cost']) ? $params['cost'] : '',
              [''=>'Все','0'=>'Бесплатные','1'=>'Ожидают оплаты','2'=>'Оплачены']
            ),
            'name' => 'ismoder',
            'value' => 'getCostLabel($data->cost)',
            'type' => 'raw',
            'htmlOptions' => ['style'=>'width:3%']
          ]
        ]
      )
    ); ?>
  </div>
</div>
<?
function getStatusLabel($value)
{
  return '<span class="label label-' . ($value==Vacancy::$STATUS_ACTIVE ? 'success' : 'warning')
    . '">' . ($value==Vacancy::$STATUS_ACTIVE ? 'активна' : 'неактивна') . '</span>';
}
function getIsmoderLabel($value)
{
  return '<span class="label label-' . ($value==Vacancy::$ISMODER_APPROVED ? 'success' : 'warning')
    . '">' . ($value==Vacancy::$STATUS_ACTIVE ? 'просмотрена' : 'в работе') . '</span>';
}
function getCostLabel($value)
{
  return '<span class="label label-' . ($value=='' ? 'success' : ($value==1?'info':'warning'))
    . '">' . ($value=='' ? 'бесплатная' : ($value==1?'оплачена':'ожидает оплаты')) . '</span>';
}
?>