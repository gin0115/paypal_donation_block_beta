<?php
/**
 * The view template used to render the markup to show donation button
 *
 * The following values are expected (all values escaped before use.)
 * @var string  $donation_button_id
 * @var string  $donation_account
 * @var string  $button_image_url
 * @var string  $button_title
 * @var string  $button_alt
 * @var bool    $is_sandbox
 * @var string  $in_sandbox_mode
 *
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped, all data escaped in calling method.
 */

// If in sandbox mode ?>
<?php if ( true === $is_sandbox ) : ?>
	<div id="paypal-donate-button-sandbox-mode"><?php echo $in_sandbox_mode; ?></div>
<?php endif; ?>


<div id="paypal-donate-button-container"></div>
<div 
	id="render_paypal_donation_block" 
	data-hosted_button_id="<?php echo $donation_button_id; ?>"
	data-business="<?php echo $donation_account; ?>"
	data-button_src="<?php echo $button_image_url; ?>"
	data-button_title="<?php echo $button_title; ?>"
	data-button_alt="<?php echo $button_alt; ?>"
	data-is-sandbox="<?php echo true === $is_sandbox ? '1' : '0'; ?>"
></div>

<?php //phpcs:enable ?>
