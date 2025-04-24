<?php
require_once '../config/db.php';
if (!isset($_SESSION['admin'])) {
    redirect('../login.php');
} else {
    $admin = $_SESSION['admin'];
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $sql_user = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();

        // check in quotations SELECT `quotation_detail_id`, `quotation_detail_quotation_id`, `quotation_detail_product_id`, `quotation_detail_quantity`, `quotation_detail_selling_price`, `quotation_detail_discount_percent`, `quotation_detail_discount_amount`, `quotation_detail_tax_code`, `quotation_detail_total` FROM `quotation_details` WHERE 1
        //$sql_quotation_detail = "SELECT * FROM `quotation_details` WHERE `quotation_detail_product_id` = '$product_id'";
        //$result_quotation_detail = $conn->query($sql_quotation_detail);

            $current_date = date('Y-m-d H:i:s');
            $sql_update_user = "UPDATE `users` SET `user_status` = '0', `user_updated_at` = '$current_date' WHERE `user_id` = '$user_id'";
            $result_update_user = $conn->query($sql_update_user);

            if ($result_update_user) {
                $_SESSION['message'] = alert('Employee has been deleted', 'success');
            } else {
                $_SESSION['message'] = alert('Failed to delete employee', 'danger');
            }
            redirect('employee.php');
        
    } 

            

    else {
        $_SESSION['message'] = alert('Product not found', 'danger');
        redirect('product.php');
    }
} else {
    $_SESSION['message'] = alert('Product not found', 'danger');
    redirect('product.php');
}
