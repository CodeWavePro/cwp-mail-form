<?php if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = [
	'send_to'	=> [
    	'type'	=> 'text',
    	'label'	=> esc_html__( 'Send E-mail To', 'mebel-laim' ),
	    'desc'	=> esc_html__( 'Please enter e-mail for receiving messages', 'mebel-laim' )
    ],

    'legend'	=> [
    	'type'	=> 'text',
    	'label'	=> esc_html__( 'Form Title', 'mebel-laim' ),
	    'desc'	=> esc_html__( 'Please enter form title text', 'mebel-laim' )
    ],

    'is_firstname_field'   => [
        'type'  => 'multi-picker',
        'label' => false,
        'desc'  => false,
        'value' => [
            'firstname_choice'  => 'firstname_true'
        ],

        'picker'    => [
            'firstname_choice'  => [
                'type'      => 'select',
                'label'     => esc_html__( 'Firstname Field', 'mebel-laim' ),
                'desc'      => esc_html__( 'Please choose if form will have firstname field', 'mebel-laim' ),
                'choices'   => [
                    'firstname_true'    => esc_html__( 'Show Firstname Field', 'mebel-laim' ),
                    'firstname_false'   => esc_html__( 'Hide Firstname Field', 'mebel-laim')
                ]
            ]
        ],

        'choices'   => [
            'firstname_true'    => [
                'firstname_placeholder'   => [
                    'type'  => 'text',
                    'label' => esc_html__( 'First Name Placeholder', 'mebel-laim' ),
                    'desc'  => esc_html__( 'Please enter first name field placeholder text', 'mebel-laim' )
                ],

                'firstname_icon'  => [
                    'type'          => 'icon-v2',
                    'label'         => esc_html__( 'First Name Icon', 'mebel-laim' ),
                    'desc'          => esc_html__( 'Please choose first name field icon', 'mebel-laim' ),
                    'preview_size'  => 'medium',
                    'modal_size'    => 'medium'
                ]
            ]
        ],
        'show_borders'  => false
    ],

    'is_phone_field'   => [
        'type'  => 'multi-picker',
        'label' => false,
        'desc'  => false,
        'value' => [
            'phone_choice'  => 'phone_true'
        ],

        'picker'    => [
            'phone_choice'  => [
                'type'      => 'select',
                'label'     => esc_html__( 'Phone Field', 'mebel-laim' ),
                'desc'      => esc_html__( 'Please choose if form will have phone field', 'mebel-laim' ),
                'choices'   => [
                    'phone_true'    => esc_html__( 'Show Phone Field', 'mebel-laim' ),
                    'phone_false'   => esc_html__( 'Hide Phone Field', 'mebel-laim')
                ]
            ]
        ],

        'choices'   => [
            'phone_true'    => [
                'phone_placeholder'   => [
                    'type'  => 'text',
                    'label' => esc_html__( 'Phone Placeholder', 'mebel-laim' ),
                    'desc'  => esc_html__( 'Please enter phone field placeholder text', 'mebel-laim' )
                ],

                'phone_icon'  => [
                    'type'          => 'icon-v2',
                    'label'         => esc_html__( 'Phone Icon', 'mebel-laim' ),
                    'desc'          => esc_html__( 'Please choose phone field icon', 'mebel-laim' ),
                    'preview_size'  => 'medium',
                    'modal_size'    => 'medium'
                ]
            ]
        ],
        'show_borders'  => false
    ],

    'is_message_field'   => [
        'type'  => 'multi-picker',
        'label' => false,
        'desc'  => false,
        'value' => [
            'message_choice'  => 'message_true'
        ],

        'picker'    => [
            'message_choice'  => [
                'type'      => 'select',
                'label'     => esc_html__( 'Message Field', 'mebel-laim' ),
                'desc'      => esc_html__( 'Please choose if form will have message field', 'mebel-laim' ),
                'choices'   => [
                    'message_true'    => esc_html__( 'Show Message Field', 'mebel-laim' ),
                    'message_false'   => esc_html__( 'Hide Message Field', 'mebel-laim')
                ]
            ]
        ],

        'choices'   => [
            'message_true'    => [
                'message_placeholder'   => [
                    'type'  => 'text',
                    'label' => esc_html__( 'Message Placeholder', 'mebel-laim' ),
                    'desc'  => esc_html__( 'Please enter message field placeholder text', 'mebel-laim' )
                ],

                'message_icon'  => [
                    'type'          => 'icon-v2',
                    'label'         => esc_html__( 'Message Icon', 'mebel-laim' ),
                    'desc'          => esc_html__( 'Please choose message field icon', 'mebel-laim' ),
                    'preview_size'  => 'medium',
                    'modal_size'    => 'medium'
                ]
            ]
        ],
        'show_borders'  => false
    ],

    'button_text'	=> [
    	'type'	=> 'text',
    	'label'	=> esc_html__( 'Send Button Text', 'mebel-laim' ),
	    'desc'	=> esc_html__( 'Please enter send button text', 'mebel-laim' )
    ],

    'popup_button_text'   => [
        'type'  => 'text',
        'label' => esc_html__( 'Success Popup Button Text', 'mebel-laim' ),
        'desc'  => esc_html__( 'Please enter success popup button text', 'mebel-laim' )
    ]
];