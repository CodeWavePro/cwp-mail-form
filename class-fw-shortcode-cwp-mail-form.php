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
								'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я',
								'Є', 'є', 'І', 'і', 'Ї', 'ї',
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

			if ( $break_flag == 1 ) {
				return false;
				break;
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
		parse_str( $_POST['serial'], $serialize_arr );
		$user_firstname = $this->clean_value( $serialize_arr['cwpmf-input-firstname'] );
		$user_phone = $this->clean_value( $serialize_arr['cwpmf-input-phone'] );
		$user_email = $this->clean_value( $serialize_arr['cwpmf-input-email'] );
		$user_message = $this->clean_value( $serialize_arr['cwpmf-input-message'] );
		$email_to = $this->clean_value( $_POST['email_to'] );

		/**
		 * Check if field exists in form.
		 * True - field exists, false - field does not exists.
		 */
		$is_firstname = $this->clean_value( $_POST['is_firstname'] );
		$is_phone = $this->clean_value( $_POST['is_phone'] );
		$is_email = $this->clean_value( $_POST['is_email'] );
		$is_message = $this->clean_value( $_POST['is_message'] );

		/**
		 * Check if this fields are empty.
		 */
		if ( empty( $is_firstname ) ||
			 empty( $is_phone ) ||
			 empty( $is_email ) ||
			 empty( $is_message ) ) {
			wp_send_json_error(
				[
					'firstname' 	=> true,
					'phone' 		=> true,
					'email' 		=> true,
					'textarea'		=> true,
					'message' 		=> esc_html__( 'Переданы некорректные данные о наличии полей формы.', 'mebel-laim' )
				]
			);
		}

		/**
		 * Check if field is required.
		 * True - field required, false - not required.
		 */
		$is_firstname_required = $this->clean_value( $_POST['is_firstname_required'] );
		$is_phone_required = $this->clean_value( $_POST['is_phone_required'] );
		$is_email_required = $this->clean_value( $_POST['is_email_required'] );
		$is_message_required = $this->clean_value( $_POST['is_message_required'] );

		/**
		 * Check if this fields are empty.
		 */
		if ( empty( $is_firstname_required ) ||
			 empty( $is_phone_required ) ||
			 empty( $is_email_required ) ||
			 empty( $is_message_required ) ) {
			wp_send_json_error(
				[
					'firstname' 	=> true,
					'phone' 		=> true,
					'email' 		=> true,
					'textarea'		=> true,
					'message' 		=> esc_html__( 'Переданы некорректные данные об обязательных полях формы.', 'mebel-laim' )
				]
			);
		}

		/**
		 * Fields for writing error information for user.
		 * First @param set to true means all is OK.
		 * First @param set to false means that some error occurred.
		 * Second @param is text of error, that will be displayed to user on the bottom of the field.
		 */
		$firstname_valid = [true, ''];
		$phone_valid = [true, ''];
		$email_valid = [true, ''];
		$message_valid = [true, ''];

		// Check if firstname field exists in form.
		if ( $is_firstname === 'true' ) {
			/**
			 * So, firstname exists in form.
			 * If it is required but empty - write error.
			 */
			if ( ( $is_firstname_required === 'true' ) && empty( $user_firstname ) ) {
				$firstname_valid[0] = false;
				$firstname_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
			/**
			 * So, firstname exists in form, even if not required.
			 * If it is not empty - check the data.
			 * If it has not allowed symbols - write error.
			 * If it is too long - write error.
			 */
			if ( !empty( $user_firstname ) &&
				 !$this->check_name( $user_firstname ) ||
			 	 !$this->check_length( $user_firstname, 0, 30 ) ) {
				$firstname_valid[0] = false;
				$firstname_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
		}
		$user_firstname = ( $is_firstname_required === 'true' ) ? $user_firstname : esc_html__( 'данное поле отсутствует в форме', 'mebel-laim' );

		// Check if phone field exists in form.
		if ( $is_phone === 'true' ) {
			/**
			 * So, phone exists in form.
			 * If it is required but empty - write error.
			 */
			if ( ( $is_phone_required === 'true' ) && empty( $user_phone ) ) {
				$phone_valid[0] = false;
				$phone_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
			/**
			 * So, phone exists in form, even if not required.
			 * If it is not empty - check the data.
			 * If it has not allowed symbols - write error.
			 * If it is too long - write error.
			 */
			if ( !empty( $user_phone ) &&
				 !$this->check_phone( $user_phone ) ||
			 	 !$this->check_length( $user_phone, 0, 30 ) ) {
				$phone_valid[0] = false;
				$phone_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
		}
		$user_phone = ( $is_phone_required === 'true' ) ? $user_phone : esc_html__( 'данное поле отсутствует в форме', 'mebel-laim' );

		// Check if e-mail field exists in form.
		if ( $is_email === 'true' ) {
			/**
			 * So, e-mail exists in form.
			 * If it is required but empty - write error.
			 */
			if ( ( $is_email_required === 'true' ) && empty( $user_email ) ) {
				$email_valid[0] = false;
				$email_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
			/**
			 * So, e-mail exists in form, even if not required.
			 * If it is not empty - check the data.
			 * If it has not allowed symbols - write error.
			 * If it is too long - write error.
			 */
			if ( !empty( $user_email ) &&
				 !filter_var( $user_email, FILTER_VALIDATE_EMAIL ) ||
			 	 !$this->check_length( $user_email, 0, 30 ) ) {
				$email_valid[0] = false;
				$email_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
		}
		$user_email = ( $is_email_required === 'true' ) ? $user_email : esc_html__( 'данное поле отсутствует в форме', 'mebel-laim' );

		// Check if message field exists in form.
		if ( $is_message === 'true' ) {
			/**
			 * So, message exists in form.
			 * If it is required but empty - write error.
			 */
			if ( ( $is_message_required === 'true' ) && empty( $user_message ) ) {
				$message_valid[0] = false;
				$message_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
			/**
			 * So, message exists in form, even if not required.
			 * If it is not empty - check the data.
			 * If it is too long - write error.
			 */
			if ( !empty( $user_message ) &&
				 !$this->check_length( $user_message, 0, 500 ) ) {
				$message_valid[0] = false;
				$message_valid[1] = esc_html__( 'Данные отсутствуют или недопустимы.', 'mebel-laim' );
			}
		}
		$user_message = ( $is_message_required === 'true' ) ? $user_message : esc_html__( 'данное поле отсутствует в форме', 'mebel-laim' );

		/**
		 * If some of existing fields has errors
		 * send this errors to front-end.
		 */
		if ( !$firstname_valid[0] ||
			 !$phone_valid[0] ||
			 !$email_valid[0] ||
			 !$message_valid[0] ) {
			wp_send_json_error(
				[
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'email'			=> $email_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'Ошибка введенных данных.', 'mebel-laim' )
				]
			);
		}

		/**
		 * If e-mail option from options.php file is empty
		 * send error about its emptiness to front-end, f.e. console.
		 */
		if ( empty( $email_to ) ) {
			wp_send_json_error(
				[
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'email'			=> $email_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'Почта для отправки отсутствует.', 'mebel-laim' )
				]
			);
		}	else {
			// E-mail format validation.
			$mail_validate = filter_var( $email_to, FILTER_VALIDATE_EMAIL );
			// If there are some errors.
			if ( !$mail_validate ) {
				// Send error about it.
				wp_send_json_error(
					[
						'firstname' 	=> $firstname_valid,
						'phone' 		=> $phone_valid,
						'email'			=> $email_valid,
						'textarea'		=> $message_valid,
						'message' 		=> esc_html__( 'Почта для отправки неверного формата.', 'mebel-laim' )
					]
				);
			}
			// If e-mail length is too big or small.
			if ( !$this->check_length( $email_to, 5, 30 ) ) {
				// Send error about it.
				wp_send_json_error(
					[
						'firstname' 	=> $firstname_valid,
						'phone' 		=> $phone_valid,
						'email'			=> $email_valid,
						'textarea'		=> $message_valid,
						'message' 		=> esc_html__( 'Недопустимый размер почтового адреса для отправки.', 'mebel-laim' )
					]
				);
			}
		}

		// E-mail message text:
		$message_to_send = __( 'Здравствуйте!', 'mebel-laim' ) . "\n";
		$message_to_send .= __( 'Вам прислали сообщение с формы обратной связи.', 'mebel-laim' ) . "\n\n";
		$message_to_send .= __( 'Отправитель: ', 'mebel-laim' ) . $user_firstname . ".\n";
		$message_to_send .= __( 'Телефон отправителя: ', 'mebel-laim' ) . $user_phone . ".\n";
		$message_to_send .= __( 'E-mail отправителя: ', 'mebel-laim' ) . $user_email . ".\n";
		$message_to_send .= __( 'Сообщение: ', 'mebel-laim' ) . $user_message;

		$headers = "From: \"" . get_bloginfo( 'name' ) . "\"<no-reply>\r\nContent-type: text/plain; charset=utf-8 \r\n";
		$send = mail( $email_to, __( 'Форма обратной связи', 'mebel-laim'), $message_to_send, $headers );

		$firstname_valid = [true, ''];
		$phone_valid = [true, ''];
		$email_valid = [true, ''];
		$message_valid = [true, ''];

		if ( $send ) {	// If e-mail send is OK.
			wp_send_json_success(
				[
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'email'			=> $email_valid,
					'textarea'		=> $message_valid,
					'message'	 	=> esc_html__( 'Спасибо за Ваше сообщение. Мы постараемся ответить Вам в кратчайшие сроки.', 'mebel-laim' )
				]
			);
		}	else {	// If e-mail send is not OK.
			wp_send_json_error(
				[
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'email'			=> $email_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'К сожалению, при отправке сообщения произошла непредвиденная ошибка. Попробуйте повторить отправку позже.', 'mebel-laim' )
				]
			);
		}
    }
}