<?php
$title = 'Profit And Loss';
include 'layout/header.php';

$totalSales = calculateTotalSales();
$costOfSales = calculateTotalCost();
$profit = $totalSales - $costOfSales;
$other = 0.00;
$net = 0.00;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     
    if(isset($_POST['submitExpenses'])){
        $other = (double)$_POST['other'];
        $net = $profit - $other;
    }
}

function calculateTotalSales() {
    global $conn;

    $desiredMonth = date('m');
    $desiredYear = date('Y');
    
    // Query to fetch the sum of quotation total from the quotation_details table
    $sqlQuotation = "SELECT SUM(qd.quotation_detail_total) AS quotation_total FROM quotation_details qd
    INNER JOIN quotations q ON q.quotation_id = qd.quotation_detail_quotation_id
    WHERE q.quotation_status = 1
    AND MONTH(q.quotation_date) = $desiredMonth AND YEAR (q.quotation_date)=$desiredYear";

    // Execute the query for quotation total
    $resultQuotation = $conn->query($sqlQuotation);
    $rowQuotation = $resultQuotation->fetch_assoc();
    $quotationTotal = (double) $rowQuotation['quotation_total'];

    // Query to fetch the sum of delivery total from the invoice table
    $sqlInvoice = "SELECT SUM(invoice_payment_delivery_fee) AS invoice_total FROM invoices WHERE MONTH(invoice_payment_created_at)=$desiredMonth AND YEAR (invoice_payment_created_at)=$desiredYear" ;

    // Execute the query for invoice total
    $resultInvoice = $conn->query($sqlInvoice);
    $rowInvoice = $resultInvoice->fetch_assoc();
    $invoiceTotal = (double) $rowInvoice['invoice_total'];

    // Calculate the total sales
    return $quotationTotal + $invoiceTotal;
}

function calculateTotalCost() {
    global $conn;
    $sqlProduct = "SELECT SUM(product_cost_price*product_quantity) AS product_cost FROM products";

    $resultProduct = $conn->query($sqlProduct);
    $rowProduct = $resultProduct->fetch_assoc();
    $productTotal = (double) $rowProduct['product_cost'];

    return $productTotal;
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profit And Loss Summary</h1>
        <div class="card-footer">
    <button type="button" class="btn btn-primary" onclick="printPage()">Print</button>
</div>
    </div>
    <div class="card card-outline-primary shadow mb-4" id="report-form">
        <form action="" method="post">
            <div class="card">
                <div class="form-row">
                    <div class="col-2">
                        <img src="<?= base_url('assets/img/logo2.jpg') ?>" alt="Company Logo" class="img-fluid logo-company">
                    </div>
                    <div class="col-10 mt-3">
                        <h5>Profit And Loss (Summary) ENDED <?php echo date('Y-m-d'); ?></h5>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="product-table">
                            <thead>
                                <tr>
                                    <th scope="col">ACCOUNT NAME</th>
                                    <th scope="col">AMOUNT (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>SALES</td>
                                    <td><?php echo number_format ($totalSales,2); ?></td>
                                </tr>
                                <tr>
                                    <td>COST OF SALES</td>
                                    <td>
                                        <?php echo number_format ($costOfSales,2); ?>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td>GROSS PROFIT</td>
                                        <td><?php echo number_format($profit,2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>OTHER EXPENSES</td>
                                        <td><input type="number" name="other" class="form-control col-lg-6 col-12" id="cost" placeholder="Enter Other Expenses" value="<?php echo number_format($other,2); ?>">
                                            <button type="submit" name="submitExpenses" class="btn btn-primary mt-2">Submit</button></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>NET PROFIT</td>
                                            <td><?php echo number_format($net,2); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<script>
    // Print
    function printPage() {
        var printContents = document.getElementById("report-form").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = '<html><head><title>Print</title></head><body>' + printContents + '</body></html>';

        window.print();

        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>


