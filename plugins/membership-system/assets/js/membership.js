jQuery(document).ready(function($) {
    const stripe = Stripe(membershipData.stripe_pk);
    const $checkoutButton = $('#checkout-button');

    $checkoutButton.on('click', function() {
        $.ajax({
            url: membershipData.ajax_url,
            method: 'POST',
            data: {
                action: 'membership_create_checkout',
                nonce: membershipData.nonce,
            },
            success: function(response) {
                if (response.success) {
                    stripe.redirectToCheckout({ sessionId: response.data.sessionId });
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('Failed to connect to the server.');
            }
        });
    });
});