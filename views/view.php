<?php
if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

// Unyson Icons v2 option type frontend enqueue.
fw()->backend->option_type('icon-v2')->packs_loader->enqueue_frontend_css();
// Options from options.php file.
$email_to = ( isset( $atts['send_to'] ) && $atts['send_to'] ) ? $atts['send_to'] : '';
$legend = ( isset( $atts['legend'] ) && $atts['legend'] ) ? $atts['legend'] : '';
$button_text = ( isset( $atts['button_text'] ) && $atts['button_text'] ) ? $atts['button_text'] : 'Отправить';
$popup_button_text = ( isset( $atts['popup_button_text'] ) && $atts['popup_button_text'] ) ? $atts['popup_button_text'] : 'OK';

// Check is firstname field set to 'Show' or 'Hide' in options.php.
switch ( $atts['is_firstname_field']['firstname_choice'] ) {
	case 'firstname_true':
		$firstname_placeholder = ( isset( $atts['is_firstname_field']['firstname_true']['firstname_placeholder'] ) &&
								   $atts['is_firstname_field']['firstname_true']['firstname_placeholder'] ) ?
								   $atts['is_firstname_field']['firstname_true']['firstname_placeholder'] :
								   'Имя';
		$firstname_icon = ( isset( $atts['is_firstname_field']['firstname_true']['firstname_icon'] ) &&
								   $atts['is_firstname_field']['firstname_true']['firstname_icon'] ) ?
								   $atts['is_firstname_field']['firstname_true']['firstname_icon']['icon-class'] :
								   'fas fa-question';
		break;

	case 'firstname_false':
		$firstname_placeholder = '';
		break;
}

// Check is phone field set to 'Show' or 'Hide' in options.php.
switch ( $atts['is_phone_field']['phone_choice'] ) {
	case 'phone_true':
		$phone_placeholder = ( isset( $atts['is_phone_field']['phone_true']['phone_placeholder'] ) &&
							   $atts['is_phone_field']['phone_true']['phone_placeholder'] ) ?
							   $atts['is_phone_field']['phone_true']['phone_placeholder'] :
							   'Телефон';
		$phone_icon = ( isset( $atts['is_phone_field']['phone_true']['phone_icon'] ) &&
								   $atts['is_phone_field']['phone_true']['phone_icon'] ) ?
								   $atts['is_phone_field']['phone_true']['phone_icon']['icon-class'] :
								   'fas fa-question';
		break;

	case 'phone_false':
		$phone_placeholder = '';
		break;
}

// Check is e-mail field set to 'Show' or 'Hide' in options.php.
switch ( $atts['is_email_field']['email_choice'] ) {
	case 'email_true':
		$email_placeholder = ( isset( $atts['is_email_field']['email_true']['email_placeholder'] ) &&
							   $atts['is_email_field']['email_true']['email_placeholder'] ) ?
							   $atts['is_email_field']['email_true']['email_placeholder'] :
							   'Почта';
		$email_icon = ( isset( $atts['is_email_field']['email_true']['email_icon'] ) &&
								   $atts['is_email_field']['email_true']['email_icon'] ) ?
								   $atts['is_email_field']['email_true']['email_icon']['icon-class'] :
								   'fas fa-question';
		break;

	case 'phone_false':
		$email_placeholder = '';
		break;
}

// Check is message field set to 'Show' or 'Hide' in options.php.
switch ( $atts['is_message_field']['message_choice'] ) {
	case 'message_true':
		$message_placeholder = ( isset( $atts['is_message_field']['message_true']['message_placeholder'] ) &&
							   $atts['is_message_field']['message_true']['message_placeholder'] ) ?
							   $atts['is_message_field']['message_true']['message_placeholder'] :
							   'Сообщение';
		$message_icon = ( isset( $atts['is_message_field']['message_true']['message_icon'] ) &&
								   $atts['is_message_field']['message_true']['message_icon'] ) ?
								   $atts['is_message_field']['message_true']['message_icon']['icon-class'] :
								   'fas fa-question';
		break;

	case 'message_false':
		$message_placeholder = '';
		break;
}
?>

