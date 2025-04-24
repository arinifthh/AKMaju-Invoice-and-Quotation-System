<?php
$title = 'Add Category';
include 'layout/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $category_status = $_POST['category_status'];

    $error = [];

    if (empty($category_name)) {
        $error['category_name'] = 'Category Name is required';
    } else if (strlen($category_name) < 3) {
        $error['category_name'] = 'Category Name must be at least 3 characters';
    } else if (strlen($category_name) > 50) {
        $error['category_name'] = 'Category Name must be less than 50 characters';
    } else if (!preg_match("/^[a-zA-Z ]*$/", $category_name)) {
        $error['category_name'] = 'Category Name must be alphabets only';
    } else {
        $sql = "SELECT * FROM `product_category` WHERE `product_category_name` = '$category_name'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error['category_name'] = 'Category Name already exist';
        }
    }

    if (empty($category_status)) {
        $error['category_status'] = 'Category Status is required';
    }

    if (empty($error)) {
        $sql_insert = "INSERT INTO `product_category` (`product_category_name`, `product_category_status`) VALUES ('$category_name', '$category_status')";
        $conn->query($sql_insert);

        $_SESSION['message'] = alert('Category has been added successfully', 'success');
        redirect('category.php');
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
        <div class="card-header">
            <div class="card-title">
                Category Information
            </div>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" name="category_name" id="category_name" class="form-control <?= isset($error['category_name']) ? 'is-invalid' : '' ?>" placeholder="Category Name" value="<?= isset($category_name) ? $category_name : '' ?>">
                    <?php if (isset($error['category_name'])) : ?>
                        <div class="invalid-feedback">
                            <?= $error['category_name'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="category_status">Category Status</label>
                    <select name="category_status" id="category_status" class="form-control <?= isset($error['category_status']) ? 'is-invalid' : '' ?>">
                        <option value="">-- Select Category Status --</option>
                        <option value="1" <?= isset($category_status) && $category_status == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= isset($category_status) && $category_status == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <?php if (isset($error['category_status'])) : ?>
                        <div class="invalid-feedback">
                            <?= $error['category_status'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                    <button type="reset" class="btn btn-dark">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>