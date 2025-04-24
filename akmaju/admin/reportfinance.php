<?php
$title = 'Financial Report';
include 'layout/header.php';
include 'reportfunction.php';

$other = isset($_GET['other']) ? (double)$_GET['other'] : 0.00;
$net = $profit - $other;
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card card-outline-primary shadow mb-4" id="report-form">
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
                                <td><?php echo number_format($totalSales, 2); ?></td>
                            </tr>
                            <tr>
                                <td>COST OF SALES</td>
                                <td>
                                    <?php echo number_format($costOfSales, 2); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>GROSS PROFIT</td>
                                <td><?php echo number_format($profit, 2); ?></td>
                            </tr>
                            <tr>
                                <td>OTHER EXPENSES</td>
                                <td><?php echo number_format($other, 2); ?></td>
                            </tr>
                            <tr>
                                <td>NET PROFIT</td>
                                <td><?php echo number_format($net, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary" onclick="printPage()">Print</button>
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