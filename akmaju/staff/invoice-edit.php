<?php
$title = 'Update Invoice';
include 'layout/header.php';

if (isset($_GET['quotation_id'])) {
    $quotation_id = $_GET['quotation_id'];

    $sql_quotation = "SELECT * FROM `quotations` WHERE `quotation_id` = '$quotation_id'";
    $result_quotation = $conn->query($sql_quotation);

    if ($result_quotation->num_rows > 0) {
        $quotation = $result_quotation->fetch_assoc();
    } else {
        $_SESSION['message'] = alert('Quotation not found', 'danger');
        redirect('quotation.php');
    }
}


$sql_customer = "SELECT * FROM `users` 
                    JOIN `customers` ON `users`.`user_id` = `customers`.`customer_user_id`
                    JOIN `states` ON `customers`.`customer_state_id` = `states`.`state_id`
                        WHERE `customer_id` = '" . $quotation['quotation_customer_id'] . "'";
$result_customer = $conn->query($sql_customer);
$customer = $result_customer->fetch_assoc();


$sql_quotation_detail = "SELECT * FROM `quotation_details` 
                            JOIN `products` ON `quotation_details`.`quotation_detail_product_id` = `products`.`product_id`
                                WHERE `quotation_detail_quotation_id` = '" . $quotation['quotation_id'] . "'";
$result_quotation_detail = $conn->query($sql_quotation_detail);
$total = 0;

$sql_invoice = "SELECT * FROM `invoices` WHERE `invoice_quotation_id` = '$quotation_id'";
$result_invoice = $conn->query($sql_invoice);

