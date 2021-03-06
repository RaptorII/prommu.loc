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
  MainConfig.PAGE_SEARCH_EMPL = '/searchempl';
  MainConfig.PAGE_SEARCH_VAC = '/vacancy';
  MainConfig.PAGE_VACANCY = 'user/vacancies/';
  MainConfig.PAGE_IM = 'user/im';
  MainConfig.PATH_PIC = '/theme/pic/';
  MainConfig.AJAX_GET_SHOW_MESS = '/ajax/showmobmessage';

  return MainConfig;
}());
CommFuncs = function () {
  function t() {
  }

  return t.merge = function (t, e) {
    var n = {};
    for (var i in t) {
      n[i] = t[i];
    }
    for (var i in e) {
      n[i] = e[i];
    }
    return n;
  }, t.parseUrl = function () {
    for (var t = {}, e = window.location.search.substring(1).split("&"), n = 0; n < e.length; n++) {
      var i = e[n].split("=");
      if (void 0 === t[i[0]]) {
        t[i[0]] = decodeURIComponent(i[1]);
      } else {
        if ("string" == typeof t[i[0]]) {
          var o = [t[i[0]], decodeURIComponent(i[1])];
          t[i[0]] = o;
        } else {
          t[i[0]].push(decodeURIComponent(i[1]));
        }
      }
    }
    return t;
  }, t.inArray = function (t, e, n) {
    void 0 === n && (n = !1);
    var i, o = !1;
    for (i in n = !!n, e) {
      if (n && e[i] === t || !n && e[i] == t) {
        o = !0;
        break;
      }
    }
    return o;
  }, t.scrollTo = function () {
    var t;
    t = $("*").is("a[name=smth]") ? $("a[name=smth]").offset().top : 1, $("body").stop().animate({scrollTop: t - 20 + "px"}, 500);
  }, t.base64Encode = function (t) {
    for (var e, n, i, o, a, s = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", r = 0, c = ""; e = (a = t.charCodeAt(r++) << 16 | t.charCodeAt(r++) << 8 | t.charCodeAt(r++)) >> 18 & 63, n = a >> 12 & 63, i = a >> 6 & 63, o = 63 & a, c += s.charAt(e) + s.charAt(n) + s.charAt(i) + s.charAt(o), r < t.length;) {
    }
    switch (t.length % 3) {
      case 1:
        c = c.slice(0, -2) + "==";
        break;
      case 2:
        c = c.slice(0, -1) + "=";
    }
    return c;
  }, t.clone = function (t) {
    if (null == t || "object" != typeof t) {
      return t;
    }
    var e = t.constructor();
    for (var n in t) {
      t.hasOwnProperty(n) && (e[n] = t[n]);
    }
    return e;
  }, t;
}(), HiddenText = function () {
  function i(t) {
    this.openText = "", this.closeText = "", this.hiddPic = "";
    var e = this, n = {openBtnClass: "look-full", doNotClose: !1};
    $.extend(n, t), t = n, e.options = t, e.openText = t.openText, e.closeText = t.closeText, e.hiddPic = t.hiddenImg, e.wrappObj = t.wrappObj, e.contentObj = t.contentObj, e.wrappObj.append('<div class="hidden-text"></div>'), e.wrappObj.append('<div class="' + e.options.openBtnClass + '"><a href="#">' + e.openText + "</a></div>"), e.wrappObj.find("." + e.options.openBtnClass + " a").click(function (t) {
      e.onShowFullTextClickFn(t, this);
    });
  }

  return i.init = function (n) {
    i.wrapper = n.wrapper, i.content = n.content, $(i.wrapper).each(function (t) {
      var e = $(this);
      n.contentObj = e.find(i.content), e.height() < n.contentObj.height() && (n.wrappObj = e, new i(n));
    });
  }, i.prototype.onShowFullTextClickFn = function (t, e) {
    var n = this;
    t.preventDefault();
    var i = n.contentObj.height(), o = n.wrappObj, a = o.height();
    if (o.data("opened")) {
      o.find(".hidden-text").fadeIn(400);
      var s = o.data("opened");
      o.animate({height: "-=" + (a - s)}, 300, function () {
        o.css({
          maxHeight: "",
          height: ""
        }).removeAttr("data-opened").removeData("opened"), o.find("." + n.options.openBtnClass).removeClass("-opened").css({position: ""}), o.find("." + n.options.openBtnClass + " a").text(n.openText);
      });
    } else {
      o.animate({maxHeight: i}, 300, function () {
        o.css({maxHeight: "none"}).attr("data-opened", a), o.find(".hidden-text").fadeOut(200), o.find("." + n.options.openBtnClass).addClass("-opened").css({position: "static"});
        var t = o.find("." + n.options.openBtnClass + " a");
        t.text(n.closeText), n.options.doNotClose && t.fadeOut(200);
      });
    }
  }, i.wrapper = "", i.content = "", i;
}(), ModalWindow = function () {
  function i(t) {
    this.context = "#DiContent", this.props = {}, this.defProps = {};
    var e = this;
    e.defProps = {
      action: {active: 1, btnTitle: "OK", onClick: ""},
      getContent: "",
      content: "",
      afterOpen: "",
      afterClose: "",
      bgIsCloseBtn: 1,
      position: "fixed",
      context: "#DiContent"
    }, t = e.setProps(t), e.context = t.context, i.winObj = e;
    var n = $('<div id="MWwrapper"></div>');
    n.prependTo(e.context), $('<div class="mw-bg"></div>').appendTo("#MWwrapper"), $('<div class="mw-win"><div class="loading"><i></i></div><a href="#" class="mw-close"></a><div class="header-block"></div><div class="mw-content"></div></div>').appendTo("#MWwrapper"), $("#MWwrapper .mw-close").click(function (t) {
      e.onCloseFn(t);
    }), $(".mw-closed").click(function (t) {
      e.onCloseFn(t);
    }), $("#MWwrapper .mw-bg").click(function (t) {
      e.onCloseFn(t, 1);
    }), e.winDOM = n, i.content = n.find(".mw-content");
  }

  return i.open = function (t) {
    i.winObj || new i(CommFuncs.clone(t)), i.winObj.onOpenFn(t);
  }, i.close = function (t) {
    i.winObj || new i, i.winObj.onCloseFn(t);
  }, i.redraw = function (t) {
    i.winObj || new i(CommFuncs.clone(t)), i.winObj.onRedrawFn(t), t.afterRedraw && t.afterRedraw();
  }, i.moveCenter = function (t) {
    i.winObj || new i, i.winObj.onMoveCenter(t);
  }, i.show = function (t) {
    i.winObj || new i, i.winObj.onShow(t);
  }, i.hide = function (t) {
    i.winObj || new i, i.winObj.onHide(t);
  }, i.loadingOn = function () {
    i.winObj && i.winObj.winDOM.find(".loading").fadeIn(200);
  }, i.loadingOff = function () {
    i.winObj && i.winObj.winDOM.find(".loading").fadeOut(200);
  }, i.prototype.onCloseFn = function (t, e) {
    void 0 === e && (e = 0), t && t.preventDefault(), e && !this.props.bgIsCloseBtn || ($("#MWwrapper .mw-bg").fadeOut(200), $("#MWwrapper .mw-win").fadeOut(400)), this.props.afterClose && this.props.afterClose();
  }, i.prototype.onOpenFn = function (e) {
    var n = this;
    e = n.setProps(e), $("#MWwrapper .loading").fadeIn(400);
    var i = $("#MWwrapper .mw-bg"), o = $("#MWwrapper .mw-win");
    if (null != e.additionalStyle ? o.removeClass().addClass("mw-win " + e.additionalStyle) : o.removeClass().addClass("mw-win"), n.createBtn(e), e && e.getContent) {
      $.get(e.getContent, function (t) {
        o.children(".mw-content").empty().append($(t)), $(window).width() < o.outerWidth() && o.css({width: $(window).width() - 20}), n.onMoveCenter(), i.fadeIn(400), o.fadeIn(400), e.afterOpen && e.afterOpen(), $("#MWwrapper .loading").fadeOut(200);
      }).always(function () {
      });
    } else {
      if (e && e.content) {
        var t = o.find(".header-block");
        t.empty(), e.content && $(e.content).data("header") && t.append("<div class='header'><div>".concat($(e.content).data("header"), "</div></div>")), e.content && $(e.content).data("title") && t.append("<div class='header2'>".concat($(e.content).data("title"), "</div>")), o.children(".mw-content").empty().append($(e.content)), $(window).width() < o.outerWidth() && o.css({width: $(window).width() - 20}), n.onMoveCenter(), i.fadeIn(400), o.fadeIn(400), e.afterOpen && e.afterOpen(), $("#MWwrapper .loading").fadeOut(200);
      }
    }
  }, i.prototype.onRedrawFn = function (t) {
    var e = (t = this.setProps(t)).content, n = ($("#MWwrapper .mw-bg"), $("#MWwrapper .mw-win"));
    this.createBtn(t), n.children(".mw-content").empty().append(e), "absolute" == t.position ? n.css({
      top: ($(window).outerHeight() - n.outerHeight()) / 2,
      margin: "".concat("0 0 0 -", "" + n.width() / 2, "px"),
      position: "absolute"
    }) : n.css({margin: "-".concat("" + n.height() / 2, "px 0 0 -", "" + n.width() / 2, "px")}), this.winDOM.find(".loading").fadeOut(200);
  }, i.prototype.onMoveCenter = function (t) {
    var e = this.winDOM.find(".mw-win");
    if (this.winDOM.find(".mw-info"), "absolute" == this.props.position) {
      var n = ($(window).outerHeight() - e.outerHeight()) / 2;
      $(window).outerHeight() < e.outerHeight() && (n = 0), e.css({
        top: n,
        margin: "".concat("20px 10px 20px -", "10", "px"),
        width: "50%",
        left: "26%",
        position: "absolute"
      });
    } else {
      e.css({margin: "-".concat("" + e.height() / 2, "px 0 0 -", "" + e.outerWidth() / 2, "px"), left: "50%"});
    }
  }, i.prototype.onShow = function (t) {
    this.winDOM.find(".mw-win").fadeIn(200);
  }, i.prototype.onHide = function (t) {
    this.winDOM.find(".mw-win").fadeOut(200);
  }, i.prototype.createBtn = function (t) {
    var e = this, n = t;
    if (n.action.active) {
      var i = e.winDOM.find(".button-action");
      if (i.off("click"), i.remove(), $('<div class="button-action"><button>' + n.action.btnTitle + "</button></div>").appendTo(e.winDOM.find(".mw-win")), n.action.onClick) {
        o = n.action.onClick;
      } else {
        var o = function (t) {
          e.onCloseFn(t);
        };
      }
      $("#MWwrapper .button-action").click(o);
    } else {
      $(e.winDOM.find(".button-action")).html("");
    }
  }, i.prototype.setProps = function (t) {
    var e = CommFuncs.clone(this.defProps);
    e.action = CommFuncs.clone(this.defProps.action);
    var n = t;
    return $.extend(e.action, n.action), delete n.action, $.extend(e, n), this.props = e, this.props;
  }, i;
}(), FormFilters = function () {
  function t() {
  }

  return t.prototype.bindFiltersFn = function (t) {
    var e = this;
    if ((t = $(t)) && t.length) {
      n = t.find("[data-field-filter]");
    } else {
      var n = $("[data-field-filter]");
    }
    n.keypress(function (t) {
      e.onKeyPressFilterFn(t, this);
    });
  }, t.prototype.onKeyPressFilterFn = function (t, e) {
    var n = $(e), i = n.data("field-filter").split(";"), o = {};
    for (var a in i) {
      i[a] = i[a].replace("\\:", "|||");
      var s = i[a].split(":", 2);
      s[1].length && (s[1] = s[1].replace("|||", ":")), o[s[0]] = s[1] ? s[1] : "";
    }
    for (var a in o) {
      if ("digits" == a) {
        var r = o[a];
        if (t.which < 48 || 57 < t.which) {
          if (r && -1 !== $.inArray(String.fromCharCode(t.which), r.split("")) || -1 !== $.inArray(t.keyCode, [8, 9, 46, 36, 35, 37, 39])) {
            return 1;
          }
          t.preventDefault();
        }
      } else {
        if ("max" == a && n.val().length >= o[a]) {
          if (e.selectionStart != e.selectionEnd) {
            return 1;
          }
          t.preventDefault();
        }
      }
    }
  }, t;
}(), FormCheckers = function () {
  function t() {
    this.FLAG_STOP = 0, this.errBoxObj = 0, this.addErrClass = "", this.waitTimeErrorMess = 0, this.customCheckers = {}, this.options = {}, this.filters = {}, this.T1wait = 0, this.options = {
      name: "",
      elem: !1,
      message: "",
      wait: 0
    }, this.filters = [["empty", "max", "multi", "email", "if", "password", "notonlydigits", "custom"], ["filterEmpty", "filterMax", "filterMulti", "filterEmail", "filterIf", "filterPassword", "filterNotonlydigits", "filterCustom"]];
  }

  return t.prototype.FormSubmit = function (t) {
    return this.onFormSubmit(t.event, t.form, t.justReturn);
  }, t.prototype.addCheckerCustom = function (t, e) {
    this.customCheckers[t] = e;
  }, t.prototype.onFormSubmit = function (t, e, n) {
    t.preventDefault();
    var r = this;
    e = $(e);
    var c = null, l = null, d = 0;
    return $(e).find("[data-field-check]:not(.nocheck)").each(function () {
      r.options.message = "", c = r.options.elem = $(this), l = c.val();
      var t = c.data("field-check").split(","), e = {};
      for (var n in t) {
        var i = t[n].split(":");
        e[i[0]] = !i[1] || i[1];
      }
      for (var o in e) {
        r.setOptions(o, e[o]);
      }
      for (var o in e) {
        var a;
        if (-1 < (a = $.inArray(o, r.filters[0]))) {
          var s = r.filters[1][a];
          if (d = r[s]({filterVal: e[o], item: c, itemVal: l})) {
            break;
          }
        }
      }
      if (d) {
        return !1;
      }
    }), d ? (c = r.options.elem, !(r.FLAG_STOP = 1)) : !!n || (e.off("submit").submit(), !0);
  }, t.prototype.setOptions = function (t, e) {
    "name" == t ? this.options.name = e : "elem" == t ? this.options.elem = $(e) : "message" == t ? this.options.message = e : "wait" == t && (this.options.wait = e);
  }, t.prototype.filterEmpty = function (t) {
    var e = 0;
    return "" == t.itemVal && (e = 1, this.options.message = 'Необходимо заполнить поле "' + this.options.name + '"'), e;
  }, t.prototype.filterMax = function (t) {
    return t.itemVal.length > t.filterVal && (this.options.message = 'Поле "' + this.options.name + '" не должно превышать ' + t.filterVal + " символов", !0);
  }, t.prototype.filterMulti = function (t) {
    return (!t.itemVal || t.itemVal.length < 1) && (this.options.message = 'Выберите значение в поле "' + this.options.name + '"', this.options.elem = this.options.elem.next(".ms-parent"), !0);
  }, t.prototype.filterEmail = function (t) {
    return !new RegExp("^[^@]+@[^.]+.[^.^@]{2,}[^@]*$", "").test(t.itemVal) && (this.options.message = 'Введите правильный e-mail адрес в поле "' + this.options.name + '"', !0);
  }, t.prototype.filterIf = function (t) {
    return t.itemVal == t.filterVal;
  }, t.prototype.filterPassword = function (t) {
    return t.itemVal != $(t.filterVal).val() && (this.options.message = "Пароль и его повторение не совпадают", !0);
  }, t.prototype.filterNotonlydigits = function (t) {
    return !new RegExp("([A-ZА-Я]+)").test(t.itemVal) && (this.options.message || (this.options.message = 'Поле "' + this.options.name + '" должно содержать не только цифры'), !0);
  }, t.prototype.filterCustom = function (t) {
    return this.customCheckers[t.filterVal]({value: t.itemVal});
  }, t;
}(), DDMultiAjax = function () {
  function t(t, e) {
    this.ajaxRetData = {}, this.selectObj = {}, this.selfObj = {}, this.loadObj = {}, this.options = {ajaxParams: {}}, this.vT1 = 0, this.afterItemSelectedFn = 0;
    var n = this, i = {
      ajaxParams: {inputName: "", url: "", addParams: {}},
      insertAllow: 0,
      width: 280,
      insertFirst: 0,
      loadingGIF: MainConfig.PATH_PIC + "loading2.gif",
      labelText: "Chose smth",
      btnAddHint: "",
      afterItemSelected: null
    };
    $.extend(i.ajaxParams, e.ajaxParams), $.extend(i, e), e = i, n.options = e, n.selectObj = $(t), $('<div id="DiLoadingDDMA"><img src="' + n.options.loadingGIF + '" alt=""></div>').appendTo("body"), n.loadObj = $("#DiLoadingDDMA"), n.init();
  }

  return t.prototype.setAjaxParam = function (t, e) {
    return this.options.ajaxParams.addParams[t] = e, this;
  }, t.prototype.clear = function () {
    this.selfObj.find(".choices").empty();
  }, t.prototype.setWidth = function (t) {
    var e = this.selfObj, n = this.options.width = t;
    n && (e.find(".dropdown-block").css({
      margin: "0 0 0 " + (30 - n + 5) + "px",
      width: n + "px"
    }), e.find(".dropdown").css({width: n - 12 + "px"}), e.find("input.noinsert").css({width: n - 12 + "px"}));
  }, t.prototype.init = function () {
    var e = this;
    if (e.options.insertAllow) {
      t = '<input type="text" class="edit"/><a href="#" class="ok" title="Добавить в список"></a>';
    } else {
      var t = '<input type="text" class="edit noinsert"/>';
    }
    var n = e.selfObj = e.selectObj.after('<div class="dropdown-multi"><a href="#" class="add-btn" title="' + e.options.btnAddHint + '"></a><div class="dropdown-block"><label for="">' + e.options.labelText + "</label>" + t + '<div class="dropdown"><div class="title"></div><div class="close">x</div><div class="choices"></div></div></div></div>').next();
    e.setWidth(e.options.width), n.find(".add-btn").click(function (t) {
      e.onAddBtnCLickFn(t, this);
    }), n.find("input.edit").keypress(function (t) {
      return !(13 == t.which);
    }).keyup(function (t) {
      return e.onEditKeyPressFn(t, this);
    }).blur(function (t) {
      e.onEditBlurFn(t, this);
    }), n.find(".ok").click(function (t) {
      e.onOkBtnClickFn(t, this);
    }), n.find(".close").click(function (t) {
      e.closeFn();
    });
  }, t.prototype.onAddBtnCLickFn = function (t, e) {
    var n = $(e);
    t.preventDefault(), n.toggleClass("opened"), this.selfObj.find(".dropdown-block").slideToggle(300).find("input").focus().select();
  }, t.prototype.onEditBlurFn = function (t, e) {
    var n = this;
    setTimeout(function () {
      $(e), n.closeFn();
    }, 300);
  }, t.prototype.onEditKeyPressFn = function (t, e) {
    t = t || event;
    var i = this, o = $(e), a = i.selfObj, s = a.find(".dropdown").stop().slideDown(300), r = a.find(".choices");
    clearTimeout(i.vT1), i.vT1 = setTimeout(function () {
      i.showLoadingFn(o, 1);
      var t = {};
      for (var e in t[i.options.ajaxParams.inputName] = o.val(), i.options.ajaxParams.addParams) {
        var n = i.options.ajaxParams.addParams[e];
        t[e] = n;
      }
      $.get(i.options.ajaxParams.url, t, function (t) {
        for (var e in t = JSON.parse(t), i.ajaxRetData = t, i.options.insertFirst, r.empty(), t) {
          $("<a href='#' class='item'".concat(" data-name='", t[e].name, "' data-id='", t[e].id, "'>", t[e].name, "</a>")).appendTo(r);
        }
        t.length < 1 ? i.options.insertAllow ? s.stop().slideUp(200) : a.find(".title").text("Не найдено совпадений") : (a.find(".title").text(""), s.css({height: ""}).slideDown(300)), s.find(".item").click(function (t) {
          i.onDLItemCLickFn(t, this);
        });
      }).always(function () {
        i.hideLoadingFn();
      });
    }, 500);
  }, t.prototype.closeFn = function () {
    this.selfObj.find(".dropdown-block").slideUp(200), this.selfObj.find(".add-btn").removeClass("opened");
  }, t.prototype.showLoadingFn = function (t, e) {
    var n, i = this.loadObj;
    i.find("img").attr("src", this.options.loadingGIF), e && (n = $("*").is(t) ? t.offset().top : 1, i.css({
      left: t.offset().left + t.width() - i.width() - 20,
      top: n + parseInt((t.outerHeight() - i.outerHeight()) / 2) - 1
    })), i.fadeIn(400);
  }, t.prototype.hideLoadingFn = function () {
    this.loadObj.fadeOut(400);
  }, t.prototype.onDLItemCLickFn = function (t, e) {
    var n = this, i = $(e);
    if (t.preventDefault(), i.closest(".dropdown-block").find("input").val(i.text()), o = n.findAddedCity(i.text())) {
      o.prop("selected", !0), o.parent().multipleSelect("refresh");
    } else {
      var o = $("<option value='".concat(i.data("id"), "' selected>", i.text(), "</option>"));
      if (0 < n.options.insertFirst) {
        var a = n.selectObj.find("option");
        a.length >= n.options.insertFirst ? n.options.insertFirst < 2 ? o.prependTo(n.selectObj) : $(a[n.options.insertFirst - 2]).after(o) : o.appendTo(n.selectObj);
      } else {
        o.appendTo(n.selectObj);
      }
      n.selectObj.multipleSelect("refresh");
    }
    n.closeFn(), "" != n.options.afterItemSelected && n.options.afterItemSelected([i.data("id"), i.text()]);
  }, t.prototype.findAddedCity = function (e) {
    return this.selectObj.find("option").each(function () {
      var t = $(this);
      if (t.text().toUpperCase() == e.toUpperCase()) {
        return t;
      }
    }), 0;
  }, t.prototype.onOkBtnClickFn = function (t, e) {
    var n = ($(e), this.selfObj), i = n.find("[type=text]");
    t.preventDefault();
    var o = 0;
    n.find(".item").each(function () {
      var t = $(this);
      t.data("name").toUpperCase() == i.val().toUpperCase() && (o = t.data("id"));
    }), this.findAddedCity(i.val()) || this.selectObj.prepend("<option value='".concat(o || i.val(), "' selected>", i.val(), "</option>")).multipleSelect("refresh"), this.closeFn();
  }, t;
}(), Uploaduni = function () {
  function t() {
  }

  return t.prototype.init = function (t) {
    var e = this, n = {
      scope: "",
      imgsWrapper: "",
      filesWrapper: "",
      imgBlockTmpl: "",
      filesBlockTmpl: "",
      lnktoimg: "",
      uploadForm: "",
      uploadConnector: "",
      messageBlock: "",
      loadingBLock: "",
      onDeleteEnd: ""
    };
    $.extend(n, t), t = n, e.props = t, e.props.imgsWrapper && $(e.props.imgsWrapper).on("click", ".uni-delete", function (t) {
      e.onFileDelete(t, this);
    }), e.props.filesWrapper && $(e.props.filesWrapper).on("click", ".uni-delete.file", function (t) {
      e.onFileDeleteEx(t, this);
    });
  }, t.prototype.upload = function (t) {
    this.onStartUpload(t);
  }, t.prototype.uploadEx = function (r) {
    if (!r || r && !r.uploadInput) {
      return !1;
    }
    var c = this, t = $(r.uploadInput);
    if (r.uploadInput.files[0]) {
      var l = t.closest("form");
      l.find(c.props.loadingBLock).fadeIn(400);
      var e = new FormData(l[0]);
      e.set("fn", t.attr("name")), e.set("sc", c.props.scope), e.set("meta", CommFuncs.base64Encode(JSON.stringify(r.meta))), e.set("op", "1"), $.ajax({
        url: c.props.uploadConnector,
        type: "POST",
        success: function (t) {
          var e = 0, n = _t("fileLoad");
          try {
            if ((t = JSON.parse(t)).error < 0) {
              throw t.ret && console.warn("ret", t.ret), e = t.error, new Error(t.message);
            }
            if (100 == t.error) {
              if (c.files = t.files, r.onAfterUpload && (t = r.onAfterUpload(t)), "images" == t.file.meta.type) {
                var i = $("." + c.props.imgBlockTmpl).clone();
                i.find(".uni-img").attr("src", t.file.files.tb), t.file.files.orig && i.find(".uni-img-link").attr("href", t.file.files.orig), i.find(".uni-delete").attr("data-id", t.id).click(function (t) {
                  c.onFileDelete(t, this);
                }), i.hide().removeClass("tmpl " + c.props.imgBlockTmpl).addClass("uni-img-block"), $(c.props.imgsWrapper).append(i), i.slideDown(400);
              } else {
                var o = $("." + c.props.filesBlockTmpl).clone();
                o.removeClass("tmpl"), t.file.files.orig && o.find(".uni-link").attr("href", t.file.files.orig), o.find(".uni-link").text(t.file.meta.name), o.find(".uni-delete.file").attr("data-id", t.id).click(function (t) {
                  c.onFileDelete(t, this);
                });
                var a = t.file.files.orig.substr(t.file.files.orig.lastIndexOf(".") + 1);
                o.addClass("-" + a).hide().removeClass(c.props.filesBlockTmpl).addClass("uni-file-block"), $(c.props.filesWrapper).append(o), o.slideDown(400);
              }
            }
            r.onSuccessEnd && r.onSuccessEnd(i || o);
          } catch (t) {
            var s = e;
            0 !== s && (n = t.message), e = 1;
          }
          e && (c.props.messageBlock && l.find(c.props.messageBlock).text(n), console.warn("E", s));
        },
        error: function () {
          l.find(".message").text(_t("fileLoad"));
        },
        data: e,
        cache: !1,
        contentType: !1,
        processData: !1
      }, "json").always(function () {
        l.find(".loading-ico").fadeOut(200);
      });
    }
  }, t.prototype.getFiles = function () {
    return this.files;
  }, t.prototype.setFiles = function (t) {
    this.files = t;
  }, t.prototype.onStartUpload = function (t) {
    var n = this, e = $(t);
    if (t.files[0]) {
      var i = e.closest("form");
      i.find(n.props.loadingBLock).fadeIn(400);
      var o = new FormData(i[0]);
      o.set("fn", e.attr("name")), o.set("sc", n.props.scope), o.set("op", "1"), $.ajax({
        url: n.props.uploadConnector,
        type: "POST",
        success: function (t) {
          if ((t = JSON.parse(t)).error) {
            n.props.messageBlock && i.find(n.props.messageBlock).text(t.message), t.ret && console.log("ret", t.ret);
          } else {
            n.files = t.files;
            var e = $("." + n.props.imgBlockTmpl).clone();
            e.find(".uni-img").attr("src", t.file.tb), t.file.orig && e.find(".uni-img-link").attr("href", t.file.orig), e.find(".uni-delete").attr("data-id", t.id).click(function (t) {
              n.onFileDelete(t, this);
            }), e.hide().removeClass(n.props.imgBlockTmpl).addClass("uni-img-block"), $(n.props.imgsWrapper).append(e), e.slideDown(400);
          }
        },
        error: function () {
          i.find(".message").text("Ошибка загрузки файла, обновите страницу и попробуйте еще раз");
        },
        data: o,
        cache: !1,
        contentType: !1,
        processData: !1
      }, "json").always(function () {
        i.find(".loading-ico").fadeOut(200);
      });
    }
  }, t.prototype.onFileDelete = function (t, e) {
    var o = this, a = $(e), s = a.closest("form"), r = a.data("id"), n = new FormData(s[0]);
    n.set("id", r), n.set("op", "2"), $.ajax({
      url: o.props.uploadConnector, type: "POST", success: function (t) {
        var e = 0, n = _t("fileDeleteError");
        try {
          if ((t = JSON.parse(t)).error < 0) {
            throw o.props.messageBlock && s.find(o.props.messageBlock).text(t.message), {
              message: t.message,
              code: t.error
            };
          }
          100 == t.error ? a.closest(o.props.imgsWrapper).find("[data-id=" + r + "]").closest(".uni-img-block").slideUp(200, function () {
            $(this).remove();
          }) : e = 1;
        } catch (t) {
          var i = t.code;
          null != i && (n = t.message), e = 1;
        }
        e && (o.props.messageBlock && s.find(o.props.messageBlock).text(n), i && console.warn("E", i));
      }, error: function () {
        s.find(".message").text(_t("fileDeleteError"));
      }, data: n, cache: !1, contentType: !1, processData: !1
    }, "json").always(function () {
      s.find(".loading-ico").fadeOut(200);
    });
  }, t.prototype.onFileDeleteEx = function (t, e) {
    var o = this, a = $(e), s = a.closest("form"), r = a.data("id"), n = new FormData(s[0]);
    n.set("id", r), n.set("op", "2"), $.ajax({
      url: o.props.uploadConnector, type: "POST", success: function (t) {
        var e = 0, n = _t("fileDeleteError");
        try {
          if ((t = JSON.parse(t)).error < 0) {
            throw o.props.messageBlock && s.find(o.props.messageBlock).text(t.message), {
              message: t.message,
              code: t.error
            };
          }
          100 == t.error ? a.closest(o.props.imgsWrapper + ", " + o.props.filesWrapper).find("[data-id=" + r + "]").closest(".uni-img-block").slideUp(200, function () {
            $(this).remove(), o.props.onDeleteEnd && o.props.onDeleteEnd();
          }) : e = 1;
        } catch (t) {
          var i = t.code;
          null != i && (n = t.message), e = 1;
        }
        e && (o.props.messageBlock && s.find(o.props.messageBlock).text(n), i && console.warn("E", i));
      }, error: function () {
        s.find(".message").text(_t("fileDeleteError"));
      }, data: n, cache: !1, contentType: !1, processData: !1
    }, "json").always(function () {
      s.find(".loading-ico").fadeOut(200);
    });
  }, t;
}(), Hinter = function () {
  function t() {
  }

  return t.prototype.init = function () {
  }, t.bind = function (t, e) {
    void 0 === e && (e = {});
    var n = {side: "bottom", animation: "fade"}, i = this.options;
    this.hintSide[e.side] && (n.side = this.hintSide[e.side]), this.hintAnimation[e.animation] && (n.animation = this.hintAnimation[e.animation]), $.extend(i, n), $(t).tooltipster(i);
  }, t.hintSide = {top: "top", bottom: "bottom", right: "right", left: "left"}, t.hintAnimation = {
    fade: "fade",
    swing: "swing"
  }, t.options = {side: "bottom", theme: ["tooltipster-noir", "tooltipster-noir-customized"], animation: "fade"}, t;
}(), CommentAdditor = function () {
  function t() {
    this.formAction = "";
  }

  return t.prototype.open = function (t) {
    var e = this;
    e.formAction = t.formAction, e.FormChecker || (e.FormChecker = new FormCheckers), ModalWindow.open({
      getContent: t.getUrl,
      afterOpen: function () {
        e.onAfterOpen();
      }
    });
  }, t.prototype.onAfterOpen = function () {
    var n = this;
    G_VARS.App.applyRadios("#MWwrapper"), G_VARS.App.applyCounterMemo("#MWwrapper"), $("#MWwrapper #EdName").focus(), $("#MWwrapper form").submit(function (t) {
      if ($("#MWwrapper .loading").fadeIn(400), n.FormChecker.FormSubmit({
          event: t,
          form: "#MWwrapper form",
          justReturn: 1
        })) {
        $("#BtnAddComment button").attr("type", "button");
        var e = $(this).serialize();
        $.post(n.formAction, e, function (t) {
          ModalWindow.redraw({
            content: $(t), afterRedraw: function () {
              n.onAfterRedraw();
            }
          });
        });
      } else {
        $("#MWwrapper .loading").fadeOut(200);
      }
    });
  }, t.prototype.onAfterRedraw = function () {
    $("#BtnOk a").click(function (t) {
      ModalWindow.close(t);
    }), $("#MWwrapper .loading").fadeOut(200);
  }, t;
}(), IndServ = function () {
  function t() {
    this.customProps = {};
  }

  return t.prototype.init = function () {
    var e = this, n = ".empty-search-result";
    sbjs.init({
      domain: MainConfig.SITE, lifetime: 3, callback: function (t) {
        e.doSmth(t);
      }
    }), $(".about__look-full a").click(function (t) {
      e.onShowFullTextClickFn(t);
    }), $("a.enter__register-link").click(function (t) {
      return e.stopLinkFn(t);
    }), $(".enter__user").removeClass("-autohover").find("a.um").click(function (t) {
      return e.onUserMenuClickFn(t, this);
    }).blur(function (t) {
      return e.onUserMenuClickFn(t, this, 1);
    }), $(".top-menu-wr").removeClass("-autopopup").find("a.menu").click(function (t) {
      return e.onTopMenuClickFn(t, this);
    }).blur(function (t) {
      return e.onTopMenuClickFn(t, this, 1);
    }), $(".nofirst").click(function () {
      e.dropDropBoxFirstItemFn(this);
    }), e.applyCheckbox(".checkbox-box input[type=checkbox], .checkbox-box-sm input[type=checkbox]"), e.applyRadios(""), e.applyCounterMemo(""), $(".memo-hint-box button").click(function () {
      e.onMemoHintBoxBtnClick(this);
    }), $(".memo-hint-box button").blur(function () {
      e.onMemoHintBoxBtnClick(this, 1);
    }), Hinter.bind(".js-g-hashint.-js-g-hintleft", {side: "left"}), Hinter.bind(".js-g-hashint.-js-g-hintright", {side: "right"}), Hinter.bind(".js-g-hashint");
    var a, t, s, r, c, d, p, i, o = 0;
    if ($(".vacancy.premium .border").each(function () {
        var t = $(this);
        t.outerHeight() > o && (o = t.outerHeight());
      }).each(function () {
        $(this).css({minHeight: o + "px"});
      }), "index" == G_PAGE) {
      function f(t) {
        var e = [], n = $(t).find("li");
        return $.each(n, function () {
          0 != $(this).data("id") && e.push(String($(this).data("id")));
        }), e;
      }

      function u(t) {
        for (var e = $(t).val().split(" "), n = e.length, i = 0; i < n; i++) {
          e[i] = e[i].charAt(0).toUpperCase() + e[i].slice(1).toLowerCase();
        }
        for ($(t).val(e.join(" ")), n = (e = $(t).val().split("-")).length, i = 0; i < n; i++) {
          e[i] = e[i].charAt(0).toUpperCase() + e[i].slice(1).toLowerCase();
        }
        $(t).val(e.join("-"));
      }

      $(".selectInd").click(function () {
        var t = $("#CBvacancy").val();
        1 == t && ($('#FmSearch [name="posts[]"]').remove(), $("#FmSearch").append('<input type="hidden" name="post[' + this.dataset.id + ']" value="on">')), 2 == t && ($('#FmSearch [name="post[]"]').remove(), $("#FmSearch").append('<input type="hidden" name="posts[]" value="' + this.dataset.id + '">'));
      }), a = {
        main: "#multyselect-cities",
        inputName: "cities[]"
      }, t = $(a.main).append('<ul class="cities-select"><li data-id="0"><input type="text" name="c"></li></ul><ul class="cities-list"></ul><b></b>'), s = t.find("ul").eq(0), r = s.find("input"), c = t.find("ul").eq(1), d = t.find("b"), i = !(p = !0), s.click(function (t) {
        $(t.target).is("i") || ($(".search__search-city").hide(), r.focus());
      }), r.click(function (t) {
        $(t.target).is("i") || ($(".search__search-city").hide(), r.focus());
      }), r.bind("input focus blur", function (o) {
        u(r);
        var t = r.val(), e = "focus" === o.type ? 1 : 1000;
        r.val(t).css({width: 10 * t.length + 5 + "px"}), p = !0, clearTimeout(i), i = setTimeout(function () {
          u(r);
          var e = [], n = "", t = r.val(), i = r.val().toLowerCase();
          arSelectId = f(s), "blur" !== o.type ? "" === t ? (d.show(), $.ajax({
            url: MainConfig.AJAX_GET_VE_GET_CITIES,
            data: "query=" + t,
            dataType: "json",
            success: function (t) {
              $.each(t.suggestions, function () {
                $.inArray(this.data, arSelectId) < 0 && (n += '<li data-id="' + this.data + '">' + this.value + "</li>");
              }), p ? c.empty().append(n).fadeIn() : (c.empty().append(n).fadeOut(), r.val("")), d.hide();
            }
          })) : (d.show(), $.ajax({
            url: MainConfig.AJAX_GET_VE_GET_CITIES,
            data: "query=" + t,
            dataType: "json",
            success: function (t) {
              $.each(t.suggestions, function () {
                word = this.value.toLowerCase(), word === i && $.inArray(this.data, arSelectId) < 0 && "man" !== this.data ? (html = '<li data-id="' + this.data + '">' + this.value + '<i></i><input type="hidden" name="' + a.inputName + '" value="' + this.data + '"/></li>', s.find('[data-id="0"]').before(html), p = !1) : 0 <= word.indexOf(i) && $.inArray(this.data, arSelectId) < 0 && "man" !== this.data && e.push({
                  id: this.data,
                  name: this.value
                });
              }), 0 < e.length ? $.each(e, function () {
                n += '<li data-id="' + this.id + '">' + this.name + "</li>";
              }) : n = '<li class="emp">Список пуст</li>', p ? c.empty().append(n).fadeIn() : (c.empty().append(n).fadeOut(), r.val("")), d.hide();
            }
          })) : r.val("");
        }, e);
      }), $(document).on("click", function (t) {
        $(t.target).is("li") && $(t.target).closest(c).length && !$(t.target).hasClass("emp") && ($(t.target).remove(), html = '<li data-id="' + $(t.target).data("id") + '">' + $(t.target).text() + '<i></i><input type="hidden" name="' + a.inputName + '" value="' + $(t.target).data("id") + '"/></li>', s.find('[data-id="0"]').before(html), c.fadeOut()), $(t.target).is("i") && $(t.target).closest(s).length && ($(t.target).closest("li").remove(), l = f(s).length), $(t.target).is(s) || $(t.target).closest(s).length || (p = !1, c.fadeOut(), 1 == s.find("li").length && $(".search__search-city").show());
      });
      var h = {
        url: function (t) {
          var e = $('.cities-select [name="cities[]"]'), n = "";
          return $.each(e, function () {
            n += "&cities[]=" + $(this).val();
          }), (1 == $("#CBvacancy").val() ? MainConfig.AJAX_POST_GETSEARCHVACS + "?search=" + t : 2 == $("#CBvacancy").val() ? MainConfig.AJAX_POST_GETAPPLIC + "?search=" + t : MainConfig.AJAX_POST_GETEMPLS + "?search=" + t) + n;
        },
        getValue: "name",
        ajaxSettings: {dataType: "json", method: "POST", data: {dataType: "json"}},
        preparePostData: function (t) {
          return t.phrase = $("#EdSearch").val().trim(), t;
        },
        requestDelay: 1000,
        list: {
          onChooseEvent: function () {
            e.onAutocompleteFn();
          }, onShowListEvent: function () {
            $("*").is(n) && $(n).remove();
          }, onLoadEvent: function () {
            var t = $(".easy-autocomplete-container");
            t.find(".eac-item").length || ($("*").is(n) ? $(n).addClass("active") : t.append("<div class='empty-search-result active'>Ничего не найдено</div>"));
          }
        }
      };
      $("#EdSearch").easyAutocomplete(h);
    }
    var m = null, v = null;
    // событие загрузки и ресайза для главной страницы
    $(window).on("load resize", function (t) {
      var e = $(window).width(),
        n = $("#DiEmplSlider"), // карусель соискателей
        i = $("#DiEmpl1Slider"); // карусель работодателей
      // выравнивение высоты блоков вакансий на главнйо
      function o(t)
      {
        for (var
               e = $(".page-index .vacancies__item"),
               n = $(e).find(".vacancies-item__content-title"),
               i = $(e).find(".vacancies-item__content-middle"),
               o = [],
               a = [],
               s = 0,
               r = 0,
               c = e.length;
             r < c;
             r++)
        {
          $(n[r]).css({height: "initial"}), $(i[r]).css({height: "initial"});
        }
        for (r = 0, c = e.length; r < c; r++) {
          var l = n[r].offsetHeight, d = i[r].offsetHeight;
          r && (s = ~~(r / t)), (null == o[s] || l > o[s]) && (o[s] = l), (null == a[s] || d > a[s]) && (a[s] = d);
        }
        for (r = s = 0, c = e.length; r < c; r++) {
          r && (s = ~~(r / t)), $(n[r]).css({height: o[s]}), $(i[r]).css({height: a[s]});
        }
      }
      // входные параметры карусели
      props = {
        navigation: !0,
        slideSpeed: 300,
        pagination: !0,
        paginationSpeed: 400,
        singleItem: !0,
        autoPlay: !0,
        autoplaySpeed: 300,
        autoplayTimeout: 2000,
        stopOnHover: !0,
        navigationText: ["", ""],
        singleItem : !1,
        items : 6,
        itemsDesktop : [1182, 4],
        itemsDesktopSmall : !1,
        itemsTablet : [974, 3],
        itemsTabletSmall : !1,
      };

      if(751 <= e) // разрешение, на котором требуется карусель
      {
        if(n.hasClass("off") || "load" == t.type) // станавливаем карусель для С
        {
          var appL = $(".applicants__item").length,
            empL = $(".companies__item").length;
          if((e<=974 && appL>=3) || (e>974 && e<=1182 && appL>=4) || (e>1182 && appL>=6))
          {
            n.owlCarousel(props);
          }
          n.removeClass("off");
          m = n.data("owlCarousel");
        }
        if(i.hasClass("off") || "load" == t.type)// станавливаем карусель для Р
        {
          if((e<=974 && empL>=3) || (e>974 && e<=1182 && empL>=4) || (e>1182 && empL>=6))
          {
            i.owlCarousel(props);
          }
          i.removeClass("off");
          v = i.data("owlCarousel");
        }
      }
      else
      {
        n.hasClass("off")
        ||
        (void 0 !== m && null != m && m.destroy(), n.addClass("off")), // выключаем карусель соискателей
        i.hasClass("off")
        ||
        (void 0 !== v && null != v && v.destroy(), i.addClass("off")) // выключаем карусель работодателей
      }


      // выводим правильно кол-во вакансий при нужном разрешении
      if(608 <= e && e < 751)
      {
        o(2);
      }
      else if(751 <= e && e < 974)
      {
        o(3);
      }
      else if(974 <= e)
      {
        o(4);
      }
    }),

      $(".search-category").click(function (t) {
        $.each($(".search-category"), function (t) {
          $(this).removeClass("active");
        }), $(this).addClass("active"), $("#CBvacancy").val($(this).data("val")), e.onChangeSearchTypeFn(t, $("#CBvacancy")), 3 == $(this).data("val") ? $(".search__wrd-module").addClass("disable") : $(".search__wrd-module").removeClass("disable");
      }), $(".search-word").click(function (t) {
      $("#EdSearch").val($(this).text().trim()), $("*").is(n) && $(n).remove();
    }), $("#EdSearch").focusin(function () {
      $("*").is(n) && ("" != $(this).val() ? $(n).addClass("active") : $(n).removeClass("active"));
    }), $("#EdSearch").focusout(function () {
      $("*").is(n) && $(n).removeClass("active");
    }), (new PushChecker).init(), $(".prommu_flash").is("*") && $.fancybox.open({
      src: ".prommu_flash",
      type: "inline",
      touch: !1,
      afterClose: function () {
        $("body>div.prommu_flash").remove();
      }
    });
  }, t.prototype.applyCityBox = function () {
    var e = this;
    $(".city-box [type=text]").keyup(function (t) {
      e.onEdCityChangeFn(t, this);
    }), $(".city-box [type=text]").blur(function () {
      var t = $(this);
      setTimeout(function () {
        t.closest(".city-box").find(".dropdown").fadeOut(100);
      }, 200);
    }), $(".city-box .dropdown").hide(), $(".city-block .btn-close").click(function (t) {
      t.preventDefault(), $(".city-block").length < 3 && ($("#CB1country").slideDown(200), $("#EdCountry").slideUp(200), $("#HiAddedCity").val("0"));
    });
  }, t.prototype.onEdCityChangeFn = function (t, e) {
    var o = this, a = $(e), s = a.closest(".city-box"), r = s.find(".choices");
    G_VARS.App.country && (clearTimeout(o.vT1City), o.vT1City = setTimeout(function () {
      G_VARS.App.showLoading(a, 1), $.get(MainConfig.AJAX_GET_CITYES, {
        filter: $(e).val(),
        idco: G_VARS.App.country,
        limit: 20
      }, function (t) {
        for (var e in t = JSON.parse(t), r.empty(), t) {
          var n = "1" == t[e].ismetro ? "data-id='".concat(t[e].id_city, "'") : "";
          $("<a href='#' class='item' ".concat(n, ", data-name='", t[e].name, "'>", t[e].name, "</a>")).appendTo(r);
        }
        var i = s.find(".dropdown");
        i.width(a.outerWidth()).fadeIn(300), i.find(".item").click(function (t) {
          o.onCBCityClickFn(t, this);
        });
      }).always(function () {
        G_VARS.App.hideLoading();
      });
    }, 300));
  }, t.prototype.applyMetroHTML = function (t, e) {
    this.onCBCityClickFn(0, t, e);
  }, t.prototype.onCBCityClickFn = function (t, e, n) {
    var i = $(e), o = i.closest(".city-box"), a = o.closest(".city-block").find(".metro-select"), s = a.find("select"),
      r = o.find(".EdCity");
    r.val(i.data("name")), t && t.preventDefault();
    var c = n || i.data("id");
    c ? (G_VARS.App.showLoading(r, 1), $.get(MainConfig.AJAX_GET_METRO, {idcity: c}, function (t) {
      for (var e in t = JSON.parse(t), s.empty(), t) {
        var n = "";
        G_VARS.userMetro && G_VARS.userMetro[t[e].id] && G_VARS.userMetro[t[e].id].idcity == c && (n = "selected"), $("<option value='".concat(t[e].id, "' ", n, ">", t[e].name, "</option>")).appendTo(s);
      }
      a.slideDown(400), s.multipleSelect("refresh").hide(), s.next().css({width: r.outerWidth()});
    }).always(function () {
      G_VARS.App.hideLoading();
    })) : (s.empty(), s.multipleSelect("refresh"), a.slideUp(200));
  }, t.prototype.showLoading = function (e, n, i) {
    var o = $("#DiLoading");
    if (i) {
      var a = i.offsetLeft ? i.offsetLeft : 0, s = i.align ? i.align : 0;
    }
    if (i && i.pic) {
      t = MainConfig.PATH_PIC + "loading" + i.pic + ".gif";
    } else {
      var t = MainConfig.PATH_PIC + "loading2.gif";
    }
    o.find("img").attr("src", t).one("load", function () {
      var t;
      i && 2 == i.variant && o.addClass("wb"), t = $("*").is(e) ? e.offset().top : 1, o.css({top: t + parseInt((e.outerHeight() - o.outerHeight()) / 2) - 1}), n ? o.css({
        left: e.offset().left + e.width() - o.width() - 20,
        top: t + parseInt((e.outerHeight() - o.outerHeight()) / 2) - 1
      }) : "center" == s ? o.css({left: e.offset().left + (e.outerWidth() - o.outerWidth()) / 2}) : o.css({
        left: e.offset().left + a,
        top: t + parseInt((e.outerHeight() - o.outerHeight()) / 2) - 1
      }), i && i.left && o.css({left: e.offset().left + i.left}), i && i.top && o.css({top: t}), o.fadeIn(400);
    });
  }, t.prototype.showLoading2 = function (e, n) {
    var i = $("#DiLoading"),
      t = {align: "center", valign: "middle", pic: 2, offsetX: 0, offsetY: 0, outerAlign: "", withBg: !1};
    e = $(e), $.extend(t, n), n = t;
    var o = MainConfig.PATH_PIC + "loading" + n.pic + ".gif";
    i.find("img").attr("src", o).one("load", function () {
      var t;
      "left" == n.outerAlign ? i.css({left: e.offset().left - i.width() + n.offsetX}) : "right" == n.align ? i.css({left: e.offset().left + e.width() - i.width() + n.offsetX}) : "center" == n.align ? i.css({left: e.offset().left + (e.outerWidth() - i.outerWidth()) / 2}) : "left" == n.align && i.css({left: e.offset().left + n.offsetX}), t = $("*").is(e) ? e.offset().top : 1, "top" == n.valign ? i.css({top: t + n.offsetY}) : "middle" == n.valign && i.css({top: t + parseInt((e.outerHeight() - i.height()) / 2)}), n.withBg ? "1" == n.pic ? i.addClass("wb-sq") : i.addClass("wb") : i.removeClass("wb wb-sq"), i.stop().fadeIn(400);
    });
  }, t.prototype.hideLoading = function () {
    setTimeout(function () {
      $("#DiLoading").stop().fadeOut(400);
    }, 400);
  }, t.prototype.applyCounterMemo = function (t) {
    var e = this;
    $(t + " .memo-with-counter textarea").keyup(function () {
      e.onMemoKeyUpFn(this);
    });
  }, t.prototype.applyRadios = function (t) {
    var e = this, n = $("label.radio-box");
    $.each(n, function () {
      var t = $(this);
      t.find("input").eq(0).prop("checked") && t.addClass("checked");
    }), $(t + " .radio-box input[type=radio]").change(function () {
      e.onRadioBoxChangeFn(this);
    });
  }, t.prototype.applyCheckbox = function (t) {
    var n = this;
    $(t).each(function () {
      var t = $(this), e = t.data("val"), n = e ? e.split(":") : "";
      1 < n.length ? t.val() == n[0] && (t.parent().addClass("checked"), t.prop("checked", !0)) : t.parent().toggleClass("checked", t.is(":checked"));
    }), $(t).change(function (t, e) {
      n.onChkboxChangeFn(this, e);
    });
  }, t.prototype.onShowFullTextClickFn = function (t) {
    $(".about__look-full");
    var e = $(".about__content").height();
    $(".about").animate({height: e}, 300, function () {
      $(".hidden-text").fadeOut(200), $(".about__look-full").fadeOut(200);
    }), t.preventDefault();
  }, t.prototype.stopLinkFn = function (t) {
    return t.preventDefault(), !1;
  }, t.prototype.dropDropBoxFirstItemFn = function (t) {
    var e = $(t), n = $(t).children("option");
    return e.hasClass("nofirst") && n[0].remove(), e.removeClass("nofirst"), !1;
  }, t.prototype.onChkboxChangeFn = function (t, e) {
    var n = $(t), i = n.data("val"), o = i ? i.split(":") : "";
    1 < o.length ? (n.prop("checked", !0), n.val() == o[1] ? (n.parent().addClass("checked"), n.val(o[0])) : (n.parent().removeClass("checked"), n.val(o[1]))) : n.parent().toggleClass("checked", n.is(":checked")), e || n.trigger("customOnCheck");
  }, t.prototype.onRadioBoxChangeFn = function (t) {
    $(t).closest("div.radio-box").find(".checked").each(function () {
      $(this).removeClass("checked");
    }), $(t).parent().toggleClass("checked", $(t).is(":checked"));
  }, t.prototype.onMemoKeyUpFn = function (t) {
    var e = $(t), n = e.data("counter"),
      i = e.val().length > parseInt(n) ? "".concat('<span style="color: red;">', e.val().length, "</span>") : e.val().length;
    e.parent().find(".memo-counter").html(["(", i, "/", n, ")"].join(""));
  }, t.prototype.onMemoHintBoxBtnClick = function (t, e) {
    var n = $(t).parent().find(".help-box");
    e ? n.fadeOut(200) : n.fadeIn(400, function () {
    });
  }, t.prototype.onUserMenuClickFn = function (t, e, n) {
    var i = $(e);
    t.preventDefault();
    var o = i.parent();
    !o.find("div").is(":hidden") || n ? (o.find("div").fadeOut(200), o.find("i").removeClass("up")) : (o.find("div").fadeIn(400), o.find("i").addClass("up"));
  }, t.prototype.onTopMenuClickFn = function (t, e, n) {
    var i = $(e);
    t.preventDefault();
    var o = i.parent();
    !o.find(".csson").is(":hidden") || n ? (o.find(".csson").fadeOut(200), o.find(".menu").removeClass("hover")) : (o.find(".csson").fadeIn(400), o.find(".menu").addClass("hover"));
  }, t.prototype.showHint = function (t, e) {
    var n, i = $(".error-hint-box");
    n = $("*").is(t) ? t.offset().top : 1, i.text(e).css({
      left: t.offset().left,
      top: n + t.outerHeight() + 10
    }), i.stop().fadeIn(400);
  }, t.prototype.closeHint = function () {
    $(".error-hint-box").stop().fadeOut(200);
  }, t.prototype.doSmth = function (t) {
    $(".referer").val(sbjs.get.current.typ), $(".transition").val(sbjs.get.current.src), $(".canal").val(sbjs.get.current.mdm), $(".campaign").val(sbjs.get.current.cmp), $(".content").val(sbjs.get.current.cnt), $(".keywords").val(sbjs.get.current.trm), $(".point").val(sbjs.get.current_add.ep), $(".last_referer").val(sbjs.get.current_add.rf);
  }, t.prototype.debug = function (t) {
    "publVac" == t && ($("#Mtitle").val("test"), $("#M1requirements").val("test"), $("#M2duties").val("test"), $("#M3conditions").val("test"), $("#CB1Dolj").val("18"), $("#CB2city").val("1"), $("#CB6salary").val("1"), $("#EdSalRubH").val("1"), $("#EdExp").val("1"));
  }, t.prototype.onAutocompleteFn = function () {
    var t = $("#EdSearch").getSelectedItemData();
    1 == $("#CBvacancy").val() && ($('#FmSearch [name="posts[]"]').remove(), $("#FmSearch").append('<input type="hidden" name="post[' + t.id + ']" value="on">')), 2 == $("#CBvacancy").val() && ($('#FmSearch [name="post[]"]').remove(), $("#FmSearch").append('<input type="hidden" name="posts[]" value="' + t.id + '">')), 3 == $("#CBvacancy").val() && (window.location.href = MainConfig.PAGE_SEARCH_PROMO + "/" + t.code);
  }, t.prototype.onChangeSearchTypeFn = function (t, e) {
    var n = $(e), i = $("#EdSearch"), o = $("#FmSearch"), a = n.val();
    i.val(""), $('#FmSearch [name="posts[]"]').remove(), $('#FmSearch [name="post[]"]').remove(), 1 == a && o.attr("action", MainConfig.PAGE_SEARCH_VAC), 2 == a && o.attr("action", MainConfig.PAGE_SEARCH_PROMO), 3 == a && o.attr("action", MainConfig.PAGE_SEARCH_EMPL), i.attr("placeholder", i.data("ph" + a));
  }, t;
}(), VacResponses = function () {
  function t() {
  }

  return t.prototype.doResponse = function (t, e) {
    $.get(MainConfig.AJAX_POST_SETVACATIONRESPONSE, {id: G_VARS.idVac}, function (t) {
      t = JSON.parse(t), ModalWindow.open({content: t.html}), e(t);
    });
  }, t;
}(), Page = function () {
  function t() {
    this.FLAG_STOP = 0, this.FormCheckers = new FormCheckers;
  }

  return t.prototype.bindFiltersFn = function (t) {
    var e = new FormFilters;
    e.bindFiltersFn(t), this.FormFilter = e;
  }, t.prototype.onFormSubmit = function (t, e, n) {
    (n = n || {}).onBeforeSubmit && (n.inJustReturn = 1), n = n || {};
    var i = this.FormCheckers.FormSubmit(CommFuncs.merge({event: t, form: e}, n));
    return i && n.onBeforeSubmit && (n.onBeforeSubmit() ? e.submit() : i = !1), i;
  }, t;
}(), PageSearchVacs = function () {
  function t() {
    this.CBcityMulti = {};
    var n = this;
    $("#ChkAllContacts").change(function (t, e) {
      n.onChkAllContactsChangeFN(this, e);
    }), $(".filter-dolj input.dolj").bind("customOnCheck", function () {
      n.onDoljCustomOnCheckFN(this);
    }), $("#EdSalPerHF, #EdSalPerHT").keypress(function (t) {
      $("#RBShour").click();
    }), $("#EdSalPerWF, #EdSalPerWT").keypress(function (t) {
      $("#RBSweek").click();
    }), $("#EdSalPerMF, #EdSalPerMT").keypress(function (t) {
      $("#RBSmonth").click();
    }), n.init();
  }

  return t.prototype.init = function () {
    var e = this;
    $(".filter-label .filter-name").click(function (t) {
      e.onFilterNameClickFn(t, this);
    }), Hinter.bind(".premium ._head", {animation: "swing"}), Hinter.bind(".vac-num span");
    var n = 0;
    $(".table-view .premium .border").each(function () {
      var t = $(this);
      t.outerHeight() > n && (n = t.outerHeight());
    }).each(function () {
      $(this).css({minHeight: n + "px"});
    }), $("#CBcities").change(function () {
    }).multipleSelect({
      placeholder: "выберите город...",
      selectAllText: "Все/снять выделение",
      allSelected: "",
      multipleWidthType: 120,
      minimumCountSelected: 2,
      countSelected: "# / %",
      noMatchesFound: "Добавьте город =>"
    }), e.CBcityMulti = new DDMultiAjax("#CBcities", {
      loadingObj: "#DiLoading",
      insertAllow: 0,
      width: 200,
      insertFirst: 1,
      loadingGIF: MainConfig.PATH_PIC + "loading2.gif",
      labelText: "Введите название города",
      btnAddHint: "Добавить город",
      ajaxParams: {inputName: "filter", url: MainConfig.AJAX_GET_CITYES, addParams: {idco: 0, limit: 20, getCity: 1}},
      afterItemSelected: function (t) {
      }
    });
  }, t.prototype.onChkAllContactsChangeFN = function (t, e) {
    var n = $(t).is(":checked");
    e || $(".filter-dolj input.dolj").each(function () {
      var t = $(this);
      t[0].checked = n, t.trigger("change", {noFireEvent: 1});
    });
  }, t.prototype.onDoljCustomOnCheckFN = function (t) {
    var e = 0;
    $(".filter-dolj input.dolj").each(function () {
      if (!$(this)[0].checked) {
        return !(e = 1);
      }
    });
    var n = $("#ChkAllContacts");
    n[0].checked = !e, n.trigger("change", {isJustUnchk: 1});
  }, t.prototype.onFilterNameClickFn = function (t, e) {
    var n = $(e);
    n.hasClass("opened") ? n.closest(".filter-label").find(".filter-content").slideUp(200) : n.closest(".filter-label").find(".filter-content").slideDown(400), n.toggleClass("opened");
  }, t.prototype.onFilterApplyClickFn = function (t, e) {
    var n = $("#F1Filter").serialize();
    if (1 == $G_PAGE_VIEW) {
      d = $(".table-view");
    } else {
      var d = $(".list-view");
    }
    G_VARS.App.showLoading(d, 0, {
      top: 1,
      pic: 3
    }), 2 == $G_PAGE_VIEW && (n += "&addmetro=1"), $.post(MainConfig.AJAX_POST_GETVACS, n, function (t) {
      if (t = JSON.parse(t), d.empty(), $("body").animate({scrollTop: 0}, 500), t && 0 < t.length && !t.error) {
        var e = 1;
        for (var n in t) {
          var i = t[n];
          if ("length" != n) {
            if (1 == $G_PAGE_VIEW) {
              for (var o in (r = $(".tab-view-tpl").clone()).toggleClass("tab-view-tpl"), 1 == i.ispremium && r.addClass("premium"), a = "", i.city) {
                a = a + i.city[o] + ", ";
              }
              for (var o in a = a.substr(0, a.length - 2), r.find(".city").append(a), a = "", i.post) {
                a = a + i.post[o] + ", ";
              }
              a = a.substr(0, a.length - 2), r.find("h3 a").text(a).attr("href", function (t, e) {
                return e + i.id;
              }), a = "", "1" == i.isman && (a = "Юноши"), "1" == i.isman && "1" == i.iswoman && (a += ", "), "1" == i.iswoman && (a += "Девушки"), "1" != i.isman && "1" != i.iswoman || r.find("h3").after(a + "<br>"), r.find(".istemp").text("1" == i.istemp ? "Постоянная" : "Временная");
              var a = "", s = 0;
              0 < i.shour && (a = "<span class='nowrap'>".concat(i.shour, " руб/час</span>"), s = 1), s && 0 < i.sweek && (a += ", "), 0 < i.sweek && (a += "<span class='nowrap'>" + i.sweek + " руб/неделю</span>", s = 1), s && 0 < i.smonth && (a += ", "), 0 < i.smonth && (a += "<span class='nowrap'>" + i.smonth + " руб/мес</span>"), s || i.smonth ? r.find(".payment").html(function (t, e) {
                return e + a + "<br>";
              }) : r.find(".payment").remove(), r.find(".bdate").text(i.bdate), "1" == i.istemp ? r.find(".edate").remove() : r.find(".edate span").text(i.edate), r.find(".company").text(i.coname), r.find(".date").text(i.crdate), d.append(r), e % 2 == 0 && $("<div class='clear visible-sm'></div>").appendTo(d), e % 3 == 0 && $("<div class='clear visible-md visible-lg'></div>").appendTo(d), e++;
            } else {
              var r = $(".list-view-tpl").clone();
              r.toggleClass("list-view-tpl"), 1 == i.ispremium && r.addClass("premium"), r.find(".num").text(i.id), r.find(".crdate").text(i.crdate), r.find(".title").text(i.title);
              var c = "" == i.logo ? G_DEF_LOGO : i.logo;
              for (var o in r.find(".company-logo img").attr("src", function (t, e) {
                return e + c;
              }), a = "", i.post) {
                a = a + i.post[o] + ", ";
              }
              for (var o in a = a.substr(0, a.length - 2), r.find("h2").text(a + " (" + i.id + ")").attr("href", function (t, e) {
                return e + i.id;
              }), a = "", "1" == i.isman && (a = "Юноши"), "1" == i.isman && "1" == i.iswoman && (a += ", "), "1" == i.iswoman && (a += "Девушки"), "1" == i.isman || "1" == i.iswoman ? r.find(".sexval").html(a + "<br>") : r.find(".sex").remove(), a = "", (s = 0) < i.shour && (a = "<span class='nowrap'>".concat(i.shour, " руб/час</span>"), s = 1), s && 0 < i.sweek && (a += ", "), 0 < i.sweek && (a += "<span class='nowrap'>" + i.sweek + " руб/неделю</span>", s = 1), s && 0 < i.smonth && (a += ", "), 0 < i.smonth && (a += "<span class='nowrap'>" + i.smonth + " руб/мес</span>"), s || i.smonth ? r.find(".paymentval").html(function (t, e) {
                return e + a + "<br>";
              }) : r.find(".payment").remove(), a = "", i.city) {
                a = a + i.city[o] + ", ";
              }
              for (var o in a = a.substr(0, a.length - 2), r.find(".city").html(a), a = "", i.metroes) {
                a = a + i.metroes[o] + ", ";
              }
              (a = a.substr(0, a.length - 2)).length ? r.find(".metroval").html(a) : r.find(".metro").remove(), r.find(".duties").text(i.duties), r.find(".istemp").text("1" == i.istemp ? "Постоянная" : "Временная"), r.find(".bdate").text(i.bdate), "1" == i.istemp ? r.find(".edate").remove() : r.find(".edate span").text(i.edate), r.find(".company-logo .name").text(i.coname), d.append(r);
            }
          }
        }
        var l = 0;
        $(".table-view .premium .border").each(function () {
          var t = $(this);
          t.outerHeight() > l && (l = t.outerHeight());
        }).each(function () {
          $(this).css({minHeight: l + "px"});
        });
      } else {
        d.append("<div class='nodata'>Данным условиям нет подходящих вакансий</div>");
      }
    }).always(function () {
      G_VARS.App.hideLoading();
    });
  }, t;
}(), PageRegister = function (t) {
  function e() {
    t.call(this), this.FLAG_STOP = 0;
    var e = this;
    $("#F1registerEmpl").on("submit", function (t) {
      e.onFormSubmit(t, this);
    }), $("#F1registerAppl").on("submit", function (t) {
      e.onFormSubmit(t, this);
    }), e.init();
  }

  return __extends(e, t), e.prototype.init = function () {
    $("#EdBdate").datepicker({
      format: "dd.mm.yyyy",
      todayBtn: "linked",
      calendarWeeks: !0,
      language: "ru"
    }), $("#EdBdate").datepicker("update", new Date(moment($("#EdBdate").val(), "DD.MM.YYYY"))), this.bindFiltersFn();
  }, e.prototype.onF1registerSubmitFn = function (t, e) {
    var n = this, i = "", o = 0;
    if (o || "" == (s = (a = $("#EdType")).val()) && (o = 1, i = "Необходимо выбрать тип компании"), o || "" == (s = (a = $("#EdEmail")).val()) && (o = 1, i = "Необходимо заполнить электронный адрес"), o || "" == (s = (a = $("#EdPass")).val()) && (o = 1, i = "Необходимо заполнить пароль"), !o) {
      var a = $("#EdPass"), s = a.val();
      s != $("#EdPassRep").val() && (o = 1, i = "Пароль и его подтверждение не совпадают");
    }
    if (o) {
      var r = n.FLAG_STOP = 1;
      r = $("*").is(a) ? a.offset().top : 1, $("body").stop().animate({scrollTop: r - 30 + "px"}, 500, function () {
        var t = $(".error-hint-box");
        t.text(i).css({
          left: a.offset().left,
          top: r + a.outerHeight() + 10
        }), t.stop().fadeIn(400), a.removeClass("field--success"), a.addClass("field--warning"), a.focus(), a.on("blur", function () {
          n.FLAG_STOP || t.fadeOut(200), $(this).off("blur");
        }), n.FLAG_STOP = 1;
      }), t.preventDefault();
    }
  }, e;
}(Page), PageApplicantProfile = function (t) {
  function e() {
    t.call(this);
    var e = this;
    e.CommentAdditor = new CommentAdditor, $("#BtnComment a").click(function (t) {
      e.onCommentClick(t);
    }), HiddenText.init({
      wrapper: ".comment .text-wrapp",
      content: ".text",
      openText: "Смотреть полностью",
      closeText: "Свернуть",
      hiddenImg: ""
    });
  }

  return __extends(e, t), e.prototype.onCommentClick = function (t) {
    if (FLAG_MOBILE) {
      return 1;
    }
    t.preventDefault(), this.CommentAdditor.open({
      getUrl: "/test/ajax/ajax-applicant-profile-own-comment.php",
      formAction: "/test/ajax/ajax-applicant-profile-own-comment.php"
    });
  }, e;
}(Page), PageApplicantProfileOwn = function (t) {
  function e() {
    t.call(this);
    var e = this;
    e.CommentAdditor = new CommentAdditor, $("#BtnComment a").click(function (t) {
      e.onCommentClick(t);
    }), HiddenText.init({
      wrapper: ".comment-box .text-wrapp",
      content: ".text",
      openText: "Смотреть полностью",
      closeText: "Свернуть",
      openBtnClass: "look-full-comm",
      hiddenImg: ""
    }), $(".affective-perc").hover(function (t) {
      e.showHintBox($(this), {
        content: "Чтобы повысить эффективность своего профиля: заполните как можно больше полей в своём профиле. Для этого нажмите кнопку редактирования профиля",
        posFunc: function (t, e) {
          var n;
          e.removeClass().addClass("hint-box"), 768 < $(window).width() && $(window).width() < 1200 ? e.css({left: t.offset().left + (t.outerWidth() - e.outerWidth()) / 2}).addClass("top-center") : e.css({left: t.offset().left + t.outerWidth() - e.outerWidth()}), n = $("*").is(t) ? t.offset().top : 1, e.css({top: n + t.height() + 20});
        }
      });
    }, function () {
      e.showHintBox(null, null, 0);
    }), e.init();
  }

  return __extends(e, t), e.prototype.init = function () {
    var e = this;
    $(".user-info-blocks").matchHeight({property: "min-height"}), $(".js-btn-invite a").click(function (t) {
      e.onInviteBtnClick(t, this);
    });
  }, e.prototype.onInformationClic = function (t, e) {
    var n = $(".Infos").clone();
    ModalWindow.open({
      content: n,
      action: {active: 0},
      bgIsCloseBtn: 0,
      position: "absolute",
      context: "body",
      afterOpen: function () {
        $("#DiSiteWrapp").css({overflow: "hidden"});
      },
      afterClose: function () {
        $("#DiSiteWrapp").css({overflow: ""});
      }
    });
  }, e.prototype.onCommentClick = function (t) {
    if (FLAG_MOBILE) {
      return 1;
    }
    t.preventDefault(), this.CommentAdditor.open({
      getUrl: "/test/ajax/ajax-applicant-profile-own-comment.php",
      formAction: "/test/ajax/ajax-applicant-profile-own-comment.php"
    });
  }, e.prototype.showHintBox = function (t, e, n) {
    void 0 === n && (n = 1);
    var i = $(".hint-box"), o = $(".hint-box::before");
    if (n) {
      var a = {
        content: "",
        width: "300px",
        positon: "bottom",
        bgcolor: "#abb820",
        color: "#fff",
        fontSize: "13px",
        triangle: {bottom: {right: 10}},
        posFunc: ""
      };
      if (e.triangle && $.extend(a.triangle.bottom, e.triangle.bottom), $.extend(a.triangle, e.triangle), $.extend(a, e), e = a, i.find("span").text(e.content), i.css({
          width: e.width,
          background: e.bgcolor,
          color: e.color,
          fontSize: e.fontSize
        }), o.css({background: e.bgcolor}), "bottom" == e.positon || e.positon, e.posFunc) {
        e.posFunc(t, i);
      } else {
        if ("bottom" == e.positon) {
          var s;
          s = $("*").is(t) ? t.offset().top : 1, i.css({
            left: t.offset().left + t.outerWidth() - i.outerWidth(),
            top: s + t.height() + 20
          });
        }
      }
      i.fadeIn(400);
    } else {
      i.fadeOut(200);
    }
  }, e.prototype.onInviteBtnClick = function (t, e) {
    var s = this;
    t.preventDefault(), $.get(MainConfig.AJAX_GET_GETVACANCIES, {}, function (t) {
      try {
        if (0 < Object.keys(t.vacs).length) {
          var e = (a = $($("#TplInvVacs").text())).find("select");
          for (var n in t.vacs) {
            var i = t.vacs[n], o = $("<option/>");
            o.val(i.id), o.text(i.title + " (" + i.id + ")"), e.append(o);
          }
          ModalWindow.open({
            content: a, action: {
              btnTitle: $("#TplInvVacs").data("btn"), onClick: function () {
                s.doInvite();
              }
            }
          });
        } else {
          var a = $($("#TplInvNoVacs").text());
          ModalWindow.open({content: a});
        }
      } catch (t) {
        t.code, t.message;
      }
    }, "json");
  }, e.prototype.doInvite = function () {
    ModalWindow.loadingOn();
    var t = {id: $("#CbVacs").val(), idPromo: G_VARS.App.customProps.idPromo}, o = _t("inviteError");
    $.post(MainConfig.AJAX_POST_INVITE, t, function (t) {
      var e = 0;
      try {
        if (100 != t.error) {
          throw t.error < 0 ? new CustomError(t.message, -102, t.error) : new CustomError(o, -101);
        }
        (i = $($("#TplInvSuccess").text())).text(t.message), ModalWindow.redraw({content: i});
      } catch (t) {
        var n = t.code;
        o = t.message, t.retCode && (n += ":" + t.retCode), e = 1;
      }
      if (e) {
        console.warn("E", n);
        var i = $($("#TplInvSuccess").text());
        i.text(o), ModalWindow.redraw({content: i});
      }
    }, "json").fail(function () {
      var t = $($("#TplInvSuccess").text());
      t.text(o), ModalWindow.redraw({content: t});
    });
  }, e;
}(Page), PageApplicantComments = function (t) {
  function e() {
    t.call(this);
    var e = this;
    e.CommentAdditor = new CommentAdditor, $(".btn-comment a").click(function (t) {
      e.onCommentClick(t);
    });
  }

  return __extends(e, t), e.prototype.onCommentClick = function (t) {
    t.preventDefault(), this.CommentAdditor.open({
      getUrl: "/test/ajax/ajax-applicant-profile-own-comment.php",
      formAction: "/test/ajax/ajax-applicant-profile-own-comment.php"
    });
  }, e;
}(Page), PageCompanyProfileOwn = function (t) {
  function e() {
    t.call(this), HiddenText.init({
      wrapper: ".comment-box .text-wrapp",
      openBtnClass: "look-full-comm",
      content: ".text",
      openText: "Смотреть полностью",
      closeText: "Свернуть",
      hiddenImg: ""
    }), this.init();
  }

  return __extends(e, t), e.prototype.init = function () {
    var e = this;
    1 == G_VARS.Modal && $(function (t) {
      e.onInformationClick(t, this);
    });
  }, e;
}(Page);
PageCompanyProfileOwn.prototype.onInformationClick = function (t, e) {
  var n = $(".Info").clone();
  ModalWindow.open({
    content: n,
    action: {active: 0},
    bgIsCloseBtn: 0,
    position: "absolute",
    context: "body",
    afterOpen: function () {
      $("#DiSiteWrapp").css({overflow: "hidden"});
    },
    afterClose: function () {
      $("#DiSiteWrapp").css({overflow: ""});
    }
  });
};
var PageApplicantList = function () {
  function t() {
    this.doljChoice = 19, this.cityMoskowChoice = 1001, this.cityPiterChoice = 1002, this.CBcityMulti = {};
    var e = this;
    $(".filter-dolj input.dolj").bind("customOnCheck", function () {
      e.onDoljCustomOnCheckFN(this);
    }), $(".filter-open a").click(function (t) {
      e.onFilterOpenClickFn(t, this);
    }), e.init();
  }

  return t.prototype.init = function () {
    var e = this, t = {selectAllText: "Выбрать все", countSelected: "# из %", allSelected: "Выбраны все"};
    $("#CBcities").change(function () {
    }).multipleSelect(CommFuncs.merge(t, {
      placeholder: "выберите город...",
      allSelected: "",
      noMatchesFound: "Добавьте город =>",
      onUncheckAll: function (t) {
        e.onCBcityChangeFn(t);
      },
      onClick: function (t) {
        e.onCBcityChangeFn(t);
      },
      onCheckAll: function (t) {
        e.onCBcityChangeFn(t, 1);
      }
    })), e.CBcityMulti = new DDMultiAjax("#CBcities", {
      loadingObj: "#DiLoading",
      insertAllow: 0,
      width: 180,
      insertFirst: 1,
      loadingGIF: MainConfig.PATH_PIC + "loading2.gif",
      labelText: "Введите название города",
      btnAddHint: "Добавить город",
      ajaxParams: {inputName: "filter", url: MainConfig.AJAX_GET_CITYES, addParams: {idco: 0, limit: 20, getCity: 1}},
      afterItemSelected: function (t) {
        e.onCityChangeFn(t);
      }
    }), $(".metro-block select").change(function () {
    }).multipleSelect(CommFuncs.merge(t, {
      placeholder: "выберите метро...",
      filter: !0
    })), $("#CBdolj").change(function () {
    }).multipleSelect(CommFuncs.merge(t, {
      placeholder: "выберите должность...", filter: !0, onUncheckAll: function (t) {
        e.onCBdoljChangeFn(t);
      }, onClick: function (t) {
        e.onCBdoljChangeFn(t);
      }, onCheckAll: function (t) {
        e.onCBdoljChangeFn(t, 1);
      }
    }));
  }, t.prototype.onCBcityChangeFn = function (t, e) {
    var i = $("#CBcities").multipleSelect("getSelects"), o = ($("#CBcities").multipleSelect("getSelects", "text"), []);
    $("#DiMetroesBlock .metro-block").each(function () {
      var t = parseInt($(this).attr("data-id"));
      o.push(t);
      var e = 0;
      for (var n in i) {
        if (t == i[n]) {
          e = 1;
          break;
        }
      }
      e ? $(this).slideDown(400, function () {
        $(this).find("select").prop("disabled", !1);
      }) : $(this).slideUp(200, function () {
        $(this).find("select").prop("disabled", !0);
      });
    });
  }, t.prototype.onCityChangeFn = function (r) {
    var c = r ? r[0] : 0, t = 0;
    for (var e in this.CBcityMulti.ajaxRetData) {
      if (this.CBcityMulti.ajaxRetData[e].id == c && "1" == this.CBcityMulti.ajaxRetData[e].ismetro) {
        t = 1;
        break;
      }
    }
    t && 0 < c && !G_VARS.appcache["metro" + c] && $.get(MainConfig.AJAX_GET_METRO, {idcity: c}, function (t) {
      t = JSON.parse(t), G_VARS.appcache["metro" + c] = t;
      var e = $(".metro-tpl").clone();
      e.attr("data-id", c).toggleClass("metro-tpl metro-block"), e.hide();
      var n = e.find("label"), i = n.attr("for") + c;
      n.attr("for", i), n.text(n.text() + "".concat(" (", r[1], "):"));
      var o = e.find("select");
      for (var a in o.attr("id", i), o.attr("name", "metro[]"), t) {
        var s = t[a];
        $("<option value='".concat(s.id, "'>", s.name, "</option>")).appendTo(o);
      }
      $("#DiMetroesBlock").append(e), e.slideDown(400), o.multipleSelect({
        placeholder: "выберите метро...",
        selectAllText: "Выбрать все/снять выделение",
        allSelected: "Выбраны все",
        filter: !0,
        countSelected: "# / %"
      });
    }).always(function () {
    });
  }, t.prototype.onCBdoljChangeFn = function (t, e) {
    var n = $("#CBdolj").multipleSelect("getSelects");
    -1 < $.inArray(this.doljChoice + "", n) ? $(".self-dolj").slideDown(400, function () {
      $(this).css({display: "block"});
    }) : $(".self-dolj").slideUp(200);
  }, t.prototype.onFilterOpenClickFn = function (t, e) {
    t.preventDefault();
    var n = $(e);
    $(".filter").slideToggle(400), n.closest("div").toggleClass("opened");
  }, t;
}(), PageCompanyList = function () {
  function t() {
    this.doljChoice = 19, this.cityMoskowChoice = 1001, this.cityPiterChoice = 1002, this.CBcityMulti = {};
    var e = this;
    $(".filter-open a").click(function (t) {
      e.onFilterOpenClickFn(t, this);
    }), $(".page-search-empl").on("click", ".btn-rate-details a", function (t) {
      e.onRateDetailClickFn(t, this);
    }), e.init();
  }

  return t.prototype.init = function () {
    var e = this,
      t = {selectAllText: "Выбрать все/снять выделение", countSelected: "# из %", allSelected: "Выбраны все"};
    $("#CBcities").change(function () {
    }).multipleSelect(CommFuncs.merge(t, {
      placeholder: "выберите город...",
      allSelected: "",
      noMatchesFound: "Добавьте город =>",
      onUncheckAll: function (t) {
        e.onCBcityChangeFn(t);
      },
      onClick: function (t) {
        e.onCBcityChangeFn(t);
      },
      onCheckAll: function (t) {
        e.onCBcityChangeFn(t, 1);
      }
    })), e.CBcityMulti = new DDMultiAjax("#CBcities", {
      loadingObj: "#DiLoading",
      insertAllow: 0,
      width: 180,
      insertFirst: 1,
      loadingGIF: MainConfig.PATH_PIC + "loading2.gif",
      labelText: "Введите название города",
      btnAddHint: "Добавить город",
      ajaxParams: {inputName: "filter", url: MainConfig.AJAX_GET_CITYES, addParams: {idco: 0, limit: 20, getCity: 1}},
      afterItemSelected: function (t) {
      }
    }), $("#CBtype").change(function () {
    }).multipleSelect(CommFuncs.merge(t, {placeholder: "выберите тип...", minimumCountSelected: 2}));
  }, t.prototype.onCBcityChangeFn = function (t, e) {
    $("#CBcities").multipleSelect("getSelects");
  }, t.prototype.onCBdoljChangeFn = function (t, e) {
    var n = $("#CBdolj").multipleSelect("getSelects");
    -1 < $.inArray(this.doljChoice + "", n) ? $(".self-dolj").slideDown(400, function () {
      $(this).css({display: "block"});
    }) : $(".self-dolj").slideUp(200);
  }, t.prototype.onFilterOpenClickFn = function (t, e) {
    t.preventDefault();
    var n = $(e);
    $(".filter").slideToggle(400), n.closest("div").toggleClass("opened");
  }, t.prototype.onRateDetailClickFn = function (t, e) {
    var s = $(e);
    t.preventDefault(), G_VARS.App.showLoading(s, 0, {offsetLeft: s.outerWidth() + 5}), $.get(MainConfig.AJAX_GET_GETEMPLRATE, {id: s.data("id")}, function (t) {
      t = JSON.parse(t);
      var e = s.closest(".rate-block").find("table.rate"), n = e.find("thead tr");
      for (var i in t.rate.pointRate) {
        var o = t.rate.pointRate[i], a = n.clone().removeClass("rate-tpl");
        a.find(".val .num").text(o[0] - o[1]), a.find(".val .good").text(o[0]), a.find(".val .bad").text(o[1]), a.find(".progress .progr-line").addClass(o[0] > o[1] ? "progress-green" : "progress-red").css({width: o[0] - o[1] == 0 ? 0 : 100 * Math.abs(o[0] - o[1]) / t.rate.maxPointRate + "%"}), a.find(".text").text(t.rate.rateNames[i]), e.find("tbody").append(a);
      }
      e.hide().removeClass(".hide-rate").slideDown(400), s.fadeOut(200);
    }).always(function () {
      G_VARS.App.hideLoading();
    });
  }, t.prototype.onFilterApplyClickFn = function (t, e) {
    var r = this, n = $("#F1Filter").serialize(), c = $(".list-view");
    G_VARS.App.showLoading(c, 0, {top: 1, pic: 3}), $.post(MainConfig.AJAX_POST_GETEMPLS, n, function (t) {
      if (t = JSON.parse(t), console.info("data", t), c.empty(), $("body").animate({scrollTop: 0}, 500), t && 0 < t.length && !t.error) {
        for (var e in t.empls) {
          var n = t.empls[e];
          if ("length" != e) {
            var i = $(".empl-item-tpl").clone();
            i.toggleClass("empl-item-tpl"), i.find("h2 small i").text(n.id), i.find("h2 a").text(n.name), i.find(".com-rate .pos").text(n.rate), i.find(".com-rate .neg").text(n.rate_neg), i.find(".btn-rate-details a").attr("data-id", n.id_user), i.find(".profile-link").attr("href", function (t, e) {
              return e + n.id;
            });
            var o = n.logo && "" != n.logo ? n.logo : G_VARS.DEF_LOGO_EMPL;
            for (var a in i.find(".company-logo img").attr("src", function (t, e) {
              return e + o;
            }), s1 = "", n.city) {
              s1 = s1 + n.city[a] + ", ";
            }
            for (var a in s1 = s1.substr(0, s1.length - 2), i.find(".city").html(s1), s1 = "", n.metroes) {
              s1 = s1 + n.metroes[a] + ", ";
            }
            s1 = s1.substr(0, s1.length - 2), "" == s1 ? i.find(".metroes").remove() : i.find(".metroes small").html(s1), i.find(".cotype small").text(n.tname), c.append(i), i.find(".btn-rate-details a").on("click", function (t) {
              r.onRateDetailClickFn(t, this);
            });
          }
        }
        var s = 0;
        $(".table-view .premium .border").each(function () {
          var t = $(this);
          t.outerHeight() > s && (s = t.outerHeight());
        }).each(function () {
          $(this).css({minHeight: s + "px"});
        });
      } else {
        c.append("<div class='nodata'>Данным условиям нет подходящих вакансий</div>");
      }
    }).always(function () {
      G_VARS.App.hideLoading();
    });
  }, t;
}(), PageCompanyServices = function () {
  function t() {
    this.doljChoice = 19, this.cityMoskowChoice = 1001, this.cityPiterChoice = 1002;
    var e = this;
    $(".menu a").click(function (t) {
      e.onMenuClickFn(t, this);
    }), e.init();
  }

  return t.prototype.init = function () {
  }, t.prototype.onMenuClickFn = function (t, e) {
    t.preventDefault();
    var n, i = $(e).prop("hash").replace(/#/, "");
    n = $("*").is("a[name=" + i + "]") ? $("a[name=" + i + "]").offset().top : 1, $("body").stop().animate({scrollTop: n - 20 + "px"}, 500);
  }, t;
}(), PageServices = function (t) {
  function e() {
    t.call(this), this.init();
  }

  return __extends(e, t), e.prototype.init = function () {
    var e = this;
    $(".btn-same-adr button").click(function (t) {
      e.onSameAdrClickFn(t, this);
    }), $(".order-btn a").click(function (t) {
      e.onOrderServiceClickFn(t, this);
    }), $(".page-services .services__item-block").click(function (t) {
      e.onServiceInDevClickFn(t, this);
    }), $(".page-services .services__sub-item").click(function (t) {
      e.onServiceInDevClickFn(t, this);
    }), $(".btn-order-prommucard button").click(function (t) {
      $(".form").slideDown(400), $(this).parent().slideUp(200);
    });
    var i = new Uploaduni;
    $("#UplImg").change(function () {
      e.onUploadFileSetFn(this, i);
    }), $(".btn-upload button").click(function (t) {
      $("#F2upload").find(".message").text("");
      var e = $("#UplImg");
      e.replaceWith(e.clone(!0)), $("#UplImg").trigger("click");
    }), i.init({
      uploadConnector: MainConfig.AJAX_POST_UPLOADUNI,
      scope: "services",
      imgBlockTmpl: "doc-scan-tpl",
      imgsWrapper: "#DiImgs",
      lnktoimg: "orig",
      uploadForm: "#F2upload",
      messageBlock: ".message",
      loadingBLock: ".loading-ico"
    }), i.setFiles(G_VARS.uniFiles), $(".btn-order").click(function (t) {
      t.preventDefault(), $("#F1cardOrder").submit();
    }), $("#F1cardOrder").submit(function (t) {
      var e = i.getFiles();
      for (var n in e) {
        e[n], $('<input type="hidden" name="files[]" value="' + n + '"/>').appendTo("#F1cardOrder");
      }
    }), e.bindFiltersFn();
  }, e.prototype.onOrderCreateFn = function (t, e) {
    var n = $(e), i = (new FormCheckers).FormSubmit({event: t, form: n.closest("form"), justReturn: 1});
    if (t.preventDefault(), checkFields = new InputFields, checkFields.checkPhone("#service-phone") || (i = !1), checkFields.checkEmail("#service-email") || (i = !1), i) {
      var o = n.closest("form").serialize();
      $.post(MainConfig.AJAX_POST_CREATESERVICEORDER, o, function (t) {
        t = JSON.parse(t);
        var e = $(".order-success-tpl").clone();
        e.toggleClass("order-success-tpl tmpl order-success"), ModalWindow.redraw({content: e, action: {active: 1}});
      });
    }
  }, e.prototype.onSameAdrClickFn = function (t, e) {
    $("#EdAddr").val($("#EdRegaddr").val()), $("#CbCountry").val($("#CbRegcountry").val());
  }, e.prototype.onOrderServiceClickFn = function (t, e) {
    var n = this, i = $(e).parent().data("id");
    if (t.preventDefault(), "sms" == i) {
      (o = $(".services-form.sms-form").clone()).toggleClass("services-form tmpl"), ModalWindow.open({
        content: o,
        action: {active: 0},
        additionalStyle: "light-ver",
        afterOpen: function () {
          $(".mw-win").css({position: "fixed", top: "40%"});
        }
      });
    } else {
      if ("push" == i) {
        (o = $(".services-form.push-form").clone()).toggleClass("services-form tmpl"), $("body").animate({scrollTop: 0}, 500), ModalWindow.open({
          content: o,
          action: {active: 0},
          additionalStyle: "dark-ver",
          afterOpen: function () {
            $(".mw-win.dark-ver").css({position: "absolute", margin: "0 0 0 50%", left: "-175px", top: "100px"});
          }
        });
      } else {
        if ("premium" == i) {
          var o = $(".services-form.premium-form").clone();
          o.toggleClass("services-form tmpl"), ModalWindow.open({
            content: o,
            action: {active: 0},
            additionalStyle: "light-ver",
            afterOpen: function () {
              $(".mw-win").css({position: "fixed", top: "40%"});
            }
          });
        } else {
          $.get(MainConfig.AJAX_GET_GETSERVICE, {id: i}, function (t) {
            t = JSON.parse(t);
            var e = $(".form-order-tpl").clone();
            e.attr("data-title", t.name), e.find("#HiId").val(i), checkFields = new InputFields, checkFields.setPhoneMask(e.find("#service-phone")), e.toggleClass("form-order-tpl tmpl form-order"), ModalWindow.open({
              content: e,
              action: {active: 0},
              afterOpen: function () {
                $(".mw-win").css({position: "fixed", top: "40%"});
              }
            }), e.find(".btn-order-create").click(function (t) {
              n.onOrderCreateFn(t, this);
            }), e.find("#service-phone").change(function () {
              checkFields.checkPhone("#service-phone");
            }), e.find("#service-email").change(function () {
              checkFields.checkEmail("#service-email");
            });
          });
        }
      }
    }
  }, e.prototype.onServiceInDevClickFn = function (t, e) {
    if ($(e).data("disable")) {
      var n = $(".services-form.disable-form").clone();
      n.toggleClass("services-form tmpl"), ModalWindow.open({
        content: n,
        action: {active: 0},
        additionalStyle: "dark-ver"
      });
    }
  }, e.prototype.onOrderPrommucardClickFn = function (t, e) {
    $(e).parent().data("id"), t.preventDefault();
  }, e.prototype.onUploadFileSetFn = function (t, e) {
    e.upload(t);
  }, e;
}(Page), PageLogin = function (t) {
  function e() {
    t.call(this);
  }

  return __extends(e, t), e;
}(Page), PageVacancyView = function () {
  function t() {
    var e = this;
    e.VacResponses = new VacResponses, $(".btn-show-contacts a").click(function (t) {
      e.onShowContactsCLick(t, this);
    }), $(".btn-response a").click(function (t) {
      e.onResponseClickFn(t, this);
    }), e.init();
  }

  return t.prototype.init = function () {
    var t, e = this;
    $(window).innerWidth() < 768 && $("#DiCompInfo").attr("class", "").appendTo("#DiComp"), CommFuncs.parseUrl().tab && (t = $("*").is(".tabs-panel") ? $(".tabs-panel").offset().top : 1, $("body").stop().animate({scrollTop: t - 20 + "px"}, 10)), Hinter.bind(".btn-edit-vac a"), $(".controls .view").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 1);
    }), $(".controls .cancel").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 3);
    }), $(".controls .apply").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 5);
    });
  }, t.prototype.onShowContactsCLick = function (t, e) {
    var n = $(e), i = $(".contacts-block");
    t.preventDefault(), $.get(MainConfig.AJAX_GET_GETEMPLCONTACTS, {id: G_VARS.eid, idvac: G_VARS.idvac}, function (t) {
      (t = JSON.parse(t)).mob && i.find(".mob").text(t.mob), t.addmob && i.find(".addmob").text(", " + t.addmob), t.addmob || t.mob || $(".tel-block").remove(), i.find(".email").text(t.email), i.slideDown(400), n.fadeOut(200);
    });
  }, t.prototype.onChangeStatusCLickFn = function (t, e, a) {
    var s = $(e);
    if (t.preventDefault(), 5 == a && !confirm("Подтвредите действие")) {
      return 0;
    }
    $.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, {idres: s.closest(".controls").data("sid"), s: a}, function (t) {
      if (!(t = JSON.parse(t)).error) {
        if (1 == a) {
          s.closest("tr").fadeOut(300, function () {
            $(this).remove();
          });
        } else {
          if (3 == a) {
            s.closest("tr").fadeOut(300, function () {
              $(this).remove();
            });
          } else {
            if (4 == a) {
              var e = s.closest(".controls");
              s.closest("tr").removeClass("-new"), e.find(".view").parent().fadeOut(300, function () {
                $(this).remove();
              }), e.find(".cancel").parent().fadeOut(300, function () {
                $(this).remove();
              }), s.parent().fadeOut(300, function () {
                $(this).remove(), e.find(".status").hide().removeClass("hide").fadeIn(400);
              });
            }
          }
        }
        var n = [0];
        for (var i in t.counts) {
          var o = t.counts[i];
          i = parseInt(i), CommFuncs.inArray(i, [0, 4, 5, 6, 7]) ? n[0] += o : n[i] = o;
        }
        $(".tabs-wrapp .tab1 span").text("(" + n[0] + ")"), $(".tabs-wrapp .tab2 span").text("(" + n[3] + ")"), $(".tabs-wrapp .tab3 span").text("(" + n[1] + ")");
      }
    });
  }, t.prototype.onResponseClickFn = function (t, e) {
    var n = $(e);
    t.preventDefault(), G_VARS.App.showLoading(n, 0, {
      align: "center",
      pic: 1,
      variant: 2
    }), this.VacResponses.doResponse(G_VARS.idVac, function (t) {
      G_VARS.App.hideLoading(), t.error || ($(".btn-response").fadeOut(200, function () {
        $(this).remove();
      }), $("body").append('<div class="prmu__popup"><p>' + t.message + "</p></div>"), $.fancybox.open({
        src: "body>div.prmu__popup",
        type: "inline",
        touch: !1,
        afterClose: function () {
          $("body>div.prmu__popup").remove();
        }
      }), $(".resp-message").text(t.message));
    });
  }, t;
}(), ResponsesCompany = function () {
  function t() {
    var e = this;
    e.VacResponses = new VacResponses, $(".controls .view").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 1);
    }), $(".controls .cancel").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 3);
    }), $(".controls .apply").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 5);
    }), e.init();
  }

  return t.prototype.init = function () {
    Hinter.bind(".js-hashint");
  }, t.prototype.onChangeStatusCLickFn = function (t, e, n) {
    var i = $(e).closest(".row")[0], o = $(e).closest(".controls")[0];
    if (t.preventDefault(), (3 == n || 5 == n) && !confirm("Подтвредите действие")) {
      return 0;
    }
    $(".content-block").addClass("load"), $.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, {
      idres: $(o).data("sid"),
      s: n
    }, function (t) {
      if (t = JSON.parse(t), $(".content-block").removeClass("load"), t.error) {
        return 0;
      }
      1 == n ? ($(i).removeClass("-new"), $(i).find(".label-new").remove(), $(e).fadeOut()) : 3 == n ? $(i).fadeOut() : 5 == n && ($(i).removeClass("-new"), $(i).find(".label-new").remove(), $(o).html('<div class="status">Заявка на вакансию подтверждена обеими сторонами</div>'));
    });
  }, t;
}(), ResponsesApplic = function () {
  function t() {
    var e = this;
    e.VacResponses = new VacResponses, $(".controls .js-cancel").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 3);
    }), $(".controls .apply").click(function (t) {
      e.onChangeStatusCLickFn(t, this, 5);
    }), e.init();
  }

  return t.prototype.init = function () {
    Hinter.bind(".js-hashint");
  }, t.prototype.onChangeStatusCLickFn = function (t, e, n) {
    var i = $(e).closest(".row")[0], o = $(e).closest(".controls")[0];
    if (t.preventDefault(), !confirm("Подтвредите действие")) {
      return 0;
    }
    $(".content-block").addClass("load"), $.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, {
      idres: $(o).data("sid"),
      s: n
    }, function (t) {
      if (t = JSON.parse(t), $(".content-block").removeClass("load"), t.error) {
        return 0;
      }
      3 == n ? $(i).fadeOut() : 5 == n && ($(i).removeClass("-new"), $(i).find(".label-new").remove(), $(o).html('<span class="status">Подтверждена обеими сторонами</span>'));
    });
  }, t;
}(), SetrateApplic = function (t) {
  function e() {
    t.call(this);
    var e = this;
    $(".rate-buttons-block a").click(function (t) {
      e.onSetRateClick(t, this, 5);
    }), $("#F1rate").submit(function (t) {
      e.onFormSubmit(t, this);
    });
  }

  return __extends(e, t), e.prototype.onSetRateClick = function (t, e, n) {
    var i = $(e);
    t.preventDefault(), i.closest("tr").find(".rate-value").val(i.data("val")), i.parent().find(".active").removeClass("active"), i.parent().find(".val").text(i.data("title")), i.parent().find(".val").removeClass("plus minus").addClass(i.attr("class")), i.addClass("active");
  }, e;
}(Page), PageImApplicant = function () {
  function t() {
    this.init();
  }

  return t.prototype.init = function () {
  }, t.prototype.loadChatsInfo = function (t, e) {
    $(e), t.preventDefault(), $(".attach input").click();
  }, t;
}(), Feedback = function (t) {
  function e() {
    t.call(this);
    var e = this;
    $("#F1feedback").submit(function (t) {
      e.onFormSubmit(t, this);
    });
  }

  return __extends(e, t), e;
}(Page), CommonPages = function () {
  function t() {
    var e = this;
    $("#DiContent .content-block p").click(function (t) {
      e.onQuestClick(t, this);
    });
  }

  return __extends(t, Page), t.prototype.onQuestClick = function (t, e) {
    var n = $(e);
    $("#DiContent .content-block").find("div").slideUp(200), setTimeout(function () {
      n.next().slideDown(400);
    }, 300);
  }, t;
}(), FLAG_DEBUG = 1, G_VARS = {
  appcache: {metro: {}},
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
  DEF_LOGO_EMPL: "",
  locale: G_LOCALE
};
G_VARS.App = new IndServ, MainConfig.SITE = G_SITE, $(document).ready(function () {
  if (G_VARS.App.init(), "vacancy" == G_PAGE && new PageVacancyView, "vacancy" == G_PAGE && new PageSearchVacs, "register" != G_PAGE && "register-applicant" != G_PAGE || new PageRegister, "applicant-profile" == G_PAGE && new PageApplicantProfile, "applocant-profile-own" != G_PAGE && "ankety" != G_PAGE || new PageApplicantProfileOwn, "applicant-comments" == G_PAGE && new PageApplicantComments, "company-profile-own" == G_PAGE && new PageCompanyProfileOwn, "applicant-list" != G_PAGE && "ankety" != G_PAGE || new PageApplicantList, "im" == G_PAGE && 2 == G_USER_TYPE && new PageImApplicant, "searchempl" != G_PAGE && "company-list" != G_PAGE || new PageCompanyList, "company-services" == G_PAGE && new PageCompanyServices, "services" == G_PAGE && new PageServices, "login" == G_PAGE && new PageLogin, "responses" == G_PAGE && 3 == G_USER_TYPE && new ResponsesCompany, "responses" == G_PAGE && 2 == G_USER_TYPE && new ResponsesApplic, "setrate" == G_PAGE && new SetrateApplic, "feedback" == G_PAGE && new Feedback, "page" == G_PAGE && "faq" == G_ACTION_ID && new CommonPages, $(".top-menu-wr__sandwich").click(function () {
      $(this).hasClass("active") ? $(".top-menu-wr__menu").fadeOut() : $(".top-menu-wr__menu").fadeIn(), $(this).toggleClass("active");
    }), window.arAdminNotifications = [], setTimeout(function () {
      $.ajax({
        type: "GET", url: MainConfig.AJAX_ADMIN_NOTIFICATIONS, dataType: "json", success: function (t) {
          !t.error && t.items.length && (arAdminNotifications = t.items, $("body").append('<div class="admin_notifications"><div class="circlephone"></div><div class="circle-fill"></div><div class="img-circle"><div class="img-circleblock"></div></div></div>'), $(".admin_notifications").fadeIn());
        }
      });
    }, 1000), $("body").on("click", ".admin_notifications", function () {
      if ($(this).hasClass("active")) {
        $(".admin_notifications").removeClass("active"), $(".admin_notifications_list").remove(), $(".admin_notifications_veil").remove();
      } else {
        var t = '<div class="admin_notifications_veil"></div><div class="admin_notifications_list">';
        $.each(arAdminNotifications, function () {
          t += '<span class="item" data-id="' + this.id_message + '">' + this.title + "</span><br>";
        }), t += "</div>", $("body").append(t), $(this).addClass("active");
      }
    }).on("click", ".admin_notifications_veil", function () {
      $(".admin_notifications").removeClass("active"), $(".admin_notifications_list").remove(), $(".admin_notifications_veil").remove();
    }).on("click", ".admin_notifications_list .item", function () {
      var i = this.dataset.id;
      if ($.each(arAdminNotifications, function (t, e) {
          if (this.id_message == i) {
            var n = '<div class="admin_notifications_mess prmu__popup"><h3>' + this.title + "</h3><p>" + this.text + '</p><span data-id="' + this.id_message + '" class="admin_notifications_agree">ХОРОШО</span></div>';
            $("body").append(n), $(".admin_notifications_list").remove(), $(".admin_notifications").fadeOut(), $.fancybox.open({
              src: ".admin_notifications_mess",
              type: "inline",
              touch: !1,
              baseTpl: '<div class="fancybox-container" role="dialog" tabindex="-1"><div class="fancybox-inner"><div class="fancybox-infobar"><span data-fancybox-index></span>&nbsp;/&nbsp;<span data-fancybox-count></span></div><div class="fancybox-toolbar">{{buttons}}</div><div class="fancybox-navigation">{{arrows}}</div><div class="fancybox-stage"></div><div class="fancybox-caption-wrap"><div class="fancybox-caption"></div></div></div></div>',
              afterClose: function () {
                $(".admin_notifications_mess").remove(), arAdminNotifications.length ? ($(".admin_notifications_list").fadeIn(), $(".admin_notifications").fadeIn()) : ($(".admin_notifications").remove(), $(".admin_notifications_veil").fadeOut());
              }
            }), arAdminNotifications.splice(t, 1), $.ajax({
              url: MainConfig.AJAX_ADMIN_NOTIFICATIONS,
              dataType: "json",
              data: {data: JSON.stringify({agree: i})}
            });
          }
        }), arAdminNotifications.length) {
        var t = '<div class="admin_notifications_list" style="display:none">';
        $.each(arAdminNotifications, function () {
          t += '<span class="item" data-id="' + this.id_message + '">' + this.title + "</span><br>";
        }), t += "</div>", $("body").append(t);
      }
    }), $(".personal-acc__menu").is("*")) {
  }
}).on("click", ".admin_notifications_agree", function () {
  $(".fancybox-close-small").click();
});
var AutocompleteAjax = function () {
  function t(t, e) {
    this.ajaxRetData = {}, this.selectObj = {}, this.selfObj = {}, this.loadObj = {}, this.options = {ajaxParams: {}}, this.vT1 = 0, this.afterItemSelectedFn = 0;
    var n = this, i = {
      ajaxParams: {inputName: "", url: "", addParams: {}},
      insertAllow: 0,
      width: 280,
      insertFirst: 0,
      loadingGIF: MainConfig.PATH_PIC + "loading2.gif",
      labelText: "Chose smth",
      btnAddHint: "",
      afterItemSelected: null
    };
    $.extend(i.ajaxParams, e.ajaxParams), $.extend(i, e), e = i, n.options = e, n.selectObj = $(t), $('<div id="DiLoadingDDMA"><img src="' + n.options.loadingGIF + '" alt=""></div>').appendTo("body"), n.loadObj = $("#DiLoadingDDMA"), n.init();
  }

  return t.prototype.setAjaxParam = function (t, e) {
    return this.options.ajaxParams.addParams[t] = e, this;
  }, t.prototype.clear = function () {
    this.selfObj.find(".choices").empty();
  }, t.prototype.setWidth = function (t) {
    var e = this.selfObj;
    e.find(".dropdown-block").css({
      margin: "0 0 0 NaNpx",
      width: "100%px"
    }), e.find(".dropdown").css({width: "NaNpx"}), e.find("input.noinsert").css({width: "NaNpx"});
  }, t.prototype.init = function () {
    var e = this;
    if (e.options.insertAllow) {
      t = '<input type="text" class="edit"/><a href="#" class="ok" title="Добавить в список"></a>';
    } else {
      var t = '<input type="text" class="edit noinsert"/>';
    }
    var n = e.selfObj = e.selectObj.after('<div class="autocomplete"><div class="dropdown-block"><label for="">' + e.options.labelText + "</label>" + t + '<div class="dropdown"><div class="title"></div><div class="close">x</div><div class="choices"></div></div></div></div>').next();
    e.setWidth(e.options.width), n.find(".add-btn").click(function (t) {
      e.onAddBtnCLickFn(t, this);
    }), n.find("input.edit").keypress(function (t) {
      return !(13 == t.which);
    }).keyup(function (t) {
      return e.onEditKeyPressFn(t, this);
    }).blur(function (t) {
      e.onEditBlurFn(t, this);
    }), n.find(".ok").click(function (t) {
      e.onOkBtnClickFn(t, this);
    }), n.find(".close").click(function (t) {
      e.closeFn();
    });
  }, t.prototype.onAddBtnCLickFn = function (t, e) {
    var n = $(e);
    t.preventDefault(), n.toggleClass("opened"), this.selfObj.find(".dropdown-block").slideToggle(300).find("input").focus().select();
  }, t.prototype.onEditBlurFn = function (t, e) {
    var n = this;
    setTimeout(function () {
      $(e), n.closeFn();
    }, 300);
  }, t.prototype.onEditKeyPressFn = function (t, e) {
    t = t || event;
    var i = this, o = $(e), a = i.selfObj, s = a.find(".dropdown").stop().slideDown(300), r = a.find(".choices");
    clearTimeout(i.vT1), i.vT1 = setTimeout(function () {
      i.showLoadingFn(o, 1);
      var t = {};
      for (var e in t[i.options.ajaxParams.inputName] = o.val(), i.options.ajaxParams.addParams) {
        var n = i.options.ajaxParams.addParams[e];
        t[e] = n;
      }
      $.get(i.options.ajaxParams.url, t, function (t) {
        for (var e in t = JSON.parse(t), i.ajaxRetData = t, i.options.insertFirst, r.empty(), t) {
          $("<a href='#' class='item'".concat(" data-name='", t[e].name, "' data-id='", t[e].id, "'>", t[e].name, "</a>")).appendTo(r);
        }
        t.length < 1 ? i.options.insertAllow ? s.stop().slideUp(200) : a.find(".title").text("Не найдено совпадений") : (a.find(".title").text(""), s.css({height: ""}).slideDown(300)), s.find(".item").click(function (t) {
          i.onDLItemCLickFn(t, this);
        });
      }).always(function () {
        i.hideLoadingFn();
      });
    }, 500);
  }, t.prototype.closeFn = function () {
    this.selfObj.find(".dropdown-block").slideUp(200), this.selfObj.find(".add-btn").removeClass("opened");
  }, t.prototype.showLoadingFn = function (t, e) {
    var n, i = this.loadObj;
    i.find("img").attr("src", this.options.loadingGIF), e && (n = $("*").is(t) ? t.offset().top : 1, i.css({
      left: t.offset().left + t.width() - i.width() - 20,
      top: n + parseInt((t.outerHeight() - i.outerHeight()) / 2) - 1
    })), i.fadeIn(400);
  }, t.prototype.hideLoadingFn = function () {
    this.loadObj.fadeOut(400);
  }, t.prototype.onDLItemCLickFn = function (t, e) {
    var n = this, i = $(e);
    if (t.preventDefault(), i.closest(".dropdown-block").find("input").val(i.text()), o = n.findAddedCity(i.text())) {
      o.prop("selected", !0), o.parent().multipleSelect("refresh");
    } else {
      var o = $("<option value='".concat(i.data("id"), "' selected>", i.text(), "</option>"));
      if (0 < n.options.insertFirst) {
        var a = n.selectObj.find("option");
        a.length >= n.options.insertFirst ? n.options.insertFirst < 2 ? o.prependTo(n.selectObj) : $(a[n.options.insertFirst - 2]).after(o) : o.appendTo(n.selectObj);
      } else {
        o.appendTo(n.selectObj);
      }
      n.selectObj.multipleSelect("refresh");
    }
    n.closeFn(), "" != n.options.afterItemSelected && n.options.afterItemSelected([i.data("id"), i.text()]);
  }, t.prototype.findAddedCity = function (e) {
    return this.selectObj.find("option").each(function () {
      var t = $(this);
      if (t.text().toUpperCase() == e.toUpperCase()) {
        return t;
      }
    }), 0;
  }, t.prototype.onOkBtnClickFn = function (t, e) {
    var n = ($(e), this.selfObj), i = n.find("[type=text]");
    t.preventDefault();
    var o = 0;
    n.find(".item").each(function () {
      var t = $(this);
      t.data("name").toUpperCase() == i.val().toUpperCase() && (o = t.data("id"));
    }), this.findAddedCity(i.val()) || this.selectObj.prepend("<option value='".concat(o || i.val(), "' selected>", i.val(), "</option>")).multipleSelect("refresh"), this.closeFn();
  }, t;
}(), AutocompleteHelper = function () {
  function t() {
    this.defOpts = {url: "", delay: 800, limit: 10, afterItemSelected: null}, this.init();
  }

  return t.prototype.init = function () {
  }, t.prototype.bind = function (e, t) {
    var n = this.defOpts;
    return $.extend(n, t), this.options = n, this.selectObj = $(e), e.autocomplete({
      serviceUrl: n.url,
      params: {limit: n.limit},
      deferRequestBy: n.delay,
      onSearchComplete: function (t, e) {
        G_VARS.App.hideLoading();
      },
      onSearchStart: function (t) {
        G_VARS.App.showLoading2(e, {pic: 2, align: "right"});
      },
      onSelect: function (t) {
        n.afterItemSelected(t);
      },
      onSearchError: function () {
        G_VARS.App.hideLoading();
      }
    }), this;
  }, t;
}(), Localization = function () {
  function e() {
    this.StringsObj = new String;
  }

  return e.translate = function (t) {
    return this.Instance || (this.Instance = new e), this.Instance.translate(t);
  }, e.prototype.translate = function (t) {
    var e = t.split("."), n = this.StringsObj;
    for (var i in e) {
      n = n[e[i]];
    }
    return n;
  }, e;
}(), CustomError = function (i) {
  function t(t, e, n) {
    i.call(this, t), this.message = t, this.code = e, n && (this.retCode = n);
  }

  return __extends(t, i), t;
}(Error), PushChecker = function () {
  function t() {
  }

  return t.prototype.init = function () {
    this.getUserNewMessages(), this.getUserNewComments();
  }, t.prototype.getUserNewMessages = function () {
    $.get(MainConfig.AJAX_GET_GETUSERNEWMESSAGES, {}, function (t) {
      try {
        var e = 0;
        (t = JSON.parse(t)).newmessages.length && $.each(t.newmessages, function () {
          e += Number(this.count);
        }), 0 < (e += Number(t.vacancy_public_mess_cnt)) && ($("#sm-notice-cnt b").html(e), $("#sm-notice-cnt").addClass("active")) && ($("#sm-notice-cnt-m b").html(e), $("#sm-notice-cnt-m").addClass("active"));
      } catch (t) {
      }
    });
  }, t.prototype.getUserNewComments = function () {
    $.get(MainConfig.AJAX_GET_GETUSERNEWCOMMENTS, {}, function (t) {
      try {
        t = JSON.parse(t), parseInt(t.newcomments) && ($("#sm-notice-cnt b").text(count), $("#sm-notice-cnt").addClass("active")) && ($("#sm-notice-cnt-m b").text(count), $("#sm-notice-cnt-m").addClass("active"));
      } catch (t) {
      }
    });
  }, t;
}(), Strings_ru = function () {
  function t() {
  }

  return t.AJAX_GET_CITYES = "/ajax/getcities/", t;
}(), InputFields = function () {
  function t() {
  }

  return t.prototype.checkPhone = function (t) {
    return $this = $(t), 11 == $this.val().replace(/\D+/g, "").length ? ($this.removeClass("error"), !0) : ($this.addClass("error"), !1);
  }, t.prototype.checkEmail = function (t) {
    return $this = $(t), "" != $this.val() && /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i.test($this.val()) ? ($this.removeClass("error"), !0) : ($this.addClass("error"), !1);
  }, t.prototype.setPhoneMask = function (t) {
    t.mask("+7(999) 999-99-99"), t.val("+7(___) ___-__-__");
  }, t;
}(), MainScript = function () {
  function t() {
  }

  return t.buttonLoading = function (t, e) {
    if (1 == e) {
      var n = $(t).html(), i = $(t).innerWidth(), o = $(t).innerHeight(), a = o / 2 - 5, s = i / 2 - 32;
      a < 0 && (a = 0), s < 0 && (s = 0), $(t).css({
        position: "relative",
        width: i,
        height: o
      }), t.dataset.content = n, $(t).html('<div class="btn-loading"><div></div><div></div><div></div><div></div></div>'), $(t).find(".btn-loading").css({
        height: o,
        left: s
      }), $(t).find(".btn-loading>div").css({top: a});
    }
    0 == e && (n = t.dataset.content, $(t).html("").html(n));
  }, t.isButtonLoading = function (t) {
    return $(t).find(".btn-loading").length;
  }, t.stateLoading = function (t) {
    1 == t ? $("*").is(".prmu-load") || $("body").append('<div class="prmu-load">') : $(".prmu-load").remove();
  }, t;
}();