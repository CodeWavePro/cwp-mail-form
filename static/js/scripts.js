jQuery( function( $ ) {
	var isActiveAjax = false;

	/**
	 * When all page is loaded.
	 */
	$( document ).ready( function() {
		var form, ajaxData, serial, emailTo;	// For e-mail form data sending.

		/**
		 * Show more info about product.
		 */
		$( 'body' ).on( 'submit', '.cwpmf', function( e ) {
			e.preventDefault();

			if ( !isActiveAjax ) {	// If user can use ajax.
				isActiveAjax = true;	// Ajax for other actions is blocked.
				form = $( this );
				serial = form.serialize();
				emailTo = form.attr( 'data-to' );

				// Check each label in form.
				$( '.cwpmf-label', form ).each(
					function( index, el ) {
						// If input field has class for error.
						if ( $( '.cwpmf-input', this ).hasClass( 'cwpmf-input-with-error' ) ) {
							$( '.cwpmf-input', this ).removeClass( 'cwpmf-input-with-error' );	// Remove error class from this input field.
							$( '.cwpmf-input-error-msg', this ).text( '' );	// Remove all error text.
						}
					}
				);

				ajaxData = {
					action		: '_cwpmf_send_email',
					serial 		: serial,
					email_to	: emailTo
				};

				$.post( cwpAjax.ajaxurl, ajaxData, function( data ) {	// Ajax post request.
					switch ( data.success ) {	// Checking ajax response.
						case true: 	// If ajax response is success.
							console.log( data.data.message );	// Show success message in console.
							$( 'input, textarea', form ).attr( 'value', '' );	// Clear all fields.
							isActiveAjax = false;	// User can use ajax ahead.
			    			break;

						case false: 	// If we have some errors.
			    			console.log( data.data.message );	// Show errors in console.

		    				if ( data.data.firstname[0] === false ) {	// If error occured with name field.
		    					if ( $( 'span', form ).hasClass( 'cwpmf-input__firstname' ) ) {	// If form has input for name.
		    						$( '.cwpmf-input__firstname' ).closest( '.cwpmf-label' ).find( 'input' ).addClass( 'cwpmf-input-with-error' );
									$( '.cwpmf-input__firstname .cwpmf-input-error-msg', form ).text( data.data.firstname[1] );	// Show error message near field.
								}
							}

							if ( data.data.phone[0] === false ) {	// If error occured with phone field.
			    				if ( $( 'span', form ).hasClass( 'cwpmf-input__phone' ) ) {	// If form has input for phone.
			    					$( '.cwpmf-input__phone' ).closest( '.cwpmf-label' ).find( 'input' ).addClass( 'cwpmf-input-with-error' );
									$( '.cwpmf-input__phone .cwpmf-input-error-msg', form ).text( data.data.phone[1] );	// Show error message near field.
								}
							}

							if ( data.data.textarea[0] === false ) {	// If error occured with phone field.
								if ( $( 'span', form ).hasClass( 'cwpmf-input__message' ) ) {	// If form has input for phone.
									$( '.cwpmf-input__message' ).closest( '.cwpmf-label' ).find( 'input' ).addClass( 'cwpmf-input-with-error' );
									$( '.cwpmf-input__message .cwpmf-input-error-msg', form ).text( data.data.textarea[1] );	// Show error message near field.
								}
							}
			    			isActiveAjax = false;	// User can use ajax ahead.
			    			break;

			    		default: 	// Default variant.
			    			console.log( 'Unknown error!' );	// Show message of unknown error in console.
			    			isActiveAjax = false;	// User can use ajax ahead.
			    			break;
					}
				} );
			}
		} );
	} );

} );