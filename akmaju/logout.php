<?php
require_once 'config/db.php';
session_destroy();
$_SESSION['message'] = alert('You have been logged out', 'success');
redirect('login.php');
