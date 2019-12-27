<?php
if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

// E-mail address to send letter.
$email_to = ( isset( $atts['send_to'] ) && $atts['send_to'] ) ? $atts['send_to'] : '';
// Form title.
$legend = ( isset( $atts['legend'] ) && $atts['legend'] ) ? $atts['legend'] : '';
// Form send button text.
$button_text = ( isset( $atts['button_text'] ) && $atts['button_text'] ) ? $atts['button_text'] : 'Отправить';
// Success popup button text.
$popup_button_text = ( isset( $atts['popup_button_text'] ) && $atts['popup_button_text'] ) ? $atts['popup_button_text'] : 'OK';

// All form fields array.
if ( isset( $atts['fields'] ) && $atts['fields'] ) {
	$text_field_count = $name_field_count = $phone_field_count = $email_field_count = $textarea_field_count = 1;

	foreach ( $atts['fields'] as $key => $field ) {
		$fields_array[$key]['is_required'] = ( isset( $field['field_is_required'] ) && $field['field_is_required'] ) ? 'true' : 'false';
		$fields_array[$key]['placeholder'] = ( isset( $field['field_placeholder'] ) && $field['field_placeholder'] ) ? $field['field_placeholder'] : '';
		$fields_array[$key]['placeholder'] .= $fields_array[$key]['is_required'] ? ' *' : '';
		$fields_array[$key]['icon'] = ( isset( $field['field_icon'] ) && $field['field_icon'] ) ? '<i class = "' . $field['field_icon']['icon-class'] . ' cwpmf-icon"></i>' : '';

		switch ( $field['field_type'] ) {
			case 'choice-text':
				$fields_array[$key]['structure'] = '
					<input type = "text"
						   id = "cwpmf-input-text-' . esc_attr( $text_field_count ) . '"
						   class = "cwpmf-input cwpmf-input-text"
						   name = "cwpmf-input-text-' . esc_attr( $text_field_count ) . '"
						   placeholder = "' . esc_attr( $fields_array[$key]['placeholder'] ) . '"
						   data-required = "' . esc_attr( $fields_array[$key]['is_required'] ) . '" />
					' . $fields_array[$key]['icon'] . '
					<span class = "cwpmf-input-error cwpmf-input__text">
						<span class = "cwpmf-input-error-msg"></span>
					</span>';
					$text_field_count++;
				break;

			case 'choice-name':
				$fields_array[$key]['structure'] = '
					<input type = "text"
						   id = "cwpmf-input-name-' . esc_attr( $name_field_count ) . '"
						   class = "cwpmf-input cwpmf-input-name"
						   name = "cwpmf-input-text-' . esc_attr( $name_field_count ) . '"
						   placeholder = "' . esc_attr( $fields_array[$key]['placeholder'] ) . '"
						   data-required = "' . esc_attr( $fields_array[$key]['is_required'] ) . '" />
					' . $fields_array[$key]['icon'] . '
					<span class = "cwpmf-input-error cwpmf-input__text">
						<span class = "cwpmf-input-error-msg"></span>
					</span>';
					$text_field_count++;
				break;

			case 'choice-phone':
				$fields_array[$key]['structure'] = '
					<input type = "text"
						   id = "cwpmf-input-phone-' . esc_attr( $phone_field_count ) . '"
						   class = "cwpmf-input cwpmf-input-phone"
						   name = "cwpmf-input-phone-' . esc_attr( $phone_field_count ) . '"
						   placeholder = "' . $fields_array[$key]['placeholder'] . '"
						   data-required = "' . $fields_array[$key]['is_required'] . '" />
					' . $fields_array[$key]['icon'] . '
					<span class = "cwpmf-input-error cwpmf-input__phone">
						<span class = "cwpmf-input-error-msg"></span>
					</span>';
					$phone_field_count++;
				break;

			case 'choice-email':
				$fields_array[$key]['structure'] = '
					<input type = "text"
						   id = "cwpmf-input-email-' . esc_attr( $email_field_count ) . '"
						   class = "cwpmf-input cwpmf-input-email"
						   name = "cwpmf-input-email-' . esc_attr( $email_field_count ) . '"
						   placeholder = "' . $fields_array[$key]['placeholder'] . '"
						   data-required = "' . $fields_array[$key]['is_required'] . '" />
					' . $fields_array[$key]['icon'] . '
					<span class = "cwpmf-input-error cwpmf-input__email">
						<span class = "cwpmf-input-error-msg"></span>
					</span>';
					$email_field_count++;
				break;

			case 'choice-message':
				$fields_array[$key]['structure'] = '
					<textarea id = "cwpmf-input-message-' . esc_attr( $textarea_field_count ) . '"
							  class = "cwpmf-textarea cwpmf-input-message"
						      name = "cwpmf-input-message-' . esc_attr( $textarea_field_count ) . '"
						      placeholder = "' . $fields_array[$key]['placeholder'] . '"
						      data-required = "' . $fields_array[$key]['is_required'] . '"></textarea>
					' . $fields_array[$key]['icon'] . '
					<span class = "cwpmf-input-error cwpmf-input__message">
						<span class = "cwpmf-input-error-msg"></span>
					</span>';
					$textarea_field_count++;
				break;
		}
	}
}
?>

<!-- Form to send e-mail. -->
<form id = "cwpmf" class = "cwpmf" method = "POST" data-to = "<?php echo esc_attr( $email_to ) ?>" data-letter = "<?php echo esc_attr( $letter_settings ) ?>">
	<!-- All form fields. -->
	<fieldset class = "cwpmf-fieldset">
		<!-- Form title text. -->
		<legend class = "cwpmf-legend">
			<?php printf( esc_html__( '%s', 'mebel-laim' ), $legend ) ?>
		</legend>
		<!-- All form fields outputing. -->
		<?php foreach ( $fields_array as $field ) { ?>
			<label class = "cwpmf-label">
				<?php echo $field['structure'] ?>
			</label>
		<?php } ?>

		<!-- Nonce field to verify AJAX response at server side. -->
		<?php wp_nonce_field( '_cwpmf_send_email_nonce', 'cwpmf_nonce_field' ) ?>

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