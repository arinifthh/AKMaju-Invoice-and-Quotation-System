<?php
// Start session
session_start();

// Timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Database Credentials
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'db_akmaju2';

$phpMailerHost = 'smtp.gmail.com';
$phpMailerUsername = 'nrulerina@gmail.com';
$phpMailerPassword = 'bmul xsry ueiz ekfz';


// Connect with the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Display error if failed to connect
if ($conn->connect_errno) {
    die("Failed to connect with MySQL: " . $conn->connect_error);
}

// base url
$baseUrl = 'http://localhost/akmaju/';

function base_url($url = null)
{
    global $baseUrl;
    return $baseUrl . $url;
}

// redirect url
function redirect($url = null)
{
    echo "<script>window.location.href='" . $url . "'</script>";
    die;
}

function alert($message, $type = 'info')
{
    // bootsrap 4 alert
    $text = '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
    $text .= $message;
    $text .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    $text .= '<span aria-hidden="true">&times;</span>';
    $text .= '</button>';
    $text .= '</div>';
    return $text;
}