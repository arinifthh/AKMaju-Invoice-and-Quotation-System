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