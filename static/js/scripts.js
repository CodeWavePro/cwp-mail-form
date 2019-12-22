jQuery( function( $ ) {
	var isActiveAjax = false;

	/**
	 * If field in some form has some class name (exists),
	 * return true, if not - false.
	 */
	function isFieldExistsInForm( form, tag, className ) {
		return ( $( tag, form ).hasClass( className ) ? true : false );
	}

	/**
	 * If field in some form has some class name (exists) and data-required attribute,
	 * return true, if not - false.
	 */
	function isFieldRequired( form, className ) {
		return ( $( className, form ).attr( 'data-required' ) ? true : false );
	}

	/**
	 * When all page is loaded.
	 */
	$( document ).ready( function() {
		var form, ajaxData, emailTo;	// For e-mail form data sending.
		var fieldsArray;	// Array for all form fields.
		var iter,
			invalidFieldId,
			nonce;

		/**
		 * Show more info about product.
		 */
		$( 'body' ).on( 'submit', '.cwpmf', function( e ) {
			e.preventDefault();

			if ( !isActiveAjax ) {	// If user can use ajax.
				isActiveAjax = true;	// Ajax for other actions is blocked.
				form = $( this );
				emailTo = form.attr( 'data-to' );
				nonce = $( '#cwpmf_nonce_field', form ).val();

				$( 'input, textarea', form ).removeClass( 'cwpmf-field-with-error' );	// Remove error class from fields.
				$( '.cwpmf-input-error-msg', form ).text( '' );	// Remove all error text.
				fieldsArray = new Array();	// Empty array for all important data.

				if ( $( 'input', form ).hasClass( 'cwpmf-input-text' ) ) {
					$( '.cwpmf-input-text' ).each( function( index, el ) {
						fieldsArray.push( {
							'type'		: 'text',
							'id'		: $( this ).attr( 'id' ),
							'required'	: $( this ).attr( 'data-required' ),
							'value'		: $( this ).val()
						} );
					} );
				}

				if ( $( 'input', form ).hasClass( 'cwpmf-input-name' ) ) {
					$( '.cwpmf-input-name' ).each( function( index, el ) {
						fieldsArray.push( {
							'type'		: 'name',
							'id'		: $( this ).attr( 'id' ),
							'required'	: $( this ).attr( 'data-required' ),
							'value'		: $( this ).val()
						} );
					} );
				}

				if ( $( 'input', form ).hasClass( 'cwpmf-input-phone' ) ) {
					$( '.cwpmf-input-phone' ).each( function( index, el ) {
						fieldsArray.push( {
							'type'		: 'phone',
							'id'		: $( this ).attr( 'id' ),
							'required'	: $( this ).attr( 'data-required' ),
							'value'		: $( this ).val()
						} );
					} );
				}

				if ( $( 'input', form ).hasClass( 'cwpmf-input-email' ) ) {
					$( '.cwpmf-input-email' ).each( function( index, el ) {
						fieldsArray.push( {
							'type'		: 'email',
							'id'		: $( this ).attr( 'id' ),
							'required'	: $( this ).attr( 'data-required' ),
							'value'		: $( this ).val()
						} );
					} );
				}

				if ( $( 'textarea', form ).hasClass( 'cwpmf-input-message' ) ) {
					$( '.cwpmf-input-message' ).each( function( index, el ) {
						fieldsArray.push( {
							'type'		: 'message',
							'id'		: $( this ).attr( 'id' ),
							'required'	: $( this ).attr( 'data-required' ),
							'value'		: $( this ).val()
						} );
					} );
				}

				ajaxData = {
					action		: '_cwpmf_send_email',
					email_to	: emailTo,
					nonce 		: nonce,
					fields_array: fieldsArray
				};

				$.post( cwpAjax.ajaxurl, ajaxData, function( data ) {	// Ajax post request.
					switch ( data.success ) {	// Checking ajax response.
						case true: 	// If ajax response is success.
							$( '.cwpmf-popup-message' ).html( data.data.message );	// Put success message to its wrapper in popup.
							$( '.cwpmf-popup' ).css( 'display', 'block' );	// Show wrapper for success message. 
							// 1ms timeout to show animation after it. Needed because of "display none - display block" changing.
							setTimeout(
								function() {
									$( '.cwpmf-popup' ).css( 'z-index', '51' );
									$( '.cwpmf-popup' ).removeClass( 'fadeOut' ).addClass( 'fadeIn' );	// Animate wrapper for success message.
									$( '.cwpmf-popup-inner' ).removeClass( 'bounceOutUp' ).addClass( 'jackInTheBox' );	// Animate popup inner for success message.
								}, 1
							);
							$( 'input, textarea', form ).attr( 'value', '' );	// Clear all fields.
							isActiveAjax = false;	// User can use ajax ahead.
			    			break;

						case false: 	// If we have some errors.
			    			if ( data.data.array ) {	// If array of invalid fields is not empty.
			    				console.log( data.data.message );	// Show errors in console.

			    				for( iter = 0; iter < data.data.array.length; iter++ ) {
			    					if ( data.data.array[iter]['message'] != '' ) {	// If current field has error.
			    						invalidFieldId = '#' + data.data.array[iter]['id'];	// Make ID 'jQuery-like'.
				    					$( invalidFieldId, form ).addClass( 'cwpmf-field-with-error' );	// Add error class to invalid field.
				    					$( invalidFieldId, form ).closest( '.cwpmf-label' ).find( '.cwpmf-input-error-msg' ).text( data.data.array[iter]['message'] ); // Output error message for invalid field.
			    					}
			    				}
			    			}	else {	// If array is not in response show specific error message in popup.
			    				$( '.cwpmf-popup-message' ).html( data.data.message );	// Put success message to its wrapper in popup.
								$( '.cwpmf-popup' ).css( 'display', 'block' );	// Show wrapper for success message. 
								// 1ms timeout to show animation after it. Needed because of "display none - display block" changing.
								setTimeout(
									function() {
										$( '.cwpmf-popup' ).css( 'z-index', '51' );
										$( '.cwpmf-popup' ).removeClass( 'fadeOut' ).addClass( 'fadeIn' );	// Animate wrapper for success message.
										$( '.cwpmf-popup-inner' ).removeClass( 'bounceOutUp' ).addClass( 'jackInTheBox' );	// Animate popup inner for success message.
									}, 1
								);
			    			}
			    			isActiveAjax = false;	// User can use ajax ahead.
			    			break;

			    		default: 	// Default variant.
			    			$( '.cwpmf-popup-message' ).html( data.data.message );	// Put success message to its wrapper in popup.
							$( '.cwpmf-popup' ).css( 'display', 'block' );	// Show wrapper for success message. 
							// 1ms timeout to show animation after it. Needed because of "display none - display block" changing.
							setTimeout(
								function() {
									$( '.cwpmf-popup' ).css( 'z-index', '51' );
									$( '.cwpmf-popup' ).removeClass( 'fadeOut' ).addClass( 'fadeIn' );	// Animate wrapper for success message.
									$( '.cwpmf-popup-inner' ).removeClass( 'bounceOutUp' ).addClass( 'jackInTheBox' );	// Animate popup inner for success message.
								}, 1
							);
			    			isActiveAjax = false;	// User can use ajax ahead.
			    			break;
					}
				} );
			}
		} );

		/**
		 *	Popup message close.
		 */
		$( 'body' ).on(
			'click',
			'.cwpmf-popup__close, .cwpmf-popup-button__link',
			function( e ) {
				e.preventDefault();
				$( '.cwpmf-popup-inner' ).removeClass( 'jackInTheBox' ).addClass( 'bounceOutUp' );	// Animate popup inner before hiding.
				$( '.cwpmf-popup' ).removeClass( 'fadeIn' ).addClass( 'fadeOut' );	// Animate wrapper for success message before hiding.
				// 1s timeout to play exiting animation.
				setTimeout(
					function() {
						$( '.cwpmf-popup' ).css( 'display', 'none' );	// Hide wrapper for success message. 
						$( '.cwpmf-popup-message' ).html( '' );	// Clear message field in popup.
						$( '.cwpmf-popup' ).css( 'z-index', '-1' );
					}, 1000
				);
			}
		);
	} );

} );