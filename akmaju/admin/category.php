<?php
$title = 'Category';
include 'layout/header.php';

$sql_category = "SELECT * FROM `product_category`";
$result_category = $conn->query($sql_category);

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
                    <a class="btn btn-outline-dark col-2 " data-bs-toggle="offcanvas" href="category-add.php" role="button" aria-controls="offcanvasExample">
                        Add New Category</a>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['message'])) : ?>
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']) ?>
            <?php endif; ?>
            <div class='table-responsive'>

                <table class="table table-hover" id="dataTable">
                    <thead class="thead-warning" style="background-color: maroon; color: white;">
                        <tr>
                            <th scope="col">Category ID</th>
                            <th scope="col">Category Name</th>

                            <th scope="col">Status</th>
                            <th class="text-center" scope="col" style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_category as $category) : ?>
                            <tr>
                                <td><?= $category['product_category_id'] ?></td>
                                <td><?= $category['product_category_name'] ?></td>
                                <td>
                                    <?php if ($category['product_category_status'] == 1) : ?>
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
                                            <a href="category-edit.php?category_id=<?= $category['product_category_id'] ?>"  style="text-decoration: none;">Edit Category</a>
                                            <br>
                                    <a href="javascript:void(0);" onclick="categoryDelete(<?= $category['product_category_id'] ?>)"  style="text-decoration: none;"> Delete Category
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
    function categoryDelete(category_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this category?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `category-delete.php?category_id=${category_id}`
            }
        })
    }
</script>