<?php
require_once '../config/db.php';

if (!isset($_SESSION['admin'])) {
    redirect('../login.php');
} else {
    $admin = $_SESSION['admin'];
}

if (isset($_GET['quotation_id'])) {
    $quotation_id = $_GET['quotation_id'];

    $sql_quotation = "SELECT * FROM `quotations` WHERE `quotation_id` = '$quotation_id'";
    $result_quotation = $conn->query($sql_quotation);

    if ($result_quotation->num_rows > 0) {
        $quotation = $result_quotation->fetch_assoc();

        // check invoice
        $sql_invoice = "SELECT * FROM `invoices` WHERE `invoice_quotation_id` = '$quotation_id' AND `invoice_status`=1";
        $result_invoice = $conn->query($sql_invoice);

        if ($result_invoice->num_rows > 0) {
            $_SESSION['message'] = alert('Quotation cannot be deleted because it has been converted to an invoice', 'danger');
            redirect('quotation.php');
        } else {
            // Start a transaction
            $conn->begin_transaction();

            // Update quotation status
            $current_date = date('Y-m-d H:i:s');
            $sql_update_quotation = "UPDATE `quotations` SET `quotation_status` = '0', `quotation_deleted_at` = '$current_date' WHERE `quotation_id` = '$quotation_id'";
            $result_update_quotation = $conn->query($sql_update_quotation);

            if (!$result_update_quotation) {
                $conn->rollback();
                $_SESSION['message'] = alert('Failed to delete quotation', 'danger');
                redirect('quotation.php');
            }

            // Deduct stock quantity
            $sql_quotation_details = "SELECT * FROM `quotation_details` WHERE `quotation_detail_quotation_id` = '$quotation_id'";
            $result_quotation_details = $conn->query($sql_quotation_details);

            while ($quotation_detail = $result_quotation_details->fetch_assoc()) {
                $quotation_detail_product_id = $quotation_detail['quotation_detail_product_id'];
                $quotation_detail_quantity = $quotation_detail['quotation_detail_quantity'];

                $sql_update_product = "UPDATE `products` SET `product_updated_quantity` = `product_updated_quantity` + '$quotation_detail_quantity' WHERE `product_id` = '$quotation_detail_product_id'";
                $result_update_product = $conn->query($sql_update_product);

                if (!$result_update_product) {
                    $conn->rollback();
                    $_SESSION['message'] = alert('Failed to update product quantity', 'danger');
                    redirect('quotation.php');
                }
            }

            // Commit the transaction if all queries were successful
            $conn->commit();

            $_SESSION['message'] = alert('Quotation has been deleted');
            redirect('quotation.php');
        }
    } else {
        $_SESSION['message'] = alert('Quotation not found', 'danger');
        redirect('quotation.php');
    }
}