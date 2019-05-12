'use strict'
$(function(){
  // datepicker
  $('#export_beg_date').on("change", function(){ changeDate(1) });
  $('#export_end_date').on("change", function(){ changeDate(2) });

  $(document).on(
    'click',
    '#export_beg_date,#export_end_date', 
    function(){ 
      if (!$(this).hasClass('hasDatepicker')) { 
        $(this).datepicker(); $(this).datepicker('show'); 
      }
  }); 

  function changeDate(cnt)
  {
    var date1 = $('#export_beg_date').datepicker('getDate'),
        date2 = $('#export_end_date').datepicker('getDate');

    if(cnt==1)
    {
      $('#export_beg_date').datepicker('setDate',date1);
      $('#export_end_date').datepicker("option","minDate",date1);   
    }
    else
    {
      $('#export_end_date').datepicker('setDate',date2);
      $('#export_beg_date').datepicker("option","maxDate",date2);                
    }
  }
  // popup
  $('.export_btn').click(function(){
    $('#export_form').fadeIn();
    $('.bg_veil').fadeIn();
  });
  $('.bg_veil,.export_form-close,.export_start_btn').click(function(){
    $('#export_form').fadeOut();
    $('.bg_veil').fadeOut();
  });
  // update vacancy
  $(document).on('click','.select_update li',function(){
    var self = this;

    if(!MainAdmin.bAjaxTimer)
    {
      MainAdmin.bAjaxTimer = true;
      $.ajax({
        type: 'GET',
        url: '/admin/ajax/vacancy',
        data: {'data':JSON.stringify(self.dataset)},
        dataType: 'json',
        success: function (result)
        {
          confirm(result.message);
          MainAdmin.bAjaxTimer = false;
          $.fn.yiiGridView.update("vacancy_table");
        }
      });
    }
  });
});