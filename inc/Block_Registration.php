<?php

declare(strict_types=1);

/**
 * Registers the PayPal Donation Block
 *
 * @since 0.1.0
 * @author WordPress.com Special Projects
 * @package Team51\PayPal Donation Block
 */

namespace Team51\PayPal_Donation_Block;

use Team51\PayPal_Donation_Block\Helper;
use Team51\PayPal_Donation_Block\Bootable;

class Block_Registration implements Bootable {

	public const ENQUEUE_HANDLE_SCRIPT       = 'paypal-donations-block-js';
	public const ENQUEUE_HANDLE_STYLE_EDITOR = 'paypal-donations-block-editor';
	public const ENQUEUE_HANDLE_STYLE_VIEW   = 'paypal-donations-block';
	public const GUTENBERG_API_VERSION       = 2;

	/**
	 * Registers all hooks and other values.
	 *
	 * @return self
	 */
	public function register(): self {
		add_action( 'init', array( $this, 'enqueue' ) );
		add_action( 'init', array( $this, 'register_block' ) );
		return $this;
	}

	/**
	 * Gets the blocks settings array
	 *
	 * @filter team51_paypal_donation_block_settings
	 * @return array{name:string,icon:string,i18n:array<string,string>}
	 */
	private function get_block_settings(): array {
		$settings = array(
			'name'             => 'team51/paypal-donations-block',
			'icon'             => 'heart',
			'defaultButtonUrl' => esc_url( 'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif' ),
			'i18n'             => array(
				'blockName'                     => esc_html__( 'Paypal Donation', 'team51-paypal-donations' ),
				'blockDescription'              => esc_html__( 'A form to collect donations.', 'team51-paypal-donations' ),
				'isSandboxMode'                 => esc_html__( 'Enable sandbox mode', 'team51-paypal-donations' ),
				'inSandboxMode'                 => esc_html__( 'Sandbox mode endabled.', 'team51-paypal-donations' ),

				// Paypal Button Values
				'buttonAltLabel'                => esc_html__( 'Please enter the alt tag for the button', 'team51-paypal-donations' ),
				'buttonAltDefault'              => esc_html__( 'Donate with PayPal button', 'team51-paypal-donations' ),
				'buttonTitleLabel'              => esc_html__( 'Please enter the label for the button.', 'team51-paypal-donations' ),
				'buttonTitleDefault'            => esc_html__( 'PayPal - The safer, easier way to pay online!', 'team51-paypal-donations' ),
				'buttonImageLabel'              => esc_html__( 'Please enter the URL for the button.', 'team51-paypal-donations' ),

				// Paypal Account Details.
				'donationAccountLabel'          => esc_html__( 'Please enter your paypal email or payer ID.', 'team51-paypal-donations' ),
				'donationButtonIDLabel'         => esc_html__( 'Please enter your donation buttons unique key (hosted_button_id).', 'team51-paypal-donations' ),

				// Media Library labels
				'buttonMediaLibraryButtonLabel' => esc_html__( 'Button Image', 'team51-paypal-donations' ),
				'changeImageButtonLabel'        => esc_html__( 'Change Image', 'team51-paypal-donations' ),
				'removeImageButtonLabel'        => esc_html__( 'Reset Image', 'team51-paypal-donations' ),
			),
		);

		return apply_filters( 'team51_paypal_donation_block_settings', $settings );
	}

	/**
	 * Gets a value from the settings translatable strings.
	 *
	 * @param string $key
	 * @return string|null
	 */
	private function get_translatable_string( string $key ): ?string {
		$settings = $this->get_block_settings();
		if ( ! \array_key_exists( 'i18n', $settings ) ) {
			return null;
		}

		return \array_key_exists( $key, $settings['i18n'] )
			? $settings['i18n'][ $key ]
			: null;
	}

	/**
	 * Gets the attributes used for the block.
	 *
	 * @filter team51_paypal_donation_block_attributes
	 * @return array<string, array<string, mixed>>
	 */
	private function get_block_attributes(): array {
		$attributes = array(
			'donationButtonID' => array(
				'type' => 'string',
			),
			'donationAccount'  => array(
				'type' => 'string',
			),
			'isSandbox'        => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'buttonTitle'      => array(
				'type'    => 'string',
				'default' => $this->get_translatable_string( 'buttonTitleDefault' ),
			),
			'buttonImage'      => array(
				'type'    => 'string',
				'default' => $this->get_translatable_string( 'buttonImageDefault' ),
			),
			'buttonImageID'    => array(
				'type'    => 'number',
				'default' => 0,
			),
			'buttonAlt'        => array(
				'type'    => 'string',
				'default' => $this->get_translatable_string( 'buttonAltDefault' ),
			),
		);

		return apply_filters( 'team51_paypal_donation_block_attributes', $attributes );
	}

	/**
	 * Returns the block name after being run through the filter.
	 *
	 * @return string
	 */
	public function get_block_name(): string {
		$settings = $this->get_block_settings();
		return \array_key_exists( 'name', $settings )
			? $settings['name']
			: 'team51/paypal-donations-block'; // Hardcoded fallback.
	}

