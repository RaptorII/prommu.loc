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
	        this.doljChoice = '137';
	        this.postSecretCustomer = '12';
	        this.cityMoskowChoice = 1001;
	        this.cityPiterChoice = 1002;
	        this.cityCounter = 1;
	        this.cropOpts = {};
	        this.cropper = null;
	        var self = this;
	        $("#F1profdata").submit(function (e) { self.onFormSubmit(e, this); });
	        G_VARS.App.country = $("#CB1country").val();
	        $("#CB1country").change(function (e) { G_VARS.App.country = $(this).val(); });
	        $("#CB1country").change(function (e) { self.onBtnAddCityClick(e, this); });
	        $("#EdCountry").hover(function () {
	            G_VARS.App.showHint($(this), 'Созданы блоки городов - смена страны заблокирована, для смены страны удалите все блоки с городами');
	        }, function () { G_VARS.App.closeHint(); });
	        self.bindFiltersFn();
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
	    
	        var yesterday = new Date();
	        yesterday.setDate(yesterday.getDate() - 1);
	        $('#EdDateBirth').datepicker({
	            format: "dd.mm.yyyy",
	            todayBtn: "linked",
	            calendarWeeks: true,
	            endDate: yesterday,
	            language: "ru"
	        });
	        $('#CBdolj').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, {
	            placeholder: "выберите должность...",
	            selectAll: false,
	            onUncheckAll: function (p1) { self.onCBDoljChangeFn(p1); },
	            onClick: function (p1) { self.onCBDoljChangeFn(p1); },
	            onCheckAll: function (p1) { self.onCBDoljChangeFn(p1, 1); },
	        }));
	        $('#CBcityWork').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, {
	            placeholder: "выберите город...",
	            onUncheckAll: function (p1) { self.onCBcityWorkChangeFn(p1); },
	            onClick: function (p1) { self.onCBcityWorkChangeFn(p1); },
	            onCheckAll: function (p1) { self.onCBcityWorkChangeFn(p1, 1); },
	        }));
	        $('#CBdoljExp').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, {
	            selectAll: false,
	            placeholder: "выберите должность...",
	            onUncheckAll: function (p1) { self.onCBdoljExpChangeFn(p1); },
	            onClick: function (p1) { self.onCBdoljExpChangeFn(p1); },
	            onCheckAll: function (p1) { self.onCBdoljExpChangeFn(p1); },
	        }));

	        $('#CBlangs').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, {
	            placeholder: "выберите языки...",
	            selectAll: false,
	            onUncheckAll: function (p1) { self.onCBlangsChangeFn(p1); },
	            onCheckAll: function (p1) { self.onCBlangsChangeFn(p1, 1); },
	            onClick: function (p1) { self.onCBlangsChangeFn(p1); },
	        }));
	        if (G_VARS.initPaymentsData)
	            self.onCBDoljChangeFn(0, 0, G_VARS.initPaymentsData);
	        self.initCities(G_VARS.userCities[0]);
	        self.onCBlangsChangeFn(0, 0, { langs: G_VARS.langsSeled });
	        self.onCBdoljExpChangeFn({});
	    };
	    PageRegApplic.prototype.initCities = function (inData) {
	        var self = this;
	        for (var ii in inData) {
	            var block = self.onBtnAddCityClick(0, 0, inData[ii]);
	            if (inData[ii].ismetro == '1') {
	                block.find('.EdCity').attr('data-name', inData[ii].name);
	                G_VARS.App.applyMetroHTML(block.find('.EdCity')[0], ii);
	            }
	        }
	    };
	    PageRegApplic.prototype.onBtnAddCityClick = function (e, that, inInitData) {
	        var self = this;
	        var blockCity = $("#DiCityTmplate").clone();
	        e && e.preventDefault();
	        var cityName = inInitData ? inInitData.name : '';
	        var street = inInitData ? inInitData.street : '';
	        var addinfo = inInitData ? inInitData.addinfo : '';
	        blockCity.attr('id', '');
	        blockCity.find('.EdCity').toggleClass('nocheck').attr('name', 'cities[' + self.cityCounter + ']').val(cityName);
	        blockCity.find('.EdStreet').attr('name', 'street[' + self.cityCounter + ']').val(street);
	        blockCity.find('.EdCustomPlaceWork').attr('name', 'custom-place-work[' + self.cityCounter + ']').val(addinfo);
	        blockCity.find('.metro-select select').multipleSelect({
	            selectAll: false,
	            countSelected: '# из %',
	            allSelected: 'Выбраны все',
	            placeholder: "выберите метро...",
	            filter: true
	        });
	        var days = { mon: 'Понедельник',
	            tue: 'Вторник',
	            wed: 'Среда',
	            thu: 'Четверг',
	            fri: 'Пятница',
	            tha: 'Суббота',
	            sun: 'Воскресенье'
	        };
	        var jj = 1;
	        for (var ii in days) {
	            var btime = '';
	            var etime = '';
	            if (inInitData && G_VARS.userWdays && G_VARS.userWdays[inInitData.id] && G_VARS.userWdays[inInitData.id][jj]) {
	                btime = G_VARS.userWdays[inInitData.id][jj].timeb;
	                etime = G_VARS.userWdays[inInitData.id][jj].timee;
	            }
	            var dayItm = blockCity.find('.day-tpl').clone();
	            dayItm.toggleClass('day-tpl day');
	            dayItm.find('.day-title').text(days[ii]);
	            dayItm.find('.day-chk').attr('name', 'week-day['.concat(self.cityCounter, '][', ii, '][ch]'));
	            dayItm.find('.day-from').attr('name', 'week-day['.concat(self.cityCounter, '][', ii, '][f]')).val(btime);
	            dayItm.find('.day-to').attr('name', 'week-day['.concat(self.cityCounter, '][', ii, '][t]')).val(etime);
	            G_VARS.App.applyCheckbox(dayItm.find('.checkbox-box input[type=checkbox]'));
	            blockCity.find('.days-block').append(dayItm);
	            jj++;
	        }
	        blockCity.hide();
	        // $("#DiCitiBlocks").prepend(blockCity);
	        // blockCity.slideDown(400, function () { return that && blockCity.find('.EdCity').focus(); });
	        // self.FormFilter.bindFiltersFn(blockCity);
	        // G_VARS.App.applyCityBox();
	        // var $CB1country = $("#CB1country")[0];
	        // $("#EdCountry").val($CB1country.options[$CB1country.selectedIndex].innerHTML);
	        // $("#CB1country").slideUp(200);
	        // $("#EdCountry").slideDown(200);
	        // $("#HiAddedCity").val('1');
	        // self.cityCounter++;
	        // blockCity.hide();
	        // return blockCity;
	    };
	    PageRegApplic.prototype.onCB2cityChangeFn = function (props, all) {
	        var self = this;
	        var vals = $('#CB2city').multipleSelect('getSelects');
	        var valsText = $('#CB2city').multipleSelect('getSelects', 'text');
	        if ($.inArray('aa', vals) > -1) {
	            $("#CityManualMultiBLock").slideDown(400).find('input').removeClass('nocheck');
	        }
	        else {
	            $("#CityManualMultiBLock").slideUp(200).find('input').addClass('nocheck');
	        }
	        var metro = G_VARS.appcache.metro;
	        var promises = $.map(vals, function (id) {
	            if (G_VARS.cityWmetro[id]) {
	                if (metro && metro['city' + id]) {
	                }
	                else {
	                    return $.get(MainConfig.AJAX_GET_METRO, { idcity: id })
	                        .then(function (data) {
	                        data = JSON.parse(data);
	                        G_VARS.appcache.metro['city' + id] = data;
	                    });
	                }
	            }
	            else {
	            }
	        });
	        $.when.apply(this, promises).then(function () {
	            self.renderMetroesHtml(vals, valsText);
	        });
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
	    PageRegApplic.prototype.onCBDoljChangeFn = function (props, all, initData) {
	        var self = this;
	        var flagLoadInitData = initData && initData.length > 0;
	        var CBdolj = $('#CBdolj');
	        var items = CBdolj.multipleSelect('getSelects');
	        var itemsText = CBdolj.multipleSelect('getSelects', 'text');
	        if (items.length == CBdolj.children('option').length)
	            $("#CBdoljData").text('');
	        var ids = [];
	        $('.price-level').each(function () {
	            var ID = parseInt($(this).attr('data-id'));
	            ids.push(ID);
	            var flag = 0;
	            for (var ii in items)
	                if (items[ii] == ID) {
	                    flag = 1;
	                    break;
	                }
	            if (!flag)
	                $(this).slideUp(200, function () { $(this).remove(); });
	        });
	        var flagCustomPost = 0;
	        items = flagLoadInitData ? initData : items;
	        var _loop_1 = function() {
	            itemID = flagLoadInitData ? items[ii].idpost : items[ii];
	            if (false) {
	                flagCustomPost = 1;
	            }
	            else {
	                flag = 0;
	                for (jj in ids)
	                    if (ids[jj] == itemID) {
	                        flag = 1;
	                        break;
	                    }
	                if (flagLoadInitData)
	                    flag = 0;
	                if (!flag) {
	                    payLvl = $($('#TPL1Payment').text());
	                    payLvl.attr('data-id', itemID);
	                    payLvl.hide();
	                    label = payLvl.find('label');
	                    var name_1 = flagLoadInitData ? items[ii].val : itemsText[ii];
	                    label.children('b').text(function (i, text) { return ''.concat(name_1, ' ', text); });
	                    edName = payLvl.find('.js-name');
	                    edName.attr('name', "post[" + itemID + "][" + edName.attr('name') + "]");
	                    if (itemID != self.doljChoice) {
	                        edName.parent().hide();
	                    }
	                    edPay = payLvl.find('.js-payment');
	                    edPay.attr('name', "post[" + itemID + "][" + edPay.attr('name') + "]");
	                    select = payLvl.find('select');
	                    select.attr('name', "post[" + itemID + "][" + select.attr('name') + "]");
	                    $("#DiPayment").append(payLvl);
	                    payLvl.slideDown(400);
	                    if (flagLoadInitData) {
	                        edPay.val(items[ii].pay);
	                        edName.val(items[ii].mech);
	                        $.each(select.children(), function () {
	                            var $that = $(this);
	                            if ($that.attr('value') == items[ii].pt)
	                                $that.prop('selected', true);
	                        });
	                    }
	                }
	            }
	        };
	        var itemID, flag, jj, payLvl, label, edName, edPay, select;
	        for (var ii in items) {
	            _loop_1();
	        }
	        if (flagCustomPost)
	            $(".custom-dolj").slideDown(400);
	        else
	            $(".custom-dolj").slideUp(200);
	    };
	    PageRegApplic.prototype.onCBdoljExpChangeFn = function (props, initData) {
	        var self = this;
	        var items = $('#CBdoljExp').multipleSelect('getSelects');
	        var itemsText = $('#CBdoljExp').multipleSelect('getSelects', 'text');
	        if (true) {
	            if (initData) {
	                var CBlangs = $('#CBlangs');
	                var ids = [];
	                for (var ii in initData.langs) {
	                    ids.push(ii);
	                }
	                CBlangs.multipleSelect('setSelects', ids);
	            }
	            var ids = [];
	            $('.exp-lvl').each(function () {
	                var ID = parseInt($(this).attr('data-id'));
	                ids.push(ID);
	                var flag = 0;
	                for (var ii in items)
	                    if (items[ii] == ID) {
	                        flag = 1;
	                        break;
	                    }
	                if (!flag)
	                    $(this).slideUp(200, function () { $(this).remove(); });
	            });
	            for (var ii in items) {
	                var flag = 0;
	                for (var jj in ids)
	                    if (ids[jj] == items[ii]) {
	                        flag = 1;
	                        break;
	                    }
	                if (!flag) {
	                    var posts = G_VARS.postsSelected;
	                    var expLvl = $($('#TPL2Exp').text());
	                    expLvl.attr('data-id', items[ii]);
	                    expLvl.hide();
	                    expLvl.find('b').text(function (i, v) { return v + ' (' + itemsText[ii] + '):'; });
	                    var edName = expLvl.find('.name input');
	                    edName.attr('name', 'exp[' + items[ii] + '][name]');
	                    items[ii] == self.doljChoice && posts.forEach(function (val) { if (items[ii] == val.idpost)
	                        edName.val(val.mech); });
	                    if (items[ii] != self.doljChoice) {
	                        edName.parent().hide();
	                    }
	                    var select = expLvl.find('select');
	                    select.attr('name', 'exp[' + items[ii] + '][level]');
	                    select.find('option').each(function (v) {
	                        var $that = $(this);
	                        for (var kk in posts) {
	                            var selected = posts[kk]['idpost'] == items[ii] && $that.attr('value') == posts[kk]['id_attr'];
	                            if (selected)
	                                $that.prop('selected', true);
	                        }
	                    });
	                    $("#DiExpLevels").append(expLvl);
	                    expLvl.slideDown(400);
	                }
	            }
	        }
	    };
	    PageRegApplic.prototype.onCBcityWorkChangeFn = function (props, all) {
	        var self = this;
	        var items = $('#CBcityWork').multipleSelect('getSelects');
	        var itemsText = $('#CBcityWork').multipleSelect('getSelects', 'text');
	        var flagM = 0;
	        var flagP = 0;
	        for (var ii in items) {
	            if (self.cityMoskowChoice == items[ii]) {
	                flagM = 1;
	            }
	            else if (self.cityPiterChoice == items[ii]) {
	                flagP = 1;
	            }
	        }
	        if (flagM) {
	            $("#LMetroMosk").slideDown(400);
	        }
	        else {
	            $("#LMetroMosk").slideUp(200);
	        }
	        if (flagP) {
	            $("#LMetroPiter").slideDown(400);
	        }
	        else {
	            $("#LMetroPiter").slideUp(200);
	        }
	    };
	    PageRegApplic.prototype.onCBlangsChangeFn = function (props, all, initData) {
	        var self = this;
	        var CBlangs = $('#CBlangs');
	        if (initData) {
	            var ids = [];
	            for (var ii in initData.langs) {
	                ids.push(ii);
	            }
	            CBlangs.multipleSelect('setSelects', ids);
	        }
	        var items = CBlangs.multipleSelect('getSelects');
	        var itemsText = CBlangs.multipleSelect('getSelects', 'text');
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
	                $(this).slideUp(200, function () { $(this).remove(); });
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
	                label.find('b').text(label.find('b').text() + ' (' + itemsText[ii] + '):');
	                var select = langLvl.find('select');
	                select.attr('id', itmID);
	                select.attr('name', select.attr('name') + '[' + items[ii] + ']');
	                var lvls = G_VARS.langsLvls;
	                for (var kk in lvls) {
	                    var selected = initData && initData.langs[items[ii]] == lvls[kk][0] ? 'selected' : '';
	                    $("<option value='".concat(lvls[kk][0], "' ", selected, ">", lvls[kk][1], "</option>"))
	                        .appendTo(select);
	                }
	                $("#DiLangsLevels").append(langLvl);
	                langLvl.slideDown(400);
	            }
	        }
	    };
	    PageRegApplic.prototype.debug = function () {
	        $("#EdName").val('test');
	        $("#EdSurname").val('test');
	        $("#EdDateBirth").val('test');
	        $("#CB2city").val('1');
	        $("#EdEmail").val('aazz@aa.com');
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
	    PageRegApplic.prototype.onSendCroppedFn = function (e, that) {
	        var self = this;
	        var $that = $(that);
	        var form = $that.closest('form');
	        e.preventDefault();
	        form.find('.loading-ico').fadeIn(400);
	        $.post(MainConfig.AJAX_POST_CROPLOGO, self.cropOpts, function (data) {
	            data = JSON.parse(data);
	            $("#HiLogo").val(data.idfile);
	            
	        }).always(function () {
				form.find('.loading-ico').fadeOut(200);
				self.cropper.destroy();
				ModalWindow.close();
	        });
	    };
	    return PageRegApplic;
	}(Page));
	exports.PageRegApplic = PageRegApplic;


/***/ }
/******/ ]);