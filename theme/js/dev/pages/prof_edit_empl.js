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
/******/ ({

/***/ 0:
/***/ function(module, exports, __webpack_require__) {

	"use strict";
	var page_register_company_1 = __webpack_require__(5);
	$(document).ready(function () {
	    new page_register_company_1.PageRegCompany();
	});


/***/ },

/***/ 5:
/***/ function(module, exports) {

	"use strict";
	var __extends = (this && this.__extends) || function (d, b) {
	    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
	    function __() { this.constructor = d; }
	    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
	};
	var PageRegCompany = (function (_super) {
	    __extends(PageRegCompany, _super);
	    function PageRegCompany() {
	        _super.call(this);
	        this.customCity = 'aa';
	        this.vT1City = 0;
	        var self = this;
	        $("#CB1country").change(function (e) { self.onCB1countryChangeFn(e, this); });
	        $("#F1compprof").submit(function (e) { self.onFormSubmit(e, this); });
	        $(".add-city-block .add-city").click(function (e) { self.onAddCityClickFn(e, this); });
	        $(".add-city-block input").keypress(function (e) { return !(e.which == 13); })
	            .keyup(function (e) { return self.onDLCityKeyPressFn(e, this); })
	            .blur(function (e) { self.onDLCityBlurFn(e, this); });
	        $(".add-city-block .ok").click(function (e) { self.onOkCityClickFn(e, this); });
	        self.bindFiltersFn();
	        self.init();
	    }
	    PageRegCompany.prototype.init = function () {
	        var self = this;
	        if(G_VARS.Modal == 1) {
	        $(function (e) { self.onInformationClick(e, this); }); }
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
	        $('#CB2city').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, { placeholder: "выберите город...",
	            noMatchesFound: 'Введите город справа ->',
	            selectAll: false,
	            allSelected: '',
	            onClick: function (p1) { self.onCB2cityChangeFn(p1); }, }));
	    };
	    PageRegCompany.prototype.onCB2cityChangeFn = function (props, all) {
	        var self = this;
	        var vals = $('#CB2city').multipleSelect('getSelects');
	        if ($.inArray(self.customCity, vals) > -1) {
	            $("#CityManualMultiBLock").slideDown(400).find('input').removeClass('nocheck');
	        }
	        else {
	            $("#CityManualMultiBLock").slideUp(200).find('input').addClass('nocheck');
	        }
	    };
	    PageRegCompany.prototype.onCB1countryChangeFn = function (e, that) {
	        var self = this;
	        G_VARS.App.country = $(that).val();
	        $('#CB2city').empty().multipleSelect('refresh');
	    };
	    PageRegCompany.prototype.onAddCityClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        $that.toggleClass('opened').parent().find('.dropdown-block').slideToggle(300)
	            .find('input').focus();
	    };
	    PageRegCompany.prototype.onDLCityBlurFn = function (e, that) {
	        var self = this;
	        setTimeout(function () {
	            var $that = $(that);
	            $that.closest('.dropdown-block').slideUp(200);
	            $that.closest('.add-city-block').find('.add-city').removeClass('opened');
	        }, 300);
	    };
	    PageRegCompany.prototype.onDLCityKeyPressFn = function (e, that) {
	        e = e || event;
	        var self = this;
	        var $that = $(that);
	        var idco = G_VARS.countryID;
	        var wrapper = $that.closest('.add-city-block');
	        var select = wrapper.find('.dropdown').stop().slideDown(300);
	        var choices = wrapper.find('.choices');
	        if (idco) {
	            clearTimeout(self.vT1City);
	            self.vT1City = setTimeout(function () {
	                G_VARS.App.showLoading($that, 1);
	                $.get(MainConfig.AJAX_GET_CITYES, { filter: $that.val(), idco: G_VARS.countryID, limit: 20 }, function (data) {
	                    data = JSON.parse(data);
	                    choices.empty();
	                    for (var ii in data) {
	                        $("<a href='#' class='item'".concat(" data-name='", data[ii].name, "' data-id='", data[ii].id_city, "'>", data[ii].name, "</a>"))
	                            .appendTo(choices);
	                    }
	                    if (data.length < 1) {
	                        select.stop().slideUp(200);
	                    }
	                    else
	                        select.css({ height: '' }).slideDown(300);
	                    select.find('.item').click(function (e) { self.onDLCityItemCLickFn(e, this); });
	                }).always(function () {
	                    G_VARS.App.hideLoading();
	                });
	            }, 500);
	        }
	    };
	    PageRegCompany.prototype.onDLCityItemCLickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        $that.closest('.dropdown-block').find('input').val($that.text());
	        if (!self.findAddedCity($that.text()))
	            $('#CB2city').prepend("<option value='".concat($that.data('id'), "' selected>", $that.text(), "</option>"))
	                .multipleSelect('refresh');
	        $that.closest('.dropdown-block').slideUp(200);
	        $that.closest('.add-city-block').find('.add-city').removeClass('opened');
	    };
	    PageRegCompany.prototype.onOkCityClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.add-city-block');
	        var input = wrapper.find('[type=text]');
	        e.preventDefault();
	        var flag = 0;
	        wrapper.find('.item').each(function () {
	            var $that = $(this);
	            if (($that.data('name')).toUpperCase() == (input.val()).toUpperCase()) {
	                flag = $that.data('id');
	            }
	        });
	        if (!self.findAddedCity(input.val()))
	            $('#CB2city').prepend("<option value='".concat(flag ? flag : input.val(), "' selected>", input.val(), "</option>"))
	                .multipleSelect('refresh');
	        $that.closest('.dropdown-block').slideUp(200);
	        $that.closest('.add-city-block').find('.add-city').removeClass('opened');
	    };
	    PageRegCompany.prototype.findAddedCity = function (inName) {
	        var wrapper = $('#CB2city');
	        var flag = 0;
	        wrapper.find('option').each(function () {
	            var $that = $(this);
	            if (($that.text()).toUpperCase() == inName.toUpperCase()) {
	                flag = $that.val();
	                return flag;
	            }
	        });
	        return flag;
	    };
	    PageRegCompany.prototype.onInformationClick = function (e, that) {
	        var self = this;
	        // e.preventDefault();
	        var form = $(".Info").clone();
	        // form.toggleClass('tmpl F2photo').attr('id', 'F2photo');
	        // form.find('.btn-startupload a').click(function (e) {
	        //     e.preventDefault();
	        //     $(this).closest('form').find('.message').text('');
	        //     $(this).closest('form').find(".inp-photo").trigger('click');
	        // });
	        // form.find(".inp-photo").change(function () { self.onUploadFileSetFn(this); });
	        // form.find(".btn-crop a").click(function (e) { self.onSendCroppedFn(e, this); });
	        ModalWindow.open({ content: form, action: { active: 0 }, bgIsCloseBtn: 0, position: 'absolute', context: 'body',
	            afterOpen: function () { $("#DiSiteWrapp").css({ overflow: "hidden" }); },
	            afterClose: function () { $("#DiSiteWrapp").css({ overflow: "" }); },
	            location:"https://prommu.com/user/editprofile?ep=1"
	        });
	    };
	    PageRegCompany.prototype.onUploadPhotoClickFn = function (e, that) {
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
	    PageRegCompany.prototype.onUploadFileSetFn = function (that) {
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
	                            if (data.code)
	                                console.log('code', data.code);
	                        }
	                        else {
	                            var img = form.find(".img-crop");
	                            img.attr('src', data.file).load(function () {
	                                $('body').stop().animate({ scrollTop: 0 + 'px' }, 0);
	                                form.find(".img-crop-block").slideDown(400, function () {
	                                    ModalWindow.moveCenter();
	                                    setTimeout(function () {
	       	                                form.find('.btn-startupload').fadeOut(200);
	                                        form.find('.info').fadeOut(200);
	                                        var minWidth = img.width() > img.height() ? img.width() : img.height();
	                                        if (minWidth > 700)
	                                            minWidth = 700;
	                                        if (self.cropper)
	                                            self.cropper.destroy();
	                                        self.cropper = new Cropper(img[0], {
	                                            aspectRatio: 1 / 1,
	                                            viewMode: 0,
	                                            minCropBoxWidth: 100,
	                                            minCropBoxHeight: 100,
	                                            minCanvasWidth: minWidth,
	                                            minCanvasHeight: minWidth,
	                                            minContainerWidth: minWidth,
	                                            minContainerHeight: minWidth,
	                                            zoomable: true,
	                                            rotatable: true,
	                                            background: false,
	                                            guides: false,
	                                            highlight: false,
	                                            crop: function (e) {
	                                                self.cropOpts = e.detail;
	                                            }
	                                        });
				                            $('.cropper__rotate-right').click(function(){
				                              self.cropper.rotate(90);
				                            });
				                            $('.cropper__rotate-left').click(function(){
				                              self.cropper.rotate(-90);
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
	    PageRegCompany.prototype.onSendCroppedFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var form = $that.closest('form');
	        e.preventDefault();
	        form.find('.loading-ico').fadeIn(400);
	        $.post(MainConfig.AJAX_POST_CROPLOGO, self.cropOpts, function (data) {
	            data = JSON.parse(data);
	            if (data.error) {
	                form.find('.message').text(data.message);
	                if (data.ret)
	                    console.log('ret', data.ret);
	                if (data.code)
	                    console.log('ret', data.code);
	            }
	            else {
	                var img = $("#DiAvatar img");
	                img.attr('src', img.data('path') + '/' + data.file);
	                $("#HiLogo").val(data.idfile);
	                self.cropper.destroy();
	                ModalWindow.close();
	            }
	        })
	            .always(function () {
	            form.find('.loading-ico').fadeOut(200);
	        });
	    };
	    return PageRegCompany;
	}(Page));
	exports.PageRegCompany = PageRegCompany;


/***/ }

/******/ });