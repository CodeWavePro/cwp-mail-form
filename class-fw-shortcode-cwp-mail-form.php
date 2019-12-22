<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Shortcode_CWP_Mail_Form extends FW_Shortcode {
	public function _init() {
        $this->register_ajax();
    }

    /**
     * Clean incoming value from trash.
     */
    private function clean_value( $value ) {
    	$value = trim( $value );
	    $value = stripslashes( $value );
	    $value = strip_tags( $value );
	    $value = htmlspecialchars( $value );
	    return $value;
    }

    /**
	 * Function checks name symbols (RU, UA, EN).
	 */
	private function check_name( $name ) {
		$name_check = [ 'А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з',
						'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р',
						'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ',
						'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я', 'Є', 'є', 'І', 'і', 'Ї', 'ї',
						'A', 'a', 'B', 'b', 'C', 'c', 'D', 'd', 'E', 'e', 'F', 'f', 'G', 'g', 'H', 'h', 'I', 'i',
						'J', 'j', 'K', 'k', 'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'Q', 'q', 'R', 'r',
						'S', 's', 'T', 't', 'U', 'u', 'V', 'v', 'W', 'w', 'X', 'x', 'Y', 'y', 'Z', 'z', '-', '\'', ' ' ];
		$name_arr = preg_split( '//u', $name, -1, PREG_SPLIT_NO_EMPTY );

		for ( $i = 0; $i < count( $name_arr ); $i++ ) {
			$break_flag = 1;

			for ( $j = 0; $j < count( $name_check ); $j++ ) {
				if ( $name_arr[$i] == $name_check[$j] ) {
					$break_flag = 0;
					break;
				}
			}

			if ( $break_flag === 1 ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Function checks if value length is between min and max parameters.
	 */
	private function check_length( $value, $min, $max ) {
	    $result = ( mb_strlen( $value ) < $min || mb_strlen( $value ) > $max );
	    return !$result;
	}

	/**
	 * Function checks phone symbols (only 0-9, -, +, (, ), ' ' are allowed).
	 */
	private function check_phone( $phone ) {
		$phone_check = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '+', '(', ')', ' '];
		$phone_arr = preg_split( '//u', $phone, -1, PREG_SPLIT_NO_EMPTY );

		for ( $i = 0; $i < count( $phone_arr ); $i++ ) {
			$break_flag = 1;

			for ( $j = 0; $j < count( $phone_check ); $j++ ) {
				if ( $phone_arr[$i] == $phone_check[$j] ) {
					$break_flag = 0;
					break;
				}
			}

			if ( $break_flag === 1 ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Function checks data from form fields.
	 * @param $is_field_required - is field required or not.
	 * @param $field_data - what data from this field came to back-end.
	 * @param $field_type - data type to check.
	 */
	private function check_field_data( $is_field_required, $field_data, $field_type ) {
		if ( ( $is_field_required === 'true' ) && empty( $field_data ) ) {
			return false;
		}

		switch ( $field_type ) {
			case 'text':
				if ( !empty( $field_data ) &&
		 	 		 !$this->check_length( $field_data, 0, 50 ) ) {
					return false;
				}
				break;

			case 'name':
				if ( !empty( $field_data ) &&
			 		 !$this->check_name( $field_data ) ||
		 	 		 !$this->check_length( $field_data, 0, 30 ) ) {
					return false;
				}
				break;

			case 'phone':
				if ( !empty( $field_data ) &&
			 		 !$this->check_phone( $field_data ) ||
		 	 		 !$this->check_length( $field_data, 0, 30 ) ) {
					return false;
				}
				break;

			case 'email':
				if ( !empty( $field_data ) &&
			 		 !filter_var( $field_data, FILTER_VALIDATE_EMAIL ) ||
		 	 		 !$this->check_length( $field_data, 0, 30 ) ) {
					return false;
				}
				break;

			case 'text':
				if ( !empty( $field_data ) &&
		 	 		 !$this->check_length( $field_data, 0, 500 ) ) {
					return false;
				}
				break;
		}
		return true;
	}

	/**
	 * Creating e-mail HTML-structure.
	 */
	private function cwpmf_email_letter_structure( $user_name, $user_phone, $user_email, $user_text, $user_message ) {
		// Logotype from customizer theme settings.
		$header_logo = ( fw_get_db_customizer_option( 'header_logo' ) ) ? fw_get_db_customizer_option( 'header_logo' )['url'] : get_template_directory_uri() . 'inc/img/no-img.png';

		$message = '
			<!DOCTYPE html>
			<html>
			  <head>
			    <meta charset = "utf-8" />
			    <title>Форма обратной связи на сайте ' . $_SERVER['HTTP_HOST'] . '</title>
			    <style>
				    a {
				    	color: #58c800 !important
				    }

			    	.cwpmf-letter-header {
			    		padding: 30px
			    	}

			    	h1,
			    	h2 {
			    		font-family: Calibri
			    	}

			    	h1 {
			    		text-transform: uppercase;
			    		letter-spacing: 1px
			    	}

			    	.cwpmf-letter-header {
			    		position: relative;
			    		background-color: #efefef;
			    		margin: 0 auto;
			    		margin-bottom: 50px;
			    		max-width: 1170px
			    	}

			    	.cwpmf-letter-header__logo {
			    		height: 100px;
			    		width: auto
			    	}

			    	.cwpmf-letter-header__title,
			    	.cwpmf-letter-header__posttitle,
			    	.cwpmf-letter-content,
			    	.cwpmf-letter-footer__text {
			    		text-align: center
			    	}

			    	.cwpmf-letter-content {
			    		position: relative;
			    		padding: 0 30px;
			    		margin: 0 auto;
			    		max-width: 1170px
			    	}

			    	.cwpmf-letter-content-paragraph {
			    		color: #323232 !important;
			    		font-size: 18px !important;
			    		font-family: Calibri
			    	}

			    	.cwpmf-letter-footer {
			    		background-color: #323232;
						padding: 15px 30px;
						margin-top: 50px;
						margin: 0 auto;
			    		max-width: 1170px
			    	}

			    	.cwpmf-letter-footer__text {
			    		color: #f9f9f9;
			    		font-size: 14px !important;
			    		font-family: Calibri
			    	}

			    	.cwpmf-letter-footer__link {
			    		color: #ff3e3e !important
			    	}
			    </style>
			  </head>

			  <body>
			    <header class = "cwpmf-letter-header">
			    	<h1 class = "cwpmf-letter-header__title">
			    		Здравствуйте!
			    	</h1>
			    	<h2 class = "cwpmf-letter-header__posttitle">
			    		Вы получили это сообщение с формы обратной связи на сайте ' . $_SERVER['HTTP_HOST'] . '
			    	</h2>
			    </header>

			    <div class = "cwpmf-letter-content">
			    	<p class = "cwpmf-letter-content-paragraph">
			    		' . $user_name . '<br />' . $user_phone . '<br />' . $user_email . '<br />' . $user_text . '<br />' . $user_message . '
			    	</p>
			    </div>

			    <footer class = "cwpmf-letter-footer">
			    	<p class = "cwpmf-letter-footer__text">
			    		Сайт разработан <a class = "cwpmf-letter-footer__link" href = "https://codewave.pro/" target = "_blank">CodeWavePro</a>
			    	</p>
			    </footer>
			  </body>
			</html>
		';
		return $message;
	}

    /**
     * Register Ajax functions.
     */
    private function register_ajax() {
        add_action(
        	'wp_ajax__cwpmf_send_email',
        	[
        		$this,
        		'_cwpmf_send_email'
        	]
        );
		add_action(
			'wp_ajax_nopriv__cwpmf_send_email',
			[
				$this,
				'_cwpmf_send_email'
			]
		);
    }

    /**
	 * Send User e-mail to website Administrator.
	 */
    public function _cwpmf_send_email() {
    	// Verify nonce from hidden field.
    	if ( empty( $_POST ) ||
    		 !wp_verify_nonce( $_POST['nonce'], '_cwpmf_send_email_nonce') ) {
    		// Send error message if nonce not verified.
			wp_send_json_error(
				[
					'message'	=> esc_html__( 'Данные переданы из неизвестного источника. Выход из функции.', 'mebel-laim' )
				]
			);
		}

		// E-mail where letter must be send to.
		$email_to = $this->clean_value( $_POST['email_to'] );
		// Empty array for results of all fields validation.
		$post_fields_array = $_POST['fields_array'];
		$fields_array = $field_check_result = [];
		// Variable is false if all fields are good. Will be set to true if at least one error appeared.
		$is_error = false;

		// Cleaning all array data and checking for not valid data.
		foreach ( $post_fields_array as $key => $field ) {
			$fields_array[$key]['type'] = $this->clean_value( $field['type'] );
			$fields_array[$key]['id'] = $this->clean_value( $field['id'] );
			$fields_array[$key]['required'] = $this->clean_value( $field['required'] );
			$fields_array[$key]['value'] = $this->clean_value( $field['value'] );

			/**
			 * Check if this fields are empty -> sending error message.
			 */
			if ( empty( $fields_array[$key]['type'] ) ||
				 empty( $fields_array[$key]['required'] ) ) {
				wp_send_json_error(
					[
						'message'	=> sprintf( esc_html__( 'Переданы некорректные данные о поле с классом %s.', 'mebel-laim' ), $fields_array[$key]['class'] )
					]
				);
			}

			/**
			 * Validation results array - will be send to front-end:
			 * - class name of the field that goes to validation;
			 * - field validation result;
			 * - validation result message: error text if error, nothing if it's OK.
			 */
			$field_check_result[$key]['id'] = $fields_array[$key]['id'];
			$field_check_result[$key]['result'] = $this->check_field_data( $fields_array[$key]['required'], $fields_array[$key]['value'], $fields_array[$key]['type'] );
			$field_check_result[$key]['message'] = !$field_check_result[$key]['result'] ? esc_html__( 'Данные отсутствуют или некорректы.', 'mebel-laim' ) : '';

			// If at least one field has error after validation.
			if ( !$field_check_result[$key]['result'] ) {
				$is_error = true;	// Set variable for error checking to true.
			}
		}

		// If at least one field has error.
		if ( $is_error ) {
			wp_send_json_error(
				[
					'array'		=> $field_check_result,
					'message' 	=> esc_html__( 'Ошибка введенных данных.', 'mebel-laim' )
				]
			);
		}

		/**
		 * If all form fields are valid - let's check e-mail address for sending message.
		 * If e-mail option from options.php file is empty -> send error about its emptiness to front-end, f.e. for console.
		 */
		if ( empty( $email_to ) ) {
			wp_send_json_error(
				[
					'message'	=> esc_html__( 'Почта для отправки отсутствует.', 'mebel-laim' )
				]
			);
		}	else {
			// E-mail format validation.
			$mail_validate = filter_var( $email_to, FILTER_VALIDATE_EMAIL );
			// If there are some errors -> send error message about it.
			if ( !$mail_validate ) {
				wp_send_json_error(
					[
						'message' 	=> esc_html__( 'Почта для отправки неверного формата.', 'mebel-laim' )
					]
				);
			}
			// If e-mail length is too big or small -> send error about it.
			if ( !$this->check_length( $email_to, 5, 30 ) ) {
				wp_send_json_error(
					[
						'message' 	=> esc_html__( 'Недопустимый размер почтового адреса для отправки.', 'mebel-laim' )
					]
				);
			}
		}

		/**
		 * If all form data is correct and e-mail for sending message is valid,
		 * let's make readable message (more or less ¯\_(ツ)_/¯ ).
		 * At first - empty all fields for future letter.
		 */
		$user_text = $user_name = $user_phone = $user_email = $user_message = '';
		foreach ( $fields_array as $field ) {
			switch ( $field['type'] ) {
				case 'text':
					$user_text .= $field['value'] . ' ';
					break;

				case 'name':
					$user_name .= $field['value'] . ' ';
					break;

				case 'phone':
					$user_phone .= $field['value'] . ' ';
					break;

				case 'email':
					$user_email .= $field['value'] . ' ';
					break;

				case 'message':
					$user_message .= $field['value'] . ' ';
					break;
			}
		}
		// Remove last symbols (spaces).
		$user_text = substr( $user_text, 0, -1 );
		$user_name = substr( $user_name, 0, -1 );
		$user_phone = substr( $user_phone, 0, -1 );
		$user_email = substr( $user_email, 0, -1 );
		$user_message = substr( $user_message, 0, -1 );

		$user_name = !empty( $user_name ) ? sprintf( '<b>Отправитель:</b> %s.', $user_name ) : '';
		$user_phone = !empty( $user_phone ) ? sprintf( '<b>Телефон отправителя:</b> %s.', $user_phone ) : '';
		$user_email = !empty( $user_email ) ? sprintf( '<b>E-mail отправителя:</b> %s.', $user_email ) : '';
		$user_text = !empty( $user_text ) ? sprintf( '<b>Содержание текстовых полей формы:</b> %s.', $user_text ) : '';
		$user_message = !empty( $user_message ) ? sprintf( '<b>Сообщение отправителя:</b> %s', $user_message ) : '';

		$message_to_send = $this->cwpmf_email_letter_structure( $user_name, $user_phone, $user_email, $user_text, $user_message );

		// Add website name to 'From' field in letter headers.
		add_filter( 'wp_mail_from_name', 'cwpmf_wp_mail_from_name' );
		function cwpmf_wp_mail_from_name( $email_from ){
			return htmlspecialchars_decode( get_bloginfo( 'name' ) );
		}
		// Add website no-reply e-mail to 'From' field in letter headers.
		add_filter( 'wp_mail_from', 'cwpmf_wp_mail_from' );
		function cwpmf_wp_mail_from( $email_address ){
			return 'no-reply@' . $_SERVER['HTTP_HOST'];
		}
		// Set letter content type to html.
		add_filter( 'wp_mail_content_type', 'cwpmf_set_html_content_type' );
		function cwpmf_set_html_content_type( $content_type ) {
			return 'text/html';
		}
		// Sending letter.
		$send = wp_mail( $email_to, esc_html__( 'Форма обратной связи', 'mebel-laim'), $message_to_send );
		// Remove content type filter to avoid conflicts.
		remove_filter( 'wp_mail_content_type', 'cwpmf_set_html_content_type' );

		if ( $send ) {	// If e-mail send is OK.
			wp_send_json_success(
				[
					'message' 	=> esc_html__( 'Спасибо за Ваше сообщение. Мы постараемся ответить Вам в кратчайшие сроки.', 'mebel-laim' )
				]
			);
		}	else {	// If e-mail send is not OK.
			wp_send_json_error(
				[
					'message'	=> esc_html__( 'К сожалению, при отправке сообщения произошла непредвиденная ошибка. Попробуйте повторить отправку позже.', 'mebel-laim' )
				]
			);
		}
    }
}