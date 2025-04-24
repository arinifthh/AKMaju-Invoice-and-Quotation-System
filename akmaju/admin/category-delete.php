<?php
require_once '../config/db.php';
if (!isset($_SESSION['admin'])) {
    redirect('../login.php');
} else {
    $admin = $_SESSION['admin'];
}

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $sql_category = "SELECT * FROM `product_category` WHERE `product_category_id` = '$category_id'";
    $result_category = $conn->query($sql_category);

    if ($result_category->num_rows > 0) {
        $category = $result_category->fetch_assoc();

        // check if category has product
        $sql_product = "SELECT * FROM `products` WHERE `product_category_id` = '$category_id'";
        $result_product = $conn->query($sql_product);

        if ($result_product->num_rows > 0) {
            $_SESSION['message'] = alert('Category cannot be deleted because it has product', 'danger');
            redirect('category.php');
        } else {
            $current_date = date('Y-m-d H:i:s');
            $sql_update_category = "UPDATE `product_category` SET `product_category_status` = '0', `product_category_updated_at` = '$current_date' WHERE `product_category_id` = '$category_id'";
            $result_update_category = $conn->query($sql_update_category);

            if ($result_update_product) {
                $_SESSION['message'] = alert('Category has been deleted', 'success');
            } else {
                $_SESSION['message'] = alert('Failed to delete category', 'danger');
            }
            redirect('category.php');
        }
    }




    else {
        $_SESSION['message'] = alert('Category not found', 'danger');
        redirect('category.php');
    }
} else {
    $_SESSION['message'] = alert('Category not found', 'danger');
    redirect('category.php');
}
