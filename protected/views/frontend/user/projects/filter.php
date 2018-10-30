<div
        class="<?= $arFilterData['STYLES'] ?> prommu__universal-filter"
    <?= $arFilterData['HIDE'] ? ' style="display:none"' : '' ?>>
    <? foreach ($arFilterData['FILTER_SETTINGS'] as $key => $value): ?>

    <?
    if (count($value['CONDITION']['PARENT_VALUE_ID']) > 1):
        for ($i = 0; $i < count($value['CONDITION']['PARENT_VALUE_ID']); $i++):
            if ($i == 0) {
                $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'][$i];
            } else {
                $parentValueId .= "," . $value['CONDITION']['PARENT_VALUE_ID'][$i];
            }
        endfor;
    else:
        $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'][0];
    endif; ?>

    <? switch ($value['TYPE']):
    case 'block':
        ?>
        <div data-type="<?= $value['TYPE'] ?>"
             class="u-filter__item u-filter__item-<?= $key ?> u-filter__blockitem">
        </div>
        <?
        break;
    case 'text':
        ?>
        <div data-type="<?= $value['TYPE'] ?>"
             data-id="<?= $key ?>"
             data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
             data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
             data-parent-value-id="<?= $parentValueId ?>"
             class="u-filter__item u-filter__item-<?= $key ?>  <?= ($value['CONDITION']['BLOCKED']) ? 'blocked' : '' ?>">
            <div class="u-filter__item-title">
                <?= $value['NAME']; ?>
            </div>
            <div class="u-filter__item-data">
                <input
                        placeholder="<?= $value['PLACEHOLDER'] ?>"
                        class="u-filter__text"
                        type="text"
                        name="<?= $value['INPUT_NAME']; ?>"
                />
                <input
                        type="hidden"
                        class="u-filter__hidden-default"
                        value="<?= $value['DATA_DEFAULT'] ?>"
                />
            </div>
        </div>
        <?
        break;
    case 'select':
        ?>
        <div data-type="<?= $value['TYPE'] ?>"
             data-id="<?= $key ?>"
             data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
             data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
             data-parent-value-id="<?= $parentValueId ?>"
             data-blocked="<?= $value['CONDITION']['BLOCKED'] ?>"
             class="u-filter__item u-filter__item-<?= $key ?> <?= ($value['CONDITION']['BLOCKED'] == 'true') ? 'blocked' : '' ?>">
            <div class="u-filter__item-title">
                <?= $value['NAME']; ?>
            </div>
            <div class="u-filter__item-data">
                <span class="u-filter__select"></span>
                <ul class="u-filter__ul-hidden">
                    <? foreach ($value['DATA'] as $d_key => $d_value): ?>
                        <li class="u-filter__li-hidden"
                            data-li-parent-value-id="<?= $d_value['DATA_VALUE_PARENT_ID']; ?>"
                            data-id="<?= $d_value['id']; ?>"><?= $d_value['title']; ?></li>
                    <? endforeach; ?>
                </ul>
                <input
                        type="hidden"
                        class="u-filter__li-visible"
                        value="<?= $value['DATA_LI_VISIBLE'] ?>"
                />


                <input
                        type="hidden"
                        name="<?= $value['INPUT_NAME'] ?>"
                        class="u-filter__hidden-data"
                        value="<?= $value['DATA_DEFAULT'] ?>"
                />
                <input
                        type="hidden"
                        class="u-filter__hidden-default"
                        value="<?= $value['DATA_DEFAULT'] ?>"
                />
            </div>
        </div>
        <?
        break;
    case 'select-multi':
    ?>
    <div data-type="<?= $value['TYPE'] ?>"
         data-id="<?= $key ?>"
         data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
         data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
         data-parent-value-id="<?= $parentValueId ?>"
         data-blocked="<?= $value['CONDITION']['BLOCKED'] ?>"
         class="u-filter__item u-filter__item-<?= $key ?> <?= ($value['CONDITION']['BLOCKED'] == 'true') ? 'blocked' : '' ?>">
        <div class="u-filter__item-title">
            <?= $value['NAME']; ?>
        </div>
        <div class="u-filter__item-data">
            <div class="fav__select-cities filter-city" id="filter-city">
                <ul class="filter-city-select">

                    <li data-id="0">
                        <input type="text" name="fc" class="city-inp" autocomplete="off">
                    </li>
                </ul>
                <ul class="select-list"></ul>
            </div>
        </div>
    </div>

            <script>
                $(function () {

                    var arStatus = <?=json_encode($value['DATA']);?>;

                    var e = $("#F1Filter"), i = $("#content"), l = $(".filter-dolj .psv__checkbox-input"), s = $(".psv__veil"),
                        a = $(".psv__salary .psv__input"), c = $("#psv-salary-type"), r = !1, o = "#psv-seo-text";

                    function f(t, e) {
                        var i = $(e), l = $("#filter-city").data("city"), s = i.closest(".filter-city-select")[0],
                            a = $(s).siblings(".select-list")[0], n = $(".filter-city-select").find('[type="hidden"]'),
                            c = "query=" + t + "&idco=" + l, r = t.toLowerCase().trim(), o = [], f = "";
                        if (n.length)for (var u = 0, d = n.length; u < d; u++)o.push($(n[u]).val());
                        //$(s).addClass("load");


                        for (var e in arStatus) {
                            var i = arStatus[e];
                            $.inArray(e+1, o) < 0 && 0 <= i.toLowerCase().indexOf(r) && (f += '<li class="u-filter__li-hidden" data-id="' + (e+1) + '">' + i + "</li>")

                        }
                        f ? $(a).html(f).fadeIn() : $(a).html('<li class="emp">Список пуст</li>').fadeIn(), $(s).removeClass("load")
                    }

                    $(".filter-dolj .psv__checkbox-input").change(function () {
                        if ($(this).is(l[0]))if ($(this).is(":checked"))for (t = 1; t < l.length; t++)$(l[t]).prop("checked", !0); else for (var t = 1; t < l.length; t++)$(l[t]).prop("checked", !1);
                        setTimeout(function () {
                            n()
                        }, 300)
                    }), $(".filter-busy input, .filter-sex input, .filter-smart input, .filter-card input").change(function () {
                        setTimeout(function () {
                            n()
                        }, 300)
                    }), a.focus(function () {
                        for (var t = 1, e = 0; e < a.length; e++)$(this).is(a[e]) && (t = 5 < e ? 4 : e < 4 ? 1 < e ? 2 : 1 : 3);
                        for (e = 0; e < a.length; e++)(1 == t && 0 != e && 1 != e || 2 == t && 2 != e && 3 != e || 3 == t && 4 != e && 5 != e || 4 == t && 6 != e && 7 != e) && $(a[e]).val("");
                        c.val(t)
                    }), $(".psv__filter-btn").click(function () {
                        var t = $(this).closest(".filter-label");
                        if ($(t).hasClass("filter-age")) {
                            var e = $(t).find("input"), i = Number($(e[0]).val()), l = Number($(e[1]).val());
                            i < 14 && ($(e[0]).val("14"), i = 14), l < i && 0 < l && $(e[1]).val("14")
                        }
                        setTimeout(function () {
                            n()
                        }, 300)
                    }), $("#content").on("click", ".paging-wrapp a", function (t) {
                        t.preventDefault(), n(t.target)
                    }), $("#content").on("click", ".psv__view-block a", function (t) {
                        t.preventDefault(), n(t.target)
                    }), $(".more-posts").click(function () {
                        $(this).closest(".filter-content").css({height: "inherit"}), $(this).fadeOut()
                    }), $(window).on("load resize", function () {
                        $(window).width() < "751" ? $(".psv__filter-vis").hasClass("active") ? e.show() : e.hide() : e.show()
                    }), $(".psv__filter-vis").click(function () {
                        $(this).hasClass("active") ? e.fadeOut() : e.fadeIn(), $(this).toggleClass("active")
                    }), $(".filter-salary input,.filter-age input").on("input", function () {
                        var t = $(this).val().replace(/\D+/g, "");
                        $(this).val(t)
                    }), $("#filter-city").on("input", ".city-inp", function (t) {
                        var e = $(t.target), i = e.val();
                        e.css({width: 10 * i.length + 5 + "px"}), clearTimeout(r), r = setTimeout(function () {
                            f(i, e)
                        }, 1e3)
                    }), $("#filter-city").on("focus", ".city-inp", function (t) {
                        var e = $(t.target), i = e.val();
                        e.val("").val(i), f(i, e)
                    }), $("#filter-city").on("click", ".filter-city-select", function (t) {
                        $(t.target).is("b") || $(t.target).find(".city-inp").focus()
                    }), $(document).on("click", function (t) {
                        var e = $(t.target);
                        $("#filter-city .select-list");
                        if (e.closest("#filter-city").length || e.is("#filter-city"))if (e.is(".select-list li") && !e.hasClass("emp")) {
                            var i = e.closest("#filter-city")[0], l = $(i).find(".filter-city-select"), s = $(i).find(".city-inp"),
                                a = $(i).find(".select-list");
                            s.val("").css({width: "5px"}), $(l).find('[data-id="0"]').before("<li>" + e.text() + '<b></b><input name="cities[]" type="hidden" value="' + t.target.dataset.id + '"/></li>'), a.fadeOut()
                        } else e.is(".filter-city-select b") && (e.closest("li").remove()); else $("#filter-city .city-inp").val("").css({width: "5px"}), $("#filter-city .select-list").fadeOut();
                    })
                });
            </script>
        <?
        break;
        case 'calendar':
            ?>
            <div data-type="<?= $value['TYPE'] ?>"
                 data-id="<?= $key ?>"
                 data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
                 data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
                 data-parent-value-id="<?= $parentValueId ?>"
                 class="geo__header-date u-filter__item u-filter__item-<?= $key ?> <?= ($value['CONDITION']['BLOCKED'] == 'true') ? 'blocked' : '' ?>">
                <div class="u-filter__item-title">
                    <?= $value['NAME']; ?>
                </div>
                <div class="u-filter__item-data calendar-filter">
                    <span class="u-filter__calendar"><?= $value['DATA_SHORT'] ?></span>
                    <div class="calendar u-filter__calendarbox" data-type="bdate">
                        <table>
                            <thead>
                            <tr>
                                <td class="mleft">‹
                                <td colspan="5" class="mname">
                                <td class="mright">›
                            </tr>
                            <tr>
                                <td>Пн
                                <td>Вт
                                <td>Ср
                                <td>Чт
                                <td>Пт
                                <td>Сб
                                <td>Вс
                            </tr>
                            <tbody></tbody>
                        </table>
                    </div>

                    <input
                            type="hidden"
                            name="<?= $value['INPUT_NAME'] ?>"
                            class="u-filter__hidden-data"
                            value="<?= $value['DATA_DEFAULT'] ?>"
                    />
                </div>
            </div>

            <?
            break;
        endswitch; ?>
        <? endforeach; ?>

        <? if (isset($arFilterData['ID']) && !empty($arFilterData['ID'])): ?>
            <input type="hidden" name="id" value="<?= $arFilterData['ID'] ?>"/>
        <? endif; ?>
        <? if (count($arFilterData['FILTER_ADDITIONAL_VALUE']) > 0): ?>
            <? foreach ($arFilterData['FILTER_ADDITIONAL_VALUE'] as $addKey => $addValue): ?>
                <input type="hidden" name="<?= $addKey ?>" value="<?= $addValue ?>"/>
            <? endforeach; ?>
        <? endif; ?>
    </div>


