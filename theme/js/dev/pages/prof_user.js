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
	var page_register_applicant_1 = __webpack_require__(4);
	$(document).ready(function () {
	    new page_register_applicant_1.PageRegApplic();
	});


/***/ },
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	var __extends = (this && this.__extends) || function (d, b) {
	    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
	    function __() { this.constructor = d; }
	    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
	};
	var PageRegApplic = (function (_super) {
	    __extends(PageRegApplic, _super);
	    function PageRegApplic() {
	        _super.call(this);
	        var self = this;
	        self.init();
	    }
	    PageRegApplic.prototype.init = function () {
	        var self = this;
	        if(G_VARS.Modal == 1) {
	        $(function (e) { self.onInformationClick(e, this); }); }
	        Hinter.bind('.hashint');
	        $("#BtnUploadPhoto a").click(function (e) { self.onUploadPhotoClickFn(e, this); });
	        $(".btn-update button, .btn-save button").click(function (e) { $("#Hisavest").val($(this).hasClass('savest') ? "2" : "1"); });
	        $(".c-logo ._controls ._delete").click(function (e) { confirm($(this).data('confirm')) ? '' : e.preventDefault(); });
	        $('.c-logo').magnificPopup({
	            delegate: '._more-photo>a',
	            type: 'image',
	            gallery: {
	                enabled: true,
	                preload: [0, 2],
	                navigateByImgClick: true,
	                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
	                tPrev: '',
	                tNext: '',
	                tCounter: '<span class="mfp-counter">%curr% / %total%</span>'
	            }
	        });
	        var cbParams = {
	            selectAllText: 'Выбрать все/снять выделение',
	            countSelected: '# из %',
	            allSelected: 'Выбраны все',
	        };

	    };

	    PageRegApplic.prototype.onInformationClick = function (e, that) {
	        var self = this;
	        var form = $(".Infos").clone();
	        ModalWindow.open({ content: form, action: { active: 0 }, bgIsCloseBtn: 0, position: 'absolute', context: 'body',
	            afterOpen: function () { $("#DiSiteWrapp").css({ overflow: "hidden" }); },
	            afterClose: function () { $("#DiSiteWrapp").css({ overflow: "" }); }
	        });
	    };
	    PageRegApplic.prototype.renderMetroesHtml = function (inIds, inValues) {
	        var self = this;
	        var ids = [];
	        $("#DiMetroBlock .metro-select").each(function () {
	            var ID = parseInt($(this).attr('data-id'));
	            ids.push(ID);
	            var flag = 0;
	            for (var ii in inIds)
	                if (inIds[ii] == ID) {
	                    flag = 1;
	                    break;
	                }
	            if (!flag)
	                $(this).slideUp(200, function () { $(this).remove(); });
	        });
	        inIds.map(function (id, ii) {
	            var flag = 0;
	            for (var jj in ids)
	                if (ids[jj] == id) {
	                    flag = 1;
	                    break;
	                }
	            if (!flag) {
	                if (!G_VARS.cityWmetro[id])
	                    return 1;
	                var metroBlock = $("#LMetro").clone();
	                metroBlock.hide();
	                metroBlock.attr('id', 'CBmetro' + id);
	                metroBlock.attr('data-id', id);
	                metroBlock.find('b').text(function (i, text) {
	                    return text.concat(inValues[ii]);
	                });
	                var select = metroBlock.find('select');
	                select.attr('name', 'metro'.concat('[', id, '][]'));
	                G_VARS.appcache.metro['city' + id].map(function (val) {
	                    var flag = 0;
	                    for (var kk in G_VARS.userMetro)
	                        if (id == G_VARS.userMetro[kk].idcity && val.id == kk) {
	                            flag = 1;
	                            break;
	                        }
	                    ;
	                    var selected = flag ? 'selected' : '';
	                    $("<option value='".concat(val.id, "' ", selected, ">", val.name, "</option>"))
	                        .appendTo(select);
	                });
	                $("#DiMetroBlock").append(metroBlock);
	                select.multipleSelect({
	                    selectAllText: 'Выбрать все/снять выделение',
	                    countSelected: '# из %',
	                    allSelected: 'Выбраны все',
	                    placeholder: "выберите метро...",
	                    filter: true
	                });
	                metroBlock.slideDown(400);
	            }
	        });
	    };
	
	    PageRegApplic.prototype.debug = function () {

	    };
	    PageRegApplic.prototype.onUploadPhotoClickFn = function (e, that) {
	        var self = this;
	        e.preventDefault();
	        var form = $(".F2photo").clone();
	        form.toggleClass('tmpl F2photo').attr('id', 'F2photo');
	        form.find('.btn-startupload a').click(function (e) {
	            e.preventDefault();
	            $(this).closest('form').find('.message').text('');
	            $(this).closest('form').find(".inp-photo").trigger('click');
	        });
	        form.find(".inp-photo").change(function () { self.onUploadFileSetFn(this); });
	        form.find(".btn-crop").click(function (e) { self.onSendCroppedFn(e, this); });
	        $('body').stop().animate({ scrollTop: 0 + 'px' }, 0);
	        ModalWindow.open({ content: form, action: { active: 0 }, bgIsCloseBtn: 0, position: 'absolute', context: 'body',
	            afterOpen: function () { $("#DiSiteWrapp").css({ overflow: "hidden" }); },
	            afterClose: function () { $("#DiSiteWrapp").css({ overflow: "" }); }
	        });
	    };
	    PageRegApplic.prototype.onUploadFileSetFn = function (that) {
	        var self = this;
	        var $that = $(that);
	        var file = that.files[0];
	        var flag = 1, message;
	        var form = $that.closest('form');
	        var messageBox = form.find('.message');
	        if (file) {
	            if (file.type != "image/jpeg" && file.type != "image/png") {
	                flag = 0;
	                message = messageBox.data('type');
	            }
	            else if (file.size > 5242880) {
	                flag = 0;
	                message = messageBox.data('size');
	            }
	            if (!flag) {
	                messageBox.text(message);
	            }
	            else {
	                form.find('.loading-ico').fadeIn(400);
	                var formData = new FormData(form[0]);
	                $.ajax({
	                    url: MainConfig.AJAX_POST_POSTLOGOFILE,
	                    type: 'POST',
	                    success: function (data) {
	                        data = JSON.parse(data);
	                        if (data.error) {
	                            form.find('.message').text(data.message);
	                            if (data.ret)
	                                console.log('ret', data.ret);
	                        }
	                        else {
	                            var img = form.find('.img-crop-tpl').clone();
	                            img.toggleClass('img-crop img-crop-tpl tmpl');
	                            var imgBlock = form.find('.img-block ._img-wrapp');
	                            imgBlock.empty();
	                            imgBlock.prepend(img);
	                            img.css({ display: 'none' });
	                            img.attr('src', data.file).load(function () {
	                                var height = this.height > this.width ? this.height : 0;
	                                $('body').stop().animate({ scrollTop: 0 + 'px' }, 0);
	                                form.find(".img-crop-block").slideDown(400, function () {
	                                    ModalWindow.hide();
	                                    form.find('.btn-startupload').fadeOut(200);
	                                    form.find('.info').fadeOut(200);
	                                    setTimeout(function () {
	                                        img.show();
	                                        if (height > 700)
	                                            img.css({ height: '700px' });
	                                        ModalWindow.moveCenter({ fade: true });
	                                        ModalWindow.show();
	                                        if (self.cropper)
	                                            self.cropper.destroy();
	                                        self.cropper = new Cropper(img[0], {
	                                            aspectRatio: 1 / 1,
	                                            viewMode: 1,
	                                            minCropBoxWidth: 100,
	                                            minCropBoxHeight: 100,
	                                            zoomable: false,
	                                            rotatable: false,
	                                            background: false,
	                                            guides: false,
	                                            highlight: false,
	                                            crop: function (e) {
	                                                self.cropOpts = e.detail;
	                                            }
	                                        });
	                                    }, 500);
	                                });
	                            });
	                        }
	                    },
	                    error: function () {
	                        form.find('.message').text("Ошибка загрузки файла, обновите страницу и попробуйте еще раз");
	                    },
	                    data: formData,
	                    cache: false,
	                    contentType: false,
	                    processData: false
	                }, 'json')
	                    .always(function () {
	                    form.find('.loading-ico').fadeOut(200);
	                });
	            }
	        }
	    };
	    PageRegApplic.prototype.onSendCroppedFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var form = $that.closest('form');
	        e.preventDefault();
	        form.find('.loading-ico').fadeIn(400);
	        $.post(MainConfig.AJAX_POST_CROPLOGO, self.cropOpts, function (data) {
	            data = JSON.parse(data);
	            $("#HiLogo").val(data.idfile);
	            
	        })
	            .always(function () {
	            form.find('.loading-ico').fadeOut(200);
	             // var img = $("#DiAvatar img");
	             //    img.attr('src', img.data('path') + '/' + data.file);
	               
	                self.cropper.destroy();
	                ModalWindow.close();
	        });
	    };
	    return PageRegApplic;
	}(Page));
	exports.PageRegApplic = PageRegApplic;


/***/ }
/******/ ]);