<?php

declare(strict_types=1);

/**
 * Handles the rendering and interop with the PayPal Donations SDK
 *
 * @since 0.1.0
 * @author WordPress.com Special Projects
 * @package Team51\PayPal Donation Block
 */

namespace Team51\PayPal_Donation_Block;

use Team51\PayPal_Donation_Block\Bootable;

class PayPal_Donations implements Bootable {

	public const PAYPAL_SDK_HANDLE = 'paypal_dontation_sdk';

	/**
	 * Registers all the hooks.
	 *
	 * @return self
	 */
	public function register(): self {
		add_action( 'wp_head', array( $this, 'enable_x_ua_compatibility_for_edge' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'script_loader_tag', array( $this, 'add_attributes_to_script_tag' ), 10, 3 );
		return $this;
	}

	/**
	 * Enqueues the PayPal SDK
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		// Enqueue the SDK class.
		\wp_enqueue_script(
			self::PAYPAL_SDK_HANDLE,
			esc_url( 'https://www.paypalobjects.com/donate/sdk/donate-sdk.js' ),
			array(),
			Helper::get_plugin_version(),
			false
		);

		// Enqueue the PayPal donations renderer
		\wp_enqueue_script(
			'paypal_dontation_renderer',
			TEAM51_PAYPAL_DONATION_BLOCK_ROOT_URI . '/assets/paypal-donation-renderer.js',
			array(),
			Helper::get_plugin_version(),
			true
		);
	}

	/**
	 *  Dynamically adds the charset tag to the SDK script tag.
	 *
	 * @param string $tag     The parsed SCRIPT tag (HTML format)
	 * @param string $handle  The handle for the enqueued script
	 * @param string $source  The URI of the script file.
	 * @return string
	 */
	public function add_attributes_to_script_tag( string $tag, string $handle, string $source ): string {
		return self::PAYPAL_SDK_HANDLE !== $handle
			? $tag
			: sprintf( '<script type="text/javascript" src="%s" %s></script>', $source, 'charset="UTF-8"' ); // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript, script is enqueued, just adding data args.
	}

	/**
	 * Adds the X-UA-Compatible header meta for IE/Edge compatibility.
	 *
	 * @return void
	 */
	public function enable_x_ua_compatibility_for_edge() {
		echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"/>\n";
	}
}
