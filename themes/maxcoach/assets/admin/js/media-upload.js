jQuery( document ).ready( function( $ ) {
	'use strict';

	console.clear();
	console.log( 'script running' );

	// Only show the "remove button" when needed.
	$( '.maxcoach-media-wrap' ).each( function() {
		var $mediaInput = $( this ).find( '.maxcoach-media-input' );

		if ( ! $mediaInput.val() ) {
			$( this ).find( '.maxcoach-media-remove' ).hide();
		}
	} );

	$( document ).on( 'click', '.maxcoach-media-upload', function( e ) {
		e.preventDefault();

		var $parent     = $( this ).parents( '.maxcoach-media-wrap' ).first(),
		    $mediaInput = $parent.find( '.maxcoach-media-input' );

		/*var mediaFrame;

		// If the frame already exists, re-open it.
		if ( mediaFrame ) {
			mediaFrame.open();
			return;
		}*/

		var mediaFrame = wp.media.frames.mediaFrame = wp.media( {
			title: 'Select File',
			button: {
				text: 'Use image'
			},
			className: 'media-frame maxcoach-media-frame',
			frame: 'select',
			multiple: false,
			library: {
				type: 'video'
			}
		} );

		mediaFrame.on( 'select', function() {
			var attachment = mediaFrame.state().get( 'selection' ).first().toJSON();

			$mediaInput.val( attachment.url ).trigger( 'change' );

			$parent.find( '.maxcoach-media-remove' ).show();
		} );

		// Finally, open up the frame, when everything has been set.
		mediaFrame.open();
	} );

	$( document ).on( 'click', '.maxcoach-media-remove', function( e ) {
		e.preventDefault();

		var $parent     = $( this ).parents( '.maxcoach-media-wrap' ).first(),
		    $mediaInput = $parent.find( '.maxcoach-media-input' );

		$mediaInput.val( '' );
		$( this ).hide();
	} );
} );
