<?php
$title = 'Quotation Edit';
include 'layout/header.php';
// clear session quotation
unset($_SESSION['quotation']);
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


$sql_customer = "SELECT * FROM `customers`
                    JOIN `states` ON `customers`.`customer_state_id` = `states`.`state_id`
                        WHERE `customer_id` = '" . $quotation['quotation_customer_id'] . "'";
$result_customer = $conn->query($sql_customer);
$customer = $result_customer->fetch_assoc();


$sql_quotation_detail = "SELECT * FROM `quotation_details` 
                            JOIN `products` ON `quotation_details`.`quotation_detail_product_id` = `products`.`product_id`
                                WHERE `quotation_detail_quotation_id` = '" . $quotation['quotation_id'] . "'";
$result_quotation_detail = $conn->query($sql_quotation_detail);

foreach ($result_quotation_detail as $quotation_detail) {
    $_SESSION['quotation'][$quotation_detail['product_id']] = $quotation_detail['quotation_detail_quantity'];
}


$total = 0;

$sql_product = "SELECT * FROM `products` JOIN `product_category` ON `products`.`product_category_id` = `product_category`.`product_category_id` WHERE `products`.`product_status` = '1'";
$result_product = $conn->query($sql_product);



?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Generate Quotation</h1>
    </div>
    <div class="card card-outline-primary shadow mb-4">
        <form action="" method="post" id="quotation-form">
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
                            <input type="hidden" name="customer_id" id="customer" value="<?= $customer['customer_id'] ?>">
                            <input type="hidden" name="quotation_id" id="quotation_id" value="<?= $quotation['quotation_id'] ?>">
                        </div>
                        <div class="col-sm-6">
                            <span class="font-weight-bold pt-3 mt-3"><?= $customer['customer_name'] ?></span>
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
                        <div class="col-sm-2 text-center">
                            <span class="font-weight-bold pt-3 mt-3">Quotation No.</span><span class="float-right">:</span>
                        </div>
                        <div class="col-sm-3">
                            <span class="font-weight-bold pt-3 mt-3"><?= $quotation['quotation_id'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="form-row">
                        <label for="product" class="col-sm-2 col-form-label">Product</label>
                        <div class="col-sm-3">
                            <select class="form-control select2" id="product" name="product">
                                <option value="">Select Product</option>
                                <?php foreach ($result_product as $product) : ?>
                                    <option value="<?= $product['product_id'] ?>"><?= $product['product_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label for="quantity" class="col-sm-1 col-form-label">Quantity</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity">
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary" id="add-product">Add Product</button>
                        </div>
                    </div>
                </div>
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
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right">Total Price</td>
                                    <td id="total-price">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="quotation.php" class="btn btn-secondary">Back</a>
                    <button type="button" class="btn btn-primary" id="generate-quotation">Generate Quotation</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>
<script>
    $('#add-product').click(function() {

        var product_id = $('#product').val();
        var quantity = $('#quantity').val();

        if (product_id == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select product',
            })
            return false;
        }

        if (quantity == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter quantity',
            })
            return false;
        }

        $.ajax({
            url: 'product-request.php',
            type: 'post',
            data: {
                product_id: product_id,
                quantity: quantity,
                action: 'add'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === false) {
                    alert(response.message);
                }
            },
        });
        displayQuotation();
    });
    displayQuotation();

    function displayQuotation() {
        $.ajax({
            url: 'product-request.php',
            type: 'post',
            data: {
                action: 'list'
            },
            dataType: 'json',
            success: function(response) {
                // above code from ajax 
                $('#product-table tbody').html('');
                var total_price = 0;
                $.each(response, function(index, value) {
                    value['product_total'] = parseFloat(value['product_total']).toFixed(2);
                    $('#product-table tbody').append('<tr><td>' + value['product_name'] + '</td><td>' + value['product_quantity'] + '</td><td>' + value['product_selling_price'] + '</td><td>' + value['product_discount_percent'] + '</td><td>' + value['product_discount_amount'] + '</td><td>' + value['product_tax_code'] + '</td><td>' + value['product_total'] + '</td><td><button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(' + value['product_id'] + ')">Remove</button></td></tr>');
                    total_price += parseFloat(value['product_total']);
                });
                $('#total-price').html(total_price);
            },
            error: function(response) {}
        });
    }

    function removeProduct(product_id) {
        $.ajax({
            url: 'product-request.php',
            type: 'post',
            data: {
                product_id: product_id,
                action: 'remove'
            },
            dataType: 'json',
            success: function(response) {},
            error: function(response) {}
        });
        displayQuotation();
    }

    $('#generate-quotation').click(function() {
        var customer_id = $('#customer').val();
        var quotation_id = $('#quotation_id').val();
        if (customer_id == '') {
            // alert('Please select customer');
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select customer',
            })
            return false;
        }

        $.ajax({
            url: 'product-request.php',
            type: 'post',
            data: {
                customer_id: customer_id,
                quotation_id: quotation_id,
                action: 'generate'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "quotation.php";
                        }
                    })
                } else {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    })
                }
            },
            error: function(response) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
                console.log(response);
            }
        });
    });
</script>
