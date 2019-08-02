'use strict'
$(function(){
  $('#cities_form input').on('input',function(){
    let clearValue = /[^а-яА-ЯїЇєЄіІёЁ ]/g.exec(this.value),
        value = '',
        arCities = [];

    clearValue = this.value.replace(clearValue, '');
    this.value = clearValue;
    value = clearValue.toLowerCase();

    if(value.length)
    {
      $('#cities_block .othercities_item').each(function(){
         let link = $(this).find('a'),
             city = $(link).text().toLowerCase();

         if(city.indexOf(value)>=0)
         {
           arCities.push(this);
         }
      });
      //
      if(arCities.length)
      {
        $('#cities_block .othercities_item').each(function(){
          $.inArray(this,arCities)>=0 ? $(this).show() : $(this).hide();
        });
        $('.othercities_item-empty').hide();
      }
      else
      {
        $('#cities_block .othercities_item').hide();
        $('.othercities_item-empty').show();
      }
    }
    else
    {
      $('#cities_block .othercities_item').show();
      $('.othercities_item-empty').hide();
    }
  });

});