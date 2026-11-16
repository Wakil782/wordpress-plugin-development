<?php
/*
Plugin Name: simple views
Plugin URI: #
Author: wakil
Author URI: #
Description: This is mine new plugin
*/

function simple_views_plugin(){
    if(is_single()){
        global $post;
        $views = get_post_meta($post->ID, 'views', true);
        if(empty($views)){
            add_post_meta( $post->ID, 'views', 1);
        }else{
            $views++;
            update_post_meta($post->ID, 'views', $views);
        }
    }
}
add_action('wp', 'simple_views_plugin');

function simple_view_shortcode(){
    return get_post_meta(get_the_ID(), 'views', true);
}
add_shortcode('views', 'simple_view_shortcode');



?>