<?php
/*
Plugin Name: Plugin csv data uploder
Plugin URI: #
Author: wakil
Author URI: #
Description: This is warning notice plugin 
Version: 1.0.0
*/

define("CSV_DIR_PATH", plugin_dir_path(__FILE__));

function csv_plugin_shortcode(){

    ob_start();

    require_once CSV_DIR_PATH . '/templates/views.php';

    

    return ob_get_clean();
}

add_shortcode('new_code', 'csv_plugin_shortcode');

function csv_plugin_activation(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'students_data';
    $charset_collate = $wpdb->get_charset_collate();

    $spl = "CREATE TABLE $table_name(
      id INT(11) NOT NULL AUTO_INCREMENT,
      name VARCHAR(50) DEFAULT NULL,
      email VARCHAR(50) DEFAULT NULL,
      age INT(5) DEFAULT NULL,
      phone VARCHAR(30) DEFAULT NULL,
      photo VARCHAR(120) DEFAULT NULL,
      PRIMARY KEY(id)
    ) $charset_collate;";

    require_once(ABSPATH .'wp-admin/includes/upgrade.php');
    dbDelta($spl);
    
}
register_activation_hook(__FILE__, 'csv_plugin_activation');



function csv_enqueue_style_script(){
    wp_enqueue_script('jquery');
    wp_enqueue_script('csv-script', plugin_dir_url(__FILE__) .'assets/script.js', array('jquery') );
    wp_localize_script('csv-script', 'csv-object', array(
        'ajax_url'=> admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'csv_enqueue_style_script');

add_action('wp_ajax_cdu_submit_form_data', 'csv_ajax_handler');
add_action('wp_ajax_nopriv_cdu_submit_form_data', 'csv_ajax_handler');


function csv_ajax_handler(){

    if($_FILES['csv_data_file']){

    $csvfiles = $_FILES['csv_data_file']['tmp_name'];
    $handle = fopen($csvFile, "r");

        global $wpdb;
        $table_name = $wpdb->prefix."students_data";

        if($handle){

           $row = 0;
           while( ($data = fgetcsv($handle, 1000, ",")) !== FALSE ){

                if($row == 0){
                    $row++;
                    continue;
                }   

                // Insert data into Table
                $wpdb->insert($table_name, array(
                    "name" => $data[1],
                    "email" => $data[2],
                    "age" => $data[3],
                    "phone" => $data[4],
                    "photo" => $data[5]
                ));
           }

           fclose($handle);

           echo json_encode([
              "status" => 1,
              "message" => "Data uploaded successfully"
           ]);
        }
    }else{

        echo json_encode(array(
            "status" => 0,
            "message" => "No File Found"
        ));
    }
   
    exit;
}

?>