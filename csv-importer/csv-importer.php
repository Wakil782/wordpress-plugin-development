<?php

/*
Plugin Name: CSV IMPOTER
Plugin URI: #
Author: wakil
Author URI: #
Version: 1.0.0
Description: that is csv importer plugin
*/

if(!defined('ABSPATH')){
    exit;
}

add_shortcode('csv_new_importer', 'csv_new_impoter_function');

function csv_new_impoter_function(){
    if(isset($_POST['csv_importer'])){
        csv_save_import_data();
    }

    ob_start();
    ?>
    <form  method="post"  enctype="multipart/form-data">
    <input type="file" name="csv_main_file" id="csv_file"><br> <br>
    <input type="submit" name="csv_importer" value="Csv Submit">
    </form>
    <?php
    return ob_get_clean();
}

function csv_files_database(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'csv';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name(
        ID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100),
        email VARCHAR(100),
        age int,
        photo VARCHAR(100),
        phone VARCHAR(50),
        PRIMARY KEY(ID)
    ) $charset_collate";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'csv_files_database');

function csv_save_import_data(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'csv';

    if(!empty($_FILES['csv_main_file']['tmp_name'])){
        $csvfile = $_FILES['csv_main_file']['tmp_name'];
        $handle = fopen($csvfile, "r");
        if($handle){

            $row = 0;

            while(($data = fgetcsv($handle , 1000, ",")) !== false){
                    if($row == 0){
                        $row++;
                        continue;
                    }

                    $wpdb->insert($table_name, array(
                        'name' => sanitize_text_field($data[1]),
                        'email' => sanitize_email($data[2]),
                        'age' => intval($data[3]),
                        'photo' => sanitize_text_field($data[4]),
                        'phone' => sanitize_text_field($data[5])
                    ));

            }
            fclose($handle);
            echo "<p style='color:green;'>CSV Imported Successfully</p>";
        }else {
            echo "<p style='color:red;'>Please Select CSV File</p>";
        }
    }
}

function csv_importer_admin_menu(){
    add_menu_page(
        'CSV Import Data',
        'CSV Import',
        'manage_options',
        'csv-import-data',
        'csv_importer_admin_page',
        'dashicons-media-spreadsheet',
        101
    );
}

add_action('admin_menu', 'csv_importer_admin_menu');

function csv_importer_admin_page(){

    global $wpdb;

    $table_name = $wpdb->prefix . 'csv';

    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ?>

    <div class="wrap">
        <h1>CSV Imported Data</h1>

        <table class="widefat striped">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Photo</th>
                    <th>Phone</th>
                </tr>
            </thead>

            <tbody>

            <?php

            if($results){

                foreach($results as $row){

                    ?>

                    <tr>

                        <td><?php echo esc_html($row->ID); ?></td>

                        <td><?php echo esc_html($row->name); ?></td>

                        <td><?php echo esc_html($row->email); ?></td>

                        <td><?php echo esc_html($row->age); ?></td>

                        <td><?php echo esc_html($row->photo); ?></td>

                        <td><?php echo esc_html($row->phone); ?></td>

                    </tr>

                    <?php

                }

            }else{

                ?>

                <tr>
                    <td colspan="6">No Data Found</td>
                </tr>

                <?php

            }

            ?>

            </tbody>

        </table>

    </div>

    <?php
}


