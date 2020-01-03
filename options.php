<?php if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = [
    [
        'type'      => 'tab',
        'title'     => esc_html__( 'Form Settings', 'mebel-laim' ),
        'options'   => [
            'send_to'   => [
                'type'  => 'text',
                'label' => esc_html__( 'Send E-mail To', 'mebel-laim' ),
                'desc'  => esc_html__( 'Please enter e-mail for receiving messages', 'mebel-laim' )
            ],

            'form_margin_top'    => [
                'type'  => 'text',
                'label' => esc_html__( 'Margin Top (px)', 'mebel-laim' ),
                'desc'  => esc_html__( 'Please enter form margin top value in pixels', 'mebel-laim' )
            ],

            'legend'    => [
                'type'  => 'text',
                'label' => esc_html__( 'Form Title', 'mebel-laim' ),
                'desc'  => esc_html__( 'Please enter form title text', 'mebel-laim' )
            ],

            'fields' => [
                'type'  => 'addable-box',
                'label' => esc_html__( 'Form Fields', 'mebel-laim' ),
                'desc'  => esc_html__( 'Add, remove or edit e-mail form fields', 'mebel-laim' ),
                'box-options' => [
                    'field_type'  => [
                        'type'      => 'radio',
                        'value'     => 'choice-text',
                        'label'     => esc_html__( 'Field Type', 'mebel-laim' ),
                        'desc'      => esc_html__( 'Select field type', 'mebel-laim' ),
                        'inline'    => false,
                        'choices'   => [
                            'choice-text'       => esc_html__( 'Text Input', 'mebel-laim' ),
                            'choice-name'       => esc_html__( 'Name Input', 'mebel-laim' ),
                            'choice-phone'      => esc_html__( 'Phone Input', 'mebel-laim' ),
                            'choice-email'      => esc_html__( 'E-mail Input', 'mebel-laim' ),
                            'choice-message'    => esc_html__( 'Textarea', 'mebel-laim' )
                        ]
                    ],

                    'field_is_required' => [
                        'type'  => 'switch',
                        'label' => esc_html__( 'Is Field Is Required?', 'mebel-laim' ),
                        'desc'  => esc_html__( 'Please choose is this field required or not', 'mebel-laim' )
                    ],

                    'field_placeholder'   => [
                        'type'  => 'text',
                        'label' => esc_html__( 'Placeholder', 'mebel-laim' ),
                        'desc'  => esc_html__( 'Please enter field placeholder text', 'mebel-laim' )
                    ],

                    'field_icon'  => [
                        'type'          => 'icon-v2',
                        'label'         => esc_html__( 'Icon', 'mebel-laim' ),
                        'desc'          => esc_html__( 'Please choose field icon', 'mebel-laim' ),
                        'preview_size'  => 'medium',
                        'modal_size'    => 'medium'
                    ]
                ],
                'template'          => '{{- field_placeholder }}', // box title
                'limit'             => 0, // limit the number of boxes that can be added
                'add-button-text'   => esc_html__( 'Add new field', 'mebel-laim' ),
                'sortable'          => true
            ],

            'button_text'   => [
                'type'  => 'text',
                'label' => esc_html__( 'Send Button Text', 'mebel-laim' ),
                'desc'  => esc_html__( 'Please enter send button text', 'mebel-laim' )
            ],

            'popup_button_text'   => [
                'type'  => 'text',
                'label' => esc_html__( 'Success Popup Button Text', 'mebel-laim' ),
                'desc'  => esc_html__( 'Please enter success popup button text', 'mebel-laim' )
            ]
        ]
    ],

    [
        'type'      => 'tab',
        'title'     => esc_html__( 'Letter Settings', 'mebel-laim' ),
        'options'   => [
            'header_background_color'   => [
                'type'  => 'color-picker',
                'value' => '#fff',
                'label' => esc_html__( 'Header Background Color', 'mebel-laim' ),
                'desc'  => esc_html__( 'Choose background color for header', 'mebel-laim' )
            ],

            'header_text_color'   => [
                'type'  => 'color-picker',
                'value' => '#000',
                'label' => esc_html__( 'Header Text Color', 'mebel-laim' ),
                'desc'  => esc_html__( 'Choose color for header text', 'mebel-laim' )
            ],

            'default_font_size'   => [
                'type'  => 'text',
                'value' => '18',
                'label' => esc_html__( 'Default Text Size (px)', 'mebel-laim' ),
                'desc'  => esc_html__( 'Enter default letter font size value in pixels', 'mebel-laim' )
            ],

            'text_color'   => [
                'type'  => 'color-picker',
                'value' => '#000',
                'label' => esc_html__( 'Text Color', 'mebel-laim' ),
                'desc'  => esc_html__( 'Choose color for letter text', 'mebel-laim' )
            ],

            'links_color'   => [
                'type'  => 'color-picker',
                'value' => '#fff',
                'label' => esc_html__( 'Links Color', 'mebel-laim' ),
                'desc'  => esc_html__( 'Choose color for links', 'mebel-laim' )
            ],

            'footer_background_color'   => [
                'type'  => 'color-picker',
                'value' => '#fff',
                'label' => esc_html__( 'Footer Background Color', 'mebel-laim' ),
                'desc'  => esc_html__( 'Choose background color for footer', 'mebel-laim' )
            ],

            'footer_text_color'   => [
                'type'  => 'color-picker',
                'value' => '#000',
                'label' => esc_html__( 'Footer Text Color', 'mebel-laim' ),
                'desc'  => esc_html__( 'Choose color for footer text', 'mebel-laim' )
            ]
        ]
    ]
];