<?php
require_once '../config/db.php';
if (!isset($_SESSION['admin'])) {
    redirect('../login.php');
} else {
    $admin = $_SESSION['admin'];
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql_product = "SELECT * FROM `products` WHERE `product_id` = '$product_id'";
    $result_product = $conn->query($sql_product);

    if ($result_product->num_rows > 0) {
        $product = $result_product->fetch_assoc();

        // check in quotations SELECT `quotation_detail_id`, `quotation_detail_quotation_id`, `quotation_detail_product_id`, `quotation_detail_quantity`, `quotation_detail_selling_price`, `quotation_detail_discount_percent`, `quotation_detail_discount_amount`, `quotation_detail_tax_code`, `quotation_detail_total` FROM `quotation_details` WHERE 1
        $sql_quotation_detail = "SELECT * FROM `quotation_details` WHERE `quotation_detail_product_id` = '$product_id'";
        $result_quotation_detail = $conn->query($sql_quotation_detail);

        if ($result_quotation_detail->num_rows > 0) {
            $_SESSION['message'] = alert('Product cannot be deleted because it has been used in quotation', 'danger');
            redirect('product.php');
        } else {
            $current_date = date('Y-m-d H:i:s');
            $sql_update_product = "UPDATE `products` SET `product_status` = '0', `product_updated_at` = '$current_date' WHERE `product_id` = '$product_id'";
            $result_update_product = $conn->query($sql_update_product);

            if ($result_update_product) {
                $_SESSION['message'] = alert('Product has been deleted', 'success');
            } else {
                $_SESSION['message'] = alert('Failed to delete product', 'danger');
            }
            redirect('product.php');
        }
    } 

            

    else {
        $_SESSION['message'] = alert('Product not found', 'danger');
        redirect('product.php');
    }
} else {
    $_SESSION['message'] = alert('Product not found', 'danger');
    redirect('product.php');
}
