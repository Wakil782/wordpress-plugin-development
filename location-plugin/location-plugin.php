<?php
/*
Plugin Name: Simple Location Map
Plugin URI: #
Description: Simple Google Map Embed Plugin
Version: 1.0
Author: Wakil
*/

if (!defined('ABSPATH')) {
    exit;
}

/*==========================
Admin Menu
==========================*/

function slm_admin_menu() {

    add_menu_page(
        'Location Map',
        'Location Map',
        'manage_options',
        'slm-location-map',
        'slm_settings_page',
        'dashicons-location',
        20
    );

}

add_action('admin_menu', 'slm_admin_menu');


/*==========================
Settings Page
==========================*/

function slm_settings_page() {

    if (isset($_POST['save_location'])) {

        update_option(
            'slm_location',
            sanitize_text_field($_POST['slm_location'])
        );

        echo '<div class="updated"><p>Location Saved Successfully.</p></div>';
    }

    $location = get_option('slm_location', '');

?>

<div class="wrap">

    <h1>Simple Location Map</h1>

    <form method="post">

        <table class="form-table">

            <tr>

                <th>Location</th>

                <td>

                    <input
                        type="text"
                        name="slm_location"
                        value="<?php echo esc_attr($location); ?>"
                        style="width:400px;"
                        placeholder="Dhaka Bangladesh"
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


/*==========================
Shortcode
==========================*/

function slm_map_shortcode() {

    $location = get_option('slm_location');

    if (empty($location)) {
        return "<p>Please save a location first.</p>";
    }

    ob_start();
    ?>

    <iframe
        width="100%"
        height="450"
        style="border:0;"
        loading="lazy"
        allowfullscreen
        src="https://maps.google.com/maps?q=<?php echo urlencode($location); ?>&output=embed">
    </iframe>

    <?php

    return ob_get_clean();

}

add_shortcode('location_map', 'slm_map_shortcode');


