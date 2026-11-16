<?php
/*
Plugin Name: contact Form
Plugin URI: #
Author: wakil
Author URI: #
Description: this is my contact form plugin
Version: 1.0.0
Text Domain: contact form
*/
if(!defined('ABSPATH')){
    die;
}

register_activation_hook(__FILE__, 'rating_plugin_create_table');
function rating_plugin_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . "ratings";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        rating int(2) NOT NULL,
        user_ip varchar(100) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function rating_plugin_shortcode($atts){
    global $post;
    ob_start();
    ?>
    <form method="post">
        <label>⭐ Give Your Rating:</label> <br>
        <select name="rating_value">
            <option value="1">⭐</option>
            <option value="2">⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
        </select>
        <input type="submit" name="rating_submit" value="Submit">
    </form>
    <?php
    rating_plugin_save($post->ID);
    rating_plugin_display($post->ID);
    return ob_get_clean();
}

add_shortcode('rating_form', 'rating_plugin_shortcode');

function rating_plugin_save($post_id){
    if(isset($_POST['rating_submit'])){
       global $wpdb;
       $table_name = $wpdb->prefix . "ratings";  
       $rating = intval($_POST['rating_value']);
       $user_ip = $_SERVER['REMOTE_ADDR'];
       $wpdb->insert($table_name, array(
        'post_id'=> $post_id,
        'rating'=> $rating,
        'user_ip'=> $user_ip
       ))      ;
    }
}

function rating_plugin_display($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "ratings";
    $results = $wpdb->get_results("SELECT rating FROM $table_name WHERE post_id = $post_id");

    if ($results) {
        $total = 0;
        foreach ($results as $row) {
            $total += $row->rating;
        }
        $average = $total / count($results);
        echo "<p>📊 গড় রেটিং: " . round($average, 2) . " ⭐</p>";
    } else {
        echo "<p>কোনো রেটিং নেই।</p>";
    }
}



?>