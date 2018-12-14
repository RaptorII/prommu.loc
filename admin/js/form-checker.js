/**
 * Created by Stanislav on 14.12.2018.
 */
$(document).ready(function(){
    $(".form-horizontal").submit(function() {
       var text = $('#admin-answer').val();
       if(text.length==0){
          alert('Введите текст ответа');
          return false;
       }else{
           return true;
       }
    });
});
