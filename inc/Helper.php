<?php

declare(strict_types=1);

/**
 * Class of helper methods
 *
 * @since 0.1.0
 * @author WordPress.com Special Projects
 * @package Team51\PayPal Donation Block
 */

namespace Team51\PayPal_Donation_Block;

class Helper {

	/**
	 * Gets the version from the plugin data.
	 *
	 * @param string $fallback The fallback to use if the value isnt found.
	 * @return string
	 */
	public static function get_plugin_version( string $fallback = '1.0.0' ): string {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Plugin entry file.
		$file = \dirname( __DIR__, 1 ) . '/paypal-donation-block.php';
		$data = \get_plugin_data( $file );

		// If version is set, return that or use the fallback.
		return array_key_exists( 'Version', $data )
			? $data['Version']
			: $fallback;
	}
}
