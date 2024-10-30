<?php
/**
 * Plugin Name: Image caption for WP
 * Plugin URI: https://www.wpcocktail.com/image-caption
 * Description: After activating this plugin in WordPress, any new captions for images should automatically be set to your site's title.
 * Version: 1.0.0
 * Author: WPCocktail
 * Author URI: https://www.wpcocktail.com/
 * License:  GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: image-caption-for-wp
 * Domain Path: /languages
 * Tested up to: 6.4.2
 */

// Exit if accessed directly, I would like to protect my packag
if (!defined('ABSPATH')) {
    exit;
}

// Hook the function to the filter
add_filter('img_caption_shortcode', 'wpc_modify_image_caption', 10, 3);

/**
 * Modify the caption of the image.
 *
 * @param string $output The caption output.
 * @param array  $attr   Attributes of the shortcode.
 * @param string $content The image element, possibly wrapped in a hyperlink.
 *
 * @return string Modified caption.
 */
function wpc_modify_image_caption($output, $attr, $content) {
    // Get the site title
    $site_title = get_bloginfo('name');

    // Check if the caption is already set to the site title
    if (trim($attr['caption']) === $site_title) {
        return $output;
    }

    // Build the new caption HTML
    $attributes = shortcode_atts(array(
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => $site_title,
    ), $attr, 'caption');

    $attributes['width'] = (int) $attributes['width'];
    if ($attributes['width'] < 1 || empty($attributes['caption'])) {
        return $content;
    }

    if ($attributes['id']) {
        $attributes['id'] = 'id="' . esc_attr($attributes['id']) . '" ';
    }

    return '<figure ' . $attributes['id'] . 'class="wp-caption ' . esc_attr($attributes['align']) . '" style="width: ' . ($attributes['width'] + 10) . 'px">'
    . do_shortcode($content)
    . '<figcaption class="wp-caption-text">' . $attributes['caption'] . '</figcaption>'
    . '</figure>';
}

