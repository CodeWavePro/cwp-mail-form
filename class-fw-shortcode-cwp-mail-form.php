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
	 * Function checks name symbols
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
		$name_arr = preg_split('//u', $name, -1, PREG_SPLIT_NO_EMPTY);

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
	 * Function checks if value length is between min and max parameters
	 */
	private function check_length( $value, $min, $max ) {
	    $result = ( mb_strlen( $value ) < $min || mb_strlen( $value ) > $max );
	    return !$result;
	}

	/**
	 * Function checks phone symbols
	 */
	private function check_phone( $phone ) {
		$phone_check = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '+', '(', ')'];
		$phone_arr = str_split( $phone );

		for ( $i = 0; $i < count( $phone_arr ); $i++ ) {

			$break_flag = 1;

			for ( $j = 0; $j < count( $phone_check ); $j++ ) {
				if ( $phone_arr[$i] == $phone_check[$j] ) {
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
     * Register Ajax functions.
     */
    private function register_ajax() {
    	// Show more product info.
        add_action( 'wp_ajax__cwpmf_send_email', array( $this, '_cwpmf_send_email' ) );
		add_action( 'wp_ajax_nopriv__cwpmf_send_email', array( $this, '_cwpmf_send_email' ) );
    }

    /**
	 * Send User e-mail to website Administrator.
	 */
    public function _cwpmf_send_email() {
		parse_str( $_POST['serial'], $serialize_arr );
		$firstname = $this->clean_value( $serialize_arr['cwpmf-input-firstname'] );
		$phone = $this->clean_value( $serialize_arr['cwpmf-input-phone'] );
		$message = $this->clean_value( $serialize_arr['cwpmf-input-message'] );
		$email_to = $this->clean_value( $_POST['email_to'] );

		/**
		 * Fields for writing error information for user.
		 * First @param set to true means all is OK.
		 * First @param set to false means that some error occurred.
		 * Second @param is text of error, that will be displayed to user on the bottom of the field.
		 */
		$firstname_valid = array( true, '' );
		$phone_valid = array( true, '' );
		$message_valid = array( true, '' );

		/**
		 * If some of required fields are empty,
		 * writing errors to these fields
		 */
		if ( empty( $firstname ) || empty( $phone ) || empty( $message ) ) {
			if ( empty( $firstname ) ) {
				$firstname_valid[0] = false;
				$firstname_valid[1] = esc_html__( 'Пожалуйста, введите имя.', 'mebel-laim' );
			}

			if ( empty( $phone ) ) {
				$phone_valid[0] = false;
				$phone_valid[1] = esc_html__( 'Пожалуйста, введите телефон.', 'mebel-laim' );
			}

			if ( empty( $message ) ) {
				$message_valid[0] = false;
				$message_valid[1] = esc_html__( 'Пожалуйста, введите сообщение.', 'mebel-laim' );
			}

			wp_send_json_error(
				array(
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'Ошибка введенных данных.', 'mebel-laim' )
				)
			);
		}

		// If e-mail option from options.php file is empty.
		if ( empty( $email_to ) ) {
			// Send error about its emptiness.
			wp_send_json_error(
				array(
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'Почта для отправки отсутствует.', 'mebel-laim' )
				)
			);
		}	else {
			// E-mail format validation.
			$mail_validate = filter_var( $email_to, FILTER_VALIDATE_EMAIL );
			// If there are some errors.
			if ( !$mail_validate ) {
				// Send error about it.
				wp_send_json_error(
					array(
						'firstname' 	=> $firstname_valid,
						'phone' 		=> $phone_valid,
						'textarea'		=> $message_valid,
						'message' 		=> esc_html__( 'Почта для отправки неверного формата.', 'mebel-laim' )
					)
				);
			}
			// If e-mail length is too big or small.
			if ( !$this->check_length( $email_to, 5, 30 ) ) {
				// Send error about it.
				wp_send_json_error(
					array(
						'firstname' 	=> $firstname_valid,
						'phone' 		=> $phone_valid,
						'textarea'		=> $message_valid,
						'message' 		=> esc_html__( 'Недопустимый размер почтового адреса для отправки.', 'mebel-laim' )
					)
				);
			}
		}

		if ( !$this->check_name( $firstname ) ) {
			$firstname_valid[0] = false;
			$firstname_valid[1] = esc_html__( 'Недопустимые символы в имени.', 'mebel-laim' );
		}

		if ( !$this->check_length( $firstname, 1, 30 ) ) {
			$firstname_valid[0] = false;
			$firstname_valid[1] = esc_html__( 'Недопустимый размер имени.', 'mebel-laim' );
		}

		if ( !$this->check_phone( $phone ) ) {
			$phone_valid[0] = false;
			$phone_valid[1] = esc_html__( 'Неверный формат телефона.', 'mebel-laim' );
		}

		if ( !$this->check_length( $phone, 4, 30 ) ) {
			$phone_valid[0] = false;
			$phone_valid[1] = esc_html__( 'Недопустимый размер телефона.', 'mebel-laim' );
		}

		if ( !$this->check_length( $message, 10, 300 ) ) {
			$message_valid[0] = false;
			$message_valid[1] = esc_html__( 'Недопустимый размер сообщения.', 'mebel-laim' );
		}

		if ( !$firstname_valid[0] || !$phone_valid[0] || !$message_valid[0] ) {
			wp_send_json_error(
				array(
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'Ошибка введенных данных.', 'mebel-laim' )
				)
			);
		}

		// E-mail message text:
		$message_to_send = __( 'Здравствуйте!', 'mebel-laim' ) . "\n";
		$message_to_send .= __( 'Вам прислали сообщение с формы обратной связи.', 'mebel-laim' ) . "\n\n";
		$message_to_send .= __( 'Отправитель: ', 'mebel-laim' ) . $firstname . ".\n";
		$message_to_send .= __( 'Телефон отправителя: ', 'mebel-laim' ) . $phone . ".\n";
		$message_to_send .= __( 'Сообщение: ', 'mebel-laim' ) . $message;

		$headers = "From: \"" . get_bloginfo( 'name' ) . "\"<no-reply>\r\nContent-type: text/plain; charset=utf-8 \r\n";
		$send = mail( $email_to, __( 'Форма обратной связи', 'mebel-laim'), $message_to_send, $headers );

		$firstname_valid = array( true, '' );
		$phone_valid = array( true, '' );
		$message_valid = array( true, '' );

		if ( $send ) {	// If all is OK :)
			wp_send_json_success(
				array(
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'textarea'		=> $message_valid,
					'message'	 	=> esc_html__( 'Спасибо за Ваше сообщение. Мы постараемся ответить Вам в кратчайшие сроки.', 'mebel-laim' )
				)
			);
		}	else {	// If is not OK :(
			wp_send_json_error(
				array(
					'firstname' 	=> $firstname_valid,
					'phone' 		=> $phone_valid,
					'textarea'		=> $message_valid,
					'message' 		=> esc_html__( 'К сожалению, при отправке сообщения произошла непредвиденная ошибка. Попробуйте повторить отправку позже.', 'mebel-laim' )
				)
			);
		}
    }
}