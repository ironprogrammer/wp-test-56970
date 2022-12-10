<?php
/**
 * Plugin Name: Test for Trac 56970
 * Description: Clears the global stylesheet transient after upgrade to 6.1.1. See <a href="https://core.trac.wordpress.org/ticket/56970">Trac 56970</a>.
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
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}
	public static function deactivate() {
		delete_option( 'wp_test_56970_run' );
	}
	public static function uninstall() {
		self::deactivate();
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
		add_filter( "transient_{$transient_name}", 'wp_test_56970_transient_global_styles_stylesheet', 10, 2 );
	}
}

function wp_test_56970_transient_global_styles_stylesheet( $value, $transient ) {
	// Prevent hook from firing again.
	update_option( 'wp_test_56970_run', false );

	return false;
}

// Hook to delete the global styles stylesheet transient after core update.
add_action( '_core_updated_successfully', 'wp_test_56970_delete_global_styles_stylesheet_transient' );
function wp_test_56970_delete_global_styles_stylesheet_transient() {
	$transient_name = 'global_styles_' . get_stylesheet();
	delete_site_transient( "transient_{$transient_name}" );
}
