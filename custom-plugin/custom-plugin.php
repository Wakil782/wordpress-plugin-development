<?php
/*
Plugin Name: Simple Pagination
Description: Custom Pagination Plugin using hooks
Version: 1.0
Author: Wakil
*/

// 🔹 Hook দিয়ে Pagination Function রেজিস্টার করা
add_action('wp_footer', 'simple_pagination_display');

function simple_pagination_display() {
    if (is_home() || is_archive()) {
        global $wp_query;

        $big = 999999999; // unique number
        $pages = paginate_links( array(
            'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'  => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total'   => $wp_query->max_num_pages,
            'type'    => 'array'
        ));

        if (is_array($pages)) {
            echo '<div class="custom-pagination">';
            foreach ($pages as $page) {
                echo '<span class="page-link">'.$page.'</span>';
            }
            echo '</div>';
        }
    }
}

?>

