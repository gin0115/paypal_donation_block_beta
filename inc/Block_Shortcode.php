<?php

declare(strict_types=1);

/**
 * Allows for rendering of the Donation Block via a shortcode
 * Added to allow support for sites without gutenberg or for use in
 * footer and widget content.
 *
 * @since 0.1.0
 * @author WordPress.com Special Projects
 * @package Team51\PayPal Donation Block
 */

namespace Team51\PayPal_Donation_Block;

use Team51\PayPal_Donation_Block\Bootable;

class Block_Shortcode implements Bootable {

	protected Block_Registration $block_registration;

	public function __construct() {
		$this->block_registration = new Block_Registration();
	}

	/**
	 * Registers all hooks and other values.
	 *
	 * @return self
	 */
	public function register(): self {
		add_shortcode( 'paypal_donation_block', array( $this, 'shortcode_renderer' ) );
		return $this;
	}

	/**
	 * The callback used to render the donation block.
	 *
	 * @param array $attributes
	 * @param string $content
	 * @return string
	 */
	public function shortcode_renderer( array $attributes, string $content = '' ): string {
		// Allowed attribute keys, with mapping to JS values.
		$allowed = array(
			'hosted_button_id' => 'donationButtonID',
			'donation_account' => 'donationAccount',
			'button_image_url' => 'buttonImage',
			'button_title'     => 'buttonTitle',
			'button_alt'       => 'buttonAlt',
		);

		// Compose the arguments needed for the Block.
		$arguments = array_reduce(
			array_keys( $allowed ),
			function( $arguments, $key ) use ( $allowed, $attributes ) {
				if ( array_key_exists( $key, $attributes ) ) {
					$arguments[ $allowed[ $key ] ] = 'button_image_url' === $key
					? esc_url( $attributes[ $key ] )
					: esc_html( $attributes[ $key ] );
				}
				return $arguments;
			},
			array()
		);

		// Get the block renderers view callback, but allow filters to alter this.
		$view_callable = $this->block_registration->get_view_callback();
		return $view_callable( $arguments );
	}
}
