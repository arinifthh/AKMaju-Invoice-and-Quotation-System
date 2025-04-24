<?php
require_once '../config/db.php';

$totalSales = calculateTotalSales();
$costOfSales = calculateTotalCost();
$profit = $totalSales - $costOfSales;
$other = 0.00;
$net = 0.00;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitExpenses'])) {
        echo "<script>window.location.href = 'reportfinance.php?other=$other';</script>";
        exit();

    }
}

function calculateTotalSales() {
    global $conn;
    
    $sqlQuotation = "SELECT SUM(qd.quotation_detail_total) AS quotation_total 
                FROM quotation_details qd
                INNER JOIN quotations q ON q.quotation_id = qd.quotation_detail_quotation_id
                INNER JOIN invoices i ON i.invoice_quotation_id = q.quotation_id
                WHERE i.invoice_payment_pdf IS NOT NULL";

    $resultQuotation = $conn->query($sqlQuotation);
    $rowQuotation = $resultQuotation->fetch_assoc();
    $quotationTotal = (double) $rowQuotation['quotation_total'];

    $sqlInvoice = "SELECT SUM(invoice_payment_delivery_fee) AS invoice_total FROM invoices WHERE invoice_payment_pdf IS NOT NULL";

    $resultInvoice = $conn->query($sqlInvoice);
    $rowInvoice = $resultInvoice->fetch_assoc();
    $invoiceTotal = $rowInvoice['invoice_total'];

    // Calculate the total sales
    return $quotationTotal + $invoiceTotal;
}

function calculateTotalCost() {
    global $conn;
    $sqlProduct = "SELECT SUM((product_cost_price)*(product_quantity)) AS product_cost FROM products WHERE product_status=1 ";

    $resultProduct = $conn->query($sqlProduct);
    $rowProduct = $resultProduct->fetch_assoc();
    $productTotal = (double) $rowProduct['product_cost'];
    return $productTotal;
}

?>

