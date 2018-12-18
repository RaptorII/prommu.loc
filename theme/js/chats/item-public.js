'use strict'
var PublicChat = (function () {
	PublicChat.prototype.WINDOW = document.getElementById("DiMessagesWrapp");
	PublicChat.prototype.TAPE = document.getElementById("DiMessagesInner");
	PublicChat.prototype.VACANCY = $('#chat-vacancy').val();
	PublicChat.prototype.ajaxTimer = false;
	PublicChat.prototype.TIME = 5000;
	PublicChat.prototype.OFFSET = 1;
	PublicChat.prototype.isMyMess = false;
	PublicChat.prototype.idTheme = $('#chat-vacancy').val();

	function PublicChat() { this.init(); }
	//
	PublicChat.prototype.init = function ()
	{
		let self = this,
				myNicEditor = new nicEditor(
					{ 
						maxHeight: 52, 
						buttonList: ['bold', 'italic', 'underline'] 
					}
				);

		if ($("#Mmessage").is('*'))
		{
			self.NicEditor = myNicEditor;
			myNicEditor.addInstance('Mmessage');
			myNicEditor.setPanel('DiButtonPanel');
			$(".go button").click( function(e){ self.sendMessage(e.target) } );
		}

		self.WINDOW.scrollTop = self.WINDOW.scrollHeight;

		self.ajaxActivate('new');

		$(self.WINDOW).scroll(function(){
        if($(this).scrollTop()==0)
        	self.ajaxActivate('old');
		});

        $("#DiButtonPanel .js-attach-file").click(function (e) { self.onAttachClickFn(e, this); });
        var Upli = new Uploaduni();
        self.uploaduni = Upli;
        Upli.init({ uploadConnector: MainConfig.AJAX_POST_UPLOADUNI_EX,
            scope: 'im',
            imgBlockTmpl: 'attached-image-tpl',
            filesBlockTmpl: 'attached-file-tpl',
            imgsWrapper: '#DiImgs',
            filesWrapper: '#DiFiles',
            lnktoimg: 'orig',
            uploadForm: '#F2upload',
            messageBlock: '.message',
            loadingBLock: '.loading-ico',
            onDeleteEnd: function (item) {
                if ($('#DiImgs').find('.uni-delete').length < 1 && $('#DiFiles').find('.uni-delete').length < 1)
                    $("#F3uploaded").fadeOut(200);
            },
        });
        Upli.setFiles(G_VARS.uniFiles);
	};

    PublicChat.prototype.onAttachClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        var form = $("#TmplF2upload").html();
        ModalWindow.open({ content: form, action: { active: 0 }, bgIsCloseBtn: 0, position: 'fixed', context: '#DiContent',
            afterOpen: function () {
                ModalWindow.content.find('.btn-upload button').click(function (e) { $("#UplImg").click(); });
                ModalWindow.content.find('#UplImg').change(function (e) { self.onAtachFileChangeFn(e, this); });
            }
        });
    };

    PublicChat.prototype.onAtachFileChangeFn = function (e, that) {
        var self = this;
        var $that = $(that);
        self.uploaduni.uploadEx({ 'uploadInput': that, meta: { idTheme: self.idTheme },
            onSuccessEnd: function (item) {
                $("#F3uploaded").fadeIn(400);
                ModalWindow.close();
                Hinter.bind(item.find('.js-hashint'));
            },
            onAfterUpload: function (data) {
                for (var ii in data['file']['files']) {
                    var val = data['file']['files'][ii];
                    data['file']['files'][ii] += ',' + self.idTheme;
                }
                return data;
            },
        });
    };
	//
	PublicChat.prototype.sendMessage = function(button)
	{
		let self = this,
				message = self.NicEditor.nicInstances[0].getContent().trim(),
				data = {vacancy:self.VACANCY, message:message};

		if(!message.length || message==='<br>')
			return;

		$(button).addClass('load');
        $(button).prop('disabled', true);

		$.ajax({
			type: 'POST',
			url: '/ajax/Chat',
			data: {data: JSON.stringify(data)},
			success: function(result) {
				result = JSON.parse(result);
				if(result.error)
				{
					ModalWindow.open({ 
						content: "<div data-header='Ошибка'>Пожалуйста обновите страницу</div>", 
						action: { active: 0 }, 
						additionalStyle:'dark-ver' 
					});
				}
				else
				{
					self.ajaxActivate('new');
					self.NicEditor.nicInstances[0].setContent('');
					$(button).removeClass('load');
					self.isMyMess = true;
				}
                $(button).prop('disabled', false);
			},
		});
	}
	//
	PublicChat.prototype.getMessages = function(type)
	{
		let self = this,
				lastMess = undefined,
				data = { vacancy : self.VACANCY };

		if(type==='new')
			lastMess = $(self.TAPE).find('.mess-box:eq(-1)')[0]
		if(type==='old')
			lastMess = $(self.TAPE).find('.mess-box:eq(0)')[0]

		if(lastMess!=undefined && type==='new')
			data['id_message'] = lastMess.dataset.id;
		if(lastMess!=undefined && type==='old')
			data['offset'] = self.OFFSET;

		$('.go button').addClass('load');
		$(self.WINDOW).addClass('load');
		$.ajax({
			type: 'GET',
			url: window.location.pathname,
			data: data,
			success: function(result)
			{
				if(lastMess==undefined && result.length) // первое собщение
				{
					$(self.TAPE).html(result);
					self.WINDOW.scrollTop = self.WINDOW.scrollHeight;
				}
				else if(type==='new')
				{
					if(result.length)
					{
						$('#DiMessagesInner .new-mess').remove(); // удаляем подсказку о новом сообщении
					}
					$(self.TAPE).append(result);
					if(self.isMyMess)
					{
						self.WINDOW.scrollTop = self.WINDOW.scrollHeight;
						self.isMyMess = false;
					}
				}
				else if(type==='old')
				{
					let scroll = self.WINDOW.scrollHeight;
					if(result.length)
					{
						self.OFFSET++;
						$(self.TAPE).prepend(result);
						self.WINDOW.scrollTop = self.WINDOW.scrollHeight - scroll;
					}	
				}	
			},
			complete: function(){
				$('.go button').removeClass('load');
				$(self.WINDOW).removeClass('load');
			}
		});
	}
	//
	PublicChat.prototype.ajaxActivate = function(type)
	{
		let self = this;
		clearTimeout(self.ajaxTimer);
		self.getMessages(type);
		self.ajaxTimer = setTimeout(function(){self.ajaxActivate('new')}, self.TIME);
	}

	return PublicChat;
}());
/*
*
*/
$(document).ready(function () {
	new PublicChat();
});