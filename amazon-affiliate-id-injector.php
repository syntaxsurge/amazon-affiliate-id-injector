<?php
/**
 * Plugin Name: Amazon Affiliate ID Injector
 * Plugin URI: https://syntaxsurge.com
 * Description: Adds your Amazon affiliate ID to all Amazon links in your content.
 * Version: 1.0
 * Author: SyntaxSurge
 * Author URI: https://syntaxsurge.com
 */

// Ensure Wordpress is running to prevent direct access
defined( 'ABSPATH' ) || exit;

// Include settings page
require_once(plugin_dir_path(__FILE__) . 'settings.php');

function amazon_affiliate_id_injector($content) {
    $affiliate_id = get_option('amazon_affiliate_id'); // Set in the settings.php
    
    // Ensure we have an affiliate ID to use
    if (empty($affiliate_id)) {
        return $content;
    }
    
    // Matching Amazon links
    $pattern = '/<a(.*?)href=["\'](https:\/\/www\.amazon\.com(\/[A-Za-z0-9]+)*\/?[?&]?)([^"\']*?)["\'](.*?)>/i';
    
    // Replace callback
    return preg_replace_callback($pattern, function($matches) use ($affiliate_id) {
        $url = $matches[2] . $matches[4];
        
        // If there's already an affiliate tag
        if (strpos($url, 'tag=') !== false) {
            // Replace it if it's not the desired one
            return preg_replace('/tag=[A-Za-z0-9\-_]+/', 'tag=' . $affiliate_id, $matches[0]);
        } else {
            // Append the affiliate tag
            $connector = (strpos($url, '?') !== false) ? '&' : '?';
            return '<a' . $matches[1] . 'href="' . $url . $connector . 'tag=' . $affiliate_id . '"' . $matches[5] . '>';
        }
    }, $content);
}

// Hook the function into 'the_content' filter
add_filter('the_content', 'amazon_affiliate_id_injector', 20);
