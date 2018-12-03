/**
 * Created by Stanislav on 03.12.2018.
 */
$(document).ready(function () {
    var control = $('.staff__control').val();
    if(control=='add'){
        $('#control__add-personal').click();
    }else if(control=='new'){
        $('#control__new-personal').click();
    }


});