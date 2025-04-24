<?php
$title = 'Profit And Loss';
include 'layout/header.php';
include 'reportfunction.php';

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    
    <div class="card card-outline-primary shadow mb-4" id="report-form">
        <form action="reportfunction.php" method="post">
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
                                        <td><input type="number" name="other" class="form-control col-lg-6 col-12" id="other" placeholder="Enter Other Expenses" value="<?php echo number_format($other,2); ?>">
                                            <button type="button" class="btn btn-primary mt-2" onclick="submitForm()">Submit</button></td>
                                            <!--<button type="submit" name="submitExpenses" class="btn btn-primary mt-2">Submit</button>-->
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
    // Submit form and redirect
    function submitForm() {
        var otherValue = document.getElementById("other").value;
        window.location.href = 'reportfinance.php?other=' + otherValue;
    }
</script>


