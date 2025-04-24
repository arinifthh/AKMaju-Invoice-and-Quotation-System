<?php
require_once '../config/db.php';
header('Content-Type: application/json');
if (!isset($_SESSION['admin'])) {
    redirect('../login.php');
} else {
    $admin = $_SESSION['admin'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'create-invoice') {

        $payment_type = $_POST['payment_type'];
        $delivery_fee = $_POST['delivery_fee'];
        $total_price = $_POST['total_price'];
        $quotation_id = $_POST['quotation_id'];


        $sql_quotation = "SELECT * FROM quotations WHERE quotation_id = '$quotation_id'";
        $result_quotation = $conn->query($sql_quotation);

        if ($result_quotation->num_rows > 0) {
            $quotation = $result_quotation->fetch_assoc();

            // check if invoice already created
            $sql_invoice = "SELECT * FROM invoices WHERE invoice_quotation_id = '$quotation_id' AND invoice_status = '1'";
            $result_invoice = $conn->query($sql_invoice);

            if ($result_invoice->num_rows > 0) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Invoice already created'
                ]);
                exit;
            }

            $sql_invoice = "INSERT INTO `invoices` (`invoice_quotation_id`, `invoice_payment_method`, `invoice_payment_delivery_fee`, `invoice_payment_status`) VALUES ('$quotation_id', '$payment_type', '$delivery_fee', '0')";
            $conn->query($sql_invoice);
            echo json_encode([
                'status' => true,
                'message' => 'Invoice created'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Invoice not created'
            ]);
        }
    } else if ($action == 'update-invoice') {
        $invoice_id = $_POST['invoice_id'];
        $payment_type = $_POST['payment_type'];
        $delivery_fee = $_POST['delivery_fee'];
        $total_price = $_POST['total_price'];
        $quotation_id = $_POST['quotation_id'];

        $sql_invoice = "SELECT * FROM invoices WHERE invoice_id = '$invoice_id'";
        $result_invoice = $conn->query($sql_invoice);

        if ($result_invoice->num_rows > 0) {
            $invoice = $result_invoice->fetch_assoc();

            // check if invoice already created
            $sql_invoice = "UPDATE `invoices` SET `invoice_payment_method` = '$payment_type', `invoice_payment_delivery_fee` = '$delivery_fee' WHERE `invoice_id` = '$invoice_id'";
            $conn->query($sql_invoice);
            echo json_encode([
                'status' => true,
                'message' => 'Invoice updated'
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Invoice not updated'
            ]);
        }
    }
}
