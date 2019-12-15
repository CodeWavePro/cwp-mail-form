<?php if ( !defined( 'FW' ) ) die( 'Forbidden' );

/**
 * Function localizes js file and makes own variable for ajax-url.
 */
if ( !is_admin() ) {
	$uri = fw_get_template_customizations_directory_uri( '/extensions/shortcodes/shortcodes/cwp-mail-form' );
	wp_enqueue_style(
	    'fw-shortcode-cwp-mail-form',
	    $uri . '/static/css/styles.css'
	);

	if ( wp_script_is( 'cwp-mail-form', 'registered' ) ) {
		return false;
	}	else {
		wp_enqueue_script(
			'cwp-mail-form',
			$uri . '/static/js/scripts.min.js',
			array( 'jquery' )
		);
		wp_localize_script(
			'cwp-mail-form',
			'cwpAjax',
			array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
		);
	}
}