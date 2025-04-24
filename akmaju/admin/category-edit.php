<?php
$title = 'Edit Category';
include 'layout/header.php';

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $sql_category = "SELECT * FROM `product_category` WHERE `product_category_id` = '$category_id'";
    $result_category = $conn->query($sql_category);

    if ($result_category->num_rows > 0) {
        $category = $result_category->fetch_assoc();
        $category_name = $category['product_category_name'];
        $category_status = $category['product_category_status'];
    } else {
        $_SESSION['message'] = alert('Category not found', 'danger');
        redirect('category.php');
    }
} else {
    $_SESSION['message'] = alert('Category not found', 'danger');
    redirect('category.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $category_status = $_POST['category_status'];

    $error = [];

    if (empty($category_name)) {
        $error['category_name'] = 'Category Name is required';
    } elseif (strlen($category_name) < 3 || strlen($category_name) > 50 || !preg_match("/^[a-zA-Z ]*$/", $category_name)) {
        $error['category_name'] = 'Category Name must be between 3 and 50 characters and contain only alphabets';
    } else {
        $stmt = $conn->prepare("SELECT * FROM `product_category` WHERE `product_category_name` = ? AND `product_category_id` != ?");
        $stmt->bind_param("si", $category_name, $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error['category_name'] = 'Category Name already exists';
        }
    }

    if ($category_status === '') {
        $error['category_status'] = 'Category Status is required';
    } elseif (!in_array($category_status, ['0', '1'])) {
        $error['category_status'] = 'Invalid Category Status';
    }

    if (empty($error)) {
        $stmt_update = $conn->prepare("UPDATE `product_category` SET `product_category_name` = ?, `product_category_status` = ? WHERE `product_category_id` = ?");
        $stmt_update->bind_param("ssi", $category_name, $category_status, $category_id);
        $stmt_update->execute();

        $_SESSION['message'] = alert('Category has been updated successfully', 'success');
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
                        <option value="1" <?= isset($category_status) && $category_status == '1' ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= isset($category_status) && $category_status == '0' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                    <?php if (isset($error['category_status'])) : ?>
                        <div class="invalid-feedback">
                            <?= $error['category_status'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <button type="reset" class="btn btn-dark">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>