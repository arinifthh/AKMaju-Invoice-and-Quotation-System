<?php
$title = 'Profile';
include 'layout/header.php';
$user_id = $_SESSION['admin']['user_id'];

$sql_user = "SELECT * FROM `users` WHERE `users`.`user_id` = '$user_id'"; // `users`.`user_id` = '$user_id'
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

$sql_states = "SELECT * FROM `states`";
$result_states = $conn->query($sql_states);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $ic = $_POST['ic'];

    $error = [];

    if (empty($name)) {
        $error['name'] = 'Name is required';
    } else if (strlen($name) < 3) {
        $error['name'] = 'Name must be at least 3 characters';
    } else if (strlen($name) > 50) {
        $error['name'] = 'Name must be less than 50 characters';
    } else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $error['name'] = 'Name must be alphabets only';
    }

    if (empty($email)) {
        $error['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Email is invalid';
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_email` = '$email' AND `user_id` != '$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error['email'] = 'Email already exist';
        }
    }

    if (empty($ic)) {
        $error['ic'] = 'IC is required';
    } else if (!preg_match("/^[0-9]*$/", $ic)) {
        $error['ic'] = 'IC must be numbers only';
    } else if (strlen($ic) != 12) {
        $error['ic'] = 'IC must be 12 characters';
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_ic` = '$ic' AND `user_id` != '$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error['ic'] = 'IC already exist';
        }
    }

    if (empty($error)) {
        $sql_update = "UPDATE `users` SET `user_name` = '$name', `user_email` = '$email', `user_ic` = '$ic' WHERE `user_id` = '$user_id'";
        $conn->query($sql_update);

        $_SESSION['message'] = alert('Profile has been updated successfully', 'success');
        redirect('profile.php');
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
            <h4 class="card-title">
                Profile Information
            </h4>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <?php if (isset($_SESSION['message'])) : ?>
                    <?= $_SESSION['message'] ?>
                    <?php unset($_SESSION['message']) ?>
                <?php endif; ?>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" id="name" class="form-control <?= isset($error['name']) ? 'is-invalid' : '' ?>" value="<?= $user['user_name'] ?>">
                        <?php if (isset($error['name'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['name'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-4">
                        <input type="text" name="email" id="email" class="form-control <?= isset($error['email']) ? 'is-invalid' : '' ?>" value="<?= $user['user_email'] ?>">
                        <?php if (isset($error['email'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['email'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ic" class="col-sm-2 col-form-label">IC</label>
                    <div class="col-sm-4">
                        <input type="text" name="ic" id="ic" class="form-control <?= isset($error['ic']) ? 'is-invalid' : '' ?>" value="<?= $user['user_ic'] ?>">
                        <?php if (isset($error['ic'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['ic'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>