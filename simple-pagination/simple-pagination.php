<?php
/*
Plugin Name: Email Notification Plugin
Description: নতুন পোস্ট publish হলে admin কে ইমেইল পাঠাবে।
Version: 1.1
Author: Wakil
*/

// নিরাপত্তা
if ( !defined('ABSPATH') ) exit;

// ইমেইল পাঠানোর ফাংশন
function my_email_notification_on_publish($ID, $post) {
    $admin_email = get_option('admin_email');

    $subject = 'নতুন পোস্ট প্রকাশিত হয়েছে: ' . $post->post_title;
    $message = "হ্যালো Admin,\n\nএকটি নতুন পোস্ট প্রকাশিত হয়েছে।\n\n";
    $message .= "শিরোনাম: " . $post->post_title . "\n";
    $message .= "লিঙ্ক: " . get_permalink($ID) . "\n\n";
    $message .= "ধন্যবাদ।";

    $headers = array('Content-Type: text/plain; charset=UTF-8');

    wp_mail($admin_email, $subject, $message, $headers);
}
add_action('publish_post', 'my_email_notification_on_publish', 10, 2);

// SMTP সেটআপ (ডেমো)
add_action('phpmailer_init', function($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'yourgmail@gmail.com';
    $phpmailer->Password = 'your_app_password';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = 'yourgmail@gmail.com';
    $phpmailer->FromName = 'WordPress Notification';
});
