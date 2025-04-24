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
        $sql_invoice = "SELECT * FROM `invoices` WHERE `invoice_quotation_id` = '$quotation_id' AND `invoice_status` = '1'";
        $result_invoice = $conn->query($sql_invoice);

        if ($result_invoice->num_rows > 0) {
            // update invoice status to deleted
            $current_date = date('Y-m-d H:i:s');
            $sql_update_invoice = "UPDATE `invoices` SET `invoice_status` = '0', `invoice_deleted_at` = '$current_date' WHERE `invoice_quotation_id` = '$quotation_id'";
            $result_update_invoice = $conn->query($sql_update_invoice);
            $_SESSION['message'] = alert('Invoice has been deleted', 'success');
            redirect('invoice.php');
        } else {
            $_SESSION['message'] = alert('Invoice not found', 'danger');
            redirect('invoice.php');
        }
    } else {
        $_SESSION['message'] = alert('Quotation not found', 'danger');
        redirect('invoice.php');
    }
}
