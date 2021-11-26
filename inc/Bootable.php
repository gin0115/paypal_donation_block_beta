<?php

declare(strict_types=1);

/**
 * Interface to allow the autoloading of classes.
 *
 * @since 0.1.0
 * @author WordPress.com Special Projects
 * @package Team51\PayPal Donation Block
 */

namespace Team51\PayPal_Donation_Block;

interface Bootable {
	/**
	 * Auto run at initlaisation to register all hooks.
	 *
	 * @return self
	 */
	public function register(): self;
}