<!-- Form to send e-mail. -->
<form id = "cwpmf" class = "cwpmf" method = "POST" data-to = "<?php echo esc_attr( $email_to ) ?>">
	<!-- All form fields. -->
	<fieldset class = "cwpmf-fieldset">
		<!-- Form title text. -->
		<legend class = "cwpmf-legend">
			<?php printf( esc_html__( '%s', 'mebel-laim' ), $legend ) ?>
		</legend>

		<!-- If firstname field is set to 'Show', so placeholder is not empty. -->
		<?php if ( !empty( $firstname_placeholder ) ) : ?>
			<label class = "cwpmf-label" for = "cwpmf-input-firstname">
				<input id = "cwpmf-input-firstname" class = "cwpmf-input cwpmf-input-firstname" type = "text" name = "cwpmf-input-firstname" placeholder = "<?php printf( esc_attr__( '%s', 'mebel-laim' ), $firstname_placeholder ) ?>" />
				<!-- Icon for this field. -->
				<i class = "<?php echo esc_attr( $firstname_icon ) ?> cwpmf-icon"></i>

				<!-- Hidden field to show error if it occured. -->
				<span class = "cwpmf-input-error cwpmf-input__firstname">
					<span class = "cwpmf-input-error-msg"></span>
				</span>
			</label>
		<?php endif ?>

		<!-- If phone field is set to 'Show', so placeholder is not empty. -->
		<?php if ( !empty( $phone_placeholder ) ) : ?>
			<label class = "cwpmf-label" for = "cwpmf-input-phone">
				<input id = "cwpmf-input-phone" class = "cwpmf-input cwpmf-input-phone" type = "text" name = "cwpmf-input-phone" placeholder = "<?php printf( esc_attr__( '%s', 'mebel-laim' ), $phone_placeholder ) ?>" />
				<!-- Icon for this field. -->
				<i class = "<?php echo esc_attr( $phone_icon ) ?> cwpmf-icon"></i>

				<!-- Hidden field to show error if it occured. -->
				<span class = "cwpmf-input-error cwpmf-input__phone">
					<span class = "cwpmf-input-error-msg"></span>
				</span>
			</label>
		<?php endif ?>

		<!-- If e-mail field is set to 'Show', so placeholder is not empty. -->
		<?php if ( !empty( $email_placeholder ) ) : ?>
			<label class = "cwpmf-label" for = "cwpmf-input-email">
				<input id = "cwpmf-input-email" class = "cwpmf-input cwpmf-input-email" type = "text" name = "cwpmf-input-email" placeholder = "<?php printf( esc_attr__( '%s', 'mebel-laim' ), $email_placeholder ) ?>" />
				<!-- Icon for this field. -->
				<i class = "<?php echo esc_attr( $email_icon ) ?> cwpmf-icon"></i>

				<!-- Hidden field to show error if it occured. -->
				<span class = "cwpmf-input-error cwpmf-input__email">
					<span class = "cwpmf-input-error-msg"></span>
				</span>
			</label>
		<?php endif ?>

		<!-- If message field is set to 'Show', so placeholder is not empty. -->
		<?php if ( !empty( $message_placeholder ) ) : ?>
			<label class = "cwpmf-label" for = "cwpmf-input-message">
				<textarea id = "cwpmf-input-message" class = "cwpmf-textarea cwpmf-input-message" name = "cwpmf-input-message" placeholder = "<?php printf( esc_attr__( '%s', 'mebel-laim' ), $message_placeholder ) ?>"></textarea>
				<!-- Icon for this field. -->
				<i class = "<?php echo esc_attr( $message_icon ) ?> cwpmf-icon"></i>

				<!-- Hidden field to show error if it occured. -->
				<span class = "cwpmf-input-error cwpmf-input__message">
					<span class = "cwpmf-input-error-msg"></span>
				</span>
			</label>
		<?php endif ?>

		<!-- Button to submit form. -->
		<button class = "button cwpmf-button" title = "<?php printf( esc_attr__( '%s', 'mebel-laim' ), $button_text ) ?>" form = "cwpmf" type = "submit">
			<?php printf( esc_html__( '%s', 'mebel-laim' ), $button_text ) ?>
		</button>
	</fieldset>
</form>

<!-- Hidden popup for displaying success|error messages after sending e-mail. -->
<div class = "cwpmf-popup animated">
	<!-- Popup inner for message and button. -->
	<div class = "cwpmf-popup-inner animated">
		<!-- Close popup button. -->
		<a href = "#" class = "cwpmf-popup__close" title = "<?php esc_attr_e( 'Закрыть окно', 'mebel-laim' ) ?>">
			<span class = "cwpmf-popup__line"></span>
			<span class = "cwpmf-popup__line cwpmf-popup__line_cross"></span>
		</a>
		<!-- Popup message wrapper. Text will be sent from scripts.js. -->
		<div class = "cwpmf-popup-message"></div>
		<!-- Popup button for better UI, I think. -->
		<div class = "cwpmf-popup-button">
			<a class = "button cwpmf-popup-button__link" href = "#">
				<?php printf( esc_html__( '%s', 'mebel-laim' ), $popup_button_text ) ?>
			</a>
		</div>
	</div>
</div>