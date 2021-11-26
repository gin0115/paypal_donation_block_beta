# Extending the Block

The block can be extend using the following hooks.

## team51_paypal_donation_block_settings

This allows for the changing of all settings that are used throughout the block. You have access to the block name(id) and all translateable strings used (front and backend).

```php
add_filter('team51_paypal_donation_block_settings', function(array $settings): array{

    // Sets the name/id the block is registered with.
    $settings['name'] = 'acme/paypal-donations-block-custom';

    // Translatable strings (used by JS and PHP).
    $settings['i18n']['block_name'] =               esc_html__( 'Donations', 'team51-donations' );
    $settings['i18n']['block_description'] =        esc_html__( 'A form to collect donations.', 'team51-donations' );
    $settings['i18n']['label_default_amount'] =     esc_html__( 'Default donation amount', 'team51-donations' );
    $settings['i18n']['label_button_text'] =        esc_html__( 'Button text', 'team51-donations' );
    $settings['i18n']['label_recurring_checkbox'] = esc_html__( 'Accept recurring donations', 'team51-donations' );
    $settings['i18n']['label_recurring_text'] =     esc_html__( 'Recurring option description', 'team51-donations' );
    $settings['i18n']['subscriptions_missing'] =    esc_html__( 'Accept recurring donations by installing WooCommerce Subscriptions.', 'team51-donations' );
    $settings['i18n']['recurring_text_default'] =   esc_html__( 'Make this a monthly recurring donation', 'team51-donations' );
    $settings['i18n']['button_text_default'] =      esc_html__( 'Donate Now', 'team51-donations');

}); 
