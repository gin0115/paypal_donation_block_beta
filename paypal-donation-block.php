<?php

/**
 * Plugin Name: Team51 PayPal Donation Block
 * Plugin URI: https://wpspecialprojects.wordpress.com/
 * Description: Gutenberg block for accepting PayPal donations on the current page.
 * Version: 0.1.0
 * Author: WordPress.com Special Projects
 * Author URI: https://wpspecialprojects.wordpress.com/
 * Text Domain: team51-paypal-donations
 * Domain Path: /languages
 * Tested up to: 5.8
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 **/

use Team51\PayPal_Donation_Block\{
	Bootable,
	Block_Shortcode,
	PayPal_Donations,
	Block_Registration
};


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Define all constants for the plugin.
 */
define( 'TEAM51_PAYPAL_DONATION_BLOCK_ROOT_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'TEAM51_PAYPAL_DONATION_BLOCK_ROOT_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );


// Register all hooks.
$classes = array(
	Block_Registration::class,
	PayPal_Donations::class,
	Block_Shortcode::class,
);
foreach ( $classes as $class ) {
	if ( is_subclass_of( $class, Bootable::class ) ) {
		( new $class() )->register();
	}
}
