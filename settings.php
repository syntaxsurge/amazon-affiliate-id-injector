<?php

// Ensure Wordpress is running to prevent direct access
defined('ABSPATH') || exit;

// Adding submenu
function amazon_affiliate_submenu() {
    add_submenu_page(
        'options-general.php',
        'Amazon Affiliate Settings',
        'Amazon Affiliate',
        'manage_options',
        'amazon_affiliate',
        'amazon_affiliate_options_page'
    );
}
add_action('admin_menu', 'amazon_affiliate_submenu');

// Settings page
function amazon_affiliate_options_page() {
    ?>
    <div class="wrap">
        <h2>Amazon Affiliate Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('amazon_affiliate_options_group');
            do_settings_sections('amazon_affiliate');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function amazon_affiliate_register_settings() {
    register_setting('amazon_affiliate_options_group', 'amazon_affiliate_id');
    add_settings_section('amazon_affiliate_main_section', null, null, 'amazon_affiliate');
    add_settings_field('amazon_affiliate_id', 'Amazon Affiliate ID', 'amazon_affiliate_id_field', 'amazon_affiliate', 'amazon_affiliate_main_section');
}
add_action('admin_init', 'amazon_affiliate_register_settings');

// Field callback
function amazon_affiliate_id_field() {
    $amazon_affiliate_id = esc_attr(get_option('amazon_affiliate_id'));
    echo "<input type='text' name='amazon_affiliate_id' value='{$amazon_affiliate_id}' />";
}
