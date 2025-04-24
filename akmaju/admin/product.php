<?php
$title = 'Product';
include 'layout/header.php';

$sql_product = "SELECT * FROM `products` JOIN `product_category` ON `products`.`product_category_id` = `product_category`.`product_category_id`";
$result_product = $conn->query($sql_product);

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="form-group row ">
                <form class="form-inline col-12 ">
                    <a class="btn btn-outline-dark col-2 " data-bs-toggle="offcanvas" href="product-add.php" role="button" aria-controls="offcanvasExample">
                        Add New Product</a>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['message'])) : ?>
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']) ?>
            <?php endif; ?>
            <div class='table-responsive'>

                <table class="table table-hover table-sm" id="dataTable">
                    <thead class="thead-warning" style="background-color: maroon; color: white;">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Category</th>
                            <th scope="col">Cost Price /unit</th>
                            <th scope="col">Selling Price /unit</th>
                            <th scope="col">Tax Code</th>
                            <th scope="col">Tax Amount</th>
                            <th scope="col">Discount percent</th>
                            <th scope="col">Discount Amount</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Status</th>
                            <th class="text-center" scope="col" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_product as $product) : ?>
                            <tr>
                                <td><?= $product['product_id'] ?></td>
                                <td><?= $product['product_name'] ?></td>
                                <td><?= $product['product_description'] ?></td>
                                <td><?= $product['product_category_name'] ?></td>
                                <td><?= $product['product_cost_price'] ?></td>
                                <td><?= $product['product_selling_price'] ?></td>
                                <td><?= $product['product_tax_code'] ?></td>
                                <td><?= $product['product_tax_amount'] ?></td>
                                <td><?= $product['product_discount_percent'] ?></td>
                                <td><?= $product['product_discount_amount'] ?></td>
                                <td><?= $product['product_updated_quantity'] ?></td>
                                <td>
                                    <?php if ($product['product_status'] == 1) : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                <div class="dropdown mb-4">
                                        <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            Action
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="product-edit.php?product_id=<?= $product['product_id'] ?>" style="text-decoration: none;">Edit Item</a>
                                            <br>
                                            <a href="javascript:void(0);" onclick="productDelete(<?= $product['product_id'] ?>)" style="text-decoration:none;"> Delete Item
                                            </a>
                                        </div>
                                </td>

                                    
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>
<script>
    function productDelete(product_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this product?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "product-delete.php?product_id=" + product_id;
            }
        })
    }
</script>