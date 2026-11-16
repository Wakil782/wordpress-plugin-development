<?php
/*
Plugin Name: Location Time For My Plugin
Plugin URI: #
Author: Wakil
Author URI: #
Version: 1.0
Description: This is my locational plugin.
Requires at least: 6.0
Tested up to: 6.8

*/

if (!defined('ABSPATH')) {
    exit;
}

// Admin Menu
function location_plugin_map() {
    add_menu_page(
        'Simple Map',
        'Simple Map',
        'manage_options',
        'simple-maps',
        'simple_location_map_callback',
        'dashicons-location-alt',
        101
    );
}
add_action('admin_menu', 'location_plugin_map');

// Admin Page
function simple_location_map_callback() {

    if (isset($_POST['save_location'])) {

        update_option(
            'sim_location',
            sanitize_text_field($_POST['sim_location'])
        );

        echo '<div class="notice notice-success is-dismissible">
                <p>Location Saved Successfully.</p>
              </div>';
    }

    $location = get_option('sim_location');
    ?>

    <div class="wrap">
        <h1>Simple Google Map</h1>

        <form method="post">

            <table class="form-table">

                <tr>
                    <th>Location</th>

                    <td>
                        <input
                            type="text"
                            name="sim_location"
                            class="regular-text"
                            placeholder="Dhaka, Bangladesh"
                            value="<?php echo esc_attr($location); ?>"
                        >
                    </td>

                </tr>

            </table>

            <input
                type="submit"
                name="save_location"
                class="button button-primary"
                value="Save Location"
            >

        </form>

    </div>

    <?php
}

// Shortcode
function save_location_func() {

    $location = get_option('sim_location');

    if (empty($location)) {
        return "<p><strong>Please save a location first.</strong></p>";
    }

    ob_start();
    ?>

    <iframe
        width="100%"
        height="450"
        style="border:0;"
        loading="lazy"
        allowfullscreen
        src="https://maps.google.com/maps?q=<?php echo urlencode($location); ?>&output=embed"
        frameborder="0">
    </iframe>

    <?php

    return ob_get_clean();
}

add_shortcode('location_map', 'save_location_func');