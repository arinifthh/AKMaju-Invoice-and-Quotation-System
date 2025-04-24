<?php
$title = 'Print Customer Info';
include 'layout/header.php';
require_once '../config/db.php';

$sql_customer = "SELECT * FROM `customers` c INNER JOIN `quotations` q ON q.`quotation_customer_id` = c.`customer_id`
INNER JOIN `invoices` i ON i.`invoice_quotation_id` = q.`quotation_id` INNER JOIN `quotation_details` qd ON qd.`quotation_detail_quotation_id` = q.`quotation_id`
WHERE i.invoice_payment_pdf IS NOT NULL";

$result_customer = $conn->query($sql_customer);

?>

<style>
    .text-center {
        text-align: center;
    }
</style>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card card-outline-primary shadow mb-4" id="report-form">
        <div class="card">
            <div class="form-row">
                <div class="col-2">
                    <img src="<?= base_url('assets/img/logo2.jpg') ?>" alt="Company Logo" class="img-fluid logo-company">
                </div>
                <div class="col-10 mt-3">
                    <p>AK MAJU RESOURCES SDN. BHD.<br>
                    No. 39 & 41, Jalan Utama 3/2, Pusat Komersial Sri Utama, Segamal, Johor, Malaysia- 85000</p>
                    <p>Phone: 07-9310717, 010-2218224 | Email: akmaju.acc@gmail.com</p>
                    <p>Company No: 1088436</p>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <h3 class="text-center" >TRANSACTION LISTING</h3>
            <div class="table-responsive">
                <table class="table table-hover" id="customerTable">
                            <thead>
                                <tr>
                                    <th scope="col">Customer ID</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Payment ID</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Transaction Date & Time</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result_customer as $customer) : ?>
                                    <tr>
                                        <td><?= $customer['customer_id'] ?></td>
                                        <td><?= $customer['customer_name'] ?></td>
                                        <td><?= $customer['invoice_id'] ?></td>
                                        <td><?= $customer['invoice_payment_method'] ?></td>
                                        <td><?= $customer['invoice_payment_created_at'] ?></td>
                                        <td>RM<?= number_format($customer['quotation_detail_total'] + $customer['invoice_payment_delivery_fee'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary" onclick="printPage()">Print</button>
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


