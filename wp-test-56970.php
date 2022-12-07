<?php
/**
 * Plugin Name: Test for Trac 56970
 * Description: Clears global stylesheet transient. See <a href="https://core.trac.wordpress.org/ticket/56970">Trac 56970</a>.
 * Version: 0.1
 */

// Initialize plugin.
WP_Test_56970_Controller::init();

class WP_Test_56970_Controller {
	public static function init() {
		add_action( 'init', 'wp_test_56970_init' );
		register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivate' ) );
	}
	public static function activate() {
		add_option( 'wp_test_56970_run', true );
	}
	public static function deactivate() {
		delete_option( 'wp_test_56970_run' );
	}
}

function wp_test_56970_init() {
	global $wp_version;

	if ( ! get_option( 'wp_test_56970_run' ) ) {
		return;
	}

	// Issue described in Trac 56970 only affects upgrade from 5.9/6.0 to 6.1.1.
	if ( '6.1.1' === $wp_version ) {
		// Name of `global_styles_` stylesheet transient, e.g. 'global_styles_twentytwentyone'.
		$transient_name = 'global_styles_' . get_stylesheet();
		// Hook to invalidate the transient.
		add_filter( "transient_$transient_name", 'wp_test_56970_transient_global_styles_stylesheet', 10, 2 );
	}
}

function wp_test_56970_transient_global_styles_stylesheet( $value, $transient ) {
	return false;
}
