<?php
$title = 'Quotation Add';
include 'layout/header.php';
// clear session quotation
unset($_SESSION['quotation']);
$sql_customer = "SELECT * FROM `customers`";
$result_customer = $conn->query($sql_customer);

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
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5>Customer Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <label for="customer" class="col-sm-2 col-form-label">Customer Name</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" id="customer" name="customer">
                                <option value="">Select Customer</option>
                                <?php foreach ($result_customer as $customer) : ?>
                                    <option value="<?= $customer['customer_id'] ?>"><?= $customer['customer_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
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
                title: 'Incorrect Input',
                text: 'Please select product',
            })
            return false;
        }

        if (quantity == '') {
            Swal.fire({
                icon: 'error',
                title: 'Incorrect Input',
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
        if (customer_id == '') {
            // alert('Please select customer');
            Swal.fire({
                icon: 'error',
                title: 'Incorrect Input',
                text: 'Please select customer',
            })
            return false;
        }

        $.ajax({
            url: 'product-request.php',
            type: 'post',
            data: {
                customer_id: customer_id,
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
                        title: 'Incorrect Input',
                        text: response.message,
                    })
                }
            },
            error: function(response) {
                swal.fire({
                    icon: 'error',
                    title: 'Sorry...',
                    text: 'Something went wrong!',
                })
                console.log(response);
            }
        });
    });
</script>