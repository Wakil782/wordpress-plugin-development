<?php
/*
Plugin Name: New Contact Form
Plugin URI: #
Author: wakil
Author URI: #
Description: This is my new contact form plugin.
*/
if(!defined("ABSPATH")){
    exit;
}

function new_contact_form_plugin(){
    global $wpdb;
    $table_name = $wpdb->prefix . "contact_message";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name(
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY(id)


    ) $charset_collate";
    require_once(ABSPATH . "wp-admin/includes/upgrade.php");
    dbDelta($sql);
}
register_activation_hook(__FILE__, "new_contact_form_plugin");

function contact_form_shortcode(){

    global $wpdb;
    $table_name = $wpdb->prefix .  "contact_message";
    if(isset($_POST["cnf_submit"])){
        $name = sanitize_text_field($_POST["cnf_name"]);
        $email = sanitize_email($_POST["cnf_email"]);
        $message = sanitize_textarea_field($_POST["cnf_message"]);

        $wpdb->insert(
            $table_name,
            array(
                "name"=> $name,
                "email"=> $email,
                "message"=> $message
            ),
            array(
                "%s",
                "%s",
                "%s",
            )
        );
    }



    ob_start();

    ?>
    <form  method="post">
        <p>Name: <br> <input type="text" name="cnf_name" ></p>
        <p>Email: <br> <input type="email" name="cnf_email"></p>
        <p>Message: <br> <textarea name="cnf_message" ></textarea></p>
        <p><input type="submit" name="cnf_submit" value="cnf submit"></p>
    </form>
    <?php
    return ob_get_clean();


}
add_shortcode("contact_form", "contact_form_shortcode");

function contact_message_shortcode(){
    global $wpdb;
    $table_name = $wpdb->prefix . "contact_message";
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    ob_start();
    ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message </th>
            <th>Date</th>
        </tr>
        <?php foreach($results as $row){
            ?>
                <tr>
                    <td><?php echo esc_html($row->id); ?></td>
                    <td><?php echo esc_html($row->name); ?></td>
                    <td><?php echo esc_html($row->email); ?></td>
                    <td><?php echo esc_html($row->message); ?></td>
                    <td><?php echo esc_html($row->created_at); ?></td>
                </tr>
            <?php
        }
        ?>
    </table>
    <?php
    return ob_get_clean();

}
add_shortcode("cnf_data", "contact_message_shortcode");





?>