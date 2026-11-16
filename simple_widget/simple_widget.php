<?php
/*
Plugin Name: Simple widget
Plugin URI: #
Author: wakil
Author URI: #
Version: 1.0.0
*/

if(!defined('ABSPATH')){
    exit;
}

class simple_widget extends WP_Widget{
    public function __construct(){
        parent::__construct(
                'simple_widget',
                'Simple Widget',
                array(
                    'description'=> 'This is new simple widget'
                ));      
    }

    public function widget($args, $instance){
        echo $args['before_widget'];
        echo $args['before_title'];
        echo 'simple widget';
        echo $args['after_title'];

        echo "<p> This is new simple widget api. </p>";

        echo $args['after_widget'];
    }
    public function form($instance){
        ?>
        <p>
            This is basic widget.
        </p>
        <?php
    }

    public function update($new_instance, $old_instance){
        return $new_instance;
    }
}

function simple_widget_register(){
    register_widget('simple_widget');
}

add_action('widgets_init', 'simple_widget_register');













