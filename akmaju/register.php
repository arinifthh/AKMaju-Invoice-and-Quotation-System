<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $ic = $_POST['ic'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $roleString = $_POST['roleString'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $error = [];

    if (empty($fullname)) {
        $error['fullname'] = 'Full Name is required';
    }

    if (empty($ic)) {
        $error['ic'] = 'Identity Card Number is required';
    } else if (strlen($ic) != 12) {
        $error['ic'] = 'Identity Card Number must be 12 digits';
    } else if (!is_numeric($ic)) {
        $error['ic'] = 'Identity Card Number must be numeric';
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_ic` = '$ic'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $error['ic'] = 'Identity Card Number already exists';
        }
    }

    if (empty($password)) {
        $error['password'] = 'Password is required';
    } else if (strlen($password) < 8) {
        $error['password'] = 'Password must be at least 8 characters long';
    }

    if (empty($confirm_password)) {
        $error['confirm_password'] = 'Confirm Password is required';
    } else if ($password !== $confirm_password) {
        $error['confirm_password'] = 'Passwords do not match';
    }

    if (empty($email)) {
        $error['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Invalid email format';
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_email` = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $error['email'] = 'Email already exists';
        }
    }

    if (empty($roleString)) {
        $error['roleString'] = 'Role is required';
    }
    $role = 0;
    if ($roleString=="Admin")
        $role=1;
    else if ($roleString=="Staff")
        $role=2;

    if (empty($error)) {
        $sql_user = "INSERT INTO `users` (`user_name`, `user_email`, `user_ic`, `user_password`, `user_role`, `user_status`) VALUES ('$fullname', '$email', '$ic', '$hashedPassword', '$role', '1')";
        $result_user = $conn->query($sql_user);

        if ($result_user) {
            $user_id = $conn->insert_id;
            $_SESSION['message'] = '<div class="alert alert-success">User added successfully.</div>';
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['message'] = '<div class="alert alert-danger">Failed to add user.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>
    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container-fluid">

        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">AK Maju Resources</h1>
                                    </div>
                                    <?php if (isset($_SESSION['message'])) : ?>
                                        <?= $_SESSION['message'] ?>
                                        <?php unset($_SESSION['message']) ?>
                                    <?php endif; ?>                                   
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <label for="fullname" class="form-label">Full Name</label>
                                            <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter Your Full Name" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>">
                                            <?php if (isset($error['fullname'])) : ?>
                                            <small class="text-danger font-weight-bold"><?= $error['fullname'] ?></small>
                                            <?php endif ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="fullname" class="form-label">Identity Card Number</label>
                                            <input type="text" name="ic" class="form-control" placeholder="Enter Your IC Number Without Dash (-)" value="<?= isset($ic) ? $ic : '' ?>">
                                            <?php if (isset($error['ic'])) : ?>
                                                <span class="text-danger"><?= $error['ic'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control" placeholder="Enter Your Email Address" value="<?= isset($email) ? $email : '' ?>">
                                            <?php if (isset($error['email'])) : ?>
                                                <span class="text-danger"><?= $error['email'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                            <?php if (isset($error['password'])) : ?>
                                                <span class="text-danger"><?= $error['password'] ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password</label>
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                                            <?php if (isset($error['confirm_password'])) : ?>
                                                <span class="text-danger"><?= $error['confirm_password'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="form-group ">
                                            <select name="roleString" class="form-control">
                                                <option value="">Select Role</option>
                                                <option value="Admin" <?= isset($roleString) && $roleString == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                                <option value="Staff" <?= isset($roleString) && $roleString == 'Staff' ? 'selected' : '' ?>>Staff</option>
                                            </select>
                                            <?php if (isset($error['roleString'])) : ?>
                                                <span class="text-danger"><?= $error['roleString'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Register</button>
                                        <button type="reset" class="btn btn-dark">Reset</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="login.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

</body>

</html>
