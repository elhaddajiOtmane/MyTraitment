jQuery( function ( $ ) {
	'use strict';
    var frame,
        $doc = $( document );

    $doc.on( 'click', '.rwmb-file-input-select', function ( e ) {
        e.preventDefault();
        var $el = $( this );

        // Create a frame only if needed
        if ( ! frame ) {
            frame = wp.media( {
                className: 'media-frame rwmb-file-frame',
                multiple: false,
                title: 'Select File'
            } );
        }

        // Open media uploader
        frame.open();

        // Remove all attached 'select' event
        frame.off( 'select' );

        // Handle selection
        frame.on( 'select', function () {
            var url = frame.state().get( 'selection' ).first().toJSON().url;
            $el.siblings( 'input' ).val( url ).siblings( 'a' ).removeClass( 'hidden' );
            if($el.siblings('.metabox-screenshot').length === 0){
            	$el.closest('.rwmb-background-row').append( '<div class="metabox-screenshot"><img class="metabox-option-image" src="' + url + '"></div>' ).slideDown( 'fast' );
            }else{
            	$el.siblings('.metabox-screenshot').find('img').attr('src', url);
            }
            
        } );
    } );

    // Clear selected images
    $doc.on( 'click', '.rwmb-file-input-remove', function ( e ) {
        e.preventDefault();
        $( this ).addClass( 'hidden' ).siblings( 'input' ).val( '' );
        $( this ).addClass( 'hidden' ).siblings( '.metabox-screenshot' ).remove();
    } );

    // Hide the Remove button when cloning
    $doc.on( 'clone', '.rwmb-file_input', function () {
        $( this ).siblings( '.rwmb-file-input-remove' ).addClass( 'hidden' );
    } );
} );