<?php
$title = 'Quotation';
include 'layout/header.php';

$sql_quotation = "SELECT * FROM `quotations` ORDER BY `quotation_id` DESC";
$result_quotation = $conn->query($sql_quotation);
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>
    <div class="card">
        <div class="card-header">
            <a class="btn btn-outline-dark" href="quotation-add.php" role="button">
                Add New Quotation</a>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['message'])) : ?>
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="quotationTable">
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
                        <?php $i = 1; ?>
                        <?php foreach ($result_quotation as $quotation) : ?>
                            <?php
                            $sql_customer = "SELECT * FROM `users` 
                                                JOIN `customers` ON `users`.`user_id` = `customers`.`customer_user_id`
                                                JOIN `states` ON `customers`.`customer_state_id` = `states`.`state_id`
                                                    WHERE `customer_id` = '" . $quotation['quotation_customer_id'] . "'";
                            $result_customer = $conn->query($sql_customer);
                            $customer = $result_customer->fetch_assoc();
                            ?>
                            <tr>
                                <td><?= $quotation['quotation_id'] ?></td>
                                <td><?= date('d/m/Y h:i:s A', strtotime($quotation['quotation_date'])) ?></td>
                                <td><?= $customer['user_name'] ?></td>
                                <td><?= $customer['customer_address'] . "<br>" . $customer['customer_postcode'] . " " . $customer['customer_city'] . ",<br>" . $customer['state_name'] ?></td>
                                <td> <a href="https://wa.me/60<?= $customer['customer_phone'] ?>" target="_blank">0<?= $customer['customer_phone'] ?></a></td>
                                <td><?= $customer['user_email'] ?></td>
                                <td><?= $customer['customer_type'] ?></td>
                                <td>
                                    <?php if ($quotation['quotation_deleted_at'] != NULL) : ?>
                                        <span class="badge badge-secondary">Deleted</span>
                                    <?php elseif ($quotation['quotation_status'] == '1') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php elseif ($quotation['quotation_status'] == '0') : ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="dropdown mb-4">
                                        <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            Action
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="quotation-detail.php?quotation_id=<?= $quotation['quotation_id'] ?>">View Detail Quotation</a>
                                            <?php
                                            $sql = "SELECT * FROM `invoices` WHERE `invoice_quotation_id` = '" . $quotation['quotation_id'] . "' AND `invoice_status` = '1'";
                                            $result = $conn->query($sql);
                                            ?>
                                            <?php if ($result->num_rows > 0) : ?>
                                                <a class="dropdown-item" href="invoice-detail.php?quotation_id=<?= $quotation['quotation_id'] ?>">View Detail Invoice</a>
                                            <?php elseif ($quotation['quotation_status'] == '1') : ?>
                                                <a class="dropdown-item" href="quotation-edit.php?quotation_id=<?= $quotation['quotation_id'] ?>">Edit</a>
                                                <a class="dropdown-item" href="invoice-add.php?quotation_id=<?= $quotation['quotation_id'] ?>">Create Invoice</a>
                                                <button type="button" class="dropdown-item" onclick="quotationDelete(<?= $quotation['quotation_id'] ?>)">Delete</button>
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
    function quotationDelete(quotation_id) {
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
                window.location.href = "quotation-delete.php?quotation_id=" + quotation_id;
            }
        })
    }

    $(document).ready(function() {
        $('#quotationTable').DataTable({
            "order": [
                [0, "desc"]
            ]
        });
    });
</script>