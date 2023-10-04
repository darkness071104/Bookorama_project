<?php 
// TODO 1: Buatlah koneksi dengan database
$db_host = 'localhost:3307';
$db_database = 'bookorama';
$db_username = 'root';
$db_password = '';

// connect database
$db = new mysqli($db_host, $db_username, $db_password, $db_database);
if ($db->connect_errno) {
    die("Tidak dapat terhubung dengan database : <br/>" . $db->connect_error);
}

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>