if ($result_invoice->num_rows > 0) {
    $invoice = $result_invoice->fetch_assoc();
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title . " - " . $quotation['quotation_id'] ?></h1>
    </div>
    <div class="card card-outline-primary shadow mb-4" id="quotation-form">
        <form action="" method="post">
            <div class="card">
                <div class="form-row">
                    <div class="col-2">
                        <img src="<?= base_url('assets/img/logo.jpg') ?>" alt="Company Logo" class="img-fluid logo-company">
                    </div>
                    <div class="col-10 mt-3">
                        <p>AK MAJU RESOURCES SDN. BHD.<br>
                            No. 39 & 41, Jalan Utama 3/2, Pusat Komersial Sri Utama, Segamal, Johor, Malaysia- 85000</p>
                        <p>Phone: 07-9310717, 010-2218224 | Email: akmaju.acc@gmail.com</p>
                        <p>Company No: 1088436</p>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <div class="card-title">
                        <h5>Customer Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-sm-2">
                            <span class="font-weight-bold pt-3 mt-3">Customer Name</span><span class="float-right">:</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="font-weight-bold pt-3 mt-3"><?= $customer['user_name'] ?></span>
                        </div>
                        <div class="col-sm-1">
                            <span class="font-weight-bold pt-3 mt-3">Date</span><span class="float-right">:</span>
                        </div>
                        <div class="col-sm-3">
                            <span class="font-weight-bold pt-3 mt-3"><?= date('d/m/Y h:i:s A', strtotime($quotation['quotation_date'])) ?></span>
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
                            <span class="font-weight-bold pt-3 mt-3"><?= $customer['user_email'] ?></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-2">
                            <span class="font-weight-bold pt-3 mt-3">Customer Address</span><span class="float-right">:</span>
                        </div>
                        <div class="col-sm-5">
                            <span class="font-weight-bold pt-3 mt-3"><?= $customer['customer_address'] . "<br>" . $customer['customer_postcode'] . " " . $customer['customer_city'] . ",<br>" . $customer['state_name'] ?></span>
                        </div>
                        <div class="col-sm-2 text-center">
                            <span class="font-weight-bold pt-3 mt-3">Quotation No.</span><span class="float-right">:</span>
                        </div>
                        <div class="col-sm-3">
                            <span class="font-weight-bold pt-3 mt-3"><?= $quotation['quotation_id'] ?></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <!-- payment Type -->
                        <div class="col-sm-2">
                            <span class="font-weight-bold pt-3 mt-3">Payment Type</span><span class="float-right">:</span>
                        </div>
                        <div class="col-sm-5">
                            <select name="payment_type" id="payment-type" class="form-control">
                                <option value="">Please select payment type</option>
                                <option value="Cash" <?= $invoice['invoice_payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                                <option value="Transfer" <?= $invoice['invoice_payment_method'] == 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                                <option value="Local Order" <?= $invoice['invoice_payment_method'] == 'Local Order' ? 'selected' : '' ?>>Local Order</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="product-table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Discount(%)</th>
                                    <th scope="col">Discount Amount</th>
                                    <th scope="col">Tax Code</th>
                                    <th scope="col">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result_quotation_detail as $quotation_detail) : ?>
                                    <tr>
                                        <td><?= $quotation_detail['product_name'] ?></td>
                                        <td><?= $quotation_detail['quotation_detail_quantity'] ?></td>
                                        <td><?= number_format($quotation_detail['product_selling_price'], 2) ?></td>
                                        <td><?= number_format($quotation_detail['product_discount_percent'], 2) ?></td>
                                        <td><?= number_format($quotation_detail['product_discount_amount'], 2) ?></td>
                                        <td><?= $quotation_detail['product_tax_code'] ?></td>
                                        <td><?= number_format($quotation_detail['quotation_detail_quantity'] * $quotation_detail['product_selling_price'], 2) ?></td>
                                    </tr>
                                    <?php $total += $quotation_detail['quotation_detail_quantity'] * $quotation_detail['product_selling_price']; ?>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right">Subtotal</td>
                                    <td id="subtotal"><?= number_format($total, 2) ?></td>
                                    <input type="hidden" name="subtotal" id="subtotal-input" value="<?= $total ?>">
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right">Delivery Fee</td>
                                    <td id="delivery-fee">
                                        <div>
                                            <input type="number" name="delivery_fee" id="delivery-fee-input" class="form-control" value="<?= $invoice['invoice_payment_delivery_fee'] ?>" step="0.01" style="width: 100px;">
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right">Total Price</td>
                                    <td id="total-price"><?= number_format($total, 2) ?></td>
                                    <input type="hidden" name="total_price" id="total-price-input" value="<?= $total ?>">
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" name="invoice_id" id="invoice-id" value="<?= $invoice['invoice_id'] ?>">
                    <button type="button" class="btn btn-primary" id="create-invoice">Update Invoice</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>
    // ready function
    $(document).ready(function() {
        var subtotal = $('#subtotal-input').val();
        var delivery_fee = $('#delivery-fee-input').val();
        if (delivery_fee == '') {
            delivery_fee = 0;
        }
        var total_price = parseFloat(subtotal) + parseFloat(delivery_fee);
        $('#total-price').html(total_price.toFixed(2));
        $('#total-price-input').val(total_price.toFixed(2));
    });

    // print quotation
    $('#generate-quotation').click(function() {
        var printContents = document.getElementById("quotation-form").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        // hide button
        $('#generate-quotation').hide();
        window.print();
        $('#generate-quotation').show();
        document.body.innerHTML = originalContents;
    });

    // onkeyup delivery fee
    $('#delivery-fee-input').keyup(function() {
        var subtotal = $('#subtotal-input').val();
        var delivery_fee = $('#delivery-fee-input').val();
        if (delivery_fee == '') {
            delivery_fee = 0;
        }
        var total_price = parseFloat(subtotal) + parseFloat(delivery_fee);
        $('#total-price').html(total_price.toFixed(2));
        $('#total-price-input').val(total_price.toFixed(2));
    });

    // create invoice
    $('#create-invoice').click(function() {
        var invoice_id = $('#invoice-id').val();
        var payment_type = $('#payment-type').val();
        var delivery_fee = $('#delivery-fee-input').val();
        var total_price = $('#total-price-input').val();
        var quotation_id = '<?= $quotation_id ?>';
        if (payment_type == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select payment type',
            })
        } else {
            $.ajax({
                url: 'quotation-request.php',
                type: 'post',
                data: {
                    invoice_id: invoice_id,
                    payment_type: payment_type,
                    delivery_fee: delivery_fee,
                    total_price: total_price,
                    quotation_id: quotation_id,
                    action: 'update-invoice'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'invoice.php';
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        }
    });
</script>