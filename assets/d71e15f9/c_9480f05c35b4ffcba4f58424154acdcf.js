var Strings = (function () {
    function Strings() {
        this.fb = {
            loginFail: 'Сбой авторизации на сайте Facebook, обновите страницу и попробуйте еще раз',
            fail2: 'Cannot connect',
        };
        this.delconfirm = 'Подтвердите удаление';
        this.fileLoad = 'Не удалось загрузить файл, обновите страницу и попробуйте еще раз';
        this.fileDeleteError = 'Ошибка удаления файла, обновите страницу и попробуйте еще раз';
        this.inviteError = 'Не удалось отправить приглашение пользователю, обновите страницу или попробуйте эту операцию через некоторое время';
    }
    return Strings;
}());
var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
var MainConfig = (function () {
    function MainConfig() {
    }
    MainConfig.SITE = "";
    MainConfig.AJAX_GET_VE_GET_CITIES = "/ajaxvacedit/vegetcities/";
    MainConfig.AJAX_GET_VE_CITY_BLOCK_DATA = '/ajaxvacedit/getvecityblockdata/';
    MainConfig.AJAX_GET_VE_LOCATION_DATA = '/ajaxvacedit/getvelocationdata/';
    MainConfig.AJAX_GET_CITYES = '/ajax/getcities/';
    MainConfig.AJAX_GET_METRO = '/ajax/getmetro/';
    MainConfig.AJAX_GET_GETEMPLCONTACTS = '/ajax/getemplcontacts';
    MainConfig.AJAX_GET_GETEMPLRATE = '/ajax/getemplrate';
    MainConfig.AJAX_GET_GETUSERMESAGES = '/ajax/getusermesages';
    MainConfig.AJAX_GET_GETNEWMESAGES = '/ajax/getnewmesages';
    MainConfig.AJAX_GET_GETSERVICE = '/ajax/getservice';
    MainConfig.AJAX_GET_GETUSERNEWMESSAGES = '/ajnotify/getusernewmessages';
    MainConfig.AJAX_GET_GETUSERNEWCOMMENTS = '/ajnotify/getusernewcomments';
    MainConfig.AJAX_GET_GETVACANCIES = '/ajresponse/getvacancies';
    MainConfig.AJAX_POST_VE_CITY_DELETE_BLOCK = '/ajaxvacedit/delvecityblock/';
    MainConfig.AJAX_POST_VE_LOCATION_DELETE = '/ajaxvacedit/delvelocation/';
    MainConfig.AJAX_POST_CITY_DATA = "/ajaxvacedit/citydatasave/";
    MainConfig.AJAX_POST_LOCATION_DATA = "/ajaxvacedit/locationdatasave/";
    MainConfig.AJAX_POST_GETVACS = '/ajax/getvacs/';
    MainConfig.AJAX_POST_GETEMPLS = '/ajax/getempls/';
    MainConfig.AJAX_POST_GETAPPLIC = '/ajax/getapplic/';
    MainConfig.AJAX_POST_GETSEARCHVACS = '/ajax/getsearchvacs/';
    MainConfig.AJAX_POST_SETVACATIONRESPONSE = '/ajax/setvacationresponse/';
    MainConfig.AJAX_POST_SETRESPONSESTATUS = '/ajax/setresponsestatus/';
    MainConfig.AJAX_POST_SENDUSERMESAGES = '/ajax/sendusermesages/';
    MainConfig.AJAX_POST_CREATESERVICEORDER = '/ajax/createServiceOrder/';
    MainConfig.AJAX_POST_POSTLOGOFILE = '/ajax/postlogofile/';
    MainConfig.AJAX_POST_CROPLOGO = '/ajax/croplogo/';
    MainConfig.AJAX_POST_UPLOADUNI = '/ajax/uploaduni/';
    MainConfig.AJAX_POST_UPLOADUNI_EX = '/ajax/uploaduniex/';
    MainConfig.AJAX_POST_INVITE = '/ajresponse/invite';
    MainConfig.PAGE_USER_PROFILE = '/user/profile';
    MainConfig.PAGE_PROFILE_COMMON = '/site/ankety';
    MainConfig.PAGE_SEARCH_PROMO = '/ankety';
    MainConfig.PAGE_SEARCH_EMPL = '/site/searchempl';
    MainConfig.PAGE_SEARCH_VAC = '/site/vacancy';
    MainConfig.PAGE_VACANCY = 'user/vacancies/';
    MainConfig.PAGE_IM = 'user/im';
    MainConfig.PAGE_REGISTER_VK = 'user/register?p=vk';
    MainConfig.PAGE_REGISTER_FB = 'user/register?p=fb';
    MainConfig.PATH_PIC = '/theme/pic/';
    return MainConfig;
}());
var CommFuncs = (function () {
    function CommFuncs() {
        var self = this;
    }
    CommFuncs.merge = function (obj1, obj2) {
        var obj3 = {};
        for (var attrname in obj1) {
            obj3[attrname] = obj1[attrname];
        }
        for (var attrname in obj2) {
            obj3[attrname] = obj2[attrname];
        }
        return obj3;
    };
    CommFuncs.parseUrl = function () {
        var query_string = {};
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (typeof query_string[pair[0]] === "undefined") {
                query_string[pair[0]] = decodeURIComponent(pair[1]);
            }
            else if (typeof query_string[pair[0]] === "string") {
                var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
                query_string[pair[0]] = arr;
            }
            else {
                query_string[pair[0]].push(decodeURIComponent(pair[1]));
            }
        }
        return query_string;
    };
    CommFuncs.inArray = function (needle, haystack, strict) {
        if (strict === void 0) { strict = false; }
        var found = false, key, strict = !!strict;
        for (key in haystack) {
            if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
                found = true;
                break;
            }
        }
        return found;
    };
    CommFuncs.scrollTo = function () {
        $('body').stop().animate({ scrollTop: $("a[name=smth]").offset().top - 20 + 'px' }, 500);
    };
    CommFuncs.base64Encode = function (data) {
        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, enc = '';
        do {
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);
            bits = o1 << 16 | o2 << 8 | o3;
            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;
            enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        } while (i < data.length);
        switch (data.length % 3) {
            case 1:
                enc = enc.slice(0, -2) + '==';
                break;
            case 2:
                enc = enc.slice(0, -1) + '=';
                break;
        }
        return enc;
    };
    CommFuncs.clone = function (obj) {
        if (null == obj || "object" != typeof obj)
            return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr))
                copy[attr] = obj[attr];
        }
        return copy;
    };
    return CommFuncs;
}());
var HiddenText = (function () {
    function HiddenText(props) {
        this.openText = '';
        this.closeText = '';
        this.hiddPic = '';
        var self = this;
        var defProps = { openBtnClass: 'look-full',
            doNotClose: false
        };
        $.extend(defProps, props);
        props = defProps;
        self.options = props;
        self.openText = props.openText;
        self.closeText = props.closeText;
        self.hiddPic = props.hiddenImg;
        self.wrappObj = props.wrappObj;
        self.contentObj = props.contentObj;
        self.wrappObj.append('<div class="hidden-text"></div>');
        self.wrappObj.append("<div class=\"" + self.options.openBtnClass + "\"><a href=\"#\">" + self.openText + "</a></div>");
        self.wrappObj.find("." + self.options.openBtnClass + " a").click(function (e) { self.onShowFullTextClickFn(e, this); });
    }
    HiddenText.init = function (props) {
        HiddenText.wrapper = props.wrapper;
        HiddenText.content = props.content;
        $(HiddenText.wrapper).each(function (e) {
            var $that = $(this);
            props.contentObj = $that.find(HiddenText.content);
            if ($that.height() < props.contentObj.height()) {
                props.wrappObj = $that;
                new HiddenText(props);
            }
        });
    };
    HiddenText.prototype.onShowFullTextClickFn = function (e, that) {
        var self = this;
        e.preventDefault();
        var lH = self.contentObj.height();
        var lWrapp = self.wrappObj;
        var maxH = lWrapp.height();
        if (lWrapp.data('opened')) {
            lWrapp.find('.hidden-text').fadeIn(400);
            var prevH = lWrapp.data('opened');
            lWrapp.animate({ height: "-=" + (maxH - prevH) }, 300, function () {
                lWrapp.css({ maxHeight: '', height: '' }).removeAttr('data-opened').removeData('opened');
                lWrapp.find("." + self.options.openBtnClass).removeClass('-opened').css({ position: '' });
                var btnOpen = lWrapp.find("." + self.options.openBtnClass + " a");
                btnOpen.text(self.openText);
            });
        }
        else {
            lWrapp.animate({ maxHeight: lH }, 300, function () {
                lWrapp.css({ maxHeight: 'none' }).attr('data-opened', maxH);
                lWrapp.find('.hidden-text').fadeOut(200);
                lWrapp.find("." + self.options.openBtnClass).addClass('-opened').css({ position: 'static' });
                var btnOpen = lWrapp.find("." + self.options.openBtnClass + " a");
                btnOpen.text(self.closeText);
                if (self.options.doNotClose)
                    btnOpen.fadeOut(200);
            });
        }
    };
    HiddenText.wrapper = '';
    HiddenText.content = '';
    return HiddenText;
}());
var ModalWindow = (function () {
    function ModalWindow(props) {
        this.context = '#DiContent';
        this.props = {};
        this.defProps = {};
        var self = this;
        var defProps = { action: { active: 1,
                btnTitle: 'OK',
                onClick: ''
            },
            getContent: '',
            content: '',
            afterOpen: '',
            afterClose: '',
            bgIsCloseBtn: 1,
            position: 'fixed',
            context: '#DiContent'
        };
        self.defProps = defProps;
        props = self.setProps(props);
        self.context = props.context;
        ModalWindow.winObj = self;
        var wrapper = $('<div id="MWwrapper"></div>');
        wrapper.prependTo(self.context);
        $('<div class="mw-bg"></div>').appendTo('#MWwrapper');
        $('<div class="mw-win"><div class="loading"><i></i></div><a href="#" class="mw-close"></a><div class="header-block"></div><div class="mw-content"></div></div>').appendTo('#MWwrapper');
        $('#MWwrapper .mw-close').click(function (e) { self.onCloseFn(e); });
         $('.mw-closed').click(function (e) { self.onCloseFn(e); });
        $('#MWwrapper .mw-bg').click(function (e) { self.onCloseFn(e, 1); });
        self.winDOM = wrapper;
        ModalWindow.content = wrapper.find('.mw-content');
    }
    ModalWindow.open = function (props) {
        if (!ModalWindow.winObj)
            new ModalWindow(CommFuncs.clone(props));
        ModalWindow.winObj.onOpenFn(props);
    };
    ModalWindow.close = function (e) {
        if (!ModalWindow.winObj)
            new ModalWindow();
        ModalWindow.winObj.onCloseFn(e);
    };
    ModalWindow.redraw = function (props) {
        if (!ModalWindow.winObj)
            new ModalWindow(CommFuncs.clone(props));
        ModalWindow.winObj.onRedrawFn(props);
        props.afterRedraw && props.afterRedraw();
    };
    ModalWindow.moveCenter = function (props) {
        var self = this;
        if (!ModalWindow.winObj)
            new ModalWindow();
        ModalWindow.winObj.onMoveCenter(props);
    };
    ModalWindow.show = function (props) {
        var self = this;
        if (!ModalWindow.winObj)
            new ModalWindow();
        ModalWindow.winObj.onShow(props);
    };
    ModalWindow.hide = function (props) {
        var self = this;
        if (!ModalWindow.winObj)
            new ModalWindow();
        ModalWindow.winObj.onHide(props);
    };
    ModalWindow.loadingOn = function () {
        if (ModalWindow.winObj)
            ModalWindow.winObj.winDOM.find(".loading").fadeIn(200);
    };
    ModalWindow.loadingOff = function () {
        if (ModalWindow.winObj)
            ModalWindow.winObj.winDOM.find(".loading").fadeOut(200);
    };
    ModalWindow.prototype.onCloseFn = function (e, inIsBg) {
        if (inIsBg === void 0) { inIsBg = 0; }
        var self = this;
        e && e.preventDefault();
        if (!inIsBg || self.props.bgIsCloseBtn) {
            $("#MWwrapper .mw-bg").fadeOut(200);
            $("#MWwrapper .mw-win").fadeOut(400);
        }
        self.props.afterClose && self.props.afterClose();
    };
    ModalWindow.prototype.onOpenFn = function (props) {
        var self = this;
        props = self.setProps(props);
        $("#MWwrapper .loading").fadeIn(400);
        var winBg = $("#MWwrapper .mw-bg");
        var winContWrapp = $("#MWwrapper .mw-win");
        self.createBtn(props);
        if (props && props.getContent) {
            $.get(props.getContent, function (data) {
                winContWrapp.children('.mw-content').empty().append($(data));
                if ($(window).width() < winContWrapp.outerWidth())
                    winContWrapp.css({ width: $(window).width() - 20 });
                self.onMoveCenter();
                winBg.fadeIn(400);
                winContWrapp.fadeIn(400);
                props.afterOpen && props.afterOpen();
                $("#MWwrapper .loading").fadeOut(200);
            })
                .always(function () {
            });
        }
        else if (props && props.content) {
            var headerBlock = winContWrapp.find('.header-block');
            headerBlock.empty();
            if (props.content && $(props.content).data('header'))
                headerBlock.append("<div class='header'><div>".concat($(props.content).data('header'), "</div></div>"));
            if (props.content && $(props.content).data('title'))
                headerBlock.append("<div class='header2'>".concat($(props.content).data('title'), "</div>"));
            winContWrapp.children('.mw-content').empty().append($(props.content));
            if ($(window).width() < winContWrapp.outerWidth())
                winContWrapp.css({ width: $(window).width() - 20 });
            self.onMoveCenter();
            winBg.fadeIn(400);
            winContWrapp.fadeIn(400);
            props.afterOpen && props.afterOpen();
            $("#MWwrapper .loading").fadeOut(200);
        }
    };
    ModalWindow.prototype.onRedrawFn = function (props) {
        var self = this;
        props = self.setProps(props);
        var incCont = props.content;
        var winBg = $("#MWwrapper .mw-bg");
        var winContWrapp = $("#MWwrapper .mw-win");
        self.createBtn(props);
        winContWrapp.children('.mw-content').empty().append(incCont);
        if (props.position == 'absolute') {
            winContWrapp.css({ top: ($(window).outerHeight() - winContWrapp.outerHeight()) / 2, margin: "".concat('0 0 0 -', '' + winContWrapp.width() / 2, 'px'), position: 'absolute' });
        }
        else {
            winContWrapp.css({ margin: "-".concat('' + winContWrapp.height() / 2, 'px 0 0 -', '' + winContWrapp.width() / 2, 'px') });
        }
        self.winDOM.find(".loading").fadeOut(200);
    };
    ModalWindow.prototype.onMoveCenter = function (inProps) {
        var self = this;
        var winContWrapp = self.winDOM.find('.mw-win');
        var winCont = self.winDOM.find('.mw-info');
        var props = inProps ? inProps : {};
        if (self.props.position == 'absolute') {
            var top = ($(window).outerHeight() - winContWrapp.outerHeight()) / 2;
            if ($(window).outerHeight() < winContWrapp.outerHeight())
                top = 0;
            winContWrapp.css({ top: top, margin: "".concat('20px 10px 20px -', '' + 10, 'px'), width: "50%", left: "26%", position: 'absolute' });
        }
        else {
            winContWrapp.css({ margin: "-".concat('' + winContWrapp.height() / 2, 'px 0 0 -', '' + winContWrapp.outerWidth() / 2, 'px'), left: "50%"});
        }
    };
    ModalWindow.prototype.onShow = function (inProps) {
        var self = this;
        self.winDOM.find('.mw-win').fadeIn(200);
    };
    ModalWindow.prototype.onHide = function (inProps) {
        var self = this;
        self.winDOM.find('.mw-win').fadeOut(200);
    };
    ModalWindow.prototype.createBtn = function (inProps) {
        var self = this;
        var props = inProps;
        if (props.action.active) {
            var btn = self.winDOM.find('.button-action');
            btn.off('click');
            btn.remove();
            $('<div class="button-action"><button>' + props.action.btnTitle + '</button></div>').appendTo(self.winDOM.find('.mw-win'));
            if (!props.action.onClick)
                var func = function (e) { self.onCloseFn(e); };
            else
                var func = props.action.onClick;
            $('#MWwrapper .button-action').click(func);
        }
    };
    ModalWindow.prototype.setProps = function (inProps) {
        var self = this;
        var defaultProps = CommFuncs.clone(self.defProps);
        defaultProps.action = CommFuncs.clone(self.defProps.action);
        var props = inProps;
        $.extend(defaultProps.action, props.action);
        delete props.action;
        $.extend(defaultProps, props);
        self.props = defaultProps;
        return self.props;
    };
    return ModalWindow;
}());
var FormFilters = (function () {
    function FormFilters() {
        var self = this;
    }
    FormFilters.prototype.bindFiltersFn = function (context) {
        var self = this;
        context = $(context);
        if (context && context.length)
            var items = context.find("[data-field-filter]");
        else
            var items = $("[data-field-filter]");
        items.keypress(function (e) { self.onKeyPressFilterFn(e, this); });
    };
    FormFilters.prototype.onKeyPressFilterFn = function (e, frmObj) {
        var $that = $(frmObj);
        var params = $that.data('field-filter').split(';');
        var filters = {};
        for (var ii in params) {
            params[ii] = params[ii].replace("\\:", "|||");
            var val = params[ii].split(':', 2);
            val[1].length && (val[1] = val[1].replace("|||", ":"));
            filters[val[0]] = val[1] ? val[1] : '';
        }
        for (var ii in filters) {
            if (ii == 'digits') {
                var additSymbols = filters[ii];
                if (e.which < 48 || e.which > 57) {
                    if (additSymbols &&
                        $.inArray(String.fromCharCode(e.which), additSymbols.split('')) !== -1 ||
                        $.inArray(e.keyCode, [8, 9, 46, 36, 35, 37, 39]) !== -1) {
                        return 1;
                    }
                    e.preventDefault();
                }
                else {
                }
            }
            else if (ii == 'max') {
                if ($that.val().length >= filters[ii]) {
                    if (frmObj.selectionStart != frmObj.selectionEnd)
                        return 1;
                    e.preventDefault();
                }
            }
        }
    };
    return FormFilters;
}());
var FormCheckers = (function () {
    function FormCheckers() {
        this.FLAG_STOP = 0;
        this.errBoxObj = 0;
        this.addErrClass = '';
        this.waitTimeErrorMess = 0;
        this.customCheckers = {};
        this.options = {};
        this.filters = {};
        this.T1wait = 0;
        var self = this;
        self.options = {
            'name': '',
            'elem': false,
            'message': '',
            'wait': 0,
        };
        self.filters = [
            [
                'empty',
                'max',
                'multi',
                'email',
                'if',
                'password',
                'notonlydigits',
                'custom',
            ],
            [
                'filterEmpty',
                'filterMax',
                'filterMulti',
                'filterEmail',
                'filterIf',
                'filterPassword',
                'filterNotonlydigits',
                'filterCustom',
            ],
        ];
    }

    FormCheckers.prototype.FormSubmit = function (props) {
        var self = this;
        
        return self.onFormSubmit(props.event, props.form, props.justReturn);
    };
    FormCheckers.prototype.addCheckerCustom = function (inName, callback) {
        var self = this;
        self.customCheckers[inName] = callback;
    };
    
    FormCheckers.prototype.onFormSubmit = function (e, frmObj, inJustReturn) {
        e.preventDefault();
        var self = this;
        frmObj = $(frmObj);
        var item = null;
        var itmVal = null;
        var mess = '';
        var flagError = 0;
        var actionMessage;
        var actionItem;
        $(frmObj).find("[data-field-check]:not(.nocheck)").each(function () {
            self.options.message = '';
            item = self.options.elem = $(this);
            itmVal = item.val();
            var params = item.data('field-check').split(',');
            var filters = {};
            for (var ii in params) {
                var val = params[ii].split(':');
                filters[val[0]] = val[1] ? val[1] : true;
            }
            for (var jj in filters) {
                self.setOptions(jj, filters[jj]);
            }
            for (var jj in filters) {
                var flpos;
                if ((flpos = $.inArray(jj, self.filters[0])) > -1) {
                    var fn = self.filters[1][flpos];
                    if (flagError = self[fn]({ filterVal: filters[jj], item: item, itemVal: itmVal }))
                        break;
                }
            }
            if (flagError)
                return false;
        });
        if (flagError) {
            item = self.options.elem;
            self.FLAG_STOP = 1;
            $('body').stop().animate({ scrollTop: item.offset().top - 30 + 'px' }, 500, function () {
                var msgBox = $('.error-hint-box');
                msgBox.text(self.options.message).css({ left: item.offset().left, top: item.offset().top + item.outerHeight() + 10 });
                msgBox.fadeIn(400);
                item.addClass('field--warning');
                item.focus();
                if (self.options.wait > 0 || item.hasClass('multiple')) {
                    var delay = self.options.wait ? self.options.wait : 3000;
                    self.T1wait = setTimeout(function () {
                        msgBox.fadeOut(200);
                        item.removeClass('field--warning');
                    }, delay);
                }
                item.on('blur', function () {
                    $(this).off('blur');
                    clearTimeout(self.T1wait);
                    self.FLAG_STOP || msgBox.fadeOut(200);
                    item.removeClass('field--warning');
                    $(this).off('blur');
                });
                self.FLAG_STOP = 0;
            });
            return false;
        }
        else {
            if (inJustReturn) {
                return true;
            }
            else {
                frmObj.off('submit').submit();
                return true;
            }
        }
    };
    FormCheckers.prototype.setOptions = function (fName, fVal) {
        var self = this;
        if (fName == 'name')
            self.options.name = fVal;
        else if (fName == 'elem') {
            self.options.elem = $(fVal);
        }
        else if (fName == 'message') {
            self.options.message = fVal;
        }
        else if (fName == 'wait') {
            self.options.wait = fVal;
        }
    };
    FormCheckers.prototype.filterEmpty = function (inProps) {
        var self = this;
        var flagError = 0;
        if (inProps.itemVal == '') {
            flagError = 1;
            self.options.message = 'Необходимо заполнить поле "' + self.options.name + '"';
        }
        return flagError;
    };
    FormCheckers.prototype.filterMax = function (inProps) {
        var self = this;
        if (inProps.itemVal.length > inProps.filterVal) {
            self.options.message = 'Поле "' + self.options.name + '" не должно превышать ' + inProps.filterVal + ' символов';
            return true;
        }
        return false;
    };
    FormCheckers.prototype.filterMulti = function (inProps) {
        var self = this;
        if (!inProps.itemVal || inProps.itemVal.length < 1) {
            self.options.message = 'Выберите значение в поле "' + self.options.name + '"';
            self.options.elem = self.options.elem.next('.ms-parent');
            return true;
        }
        return false;
    };
    FormCheckers.prototype.filterEmail = function (inProps) {
        var self = this;
        if (!(new RegExp("^[^@]+@[^.]+\.[^.^@]{2,}[^@]*$", '')).test(inProps.itemVal)) {
            self.options.message = 'Введите правильный e-mail адрес в поле "' + self.options.name + '"';
            return true;
        }
        return false;
    };
    FormCheckers.prototype.filterIf = function (inProps) {
        var self = this;
        return inProps.itemVal == inProps.filterVal;
    };
    FormCheckers.prototype.filterPassword = function (inProps) {
        var self = this;
        if (inProps.itemVal != $(inProps.filterVal).val()) {
            self.options.message = 'Пароль и его повторение не совпадают';
            return true;
        }
        return false;
    };
    FormCheckers.prototype.filterNotonlydigits = function (inProps) {
        var self = this;
        if (!(new RegExp("([A-ZА-Я]+)")).test(inProps.itemVal)) {
            if (!self.options.message)
                self.options.message = "\u041F\u043E\u043B\u0435 \"" + self.options.name + "\" \u0434\u043E\u043B\u0436\u043D\u043E \u0441\u043E\u0434\u0435\u0440\u0436\u0430\u0442\u044C \u043D\u0435 \u0442\u043E\u043B\u044C\u043A\u043E \u0446\u0438\u0444\u0440\u044B";
            return true;
        }
        return false;
    };
    FormCheckers.prototype.filterCustom = function (inProps) {
        var self = this;
        return self.customCheckers[inProps.filterVal]({ value: inProps.itemVal });
    };
    return FormCheckers;
}());
var DDMultiAjax = (function () {
    function DDMultiAjax(inObj, inParams) {
        this.ajaxRetData = {};
        this.selectObj = {};
        this.selfObj = {};
        this.loadObj = {};
        this.options = { ajaxParams: {} };
        this.vT1 = 0;
        this.afterItemSelectedFn = 0;
        var self = this;
        var defProps = { ajaxParams: { inputName: '',
                url: '',
                addParams: {}
            },
            insertAllow: 0,
            width: 280,
            insertFirst: 0,
            loadingGIF: MainConfig.PATH_PIC + 'loading2.gif',
            labelText: 'Chose smth',
            btnAddHint: '',
            afterItemSelected: null
        };
        $.extend(defProps.ajaxParams, inParams.ajaxParams);
        $.extend(defProps, inParams);
        inParams = defProps;
        self.options = inParams;
        self.selectObj = $(inObj);
        $('<div id="DiLoadingDDMA"><img src="' + self.options.loadingGIF + '" alt=""></div>').appendTo('body');
        self.loadObj = $('#DiLoadingDDMA');
        self.init();
    }
    DDMultiAjax.prototype.setAjaxParam = function (key, val) {
        var self = this;
        self.options.ajaxParams.addParams[key] = val;
        return self;
    };
    DDMultiAjax.prototype.clear = function () {
        var self = this;
        self.selfObj.find('.choices').empty();
    };
    DDMultiAjax.prototype.setWidth = function (inWidth) {
        var self = this;
        var wrapper = self.selfObj;
        var width = self.options.width = inWidth;
        if (width) {
            wrapper.find('.dropdown-block').css({ margin: "0 0 0 " + (-width + 30 + 5) + "px",
                width: width + 'px' });
            wrapper.find('.dropdown').css({ width: (width - 12) + "px" });
            wrapper.find('input.noinsert').css({ width: (width - 12) + "px" });
        }
    };
    DDMultiAjax.prototype.init = function () {
        var self = this;
        if (self.options.insertAllow) {
            var block = '<input type="text" class="edit"/><a href="#" class="ok" title="Добавить в список"></a>';
        }
        else {
            var block = '<input type="text" class="edit noinsert"/>';
        }
        var wrapper = self.selfObj = self.selectObj.after('<div class="dropdown-multi">' +
            '<a href="#" class="add-btn" title="' + self.options.btnAddHint + '"></a>' +
            '<div class="dropdown-block">' +
            '<label for="">' + self.options.labelText + '</label>' +
            block +
            '<div class="dropdown">' +
            '<div class="title"></div><div class="close">x</div>' +
            '<div class="choices">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>').next();
        self.setWidth(self.options.width);
        wrapper.find('.add-btn').click(function (e) { self.onAddBtnCLickFn(e, this); });
        wrapper.find("input.edit").keypress(function (e) { return !(e.which == 13); })
            .keyup(function (e) { return self.onEditKeyPressFn(e, this); })
            .blur(function (e) { self.onEditBlurFn(e, this); });
        wrapper.find(".ok").click(function (e) { self.onOkBtnClickFn(e, this); });
        wrapper.find(".close").click(function (e) { self.closeFn(); });
    };
    DDMultiAjax.prototype.onAddBtnCLickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        $that.toggleClass('opened');
        self.selfObj.find('.dropdown-block').slideToggle(300).find('input').focus().select();
    };
    DDMultiAjax.prototype.onEditBlurFn = function (e, that) {
        var self = this;
        setTimeout(function () {
            var $that = $(that);
            self.closeFn();
        }, 300);
    };
    DDMultiAjax.prototype.onEditKeyPressFn = function (e, that) {
        e = e || event;
        var self = this;
        var $that = $(that);
        var wrapper = self.selfObj;
        var select = wrapper.find('.dropdown').stop().slideDown(300);
        var choices = wrapper.find('.choices');
        clearTimeout(self.vT1);
        self.vT1 = setTimeout(function () {
            self.showLoadingFn($that, 1);
            var params = {};
            params[self.options.ajaxParams.inputName] = $that.val();
            for (var ii in self.options.ajaxParams.addParams) {
                var val = self.options.ajaxParams.addParams[ii];
                params[ii] = val;
            }
            $.get(self.options.ajaxParams.url, params, function (data) {
                data = JSON.parse(data);
                self.ajaxRetData = data;
                if (self.options.insertFirst > 1) {
                }
                choices.empty();
                for (var ii in data) {
                    var itm = $("<a href='#' class='item'".concat(" data-name='", data[ii].name, "' data-id='", data[ii].id, "'>", data[ii].name, "</a>")).appendTo(choices);
                }
                if (data.length < 1) {
                    if (self.options.insertAllow) {
                        select.stop().slideUp(200);
                    }
                    else {
                        wrapper.find('.title').text('Не найдено совпадений');
                    }
                }
                else {
                    wrapper.find('.title').text('');
                    select.css({ height: '' }).slideDown(300);
                }
                select.find('.item').click(function (e) { self.onDLItemCLickFn(e, this); });
            }).always(function () {
                self.hideLoadingFn();
            });
        }, 500);
    };
    DDMultiAjax.prototype.closeFn = function () {
        var self = this;
        self.selfObj.find('.dropdown-block').slideUp(200);
        self.selfObj.find('.add-btn').removeClass('opened');
    };
    DDMultiAjax.prototype.showLoadingFn = function (inElm, isRight) {
        var self = this;
        var DiLoading = self.loadObj;
        DiLoading.find('img').attr('src', self.options.loadingGIF);
        if (isRight)
            DiLoading.css({ left: inElm.offset().left + inElm.width() - DiLoading.width() - 20, top: inElm.offset().top + parseInt((inElm.outerHeight() - DiLoading.outerHeight()) / 2) - 1 });
        DiLoading.fadeIn(400);
    };
    DDMultiAjax.prototype.hideLoadingFn = function () {
        this.loadObj.fadeOut(400);
    };
    DDMultiAjax.prototype.onDLItemCLickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        $that.closest('.dropdown-block').find('input').val($that.text());
        var itm;
        if (!(itm = self.findAddedCity($that.text()))) {
            var itm = $("<option value='".concat($that.data('id'), "' selected>", $that.text(), "</option>"));
            if (self.options.insertFirst > 0) {
                var selItms = self.selectObj.find('option');
                if (selItms.length >= self.options.insertFirst) {
                    if (self.options.insertFirst < 2)
                        itm.prependTo(self.selectObj);
                    else
                        $(selItms[self.options.insertFirst - 2]).after(itm);
                }
                else {
                    itm.appendTo(self.selectObj);
                }
            }
            else
                itm.appendTo(self.selectObj);
            self.selectObj.multipleSelect('refresh');
        }
        else {
            itm.prop('selected', true);
            itm.parent().multipleSelect('refresh');
        }
        self.closeFn();
        self.options.afterItemSelected != '' && self.options.afterItemSelected([$that.data('id'), $that.text()]);
    };
    DDMultiAjax.prototype.findAddedCity = function (inName) {
        var self = this;
        var wrapper = self.selectObj;
        var flag = 0;
        wrapper.find('option').each(function () {
            var $that = $(this);
            if (($that.text()).toUpperCase() == inName.toUpperCase()) {
                return $that;
            }
        });
        return flag;
    };
    DDMultiAjax.prototype.onOkBtnClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var wrapper = self.selfObj;
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
            self.selectObj.prepend("<option value='".concat(flag ? flag : input.val(), "' selected>", input.val(), "</option>"))
                .multipleSelect('refresh');
        self.closeFn();
    };
    return DDMultiAjax;
}());
var Uploaduni = (function () {
    function Uploaduni() {
        var self = this;
    }
    Uploaduni.prototype.init = function (props) {
        var self = this;
        var defProps = { scope: '',
            imgsWrapper: '',
            filesWrapper: '',
            imgBlockTmpl: '',
            filesBlockTmpl: '',
            lnktoimg: '',
            uploadForm: '',
            uploadConnector: '',
            messageBlock: '',
            loadingBLock: '',
            onDeleteEnd: '',
        };
        $.extend(defProps, props);
        props = defProps;
        self.props = props;
        $(self.props.imgsWrapper).find('.uni-delete').click(function (e) { self.onFileDelete(e, this); });
        self.props.filesWrapper && $(self.props.filesWrapper + ', ' + self.props.imgsWrapper).find('.uni-del').click(function (e) { self.onFileDeleteEx(e, this); });
    };
    Uploaduni.prototype.upload = function (inUplInp) {
        var self = this;
        self.onStartUpload(inUplInp);
    };
    Uploaduni.prototype.uploadEx = function (opts) {
        if (!opts || opts && !opts.uploadInput)
            return false;
        var self = this;
        var $that = $(opts.uploadInput);
        var file = opts.uploadInput.files[0];
        var flag = 1;
        if (file) {
            var form = $that.closest('form');
            if (!flag) {
            }
            else {
                form.find(self.props.loadingBLock).fadeIn(400);
                var formData = new FormData(form[0]);
                formData.set('fn', $that.attr('name'));
                formData.set('sc', self.props.scope);
                formData.set('meta', CommFuncs.base64Encode(JSON.stringify(opts.meta)));
                formData.set('op', '1');
                $.ajax({
                    url: self.props.uploadConnector,
                    type: 'POST',
                    success: function (data) {
                        var error = 0;
                        var message = _t('fileLoad');
                        try {
                            data = JSON.parse(data);
                            if (data.error < 0) {
                                if (data.ret)
                                    console.warn('ret', data.ret);
                                error = data.error;
                                throw new Error(data.message);
                            }
                            else if (data.error == 100) {
                                self.files = data['files'];
                                opts.onAfterUpload && (data = opts.onAfterUpload(data));
                                if (data['file']['meta']['type'] == 'images') {
                                    var imgTpl = $('.' + self.props.imgBlockTmpl).clone();
                                    imgTpl.find('.uni-img').attr('src', data['file']['files']['tb']);
                                    if (data['file']['files']['orig'])
                                        imgTpl.find('.uni-img-link').attr('href', data['file']['files']['orig']);
                                    imgTpl.find('.uni-del').attr('data-id', data['id']).click(function (e) { self.onFileDelete(e, this); });
                                    imgTpl.hide().removeClass("tmpl " + self.props.imgBlockTmpl).addClass('uni-img-block');
                                    $(self.props.imgsWrapper).append(imgTpl);
                                    imgTpl.slideDown(400);
                                }
                                else {
                                    var fileTpl = $('.' + self.props.filesBlockTmpl).clone();
                                    fileTpl.removeClass('tmpl');
                                    if (data['file']['files']['orig'])
                                        fileTpl.find('.uni-link').attr('href', data['file']['files']['orig']);
                                    fileTpl.find('.uni-link').text(data['file']['meta']['name']);
                                    fileTpl.find('.uni-del').attr('data-id', data['id']).click(function (e) { self.onFileDelete(e, this); });
                                    var ext = data['file']['files']['orig'].substr(data['file']['files']['orig'].lastIndexOf('.') + 1);
                                    fileTpl.addClass('-' + ext).hide().removeClass(self.props.filesBlockTmpl).addClass('uni-file-block');
                                    $(self.props.filesWrapper).append(fileTpl);
                                    fileTpl.slideDown(400);
                                }
                            }
                            opts.onSuccessEnd && opts.onSuccessEnd(imgTpl ? imgTpl : fileTpl);
                        }
                        catch (e) {
                            var code = error;
                            if (code !== 0)
                                message = e.message;
                            error = 1;
                        }
                        if (error) {
                            self.props.messageBlock && form.find(self.props.messageBlock).text(message);
                            console.warn('E', code);
                        }
                    },
                    error: function () {
                        form.find('.message').text(_t('fileLoad'));
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
        else {
            return;
        }
    };
    Uploaduni.prototype.getFiles = function () {
        var self = this;
        return self.files;
    };
    Uploaduni.prototype.setFiles = function (inFiles) {
        var self = this;
        self.files = inFiles;
    };
    Uploaduni.prototype.onStartUpload = function (inUplInp) {
        var self = this;
        var $that = $(inUplInp);
        var file = inUplInp.files[0];
        var flag = 1, message;
        if (file) {
            var form = $that.closest('form');
            if (!flag) {
                form.find(self.props.messageBlock).text(message);
            }
            else {
                form.find(self.props.loadingBLock).fadeIn(400);
                var formData = new FormData(form[0]);
                formData.set('fn', $that.attr('name'));
                formData.set('sc', self.props.scope);
                formData.set('op', '1');
                $.ajax({
                    url: self.props.uploadConnector,
                    type: 'POST',
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.error) {
                            self.props.messageBlock && form.find(self.props.messageBlock).text(data.message);
                            if (data.ret)
                                console.log('ret', data.ret);
                        }
                        else {
                            self.files = data['files'];
                            var imgTpl = $('.' + self.props.imgBlockTmpl).clone();
                            imgTpl.find('.uni-img').attr('src', data['file']['tb']);
                            data['file']['orig'] && imgTpl.find('.uni-img-link').attr('href', data['file']['orig']);
                            imgTpl.find('.uni-delete').attr('data-id', data['id']).click(function (e) { self.onFileDelete(e, this); });
                            imgTpl.hide().removeClass(self.props.imgBlockTmpl).addClass('uni-img-block');
                            $(self.props.imgsWrapper).append(imgTpl);
                            imgTpl.slideDown(400);
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
    Uploaduni.prototype.onFileDelete = function (e, that) {
        var self = this;
        var $that = $(that);
        var message;
        var form = $that.closest('form');
        var id = $that.data('id');
        var formData = new FormData(form[0]);
        formData.set('id', id);
        formData.set('op', '2');
        $.ajax({
            url: self.props.uploadConnector,
            type: 'POST',
            success: function (data) {
                var error = 0;
                var message = _t('fileDeleteError');
                try {
                    data = JSON.parse(data);
                    if (data.error < 0) {
                        self.props.messageBlock && form.find(self.props.messageBlock).text(data.message);
                        throw { message: data.message, code: data.error };
                    }
                    else if (data.error == 100) {
                        var wrapp = $that.closest(self.props.imgsWrapper);
                        wrapp.find('[data-id=' + id + ']').closest('.uni-img-block').slideUp(200, function () {
                            $(this).remove();
                        });
                    }
                    else {
                        error = 1;
                    }
                }
                catch (e) {
                    var code = e.code;
                    if (code != undefined)
                        message = e.message;
                    error = 1;
                }
                if (error) {
                    self.props.messageBlock && form.find(self.props.messageBlock).text(message);
                    code && console.warn('E', code);
                }
            },
            error: function () {
                form.find('.message').text(_t('fileDeleteError'));
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }, 'json')
            .always(function () {
            form.find('.loading-ico').fadeOut(200);
        });
    };
    Uploaduni.prototype.onFileDeleteEx = function (e, that) {
        var self = this;
        var $that = $(that);
        var message;
        var form = $that.closest('form');
        var id = $that.data('id');
        var formData = new FormData(form[0]);
        formData.set('id', id);
        formData.set('op', '2');
        $.ajax({
            url: self.props.uploadConnector,
            type: 'POST',
            success: function (data) {
                var error = 0;
                var message = _t('fileDeleteError');
                try {
                    data = JSON.parse(data);
                    if (data.error < 0) {
                        self.props.messageBlock && form.find(self.props.messageBlock).text(data.message);
                        throw { message: data.message, code: data.error };
                    }
                    else if (data.error == 100) {
                        var wrapp = $that.closest(self.props.imgsWrapper + ', ' + self.props.filesWrapper);
                        wrapp.find('[data-id=' + id + ']').closest('.uni-img-block').slideUp(200, function () {
                            $(this).remove();
                            self.props.onDeleteEnd && self.props.onDeleteEnd();
                        });
                    }
                    else {
                        error = 1;
                    }
                }
                catch (e) {
                    var code = e.code;
                    if (code != undefined)
                        message = e.message;
                    error = 1;
                }
                if (error) {
                    self.props.messageBlock && form.find(self.props.messageBlock).text(message);
                    code && console.warn('E', code);
                }
            },
            error: function () {
                form.find('.message').text(_t('fileDeleteError'));
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }, 'json')
            .always(function () {
            form.find('.loading-ico').fadeOut(200);
        });
    };
    return Uploaduni;
}());
var VKservice = (function () {
    function VKservice() {
        this.idUser = 0;
        var self = this;
        VK.init({
            apiId: 5556737
        });
    }
    VKservice.prototype.init = function () {
    };
    VKservice.prototype.login = function (inUrlRedirect) {
        var self = this;
        VK.Auth.login(function (response) { self.authInfo(response, inUrlRedirect); });
    };
    VKservice.prototype.authInfo = function (response, inUrlRedirect) {
        var self = this;
        if (response.session) {
            self.idUser = response.session.mid;
            self.getProfile(inUrlRedirect);
        }
        else {
            0 || console.info('not auth');
        }
    };
    VKservice.prototype.getProfile = function (inUrlRedirect) {
        var self = this;
        var id = self.idUser;
        if (id == "")
            return;
        $.ajax({
            type: 'GET',
            url: 'https://api.vk.com/method/users.get?user_ids=' + id + '&fields=bdate,photo_id,photo_max_orig,photo_400_orig,photo_200,contacts,personal',
            headers: { 'Access-Control-Allow-Origin': 'https://prommu.com' },
            cache: false,
            dataType: 'jsonp',
            success: function (data) {
                var resp = data.response[0];
                $.ajax({
                    type: 'GET',
                    url: 'https://api.vk.com/method/photos.get?album_id=profile&rev=1&count=1&owner_id=' + id,
                    headers: { 'Access-Control-Allow-Origin': 'https://prommu.dev' },
                    cache: false,
                    dataType: 'jsonp',
                    success: function (data) {
                        var retData = CommFuncs.merge(resp, { photo_max: data.response[0].src_xxbig });
                        if (inUrlRedirect)
                            location.href = ("/" + inUrlRedirect + "&data=") + CommFuncs.base64Encode(encodeURIComponent(JSON.stringify(retData)));
                        return 1;
                    },
                    error: function (data) {
                        0 || console.info('no photo');
                    }
                });
                return 1;
            },
            error: function (data) {
                0 || console.info('Data download error');
            }
        });
    };
    return VKservice;
}());
var FBservice = (function () {
    function FBservice() {
        this.idUser = 0;
        var self = this;
    }
    FBservice.init = function () {
        window['fbAsyncInit'] = function () {
            FB.init({
                appId: '796086723839878',
                xfbml: true,
                version: 'v2.7'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    };
    FBservice.prototype.login = function (inUrlRedirect) {
        var self = this;
        FB.login(function (response) {
            if (response.authResponse) {
                FB.getLoginStatus(function (response) {
                    self.statusChangeCallback(response, inUrlRedirect);
                });
            }
            else {
                document.querySelector('#Di1Message span').innerHTML = _t('fb.loginFail');
            }
        });
    };
    FBservice.prototype.statusChangeCallback = function (response, inUrlRedirect) {
        var self = this;
        if (response.status === 'connected') {
            self.getUserInfo(inUrlRedirect);
        }
        else if (response.status === 'not_authorized') {
            document.querySelector('#Di1Message span').innerHTML = _t('fb.loginFail');
        }
        else {
            document.querySelector('#Di1Message span').innerHTML = _t('fb.loginFail');
        }
    };
    FBservice.prototype.getUserInfo = function (inUrlRedirect) {
        FB.api('/me?fields=id,email,birthday,first_name,last_name', function (response) {
            var data = response;
            FB.api("/me/picture?width=1000", function (response) {
                if (response && !response.error) {
                    var retData = CommFuncs.merge(data, { photo_max: response.data.url });
                    if (inUrlRedirect)
                        location.href = ("/" + inUrlRedirect + "&data=") + CommFuncs.base64Encode(encodeURIComponent(JSON.stringify(retData)));
                }
            });
        });
    };
    return FBservice;
}());
var Hinter = (function () {
    function Hinter() {
        var self = this;
    }
    Hinter.prototype.init = function () { };
    Hinter.bind = function (inSel, inOpts) {
        if (inOpts === void 0) { inOpts = {}; }
        var defUserOpts = { side: 'bottom',
            animation: 'fade',
        };
        var opts = this.options;
        if (this.hintSide[inOpts.side])
            defUserOpts.side = this.hintSide[inOpts.side];
        if (this.hintAnimation[inOpts.animation])
            defUserOpts.animation = this.hintAnimation[inOpts.animation];
        $.extend(opts, defUserOpts);
        $(inSel).tooltipster(opts);
    };
    Hinter.hintSide = { 'top': 'top', 'bottom': 'bottom', 'right': 'right', 'left': 'left' };
    Hinter.hintAnimation = { 'fade': 'fade', 'swing': 'swing' };
    Hinter.options = { side: 'bottom',
        theme: ['tooltipster-noir', 'tooltipster-noir-customized'],
        animation: 'fade',
    };
    return Hinter;
}());
var CommentAdditor = (function () {
    function CommentAdditor() {
        this.formAction = '';
        var self = this;
    }
    CommentAdditor.prototype.open = function (props) {
        var self = this;
        self.formAction = props.formAction;
        self.FormChecker || (self.FormChecker = new FormCheckers());
        ModalWindow.open({
            getContent: props.getUrl,
            afterOpen: function () { self.onAfterOpen(); }
        });
    };
    CommentAdditor.prototype.onAfterOpen = function () {
        var self = this;
        G_VARS.App.applyRadios('#MWwrapper');
        G_VARS.App.applyCounterMemo('#MWwrapper');
        $("#MWwrapper #EdName").focus();
        $('#MWwrapper form').submit(function (e) {
            $("#MWwrapper .loading").fadeIn(400);
            if (self.FormChecker.FormSubmit({ event: e,
                form: '#MWwrapper form',
                justReturn: 1 })) {
                $("#BtnAddComment button").attr('type', "button");
                var values = $(this).serialize();
                $.post(self.formAction, values, function (data) {
                    ModalWindow.redraw({ content: $(data),
                        afterRedraw: function () { self.onAfterRedraw(); }
                    });
                });
            }
            else {
                $("#MWwrapper .loading").fadeOut(200);
            }
        });
    };
    CommentAdditor.prototype.onAfterRedraw = function () {
        var self = this;
        $("#BtnOk a").click(function (e) { ModalWindow.close(e); });
        $("#MWwrapper .loading").fadeOut(200);
    };
    return CommentAdditor;
}());
var IndServ = (function () {
    function IndServ() {
        this.customProps = {};
        var self = this;
    }
    IndServ.prototype.init = function () {
        var self = this;
        sbjs.init({
            domain: MainConfig.SITE,
            lifetime: 3,
            callback: function (sb) { self.doSmth(sb); }
        });
        $('.about__look-full a').click(function (e) { self.onShowFullTextClickFn(e); });
        $('a.enter__register-link').click(function (e) { return self.stopLinkFn(e); });
        $('.enter__user').removeClass('-autohover')
            .find('a.um')
            .click(function (e) { return self.onUserMenuClickFn(e, this); })
            .blur(function (e) { return self.onUserMenuClickFn(e, this, 1); });
        $('.top-menu-wr').removeClass('-autopopup')
            .find('a.menu')
            .click(function (e) { return self.onTopMenuClickFn(e, this); })
            .blur(function (e) { return self.onTopMenuClickFn(e, this, 1); });
        $(".nofirst").click(function () { self.dropDropBoxFirstItemFn(this); });
        self.applyCheckbox(".checkbox-box input[type=checkbox], .checkbox-box-sm input[type=checkbox]");
        self.applyRadios('');
        self.applyCounterMemo('');
        $(".memo-hint-box button").click(function () { self.onMemoHintBoxBtnClick(this); });
        $(".memo-hint-box button").blur(function () { self.onMemoHintBoxBtnClick(this, 1); });
        Hinter.bind('.js-g-hashint.-js-g-hintleft', { side: 'left' });
        Hinter.bind('.js-g-hashint.-js-g-hintright', { side: 'right' });
        Hinter.bind('.js-g-hashint');
        var maxH = 0;
        $(".vacancy.premium .border").each(function () {
            var $that = $(this);
            if ($that.outerHeight() > maxH)
                maxH = $that.outerHeight();
        }).each(function () {
            $(this).css({ minHeight: maxH + 'px' });
        });
        if (G_PAGE == 'index') {
            var options = {
                url: function (phrase) {
                    return ($("#CBvacancy").val() == 1 ? MainConfig.AJAX_POST_GETSEARCHVACS : MainConfig.AJAX_POST_GETAPPLIC) + '?search=' + phrase;
                },
                getValue: 'name',
                ajaxSettings: {
                    dataType: "json",
                    method: "POST",
                    data: {
                        dataType: "json"
                    }
                },
                preparePostData: function (data) {
                    data.phrase = $("#EdSearch").val().trim();
                    return data;
                },
                requestDelay: 1000,
                list: { onChooseEvent: function () { self.onAutocompleteFn(); }
                }
            };
            $("#EdSearch").easyAutocomplete(options);
        }
        var props = {
            navigation: true,
            slideSpeed: 300,
            pagination: true,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true,
            autoplaySpeed: 300,
            autoplayTimeout: 2000,
            stopOnHover: true,
            navigationText: ['', ''],
        };
        $("#DiOwlSlider").owlCarousel(props);
        if (!FLAG_MOBILE) {
            props.singleItem = false;
            props.items = 4;
            props.itemsDesktop = [992, 3];
            props.itemsDesktopSmall = false;
            props.itemsTablet = [768, 1];
            props.itemsTabletSmall = false;
            props.itemsMobile = [479, 1];
            props.transitionStyle = false;
        }
        $("#DiApplicSlider").owlCarousel(props);
        if (!FLAG_MOBILE) {
            props.singleItem = false;
            props.items = 6;
            props.itemsDesktop = [992, 4];
            props.itemsDesktopSmall = false;
            props.itemsTablet = [768, 2];
            props.itemsTabletSmall = false;
            props.itemsMobile = [450, 1];
        }
        $("#DiEmplSlider").owlCarousel(props);
         if (!FLAG_MOBILE) {
            props.singleItem = false;
            props.items = 6;
            props.itemsDesktop = [992, 4];
            props.itemsDesktopSmall = false;
            props.itemsTablet = [768, 2];
            props.itemsTabletSmall = false;
            props.itemsMobile = [450, 1];
        }
        $("#DiEmpl1Slider").owlCarousel(props);
        $("#CBvacancy").change(function (e) { self.onChangeSearchTypeFn(e, this); }).msDropdown({ roundedBorder: false });
        (new PushChecker).init();
    };
    IndServ.prototype.applyCityBox = function () {
        var self = this;
        $(".city-box [type=text]").keyup(function (e) { self.onEdCityChangeFn(e, this); });
        $(".city-box [type=text]").blur(function () {
            var $that = $(this);
            setTimeout(function () {
                $that.closest('.city-box').find('.dropdown').fadeOut(100);
            }, 200);
        });
        $(".city-box .dropdown").hide();
        $(".city-block .btn-close").click(function (e) {
            e.preventDefault();
            if ($(".city-block").length < 3) {
                $("#CB1country").slideDown(200);
                $("#EdCountry").slideUp(200);
                $("#HiAddedCity").val('0');
            }
            // $(this).closest('.city-block').slideUp(200, function () {
            //     $(this).remove();
            // });
        });
    };
    IndServ.prototype.onEdCityChangeFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var box = $that.closest('.city-box');
        var choices = box.find('.choices');
        if (G_VARS.App.country) {
            clearTimeout(self.vT1City);
            self.vT1City = setTimeout(function () {
                G_VARS.App.showLoading($that, 1);
                $.get(MainConfig.AJAX_GET_CITYES, { filter: $(that).val(), idco: G_VARS.App.country, limit: 20 }, function (data) {
                    data = JSON.parse(data);
                    choices.empty();
                    for (var ii in data) {
                        var id = data[ii].ismetro == '1' ? "data-id='".concat(data[ii].id_city, "'") : '';
                        $("<a href='#' class='item' ".concat(id, ", data-name='", data[ii].name, "'>", data[ii].name, "</a>"))
                            .appendTo(choices);
                    }
                    var cbCity = box.find(".dropdown");
                    cbCity.width($that.outerWidth()).fadeIn(300);
                    cbCity.find('.item').click(function (e) { self.onCBCityClickFn(e, this); });
                }).always(function () {
                    G_VARS.App.hideLoading();
                });
            }, 300);
        }
    };
    IndServ.prototype.applyMetroHTML = function (that, inIdCity) {
        var self = this;
        self.onCBCityClickFn(0, that, inIdCity);
    };
    IndServ.prototype.onCBCityClickFn = function (e, that, inIdCity) {
        var self = this;
        var $that = $(that);
        var box = $that.closest('.city-box');
        var metroBlock = box.closest('.city-block').find('.metro-select');
        var select = metroBlock.find('select');
        var edCity = box.find('.EdCity');
        edCity.val($that.data('name'));
        e && e.preventDefault();
        var id = !inIdCity ? $that.data('id') : inIdCity;
        if (id) {
            G_VARS.App.showLoading(edCity, 1);
            $.get(MainConfig.AJAX_GET_METRO, { idcity: id }, function (data) {
                data = JSON.parse(data);
                select.empty();
                for (var ii in data) {
                    var selected = '';
                    if (G_VARS.userMetro && G_VARS.userMetro[data[ii].id] && G_VARS.userMetro[data[ii].id].idcity == id)
                        selected = "selected";
                    $("<option value='".concat(data[ii].id, "' ", selected, ">", data[ii].name, "</option>"))
                        .appendTo(select);
                }
                metroBlock.slideDown(400);
                select.multipleSelect('refresh').hide();
                select.next().css({ width: edCity.outerWidth() });
            }).always(function () {
                G_VARS.App.hideLoading();
            });
        }
        else {
            select.empty();
            select.multipleSelect('refresh');
            metroBlock.slideUp(200);
        }
    };
    IndServ.prototype.showLoading = function (inElm, isRight, props) {
        var self = this;
        var DiLoading = $("#DiLoading");
        if (props) {
            var offsetLeft = props.offsetLeft ? props.offsetLeft : 0;
            var align = props.align ? props.align : 0;
        }
        if (props && props.pic)
            var src = MainConfig.PATH_PIC + 'loading' + props.pic + '.gif';
        else
            var src = MainConfig.PATH_PIC + 'loading2.gif';
        DiLoading.find('img').attr('src', src).one('load', function () {
            if (props && props.variant == 2) {
                DiLoading.addClass('wb');
            }
            else {
            }
            DiLoading.css({ top: inElm.offset().top + parseInt((inElm.outerHeight() - DiLoading.outerHeight()) / 2) - 1 });
            if (isRight)
                DiLoading.css({ left: inElm.offset().left + inElm.width() - DiLoading.width() - 20, top: inElm.offset().top + parseInt((inElm.outerHeight() - DiLoading.outerHeight()) / 2) - 1 });
            else if (align == 'center')
                DiLoading.css({ left: inElm.offset().left + (inElm.outerWidth() - DiLoading.outerWidth()) / 2 });
            else
                DiLoading.css({ left: inElm.offset().left + offsetLeft, top: inElm.offset().top + parseInt((inElm.outerHeight() - DiLoading.outerHeight()) / 2) - 1 });
            if (props && props.left)
                DiLoading.css({ left: inElm.offset().left + props.left });
            if (props && props.top)
                DiLoading.css({ top: inElm.offset().top });
            DiLoading.fadeIn(400);
        });
    };
    IndServ.prototype.showLoading2 = function (inElm, props) {
        var self = this;
        var DiLoading = $("#DiLoading");
        var defProps = { align: 'center',
            valign: 'middle',
            pic: 2,
            offsetX: 0,
            offsetY: 0,
            outerAlign: '',
            withBg: false
        };
        inElm = $(inElm);
        $.extend(defProps, props);
        props = defProps;
        var src = MainConfig.PATH_PIC + 'loading' + props.pic + '.gif';
        DiLoading.find('img').attr('src', src).one('load', function () {
            if (props.outerAlign == 'left')
                DiLoading.css({ left: inElm.offset().left - DiLoading.width() + props.offsetX });
            else if (props.align == 'right')
                DiLoading.css({ left: inElm.offset().left + inElm.width() - DiLoading.width() + props.offsetX });
            else if (props.align == 'center')
                DiLoading.css({ left: inElm.offset().left + (inElm.outerWidth() - DiLoading.outerWidth()) / 2 });
            else if (props.align == 'left')
                DiLoading.css({ left: inElm.offset().left + props.offsetX });
            if (props.valign == 'top')
                DiLoading.css({ top: inElm.offset().top + props.offsetY });
            else if (props.valign == 'middle')
                DiLoading.css({ top: inElm.offset().top + parseInt((inElm.outerHeight() - DiLoading.height()) / 2) });
            if (props.withBg) {
                if (props.pic == '1')
                    DiLoading.addClass('wb-sq');
                else
                    DiLoading.addClass('wb');
            }
            else
                DiLoading.removeClass('wb wb-sq');
            DiLoading.stop().fadeIn(400);
        });
    };
    IndServ.prototype.hideLoading = function () {
        setTimeout(function () { $("#DiLoading").stop().fadeOut(400); }, 400);
    };
    IndServ.prototype.applyCounterMemo = function (inContext) {
        var self = this;
        $(inContext + " .memo-with-counter textarea").keyup(function () { self.onMemoKeyUpFn(this); });
    };
    IndServ.prototype.applyRadios = function (inContext) {
        var self = this;
        var items = $("label.radio-box");
        $.each(items, function () {
            var $that = $(this);
            var itm1 = $that.find('input').eq(0);
            if (itm1.prop('checked'))
                $that.addClass('checked');
        });
        $(inContext + " .radio-box input[type=radio]").change(function () { self.onRadioBoxChangeFn(this); });
    };
    IndServ.prototype.applyCheckbox = function (inContext) {
        var self = this;
        $(inContext).each(function () {
            var $that = $(this);
            var val = $that.data('val');
            var vals = val ? val.split(':') : '';
            if (vals.length > 1) {
                if ($that.val() == vals[0]) {
                    $that.parent().addClass('checked');
                    $that.prop('checked', true);
                }
            }
            else {
                $that.parent().toggleClass('checked', $that.is(':checked'));
            }
        });
        $(inContext).change(function (e, isNoFireEvent) { self.onChkboxChangeFn(this, isNoFireEvent); });
    };
    IndServ.prototype.onShowFullTextClickFn = function (e) {
        var self = this;
        var lHiddBox = $('.about__look-full');
        var lH = $('.about__content').height();
        $('.about').animate({ height: lH }, 300, function () {
            $('.hidden-text').fadeOut(200);
            $('.about__look-full').fadeOut(200);
        });
        e.preventDefault();
    };
    IndServ.prototype.stopLinkFn = function (e) {
        var self = this;
        e.preventDefault();
        return false;
    };
    IndServ.prototype.dropDropBoxFirstItemFn = function (that) {
        var self = this;
        var $that = $(that);
        var childs = $(that).children('option');
        if ($that.hasClass('nofirst'))
            childs[0].remove();
        $that.removeClass('nofirst');
        return false;
    };
    IndServ.prototype.onChkboxChangeFn = function (that, isNoFireEvent) {
        var self = this;
        var $that = $(that);
        var val = $that.data('val');
        var vals = val ? val.split(':') : '';
        if (vals.length > 1) {
            $that.prop('checked', true);
            if ($that.val() == vals[1]) {
                $that.parent().addClass('checked');
                $that.val(vals[0]);
            }
            else {
                $that.parent().removeClass('checked');
                $that.val(vals[1]);
            }
        }
        else {
            $that.parent().toggleClass('checked', $that.is(':checked'));
        }
        !isNoFireEvent && $that.trigger('customOnCheck');
    };
    IndServ.prototype.onRadioBoxChangeFn = function (that) {
        var self = this;
        $(that).closest('div.radio-box').find('.checked').each(function () {
            $(this).removeClass('checked');
        });
        $(that).parent().toggleClass('checked', $(that).is(':checked'));
    };
    IndServ.prototype.onMemoKeyUpFn = function (that) {
        var self = this;
        var $that = $(that);
        var cou = $that.data('counter');
        var len = $that.val().length > parseInt(cou) ? ''.concat('<span style="color: red;">', $that.val().length, '</span>') : $that.val().length;
        $that.parent().find('.memo-counter').html(['(', len, '/', cou, ')'].join(''));
    };
    IndServ.prototype.onMemoHintBoxBtnClick = function (that, inState) {
        var self = this;
        var $that = $(that);
        var helpBox = $that.parent().find('.help-box');
        if (inState)
            helpBox.fadeOut(200);
        else
            helpBox.fadeIn(400, function () {
            });
    };
    IndServ.prototype.onUserMenuClickFn = function (e, that, isClose) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        var userMenu = $that.parent();
        if (!userMenu.find('div').is(':hidden') || isClose) {
            userMenu.find('div').fadeOut(200);
            userMenu.find('i').removeClass('up');
        }
        else {
            userMenu.find('div').fadeIn(400);
            userMenu.find('i').addClass('up');
        }
    };
    IndServ.prototype.onTopMenuClickFn = function (e, that, isClose) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        var topMenu = $that.parent();
        if (!topMenu.find('.csson').is(':hidden') || isClose) {
            topMenu.find('.csson').fadeOut(200);
            topMenu.find('.menu').removeClass('hover');
        }
        else {
            topMenu.find('.csson').fadeIn(400);
            topMenu.find('.menu').addClass('hover');
        }
    };
    IndServ.prototype.showHint = function (item, inText) {
        var msgBox = $('.error-hint-box');
        msgBox.text(inText).css({ left: item.offset().left, top: item.offset().top + item.outerHeight() + 10 });
        msgBox.stop().fadeIn(400);
    };
    IndServ.prototype.closeHint = function () {
        $('.error-hint-box').stop().fadeOut(200);
    };
    IndServ.prototype.doSmth = function (sb) {
        $('.referer').val(sbjs.get.current.typ);
        $('.transition').val(sbjs.get.current.src);
        $('.canal').val(sbjs.get.current.mdm);
        $('.campaign').val(sbjs.get.current.cmp);
        $('.content').val(sbjs.get.current.cnt);
        $('.keywords').val(sbjs.get.current.trm);
        $('.point').val(sbjs.get.current_add.ep);
        $('.last_referer').val(sbjs.get.current_add.rf);
    };
    IndServ.prototype.debug = function (inSection) {
        if (inSection == 'publVac') {
            $("#Mtitle").val('test');
            $("#M1requirements").val('test');
            $("#M2duties").val('test');
            $("#M3conditions").val('test');
            $("#CB1Dolj").val('18');
            $("#CB2city").val('1');
            $("#CB6salary").val('1');
            $("#EdSalRubH").val('1');
            $("#EdExp").val('1');
        }
    };
    IndServ.prototype.onAutocompleteFn = function () {
        var self = this;
        var value = $("#EdSearch").getSelectedItemData().code;
        if ($("#CBvacancy").val() == 1)
       
            window.location.href = MainConfig.PAGE_VACANCY + "/" + value;
        else
            window.location.href = MainConfig.PAGE_PROFILE_COMMON + "/" + value;
    };
    IndServ.prototype.onChangeSearchTypeFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var EdSearch = $("#EdSearch");
        var FmSearch = $("#FmSearch");
        if ($that.val() == 1) {
            FmSearch.attr('action', MainConfig.PAGE_SEARCH_VAC);
            EdSearch.attr('name', 'poself');
        }
        if($that.val() == 2){
            FmSearch.attr('action', MainConfig.PAGE_SEARCH_PROMO);
            EdSearch.attr('name', 'qs');
        }
        if($that.val() == 3){
            FmSearch.attr('action', MainConfig.PAGE_SEARCH_EMPL);
            EdSearch.attr('name', 'qs');
        }
        EdSearch.attr('placeholder', EdSearch.data('ph' + $that.val()));
    };
    return IndServ;
}());
var DeviceCheck = (function () {
    function DeviceCheck() {
        this.isMobile = 0;
        var self = this;
    }
    DeviceCheck.prototype.init = function () {
        var self = this;
        if (self.isMobile) {
            setTimeout(function () {
                self.initControlsFn();
            }, 2000);
        }
    };
    DeviceCheck.prototype.initControlsFn = function () {
        var self = this;
        var siteVer = $.cookie('mobileVer');
        if (siteVer == undefined || siteVer == 3) {
            $.cookie('mobileVer', 3);
            $(".go-desktop").show(300);
            $("#DiMessBox .close").hide();
            $("#DiMessBox").slideDown(700, function () { $("#DiMessBox .close").fadeIn(200); });
        }
        else {
            if (siteVer == '2') {
                $(".go-desktop").hide();
                $(".go-mobile").show();
            }
            else {
                $(".go-desktop").show();
                $(".go-mobile").hide();
            }
        }
        $('#DiMessBox .close').click(function (e) { self.onCloseClick(); });
        $('.go-mobile').click(function (e) { self.onGoSiteVerClick(e, 1); });
        $('.go-desktop').click(function (e) { self.onGoSiteVerClick(e, 2); });
        $('#DiMessBox .mobile').click(function (e) { self.onGoSiteVerClick(e, 2); });
    };
    DeviceCheck.prototype.chkMobile = function () {
        var self = this;
        var ret = device.mobile();
        if (ret) {
            self.isMobile = 1;
            if ($.cookie('mobileVer') == undefined) {
                $.cookie('mobileVer', 3);
                document.location = "";
            }
        }
        return ret;
    };
    DeviceCheck.prototype.onCloseClick = function () {
        var self = this;
        $.cookie('mobileVer', 1);
        $('#DiMessBox').fadeOut(200);
    };
    DeviceCheck.prototype.onGoSiteVerClick = function (e, inIsMobile) {
        var self = this;
        $.cookie('mobileVer', inIsMobile);
    };
    DeviceCheck.prototype.debug = function () {
        $(".copy").click(function () { $.removeCookie('mobileVer'); alert('cookie removed'); });
    };
    return DeviceCheck;
}());
var VacResponses = (function () {
    function VacResponses() {
        var self = this;
    }
    VacResponses.prototype.doResponse = function (inId, callback) {
        var self = this;
        $.get(MainConfig.AJAX_POST_SETVACATIONRESPONSE, { id: G_VARS.idVac }, function (data) {
            data = JSON.parse(data);
            ModalWindow.open({ content: data.html });
            callback(data);
        });
    };
    return VacResponses;
}());
var Page = (function () {
    function Page() {
        this.FLAG_STOP = 0;
        var self = this;
        self.FormCheckers = new FormCheckers();
    }
    Page.prototype.bindFiltersFn = function (context) {
        var self = this;
        var ff = new FormFilters();
        ff.bindFiltersFn(context);
        self.FormFilter = ff;
    };
    Page.prototype.onFormSubmit = function (e, frmObj, props) {
        var self = this;
        props || (props = {});
        if (props.onBeforeSubmit)
            props.inJustReturn = 1;
        var fc = self.FormCheckers;
        if (!props)
            props = {};
        var ret = fc.FormSubmit(CommFuncs.merge({ event: e,
            form: frmObj
        }, props));
        if (ret && props.onBeforeSubmit) {
            if (props.onBeforeSubmit())
                frmObj.submit();
            else
                ret = false;
        }
        return ret;
    };
    return Page;
}());
var PageSearchVacs = (function () {
    function PageSearchVacs() {
        this.CBcityMulti = {};
        var self = this;
        $("#ChkAllContacts").change(function (e, isJustUnchk) { self.onChkAllContactsChangeFN(this, isJustUnchk); });
        $(".filter-dolj input.dolj").bind('customOnCheck', function () { self.onDoljCustomOnCheckFN(this); });
        $("#EdSalPerHF, #EdSalPerHT").keypress(function (e) { $("#RBShour").click(); });
        $("#EdSalPerWF, #EdSalPerWT").keypress(function (e) { $("#RBSweek").click(); });
        $("#EdSalPerMF, #EdSalPerMT").keypress(function (e) { $("#RBSmonth").click(); });
        self.init();
    }
    PageSearchVacs.prototype.init = function () {
        var self = this;
        $(".filter-label .filter-name").click(function (e) { self.onFilterNameClickFn(e, this); });
        Hinter.bind('.premium ._head', { animation: 'swing' });
        Hinter.bind('.vac-num span');
        var maxH = 0;
        $(".table-view .premium .border").each(function () {
            var $that = $(this);
            if ($that.outerHeight() > maxH)
                maxH = $that.outerHeight();
        }).each(function () {
            $(this).css({ minHeight: maxH + 'px' });
        });
        $('#CBcities').change(function () { }).multipleSelect({
            placeholder: "выберите город...",
            selectAllText: 'Все/снять выделение',
            allSelected: '',
            multipleWidthType: 120,
            minimumCountSelected: 2,
            countSelected: '# / %',
            noMatchesFound: 'Добавьте город =>',
        });
        self.CBcityMulti = new DDMultiAjax('#CBcities', { loadingObj: '#DiLoading',
            insertAllow: 0,
            width: 200,
            insertFirst: 1,
            loadingGIF: MainConfig.PATH_PIC + 'loading2.gif',
            labelText: 'Введите название города',
            btnAddHint: 'Добавить город',
            ajaxParams: { inputName: 'filter',
                url: MainConfig.AJAX_GET_CITYES,
                addParams: { idco: 0, limit: 20, getCity: 1 }
            },
            afterItemSelected: function (inData) { }
        });
    };
    PageSearchVacs.prototype.onChkAllContactsChangeFN = function (that, isJustUnchk) {
        var self = this;
        var isChecked = $(that).is(':checked');
        if (!isJustUnchk) {
            $(".filter-dolj input.dolj").each(function () {
                var $that = $(this);
                $that[0].checked = isChecked;
                $that.trigger('change', { noFireEvent: 1 });
            });
        }
    };
    PageSearchVacs.prototype.onDoljCustomOnCheckFN = function (that) {
        var self = this;
        var flag = 0;
        $(".filter-dolj input.dolj").each(function () {
            var $that = $(this);
            if (!$that[0].checked) {
                flag = 1;
                return false;
            }
        });
        var chkAll = $('#ChkAllContacts');
        if (flag)
            chkAll[0].checked = false;
        else
            chkAll[0].checked = true;
        chkAll.trigger('change', { isJustUnchk: 1 });
    };
    PageSearchVacs.prototype.onFilterNameClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        if ($that.hasClass('opened'))
            $that.closest('.filter-label').find('.filter-content').slideUp(200);
        else
            $that.closest('.filter-label').find('.filter-content').slideDown(400);
        $that.toggleClass('opened');
    };
    PageSearchVacs.prototype.onFilterApplyClickFn = function (e, that) {
        var self = this;
        var data = $('#F1Filter').serialize();
        if ($G_PAGE_VIEW == 1)
            var DiVacsBlock = $(".table-view");
        else
            var DiVacsBlock = $(".list-view");
        G_VARS.App.showLoading(DiVacsBlock, 0, { top: 1, pic: 3 });
        if ($G_PAGE_VIEW == 2)
            data += '&addmetro=1';
        $.post(MainConfig.AJAX_POST_GETVACS, data, function (data) {
            data = JSON.parse(data);
            DiVacsBlock.empty();
            $('body').animate({ scrollTop: 0 }, 500);
            if (data && data.length > 0 && !data.error) {
                var jj = 1;
                for (var ii in data) {
                    var val = data[ii];
                    if (ii == 'length')
                        continue;
                    if ($G_PAGE_VIEW == 1) {
                        var DiVacancy = $(".tab-view-tpl").clone();
                        DiVacancy.toggleClass('tab-view-tpl');
                        if (val.ispremium == 1)
                            DiVacancy.addClass('premium');
                        s1 = '';
                        for (var key in val.city) {
                            s1 = s1 + val.city[key] + ', ';
                        }
                        s1 = s1.substr(0, s1.length - 2);
                        DiVacancy.find('.city').append(s1);
                        s1 = '';
                        for (var key in val.post) {
                            s1 = s1 + val.post[key] + ', ';
                        }
                        s1 = s1.substr(0, s1.length - 2);
                        DiVacancy.find('h3 a').text(s1).attr('href', function (v, attr) { return attr + val.id; });
                        var s1 = '';
                        if (val.isman == '1')
                            s1 = 'Юноши';
                        if (val.isman == '1' && val.iswoman == '1')
                            s1 += ', ';
                        if (val.iswoman == '1')
                            s1 += 'Девушки';
                        if (val.isman == '1' || val.iswoman == '1')
                            DiVacancy.find('h3').after(s1 + "<br>");
                        DiVacancy.find('.istemp').text(val.istemp == '1' ? 'Постоянная' : 'Временная');
                        var s1 = '', flag = 0;
                        if (val.shour > 0) {
                            s1 = "<span class='nowrap'>".concat(val.shour, ' руб/час</span>');
                            flag = 1;
                        }
                        if (flag && val.sweek > 0)
                            s1 += ', ';
                        if (val.sweek > 0) {
                            s1 += "<span class='nowrap'>" + val.sweek + ' руб/неделю</span>';
                            flag = 1;
                        }
                        if (flag && val.smonth > 0)
                            s1 += ', ';
                        if (val.smonth > 0)
                            s1 += "<span class='nowrap'>" + val.smonth + ' руб/мес</span>';
                        if (flag || val.smonth)
                            DiVacancy.find('.payment').html(function (v, attr) { return attr + s1 + "<br>"; });
                        else
                            DiVacancy.find('.payment').remove();
                        DiVacancy.find('.bdate').text(val.bdate);
                        if (val.istemp == '1')
                            DiVacancy.find('.edate').remove();
                        else
                            DiVacancy.find('.edate span').text(val.edate);
                        DiVacancy.find('.company').text(val.coname);
                        DiVacancy.find('.date').text(val.crdate);
                        DiVacsBlock.append(DiVacancy);
                        if (jj % 2 == 0)
                            $("<div class='clear visible-sm'></div>").appendTo(DiVacsBlock);
                        if (jj % 3 == 0)
                            $("<div class='clear visible-md visible-lg'></div>").appendTo(DiVacsBlock);
                        jj++;
                    }
                    else {
                        var DiVacancy = $(".list-view-tpl").clone();
                        DiVacancy.toggleClass('list-view-tpl');
                        if (val.ispremium == 1)
                            DiVacancy.addClass('premium');
                        DiVacancy.find('.num').text(val.id);
                        DiVacancy.find('.crdate').text(val.crdate);
                        DiVacancy.find('.title').text(val.title);
                        var logo = val.logo == '' ? G_DEF_LOGO : val.logo;
                        DiVacancy.find('.company-logo img').attr('src', function (v, attr) { return attr + logo; });
                        s1 = '';
                        for (var key in val.post) {
                            s1 = s1 + val.post[key] + ', ';
                        }
                        s1 = s1.substr(0, s1.length - 2);
                        DiVacancy.find('h2').text(s1 + ' (' + val.id + ')').attr('href', function (v, attr) { return attr + val.id; });
                        var s1 = '';
                        if (val.isman == '1')
                            s1 = 'Юноши';
                        if (val.isman == '1' && val.iswoman == '1')
                            s1 += ', ';
                        if (val.iswoman == '1')
                            s1 += 'Девушки';
                        if (val.isman == '1' || val.iswoman == '1')
                            DiVacancy.find('.sexval').html(s1 + "<br>");
                        else
                            DiVacancy.find('.sex').remove();
                        var s1 = '', flag = 0;
                        if (val.shour > 0) {
                            s1 = "<span class='nowrap'>".concat(val.shour, ' руб/час</span>');
                            flag = 1;
                        }
                        if (flag && val.sweek > 0)
                            s1 += ', ';
                        if (val.sweek > 0) {
                            s1 += "<span class='nowrap'>" + val.sweek + ' руб/неделю</span>';
                            flag = 1;
                        }
                        if (flag && val.smonth > 0)
                            s1 += ', ';
                        if (val.smonth > 0)
                            s1 += "<span class='nowrap'>" + val.smonth + ' руб/мес</span>';
                        if (flag || val.smonth)
                            DiVacancy.find('.paymentval').html(function (v, attr) { return attr + s1 + "<br>"; });
                        else
                            DiVacancy.find('.payment').remove();
                        s1 = '';
                        for (var key in val.city) {
                            s1 = s1 + val.city[key] + ', ';
                        }
                        s1 = s1.substr(0, s1.length - 2);
                        DiVacancy.find('.city').html(s1);
                        s1 = '';
                        for (var key in val.metroes) {
                            s1 = s1 + val.metroes[key] + ', ';
                        }
                        s1 = s1.substr(0, s1.length - 2);
                        if (s1.length)
                            DiVacancy.find('.metroval').html(s1);
                        else
                            DiVacancy.find('.metro').remove();
                        DiVacancy.find('.duties').text(val.duties);
                        DiVacancy.find('.istemp').text(val.istemp == '1' ? 'Постоянная' : 'Временная');
                        DiVacancy.find('.bdate').text(val.bdate);
                        if (val.istemp == '1')
                            DiVacancy.find('.edate').remove();
                        else
                            DiVacancy.find('.edate span').text(val.edate);
                        DiVacancy.find('.company-logo .name').text(val.coname);
                        DiVacsBlock.append(DiVacancy);
                    }
                }
                var maxH = 0;
                $(".table-view .premium .border").each(function () {
                    var $that = $(this);
                    if ($that.outerHeight() > maxH)
                        maxH = $that.outerHeight();
                }).each(function () {
                    $(this).css({ minHeight: maxH + 'px' });
                });
            }
            else {
                DiVacsBlock.append("<div class='nodata'>Данным условиям нет подходящих вакансий</div>");
            }
        })
            .always(function () {
            G_VARS.App.hideLoading();
        });
    };
    return PageSearchVacs;
}());
var PageRegister = (function (_super) {
    __extends(PageRegister, _super);
    function PageRegister() {
        _super.call(this);
        this.FLAG_STOP = 0;
        var self = this;
        $("#F1registerEmpl").on('submit', function (e) { self.onFormSubmit(e, this); });
        $("#F1registerAppl").on('submit', function (e) { self.onFormSubmit(e, this); });
        self.init();
    }
    PageRegister.prototype.init = function () {
        var self = this;
        $("#BtnVkreg").click(function (e) {
            e.preventDefault();
            self.VKserv = new VKservice();
            self.VKserv.login(MainConfig.PAGE_REGISTER_VK);
        });
        self.FBserv = new FBservice();
        $("#BtnFbreg").click(function (e) {
            e.preventDefault();
            self.FBserv.login(MainConfig.PAGE_REGISTER_FB);
        });
        $('#EdBdate').datepicker({
            format: "dd.mm.yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            language: "ru"
        });
        $('#EdBdate').datepicker('update', new Date(moment($('#EdBdate').val(), 'DD.MM.YYYY')));
        self.bindFiltersFn();
    };
    PageRegister.prototype.onF1registerSubmitFn = function (e, that) {
        var self = this;
        var mess = '';
        var flagError = 0;
        // var item = $("#EdName");
        // var val = item.val();
        // if (val == '') {
        //     item = self.options.elem;
        //     item.addClass('field--warning');
        //     // flagError = 0;
        //     // mess = 'Необходимо заполнить название компании';
        // }
         if (!flagError) {
            var item = $("#EdType");
            var val = item.val();
            if (val == '') {
                flagError = 1;
                mess = 'Необходимо выбрать тип компании';
            }
        }
        if (!flagError) {
            var item = $("#EdEmail");
            var val = item.val();
            if (val == '') {
                flagError = 1;
                mess = 'Необходимо заполнить электронный адрес';
            }
        }
        if (!flagError) {
            var item = $("#EdPass");
            var val = item.val();
            if (val == '') {
                flagError = 1;
                mess = 'Необходимо заполнить пароль';
            }
        }
        if (!flagError) {
            var item = $("#EdPass");
            var val = item.val();
            if (val != $("#EdPassRep").val()) {
                flagError = 1;
                mess = 'Пароль и его подтверждение не совпадают';
            }
        }
        if (flagError) {
            self.FLAG_STOP = 1;
            $('body').stop().animate({ scrollTop: item.offset().top - 30 + 'px' }, 500, function () {
                var msgBox = $('.error-hint-box');
                msgBox.text(mess).css({ left: item.offset().left, top: item.offset().top + item.outerHeight() + 10 });
                msgBox.stop().fadeIn(400);
                item.removeClass('field--success');
                item.addClass('field--warning');
                item.focus();
                item.on('blur', function () {
                    self.FLAG_STOP || msgBox.fadeOut(200);
                    // item.removeClass('field--warning');
                    $(this).off('blur');
                });
                self.FLAG_STOP = 1;
            });
            e.preventDefault();
        }
        else {
        }
    };
    return PageRegister;
}(Page));
var PageApplicantProfile = (function (_super) {
    __extends(PageApplicantProfile, _super);
    function PageApplicantProfile() {
        _super.call(this);
        var self = this;
        self.CommentAdditor = new CommentAdditor();
        $('#BtnComment a').click(function (e) { self.onCommentClick(e); });
        HiddenText.init({ wrapper: '.comment .text-wrapp',
            content: '.text',
            openText: 'Смотреть полностью',
            closeText: 'Свернуть',
            hiddenImg: ''
        });
    }
    PageApplicantProfile.prototype.onCommentClick = function (e) {
        var self = this;
        if (FLAG_MOBILE)
            return 1;
        e.preventDefault();
        self.CommentAdditor.open({ getUrl: '/test/ajax/ajax-applicant-profile-own-comment.php',
            formAction: '/test/ajax/ajax-applicant-profile-own-comment.php',
        });
    };
    return PageApplicantProfile;
}(Page));
var PageApplicantProfileOwn = (function (_super) {
    __extends(PageApplicantProfileOwn, _super);
    function PageApplicantProfileOwn() {
        _super.call(this);
        var self = this;
    //          if(G_VARS.Modal == 1) {
    //         $(function (e) { self.onInformationClic(e, this); }); }
    // };
        self.CommentAdditor = new CommentAdditor();
        $('#BtnComment a').click(function (e) { self.onCommentClick(e); });
        HiddenText.init({ wrapper: '.comment-box .text-wrapp',
            content: '.text',
            openText: 'Смотреть полностью',
            closeText: 'Свернуть',
            openBtnClass: 'look-full-comm',
            hiddenImg: ''
        });
        $(".affective-perc").hover(function (e) {
            self.showHintBox($(this), { content: "Чтобы повысить эффективность своего профиля: заполните как можно больше полей в своём профиле. Для этого нажмите кнопку редактирования профиля",
                posFunc: function (sourceObj, hintBox) {
                    hintBox.removeClass().addClass('hint-box');
                    if ($(window).width() > 768 && $(window).width() < 1200)
                        hintBox.css({ left: sourceObj.offset().left + (sourceObj.outerWidth() - hintBox.outerWidth()) / 2 })
                            .addClass('top-center');
                    else
                        hintBox.css({ left: sourceObj.offset().left + sourceObj.outerWidth() - hintBox.outerWidth() });
                    hintBox.css({ top: sourceObj.offset().top + sourceObj.height() + 20 });
                }
            });
        }, function () {
            self.showHintBox(null, null, 0);
        });
        self.init();
    }
    PageApplicantProfileOwn.prototype.init = function () {
        var self = this;
    //     if(G_VARS.Modal === 1) {
    //         $(function (e) { self.onInformationClick(e, this); }); }
    // };
        $('.user-info-blocks').matchHeight({ property: 'min-height' });
        $(".js-btn-invite a").click(function (e) { self.onInviteBtnClick(e, this); });
    };
    PageApplicantProfileOwn.prototype.onInformationClic = function (e, that) {
            var self = this;
            var form = $(".Infos").clone();
            ModalWindow.open({ content: form, action: { active: 0 }, bgIsCloseBtn: 0, position: 'absolute', context: 'body',
                afterOpen: function () { $("#DiSiteWrapp").css({ overflow: "hidden" }); },
                afterClose: function () { $("#DiSiteWrapp").css({ overflow: "" }); }
            });
        };
    PageApplicantProfileOwn.prototype.onCommentClick = function (e) {
        var self = this;
        if (FLAG_MOBILE)
            return 1;
        e.preventDefault();
        self.CommentAdditor.open({ getUrl: '/test/ajax/ajax-applicant-profile-own-comment.php',
            formAction: '/test/ajax/ajax-applicant-profile-own-comment.php',
        });
    };
    PageApplicantProfileOwn.prototype.showHintBox = function (inObj, props, state) {
        if (state === void 0) { state = 1; }
        var hintBox = $(".hint-box");
        var hintBoxTri = $(".hint-box::before");
        if (state) {
            var defProps = { content: '',
                width: '300px',
                positon: 'bottom',
                bgcolor: '#abb820',
                color: '#fff',
                fontSize: '13px',
                triangle: {
                    bottom: { right: 10 }
                },
                posFunc: ''
            };
            props.triangle && $.extend(defProps.triangle.bottom, props.triangle.bottom);
            $.extend(defProps.triangle, props.triangle);
            $.extend(defProps, props);
            props = defProps;
            hintBox.find('span').text(props.content);
            hintBox.css({ width: props.width,
                background: props.bgcolor,
                color: props.color,
                fontSize: props.fontSize
            });
            hintBoxTri.css({ background: props.bgcolor });
            if (props.positon == 'bottom' || props.positon == 'top') {
            }
            else {
            }
            if (props.posFunc) {
                props.posFunc(inObj, hintBox);
            }
            else {
                if (props.positon == 'bottom') {
                    hintBox.css({
                        left: inObj.offset().left + inObj.outerWidth() - hintBox.outerWidth(),
                        top: inObj.offset().top + inObj.height() + 20
                    });
                }
                else {
                }
            }
            hintBox.fadeIn(400);
        }
        else {
            hintBox.fadeOut(200);
        }
    };
    PageApplicantProfileOwn.prototype.onInviteBtnClick = function (e, that) {
        var self = this;
        e.preventDefault();
        $.get(MainConfig.AJAX_GET_GETVACANCIES, {}, function (data) {
            var error = 0;
            var message = 'smth error';
            try {
                if (Object.keys(data.vacs).length > 0) {
                    var invForm = $($("#TplInvVacs").text());
                    var select = invForm.find('select');
                    for (var ii in data.vacs) {
                        var val = data.vacs[ii];
                        var option = $('<option/>');
                        option.val(val.id);
                        option.text(val.title + " (" + val.id + ")");
                        select.append(option);
                    }
                    ModalWindow.open({ content: invForm, action: { btnTitle: $("#TplInvVacs").data('btn'), onClick: function () { self.doInvite(); } } });
                }
                else {
                    var invForm = $($("#TplInvNoVacs").text());
                    ModalWindow.open({ content: invForm });
                }
            }
            catch (e) {
                var code = e.code;
                message = e.message;
                error = 1;
            }
        }, 'json');
    };
    PageApplicantProfileOwn.prototype.doInvite = function () {
        var self = this;
        ModalWindow.loadingOn();
        var props = { id: $("#CbVacs").val(), idPromo: G_VARS.App.customProps.idPromo };
        var message = _t('inviteError');
        $.post(MainConfig.AJAX_POST_INVITE, props, function (data) {
            var error = 0;
            try {
                if (data.error == 100) {
                    var invForm = $($("#TplInvSuccess").text());
                    invForm.text(data.message);
                    ModalWindow.redraw({ content: invForm });
                }
                else if (data.error < 0) {
                    throw new CustomError(data.message, -102, data.error);
                }
                else {
                    throw new CustomError(message, -101);
                }
            }
            catch (e) {
                var code = e.code;
                message = e.message;
                e.retCode && (code += ':' + e.retCode);
                error = 1;
            }
            if (error) {
                console.warn('E', code);
                var invForm = $($("#TplInvSuccess").text());
                invForm.text(message);
                ModalWindow.redraw({ content: invForm });
            }
        }, 'json')
            .fail(function () {
            var invForm = $($("#TplInvSuccess").text());
            invForm.text(message);
            ModalWindow.redraw({ content: invForm });
        });
    };
    return PageApplicantProfileOwn;
}(Page));
var PageApplicantComments = (function (_super) {
    __extends(PageApplicantComments, _super);
    function PageApplicantComments() {
        _super.call(this);
        var self = this;
        self.CommentAdditor = new CommentAdditor();
        $('.btn-comment a').click(function (e) { self.onCommentClick(e); });
    }
    PageApplicantComments.prototype.onCommentClick = function (e) {
        var self = this;
        e.preventDefault();
        self.CommentAdditor.open({ getUrl: '/test/ajax/ajax-applicant-profile-own-comment.php',
            formAction: '/test/ajax/ajax-applicant-profile-own-comment.php',
        });
    };
    return PageApplicantComments;
}(Page));
var PageCompanyProfileOwn = (function (_super) {
    __extends(PageCompanyProfileOwn, _super);
    function PageCompanyProfileOwn() {
        _super.call(this);
        var self = this;
        HiddenText.init({ wrapper: '.comment-box .text-wrapp',
            openBtnClass: 'look-full-comm',
            content: '.text',
            openText: 'Смотреть полностью',
            closeText: 'Свернуть',
            hiddenImg: ''
        });
        self.init();
    }
    PageCompanyProfileOwn.prototype.init = function () {
        var self = this;
        if(G_VARS.Modal == 1) {
            $(function (e) { self.onInformationClick(e, this); }); }
    };
    return PageCompanyProfileOwn;
}(Page));
PageCompanyProfileOwn.prototype.onInformationClick = function (e, that) {
            var self = this;
            var form = $(".Info").clone();
            ModalWindow.open({ content: form, action: { active: 0 }, bgIsCloseBtn: 0, position: 'absolute', context: 'body',
                afterOpen: function () { $("#DiSiteWrapp").css({ overflow: "hidden" }); },
                afterClose: function () { $("#DiSiteWrapp").css({ overflow: "" }); }
            });
        };
var PageApplicantList = (function () {
    function PageApplicantList() {
        this.doljChoice = 19;
        this.cityMoskowChoice = 1001;
        this.cityPiterChoice = 1002;
        this.CBcityMulti = {};
        var self = this;
        $(".filter-dolj input.dolj").bind('customOnCheck', function () { self.onDoljCustomOnCheckFN(this); });
        $(".filter-open a").click(function (e) { self.onFilterOpenClickFn(e, this); });
        self.init();
    }
    PageApplicantList.prototype.init = function () {
        var self = this;
        var cbParams = {
            selectAllText: 'Выбрать все',
            countSelected: '# из %',
            allSelected: 'Выбраны все',
        };
        $('#CBcities').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, { placeholder: "выберите город...",
            allSelected: '',
            noMatchesFound: 'Добавьте город =>',
            onUncheckAll: function (p1) { self.onCBcityChangeFn(p1); },
            onClick: function (p1) { self.onCBcityChangeFn(p1); },
            onCheckAll: function (p1) { self.onCBcityChangeFn(p1, 1); },
        }));
        self.CBcityMulti = new DDMultiAjax('#CBcities', { loadingObj: '#DiLoading',
            insertAllow: 0,
            width: 180,
            insertFirst: 1,
            loadingGIF: MainConfig.PATH_PIC + 'loading2.gif',
            labelText: 'Введите название города',
            btnAddHint: 'Добавить город',
            ajaxParams: { inputName: 'filter',
                url: MainConfig.AJAX_GET_CITYES,
                addParams: { idco: 0, limit: 20, getCity: 1 }
            },
            afterItemSelected: function (inData) { self.onCityChangeFn(inData); }
        });
        $('.metro-block select').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, { placeholder: "выберите метро...",
            filter: true
        }));
        $('#CBdolj').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, { placeholder: "выберите должность...",
            filter: true,
            onUncheckAll: function (p1) { self.onCBdoljChangeFn(p1); },
            onClick: function (p1) { self.onCBdoljChangeFn(p1); },
            onCheckAll: function (p1) { self.onCBdoljChangeFn(p1, 1); },
        }));
    };
    PageApplicantList.prototype.onCBcityChangeFn = function (e, that) {
        var self = this;
        var items = $('#CBcities').multipleSelect('getSelects');
        var itemsText = $('#CBcities').multipleSelect('getSelects', 'text');
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
                $(this).slideUp(200, function () { $(this).find('select').prop('disabled', true); });
            else
                $(this).slideDown(400, function () { $(this).find('select').prop('disabled', false); });
        });
    };
    PageApplicantList.prototype.onCityChangeFn = function (inData) {
        var self = this;
        var idcity = inData ? inData[0] : 0;
        var flag = 0;
        for (var ii in self.CBcityMulti.ajaxRetData) {
            if (self.CBcityMulti.ajaxRetData[ii].id == idcity && self.CBcityMulti.ajaxRetData[ii].ismetro == '1') {
                flag = 1;
                break;
            }
        }
        if (flag && idcity > 0 && !G_VARS.appcache['metro' + idcity]) {
            $.get(MainConfig.AJAX_GET_METRO, { idcity: idcity }, function (data) {
                data = JSON.parse(data);
                G_VARS.appcache['metro' + idcity] = data;
                var metroBlock = $('.metro-tpl').clone();
                metroBlock.attr('data-id', idcity).toggleClass('metro-tpl metro-block');
                metroBlock.hide();
                var label = metroBlock.find('label');
                var itmID = label.attr('for') + idcity;
                label.attr('for', itmID);
                label.text(label.text() + ''.concat(' (', inData[1], '):'));
                var select = metroBlock.find('select');
                select.attr('id', itmID);
                select.attr('name', 'metro[]');
                for (var ii in data) {
                    var val = data[ii];
                    $("<option value='".concat(val.id, "'>", val.name, "</option>"))
                        .appendTo(select);
                }
                $("#DiMetroesBlock").append(metroBlock);
                metroBlock.slideDown(400);
                select.multipleSelect({
                    placeholder: "выберите метро...",
                    selectAllText: 'Выбрать все/снять выделение',
                    allSelected: 'Выбраны все',
                    filter: true,
                    countSelected: '# / %'
                });
            }).always(function () {
            });
        }
    };

    PageApplicantList.prototype.onCBdoljChangeFn = function (props, all) {
        var self = this;
        var items = $('#CBdolj').multipleSelect('getSelects');
        if ($.inArray(self.doljChoice + '', items) > -1) {
            $(".self-dolj").slideDown(400, function () { $(this).css({ display: 'block' }); });
        }
        else {
            $(".self-dolj").slideUp(200);
        }
    };
    PageApplicantList.prototype.onFilterOpenClickFn = function (e, that) {
        var self = this;
        e.preventDefault();
        var $that = $(that);
        $(".filter").slideToggle(400);
        $that.closest('div').toggleClass('opened');
    };
    return PageApplicantList;
}());
var PageCompanyList = (function () {
    function PageCompanyList() {
        this.doljChoice = 19;
        this.cityMoskowChoice = 1001;
        this.cityPiterChoice = 1002;
        this.CBcityMulti = {};
        var self = this;
        $(".filter-open a").click(function (e) { self.onFilterOpenClickFn(e, this); });
        $(".btn-rate-details a").on('click', function (e) { self.onRateDetailClickFn(e, this); });
        self.init();
    }
    PageCompanyList.prototype.init = function () {
        var self = this;
        var cbParams = {
            selectAllText: 'Выбрать все/снять выделение',
            countSelected: '# из %',
            allSelected: 'Выбраны все',
        };
        $('#CBcities').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, { placeholder: "выберите город...",
            allSelected: '',
            noMatchesFound: 'Добавьте город =>',
            onUncheckAll: function (p1) { self.onCBcityChangeFn(p1); },
            onClick: function (p1) { self.onCBcityChangeFn(p1); },
            onCheckAll: function (p1) { self.onCBcityChangeFn(p1, 1); }
        }));
        self.CBcityMulti = new DDMultiAjax('#CBcities', { loadingObj: '#DiLoading',
            insertAllow: 0,
            width: 180,
            insertFirst: 1,
            loadingGIF: MainConfig.PATH_PIC + 'loading2.gif',
            labelText: 'Введите название города',
            btnAddHint: 'Добавить город',
            ajaxParams: { inputName: 'filter',
                url: MainConfig.AJAX_GET_CITYES,
                addParams: { idco: 0, limit: 20, getCity: 1 }
            },
            afterItemSelected: function (inData) { }
        });
        $('#CBtype').change(function () { }).multipleSelect(CommFuncs.merge(cbParams, { placeholder: "выберите тип...",
            minimumCountSelected: 2
        }));
    };
    PageCompanyList.prototype.onCBcityChangeFn = function (props, all) {
        var self = this;
        var items = $('#CBcities').multipleSelect('getSelects');
    };
    PageCompanyList.prototype.onCBdoljChangeFn = function (props, all) {
        var self = this;
        var items = $('#CBdolj').multipleSelect('getSelects');
        if ($.inArray(self.doljChoice + '', items) > -1) {
            $(".self-dolj").slideDown(400, function () { $(this).css({ display: 'block' }); });
        }
        else {
            $(".self-dolj").slideUp(200);
        }
    };
    PageCompanyList.prototype.onFilterOpenClickFn = function (e, that) {
        var self = this;
        e.preventDefault();
        var $that = $(that);
        $(".filter").slideToggle(400);
        $that.closest('div').toggleClass('opened');
    };
    PageCompanyList.prototype.onRateDetailClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        G_VARS.App.showLoading($that, 0, { offsetLeft: $that.outerWidth() + 5 });
        $.get(MainConfig.AJAX_GET_GETEMPLRATE, { id: $that.data('id') }, function (data) {
            data = JSON.parse(data);
            var $table = $that.closest(".rate-block").find('table.rate');
            var trTPL = $table.find('thead tr');
            for (var ii in data.rate.pointRate) {
                var val = data.rate.pointRate[ii];
                var tr = trTPL.clone().removeClass('rate-tpl');
                tr.find('.val .num').text(val[0] - val[1]);
                tr.find('.val .good').text(val[0]);
                tr.find('.val .bad').text(val[1]);
                tr.find('.progress .progr-line').addClass(val[0] > val[1] ? 'progress-green' : 'progress-red')
                    .css({ width: val[0] - val[1] == 0 ? 0 : Math.abs(val[0] - val[1]) * 100 / data.rate.maxPointRate + '%' });
                tr.find('.text').text(data.rate.rateNames[ii]);
                $table.find('tbody').append(tr);
            }
            $table.hide().removeClass('.hide-rate').slideDown(400);
            $that.fadeOut(200);
        })
            .always(function () {
            G_VARS.App.hideLoading();
        });
    };
    PageCompanyList.prototype.onFilterApplyClickFn = function (e, that) {
        var self = this;
        var data = $('#F1Filter').serialize();
        var DiEmplBlock = $(".list-view");
        G_VARS.App.showLoading(DiEmplBlock, 0, { top: 1, pic: 3 });
        $.post(MainConfig.AJAX_POST_GETEMPLS, data, function (data) {
            data = JSON.parse(data);
            0 || console.info('data', data);
            DiEmplBlock.empty();
            $('body').animate({ scrollTop: 0 }, 500);
            if (data && data.length > 0 && !data.error) {
                var jj = 1;
                for (var ii in data['empls']) {
                    var val = data['empls'][ii];
                    if (ii == 'length')
                        continue;
                    var DiEmpl = $(".empl-item-tpl").clone();
                    DiEmpl.toggleClass('empl-item-tpl');
                    DiEmpl.find('h2 small i').text(val.id);
                    DiEmpl.find('h2 a').text(val.name);
                    DiEmpl.find('.com-rate .pos').text(val.rate);
                    DiEmpl.find('.com-rate .neg').text(val.rate_neg);
                    DiEmpl.find('.btn-rate-details a').attr('data-id', val.id_user);
                    DiEmpl.find('.profile-link').attr('href', function (v, attr) { return attr + val.id; });
                    var logo = !val.logo || val.logo == '' ? G_VARS.DEF_LOGO_EMPL : val.logo;
                    DiEmpl.find('.company-logo img').attr('src', function (v, attr) { return attr + logo; });
                    s1 = '';
                    for (var key in val.city) {
                        s1 = s1 + val.city[key] + ', ';
                    }
                    s1 = s1.substr(0, s1.length - 2);
                    DiEmpl.find('.city').html(s1);
                    s1 = '';
                    for (var key in val.metroes) {
                        s1 = s1 + val.metroes[key] + ', ';
                    }
                    s1 = s1.substr(0, s1.length - 2);
                    if (s1 == '')
                        DiEmpl.find('.metroes').remove();
                    else
                        DiEmpl.find('.metroes small').html(s1);
                    DiEmpl.find('.cotype small').text(val.tname);
                    DiEmplBlock.append(DiEmpl);
                    DiEmpl.find(".btn-rate-details a").on('click', function (e) { self.onRateDetailClickFn(e, this); });
                }
                var maxH = 0;
                $(".table-view .premium .border").each(function () {
                    var $that = $(this);
                    if ($that.outerHeight() > maxH)
                        maxH = $that.outerHeight();
                }).each(function () {
                    $(this).css({ minHeight: maxH + 'px' });
                });
            }
            else {
                DiEmplBlock.append("<div class='nodata'>Данным условиям нет подходящих вакансий</div>");
            }
        })
            .always(function () {
            G_VARS.App.hideLoading();
        });
    };
    return PageCompanyList;
}());
var PageCompanyServices = (function () {
    function PageCompanyServices() {
        this.doljChoice = 19;
        this.cityMoskowChoice = 1001;
        this.cityPiterChoice = 1002;
        var self = this;
        $(".menu a").click(function (e) { self.onMenuClickFn(e, this); });
        self.init();
    }
    PageCompanyServices.prototype.init = function () {
        var self = this;
    };
    PageCompanyServices.prototype.onMenuClickFn = function (e, that) {
        var self = this;
        e.preventDefault();
        var hash = $(that).prop("hash").replace(/#/, '');
        $('body').stop().animate({ scrollTop: $("a[name=" + hash + "]").offset().top - 20 + 'px' }, 500);
    };
    return PageCompanyServices;
}());
var PageServices = (function (_super) {
    __extends(PageServices, _super);
    function PageServices() {
        _super.call(this);
        var self = this;
        self.init();
    }
    PageServices.prototype.init = function () {
        var self = this;
        $(".btn-same-adr button").click(function (e) { self.onSameAdrClickFn(e, this); });
        $(".order-btn a").click(function (e) { self.onOrderServiceClickFn(e, this); });
        $(".btn-order-prommucard button").click(function (e) { $(".form").slideDown(400); $(this).parent().slideUp(200); });
        var Upli = new Uploaduni();
        $("#UplImg").change(function () { self.onUploadFileSetFn(this, Upli); });
        $(".btn-upload button").click(function (e) {
            $("#F2upload").find('.message').text('');
            var control = $("#UplImg");
            control.replaceWith(control.clone(true));
            $("#UplImg").trigger('click');
        });
        Upli.init({ uploadConnector: MainConfig.AJAX_POST_UPLOADUNI,
            scope: 'services',
            imgBlockTmpl: 'doc-scan-tpl',
            imgsWrapper: '#DiImgs',
            lnktoimg: 'orig',
            uploadForm: '#F2upload',
            messageBlock: '.message',
            loadingBLock: '.loading-ico'
        });
        Upli.setFiles(G_VARS.uniFiles);
        $(".btn-order").click(function (e) { e.preventDefault(); $("#F1cardOrder").submit(); });
        $("#F1cardOrder").submit(function (e) {
            var files = Upli.getFiles();
            for (var ii in files) {
                var val = files[ii];
                $('<input type="hidden" name="files[]" value="' + ii + '"/>').appendTo('#F1cardOrder');
            }
        });
        self.bindFiltersFn();
    };
    PageServices.prototype.onOrderCreateFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var fc = new FormCheckers();
        var ret = fc.FormSubmit({ event: e,
            form: $that.closest('form'),
            justReturn: 1
        });
        e.preventDefault();
        if (ret) {
            var props = $that.closest('form').serialize();
            $.post(MainConfig.AJAX_POST_CREATESERVICEORDER, props, function (data) {
                data = JSON.parse(data);
                var itm = $(".order-success-tpl").clone();
                itm.toggleClass('order-success-tpl tmpl order-success');
                ModalWindow.redraw({ content: itm, action: { active: 1 } });
            });
        }
    };
    PageServices.prototype.onSameAdrClickFn = function (e, that) {
        var self = this;
        $("#EdAddr").val($("#EdRegaddr").val());
        $("#CbCountry").val($("#CbRegcountry").val());
    };
    PageServices.prototype.onOrderServiceClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var id = $that.parent().data('id');
        e.preventDefault();
        $.get(MainConfig.AJAX_GET_GETSERVICE, { id: id }, function (data) {
            data = JSON.parse(data);
            var itm = $(".form-order-tpl").clone();
            itm.attr('data-title', "&laquo;" + data.name + "&raquo;");
            itm.find('#HiId').val(id);
            itm.toggleClass('form-order-tpl tmpl form-order');
            ModalWindow.open({ content: itm, action: { active: 0 } });
            itm.find('.btn-order-create').click(function (e) { self.onOrderCreateFn(e, this); });
        });
    };
    PageServices.prototype.onOrderPrommucardClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var id = $that.parent().data('id');
        e.preventDefault();
    };
    PageServices.prototype.onUploadFileSetFn = function (that, inService) {
        var self = this;
        inService.upload(that);
    };
    return PageServices;
}(Page));
var PageLogin = (function (_super) {
    __extends(PageLogin, _super);
    function PageLogin() {
        _super.call(this);
        var self = this;
    }
    return PageLogin;
}(Page));
var PageVacancyView = (function () {
    function PageVacancyView() {
        var self = this;
        self.VacResponses = new VacResponses();
        $(".btn-show-contacts a").click(function (e) { self.onShowContactsCLick(e, this); });
        $(".btn-response a").click(function (e) { self.onResponseClickFn(e, this); });
        self.init();
    }
    PageVacancyView.prototype.init = function () {
        var self = this;
        if ($(window).innerWidth() < 768)
            $("#DiCompInfo").attr('class', '').appendTo('#DiComp');
        var QueryString = CommFuncs.parseUrl();
        if (QueryString['tab'])
            $('body').stop().animate({ scrollTop: $(".tabs-panel").offset().top - 20 + 'px' }, 10);
        Hinter.bind(".btn-edit-vac a");
        $(".controls .view").click(function (e) { self.onChangeStatusCLickFn(e, this, 1); });
        $(".controls .cancel").click(function (e) { self.onChangeStatusCLickFn(e, this, 3); });
        $(".controls .apply").click(function (e) { self.onChangeStatusCLickFn(e, this, 4); });
    };
    PageVacancyView.prototype.onShowContactsCLick = function (e, that) {
        var self = this;
        var $that = $(that);
        var DiContacts = $(".contacts-block");
        e.preventDefault();
        $.get(MainConfig.AJAX_GET_GETEMPLCONTACTS, { id: G_VARS.eid, idvac: G_VARS.idvac }, function (data) {
            data = JSON.parse(data);
            if (data.mob)
                DiContacts.find('.mob').text(data.mob);
            if (data.addmob)
                DiContacts.find('.addmob').text(', ' + data.addmob);
            if (!data.addmob && !data.mob)
                $(".tel-block").remove();
            DiContacts.find('.email').text(data.email);
            DiContacts.slideDown(400);
            $that.fadeOut(200);
        });
    };
    PageVacancyView.prototype.onChangeStatusCLickFn = function (e, that, status) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        if (status == 4) {
            if (!confirm('Подтвредите действие'))
                return 0;
        }
        $.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, { idres: $that.closest('.controls').data('sid'), s: status }, function (data) {
            data = JSON.parse(data);
            if (!data.error) {
                if (status == 1) {
                    $that.closest('tr').fadeOut(300, function () { $(this).remove(); });
                }
                else if (status == 3) {
                    $that.closest('tr').fadeOut(300, function () { $(this).remove(); });
                }
                else if (status == 4) {
                    var controls = $that.closest('.controls');
                    $that.closest('tr').removeClass('-new');
                    controls.find('.view').parent().fadeOut(300, function () { $(this).remove(); });
                    controls.find('.cancel').parent().fadeOut(300, function () { $(this).remove(); });
                    $that.parent().fadeOut(300, function () {
                        $(this).remove();
                        controls.find('.status').hide().removeClass('hide').fadeIn(400);
                    });
                }
                var countsArr = [0];
                for (var ii in data['counts']) {
                    var val = data['counts'][ii];
                    ii = parseInt(ii);
                    if (CommFuncs.inArray(ii, [0, 4, 5, 6, 7]))
                        countsArr[0] += val;
                    else
                        countsArr[ii] = val;
                }
                $(".tabs-wrapp .tab1 span").text("(" + countsArr[0] + ")");
                $(".tabs-wrapp .tab2 span").text("(" + countsArr[3] + ")");
                $(".tabs-wrapp .tab3 span").text("(" + countsArr[1] + ")");
            }
        });
    };
    PageVacancyView.prototype.onResponseClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        G_VARS.App.showLoading($that, 0, { align: 'center', pic: 1, variant: 2 });
        self.VacResponses.doResponse(G_VARS.idVac, function (data) {
            G_VARS.App.hideLoading();
            if (!data.error) {
                $(".btn-response").fadeOut(200, function () { $(this).remove(); });
                $(".resp-message").text(data.message);
            }
        });
    };
    return PageVacancyView;
}());
var ResponsesCompany = (function () {
    function ResponsesCompany() {
        var self = this;
        self.VacResponses = new VacResponses();
        $(".controls .view").click(function (e) { self.onChangeStatusCLickFn(e, this, 1); });
        $(".controls .cancel").click(function (e) { self.onChangeStatusCLickFn(e, this, 3); });
        $(".controls .apply").click(function (e) { self.onChangeStatusCLickFn(e, this, 4); });
        self.init();
    }
    ResponsesCompany.prototype.init = function () {
        var self = this;
        Hinter.bind('.js-hashint');
    };
    ResponsesCompany.prototype.onChangeStatusCLickFn = function (e, that, status) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        if (status == 3 || status == 4) {
            if (!confirm('Подтвредите действие'))
                return 0;
        }
        $.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, { idres: $that.closest('.controls').data('sid'), s: status }, function (data) {
            data = JSON.parse(data);
            if (!data.error) {
                if (status == 1) {
                    $that.closest('.row').removeClass('-new');
                    $that.fadeOut(300, function () { $(this).parent().remove(); });
                }
                else if (status == 3) {
                    $that.closest('.row').fadeOut(300, function () { $(this).remove(); });
                }
                else if (status == 4) {
                    var controls = $that.closest('.controls');
                    $that.closest('.row').removeClass('-new');
                    controls.find('.view').parent().fadeOut(300, function () { $(this).remove(); });
                    controls.find('.cancel').parent().fadeOut(300, function () { $(this).remove(); });
                    $that.parent().fadeOut(300, function () {
                        $(this).remove();
                        controls.find('.status').hide().removeClass('hide').fadeIn(400);
                    });
                }
            }
        });
    };
    return ResponsesCompany;
}());
var ResponsesApplic = (function () {
    function ResponsesApplic() {
        var self = this;
        self.VacResponses = new VacResponses();
        $(".controls .js-cancel").click(function (e) { self.onChangeStatusCLickFn(e, this, 2); });
        $(".controls .apply").click(function (e) { self.onChangeStatusCLickFn(e, this, 5); });
        self.init();
    }
    ResponsesApplic.prototype.init = function () {
        var self = this;
        Hinter.bind('.js-hashint');
    };
    ResponsesApplic.prototype.onChangeStatusCLickFn = function (e, that, status) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        if ($.inArray(status, [2, 5]) > -1) {
            if (!confirm('Подтвредите действие'))
                return 0;
        }
        $.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, { idres: $that.closest('.controls').data('sid'), s: status }, function (data) {
            data = JSON.parse(data);
            if (!data.error) {
                if (status == 5) {
                    var controls = $that.closest('.controls');
                    $that.closest('.row').removeClass('-new');
                    $that.parent().fadeOut(300, function () {
                        controls.find('.status').removeClass('hint').attr('title', '').text($that.data('status'));
                        $(this).remove();
                    });
                    ModalWindow.open({ content: data.message });
                    controls.find(".js-cancel").fadeOut(200, function () { $(this).remove(); });
                    controls.find(".js-applied").fadeIn(400);
                }
                else if (status == 2) {
                    var parent = $that.closest('.row');
                    parent.fadeOut(300, function () { parent.remove(); });
                }
            }
        });
    };
    return ResponsesApplic;
}());
var SetrateApplic = (function (_super) {
    __extends(SetrateApplic, _super);
    function SetrateApplic() {
        _super.call(this);
        var self = this;
        $(".rate-buttons-block a").click(function (e) { self.onSetRateClick(e, this, 5); });
        $("#F1rate").submit(function (e) { self.onFormSubmit(e, this); });
    }
    SetrateApplic.prototype.onSetRateClick = function (e, that, status) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        $that.closest('tr').find('.rate-value').val($that.data('val'));
        $that.parent().find('.active').removeClass('active');
        $that.parent().find('.val').text($that.data('title'));
        $that.parent().find('.val').removeClass('plus minus').addClass($that.attr('class'));
        $that.addClass('active');
    };
    return SetrateApplic;
}(Page));
var PageImApplicant = (function () {
    function PageImApplicant() {
        var self = this;
        self.init();
    }
    PageImApplicant.prototype.init = function () {
    };
    PageImApplicant.prototype.loadChatsInfo = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        $(".attach input").click();
    };
    return PageImApplicant;
}());
var Feedback = (function (_super) {
    __extends(Feedback, _super);
    function Feedback() {
        _super.call(this);
        var self = this;
        $("#F1feedback").submit(function (e) { self.onFormSubmit(e, this); });
    }
    return Feedback;
}(Page));
var CommonPages = (function (_super) {
    __extends(CommonPages, _super);
    function CommonPages() {
        var self = this;
        $("#DiContent .content-block p").click(function (e) { self.onQuestClick(e, this); });
    }
    CommonPages.prototype.onQuestClick = function (e, that) {
        var self = this;
        var $that = $(that);
        var wrapper = $("#DiContent .content-block");
        wrapper.find('div').slideUp(200);
        setTimeout(function () {
            $that.next().slideDown(400);
        }, 300);
    };
    return CommonPages;
}(Page));
var FLAG_DEBUG = 1;
var G_VARS = {
    appcache: { metro: {} },
    uniq: 1,
    eid: 0,
    idVac: 0,
    countryID: 0,
    country: 0,
    cityWmetro: {},
    App: IndServ,
    userCities: {},
    userMetro: {},
    userWdays: {},
    DEF_LOGO_EMPL: '',
    locale: G_LOCALE,
};
G_VARS.App = new IndServ();
var DevChk = new DeviceCheck();
DevChk.chkMobile();
MainConfig.SITE = G_SITE;
$(document).ready(function () {
    DevChk.init();
    G_VARS.App.init();
    if (G_PAGE == 'vacancies')
        new PageVacancyView();
    if (G_PAGE == 'vacancy')
        new PageSearchVacs();
    if (G_PAGE == 'register' || G_PAGE == 'register-applicant') {
        FBservice.init();
        new PageRegister();
    }
    if (G_PAGE == 'applicant-profile')
        new PageApplicantProfile();
    if ((G_PAGE == 'applocant-profile-own' || G_PAGE == 'ankety'))
        new PageApplicantProfileOwn();
    if (G_PAGE == 'applicant-comments')
        new PageApplicantComments();
    if (G_PAGE == 'company-profile-own')
        new PageCompanyProfileOwn();
    if (G_PAGE == 'applicant-list' || G_PAGE == 'ankety')
        new PageApplicantList();
    if (G_PAGE == 'im' && G_USER_TYPE == 2)
        new PageImApplicant();
    if (G_PAGE == 'searchempl' || G_PAGE == 'company-list')
        new PageCompanyList();
    if (G_PAGE == 'company-services')
        new PageCompanyServices();
    if (G_PAGE == 'services')
        new PageServices();
    if (G_PAGE == 'login')
        new PageLogin();
    if (G_PAGE == 'responses' && G_USER_TYPE == 3)
        new ResponsesCompany();
    if (G_PAGE == 'responses' && G_USER_TYPE == 2)
        new ResponsesApplic();
    if (G_PAGE == 'setrate')
        new SetrateApplic();
    if (G_PAGE == 'feedback')
        new Feedback();
    if (G_PAGE == 'page' && G_ACTION_ID == 'faq')
        new CommonPages();
});
var AutocompleteAjax = (function () {
    function AutocompleteAjax(inObj, inParams) {
        this.ajaxRetData = {};
        this.selectObj = {};
        this.selfObj = {};
        this.loadObj = {};
        this.options = { ajaxParams: {} };
        this.vT1 = 0;
        this.afterItemSelectedFn = 0;
        var self = this;
        var defProps = { ajaxParams: { inputName: '',
                url: '',
                addParams: {}
            },
            insertAllow: 0,
            width: 280,
            insertFirst: 0,
            loadingGIF: MainConfig.PATH_PIC + 'loading2.gif',
            labelText: 'Chose smth',
            btnAddHint: '',
            afterItemSelected: null
        };
        $.extend(defProps.ajaxParams, inParams.ajaxParams);
        $.extend(defProps, inParams);
        inParams = defProps;
        self.options = inParams;
        self.selectObj = $(inObj);
        $('<div id="DiLoadingDDMA"><img src="' + self.options.loadingGIF + '" alt=""></div>').appendTo('body');
        self.loadObj = $('#DiLoadingDDMA');
        self.init();
    }
    AutocompleteAjax.prototype.setAjaxParam = function (key, val) {
        var self = this;
        self.options.ajaxParams.addParams[key] = val;
        return self;
    };
    AutocompleteAjax.prototype.clear = function () {
        var self = this;
        self.selfObj.find('.choices').empty();
    };
    AutocompleteAjax.prototype.setWidth = function (inWidth) {
        var self = this;
        var wrapper = self.selfObj;
        var width = "100%";
        if (width) {
            wrapper.find('.dropdown-block').css({ margin: "0 0 0 " + (-width + 30 + 5) + "px",
                width: width + 'px' });
            wrapper.find('.dropdown').css({ width: (width - 12) + "px" });
            wrapper.find('input.noinsert').css({ width: (width - 12) + "px" });
        }
    };
    AutocompleteAjax.prototype.init = function () {
        var self = this;
        if (self.options.insertAllow) {
            var block = '<input type="text" class="edit"/><a href="#" class="ok" title="Добавить в список"></a>';
        }
        else {
            var block = '<input type="text" class="edit noinsert"/>';
        }
        var wrapper = self.selfObj = self.selectObj.after('<div class="autocomplete">' +
            '<div class="dropdown-block">' +
            '<label for="">' + self.options.labelText + '</label>' +
            block +
            '<div class="dropdown">' +
            '<div class="title"></div><div class="close">x</div>' +
            '<div class="choices">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>').next();
        self.setWidth(self.options.width);
        wrapper.find('.add-btn').click(function (e) { self.onAddBtnCLickFn(e, this); });
        wrapper.find("input.edit").keypress(function (e) { return !(e.which == 13); })
            .keyup(function (e) { return self.onEditKeyPressFn(e, this); })
            .blur(function (e) { self.onEditBlurFn(e, this); });
        wrapper.find(".ok").click(function (e) { self.onOkBtnClickFn(e, this); });
        wrapper.find(".close").click(function (e) { self.closeFn(); });
    };
    AutocompleteAjax.prototype.onAddBtnCLickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        $that.toggleClass('opened');
        self.selfObj.find('.dropdown-block').slideToggle(300).find('input').focus().select();
    };
    AutocompleteAjax.prototype.onEditBlurFn = function (e, that) {
        var self = this;
        setTimeout(function () {
            var $that = $(that);
            self.closeFn();
        }, 300);
    };
    AutocompleteAjax.prototype.onEditKeyPressFn = function (e, that) {
        e = e || event;
        var self = this;
        var $that = $(that);
        var wrapper = self.selfObj;
        var select = wrapper.find('.dropdown').stop().slideDown(300);
        var choices = wrapper.find('.choices');
        clearTimeout(self.vT1);
        self.vT1 = setTimeout(function () {
            self.showLoadingFn($that, 1);
            var params = {};
            params[self.options.ajaxParams.inputName] = $that.val();
            for (var ii in self.options.ajaxParams.addParams) {
                var val = self.options.ajaxParams.addParams[ii];
                params[ii] = val;
            }
            $.get(self.options.ajaxParams.url, params, function (data) {
                data = JSON.parse(data);
                self.ajaxRetData = data;
                if (self.options.insertFirst > 1) {
                }
                choices.empty();
                for (var ii in data) {
                    var itm = $("<a href='#' class='item'".concat(" data-name='", data[ii].name, "' data-id='", data[ii].id, "'>", data[ii].name, "</a>")).appendTo(choices);
                }
                if (data.length < 1) {
                    if (self.options.insertAllow) {
                        select.stop().slideUp(200);
                    }
                    else {
                        wrapper.find('.title').text('Не найдено совпадений');
                    }
                }
                else {
                    wrapper.find('.title').text('');
                    select.css({ height: '' }).slideDown(300);
                }
                select.find('.item').click(function (e) { self.onDLItemCLickFn(e, this); });
            }).always(function () {
                self.hideLoadingFn();
            });
        }, 500);
    };
    AutocompleteAjax.prototype.closeFn = function () {
        var self = this;
        self.selfObj.find('.dropdown-block').slideUp(200);
        self.selfObj.find('.add-btn').removeClass('opened');
    };
    AutocompleteAjax.prototype.showLoadingFn = function (inElm, isRight) {
        var self = this;
        var DiLoading = self.loadObj;
        DiLoading.find('img').attr('src', self.options.loadingGIF);
        if (isRight)
            DiLoading.css({ left: inElm.offset().left + inElm.width() - DiLoading.width() - 20, top: inElm.offset().top + parseInt((inElm.outerHeight() - DiLoading.outerHeight()) / 2) - 1 });
        DiLoading.fadeIn(400);
    };
    AutocompleteAjax.prototype.hideLoadingFn = function () {
        this.loadObj.fadeOut(400);
    };
    AutocompleteAjax.prototype.onDLItemCLickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        e.preventDefault();
        $that.closest('.dropdown-block').find('input').val($that.text());
        var itm;
        if (!(itm = self.findAddedCity($that.text()))) {
            var itm = $("<option value='".concat($that.data('id'), "' selected>", $that.text(), "</option>"));
            if (self.options.insertFirst > 0) {
                var selItms = self.selectObj.find('option');
                if (selItms.length >= self.options.insertFirst) {
                    if (self.options.insertFirst < 2)
                        itm.prependTo(self.selectObj);
                    else
                        $(selItms[self.options.insertFirst - 2]).after(itm);
                }
                else {
                    itm.appendTo(self.selectObj);
                }
            }
            else
                itm.appendTo(self.selectObj);
            self.selectObj.multipleSelect('refresh');
        }
        else {
            itm.prop('selected', true);
            itm.parent().multipleSelect('refresh');
        }
        self.closeFn();
        self.options.afterItemSelected != '' && self.options.afterItemSelected([$that.data('id'), $that.text()]);
    };
    AutocompleteAjax.prototype.findAddedCity = function (inName) {
        var self = this;
        var wrapper = self.selectObj;
        var flag = 0;
        wrapper.find('option').each(function () {
            var $that = $(this);
            if (($that.text()).toUpperCase() == inName.toUpperCase()) {
                return $that;
            }
        });
        return flag;
    };
    AutocompleteAjax.prototype.onOkBtnClickFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var wrapper = self.selfObj;
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
            self.selectObj.prepend("<option value='".concat(flag ? flag : input.val(), "' selected>", input.val(), "</option>"))
                .multipleSelect('refresh');
        self.closeFn();
    };
    return AutocompleteAjax;
}());
var AutocompleteHelper = (function () {
    function AutocompleteHelper() {
        var self = this;
        self.defOpts = { url: '',
            delay: 800,
            limit: 10,
            afterItemSelected: null
        };
        self.init();
    }
    AutocompleteHelper.prototype.init = function () {
    };
    AutocompleteHelper.prototype.bind = function (inObj, inProps) {
        var self = this;
        var props = self.defOpts;
        $.extend(props, inProps);
        self.options = props;
        self.selectObj = $(inObj);
        inObj.autocomplete({
            serviceUrl: props.url,
            params: { limit: props.limit },
            deferRequestBy: props.delay,
            onSearchComplete: function (query, data) {
                G_VARS.App.hideLoading();
            },
            onSearchStart: function (query) {
                G_VARS.App.showLoading2(inObj, { pic: 2, align: 'right' });
            },
            onSelect: function (data) {
                props.afterItemSelected(data);
            },
            onSearchError: function () {
                G_VARS.App.hideLoading();
            }
        });
        return self;
    };
    return AutocompleteHelper;
}());
function _t(inStr) {
    return Localization.translate(inStr);
}
var Localization = (function () {
    function Localization() {
        this.StringsObj = new Strings();
    }
    Localization.translate = function (inText) {
        if (!this.Instance)
            this.Instance = new Localization();
        return this.Instance.translate(inText);
    };
    Localization.prototype.translate = function (inText) {
        var self = this;
        var chain = inText.split('.');
        var val = self.StringsObj;
        for (var ii in chain) {
            var val = val[chain[ii]];
        }
        return val;
    };
    return Localization;
}());
var CustomError = (function (_super) {
    __extends(CustomError, _super);
    function CustomError(inMessage, inCode, inRetCode) {
        _super.call(this, inMessage);
        this.message = inMessage;
        this.code = inCode;
        inRetCode && (this.retCode = inRetCode);
    }
    return CustomError;
}(Error));
var PushChecker = (function () {
    function PushChecker() {
        var self = this;
    }
    PushChecker.prototype.init = function () {
        var self = this;
        self.getUserNewMessages();
        self.getUserNewComments();
    };
    PushChecker.prototype.getUserNewMessages = function () {
        var self = this;
        $.get(MainConfig.AJAX_GET_GETUSERNEWMESSAGES, {}, function (data) {
            try {
                data = JSON.parse(data);
                if (data['newmessages'].length) {
                    var count = 0;
                    for (var ii in data['newmessages']) {
                        var val = data['newmessages'][ii];
                        count += parseInt(val.count);
                    }
                    $(".js-push-block .js-pum b").removeClass('nm').text(count);
                }
                else {
                }
            }
            catch (e) {
            }
        });
    };
    PushChecker.prototype.getUserNewComments = function () {
        var self = this;
        $.get(MainConfig.AJAX_GET_GETUSERNEWCOMMENTS, {}, function (data) {
            try {
                data = JSON.parse(data);
                if (parseInt(data['newcomments']))
                    $(".js-push-block .js-pum-comments b").removeClass('nm').text(data['newcomments']);
            }
            catch (e) {
            }
        });
    };
    return PushChecker;
}());
var Strings_ru = (function () {
    function Strings_ru() {
    }
    Strings_ru.AJAX_GET_CITYES = '/ajax/getcities/';
    return Strings_ru;
}());
