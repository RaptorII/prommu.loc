'use strict'
var MainAdmin = {
	bAjaxTimer : false,
    showMessage : function(message, name)
    {
        if(!$('.content-wrapper .content>.alert').is('*'))
        {
            let str = '<div class="alert '+name+'">'+message+'</div>';
            $('.content-wrapper .content').prepend(str);
        }
        else
        {
            $('.content-wrapper .content>.alert').text(message)
                .removeClass('success danger')
                .addClass(name);
        }
    },
    loadingButton : function(e, flag)
    {
        if(flag==true)
        {
            let name = $(e).text();
            e.dataset.name = name;
            $(e).html('<div class="btn-loading"><div></div><div></div><div></div><div></div></div>');
        }
        if(flag==false)
        {
            let name = e.dataset.name;
            $(e).html('').text(name);
        }
    }   
};