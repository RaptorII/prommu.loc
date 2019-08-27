/**
 * Misha, adaptiruy)
 */
$(function() {
    function n() {
        var t = e.serialize();
        arguments.length && ($(arguments[0]).hasClass("psv__view-list") ? t += "&view=list" : $(arguments[0]).hasClass("psv__view-table") ? t += "&view=table" : t = arguments[0].href.slice(arguments[0].href.indexOf("/vacancy?") + 9)),
            s.show(),
            urlString = "/vacancy?" + t,
            $.ajax({
                type: "GET",
                url: "/vacancy",
                data: t,
                success: function(t) {
                    i.html(t),
                    "" !== redirect && (document.location.href = redirect),
                    null != arSeo.url && (urlString = arSeo.url),
                        null != arSeo.seo_h1 ? $("h1").text(arSeo.seo_h1) : $("h1").text("Поиск вакансий"),
                        null != arSeo.meta_keywords ? $(o).html(arSeo.meta_keywords) : $(o).html(""),
                        null != arSeo.meta_title ? document.title = arSeo.meta_title : document.title = "Поиск вакансий",
                        window.history.pushState("object or string", "page name", urlString),
                    l && $("html, body").animate({
                        scrollTop: i.offset().top - 100
                    }, 700),
                        s.hide()
                }
            })
    }
    var e = $("#F1Filter")
        , i = $("#content")
        , l = $(".filter-dolj .psv__checkbox-input")
        , s = $(".psv__veil")
        , a = $(".psv__salary .psv__input")
        , c = $("#psv-salary-type")
        , r = !1
        , o = "#psv-seo-text";
    function f(t, e) {
        var i = $(e)
            , l = $("#filter-city").data("city")
            , s = i.closest(".filter-city-select")[0]
            , a = $(s).siblings(".select-list")[0]
            , n = $(".filter-city-select").find('[type="hidden"]')
            , c = "query=" + t + "&idco=" + l
            , r = t.toLowerCase()
            , o = []
            , f = "";
        if (n.length)
            for (var u = 0, d = n.length; u < d; u++)
                o.push($(n[u]).val());
        $(s).addClass("load"),
            $.ajax({
                type: "POST",
                url: MainConfig.AJAX_GET_VE_GET_CITIES,
                data: c,
                dataType: "json",
                success: function(t) {
                    for (var e in t.suggestions) {
                        var i = t.suggestions[e]
                            , l = i.data;
                        if (isNaN(i.data))
                            break;
                        $.inArray(l, o) < 0 && 0 <= i.value.toLowerCase().indexOf(r) && (f += '<li data-id="' + i.data + '" data-metro="' + i.ismetro + '">' + i.value + "</li>")
                    }
                    f ? $(a).html(f).fadeIn() : $(a).html('<li class="emp">Список пуст</li>').fadeIn(),
                        $(s).removeClass("load")
                }
            })
    }
    $(".filter-dolj .psv__checkbox-input").change(function() {
        if ($(this).is(l[0]))
            if ($(this).is(":checked"))
                for (t = 1; t < l.length; t++)
                    $(l[t]).prop("checked", !0);
            else
                for (var t = 1; t < l.length; t++)
                    $(l[t]).prop("checked", !1);
        setTimeout(function() {
            n()
        }, 300)
    }),
        $(".filter-busy input, .filter-sex input, .filter-smart input, .filter-card input").change(function() {
            setTimeout(function() {
                n()
            }, 300)
        }),
        a.focus(function() {
            for (var t = 1, e = 0; e < a.length; e++)
                $(this).is(a[e]) && (t = 5 < e ? 4 : e < 4 ? 1 < e ? 2 : 1 : 3);
            for (e = 0; e < a.length; e++)
                (1 == t && 0 != e && 1 != e || 2 == t && 2 != e && 3 != e || 3 == t && 4 != e && 5 != e || 4 == t && 6 != e && 7 != e) && $(a[e]).val("");
            c.val(t)
        }),
        $(".psv__filter-btn").click(function() {
            var t = $(this).closest(".filter-label");
            if ($(t).hasClass("filter-age")) {
                var e = $(t).find("input")
                    , i = Number($(e[0]).val())
                    , l = Number($(e[1]).val());
                i < 14 && ($(e[0]).val("14"),
                    i = 14),
                l < i && 0 < l && $(e[1]).val("14")
            }
            setTimeout(function() {
                n()
            }, 300)
        }),
        $("#content").on("click", ".paging-wrapp a", function(t) {
            t.preventDefault(),
                n(t.target)
        }),
        $("#content").on("click", ".psv__view-block a", function(t) {
            t.preventDefault(),
                n(t.target)
        }),
        $(".more-posts").click(function() {
            $(this).closest(".filter-content").css({
                height: "inherit"
            }),
                $(this).fadeOut()
        }),
        $(window).on("load resize", function() {
            $(window).width() < "768" ? $(".psv__filter-vis").hasClass("active") ? e.show() : e.hide() : e.show()
        }),
        $(".psv__filter-vis").click(function() {
            $(this).hasClass("active") ? e.fadeOut() : e.fadeIn(),
                $(this).toggleClass("active")
        }),
        $(".filter-salary input,.filter-age input").on("input", function() {
            var t = $(this).val().replace(/\D+/g, "");
            $(this).val(t)
        }),
        $("#filter-city").on("input", ".city-inp", function(t) {
            var e = $(t.target)
                , i = e.val();
            e.css({
                width: 10 * i.length + 5 + "px"
            }),
                clearTimeout(r),
                r = setTimeout(function() {
                    f(i, e)
                }, 1e3)
        }),
        $("#filter-city").on("focus", ".city-inp", function(t) {
            var e = $(t.target)
                , i = e.val();
            e.val("").val(i),
                f(i, e)
        }),
        $("#filter-city").on("click", ".filter-city-select", function(t) {
            $(t.target).is("b") || $(t.target).find(".city-inp").focus()
        }),
        $(document).on("click", function(t) {
            var e = $(t.target);
            if ($("#filter-city .select-list"),
            e.closest("#filter-city").length || e.is("#filter-city"))
                if (e.is(".select-list li") && !e.hasClass("emp")) {
                    var i = e.closest("#filter-city")[0]
                        , l = $(i).find(".filter-city-select")
                        , s = $(i).find(".city-inp")
                        , a = $(i).find(".select-list");
                    s.val("").css({
                        width: "5px"
                    }),
                        $(l).find('[data-id="0"]').before("<li>" + e.text() + '<b></b><input name="cities[]" type="hidden" value="' + t.target.dataset.id + '"/></li>'),
                        a.fadeOut(),
                        setTimeout(n(), 300)
                } else
                    e.is(".filter-city-select b") && (e.closest("li").remove(),
                        setTimeout(n(), 300));
            else
                $("#filter-city .city-inp").val("").css({
                    width: "5px"
                }),
                    $("#filter-city .select-list").fadeOut()
        }),
        $(document).on("click",".psv-list__responce-btn", function(t) {
            $.get(
                MainConfig.AJAX_POST_SETVACATIONRESPONSE,
                {id: this.dataset.id, url:'list'},
                function(t) {
                    t = JSON.parse(t);
                    if(typeof t.message !=undefined)
                    {
                        $('body').append('<div class="prmu__popup"><p>'+t.message+'</p></div>'),
                            $.fancybox.open({
                                src: "body>div.prmu__popup",
                                type: 'inline',
                                touch: false,
                                afterClose: function(){ $('body>div.prmu__popup').remove() }
                            })
                    }
                }
            )
        })

});