/*
 *  Project:        AvocodeFormExtensionsBundle
 *  Description:    jQuery plugin for Bootstrap collection
 *  Author:         loostro <loostro@gmail.com>
 *  License:        MIT
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, undefined ) {
    
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.
    
    // window is passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'bootstrapCollection',
        document = window.document,
        defaults = {
            allow_add:        false,
            allow_delete:     false,
            sortable:         false,
            sortable_field:   'position',
            prototype_name:   '__name__',
            trans: {
                new_label:      'New item',
                confirm:        'Are you sure you want to delete this element?',
                confirm_batch:  'Are you sure you want to delete all selected elements?'
            },
            javascript: function(id) {}
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;
        this.$element = $('#' + element.id);

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend( {}, defaults, options) ;
        
        this._defaults = defaults;
        this._name = pluginName;
        
        this._init();
    }
    
    Plugin.prototype = {

        _init: function() {
            // Plugin-scope helper
            var that = this;
            
            // Configure allow add
            if (this.options.allow_add) {
                this.$new   = $('#'+ this.element.id +'_toolbar > .new');
                this.nextId = this.$element.children('.collection').children('.collection-item').length;
                
                this.$new.on('click', function(e) {
                    e.preventDefault();
                    var addingEvent = $.Event('adding');
                    that.$element.trigger(addingEvent);
                    if (!addingEvent.isDefaultPrevented()) {
                    	that._onAdd();
                    	that.$element.trigger('added');
                    }
                });
            }
            
            // Configure allow delete
            if (this.options.allow_delete) {

                // remove item
                $('.'+ this.element.id + '_actions .delete').click(function(e){
                    e.preventDefault();
                    var deletingEvent = $.Event('deleting');
                    var $targetedElement = $(this).closest('.collection-item');
                    that.$element.trigger(deletingEvent, [ $targetedElement ]);
                    if (!deletingEvent.isDefaultPrevented()) {
                    	that._onDelete(this);
                    	that.$element.trigger('deleted', [ $targetedElement ]);
                    }
                });
                
                // select/deselect all
                this.$toggleAll = $('#'+ this.element.id + '_toolbar > .btn-toggle > input[name="toggle"]');
                
                this.$toggleAll.on('change', function(e) {
                    that._onToggleAll();
                });

                // delete selected
                $('#'+ this.element.id + '_toolbar > .batch-delete').click(function() {
                	var deletingAllEvent = $.Event('deleting-all');
                    that.$element.trigger(deletingAllEvent);
                    if (!deletingAllEvent.isDefaultPrevented()) {
                    	that._onDeleteAll();
                    	that.$element.trigger('deleted-all');
                    }
                });
            }
            
            // Init sortable
            if (this.options.sortable) {
                $('#' + this.element.id + ' > .collection').sortable({
                    update: function(e, ui) {
                        that._onChange();
                    },
                    cancel: "a, button, img, input, textarea, select, iframe, .cke div, .cke span",
                    start: function (event,ui) {
                        if (typeof CKEDITOR !== "undefined") {
                            // save configs for dragged instances of ckeditior and destroy them
                            that.ckeConfigs = [];
                            $('textarea', ui.item).each(function(){
                                var tagId = $(this).attr('id');
                                if (CKEDITOR.instances[tagId]) {
                                    var ckeClone = $(this).next('.cke').clone().addClass('cloned');
                                    that.ckeConfigs[tagId] = CKEDITOR.instances[tagId].config;
                                    CKEDITOR.instances[tagId].destroy();
                                    $(this).hide().after(ckeClone);
                                }
                            });
                        }
                    },
                    stop: function(event, ui) {
                        if (typeof CKEDITOR !== "undefined") {
                            // reinitialize dragged instances of ckeditior
                            $('textarea', ui.item).each(function(){
                                var tagId = $(this).attr('id');
                                if (that.ckeConfigs[tagId]) {
                                    CKEDITOR.replace(tagId, that.ckeConfigs[tagId]);
                                    $(this).next('.cloned').remove();
                                }
                            });
                        }
                    }
                });
            }
        },
                
        _onAdd: function() {
            var new_item = $('#'+ this.element.id).data('prototype');
            var new_id = this.nextId;
            new_item = new_item.replace(new RegExp('label__', 'g'), this.options.trans.new_label);
            new_item = new_item.replace(new RegExp(this.options.prototype_name, 'g'), new_id);
            $new_item = $(new_item);

            if (this.options.allow_delete) {
                var that = this;
                $new_item.find('.delete').click(function(){
                	var deletingEvent = $.Event('deleting');
                    var $targetedElement = $(this).closest('.collection-item');
                    that.$element.trigger(deletingEvent, [ $targetedElement ]);
                    if (!deletingEvent.isDefaultPrevented()) {
                    	that._onDelete(this);
                    	that.$element.trigger('deleted', [ $targetedElement ]);
                    }
                });
            }

            this.nextId++;
            $('#'+ this.element.id +' > .collection').append($new_item);
            
            this.options.javascript.call(window, this.element.id + '_' + new_id);

            if (this.options.sortable) {
                this._onChange();
            }
        },
                
        _onDelete: function(button) {
            if (confirm(this.options.trans.confirm)) {
                $(button).closest('.collection-item').remove();
                
                if (this.options.sortable) {
                    this._onChange();
                }
            }
        },
                
        _onChange: function() {
            // update sortable positions
            $('[id^="'+ this.element.id +'"][id$="'+ this.options.sortable_field +'"]').each(function(i){
                $(this).val(i);
            });
        },
                
        _onToggleAll: function() {
            if (this.$toggleAll.is(':checked')) {
                this.$toggleAll.prop('checked', true);
                $('.'+ this.element.id +'_actions > .btn-toggle > input[name="delete"]').prop('checked', true);
            } else {
                this.$toggleAll.prop('checked', false);
                $('.'+ this.element.id +'_actions > .btn-toggle > input[name="delete"]').prop('checked', false);
            }
        },
                
        _onDeleteAll: function() {
            if (confirm(this.options.trans.confirm_batch)) {
                $('.'+ this.element.id +'_actions > .btn-toggle > input[name="delete"]:checked').each(function(){
                      $(this).closest('.collection-item').remove();
                });

                if (this.options.sortable) {
                    this._onChange();
                }

                this.$toggleAll.prop('checked', false);
            }
        }
        
    };

    // You don't need to change something below:
    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations and allowing any
    // public function (ie. a function whose name doesn't start
    // with an underscore) to be called via the jQuery plugin,
    // e.g. $(element).defaultPluginName('functionName', arg1, arg2)
    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {

                // Only allow the plugin to be instantiated once,
                // so we check that the element has no plugin instantiation yet
                if (!$.data(this, 'plugin_' + pluginName)) {

                    // if it has no instance, create a new one,
                    // pass options to our plugin constructor,
                    // and store the plugin instance
                    // in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
                }
            });

        // If the first parameter is a string and it doesn't start
        // with an underscore or "contains" the `init`-function,
        // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                  $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window));
