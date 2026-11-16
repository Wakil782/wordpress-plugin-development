<?php
/*
Plugin Name: Student Management
Plugin URI: #
Author: wakil
Author URI: #
Version: 1.0.0
Description: this is student management plugin.
*/
if(!defined('ABSPATH')){
    exit;
}

function student_management_install(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'student';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name(
        ID INT NOT NULL AUTO_INCREMENT,
        Name VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL,
        Phone VARCHAR(20) NOT NULL,
        Deperment VARCHAR(50) NOT NULL,
        Address text NOT NULL,
        PRIMARY KEY(ID)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}
register_activation_hook(__FILE__, 'student_management_install');

add_shortcode('student_management', 'student_management_shortcode_check');

function student_management_shortcode_check(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'student';

    if(isset($_POST['student_submit'])){
        $name = sanitize_text_field($_POST['student_name']);
        $email = sanitize_text_field($_POST['student_email']);
        $phone = sanitize_text_field($_POST['student_phone']);
        $deperment = sanitize_text_field($_POST['student_deperment']);
        $address = sanitize_text_field($_POST['student_address']);
        
        $wpdb->insert($table_name, array(
            'Name' => $name,
            'Email' => $email,
            'Phone' => $phone,
            'Deperment' => $deperment,
            'Address' => $address
        ));
        echo "<p style='color:green;'>✅ Student Added Successfully!</p>";

    }

    ob_start();
    ?>
    <form  method="post">
        Name:  <input type="text" name="student_name" placeholder="Name" ><br><br>
        Email: <input type="email" name="student_email" placeholder="Email" ><br><br>
        Phone:  <input type="number" name="student_phone" placeholder="Phone" ><br><br>
        Deperment:  <input type="text" name="student_deperment" placeholder="Deperment" required><br><br>
        Address:  <input type="text" name="student_address" placeholder="Address" required><br><br>

        <input type="submit" name="student_submit" value="Submit">
    </form>

    <?php

    $result = $wpdb->get_results("SELECT * FROM $table_name");

    if($result){
        echo  "<h3>Student List</h3><table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Department</th></tr>";

        foreach($result as $data){
         echo "<tr>
                    <td>{$data->ID}</td>
                    <td>{$data->Name}</td>
                    <td>{$data->Email}</td>
                    <td>{$data->Phone}</td>
                    <td>{$data->Deperment}</td>
                    <td>{$data->Address}</td>
                  </tr>";
        }
        echo "</table>";

    }



    return ob_get_clean();
}







?>