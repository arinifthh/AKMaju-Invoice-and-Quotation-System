<?php
require_once 'config/db.php';

// check if user is logged in
if (!isset($_SESSION['admin'])) 
{
    if (!isset($_SESSION['staff'])) 
    {
        redirect('login.php');
    }
    else
    {
        $staff = $_SESSION['staff'];
        redirect('staff/index.php');
    }
} 
else 
{
    $admin = $_SESSION['admin'];
    redirect('admin/index.php');
}