<? /*

//Пример Массива на всякий случай

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => [
        'SECTION_ID' => Yii::app()->request->getParam('section')
    ],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'Имя',
            'TYPE' => 'text',
            'INPUT_NAME' => 'fname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        1 => [
            'NAME' => 'Статус',
            'TYPE' => 'select',
            'INPUT_NAME' => 'status',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Подтверждено',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не подтверждено',
                    'id' => '2'
                ]

            ],
            'DATA_DEFAULT' => '0'
        ],
        2 => [
            'NAME' => 'Город',
            'TYPE' => 'select',
            'INPUT_NAME' => 'city',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ]
            ],
            'DATA_DEFAULT' => '0',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
                ]
            ]
        ],
        3 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        4 => [
            'NAME' => 'Привязка к адресу',
            'TYPE' => 'select',
            'INPUT_NAME' => 'haspoint',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Привязан',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не привязан',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        5 => [
            'NAME' => 'Название ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_name',
            'DATA' => [
                0 => [
                    'title' => 'Название 1',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Название 2',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Все',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '2',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
                ]
            ]
        ],
        6 => [
            'TYPE' => 'block',
        ],
        7 => [
            'TYPE' => 'block',
        ],
        8 => [
            'NAME' => 'Адрес ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_location',
            'DATA' => [
                0 => [
                    'title' => 'Адрес ТТ 1',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Адрес ТТ 2',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Адрес ТТ 3',
                    'id' => '2'
                ],
                3 => [
                    'title' => 'Все',
                    'id' => '3'
                ]
            ],
            'DATA_DEFAULT' => '2',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
                ]
            ]
        ],
        9 => [
            'TYPE' => 'block',
        ],
        10 => [
            'TYPE' => 'block',
        ],
        11 => [
            'NAME' => 'Метро',
            'TYPE' => 'select',
            'INPUT_NAME' => 'metro',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ]
            ]
        ]
    ],
];
?>