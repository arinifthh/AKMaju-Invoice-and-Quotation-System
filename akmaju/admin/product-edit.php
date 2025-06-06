<?php
$title = 'Edit Product';
include 'layout/header.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql_product = "SELECT * FROM `products` WHERE `product_id` = '$product_id'";
    $result_product = $conn->query($sql_product);

    if ($result_product->num_rows > 0) {
        $product = $result_product->fetch_assoc();
        $product_name = $product['product_name'];
        $product_category_id = $product['product_category_id'];
        $product_cost_price = $product['product_cost_price'];
        $product_selling_price = $product['product_selling_price'];
        $product_tax_code = $product['product_tax_code'];
        $product_tax_amount = $product['product_tax_amount'];
        $product_discount_percent = $product['product_discount_percent'];
        $product_discount_amount = $product['product_discount_amount'];
        $product_quantity = $product['product_quantity'];
        $product_status = $product['product_status'];
        $product_description = $product['product_description'];
    } else {
        $_SESSION['message'] = alert('Product not found', 'danger');
        redirect('product.php');
    }
} else {
    $_SESSION['message'] = alert('Product not found', 'danger');
    redirect('product.php');
}

$sql_category = "SELECT * FROM `product_category`";
$result_category = $conn->query($sql_category);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = [];
    if (empty($_POST['product_name'])) {
        $error['product_name'] = 'Product Name is required';
    } else {
        $product_name = $_POST['product_name'];

        $sql = "SELECT * FROM `products` WHERE `product_name` = '$product_name' AND `product_id` != '$product_id'";
        $result = $conn->query($sql);
    }
    if (empty($_POST['product_category_id'])) {
        $error['product_category_id'] = 'Product Category is required';
    } else {
        $product_category_id = $_POST['product_category_id'];
    }
    if (empty($_POST['product_cost_price'])) {
        $error['product_cost_price'] = 'Product Cost Price is required';
    } else {
        $product_cost_price = $_POST['product_cost_price'];
    }
    if (empty($_POST['product_selling_price'])) {
        $error['product_selling_price'] = 'Product Selling Price is required';
    } else {
        $product_selling_price = $_POST['product_selling_price'];
    }

    $product_tax_code = $_POST['product_tax_code'];

    if ($_POST['product_tax_amount'] == '') {
        $error['product_tax_amount'] = 'Product Tax Amount is required';
    } else {
        $product_tax_amount = $_POST['product_tax_amount'];
    }
    if ($_POST['product_discount_percent'] == '') {
        $error['product_discount_percent'] = 'Product Discount Percent is required';
    } else {
        $product_discount_percent = $_POST['product_discount_percent'];
    }
    if ($_POST['product_discount_amount'] == '') {
        $error['product_discount_amount'] = 'Product Discount Amount is required';
    } else {
        $product_discount_amount = $_POST['product_discount_amount'];
    }
    if (empty($_POST['product_quantity'])) {
        $error['product_quantity'] = 'Product Quantity is required';
    } else {
        $product_quantity = $_POST['product_quantity'];
    }
    if ($_POST['product_status'] == '') {
        $error['product_status'] = 'Product Status is required';
    } else {
        $product_status = $_POST['product_status'];
    }
    if (empty($_POST['product_description'])) {
        $error['product_description'] = 'Product Description is required';
    } else {
        $product_description = $_POST['product_description'];
    }
    if (empty($error)) {
        $sql_product = "UPDATE `products` SET `product_category_id`='$product_category_id',`product_name`='$product_name',`product_description`='$product_description',`product_cost_price`='$product_cost_price',`product_selling_price`='$product_selling_price',`product_tax_code`='$product_tax_code',`product_tax_amount`='$product_tax_amount',`product_discount_percent`='$product_discount_percent',`product_discount_amount`='$product_discount_amount',`product_quantity`='$product_quantity',`product_updated_quantity`='$product_quantity',`product_status`='$product_status' WHERE `product_id` = '$product_id'";
        if ($conn->query($sql_product) === TRUE) {
            $_SESSION['message'] = alert('Product has been updated successfully', 'success');
        } else {
            $_SESSION['message'] = alert('Something Went Wrong: ' . $conn->error, 'danger');
        }
        redirect('product.php');
    }
}
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
                <?php unset($_SESSION['message']) ?>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group col-lg-6 col-12">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" value="<?= isset($product_name) ? $product_name : '' ?>">
                        <?php if (isset($error['product_name'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_name'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-lg-6 col-12">
                        <label for="product_category_id">Product Category</label>
                        <select class="form-control" id="product_category_id" name="product_category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($result_category as $category) : ?>
                                <option value="<?= $category['product_category_id'] ?>" <?= isset($product_category_id) && $product_category_id == $category['product_category_id'] ? 'selected' : '' ?>><?= $category['product_category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($error['product_category_id'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_category_id'] ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-6 col-12">
                        <label for="product_cost_price">Product Cost Price</label>
                        <input type="number" class="form-control" id="product_cost_price" name="product_cost_price" placeholder="Enter Product Cost Price" value="<?= isset($product_cost_price) ? $product_cost_price : '' ?>" step="0.01">
                        <?php if (isset($error['product_cost_price'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_cost_price'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-lg-6 col-12">
                        <label for="product_selling_price">Product Selling Price</label>
                        <input type="number" class="form-control" id="product_selling_price" name="product_selling_price" placeholder="Enter Product Selling Price" value="<?= isset($product_selling_price) ? $product_selling_price : '' ?>" step="0.01">
                        <?php if (isset($error['product_selling_price'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_selling_price'] ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-3 col-6">
                        <label for="product_tax_code">Product Tax Code</label>
                        <input type="text" class="form-control" id="product_tax_code" name="product_tax_code" placeholder="Enter Product Tax Code" value="<?= isset($product_tax_code) ? $product_tax_code : '' ?>">
                        <?php if (isset($error['product_tax_code'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_tax_code'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-lg-3 col-6">
                        <label for="product_tax_amount">Product Tax Amount</label>
                        <input type="number" class="form-control" id="product_tax_amount" name="product_tax_amount" placeholder="Enter Product Tax Amount" value="<?= isset($product_tax_amount) ? $product_tax_amount : '' ?>">
                        <?php if (isset($error['product_tax_amount'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_tax_amount'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-lg-3 col-6">
                        <label for="product_discount_percent">Product Discount Percent</label>
                        <input type="number" class="form-control" id="product_discount_percent" name="product_discount_percent" placeholder="Enter Product Discount Percent" value="<?= isset($product_discount_percent) ? $product_discount_percent : '' ?>">
                        <?php if (isset($error['product_discount_percent'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_discount_percent'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-lg-3 col-6">
                        <label for="product_discount_amount">Product Discount Amount</label>
                        <input type="number" class="form-control" id="product_discount_amount" name="product_discount_amount" placeholder="Enter Product Discount Amount" value="<?= isset($product_discount_amount) ? $product_discount_amount : '' ?>">
                        <?php if (isset($error['product_discount_amount'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_discount_amount'] ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-6 col-12">
                        <label for="product_quantity">Product Quantity</label>
                        <input type="number" class="form-control" id="product_quantity" name="product_quantity" placeholder="Enter Product Quantity" value="<?= isset($product_quantity) ? $product_quantity : '' ?>">
                        <?php if (isset($error['product_quantity'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_quantity'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-lg-6 col-12">
                        <label for="product_status">Product Status</label>
                        <select class="form-control" id="product_status" name="product_status">
                            <option value="">Select Status</option>
                            <option value="1" <?= isset($product_status) && $product_status == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= isset($product_status) && $product_status == 0 ? 'selected' : '' ?>>Inactive</option>
                        </select>
                        <?php if (isset($error['product_status'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['product_status'] ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product_description">Product Description</label>
                    <textarea class="form-control" id="product_description" name="product_description" rows="3"><?= isset($product_description) ? $product_description : '' ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <button type="reset" class="btn btn-dark">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>