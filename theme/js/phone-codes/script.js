!function(factory){
    "function" == typeof define && define.amd ? define([ "jquery" ], factory) : factory("object" == typeof exports ? require("jquery") : jQuery);
}(function($){
    var countryCache;
    var countryRequesting = false;
    var elemInput = '#phone-code',
        oldValPhone = '',
        keyCode = 0,
        startCheckPhone = false;

    var phoneCode = {
            data: [],
            container: null,
            prefixField: null,
            searchTimeout: null,
            suggestTimeout: null,
            hideTimeout: null,
            options: {
                default_prefix: '7',
                prefix: '',
                preferCo: 'ru'
            },

            _create: function() {
                this._loadData();
                this.element.wrap('<div class="country-phone">');
                var container = this.element.parent('.country-phone');
                var selector = $('<div class="country-phone-selector"><div class="country-phone-selected"></div><div class="country-phone-options"></div></div>');
                $(selector).prependTo(container);

                var prefixName = this.options.prefix ?
                    this.options.prefix : '__phone_prefix';
                var hidden = $('<input type="hidden" name="'+ prefixName +'" value="'+ this.options.default_prefix +'">');
                $(hidden).appendTo(container);

                this.container = container;
                this.prefixField = hidden;
            },

            _loadData : function(){
                var self = this;
                if(!countryCache && !countryRequesting) {
                    countryRequesting = $.getJSON('/theme/js/phone-codes/countries.json', {})
                        .done(function(json) {
                            self.data = json;
                            countryCache = self.data;
                            self._initSelector();
                        })
                        .fail(function(xhr, status, error) {
                            //alert(status + ' ' + error);
                            self.data = countries;
                            countryCache = self.data;
                            self._initSelector();
                        });
                }
                else if(countryCache) {
                    this.data = countryCache;
                    self._initSelector();
                }
                else if(countryRequesting) {
                    countryRequesting.done(function(json) {
                        self.data = json;
                        countryCache = self.data;
                        self._initSelector();
                    });
                }
            },

            _initSelector: function() {
                var options = this.container.find('.country-phone-options');
                var selector = this.container.find('.country-phone-selected');
                var selected = null;
                var self = this;
                var searchInput = $('<input type="text" class="country-phone-search" value="">');
                $(searchInput).appendTo(options);
                var searchLabel = $('<label class="country-phone-search-label">Введите страну</label>');
                $(searchLabel).on('click',function(){
                    $(this).hide();
                    $(searchInput).focus();
                }).insertAfter(searchInput);
                $(searchLabel).hide().show();
                $(searchInput).bind('keyup', function(e){
                    if(self.suggestTimeout) {
                        window.clearTimeout(self.suggestTimeout);
                    }
                    var input = this;
                    var ev = e;
                    self.suggestTimeout = window.setTimeout(function(){
                        var text = $(input).val().toLowerCase();
                        self.suggestCountry(text);
                        if(ev.keyCode == 40) {
                            self._moveSuggestDown(options);
                        }
                        if(ev.keyCode == 38) {
                            self._moveSuggestUp(options);
                        }
                        if(ev.keyCode == 13) {
                            var hovered = $(options).find('.hovered:visible');
                            if(hovered.length) {
                                if(!$(hovered).hasClass('country-phone-search')) {
                                    self.setElementSelected(hovered);
                                    self._toggleSelector();
                                }
                            }
                            ev.stopPropagation();
                            ev.preventDefault();
                        }
                    }, 100);

                    if($(this).val() == '') {
                        $(searchLabel).show();
                    }
                    else {
                        $(searchLabel).hide();
                    }

                }).bind('keypress', function(e){
                    if(e.keyCode == 13) {
                        e.stopPropagation();
                        e.preventDefault();
                        return false;
                    }
                });

                for(var i = 0; i < this.data.length; i++) {
                    if(i == 0) {
                        selected = this.data[i];
                    }
                    var country = this.data[i];
                    var prefCountry = country.co;

                    var option = $('<div data-phone="'+
                        country.ph + '" data-co="'+ prefCountry.toLowerCase() +'"' +
                        ' class="country-phone-option"><span>+'+ country.ph +'<img src="/theme/pic/phone-codes/blank.gif" class="flag flag-'+
                        country.co +
                        '"></span>'+ country.na +'</div>'
                    );
                    $(option).appendTo(options);
                    if(this.options.preferCo && (this.options.preferCo != undefined)) {
                        if(prefCountry == this.options.preferCo) {
                            selected = country;
                        }
                    }
                    else {
                        if(country.ph == this.options.default_prefix) {
                            selected = country;
                        }
                    }
                }
                if(selected) {
                    this.container.find('.country-phone-selected')
                        .html('<img src="/theme/pic/phone-codes/blank.gif" class="flag flag-'+ selected.co +'"><span>+'+ selected.ph+'</span>');
                }
                $(selector).bind('click', function(e){
                    self._toggleSelector();
                });
                $(options).find('.country-phone-option').bind('click', function(){
                    self.setElementSelected(this);
                    self._toggleSelector();
                });
                $(options).hover(function(){
                    if(self.hideTimeout) {
                        window.clearTimeout(self.hideTimeout);
                    }
                }, function(){
                    var select = this;
                    self.hideTimeout = window.setTimeout(self._mouseOverHide, 1000, select, self);
                });

                this._initInput();
            },

            _mouseOverHide: function(select, self) {
                if(self.container) {
                    var searchInput = self.container.find('.country-phone-search');
                    if(!$(searchInput).is(':focus')) {
                        $(select).hide();
                    }
                    else {
                        self.hideTimeout = window.setTimeout(self._mouseOverHide, 1000, select, self);
                    }
                }
            },

            _moveSuggestDown: function(options) {
                var select = null;
                var hovered = $(options).find('.hovered:visible');
                if(hovered.length) {
                    var next = $(hovered).next(':visible');
                    if(next.length) {
                        select = next;
                    }
                    else {
                        next = $(hovered).nextUntil(':visible').last().next();
                        if(next.length) {
                            select = next;
                        }
                    }
                }
                if(!select) {
                    select = $(options).find('.country-phone-option:visible').first();
                }
                if(select) {
                    $(options).find('.country-phone-option').add('.country-phone-search').removeClass('hovered');
                    $(select).addClass('hovered');
                }
            },

            _moveSuggestUp: function(options) {
                var select = null;
                var hovered = $(options).find('.hovered:visible');
                if(hovered.length) {
                    var next = $(hovered).prev(':visible');
                    if(next.length) {
                        select = next;
                    }
                    else {
                        next = $(hovered).prevUntil(':visible').last().prev();
                        if(next.length) {
                            select = next;
                        }
                    }
                }
                if(!select) {
                    select = $(options).find('.country-phone-option:visible').last();
                }
                if(select) {
                    $(options).find('.country-phone-option').add('.country-phone-search').removeClass('hovered');
                    $(select).addClass('hovered');
                }
            },

            suggestCountry: function(text, checkCode) {
                var options = this.container.find('.country-phone-options');
                var self = this;
                $(options).find('.country-phone-option').each(function(){
                    if(text) {
                        if(text == 'россия') {
                            text = 'росси';
                        }
                        var match = $(this).text().toLowerCase();
                        if(match.indexOf(text) >= 0) {
                            $(this).show();
                            if(checkCode && checkCode != undefined) {
                                var code = $(this).data('phone');
                                var selCode = self.prefixField.val();
                                if(selCode == code) {
                                    self.setElementSelected(this);
                                }
                            }
                        }
                        else {
                            if(!checkCode) {
                                $(this).hide();
                            }
                        }
                    }
                    else {
                        $(this).show();
                    }
                });
            },

            _toggleSelector: function(){
                var options = this.container.find('.country-phone-options');
                if($(options).is(':visible')) {
                    $(options).hide('fast');
                    $(options).find('.country-phone-search').val('').blur();
                    this.element.focus();
                    this.suggestCountry('');
                }
                else {
                    $(options).show('fast');
                    window.setTimeout(function(){
                        var searchInp = $(options).find('.country-phone-search');
                        $(searchInp).val('').focus();
                    }, 300);
                }
            },

            setElementSelected: function(el) {
                var selector = this.container.find('.country-phone-selected');
                var code = $(el).data('phone');
                var sel = $(el).find('img').clone();
                $(selector).empty().append(sel).append('<span>+'+code+'</span>');
                this.prefixField.val(code);
                this.element.val('');
                return code;
            },

            _initInput: function() {
                var self = this;
                this.element.bind('keyup', function(){
                    var text = $(this).val();
                    if(text.length > 1 && text[0] == '+') {
                        var code = text.substring(1);
                        if(self.searchTimeout) {
                            window.clearTimeout(self.searchTimeout);
                        }
                        var input = this;
                        window.setTimeout(function(){
                            var found = self.searchCountryCode(code);
                            if(found) {
                                text = $(input).val();
                                text = text.replace('+' + found, '');
                                $(input).val(text);
                            }
                        }, 1000);
                    }
                });

                this.initInputVal();
            },

            initInputVal: function() {
                var text = this.element.val();
                var self = this;
                if(text.length > 1 && text[0] == '+') {
                    for(var i = 6; i >= 1; i--) {
                        var code = text.substring(1, i);
                        var found = self.searchCountryCode(code);
                        if(found) {
                            text = this.element.val();
                            text = text.replace('+' + found, '');
                            this.element.val(text);
                            break;
                        }
                    }
                }
                else if(text.length == 1 && text[0] == '+') {
                    this.element.val('');
                }
            },

            searchCountryCode: function(code) {
                var options = this.container.find('.country-phone-options');
                var search = code;
                var self = this;
                var found = false;
                var foundItems = [];
                $(options).find('.country-phone-option').each(function(){
                    if(search == $(this).data('phone')) {
                        foundItems.push({
                            co: $(this).data('co'),
                            el: this
                        });
                    }
                });

                if(foundItems.length == 1) {
                    found = self.setElementSelected(foundItems[0].el);
                }
                else if(foundItems.length > 1) {
                    for(var i = 0; i < foundItems.length; i++) {
                        if(self.options.preferCo) {
                            if(self.options.preferCo == foundItems[i].co) {
                                found = self.setElementSelected(foundItems[i].el);
                                break;
                            }
                        }
                        else {
                            found = self.setElementSelected(foundItems[i].el);
                            break;
                        }
                    }
                    if(!found) {
                        found = self.setElementSelected(foundItems[0].el);
                    }
                }

                return found;
            }
        };
    //
    //
    //
    //
    //
    if($(elemInput).is('*')){
        phoneCode.element = $(elemInput);
        oldValPhone = $(elemInput).val();

        if(typeof selectPhoneCode!=='undefined'){ // устанавливаем, если номер задан
            if(selectPhoneCode==7) phoneCode.options.preferCo = 'ru'; // RF
            if(selectPhoneCode==380) phoneCode.options.preferCo = 'ua'; // UA
            if(selectPhoneCode==375) phoneCode.options.preferCo = 'by'; // BY
            phoneCode.options.default_prefix = (selectPhoneCode ? selectPhoneCode : '7');
        }
        phoneCode._create();       
    }

    $(document).keydown(function(e){ keyCode = e.keyCode });

    $(elemInput).on('input',function(){
        var val = $(this).val().replace(/\D+/g,''),
            len = val.length,
            newVal = '',
            code = $('[name="__phone_prefix"]').val(),
            phoneLen = 10;

        if(code.length==3){ // UKR
            phoneLen = 9;
            if(keyCode==8){ //backspace
                if(len==8) newVal = oldValPhone.slice(0, -1);
                if(len==7) newVal = oldValPhone.slice(0, -2);
                if(len==6) newVal = oldValPhone.slice(0, -1);
                if(len==5) newVal = oldValPhone.slice(0, -2);
                if(len==4) newVal = oldValPhone.slice(0, -1);
                if(len==3) newVal = oldValPhone.slice(0, -1);
                if(len==2) newVal = oldValPhone.slice(0, -2);
                if(len==1) newVal = oldValPhone.slice(0, -1);
            }
            else{
                if(len>=1) newVal = '(' + val.slice(0,1);
                if(len>=2) newVal += val.slice(1,2) + ')';
                if(len>=3) newVal += val.slice(2,3);
                if(len>=4) newVal += val.slice(3,4);
                if(len>=5) newVal += val.slice(4,5) + '-';
                if(len>=6) newVal += val.slice(5,6);
                if(len>=7) newVal += val.slice(6,7) + '-';
                if(len>=8) newVal += val.slice(7,8);
                if(len>=9){
                    startCheckPhone = true;
                    newVal += val.slice(8,9);
                }
            }
        }
        if(code.length==1){ // RF
            phoneLen = 10;
            if(keyCode==8){ //backspace
                if(len==9) newVal = oldValPhone.slice(0, -1);
                if(len==8) newVal = oldValPhone.slice(0, -2);
                if(len==7) newVal = oldValPhone.slice(0, -1);
                if(len==6) newVal = oldValPhone.slice(0, -2);
                if(len==5) newVal = oldValPhone.slice(0, -1);
                if(len==4) newVal = oldValPhone.slice(0, -1);
                if(len==3) newVal = oldValPhone.slice(0, -2);
                if(len==2) newVal = oldValPhone.slice(0, -1);
                if(len==1) newVal = oldValPhone.slice(0, -1);
            }
            else{
                if(len>=1) newVal = '(' + val.slice(0,1);
                if(len>=2) newVal += val.slice(1,2);
                if(len>=3) newVal += val.slice(2,3) + ')';
                if(len>=4) newVal += val.slice(3,4);
                if(len>=5) newVal += val.slice(4,5);
                if(len>=6) newVal += val.slice(5,6) + '-';
                if(len>=7) newVal += val.slice(6,7);
                if(len>=8) newVal += val.slice(7,8) + '-';
                if(len>=9) newVal += val.slice(8,9);
                if(len>=10){
                    startCheckPhone = true;
                    newVal += val.slice(9,10);
                }
            }
        }

        (len<phoneLen && startCheckPhone)
            ? $(this).closest('.country-phone').addClass('error')
            : $(this).closest('.country-phone').removeClass('error');

        oldValPhone = newVal;

        $(this).val('').val(newVal);
    });
});