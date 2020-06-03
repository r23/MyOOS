(function ($) {
    $.widget( "cpti.cptiColorPicker", {
 
        options : {
            value          : '',
            colors         : {},
            enableDefault : false,

            onChange : function( value ){},
        },

        _element        : null,
        _colorPickerElm : null,

        _create: function() {
            var THIS = this;
            this.options.value = this.element.val();
            var value = this.options.value;
            var custom_color = ( value.indexOf("#") != -1 )? value : '';
            var id = 'cpti-color-picker-'+this.uuid;

            html = '';
            html +='<div id="'+id+'" class="cpti-color-picker-">';
                html += '<div class="cpti-color-dropdown closed">';
                    html += '<span class="cpti-header-item">';
                    if(!this.options.enableDefault || !('' in this.options.colors)) {
                        html += '<span style="background-color: transparent"></span> None</span>';
                    }
                    else {
                        var hex  = this.options.colors[''].hex;
                        var name = ( typeof this.options.colors[''].name != 'undefined' ) ? this.options.colors[''].name : this._titleCase(slug);
                        html += '<span style="background-color: '+hex+'"></span> '+name+'</span>';
                    }
                    html += '<ul class="cpti-color-list">';
                        if(!this.options.enableDefault || (this.options.enableDefault && !('' in this.options.colors))) {
                            html += '<li cpti-val=""> None</li>';
                        }
                        for(var slug in this.options.colors) {
                            if(!this.options.enableDefault && !slug) {
                                continue;
                            }
                            
                            var hex  = this.options.colors[slug].hex;
                            var name = ( typeof this.options.colors[slug].name != 'undefined' ) ? this.options.colors[slug].name : this._titleCase(slug);
                            html += '<li cpti-val="'+slug+'"><span style="background-color: '+hex+';"></span> '+name+'</li>';
                        }
                        html += '<li cpti-val="#">Custom</li>';
                    html += '</ul>';
                html += '</div>';
                html += '<div class="cpti-custom-color-picker-wrap">';
                    html += '<input class="cpti-custom-color" type="text" value="'+custom_color+'" data-alpha="true">';
                html += '</div>';
            html += '</div>';
            this.element.hide();
            this._element = $(html).insertAfter( this.element );
            this._colorPickerElm = this._element.find('.cpti-custom-color').first();
            this._colorPickerElm.wpColorPicker({
                change: function(event, ui) {
                    var element = event.target;
                    var color = ui.color.toString();
                    THIS.onChangeCusomColor( color );
                },
                clear: function(event) {
                    THIS.onClearCusomColor();
                }
            });
            this.setValue( value );
            this._addEvents();
        },

        _addEvents : function(){
            var THIS = this;
            var elm = this._element;
            $(elm).on('click', '.cpti-header-item', function (e) {
                e.preventDefault();
                THIS.toggleDropdown();
            });

            $(elm).on('click', '.cpti-color-list li', function (e) {
                e.preventDefault();
                var value = $(this).attr('cpti-val');
                THIS.setValue( value );
                THIS.toggleDropdown();
            });
        },

        _titleCase : function(s){
            if(s == ''){ return s; }
            s = s.replace(/-/g, ' ');
            s = s.replace(/_/g, ' ');
            var s = s.toLowerCase().split(" ");
            for(var i = 0; i< s.length; i++){
            s[i] = s[i][0].toUpperCase() + s[i].slice(1);
            }
            return s;
        },
        
        _getHex : function(v){
            if( !this.options.enableDefault && v == ''){
                return '';
            }
            if( typeof this.options.colors[v] !='undefined' ) {
                return this.options.colors[v].hex;
            } else {
                return '';
            }
        },

        _getName : function(v){
            if( !this.options.enableDefault && v == ''){
                return 'None';
            }
            
            if( this._isCustom(v) ) {
                return 'Custom';
            }
            if( typeof this.options.colors[v] !='undefined' ) {
                if( typeof this.options.colors[v].name !='undefined' ) {
                    return this.options.colors[v].name;
                } else {
                    return _titleCase( v );
                }
            } else {
                return 'None';
            }
        },

        _isCustom : function( v ){
            return ( v.indexOf("#") != -1 )? true : false;
        },

        _updateHeaderItem : function( v ){

            var hex  = this._getHex( v );
            var name = this._getName( v );
            
            var header_item = $(this._element).find('.cpti-header-item').first();
            if(hex) {
                header_item.html( '<span style="background-color: '+hex+'"></span> '+name );
            }
            else {
                header_item.html( name );
            }
        },

        _updateInputs : function( v ){
            if(v == '#'){//special value for 'custom' item in the dropdown list
                v = '';
            }
            this.element.val(v);
        },

        _setValue : function( update_color_picker ) {
            if ( update_color_picker === undefined ) { update_color_picker = true; }
            var v = this.options.value;
            this._updateInputs(v);
            this._updateHeaderItem(v);
            if( this._isCustom(v) ) {
                this._showCustomColorPicker();
                if( update_color_picker === true ) {
                    this._updateColorPicker( v );
                }
            } else {
                this._hideCustomColorPicker();
            }
            this.options.onChange(v);
        },
        
        setValue : function( v ) {
            this.options.value = v;
            this._setValue();
        },

        _updateColorPicker : function( hex ){
            this._colorPickerElm.wpColorPicker('color', hex);
        },

        onChangeCusomColor : function( hex ){
            this.options.value = hex;
            this._setValue(false);
        },

        onClearCusomColor : function(){
            this.onChangeCusomColor('');
        },
        
        toggleDropdown : function(){
            $(this._element).find('.cpti-color-dropdown').first().toggleClass('closed');
        },
        
        _showCustomColorPicker : function(){
            $(this._element).find('.cpti-custom-color-picker-wrap').first().removeClass('hide');
        },

        _hideCustomColorPicker : function(){
            $(this._element).find('.cpti-custom-color-picker-wrap').first().addClass('hide');
        }
     
    }); //end cpti.cptiColorPicker widget

    $.fn.serializeArrayAll = function () {
        var rCRLF = /\r?\n/g;
        return this.map(function () {
            return this.elements ? jQuery.makeArray(this.elements) : this;
        }).map(function (i, elem) {
            var val = jQuery(this).val();
            if (val == null) {
                return val == null
            } else if (this.type == "checkbox" && this.checked == false) {
                return {name: this.name, value: this.checked ? this.value : ''}
            } else {
                return jQuery.isArray(val) ?
                        jQuery.map(val, function (val, i) {
                            return {name: elem.name, value: val.replace(rCRLF, "\r\n")};
                        }) :
                        {name: elem.name, value: val.replace(rCRLF, "\r\n")};
            }
        }).get();
    };
    var wpmi = {
        __instance: undefined
    };

    wpmi.Application = Backbone.View.extend(
            {
                id: 'wpmi_modal',
                events: {
                    'click .close': 'Close',
                    'click .remove': 'Remove',
                    'click .save': 'Save',
                    'click .attachments .attachment': 'Select',
                    'keyup #media-search-input': 'Search',
                },
                ui: {
                    nav: undefined,
                    content: undefined,
                    media: undefined
                },
                templates: {},
                initialize: function (e) {
                    'use strict';
                    _.bindAll(this, 'render', 'preserveFocus', 'Search', 'Select', 'Close', 'Save', 'Remove');
                    this.initialize_templates();
                    this.render(e);
                    this.backdrop(e);
                },
                backdrop: function (e) {
                    'use strict';

                    var plugin = this;

                    $(document).on('click', '.media-modal-backdrop', function (e) {
                        plugin.Close(e);
                    });
                },
                initialize_templates: function () {
                    this.templates.window = wp.template('wpmi-modal-window');
                    this.templates.backdrop = wp.template('wpmi-modal-backdrop');
                    this.templates.preview = wp.template('wpmi-modal-preview');
                    this.templates.settings = wp.template('wpmi-modal-settings');
                },
                render: function (e) {
                    'use strict';

                    var $li = $(e.target).closest('li'),
                            menu_item_id = parseInt($li.prop('id').match(/menu-item-([0-9]+)/)[1]),
                            wpmi = {};

                    $(e.target).closest('li').find('input.wpmi-input').each(function (i) {

                        var key = $(this).prop('id').match(/wpmi-input-([a-zA-Z_]+)/)[1],
                                value = $(this).val();

                        wpmi[key] = value;
                    });

                    this.$el.attr('tabindex', '0')
                            .data('menu_item_id', menu_item_id)
                            .append(this.templates.window())
                            .append(this.templates.backdrop());

                    this.ui.preview = this.$('.media-sidebar')
                            .append(this.templates.preview(wpmi))

                    this.ui.settings = this.$('.media-sidebar')
                            .append(this.templates.settings(wpmi))

                    this.ui.settings.find('#wpmi-input-color').cptiColorPicker({
                        colors : wpmi_options.predefined_colors,
                        enableDefault : true,
                    });
                    this.ui.settings.find('#wpmi-input-bgcolor').cptiColorPicker({
                        colors : wpmi_options.predefined_colors,
                    });
                    
                    $(document).on('focusin', this.preserveFocus);
                    $('body').addClass('modal-open').append(this.$el);
                    this.$el.focus();
                },
                preserveFocus: function (e) {
                    'use strict';
                    if (this.$el[0] !== e.target && !this.$el.has(e.target).length) {
                        this.$el.focus();
                    }
                },
                Search: function (e) {
                    'use strict';
                    var $this = $(e.target),
                            $icons = this.$el.find('.attachments .attachment');
                    $this.on('keyup', function (e) {
                        e.preventDefault();
                        setTimeout(function () {
                            var query = $this.val();
                            if (query !== '') {
                                $icons.css({'display': 'none'});
                                $icons.filter('[class*="' + query + '"]').css({'display': 'block'});
                            } else {
                                $icons.removeAttr('style');
                            }
                        }, 600);
                    });
                },
                Select: function (e) {
                    'use strict';
                    var $this = $(e.target),
                            $filename = this.$el.find('.media-sidebar .filename'),
                            $thumbnail = this.$el.find('.media-sidebar .thumbnail > i'),
                            $input = this.$el.find('input[name="wpmi[icon]"]'),
                            icon = $this.find('i').attr('class');
                    $filename.text(icon);
                    $input.val(icon);
                    $thumbnail.removeAttr('class').addClass(icon);
                },
                Close: function (e) {
                    'use strict';
                    e.preventDefault();
                    this.undelegateEvents();
                    $(document).off('focusin');
                    $('body').removeClass('modal-open');
                    this.remove();
                    wpmi.__instance = undefined;
                },
                Save: function (e) {
                    'use strict';
                    e.preventDefault();

                    var plugin = this,
                            $form = $('form', this.$el),
                            menu_item_id = this.$el.data('menu_item_id');

                    if (!menu_item_id)
                        return;

                    if (!$form.length)
                        return;

                    var $li = $('#menu-to-edit').find('#menu-item-' + menu_item_id),
                            $plus = $li.find('.menu-item-wpmi_plus'),
                            $icon = $li.find('.menu-item-wpmi_icon');

                    if (!$li.length)
                        return;

                    $form.find('.wpmi-input').each(function (i) {

                        var key = $(this).prop('id').match(/wpmi-input-([a-zA-Z_]+)/)[1],
                                value = $(this).val();

                        $li.find('input#wpmi-input-' + key).val(value);

                        if (key === 'icon') {

                            if ($icon.length) {

                                $icon.remove();
                            }

                            $plus.before('<i class="menu-item-wpmi_icon ' + value + '"></i>');
                        }
                    });

                    plugin.Close(e);
                },
                Remove: function (e) {
                    'use strict';
                    e.preventDefault();

                    var plugin = this,
                            $form = $('form', this.$el),
                            menu_item_id = this.$el.data('menu_item_id');

                    if (!menu_item_id)
                        return;

                    if (!$form.length)
                        return;

                    var $li = $('#menu-to-edit').find('#menu-item-' + menu_item_id),
                            $icon = $li.find('.menu-item-wpmi_icon');

                    if (!$li.length)
                        return;

                    $form.find('.wpmi-input').each(function (i) {

                        var key = $(this).prop('id').match(/wpmi-input-([a-zA-Z_]+)/)[1];

                        $li.find('input#wpmi-input-' + key).val('');

                    });

                    $icon.remove();

                    plugin.Close(e);
                }
            });

    $(document).on('click', '.menu-item-wpmi_open', function (e) {
        e.preventDefault();
        if (wpmi.__instance === undefined) {
            wpmi.__instance = new wpmi.Application(e);
        }
    });

    $(document).on('click', '#wpmi_metabox', function (e) {

        var menu_font = $('input:checked', $(this)).val(),
                menu_id = $('#menu').val();

        if ($(e.target).hasClass('save') && menu_font && menu_id) {

            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'wpmi_save_nav_menu',
                    menu_id: menu_id,
                    menu_font: menu_font,
                    nonce: wpmi_l10n.nonce
                },
                beforeSend: function () {
                },
                complete: function () {
                },
                error: function () {
                    alert('Error!');
                },
                success: function (response) {
                    location.reload();
                }
            });

        }
    });

})(jQuery);