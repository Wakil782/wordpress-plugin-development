<?php
global $wpdb;
$table_name = $wpdb->prefix . 'students';
if(isset($_POST['students_submit'])){
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $age = intval($_POST['age']);
    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'email' => $email,
            'age' => $age,
        ),
        array(
            '%s', '%s', '%d'
        )
    );
     echo "Student Added Successfully!";
}


?>

<form method="post">
<label for="name">Name: </label>
<input type="text" name="name" id="students_name"> <br>
<label for="email">Email :</label>
<input type="email" name="email" id="students_email"><br>
<label for="age">Age :</label>
<input type="number" name="age" id="students_age"><br>
<input type="submit" name="students_submit" value="Submit">

</form>


