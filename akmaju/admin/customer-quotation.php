<?php
$title = 'Customer Quotation';
include 'layout/header.php';
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    $sql_customer = "SELECT * FROM `customers`
                        JOIN `states` ON `customers`.`customer_state_id` = `states`.`state_id`
                            WHERE `customer_id` = '$customer_id'";
    $result_customer = $conn->query($sql_customer);

    if ($result_customer->num_rows > 0) {
        $customer = $result_customer->fetch_assoc();
        $customer_name = $customer['customer_name'];
        $customer_address = $customer['customer_address'];
        $customer_postcode = $customer['customer_postcode'];
        $customer_city = $customer['customer_city'];
        $customer_state_id = $customer['customer_state_id'];
        $customer_phone = $customer['customer_phone'];
        $customer_email = $customer['customer_email'];
        $customer_type = $customer['customer_type'];
    } else {
        $_SESSION['message'] = alert('Customer not found', 'danger');
        redirect('customer.php');
    }
} else {
    $_SESSION['message'] = alert('Customer not found', 'danger');
    redirect('customer.php');
}

$sql_quotation = "SELECT * FROM `quotations` WHERE `quotation_customer_id` = '$customer_id' ORDER BY `quotation_id` DESC";
$result_quotation = $conn->query($sql_quotation);
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <div class="card-title">
                <h5>Customer Information
                    <!-- <a href="customer-edit.php?customer_id=<?= $customer_id ?>" class="btn btn-outline-dark float-right">Edit</a> -->
                </h5>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-sm-2">
                    <span class="font-weight-bold pt-3 mt-3">Customer Name</span><span class="float-right">:</span>
                </div>
                <div class="col-sm-6">
                    <span class="font-weight-bold pt-3 mt-3"><?= $customer['customer_name'] ?></span>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-2">
                    <span class="font-weight-bold pt-3 mt-3">Customer Phone</span><span class="float-right">:</span>
                </div>
                <div class="col-sm-6">
                    <span class="font-weight-bold pt-3 mt-3"><a href="https://wa.me/60<?= $customer['customer_phone'] ?>" target="_blank">0<?= $customer['customer_phone'] ?></a></span>
                </div>
                <div class="col-sm-1">
                    <span class="font-weight-bold pt-3 mt-3">Email</span><span class="float-right">:</span>
                </div>
                <div class="col-sm-3">
                    <span class="font-weight-bold pt-3 mt-3"><?= $customer['customer_email'] ?></span>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-2">
                    <span class="font-weight-bold pt-3 mt-3">Customer Address</span><span class="float-right">:</span>
                </div>
                <div class="col-sm-5">
                    <span class="font-weight-bold pt-3 mt-3"><?= $customer['customer_address'] . "<br>" . $customer['customer_postcode'] . " " . $customer['customer_city'] . ",<br>" . $customer['state_name'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
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
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($result_quotation as $quotation) : ?>
                            <tr>
                                <td><?= $quotation['quotation_id'] ?></td>
                                <td><?= date('d/m/Y h:i:s A', strtotime($quotation['quotation_date'])) ?></td>
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
                                                <?php
                                                $invoice = $result->fetch_assoc();
                                                ?>
                                                <?php if ($invoice['invoice_payment_pdf'] != null) : ?>
                                                <a class="dropdown-item" href="viewpdf.php?quotation_id=<?= $quotation['quotation_id'] ?>" target="_blank">View Payment Proof</a>
                                                <?php endif; ?>
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

<script>
function redirectToPaymentDetail(quotationId) {
    // Set the quotation_id value in the search bar
    document.getElementById('searchBar').value = quotationId;

    // Trigger the search
    document.getElementById('searchForm').submit();
}
</script>