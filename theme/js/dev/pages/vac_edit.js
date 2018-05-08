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
	var page_vacancy_edit_1 = __webpack_require__(6);
	$(document).ready(function () {
	    new page_vacancy_edit_1.PageVacancyEdit();
	});


/***/ },

/***/ 6:
/***/ function(module, exports) {

	"use strict";
	var __extends = (this && this.__extends) || function (d, b) {
	    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
	    function __() { this.constructor = d; }
	    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
	};
	var PageVacancyEdit = (function (_super) {
	    __extends(PageVacancyEdit, _super);
	    function PageVacancyEdit() {
	        _super.call(this);
	        this.doljChoice = 'aa';
	        this.isEditMode = false;
	        var self = this;
	        $("#EdPaylim").change(function () {
	            self.onEdPaylimChange(this);
	        });
	        $("#BtnSubmit").click(function (e) {
	            self.onF1vacancySubmit(e, this);
	        });
	        self.init();
	    }
	    PageVacancyEdit.prototype.init = function () {
	        var self = this;
	        Hinter.bind(".btn-edit-block:not(.tmpl) a");
	        $(".js-btnAddCity button").click(function (e) { self.onAddCityClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.locations-block .btn-close a', function (e) { self.onCloseLocationClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', 'div.btn-editcity-bind a', function (e) { self.onEditCityInfoClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.ph-city-info-block-wrapp .error-message a', function (e) { self.onCityDataRefreshClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.city-block:not(.new-block-bind) .btn-save-city-bind .cancel', function (e) { self.onCityBlockCancelClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.btn-save-city-bind .save', function (e) { self.onCityBlockSaveClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.new-block-bind .btn-save-city-bind .cancel', function (e) { self.onNewCityBlockCancelClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.ph-city-info-block .btn-city-close a', function (e) { self.onCityDelClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.BtnAddLoc button', function (e) { self.onAddLocationClick(e, this); });
	        $("#DiCitiBlocks").on('click', '.btn-edit-block a', function (e) { self.onEditLocationClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.location-wrapper .error-edit-bind a, .location-wrapper .error-message a', function (e) { self.onLocDataRefreshClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.location-wrapper:not(.new-location) .btn-save-location-bind .cancel', function (e) { self.onEditLocCancelClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.location-wrapper.new-location .btn-save-location-bind .cancel', function (e) { self.onNewLocCancelClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.locations-block .edit-period-btns .add-per-bind, .js-city-block .edit-period-btns .add-per-bind', function (e) { self.onAddPeriodClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.locations-block .edit-period-btns .del-per-bind', function (e) { self.onDelPeriodClickFn(e, this); });
	        $("#DiCitiBlocks").on('click', '.locations-block .btn-close-bind a', function (e) { self.onLocationDelClickFn(e, this); });
	        $(".js-city-block").on('click', '.edit-period-btns .add-per-bind', function (e) { self.onAddPeriodClickFn(e, this); });
	        $(".js-city-block").on('click', '.edit-period-btns .del-per-bind', function (e) { self.onDelPeriodClickFn(e, this); });
	        $("#F1save, #F1vacancy").submit(function (e) { self.onFormSubmit(e, this); });
	        $("#BtnSubmit").click(function (e) { $(this).closest('form').submit(); });
	        $("#CB7exp").change(function () {
	            var expBLock = $('.exp-block');
	            if (expBLock.hasClass('show'))
	                expBLock.show().removeClass('show');
	            if ($(this).val() == 1)
	                expBLock.slideUp(200);
	            else
	                expBLock.slideDown(400);
	        });
	        var tomorrow = new Date();
	        tomorrow.setDate(tomorrow.getDate() + 1);
	        $('#EdDateAU').datepicker({
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            startDate: tomorrow,
	            autoclose: true,
	            language: "ru"
	        });
	        $('#CB1Dolj').change(function () {
	        }).multipleSelect({
	            placeholder: "выберите должность...",
	            selectAll: false,
	            countSelected: '# / %',
	            onUncheckAll: function (p1) {
	                self.onCB1DoljChangeFn(p1);
	            },
	            onClick: function (p1) {
	                self.onCB1DoljChangeFn(p1);
	            },
	            onCheckAll: function (p1) {
	                self.onCB1DoljChangeFn(p1, 1);
	            },
	        });
	        var cityBlock = $(".js-city-block");
	        (new AutocompleteHelper()).bind(cityBlock.find('.js-city'), {
	            url: MainConfig.AJAX_GET_VE_GET_CITIES,
	            afterItemSelected: function (data) {
	                var city = cityBlock.find('.js-city');
	                if (data.data == 'man') {
	                    city.parent().find('.js-bind').text("(" + city.data('man') + ")");
	                }
	                else
	                    city.parent().find('.js-bind').text(' ');
	                cityBlock.find('.js-idcity').val(data.data);
	            }
	        });
	        var params = {
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            autoclose: true,
	            startDate: (new Date()),
	            language: G_VARS.locale
	        };
	        var dateEndDP = $(".js-city-block .period-line-edit").find('.js-edate');
	        $(".js-city-block .period-line-edit").find('.js-bdate').datepicker(params)
	            .on('changeDate', function (p1, p2) {
	            var dateEndDP = $(".js-city-block .period-line-edit").find('.js-edate');
	            dateEndDP.datepicker('setStartDate', p1.date);
	            dateEndDP.datepicker('setDate', p1.date);
	        });
	        dateEndDP.datepicker(params);
	        var dateEndDP = $('#DiCitiBlocks .date-end-block input, .js-city-block .date-end-block input');
	        $('#DiCitiBlocks .date-start-block input, .js-city-block .date-start-block input').datepicker({
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            startDate: (new Date()),
	            autoclose: true,
	            language: G_VARS.locale
	        }).on('changeDate', function (p1, p2) {
	            dateEndDP.datepicker('setStartDate', p1.date);
	            dateEndDP.datepicker('setDate', p1.date);
	        });
	        dateEndDP.datepicker({
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            startDate: (new Date()),
	            autoclose: true,
	            language: G_VARS.locale
	        });
	        $('#CBlangs').change(function () {
	        }).multipleSelect({
	            placeholder: "выберите языки...",
	            selectAllText: 'Выбрать все/снять выделение',
	            allSelected: 'Выбраны все варианты',
	            countSelected: '# / %',
	            onUncheckAll: function (p1) {
	                self.onCBlangsChangeFn(p1);
	            },
	            onCheckAll: function (p1) {
	                self.onCBlangsChangeFn(p1, 1);
	            },
	            onClick: function (p1) {
	                self.onCBlangsChangeFn(p1);
	            },
	        });
	        self.bindFiltersFn('.js-city-block');
	        self.FormCheckers.addCheckerCustom('salary', function (props) { return self.onSubmitSalaryCheck(props); });
	        self.FormCheckers.addCheckerCustom('sex', function (props) { return self.onSubmitSexCheck(props); });
	    };
	    PageVacancyEdit.prototype.addMetro = function (inData) {
	        var self = this;
	        var idcity = inData.id;
	        if (!G_VARS.appcache['metro' + idcity]) {
	            $.get(MainConfig.AJAX_GET_METRO, { idcity: idcity }, function (data) {
	                data = JSON.parse(data);
	                G_VARS.appcache['metro' + idcity] = data;
	                self.fillMetroBlockAll({ obj: inData.obj, data: data, id: inData.id });
	            }).always(function () {
	                G_VARS.App.hideLoading();
	            });
	        }
	        else {
	            self.fillMetroBlockAll({ obj: inData.obj, data: G_VARS.appcache['metro' + idcity], id: inData.id });
	            G_VARS.App.hideLoading();
	        }
	    };
	    PageVacancyEdit.prototype.fillMetroBlockAll = function (inData) {
	        var self = this;
	        var metroBlocks = inData.obj.find('.locations-block');
	        for (var ii = 0, countii = metroBlocks.length; ii < countii; ii++) {
	            self.fillMetroBlock(CommFuncs.merge(inData, { obj: metroBlocks.eq(ii) }));
	        }
	    };
	    PageVacancyEdit.prototype.fillMetroBlock = function (inData) {
	        var self = this;
	        var mbl = inData.obj.find('.ph-metro-block');
	        var data = inData.data;
	        var select = mbl.find('select');
	        select.empty();
	        $("<option value='0'>" + select.data('defitm') + "</option>").appendTo(select);
	        for (var ii in data) {
	            var val = data[ii];
	            var selected = val.id == inData.mid ? 'selected' : '';
	            $("<option value='" + val.id + "' " + selected + ">" + val.name + "</option>")
	                .appendTo(select);
	        }
	        mbl.slideDown(400);
	    };
	    PageVacancyEdit.prototype.onCB2cityChangeFn = function (e, that) {
	        var self = this;
	        var items = $('#CB2city').multipleSelect('getSelects');
	        var itemsText = $('#CB2city').multipleSelect('getSelects', 'text');
	        var flag = 0;
	        for (var ii in items) {
	            if ('aa' == items[ii]) {
	                flag = 1;
	                break;
	            }
	        }
	        if (flag) {
	            $("#DiCityCustom").slideDown(400);
	        }
	        else
	            $("#DiCityCustom").slideUp(200);
	        if (items.length) {
	            $("#CBcountry").slideUp(200);
	            $("#EdCountry").slideDown(200);
	        }
	        else {
	            $("#EdCountry").slideUp(200);
	            $("#CBcountry").slideDown(200);
	        }
	        var ids = [];
	        $('#DiMetroesBlock .metro-block').each(function () {
	            var ID = parseInt($(this).attr('data-id'));
	            ids.push(ID);
	            var flag = 0;
	            for (var jj in items)
	                if (ID == items[jj]) {
	                    flag = 1;
	                    break;
	                }
	            if (!flag)
	                $(this).slideUp(200, function () {
	                    $(this).find('select').prop('disabled', true);
	                });
	            else
	                $(this).slideDown(400, function () {
	                    $(this).find('select').prop('disabled', false);
	                });
	        });
	    };
	    PageVacancyEdit.prototype.onCB1DoljChangeFn = function (props, all) {
	        var self = this;
	        var items = $('#CB1Dolj').multipleSelect('getSelects');
	        var itemsText = $('#CB1Dolj').multipleSelect('getSelects', 'text');
	        $("#CB1DoljData").text(itemsText.join(', '));
	        if (all)
	            $("#CB1DoljData").text('');
	        var flag = 0;
	        for (var ii in items) {
	            if (self.doljChoice == items[ii]) {
	                flag = 1;
	                break;
	            }
	        }
	        if (flag) {
	            $(".self-dolg").slideDown(400);
	        }
	        else {
	            $(".self-dolg").slideUp(200);
	        }
	    };
	    PageVacancyEdit.prototype.onCBlangsChangeFn = function (props, all) {
	        var self = this;
	        var items = $('#CBlangs').multipleSelect('getSelects');
	        var itemsText = $('#CBlangs').multipleSelect('getSelects', 'text');
	        var ids = [];
	        $('.lang-level').each(function () {
	            var ID = parseInt($(this).attr('data-id'));
	            ids.push(ID);
	            var flag = 0;
	            for (var ii in items)
	                if (items[ii] == ID) {
	                    flag = 1;
	                    break;
	                }
	            if (!flag)
	                $(this).slideUp(200, function () {
	                    $(this).remove();
	                });
	        });
	        for (var ii in items) {
	            var flag = 0;
	            for (var jj in ids)
	                if (ids[jj] == items[ii]) {
	                    flag = 1;
	                    break;
	                }
	            if (!flag) {
	                var langLvl = $('.langs-level-tmlp').clone();
	                langLvl.attr('data-id', items[ii]).toggleClass('langs-level-tmlp lang-level');
	                langLvl.hide();
	                var label = langLvl.find('label');
	                var itmID = label.attr('for') + items[ii];
	                label.attr('for', itmID);
	                label.text(label.text() + ''.concat(' (', itemsText[ii], '):'));
	                var select = langLvl.find('select');
	                select.attr('id', itmID);
	                select.attr('name', select.attr('name') + '[' + items[ii] + ']');
	                $("#DiLangsLevels").append(langLvl);
	                langLvl.slideDown(400);
	            }
	        }
	    };
	    PageVacancyEdit.prototype.onEdPaylimChange = function (that) {
	        var self = this;
	        if ($(that).val() == '135')
	            $('#EdPaylimSelf').slideDown(400);
	        else
	            $('#EdPaylimSelf').slideUp(200, function () {
	                $(this).removeClass('show');
	            });
	    };
	    PageVacancyEdit.prototype.onBlurAfterErrorFn = function (that) {
	        var self = this;
	    };
	    PageVacancyEdit.prototype.onF1vacancySubmit = function (e, that) {
	        var self = this;
	        var mess = '';
	        var flagError = 0;
	        e.preventDefault();
	        var item = $("#Mtitle");
	        if (item.length) {
	            var val = item.val();
	            if (val == '') {
	                flagError = 1;
	                mess = 'Необходимо заполнить заголовок вакансии';
	            }
	            else if (val.length > 70) {
	                flagError = 1;
	                mess = 'Длина заголовка не должна превышать 70 символов';
	            }
	        }
	        if (!flagError) {
	            var item = $("#M1requirements");
	            if (item.length) {
	                var val = item.val();
	                if (!flagError && val == '') {
	                    flagError = 1;
	                    mess = 'Необходимо заполнить описание вакансии ';
	                }
	            }
	        }
	        if (!flagError) {
	            var item = $("#M2duties");
	            if (item.length) {
	                var val = item.val();
	                if (!flagError && val == '') {
	                    flagError = 1;
	                    mess = 'Необходимо заполнить обязанности ';
	                }
	            }
	        }
	        if (!flagError) {
	            var item = $("#M3conditions");
	            if (item.length) {
	                var val = item.val();
	                if (val == '') {
	                    flagError = 1;
	                    mess = 'Необходимо заполнить условия ';
	                }
	            }
	        }
	        if (!flagError) {
	            var item = $("#CB1Dolj").next('.ms-parent');
	            if (item.length) {
	                var val = $('#CB1Dolj').val();
	                if (!val || val.length < 1) {
	                    flagError = 1;
	                    mess = 'Необходимо выбрать должность ';
	                }
	            }
	        }
	        if (!flagError) {
	            for (var ii in val) {
	                if (val[ii] == 'aa') {
	                    item = $("#Ed1SelfDolg");
	                    if (item.length) {
	                        val = item.val();
	                        if (item.val().length < 1) {
	                            flagError = 1;
	                            mess = 'Необходимо заполнить свой вариант должности';
	                        }
	                    }
	                }
	            }
	        }
	        if (!flagError) {
	            var item = $("#CB2city").nextUntil('.ms-parent');
	            if (item.length) {
	                var val = $('#CB2city').val();
	                if (!val || val.length < 1) {
	                    flagError = 1;
	                    mess = 'Необходимо выбрать город ';
	                }
	            }
	        }
	        if (!flagError) {
	            var item = $("#CB5busy");
	            var val = $('#CB5busy').val();
	            var flagBoth = val == 0;
	            var item = $("#Hi1DateWS");
	            var val = $('#Hi1DateWS').val();
	            if (val == '') {
	                flagError = 1;
	                mess = 'Необходимо ввести дату начала работы';
	            }
	            if (!flagError && flagBoth) {
	                var item = $("#Hi2DateWE");
	                var val = $('#Hi2DateWE').val();
	                var d2 = moment(val, 'DD.MM.YYYY');
	                if (val == '') {
	                    flagError = 1;
	                    mess = 'Необходимо ввести дату завершения работы';
	                }
	                if (!flagError) {
	                    var item = $("#Hi1DateWS");
	                    var val = $('#Hi1DateWS').val();
	                    var d1 = moment(val, 'DD.MM.YYYY');
	                    if (d1.isAfter(d2)) {
	                        flagError = 1;
	                        mess = 'Дата начала больше даты окончания';
	                    }
	                }
	            }
	        }
	        if (flagError) {
	            $('body').stop().animate({ scrollTop: item.offset().top - 30 + 'px' }, 500, function () {
	                var msgBox = $('.error-hint-box');
	                msgBox.text(mess).css({ left: item.offset().left, top: item.offset().top + item.outerHeight() + 10 });
	                msgBox.fadeIn(400);
	                item.addClass('field--warning');
	                item.focus();
	                if (item.hasClass('ms-parent') || item.hasClass('dropdown-multi')) {
	                    setTimeout(function () {
	                        msgBox.fadeOut(200);
	                        item.removeClass('field--warning');
	                    }, 3000);
	                }
	                else {
	                    item.on('blur', function () {
	                        msgBox.fadeOut(200);
	                        item.removeClass('field--warning');
	                        $(this).off('blur');
	                    });
	                }
	            });
	        }
	        else {
	            $("#F1vacancy").submit();
	        }
	    };
	    PageVacancyEdit.prototype.onCountryChangeFn = function (e, that) {
	        var self = this;
	        self.CBcityMulti.setAjaxParam('idco', $(that).val()).clear();
	        $("#DiMetroesBlock").empty();
	        $("#CB2city").empty().multipleSelect('refresh');
	        var $CBcountry = $("#CBcountry")[0];
	        $("#EdCountry").val($CBcountry.options[$CBcountry.selectedIndex].innerHTML);
	    };
	    PageVacancyEdit.prototype.onAddCityClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        var cityBLock = $(".city-block-tpl").clone();
	        cityBLock.hide().toggleClass('city-block city-block-tpl tmpl');
	        $("#DiCitiBlocks").append(cityBLock);
	        var wrapper = cityBLock.find('.ph-city-info-block-wrapp');
	        var editBlock = $(".city-editblock-tpl").clone();
	        editBlock.toggleClass('city-editblock city-editblock-tpl tmpl');
	        editBlock.find('.ph-id').val('new');
	        editBlock.find('.btn-city-close').remove();
	        (new AutocompleteHelper()).bind(editBlock.find('.ph-city'), {
	            url: MainConfig.AJAX_GET_VE_GET_CITIES,
	            afterItemSelected: function (data) {
	                editBlock.find('.ph-idcity').val(data.data);
	            }
	        });
	        var startDate = new Date();
	        startDate.setDate(startDate.getDate());
	        var params = {
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            autoclose: true,
	            startDate: startDate,
	            language: G_VARS.locale
	        };
	        $(editBlock.find('.ph-bdate, .ph-edate')).datepicker(params);
	        wrapper.find('.ph-city-info-block').html(editBlock);
	        Hinter.bind(wrapper.find('.has-hint'));
	        cityBLock.slideDown(400, function () {
	            cityBLock.find(".ph-city").focus();
	        });
	        self.startEditBlock();
	    };
	    PageVacancyEdit.prototype.onAddPeriodClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        var periodBlock = $that.closest('.period-line-edit');
	        var newPeriodBlock = $(".period-line-edit-tpl").clone();
	        newPeriodBlock.toggleClass('period-line-edit period-line-edit-tpl tmpl');
	        var startDate = new Date();
	        startDate.setDate(startDate.getDate());
	        var params = {
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            autoclose: true,
	            startDate: startDate,
	            language: G_VARS.locale
	        };
	        $(newPeriodBlock.find('.ph-bdate, .ph-edate')).datepicker(params);
	        newPeriodBlock.hide();
	        newPeriodBlock.insertAfter(periodBlock);
	        newPeriodBlock.slideDown(300);
	        self.bindFiltersFn(newPeriodBlock);
	        Hinter.bind(newPeriodBlock.find('.has-hint'));
	    };
	    PageVacancyEdit.prototype.onAutocompleteFn = function (inObj) {
	        var self = this;
	        var cityBlock = inObj.closest('.city-block');
	        var value = inObj.find('.EdCity').getSelectedItemData();
	        G_VARS.App.showLoading(inObj.find('.EdCity'), 1);
	        cityBlock.attr('data-id', value.id);
	        inObj.find('.BtnAddLoc').fadeIn(400);
	        inObj.find('.HiCity').val(value.id);
	        self.setLocationsElmsIds(cityBlock, value.id);
	        inObj.find('.metro-select').slideUp(200);
	        inObj.find('.metro-select select').empty();
	        if (value.ismetro == '1') {
	            self.addMetro({ id: value.id, obj: cityBlock });
	        }
	        G_VARS.App.hideLoading();
	    };
	    PageVacancyEdit.prototype.setLocationsElmsIds = function (inObj, inId) {
	        var self = this;
	        inObj.find('.date-start-block input').attr('name', 'date[start][' + inId + ']');
	        inObj.find('.date-end-block input').attr('name', 'date[end][' + inId + ']');
	        inObj.find('.EdLocName').attr('name', 'location[' + inId + '][name][]');
	        inObj.find('.EdLocAddr').attr('name', 'location[' + inId + '][addr][]');
	        inObj.find(".day-from, .day-to").each(function () {
	            var $that = $(this);
	            $that.attr('name', 'week-day[' + inId + '][' + $that.data('name') + '][' + ($that.hasClass('day-from') ? 'f' : 't') + '][]');
	        });
	    };
	    PageVacancyEdit.prototype.onAddLocationClick = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        self.startEditBlock();
	        var wrapper = $that.closest('.city-block');
	        var locationsPlace = wrapper.find(".locations-block");
	        var blockWrapper = $(".location-wrapper-tpl").clone();
	        blockWrapper.toggleClass('location-wrapper new-location location-wrapper-tpl tmpl');
	        var editBlock = $(".location-edit-tpl").clone();
	        editBlock.toggleClass('location-edit new-location location-edit-tpl tmpl');
	        editBlock.find('.ph-idloc').val('new');
	        editBlock.find('.ph-idcity').val(wrapper.data('id'));
	        editBlock.find('.btn-close').remove();
	        var idcity = wrapper.data('idcity');
	        var metro = G_VARS.appcache['metro' + idcity];
	        if (metro)
	            self.fillMetroBlock({ obj: editBlock, data: metro });
	        else
	            editBlock.find('.ph-metro-block').parent().hide();
	        blockWrapper.find('.ph-info-block').html(editBlock);
	        blockWrapper.hide();
	        locationsPlace.append(blockWrapper);
	        blockWrapper.slideDown(400, function () { return editBlock.find('.ph-locname').focus(); });
	        var period = $(".period-line-edit-tpl").clone();
	        period.toggleClass('period-line-edit period-line-edit-tpl tmpl');
	        editBlock.find('.ph-periods').append(period);
	        var startDate = new Date();
	        startDate.setDate(startDate.getDate());
	        var params = {
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            autoclose: true,
	            startDate: startDate,
	            language: G_VARS.locale
	        };
	        editBlock.find('.ph-bdate, .ph-edate').datepicker(params);
	        Hinter.bind(editBlock.find('.has-hint'));
	        self.bindFiltersFn(editBlock);
	        blockWrapper.find('.block-form-bind').submit(function (e) {
	            if (self.onFormSubmit(e, this, { justReturn: true }))
	                self.onLocationSaveClickFn(e, this);
	        });
	    };
	    PageVacancyEdit.prototype.onCloseLocationClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        $that.closest('.location').slideUp(200, function () {
	            $(this).remove();
	        });
	    };
	    PageVacancyEdit.prototype.onCityDelClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.ph-city-info-block-wrapp');
	        var cityBlock = $that.closest('.city-block');
	        e.preventDefault();
	        if (!confirm(_t('delconfirm')))
	            return;
	        var id = $that.closest('.city-block').data('id');
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        $.get(MainConfig.AJAX_POST_VE_CITY_DELETE_BLOCK, { id: id }, function (data) {
	            data && (data = JSON.parse(data));
	            var error = 0;
	            if (data) {
	                if (data.error < 0) {
	                    console.info('E', data.error);
	                    wrapper.find('.ph-message').text(data.message);
	                    wrapper.find('.blind').fadeOut(200);
	                }
	                else if (data.error == 100) {
	                    cityBlock.slideUp(200, function () {
	                        this.remove();
	                    });
	                    self.endEditBlock();
	                }
	                else {
	                    error = 1;
	                }
	            }
	            else {
	                error = 1;
	            }
	            if (error) {
	                wrapper.find('.error-del-block').css({ display: 'flex' });
	                setTimeout(function () { wrapper.find('.blind').css({ opacity: '.7' }); }, 500);
	            }
	        })
	            .fail(function () {
	            wrapper.find('.error-del-block').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.onLocationDelClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.location-wrapper');
	        e.preventDefault();
	        if (!confirm(_t('delconfirm')))
	            return;
	        var id = wrapper.data('id');
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        $.get(MainConfig.AJAX_POST_VE_LOCATION_DELETE, { id: id }, function (data) {
	            var error = 0;
	            try {
	                data && (data = JSON.parse(data));
	                if (data.error < 0) {
	                    console.info('E', data.error);
	                    wrapper.find('.ph-message').text(data.message);
	                    wrapper.find('.blind').fadeOut(200);
	                }
	                else if (data.error == 100) {
	                    wrapper.slideUp(200, function () {
	                        this.remove();
	                    });
	                    self.endEditBlock();
	                }
	                else {
	                    error = 1;
	                }
	            }
	            catch (e) {
	                error = 1;
	            }
	            if (error) {
	                wrapper.find('.error-del-bind').css({ display: 'flex' });
	                setTimeout(function () { wrapper.find('.blind').css({ opacity: '.7' }); }, 500);
	            }
	        })
	            .fail(function () {
	            wrapper.find('.error-del-bind').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.onEditCityInfoClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.ph-city-info-block-wrapp');
	        e.preventDefault();
	        self.startEditBlock(wrapper);
	        var id = $that.parent().data('id');
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        $.get(MainConfig.AJAX_GET_VE_CITY_BLOCK_DATA, { id: id }, function (data) {
	            data = JSON.parse(data);
	            var editBlock = $(".city-editblock-tpl").clone();
	            editBlock.toggleClass('city-editblock city-editblock-tpl tmpl');
	            editBlock.find('.ph-id').val(data.id);
	            editBlock.find('.ph-idcity').val(data.idcity);
	            editBlock.find('.ph-city').val(data.name);
	            editBlock.find('.ph-bdate').val(data.bdate);
	            editBlock.find('.ph-edate').val(data.edate);
	            if (data.idcity < 1)
	                editBlock.find('.ph-city').parent().find('.js-bind').text("(" + editBlock.find('.ph-city').data('man') + ")");
	            (new AutocompleteHelper()).bind(editBlock.find('.ph-city'), {
	                url: MainConfig.AJAX_GET_VE_GET_CITIES,
	                afterItemSelected: function (data) {
	                    var city = editBlock.find('.ph-city');
	                    if (data.data == 'man') {
	                        city.parent().find('.js-bind').text("(" + city.data('man') + ")");
	                    }
	                    else
	                        city.parent().find('.js-bind').text(' ');
	                    editBlock.find('.ph-idcity').val(data.data);
	                }
	            });
	            var startDate = new Date();
	            startDate.setDate(startDate.getDate());
	            var params = {
	                format: "dd.mm.yyyy",
	                todayBtn: "linked",
	                calendarWeeks: true,
	                autoclose: true,
	                startDate: startDate,
	                language: G_VARS.locale
	            };
	            $(editBlock.find('.ph-bdate, .ph-edate')).datepicker(params);
	            wrapper.find('.ph-city-info-block').html(editBlock);
	            wrapper.find('.blind').fadeOut(200);
	            Hinter.bind(wrapper.find('.has-hint'));
	        })
	            .fail(function () {
	            wrapper.find('.error-load-form').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.onEditLocationClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.location-wrapper');
	        e.preventDefault();
	        self.startEditBlock(wrapper);
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        var params = {};
	        params.id = wrapper.data('id');
	        params.idcity = wrapper.data('idcity');
	        if (G_VARS.appcache['metro' + params.idcity])
	            params.idcity = '';
	        $.get(MainConfig.AJAX_GET_VE_LOCATION_DATA, params, function (data) {
	            var error = 0;
	            try {
	                data && (data = JSON.parse(data));
	                if (data.loc) {
	                    var location_1 = data.loc;
	                    var editBlock = $(".location-edit-tpl").clone();
	                    editBlock.toggleClass('location-edit location-edit-tpl tmpl');
	                    editBlock.find('.ph-locname').val(location_1.name);
	                    editBlock.find('.ph-idloc').val(location_1.id);
	                    editBlock.find('.ph-locaddr').val(location_1.addr);
	                    var metro = G_VARS.appcache['metro' + location_1.idcity] ? G_VARS.appcache['metro' + location_1.idcity] : data.metro;
	                    if (metro)
	                        self.fillMetroBlock({ obj: editBlock, data: metro, mid: location_1.mid });
	                    for (var ii in location_1.loctimes) {
	                        var val = location_1.loctimes[ii];
	                        var period = $(".period-line-edit-tpl").clone();
	                        period.toggleClass('period-line-edit period-line-edit-tpl tmpl');
	                        period.find('.ph-bdate').val(val[0]);
	                        period.find('.ph-edate').val(val[1]);
	                        period.find('.ph-btime').val(val[2]);
	                        period.find('.ph-etime').val(val[3]);
	                        editBlock.find('.ph-periods').append(period);
	                        var startDate = new Date();
	                        startDate.setDate(startDate.getDate());
	                        var params = {
	                            format: "dd.mm.yyyy",
	                            todayBtn: "linked",
	                            calendarWeeks: true,
	                            autoclose: true,
	                            startDate: startDate,
	                            language: G_VARS.locale
	                        };
	                        editBlock.find('.ph-bdate, .ph-edate').datepicker(params);
	                        Hinter.bind(editBlock.find('.has-hint'));
	                    }
	                    wrapper.find('.ph-info-block').html(editBlock);
	                    wrapper.find('.blind').fadeOut(200);
	                    self.bindFiltersFn(wrapper);
	                    wrapper.find('.block-form-bind').submit(function (e) {
	                        if (self.onFormSubmit(e, this, { justReturn: true }))
	                            self.onLocationSaveClickFn(e, this);
	                    });
	                }
	                else {
	                    error = 1;
	                }
	            }
	            catch (e) {
	                error = 1;
	            }
	            if (error) {
	                wrapper.find('.error-edit-bind').css({ display: 'flex' });
	                setTimeout(function () {
	                    wrapper.find('.blind').css({ opacity: '.7' });
	                }, 500);
	            }
	        })
	            .fail(function () {
	            wrapper.find('.error-edit-bind').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.onCityBlockSaveClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.ph-city-info-block-wrapp');
	        var cityBlock = $that.closest('.city-block');
	        var idcity = wrapper.find('.ph-idcity').val();
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        var metro = !G_VARS.appcache['metro' + idcity] ? '&metro=1' : '';
	        var formData = wrapper.find('.block-form').serialize() + metro;
	        $.post(MainConfig.AJAX_POST_CITY_DATA, formData, function (data) {
	            data && (data = JSON.parse(data));
	            var error = 0;
	            if (data) {
	                if (data.error < 0) {
	                    console.info('E', data.error);
	                    wrapper.find('.ph-message').text(data.message);
	                    wrapper.find('.blind').fadeOut(200);
	                }
	                else if (data.error == 100) {
	                    if (cityBlock.hasClass('new-block-bind')) {
	                        cityBlock.attr('data-id', data.id);
	                        cityBlock.attr('data-idcity', idcity);
	                    }
	                    if (data.data && data.data.metro)
	                        G_VARS.appcache['metro' + idcity] = data.data.metro;
	                    self.cityDataRefresh(that, { message: data.message, class: '-green' });
	                }
	                else {
	                    error = 1;
	                }
	            }
	            else {
	                error = 1;
	            }
	            if (error) {
	                if (cityBlock.hasClass('new-block-bind'))
	                    wrapper.find('.error-save-new').css({ display: 'flex' });
	                else
	                    wrapper.find('.error-save-form').css({ display: 'flex' });
	                setTimeout(function () {
	                    wrapper.find('.blind').css({ opacity: '.7' });
	                }, 500);
	            }
	        })
	            .fail(function () {
	            wrapper.find('.error-load-form').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.onLocationSaveClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var wrapper = $that.closest('.location-wrapper');
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        var formData = wrapper.find('.block-form-bind').serialize();
	        $.post(MainConfig.AJAX_POST_LOCATION_DATA, formData, function (data) {
	            var error = 0;
	            try {
	                data && (data = JSON.parse(data));
	                if (data.error < 0) {
	                    console.info('E', data.error);
	                    wrapper.find('.ph-message').text(data.message);
	                    wrapper.find('.blind').fadeOut(200);
	                }
	                else if (data.error == 100) {
	                    if (wrapper.hasClass('new-location'))
	                        wrapper.attr('data-id', data.id);
	                    self.locationDataRefresh(that, { message: data.message, class: '-green' });
	                }
	                else {
	                    error = 1;
	                }
	            }
	            catch (e) {
	                error = 1;
	            }
	            if (error) {
	                if (wrapper.hasClass('new-location'))
	                    wrapper.find('.error-save-new-bind').css({ display: 'flex' });
	                else
	                    wrapper.find('.error-save-bind').css({ display: 'flex' });
	                setTimeout(function () { wrapper.find('.blind').css({ opacity: '.7' }); }, 500);
	            }
	        })
	            .fail(function () {
	            if (wrapper.hasClass('new-location'))
	                wrapper.find('.error-save-new-bind').css({ display: 'flex' });
	            else
	                wrapper.find('.error-save-bind').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.onCityBlockCancelClickFn = function (e, that) {
	        var self = this;
	        self.cityDataRefresh(that);
	    };
	    PageVacancyEdit.prototype.onNewCityBlockCancelClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        $that.closest('.city-block').slideUp(200, function () {
	            this.remove();
	            self.endEditBlock();
	        });
	    };
	    PageVacancyEdit.prototype.onNewLocCancelClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        $that.closest('.location-wrapper').slideUp(200, function () {
	            this.remove();
	            self.endEditBlock();
	        });
	    };
	    PageVacancyEdit.prototype.onEditLocCancelClickFn = function (e, that) {
	        var self = this;
	        self.locationDataRefresh(that);
	    };
	    PageVacancyEdit.prototype.onCityDataRefreshClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        self.cityDataRefresh(that);
	    };
	    PageVacancyEdit.prototype.onLocDataRefreshClickFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        e.preventDefault();
	        self.locationDataRefresh(that);
	    };
	    PageVacancyEdit.prototype.cityDataRefresh = function (inObj, props) {
	        var self = this;
	        var $that = $(inObj);
	        var id = $that.closest('.city-block').data('id');
	        var wrapper = $that.closest('.ph-city-info-block-wrapp');
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        $.get(MainConfig.AJAX_GET_VE_CITY_BLOCK_DATA, { id: id }, function (data) {
	            data = JSON.parse(data);
	            var infoBlock = $(".city-info-block-tpl").clone();
	            infoBlock.toggleClass('city-info-block-tpl tmpl');
	            infoBlock.find('.ph-editbtn').toggleClass('tmpl').attr('data-id', data.id);
	            infoBlock.find('.ph-editbtn a').attr('title', function (i, val) {
	                return val.replace('#PH_CITY_NAME#', data.name);
	            });
	            infoBlock.find('.ph-city').text(data.name);
	            infoBlock.find('.ph-bdate').text(data.bdate);
	            infoBlock.find('.ph-edate').text(data.edate);
	            if (props && props.message)
	                infoBlock.find('.ph-message').addClass(props.class).text(props.message).slideDown(400);
	            wrapper.find('.ph-city-info-block').html(infoBlock);
	            Hinter.bind(infoBlock.find('.ph-editbtn a'));
	            wrapper.find('.error-message').css({ display: 'none' });
	            self.endEditBlock();
	            wrapper.find('.blind').fadeOut(200);
	        })
	            .fail(function () {
	            wrapper.find('.error-message').css({ display: 'none' });
	            wrapper.find('.error-data-refresh').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.locationDataRefresh = function (inObj, props) {
	        var self = this;
	        var $that = $(inObj);
	        var wrapper = $that.closest('.location-wrapper');
	        var id = wrapper.data('id');
	        G_VARS.App.showLoading2(wrapper, { pic: 1, withBg: true });
	        wrapper.find('.blind').fadeIn(400);
	        $.get(MainConfig.AJAX_GET_VE_LOCATION_DATA, { id: id }, function (data) {
	            try {
	                data = JSON.parse(data);
	                data = data.loc;
	                var infoBlock = $(".location-view-tpl").clone();
	                infoBlock.toggleClass('location-view location-view-tpl tmpl');
	                infoBlock.find('.ph-editbtn').toggleClass('tmpl');
	                infoBlock.find('.ph-editbtn a').attr('title', function (i, val) {
	                    return val.replace('#PH_LOCNAME_NAME#', data.name);
	                });
	                infoBlock.find('.ph-locname').text(data.name);
	                infoBlock.find('.ph-locaddr').text(data.addr);
	                if (data.mname)
	                    infoBlock.find('.ph-metro').text(data.mname);
	                else
	                    infoBlock.find('.ph-metro').closest('.ph-mblock').hide();
	                infoBlock.find('.periods-wrapp-bind').hide();
	                for (var ii in data.loctimes) {
	                    var val = data.loctimes[ii];
	                    if (val[0]) {
	                        var period = $(".period-line-tpl").clone();
	                        period.toggleClass('period-line period-line-tpl tmpl');
	                        period.find('.ph-bdate').text(val[0]);
	                        period.find('.ph-edate').text(val[1]);
	                        period.find('.ph-btime').text(val[2]);
	                        period.find('.ph-etime').text(val[3]);
	                        infoBlock.find('.ph-periods').append(period);
	                        infoBlock.find('.periods-wrapp-bind').show();
	                    }
	                }
	                if (props && props.message)
	                    infoBlock.find('.ph-message').addClass(props.class).text(props.message).slideDown(400);
	                wrapper.find('.ph-info-block').html(infoBlock);
	                Hinter.bind(infoBlock.find('.ph-editbtn a'));
	                wrapper.find('.error-message').css({ display: 'none' });
	                self.endEditBlock();
	                wrapper.find('.blind').fadeOut(200);
	            }
	            catch (e) {
	                wrapper.find('.error-message').css({ display: 'none' });
	                wrapper.find('.error-refresh-bind').css({ display: 'flex' });
	                wrapper.find('.blind').css({ opacity: '.7' });
	            }
	        })
	            .fail(function () {
	            wrapper.find('.error-message').css({ display: 'none' });
	            wrapper.find('.error-refresh-bind').css({ display: 'flex' });
	            wrapper.find('.blind').css({ opacity: '.7' });
	        })
	            .always(function () {
	            G_VARS.App.hideLoading();
	        });
	    };
	    PageVacancyEdit.prototype.startEditBlock = function (inWrapper) {
	        var self = this;
	        $(".err-msg-block").slideUp(200);
	        inWrapper && inWrapper.find('.error-message').css({ display: 'none' });
	        self.isEditMode = true;
	        $(".control-btn").css({ opacity: 0 });
	    };
	    PageVacancyEdit.prototype.endEditBlock = function () {
	        var self = this;
	        self.isEditMode = false;
	        $(".control-btn").css({ opacity: '' });
	    };
	    PageVacancyEdit.prototype.onDelPeriodClickFn = function (e, that) {
	        var self = this;
	        e.preventDefault();
	        var periodBlock = $(that).closest('.period-line-edit');
	        periodBlock.slideUp(200, function () {
	            this.remove();
	        });
	    };
	    PageVacancyEdit.prototype.onSubmitSalaryCheck = function (props) {
	        var self = this;
	        var flag = true;
	        var inputs = $(".js-salary");
	        for (var ii = 0, countii = inputs.length; ii < countii; ii++) {
	            var val = inputs[ii];
	            if (val.value != '') {
	                flag = false;
	                return false;
	            }
	        }
	        return flag;
	    };
	    PageVacancyEdit.prototype.onSubmitSexCheck = function (props) {
	        var self = this;
	        return !$("#Chk1Mans").parent().hasClass('checked') && !$("#Chk2Womens").parent().hasClass('checked');
	    };
	    return PageVacancyEdit;
	}(Page));
	exports.PageVacancyEdit = PageVacancyEdit;


/***/ }

/******/ });