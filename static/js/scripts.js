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
		var form, ajaxData, serial, emailTo;	// For e-mail form data sending.
		var isFirstname = false, 
			isPhone = false,
			isMessage = false,
			isEmail = false;	// Data-attribute if this fields are existing in form (not existing by default here).
		var isFirstnameRequired = false,
			isPhoneRequired = false,
			isEmailRequired = false,
			isMessageRequired = false;	// Are these fields required or not (not required by default here).

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
						if ( $( '.cwpmf-input', this ).hasClass( 'cwpmf-field-with-error' ) ) {
							$( '.cwpmf-input', this ).removeClass( 'cwpmf-field-with-error' );	// Remove error class from this input field.
							$( '.cwpmf-input-error-msg', this ).text( '' );	// Remove all error text.
						}

						// If textarea field has class for error.
						if ( $( '.cwpmf-textarea', this ).hasClass( 'cwpmf-field-with-error' ) ) {
							$( '.cwpmf-textarea', this ).removeClass( 'cwpmf-field-with-error' );	// Remove error class from this input field.
							$( '.cwpmf-input-error-msg', this ).text( '' );	// Remove all error text.
						}
					}
				);
				// If firstname input exists.
				isFirstname = isFieldExistsInForm( form, 'input', 'cwpmf-input-firstname' ) ? true : false;	// Set variable.
				// If firstname field has 'data-required' attribute.
				isFirstnameRequired = isFieldRequired( form, '.cwpmf-input-firstname' ) ? true : false;	// Set variable.
				// If phone input exists.
				isPhone = isFieldExistsInForm( form, 'input', 'cwpmf-input-phone' ) ? true : false;	// Set variable.
				// If phone field has 'data-required' attribute.
				isPhoneRequired = isFieldRequired( form, '.cwpmf-input-phone' ) ? true : false;	// Set variable.
				// If e-mail input exists.
				isEmail = isFieldExistsInForm( form, 'input', 'cwpmf-input-email' ) ? true : false;	// Set variable.
				// If e-mail field has 'data-required' attribute.
				isEmailRequired = isFieldRequired( form, '.cwpmf-input-email' ) ? true : false;	// Set variable.
				// If message textarea exists.
				isMessage = isFieldExistsInForm( form, 'textarea', 'cwpmf-input-message' ) ? true : false;	// Set variable.
				// If phone field has 'data-required' attribute.
				isMessageRequired = isFieldRequired( form, '.cwpmf-input-message' ) ? true : false;	// Set variable.

				ajaxData = {
					action					: '_cwpmf_send_email',
					serial 					: serial,
					email_to				: emailTo,
					is_firstname 			: isFirstname,
					is_phone 				: isPhone,
					is_email 				: isEmail,
					is_message 				: isMessage,
					is_firstname_required 	: isFirstnameRequired,
					is_phone_required 		: isPhoneRequired,
					is_email_required 		: isEmailRequired,
					is_message_required		: isMessageRequired
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
			    			console.log( data.data.message );	// Show errors in console.

			    			// If error occured with name field.
		    				if ( data.data.firstname[0] === false ) {
		    					if ( $( 'span', form ).hasClass( 'cwpmf-input__firstname' ) ) {	// If form has input for name.
		    						$( '.cwpmf-input__firstname' ).closest( '.cwpmf-label' ).find( 'input' ).addClass( 'cwpmf-field-with-error' );
									$( '.cwpmf-input__firstname .cwpmf-input-error-msg', form ).text( data.data.firstname[1] );	// Show error message near field.
								}
							}
							// If error occured with phone field.
							if ( data.data.phone[0] === false ) {
			    				if ( $( 'span', form ).hasClass( 'cwpmf-input__phone' ) ) {	// If form has input for phone.
			    					$( '.cwpmf-input__phone' ).closest( '.cwpmf-label' ).find( 'input' ).addClass( 'cwpmf-field-with-error' );
									$( '.cwpmf-input__phone .cwpmf-input-error-msg', form ).text( data.data.phone[1] );	// Show error message near field.
								}
							}
							// If error occured with e-mail field.
							if ( data.data.email[0] === false ) {
			    				if ( $( 'span', form ).hasClass( 'cwpmf-input__email' ) ) {	// If form has input for e-mail.
			    					$( '.cwpmf-input__email' ).closest( '.cwpmf-label' ).find( 'input' ).addClass( 'cwpmf-field-with-error' );
									$( '.cwpmf-input__email .cwpmf-input-error-msg', form ).text( data.data.email[1] );	// Show error message near field.
								}
							}
							// If error occured with message field.
							if ( data.data.textarea[0] === false ) {
								if ( $( 'span', form ).hasClass( 'cwpmf-input__message' ) ) {	// If form has input for phone.
									$( '.cwpmf-input__message' ).closest( '.cwpmf-label' ).find( 'textarea' ).addClass( 'cwpmf-field-with-error' );
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