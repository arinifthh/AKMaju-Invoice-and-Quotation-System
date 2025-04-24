<?php
$title = 'Print Customer Info';
include 'layout/header.php';
require_once '../config/db.php';

$sql_customer = "SELECT * FROM `customers`";
$result_customer = $conn->query($sql_customer);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" id="printCustomerInfo">
                <div class="card-body">
                    <h2>Transaction Listing</h2> <br>
                    <div class='table-responsive'>
                        <table class="table table-hover" id="customerTable">
                            <thead  style="background-color: maroon; color: white;">
                                <tr>
                                    <th scope="col">Customer ID</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Customer Address</th>
                                    <th scope="col">Customer Phone Number</th>
                                    <th scope="col">Customer Email</th>
                                    <th scope="col">Customer Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result_customer as $customer) : ?>
                                    <tr>
                                        <td><?= $customer['customer_id'] ?></td>
                                        <td><?= $customer['customer_name'] ?></td>
                                        <td><?= $customer['customer_address'] ?></td>
                                        <td><?= $customer['customer_phone'] ?></td>
                                        <td><?= $customer['customer_email'] ?></td>
                                        <td><?= $customer['customer_type'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
    <a href="index.php" class="btn btn-secondary" >Back</a>
    <button class="btn btn-primary" type="button" onclick="printCustomerInfo()"> Print Customer Info</button>
</div>

<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>
    // Print customer info
    function printCustomerInfo() {
        var printContents = document.getElementById("printCustomerInfo").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
