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
        $sql_invoice = "SELECT * FROM `invoices` WHERE `invoice_quotation_id` = '$quotation_id'";
        $result_invoice = $conn->query($sql_invoice);

        if ($result_invoice->num_rows > 0) {
            $_SESSION['message'] = alert('Quotation cannot be deleted because it has been converted to invoice', 'danger');
            redirect('quotation.php');
        } else {
            // update quotation status
            $current_date = date('Y-m-d H:i:s');
            $sql_update_quotation = "UPDATE `quotations` SET `quotation_status` = '0', `quotation_deleted_at` = '$current_date' WHERE `quotation_id` = '$quotation_id'";
            $result_update_quotation = $conn->query($sql_update_quotation);

            if ($result_update_quotation) {
                $_SESSION['message'] = alert('Quotation has been deleted', 'success');
            } else {
                $_SESSION['message'] = alert('Failed to delete quotation', 'danger');
            }
            redirect('quotation.php');
        }
    } else {
        $_SESSION['message'] = alert('Quotation not found', 'danger');
        redirect('quotation.php');
    }
}
