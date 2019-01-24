/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	var page_applicant_mess_view_1 = __webpack_require__(2);
	$(document).ready(function () {
	    new page_applicant_mess_view_1.PageApplicantMessView();
	});


/***/ },
/* 1 */,
/* 2 */
/***/ function(module, exports) {

	"use strict";
	var PageApplicantMessView = (function () {
	    function PageApplicantMessView() {
	        this.lastMessId = 0;
	        this.T1NewMess = 0;
	        this.idTheme = 0;
	        var self = this;
	        self.init();
	    }
	    PageApplicantMessView.prototype.init = function () {
	        var self = this,
							gallery = {
								enabled: true,
								preload: [0, 2],
								navigateByImgClick: true,
								arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
								tPrev: '',
								tNext: '',
								tCounter: '<span class="mfp-counter">%curr% / %total%</span>'					
							};

	        self.idTheme = G_VARS.idTm;
	        if (G_VARS.idTm || G_VARS.isNew) {
	            self.setChatSize({ isNew: G_VARS.isNew });
	            G_VARS.isNew || self.getMessages(0);
	            $(".go button").click(function (e) {
                    $(this).prop('disabled', true);
	                self.sendMessage(e, this); });
	            if ($("#Mmessage").length)
	                self.initEditor();
	            $("#DiButtonPanel .js-attach-file").click(function (e) { self.onAttachClickFn(e, this); });
	            G_VARS.isNew && $("#DiButtonPanel .js-attach-file").hide();
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
	            G_VARS.isNew || Upli.setFiles(G_VARS.uniFiles);
	        }
				// проектор для картинок чата
				$('#DiMessagesInner').magnificPopup({
					delegate: '.-images a',
					type: 'image',
					gallery: gallery
				});
				// проектор для картинок сессии
				$('#DiImgs').magnificPopup({
					delegate: '.uni-img-link',
					type: 'image',
					gallery: gallery
				});
	    };
	    PageApplicantMessView.prototype.getMessages = function (inFirstId) {
	        if (inFirstId === void 0) { inFirstId = 0; }
	        var self = this;
	        //G_VARS.App.showLoading($("#DiMessagesWrapp"), 0, { top: 1, variant: 2 });
	        $.get(MainConfig.AJAX_GET_GETUSERMESAGES, { tm: G_VARS.idTm, fid: inFirstId }, function (data) {
	            data = JSON.parse(data);
	            self.prependMessages(data.data, data.count, inFirstId > 0);
	            var flag = 0;
	            $(".mess-box").each(function () {
	                var $that = $(this);
	                if ($that.hasClass('mess-from') && $that.hasClass('unread')) {
	                    flag || (flag = 1);
	                    if (!$('.new-mess').length) {
	                        var hr = $('.new-mess-tpl').clone();
	                        hr.toggleClass('new-mess-tpl new-mess tmpl');
	                        $that.before(hr);
	                    }
	                    if (flag)
	                        $that.addClass('-new');
	                    $("#DiMessagesInner").height($("#DiMessages").outerHeight());
	                }
	            });
	            self.timerCheckNewMess(true, 5000);
	        })
	            .always(function () {
	            //G_VARS.App.hideLoading();
	        });
	    };
	    PageApplicantMessView.prototype.onAtachFileChangeFn = function (e, that) {
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
	    PageApplicantMessView.prototype.onAttachClickFn = function (e, that) {
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
	    PageApplicantMessView.prototype.chkNewMessages = function () {
	        var self = this;

					$.get(
						MainConfig.AJAX_GET_GETNEWMESAGES, 
						{ tm: G_VARS.idTm, l: self.lastMessId },
						function (data) {
							data = JSON.parse(data);
							self.appendMessages(data.messages);
							self.timerCheckNewMess(true, 5000);
						})
					.always(function () { G_VARS.App.hideLoading() });
	    };
	    //
	    PageApplicantMessView.prototype.timerCheckNewMess = function () {
	    	var self = this;

				if(arguments[0]==true) {
					self.T1NewMess = setTimeout(
							function(){ self.chkNewMessages() },
							arguments[1]
						);
				}
				else {
					clearTimeout(self.T1NewMess);
				}
	    }
	    PageApplicantMessView.prototype.appendMessages = function (inMessages) {
					var self = this,
							flag = 0,
							DiMessages = $("#DiMessages");

					for (var ii in inMessages)
					{
							var block, val = inMessages[ii];
							if (val.isresp == 1)
							{
								block = $(".mess-from.tmpl").clone();
								block.find('.fio').text(val.namefrom);
								block.find('.author img').attr('src',val.photofrom);
							}
							else
							{
								block = $(".mess-to.tmpl").clone();
								block.find('.fio').text(val.nameto);
								block.find('.author img').attr('src',val.phototo);
								if(ii==0) // отображаем просмотрено или доставлено на последнем сообщении пользователя
								{
									var isviewed = (val.isread==1 ? 'Просмотрено' : 'Доставлено');
									block.find('.viewed').text(isviewed);
								}
							}
	            if (val.isread == '0' && val.isresp == '1') {
	                if (!$('.new-mess').length) {
	                    var hr = $('.new-mess-tpl').clone();
	                    hr.toggleClass('new-mess-tpl new-mess tmpl');
	                    DiMessages.append(hr);
	                }
	                flag = 1;
	            }
	            else {
	            }
	            if (flag)
	                block.addClass('-new');
	            block.find('.date').text(val.date);
	            block.find('.mess').html(val.message.replace("\n", '<br/>'));
	            self.prependFiles({ block: block, data: val });
	            DiMessages.append(block);
	            block.hide();
	            block.removeClass('tmpl');
	            block.fadeIn(400, function () {
	                var objDiv = document.getElementById("DiMessagesWrapp");
	                objDiv.scrollTop = objDiv.scrollHeight;
	            });
	            $("#DiMessagesInner").height(DiMessages.outerHeight());
	            block.find('.files img').one('load', function () {
	                $("#DiMessagesInner").height(DiMessages.outerHeight());
	            });
	            if (parseInt(self.lastMessId) < parseInt(val.id))
	                self.lastMessId = val.id;
	        }
	    };
	    PageApplicantMessView.prototype.onPrevMessClickFn = function (e, that, inFirstId) {
	        var self = this;
	        e.preventDefault();
	        self.getMessages(inFirstId);
	    };
	    PageApplicantMessView.prototype.prependMessages = function (inMessages, inCount, inNoScroll) {
					var self = this, 
							flag = 0, 
							hh = 0,
							DiMessages = $("#DiMessages"),
							DiMessagesInner = $("#DiMessagesInner");

					$('.prev-mess').remove();

	        for (var ii in inMessages)
	        {
							var block, val = inMessages[ii];

							if (val.isresp == 1)
							{
								block = $(".mess-from.tmpl").clone();
								block.find('.fio').text(val.namefrom);
								block.find('.author img').attr('src',val.photofrom);
							}
							else
							{
								block = $(".mess-to.tmpl").clone();
								block.find('.fio').text(val.nameto);
								block.find('.author img').attr('src',val.phototo);
								if(ii==0) // отображаем просмотрено или доставлено на последнем сообщении пользователя
								{
									var isviewed = (val.isread==1 ? 'Просмотрено' : 'Доставлено');
									block.find('.viewed').text(isviewed);
								}
							}
	            if (val.isread == '0')
	                block.addClass('unread');
	            block.find('.date').text(val.date);
	            block.find('.mess').html(val.message.replace("\n", '<br/>'));
	            self.prependFiles({ block: block, data: val });
	            DiMessages.prepend(block);
	            block.hide();
	            block.removeClass('tmpl');
	            block.fadeIn(400, function () {
	                if (!inNoScroll) {
	                    var objDiv = document.getElementById("DiMessagesWrapp");
	                    objDiv.scrollTop = objDiv.scrollHeight;
	                }
	            });
	            hh || (hh = DiMessagesInner.height());
	            DiMessagesInner.height(DiMessages.outerHeight());
	            block.find('.files img').one('load', function () {
	                DiMessagesInner.height(DiMessages.outerHeight());
	                $('#DiMessagesWrapp')[0].scrollTop = DiMessagesInner.height();
	            });
	            self.setChatSize();
	            if (parseInt(self.lastMessId) < parseInt(val.id))
	                self.lastMessId = val.id;
	        }
	        if (inNoScroll) {
	            $('#DiMessagesWrapp')[0].scrollTop = DiMessagesInner.height() - hh;
	        }
	        if (parseInt(inCount)) {
	            var prevBtn = $(".prev-mess-tpl").clone();
	            prevBtn.toggleClass('prev-mess-tpl tmpl prev-mess');
	            prevBtn.click(function (e) { self.onPrevMessClickFn(e, this, inMessages[inMessages.length - 1].id); });
	            DiMessages.prepend(prevBtn);
	            DiMessagesInner.height(DiMessages.outerHeight());
	        }
	    };
	    PageApplicantMessView.prototype.prependFiles = function (props) {
	        var self = this;
	        var block = props.block;
	        var data = props.data;
	        var files = block.find('.files');
	        var messBlock = block.find('.mess');
	        var fileTpl = files.find('a').clone();
	        var fileContainer = files.find('.js-container').clone();
	        files.empty();
	        var ii = 0;
	        var wrapper = fileContainer.clone();
	        for (var ii in data.files) {
	            var val2 = data.files[ii];
	            if (val2['meta']['type'] == 'files') {
	                var file = fileTpl.clone();
	                file.attr('href', val2['files']['orig'] + "," + self.idTheme).text(val2['meta']['name']);
	                wrapper.append(file);
	                ii++;
	            }
	        }
	        if (ii)
	            files.append(wrapper.addClass('-files clearfix'));
	        ii = 0;
	        wrapper = fileContainer.clone();
	        for (var ii in data.files) {
	            var val2 = data.files[ii];
	            if (val2['meta']['type'] == 'images') {
	                var file = fileTpl.clone();
	                file.attr('href', val2['files']['orig'] + "," + self.idTheme);
	                file.find('img').attr('src', val2['files']['tb'] + "," + self.idTheme);
	                wrapper.append(file);
	                ii++;
	            }
	        }
	        if (files.html() != '')
	            files.appendTo(messBlock);
	        if (ii) {
	            files.append(wrapper.addClass('-images'));
	        }
	    };
	    PageApplicantMessView.prototype.sendMessage = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();

	        self.timerCheckNewMess(false);
	        
	        if (self.NicEditor.nicInstances[0].getContent().replace('<br>', '').trim() == '')
	            return;

	        G_VARS.App.showLoading2($that, { outerAlign: 'left', valign: 'top', offsetX: -10, offsetY: 3 })

	        var flag = 1;
	        if (G_VARS.isNew) {
	            var theme = $("#EdTheme").val(),
	            		vid = $("#CBTheme").val(),
	            		props = { 'new': G_VARS.isNew, m: self.NicEditor.nicInstances[0].getContent() };

	            if(vid.trim().length && theme.trim().length)
	            {
	            	props['vid'] = vid;
	              props['t'] = theme;
	            }
	            if (theme.trim().length)
	              props['t'] = theme;
	        }
	        else {
	            var props = { tm: G_VARS.idTm, m: self.NicEditor.nicInstances[0].getContent(), l: self.lastMessId };
	        }
	        flag && $.post(MainConfig.AJAX_POST_SENDUSERMESAGES, props, function (data) {
	            clearTimeout(self.T1NewMess);
	            data = JSON.parse(data);
	            var DiMessages = $("#DiMessages");
	            $("#Mmessage").val('');
	            self.NicEditor.nicInstances[0].setContent('');
	            $(".message-box .nicEdit-main").focus();
	            $("#F3uploaded").fadeOut(200, function () { return $("#DiImgs").empty(); });
	            if (G_VARS.isNew) {
	                $(".nomess").fadeOut(200, function () { $(this).remove(); });
	                $(".theme-input").slideUp(200, function () { $(this).remove(); });
	                $(".header-021 b").text(data.theme);
	                //history.pushState(null, null, '/' + MainConfig.PAGE_IM + '/' + data.idtm);
	                G_VARS.idTm = data.idtm;
	                self.idTheme = data.idtm;
	                G_VARS.isNew = 0;
	                $("#DiButtonPanel .js-attach-file").fadeIn(200);
	            }
	            var flag = 0;
	            $('.new-mess').remove();
	            $('.mess-box.-new').removeClass('-new');

							if(data.messages[0].isresp==1) // если последнее сообщение работодателя
								$('#DiMessages .author .viewed').text(''); // очищаем все Просмотрено/Добавлено

							for (var ii in data.messages)
							{
									var block, val = data.messages[ii];

									if (val.isresp == 1)
									{
										block = $(".mess-from.tmpl").clone();
										block.find('.fio').text(val.namefrom);
										block.find('.author img').attr('src',val.photofrom);
									}
									else
									{
										block = $(".mess-to.tmpl").clone();
										block.find('.fio').text(val.nameto);
										block.find('.author img').attr('src',val.phototo);
										if(ii==0) // отображаем просмотрено или доставлено на последнем сообщении пользователя
										{
											var isviewed = (val.isread==1 ? 'Просмотрено' : 'Доставлено');
											block.find('.viewed').text(isviewed);
										}
	                }
	                if (val.isread == '0' && val.isresp == '1') {
	                    if (!$('.new-mess').length) {
	                        var hr = $('.new-mess-tpl').clone();
	                        hr.toggleClass('new-mess-tpl new-mess tmpl');
	                        DiMessages.append(hr);
	                        flag = 1;
	                    }
	                }
	                else {
	                }
	                if (flag)
	                    block.addClass('-new');
	                block.find('.date').text(val.date);
	                block.find('.mess').html(val.message.replace("\n", '<br/>'));
	                self.prependFiles({ block: block, data: val });
	                DiMessages.append(block);
	                block.hide();
	                block.removeClass('tmpl');
	                block.fadeIn(400, function () {
	                    var objDiv = document.getElementById("DiMessagesWrapp");
	                    objDiv.scrollTop = objDiv.scrollHeight;
	                });
	                $("#DiMessagesInner").height(DiMessages.outerHeight());
	                block.find('.files img').one('load', function () {
	                    $("#DiMessagesInner").height(DiMessages.outerHeight());
	                });
	                if (parseInt(self.lastMessId) < parseInt(val.id))
	                    self.lastMessId = val.id;
	            }
	            self.timerCheckNewMess(true,1);
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();

	            $(".go button").prop('disabled', false);
	        });
	    };
	    PageApplicantMessView.prototype.initEditor = function () {
	        var self = this;
	        var myNicEditor = new nicEditor({ maxHeight: 52, buttonList: ['bold', 'italic', 'underline'] });
	        self.NicEditor = myNicEditor;
	        myNicEditor.addInstance('Mmessage');
	        myNicEditor.setPanel('DiButtonPanel');
	    };
	    PageApplicantMessView.prototype.setChatSize = function (inProps) {
	        if (inProps === void 0) { inProps = {}; }
	        var self = this;
	        //$("#DiChatWrapp").height($(window).innerHeight());
	        if (!inProps.isNew)
	            $('html, body').animate({ scrollTop: $('.mess-box-end').offset().top }, 0);
	    };
	    return PageApplicantMessView;
	}());
	exports.PageApplicantMessView = PageApplicantMessView;


/***/ }
/******/ ]);