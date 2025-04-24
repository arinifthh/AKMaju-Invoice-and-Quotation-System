<?php
$title = 'Stock Balance';
include 'layout/header.php';

$sql_product = "SELECT * FROM `products` JOIN `product_category` ON `products`.`product_category_id` = `product_category`.`product_category_id`             WHERE `products`.`product_status` = 1";
$result_product = $conn->query($sql_product);
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
            <h3 class="text-center" >STOCK BALANCE</h3>
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="dataTable">
                    <thead class="thead-warning">
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">Product Name</th>
                            <th class="text-center" scope="col">Description</th>
                            <th class="text-center" scope="col">Category</th>
                            <th class="text-center" scope="col">Received Quantity</th>
                            <th class="text-center" scope="col">Used Quantity</th>
                            <th class="text-center" scope="col">Balance Quantity</th>
                            <th class="text-center" scope="col">Buying Price</th>
                            <th class="text-center" scope="col">Selling Price</th>
                            <th class="text-center" scope="col">Balance Stock Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_product as $product) : ?>
                            <tr>
                                <td class="text-center"><?= $product['product_id'] ?></td>
                                <td class="text-center"><?= $product['product_name'] ?></td>
                                <td class="text-center"><?= $product['product_description'] ?></td>
                                <td class="text-center"><?= $product['product_category_name'] ?></td>
                                <td class="text-center"><?= $product['product_quantity'] ?></td>
                                <td class="text-center"><?= $product['product_quantity'] - $product['product_updated_quantity'] ?></td>
                                <td class="text-center"><?= $product['product_updated_quantity'] ?></td>
                                <td class="text-center">RM<?= number_format(($product['product_cost_price'] * $product['product_quantity']), 2) ?></td>
                                <td class="text-center">RM<?= number_format(($product['product_selling_price'] * ($product['product_quantity'] - $product['product_updated_quantity'])), 2) ?></td>
                                <td class="text-center">RM<?= number_format(($product['product_updated_quantity'] * $product['product_cost_price']), 2) ?></td>
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
