<?
$bUrl = Yii::app()->request->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . '/css/template.css');
?>
<style type="text/css">
  .custom-table tbody tr:hover td:not(.empty){
    cursor: default;
    color: initial;
  }
  .table_form{ max-width: 500px; }
  .table_form .btn{ margin-top:20px; }
  .table_form input{ max-width: 110px; }
</style>
<h3><?=$this->pageTitle?></h3>
<div class="row">
  <form class="table_form">
    <div class="col-xs-6 col-sm-4">
      <label class="d-label">
        <span>Дата начала</span>
        <?php
          $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'Ab_testing[bdate]',
            'options'=>['changeMonth'=>true],
            'htmlOptions'=>[
              'id'=>'beg_date',
              'class'=>'form-control',
              'autocomplete'=>'off'
            ]
          ));
        ?>
      </label>
    </div>
    <div class="col-xs-6 col-sm-4">
      <label class="d-label">
        <span>Дата окончания</span>
        <?php
          $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'name'=>'Ab_testing[edate]',
            'options'=>['changeMonth'=>true],
            'htmlOptions'=>[
              'id'=>'end_date',
              'class'=>'form-control',
              'autocomplete'=>'off'
            ]
          ));
        ?>
      </label>
    </div>
    <div class="col-xs-12 col-sm-4">
      <?/*?><button type="submit" class="btn btn-success">Рассчитать</button><?*/?>
    </div>
    <div class="clearfix"></div>
  </form>
  <div class="result_data"><?=$this->renderPartial('seo/ab_testing-ajax',['data'=>$data]); ?></div>
</div>
<script type="text/javascript">
  'user strict'
  $(function() {
    // datepicker
    $('#beg_date').on("change", function () {
      changeDate(1);
      getAjaxData();
    });
    $('#end_date').on("change", function () {
      changeDate(2);
      getAjaxData();
    });
    $(document).on(
      'click',
      '#beg_date,#end_date',
      function () {
        if (!$(this).hasClass('hasDatepicker')) {
          $(this).datepicker();
          $(this).datepicker('show');
        }
      });
    // корректировка даты
    function changeDate(cnt)
    {
      var date1 = $('#beg_date').datepicker('getDate'),
        date2 = $('#end_date').datepicker('getDate');

      if (cnt == 1) {
        $('#beg_date').datepicker('setDate', date1);
        $('#end_date').datepicker("option", "minDate", date1);
      }
      else {
        $('#end_date').datepicker('setDate', date2);
        $('#beg_date').datepicker("option", "maxDate", date2);
      }
    }
    // запрос данных
    function getAjaxData()
    {
      $('section.content').addClass('loading');
      $.ajax({
        data: $('.table_form').serialize(),
        success: function(res)
        {
          $('.result_data').html(res);
          $('section.content').removeClass('loading');
        }
      });
    }
  });
</script>