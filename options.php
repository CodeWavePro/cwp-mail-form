<?php if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'send_to'	=> array(
    	'type'	=> 'text',
    	'label'	=> __( 'Send E-mail To', 'mebel-laim' ),
	    'desc'	=> __( 'Please enter e-mail for receiving messages', 'mebel-laim' )
    ),

    'legend'	=> array(
    	'type'	=> 'text',
    	'label'	=> __( 'Form Title', 'mebel-laim' ),
	    'desc'	=> __( 'Please enter form title text', 'mebel-laim' )
    ),

    'firstname_placeholder'   => array(
        'type'  => 'text',
        'label' => __( 'First Name Placeholder', 'mebel-laim' ),
        'desc'  => __( 'Please enter first name field placeholder text', 'mebel-laim' )
    ),

    'phone_placeholder'   => array(
        'type'  => 'text',
        'label' => __( 'Phone Placeholder', 'mebel-laim' ),
        'desc'  => __( 'Please enter phone field placeholder text', 'mebel-laim' )
    ),

    'message_placeholder'   => array(
        'type'  => 'text',
        'label' => __( 'Message Placeholder', 'mebel-laim' ),
        'desc'  => __( 'Please enter message field placeholder text', 'mebel-laim' )
    ),

    'button_text'	=> array(
    	'type'	=> 'text',
    	'label'	=> __( 'Send Button Text', 'mebel-laim' ),
	    'desc'	=> __( 'Please enter send button text', 'mebel-laim' )
    )
);