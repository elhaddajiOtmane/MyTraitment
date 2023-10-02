/* global confirm, redux, redux_change */


(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.custom_header_builder = redux.field_objects.custom_header_builder || {};

    var scroll = '';
    var itemOptBuilder,
    rowOptBuilder,
    itemPlus;

    redux.field_objects.custom_header_builder.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( ".redux-group-tab:visible" ).find( '.redux-container-custom_header_builder:visible' );
        }

        var groupTab = $( document ).find( ".redux-group-tab:visible" );

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;

                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }

                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }

                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }
                var modal_builder = 'wgl_modal_builder';

                /**    Overlay Header without active preset */

                var overlay_header = groupTab.find('.wgl_custom_preset-wrapper .overlay_header');

                if(overlay_header.length > 0){
                    overlay_header.detach();
                    el.find('.overlay_header').addClass('active');
                }

                el.find('.overlay_header a').on("click", function(e){
                    e.preventDefault();

                    jQuery('html, body').animate({
                        scrollTop: jQuery( jQuery.attr(this, 'href') ).offset().top - 40
                    }, 500);
                });

                /**    \End Overlay Header without active preset */

                /**    Call Modal Window with Items Optios */
                el.find('ul li i.edit-item').on("click", function(){
                    jQuery('table.wgl-item-option').find('tr').css({'display' : 'none'});
                    var optId = jQuery(this).data('optId');

                    for(var i = 0; i <= jQuery('table.wgl-item-option').find('tr').length; i++){
                        if(jQuery('table.wgl-item-option').find('tr')[i]){
                            var b = jQuery('table.wgl-item-option').find('tr')[i];
                            b = jQuery(b).find('fieldset').data('id');
                            if(b.indexOf(optId) >= 0){
                                jQuery(jQuery('table.wgl-item-option').find('tr')[i]).not('.hide').css({'display' : 'table-row'}).trigger('resize');
                            }
                        }
                    }

                    jQuery('.wgl-container-wrapper').css({'display' : 'none'});
                    jQuery('#wgl_modal_builder .modal-body').append(jQuery('table.wgl-item-option').css({'display' : 'table'}));
                    jQuery('#'+ modal_builder).css({'display' : 'block'});

                });

                /**    Close Modal Window */
                el.find('span.close').on("click", function(){
                    jQuery('#'+ modal_builder).css({'display' : 'none'});
                    jQuery('table.wgl-item-option').find('tr').css({'display' : 'none'});

                });

                /**    Call Modal Window with Row Optios */
                el.find('.wgl_header_row-opt a').on("click", function(e){
                     e.preventDefault();
                    var optId = jQuery(this).parent().data('sectionOpt');
                    jQuery('#wgl_modal_builder .modal-body').append( jQuery('.container_opt-row') );

                    jQuery('.wgl-container-wrapper').css({'display' : 'none'});
                    jQuery('table.wgl-item-option').find('tr').css({'display' : 'none'});

                    jQuery('.container_opt-row').each(function(){
                        if(jQuery(this).hasClass(optId)){
                            jQuery(this).css({'display' : 'block'});
                        }
                    });

                    jQuery('#'+ modal_builder).css({'display' : 'block'});

                });

                /**    Toggle Row */
                el.find('.wgl_header_row-toggle a').on("click", function(e){
                    e.preventDefault();
                    jQuery(this).closest('.wgl_header_row').toggleClass('hide_row');
                });

                /**    Disabled Row */
                el.find('.wgl_header_row-disabled a').on("click", function(e){
                    e.preventDefault();

                    if(jQuery(this).closest('.wgl_header_row').hasClass('disabled_row')){

                        jQuery(this).closest('.wgl_header_row-disabled').find('input').val('false');
                        jQuery(this).closest('.wgl_header_row').removeClass('disabled_row').addClass('enabled_row');

                    }else{
                        jQuery(this).closest('.wgl_header_row-disabled').find('input').val('true');
                        jQuery(this).closest('.wgl_header_row').removeClass('enabled_row').addClass('disabled_row');

                    }
                });

                /**    Call Modal Window with Column Optios */
                el.find('.edit_column a').on("click", function(e){
                    e.preventDefault();
                    var optId = jQuery(this).parent().data('optColId');
                    jQuery('#wgl_modal_builder .modal-body').append( jQuery('.container_opt-column') );

                    jQuery('.wgl-container-wrapper').css({'display' : 'none'});
                    jQuery('table.wgl-item-option').find('tr').css({'display' : 'none'});

                    jQuery('.container_opt-column').each(function(){
                        if(jQuery(this).hasClass(optId)){
                            jQuery(this).css({'display' : 'block'});
                        }
                    });

                    jQuery('#'+ modal_builder).css({'display' : 'block'});

                });

                /**    Add Item */
                el.find('.add_item a').on("click", function(e){
                    e.preventDefault();

                    jQuery('.wgl_header_items').css({'display' : 'block'});
                    jQuery('#wgl_modal_builder_items').css({'display' : 'block'});
                    itemPlus = jQuery(this).closest('ul').data( 'groupId' );
                });

                /**    Sorter (Layout Manager) */
                el.find( '.redux-sorter' ).each(
                    function() {
                        var id = $( this ).attr( 'id' );

                        //Options Items
                        itemOptBuilder = $(this).closest('.compiler').nextAll();
                        itemOptBuilder.wrapAll( "<table class='wgl-item-option'></table>" );
                        setTimeout(function(){
                            jQuery('table.wgl-item-option').css({'display' : 'none'});
                            jQuery('table.wgl-item-option').find('tr').css({'display' : 'none'});
                        }, 1);

                        //Options Row
                        rowOptBuilder = $(this).closest('.form-table').addClass('wgl-header-builder');

                        //Call options Row
                        b('top');
                        b('middle');
                        b('bottom');
                        setTimeout(function(){
                            jQuery('.wgl-container-wrapper').css({'display' : 'none'});
                        }, 1);

                        el.find( '#' + id ).find( 'ul' ).each(function(){
                            var opt_column = jQuery(this).find('.edit_column').data('optColId');
                            p(opt_column);
                        });

                        el.find( '#' + id ).find( 'ul:not(#bottom_header_layout_items)' ).sortable(
                            {
                                items: 'li',
                                placeholder: "placeholder",
                                connectWith: '.sortlist_' + id,
                                opacity: 0.8,
                                scroll: false,
                                cancel: '.add_item',
                                out: function( event, ui ) {
                                    if ( !ui.helper ) return;
                                    if ( ui.offset.top > 0 ) {
                                        scroll = 'down';
                                    } else {
                                        scroll = 'up';
                                    }
                                    redux.field_objects.custom_header_builder.scrolling( $( this ).parents( '.redux-field-container:first' ) );

                                },
                                revert: true,
                                over: function( event, ui ) {
                                    scroll = '';
                                },

                                deactivate: function( event, ui ) {
                                    scroll = '';
                                },
                                stop: function( event, ui ) {},

                                update: function( event, ui ) {

                                    $( this ).find( '.position' ).each(
                                        function() {
                                            //var listID = $( this ).parent().attr( 'id' );

                                            var listID = $( this ).parent().attr( 'data-id' );
                                            var parentID = $( this ).parent().parent().attr( 'data-group-id' );

                                            redux_change( $( this ) );

                                            var optionID = $( this ).parent().parent().parent().parent().parent().attr( 'id' );

                                            if(parentID != 'items'){
                                                if($( this ).parent().find( '.trash-item' ).length === 0){
                                                    $( this ).parent().find('.icon_wrapper').append('<i class="trash-item fas fa-trash fa fa-6"></i>');
                                                }
                                            }else{
                                                if($( this ).parent().find( '.add-item_icon' ).length === 0){
                                                    $( this ).parent().append('<span class="add-item_icon"></span>');
                                                }
                                            }
                                            $( this ).prop(
                                                "name",
                                                redux.args.opt_name + '[' + optionID + '][' + parentID + '][' + listID + ']'
                                            );
                                        }
                                    );
                                }
                            }
                        );
                        jQuery(document).on( "click", '.trash-item',
                            function( e ) {
                                var element = jQuery(this);

                                var r = confirm(wglBuilderVars.delete);
                                if (r == false) return;

                                jQuery( this ).closest('.redux-sorter').find( 'ul .position' ).each(
                                    function() {
                                        var listID = jQuery( this ).parent().attr( 'data-id' );
                                        var parentID = jQuery( this ).parent().parent().attr( 'data-group-id' );
                                        jQuery(element).closest('li').detach().appendTo('#bottom_header_layout_items');

                                        redux_change( jQuery( this ) );

                                        var optionID = jQuery( this ).parent().parent().parent().parent().parent().attr( 'id' );

                                        if(parentID == 'items'){
                                            if($( this ).parent().find( '.add-item_icon' ).length === 0){
                                                $( this ).parent().append('<span class="add-item_icon"></span>');
                                            }
                                        }
                                        jQuery( this ).prop(
                                            "name",
                                            redux.args.opt_name + '[' + optionID + '][' + parentID + '][' + listID + ']'
                                            );
                                    }
                                );

                                jQuery(this).remove();

                        });

                        jQuery(document).on( "click", '.add-item_icon',
                            function( e ) {
                                var element = jQuery(this);

                                jQuery( this ).closest('.redux-field-container').find( 'ul .position' ).each(
                                    function() {

                                        var listID = jQuery( this ).parent().attr( 'data-id' );

                                        jQuery(element).closest('li').detach().insertBefore('ul[data-group-id="'+ itemPlus +'"] .add_item');

                                        redux_change( jQuery( this ) );

                                        var optionID = jQuery( this ).parent().parent().parent().parent().parent().attr( 'id' );

                                        var parentID = jQuery( this ).parent().parent().attr( 'data-group-id' );

                                        if(parentID != 'items'){
                                            if($( this ).parent().find( '.trash-item' ).length === 0){
                                                $( this ).parent().find('.icon_wrapper').append('<i class="trash-item fas fa-trash fa fa-6"></i>');
                                            }
                                        }

                                        jQuery( this ).prop(
                                            "name",
                                            redux.args.opt_name + '[' + optionID + '][' + parentID + '][' + listID + ']'
                                            );
                                    }
                                );

                                jQuery(this).remove();

                        });
                        el.find( ".redux-sorter" ).disableSelection();
                    }
                );
                el.find( 'select.redux-select-item' ).each(
                    function() {
                        var default_params = {
                            width: 'resolve',
                            triggerChange: true,
                            allowClear: true
                        };

                        if ( $( this ).siblings( '.select2_params' ).size() > 0 ) {
                            var select2_params = $( this ).siblings( '.select2_params' ).val();
                            default_params = $.extend( {}, default_params, select2_params );
                        }

                        $( this ).select2( default_params );

                        $( this ).on(
                            "change", function() {
                                $( this ).siblings( '.select2_params' ).val($(this).val());
                                var parentID = jQuery( $( $( this ) ) ).closest( '.redux-group-tab' ).attr( 'id' );
                                if(parentID){
                                    redux_change( $( $( this ) ) );
                                    $( this ).select2SortableOrder();
                                }

                            }
                        );
                    }
                );
            }
        );
    };

    redux.field_objects.custom_header_builder.scrolling = function( selector ) {
        if (selector === undefined) {
            return;
        }

        var scrollable = selector.find( ".redux-sorter" );

        if ( scroll == 'up' ) {
            scrollable.scrollTop( scrollable.scrollTop() - 20 );
            setTimeout( redux.field_objects.custom_header_builder.scrolling, 50 );
        } else if ( scroll == 'down' ) {
            scrollable.scrollTop( scrollable.scrollTop() + 20 );
            setTimeout( redux.field_objects.custom_header_builder.scrolling, 50 );
        }
    };

    var b = function($params) {
        var array = [];
        array.push(jQuery(rowOptBuilder).siblings("#section-header_"+ $params +"-start"),
            jQuery(rowOptBuilder).siblings(".indent-section-container").find("#section-header_"+ $params +"-start"),
            jQuery(rowOptBuilder).siblings("#section-table-header_"+ $params +"-start"),
            jQuery(rowOptBuilder).siblings("#section-header_"+ $params +"-end"),
            jQuery(rowOptBuilder).siblings(".indent-section-container").find("#section-header_"+ $params +"-end"),
            jQuery(rowOptBuilder).siblings("#section-table-header_"+ $params +"-end")
            );
        for(var i = 0; i <= array.length; i++){
            jQuery(array[i]).wrap('<div class="'+ $params +'_header">');
        }
        jQuery('.'+ $params +'_header').wrapAll( "<div class='wgl-container-wrapper container_opt-row" + " " + $params +"' />");
    };

    var p = function($params) {
        var array = [];

        array.push(jQuery(rowOptBuilder).siblings("#section-header_column-"+ $params +"-start"),
            jQuery(rowOptBuilder).siblings(".indent-section-container").find("#section-header_column-"+ $params +"-start"),
            jQuery(rowOptBuilder).siblings("#section-table-header_column-"+ $params +"-start"),
            jQuery(rowOptBuilder).siblings("#section-header_column-"+ $params +"-end"),
            jQuery(rowOptBuilder).siblings(".indent-section-container").find("#section-header_column-"+ $params +"-end"),
            jQuery(rowOptBuilder).siblings("#section-table-header_column-"+ $params +"-end")
            );
        for(var i = 0; i <= array.length; i++){
            jQuery(array[i]).wrap('<div class="'+ $params +'_header_column">');
        }
        jQuery('.'+ $params +'_header_column').wrapAll( "<div class='wgl-container-wrapper container_opt-column" + " " + $params +"' />");
    };

    window.onclick = function(event) {
        if (event.target == document.getElementById('wgl_modal_builder')) {
            document.getElementById('wgl_modal_builder').style.display = "none";
        }
        if (event.target == document.getElementById('wgl_modal_builder_items')) {
            document.getElementById('wgl_modal_builder_items').style.display = "none";
        }

    }


})( jQuery );