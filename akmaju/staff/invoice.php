<?php
$title = 'Invoice';
include 'layout/header.php';

$sql_quotation = "SELECT * FROM `quotations` JOIN `invoices` ON `quotations`.`quotation_id` = `invoices`.`invoice_quotation_id`";
$result_quotation = $conn->query($sql_quotation);
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if (isset($_SESSION['message'])) : ?>
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead class="thead-warning" style="background-color: maroon; color: white;">
                        <tr>
                            <th scope="col">Quotation ID</th>
                            <th scope="col">Quotation Date</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Customer Address</th>
                            <th scope="col">Customer Phone Number</th>
                            <th scope="col">Customer Email</th>
                            <th scope="col">Customer Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_quotation as $quotation) : ?>
                            <?php
                            $sql_customer = "SELECT *
                            FROM `customers`
                            JOIN `states` ON `customers`.`customer_state_id` = `states`.`state_id`
                            WHERE `customer_id` = " . $quotation['quotation_customer_id'];
                            $result_customer = $conn->query($sql_customer);
                            $customer = $result_customer->fetch_assoc();
                            ?>
                            <tr>
                                <td><?= $quotation['quotation_id'] ?></td>
                                <td><?= date('d/m/Y h:i:s A', strtotime($quotation['quotation_date'])) ?></td>
                                <td><?= $customer['customer_name'] ?></td>
                                <td><?= $customer['customer_address'] . "<br>" . $customer['customer_postcode'] . " " . $customer['customer_city'] . ",<br>" . $customer['state_name'] ?></td>
                                <td> <a href="https://wa.me/60<?= $customer['customer_phone'] ?>" target="_blank">0<?= $customer['customer_phone'] ?></a></td>
                                <td><?= $customer['customer_email'] ?></td>
                                <td><?= $customer['customer_type'] ?></td>
                                <td>
                                    <?php if ($quotation['invoice_status'] == '0') : ?>
                                        <span class="badge badge-danger">Deleted</span>
                                    <?php elseif ($quotation['invoice_status'] == '1') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- dropdown action -->
                                    <div class="dropdown mb-4">
                                        <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            Action
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="invoice-detail.php?quotation_id=<?= $quotation['quotation_id'] ?>">View Detail</a>
                                            <?php if ($quotation['invoice_status'] == '1') : ?>
                                                 <a class="dropdown-item" href="do-details.php?quotation_id=<?= $quotation['quotation_id'] ?>">View DO</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>
    function invoiceDelete(quotation_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "invoice-delete.php?quotation_id=" + quotation_id;
            }
        })
    }
</script>