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
	}
}

function wp_test_56970_init() {
}
