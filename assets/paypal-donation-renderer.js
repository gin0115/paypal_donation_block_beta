/** 
 * Handles the rendering of the PayPal donation block.
 */
document.addEventListener('DOMContentLoaded', function() {

    // Attempt to get the first donation block rendered.
    let paypalData = document.getElementById('render_paypal_donation_block');
    if (paypalData.length === 0) {
        return;
    }

    // Attempt to get the data values from the paypal wrapper.
    let paypalDataSet = paypalData.dataset;
    if (paypalDataSet.length === 0) {
        return;
    }

    // Check we have either the business account or button key.
    if (paypalDataSet.business === '' && paypalDataSet.hosted_button_id === '') {
        return;
    }

    // Initialise the button rendering.
    PayPal.Donation.Button(parseButtonArgs(paypalDataSet)).render('#paypal-donate-button-container');
});

/**
 * Compiles the data object for 
 * @param {object} paypalDataSet The paypal button args
 * @returns Compiled object for rendering the donation block.
 */
function parseButtonArgs(paypalDataSet) {
    return {
        env: 'production',
        ...(paypalDataSet.business !== '' && { business: paypalDataSet.business }),
        ...(paypalDataSet.hosted_button_id !== '' && { hosted_button_id: paypalDataSet.hosted_button_id }),
        image: {
            src: paypalDataSet.button_src,
            title: paypalDataSet.button_title,
            alt: paypalDataSet.button_alt,
        },
        onComplete: composerOnCompleteCallBack(paypalDataSet)
    }
}

/**
 * Composes the callback used for completed/failed payment
 * @param {object} paypalDataSet The paypal button args
 * @returns function(params)
 */
function composerOnCompleteCallBack(paypalDataSet) {
    return function(params) {
        console.log(paypalDataSet, params);
    }
}