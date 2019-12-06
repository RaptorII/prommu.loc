<?
$bUrl = Yii::app()->request->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . '/css/template.css');
?>
<style type="text/css">
  .custom-table.table-hover>tbody>tr.default:hover,
  .custom-table tr:hover .default,
  .custom-table tr .default{
    font-size: 14px;
    color: #333;
    background-color: inherit;
  }
  .custom-table.table-hover>tbody>tr.pagination_cell,
  .custom-table.table-hover>tbody>tr.pagination_cell:hover{ background-color:#ecf0f5; }
  .table_form{
    position: relative;
    overflow: overlay;
  }
  .table_form.load:before{
    content: '';
    background: rgba(255,255,255,.7) url(/theme/pic/vacancy/loading.gif) center no-repeat;
    display: block;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 2;
  }
  .table_form .custom-table tbody tr:hover td:not(.empty){
    color: initial;
    cursor: initial;
  }
</style>
<h3><?=$this->pageTitle?></h3>
<div class="bs-callout bs-callout-info">Под гостями имеются ввиду пользователи, которые посетили первую форму регистрации, и не выполнив каких-либо действий покинули регистрацию(просто покинули регистрацию)</div>
<form class="table_form">
  <table class="table table-bordered table-hover custom-table">
    <thead>
    <?
    // title
    ?>
    <tr>
      <th rowspan="2">ID пользователя</th>
      <th rowspan="2">Тип пользователя</th>
      <th colspan="7">Страницы</th>
      <th rowspan="2">Дата и время захода на последнюю страницу</th>
      <th rowspan="2">Дата и время начала регистрации</th>
      <th rowspan="2">Соцсеть</th>
      <th rowspan="2">Лид(id_user)</th>
    </tr>
    <tr>
      <th>register/type</th>
      <th>register/login</th>
      <th>register/code</th>
      <th>register/password</th>
      <th>register/avatar</th>
      <th>lead</th>
      <th>after_activate</th>
    </tr>
    <?
    // filter
    ?>
    <tr class="filters">
      <td><input name="Registers[id]" type="text"></td>
      <td>
        <select name="Registers[type]">
          <option value="">все</option>
          <option value="1">гости</option>
          <option value="2">соискатели</option>
          <option value="3">работодатели</option>
        </select>
      </td>
      <td colspan="7">
        <select name="Registers[page_type]">
          <option value="">все</option>
          <option value="1">register/type</option>
          <option value="2">register/login</option>
          <option value="3">register/code</option>
          <option value="4">register/password</option>
          <option value="5">register/avatar</option>
          <option value="6">lead</option>
          <option value="7">after_activate</option>
        </select>
      </td>
      <td>
        <div class="filter_date_range">
          <?php
          $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'bdate',
            'options'=>['changeMonth'=>true],
            'htmlOptions'=>[
              'class'=>'grid_date',
              'autocomplete'=>'off'
            ]
          ));
          ?>
          <div class="separator">-</div>
          <?php
          $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'edate',
            'options'=>['changeMonth'=>true],
            'htmlOptions'=>[
              'class'=>'grid_date',
              'autocomplete'=>'off'
            ]
          ));
          ?>
        </div>
      </td>
      <td>
        <div class="filter_date_range">
          <?php
          $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'bdate_create',
            'options'=>['changeMonth'=>true],
            'htmlOptions'=>[
              'class'=>'grid_date',
              'autocomplete'=>'off'
            ]
          ));
          ?>
          <div class="separator">-</div>
          <?php
          $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'edate_create',
            'options'=>['changeMonth'=>true],
            'htmlOptions'=>[
              'class'=>'grid_date',
              'autocomplete'=>'off'
            ]
          ));
          ?>
        </div>
      </td>
      <td>
        <select name="Registers[social]">
          <option value="">все</option>
          <option value="1">только соцсеть</option>
        </select>
      </td>
      <td>
        <select name="Registers[lead]">
          <option value="">все</option>
          <option value="1">Зарегистрированные</option>
        </select>
      </td>
    </tr>
    </thead>
    <tbody><?=$this->renderPartial('seo/list-registers-ajax'); ?></tbody>
  </table>
</form>
<script type="text/javascript">
  'user strict'
  $(function ($) {
    var data = {};

    $('.filters select,.filters input').on('change',function(){
      var arInput = $('.table_form').serializeArray();

      $.each(arInput,function(){ data[this.name] = this.value });
      data['page'] = '';
      getAjaxData(data);
    });
    // pagination
    $('.table_form').on('click','.page a',function(e){
      e.preventDefault();
      data['page'] = $(this).text();
      getAjaxData(data);
    });
    // sort
    $('.table_form').on('click','th a',function(e){
      e.preventDefault();
      data.dir = (data.dir==='desc' ? 'asc' : 'desc');
      data.sort = this.dataset.value;
      getAjaxData(data);
    });
    //
    function getAjaxData()
    {
      //console.log(arguments[0]);

      $('.table_form').addClass('load');

      $.ajax({
        data: arguments[0],
        success: function(res){
          $('.table_form table tbody').html(res);
          $('.table_form').removeClass('load');
        }
      });
    }
  });
</script>