<?php
// Include necessary database connection code
require_once '../config/db.php';

// Retrieve the quotation_id from the query string
$quotationId = $_GET['quotation_id'];

// Set invoice_payment_pdf to null in the database
$sql = "UPDATE invoices SET invoice_payment_pdf = null WHERE invoice_quotation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $quotationId);
$stmt->execute();
$stmt->close();

// Redirect back to the original page
header('Location: payment.php');
exit();
?>
