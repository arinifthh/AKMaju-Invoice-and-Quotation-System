<?php
$title = 'Customer Info';
include 'layout/header.php';

$sql_customer = "SELECT * FROM `customers`";
$result_customer = $conn->query($sql_customer);


?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" id="customerInfo">
                <div class="card-header">
                    <h2>Customer Info</h2> <br>
                    <a class="btn btn-outline-dark" href="customer-add.php" role="button">
                        Add New Customer</a>
                </div>
                <div class="card-body">
                    <div class='table-responsive'>

                        <table class="table table-hover" id="dataTable">
                            <thead class="thead-warning" style="background-color: maroon; color: white;">
                                <tr>
                                    <th scope="col">Customer ID</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Customer Address</th>
                                    <th scope="col">Customer Phone Number</th>
                                    <th scope="col">Customer Email</th>
                                    <th scope="col">Customer Type</th>
                                    <th scope="col">Customer Order</th>
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
                                        <td>
                                            <a href="customer-quotation.php?customer_id=<?= $customer['customer_id'] ?>" class="btn btn-primary">View Order</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>
    // customerInfo
    function printCustomer() {
        var printContents = document.getElementById("customerInfo").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>