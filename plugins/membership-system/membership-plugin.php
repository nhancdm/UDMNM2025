<?php
/*
Plugin Name: Membership System
Description: A WordPress plugin for managing memberships with recurring payments via Stripe.
Version: 1.0
Author: Grok
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MEMBERSHIP_PATH', plugin_dir_path(__FILE__));
define('MEMBERSHIP_URL', plugin_dir_url(__FILE__));

// Include Stripe PHP library (assumes composer autoload or manual inclusion)
require_once MEMBERSHIP_PATH . 'vendor/autoload.php';

// Register custom post type for premium content
add_action('init', 'membership_register_cpt');
function membership_register_cpt() {
    register_post_type('premium_content', [
        'labels' => [
            'name' => 'Premium Content',
            'singular_name' => 'Premium Content',
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}

// Register shortcode for premium content
add_shortcode('premium_content', 'membership_premium_content_shortcode');
function membership_premium_content_shortcode($atts, $content = null) {
    if (!is_user_logged_in()) {
        return '<p>Please log in to access premium content.</p>';
    }

    $user_id = get_current_user_id();
    $membership_level = get_user_meta($user_id, 'membership_level', true);

    if ($membership_level !== 'premium') {
        return '<p>You need a premium membership to access this content. <a href="' . esc_url(home_url('/membership')) . '">Upgrade now</a>.</p>';
    }

    return do_shortcode($content);
}

// Enqueue frontend scripts and styles
add_action('wp_enqueue_scripts', 'membership_enqueue_scripts');
function membership_enqueue_scripts() {
    wp_enqueue_style('membership-style', MEMBERSHIP_URL . 'assets/css/membership.css', [], '1.0');
    wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], null, true);
    wp_enqueue_script('membership-script', MEMBERSHIP_URL . 'assets/js/membership.js', ['jquery', 'stripe-js'], '1.0', true);

    wp_localize_script('membership-script', 'membershipData', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('membership_nonce'),
        'stripe_pk' => get_option('membership_stripe_pk', ''),
    ]);
}

// Register admin menu
add_action('admin_menu', 'membership_admin_menu');
function membership_admin_menu() {
    add_menu_page(
        'Membership Settings',
        'Membership',
        'manage_options',
        'membership-settings',
        'membership_admin_page',
        'dashicons-groups',
        80
    );
}

// Admin page callback
function membership_admin_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['membership_save'])) {
        check_admin_referer('membership_settings');
        update_option('membership_stripe_pk', sanitize_text_field($_POST['stripe_pk']));
        update_option('membership_stripe_sk', sanitize_text_field($_POST['stripe_sk']));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $stripe_pk = get_option('membership_stripe_pk', '');
    $stripe_sk = get_option('membership_stripe_sk', '');
    ?>
<div class="wrap">
    <h1>Membership Settings</h1>
    <form method="post">
        <?php wp_nonce_field('membership_settings'); ?>
        <table class="form-table">
            <tr>
                <th><label for="stripe_pk">Stripe Publishable Key</label></th>
                <td><input type="text" name="stripe_pk" id="stripe_pk" value="<?php echo esc_attr($stripe_pk); ?>"
                        class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="stripe_sk">Stripe Secret Key</label></th>
                <td><input type="text" name="stripe_sk" id="stripe_sk" value="<?php echo esc_attr($stripe_sk); ?>"
                        class="regular-text"></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="membership_save" class="button-primary" value="Save Changes">
        </p>
    </form>
</div>
<?php
}

// Create membership page
add_action('wp', 'membership_create_page');
function membership_create_page() {
    if (!get_page_by_path('membership')) {
        wp_insert_post([
            'post_title' => 'Membership',
            'post_content' => '[membership_checkout]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'membership',
        ]);
    }
}

// Shortcode for checkout
add_shortcode('membership_checkout', 'membership_checkout_shortcode');
function membership_checkout_shortcode() {
    ob_start();
    ?>
<div id="membership-checkout">
    <h2>Premium Membership</h2>
    <p>Unlock exclusive content for $10/month.</p>
    <button id="checkout-button">Subscribe Now</button>
</div>
<?php
    return ob_get_clean();
}

// AJAX handler for creating Stripe checkout session
add_action('wp_ajax_membership_create_checkout', 'membership_create_checkout');
add_action('wp_ajax_nopriv_membership_create_checkout', 'membership_create_checkout');
function membership_create_checkout() {
    check_ajax_referer('membership_nonce', 'nonce');

    $stripe_sk = get_option('membership_stripe_sk', '');
    \Stripe\Stripe::setApiKey($stripe_sk);

    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Premium Membership',
                    ],
                    'unit_amount' => 1000, // $10.00
                    'recurring' => [
                        'interval' => 'month',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => home_url('/membership?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => home_url('/membership'),
            'client_reference_id' => get_current_user_id(),
        ]);

        wp_send_json_success(['sessionId' => $session->id]);
    } catch (Exception $e) {
        wp_send_json_error(['message' => $e->getMessage()]);
    }
}

// Handle Stripe webhook
add_action('rest_api_init', 'membership_register_webhook');
function membership_register_webhook() {
    register_rest_route('membership/v1', '/webhook', [
        'methods' => 'POST',
        'callback' => 'membership_handle_webhook',
        'permission_callback' => '__return_true',
    ]);
}

function membership_handle_webhook($request) {
    $stripe_sk = get_option('membership_stripe_sk', '');
    \Stripe\Stripe::setApiKey($stripe_sk);

    $payload = $request->get_body();
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
    $endpoint_secret = get_option('membership_stripe_webhook_secret', '');

    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    } catch (\Exception $e) {
        return new WP_Error('invalid_signature', 'Invalid webhook signature.', ['status' => 400]);
    }

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        $user_id = $session->client_reference_id;
        update_user_meta($user_id, 'membership_level', 'premium');
        update_user_meta($user_id, 'stripe_subscription_id', $session->subscription);
    } elseif ($event->type === 'customer.subscription.deleted') {
        $subscription = $event->data->object;
        $user_id = get_users([
            'meta_key' => 'stripe_subscription_id',
            'meta_value' => $subscription->id,
            'number' => 1,
            'fields' => 'ID',
        ])[0];
        update_user_meta($user_id, 'membership_level', 'free');
    }

    return new WP_REST_Response('Webhook processed', 200);
}

// Restrict premium content access
add_filter('the_content', 'membership_restrict_content');
function membership_restrict_content($content) {
    if (get_post_type() === 'premium_content' && !current_user_can('manage_options')) {
        if (!is_user_logged_in()) {
            return '<p>Please log in to access this content.</p>';
        }

        $user_id = get_current_user_id();
        $membership_level = get_user_meta($user_id, 'membership_level', true);
        if ($membership_level !== 'premium') {
            return '<p>You need a premium membership to access this content. <a href="' . esc_url(home_url('/membership')) . '">Upgrade now</a>.</p>';
        }
    }
    return $content;
}
?>