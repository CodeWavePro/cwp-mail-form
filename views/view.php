<?php
if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

// Options from options.php file.
$email_to = ( isset( $atts['send_to'] ) && $atts['send_to'] ) ? $atts['send_to'] : '';
$legend = ( isset( $atts['legend'] ) && $atts['legend'] ) ? $atts['legend'] : '';
$firstname_placeholder = ( isset( $atts['firstname_placeholder'] ) && $atts['firstname_placeholder'] ) ? $atts['firstname_placeholder'] : '';
$phone_placeholder = ( isset( $atts['phone_placeholder'] ) && $atts['phone_placeholder'] ) ? $atts['phone_placeholder'] : '';
$message_placeholder = ( isset( $atts['message_placeholder'] ) && $atts['message_placeholder'] ) ? $atts['message_placeholder'] : '';
$button_text = ( isset( $atts['button_text'] ) && $atts['button_text'] ) ? $atts['button_text'] : 'Отправить';
?>

<!-- Form to send e-mail. -->
<form id = "cwpmf" class = "cwpmf" method = "POST">
	<!-- All form fields. -->
	<fieldset class = "cwpmf-fieldset">
		<!-- Form title text. -->
		<legend class = "cwpmf-legend"><?php esc_html_e( $legend, 'mebel-laim' ) ?></legend>

		<!-- Label for first name field. -->
		<label class = "cwpmf-label" for = "cwpmf-input-firstname">
			<input id = "cwpmf-input-firstname" class = "cwpmf-input" type = "text" name = "cwpmf-input-firstname" placeholder = "<?php esc_attr_e( 'Имя *' ) ?>" />
			<!-- Hidden field to show error if it occured. -->
			<span class = "cwpmf-input-error cwpmf-input__firstname">
				<span class = "cwpmf-input-error-msg"></span>
			</span>
		</label>

		<!-- Label for phone number. -->
		<label class = "cwpmf-label" for = "cwpmf-input-phone">
			<input id = "cwpmf-input-phone" class = "cwpmf-input" type = "text" name = "cwpmf-input-phone" placeholder = "<?php esc_attr_e( 'Телефон *' ) ?>" />
			<!-- Hidden field to show error if it occured. -->
			<span class = "cwpmf-input-error cwpmf-input__phone">
				<span class = "cwpmf-input-error-msg"></span>
			</span>
		</label>

		<!-- Label for user message. -->
		<label class = "cwpmf-label" for = "cwpmf-input-message">
			<textarea id = "cwpmf-input-message" class = "textarea" name = "cwpmf-input-message" placeholder = "<?php esc_attr_e( 'Сообщение *' ) ?>"></textarea>
			<!-- Hidden field to show error if it occured. -->
			<span class = "cwpmf-input-error cwpmf-input__message">
				<span class = "cwpmf-input-error-msg"></span>
			</span>
		</label>

		<!-- Button to submit form. -->
		<button class = "cwpmf-button" title = "<?php esc_attr_e( $button_text ) ?>" form = "cwpmf" type = "submit">
			<?php esc_html_e( $button_text, 'mebel-laim' ) ?>
		</button>
	</fieldset>
</form>