	/**
	 * Returns the block icon after being run through the filter.
	 *
	 * @return string
	 */
	public function get_block_icon(): string {
		$settings = $this->get_block_settings();
		return \array_key_exists( 'icon', $settings )
			? $settings['icon']
			: 'heart'; // Hardcoded fallback.
	}

	/**
	 * Gets the defined image button URL.
	 *
	 * @return string
	 */
	public function get_default_button_url() : string {
		$settings = $this->get_block_settings();
		return \array_key_exists( 'defaultButtonUrl', $settings )
			? $settings['defaultButtonUrl']
			: 'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif'; // Hardcoded fallback.
	}

	/**
	 * Register all scripts and styles for the block.
	 *
	 * @return void
	 */
	public function enqueue(): void {
		wp_register_script(
			self::ENQUEUE_HANDLE_SCRIPT,
			TEAM51_PAYPAL_DONATION_BLOCK_ROOT_URI . '/build/index.js',
			array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-components' ),
			Helper::get_plugin_version(),
			true
		);

		wp_localize_script( 'paypal-donations-block-js', 'paypal_donations_block_settings', $this->get_block_settings() );

		wp_register_style(
			self::ENQUEUE_HANDLE_STYLE_EDITOR,
			TEAM51_PAYPAL_DONATION_BLOCK_ROOT_URI . '/build/index.css',
			array( 'wp-edit-blocks' ),
			Helper::get_plugin_version()
		);

		wp_register_style(
			self::ENQUEUE_HANDLE_STYLE_VIEW,
			TEAM51_PAYPAL_DONATION_BLOCK_ROOT_URI . '/build/style-index.css',
			array( 'wp-edit-blocks' ),
			Helper::get_plugin_version()
		);
	}

	/**
	 * Registers the block.
	 *
	 * @return void
	 */
	public function register_block(): void {
		register_block_type(
			$this->get_block_name(),
			array(
				'api_version'     => self::GUTENBERG_API_VERSION,
				'title'           => $this->get_translatable_string( 'block_name' ) ?? 'Paypal Donation Block',
				'description'     => $this->get_translatable_string( 'block_description' ) ?? 'Paypal Donation Block',
				'editor_script'   => self::ENQUEUE_HANDLE_SCRIPT,
				'editor_style'    => self::ENQUEUE_HANDLE_STYLE_EDITOR,
				'style'           => self::ENQUEUE_HANDLE_STYLE_VIEW,
				'icon'            => $this->get_block_icon(),
				'category'        => 'common',
				'attributes'      => $this->get_block_attributes(),
				'render_callback' => $this->get_view_callback(),
			)
		);
	}

	/**
	 * Renders the blocks dynamic view.
	 *
	 * THIS SHOULD NOT BE CALLED DIRECTLY, PLEASE USE $this->get_view_callback() to get the callable.
	 *
	 * @param array<string, mixed> $attributes
	 * @return string
	 */
	public function render_donation_block( array $attributes ): string {
		// Either the email or donation key must be set.
		if ( ! array_key_exists( 'donationButtonID', $attributes ) && ! array_key_exists( 'donationAccount', $attributes ) ) {
			return '';
		}

		// Define all variables.
		$donation_button_id = array_key_exists( 'donationButtonID', $attributes )
			? esc_html( $attributes['donationButtonID'] )
			: '';
		$donation_account   = array_key_exists( 'donationAccount', $attributes )
			? esc_html( $attributes['donationAccount'] )
			: '';

		$is_sandbox      = \array_key_exists( 'isSandbox', $attributes ) && \is_bool( $attributes['isSandbox'] )
			? $attributes['isSandbox']
			: false;
		$in_sandbox_mode = $this->get_translatable_string( 'inSandboxMode' );

		$button_image_url = array_key_exists( 'buttonImage', $attributes )
			? esc_url( $attributes['buttonImage'] )
			: $this->get_default_button_url();
		$button_title     = array_key_exists( 'buttonTitle', $attributes )
			? esc_html( $attributes['buttonTitle'] )
			: $this->get_translatable_string( 'buttonTitleDefault' );
		$button_alt       = array_key_exists( 'buttonAlt', $attributes )
			? esc_html( $attributes['buttonAlt'] )
			: $this->get_translatable_string( 'buttonAltDefault' );

		ob_start();
		require TEAM51_PAYPAL_DONATION_BLOCK_ROOT_PATH . '/assets/paypal-dontation-block-view.php';
		return ob_get_clean();
	}

	/**
	 * Returns the callable used to render the dynamic view.
	 * This is filtered through the filter to toggle the view method
	 *
	 * @filter team51_paypal_donation_block_view
	 * @return callable
	 */
	public function get_view_callback(): callable {
		return apply_filters( 'team51_paypal_donation_block_view', array( $this, 'render_donation_block' ) );
	}
}
