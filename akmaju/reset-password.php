<?php require_once 'config/db.php'; ?>
<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 30 minutes
    $current_date = date('Y-m-d H:i:s');
    $sql = "SELECT * FROM `password_resets` WHERE `password_reset_token` = '$token' AND `password_reset_status` = '1' AND `password_reset_created_at` >= DATE_SUB('$current_date', INTERVAL 30 MINUTE)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $password_reset = $result->fetch_assoc();

        $user_id = $password_reset['password_reset_user_id'];

        $sql_user = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
        $result_user = $conn->query($sql_user);

        if ($result_user->num_rows > 0) {
            $user = $result_user->fetch_assoc();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                $error = [];

                if (empty($password)) {
                    $error['password'] = 'Password is required';
                } else if (strlen($password) < 8) {
                    $error['password'] = 'Password must be at least 8 characters';
                }

                if (empty($confirm_password)) {
                    $error['confirm_password'] = 'Confirm Password is required';
                } else if ($password != $confirm_password) {
                    $error['confirm_password'] = 'Confirm Password must be same as Password';
                }

                if (empty($error)) {
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    $sql_update = "UPDATE `users` SET `user_password` = '$password' WHERE `user_id` = '$user_id'";
                    $conn->query($sql_update);

                    // $sql_delete = "DELETE FROM `password_resets` WHERE `password_reset_user_id` = '$user_id'";
                    // update
                    $sql_delete = "UPDATE `password_resets` SET `password_reset_status` = '0' WHERE `password_reset_user_id` = '$user_id'";
                    $conn->query($sql_delete);

                    $_SESSION['message'] = alert('Password has been reset successfully', 'success');
                    redirect('login.php');
                }
            }
        } else {
            $_SESSION['message'] = alert('Invalid token', 'danger');
            redirect('login.php');
        }
    } else {
        $_SESSION['message'] = alert('Invalid token', 'danger');
        redirect('login.php');
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

    <title>Password Reset</title>
    <!-- Custom fonts for this template-->
    <link rel="icon" href="assets/img/logo2.jpg" type="image/x-icon">
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class=container>

        <form method="POST" action="">
            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">AK Maju Resources</h1>
                                        </div>
                                        <?php if (isset($_SESSION['message'])) : ?>
                                            <?= $_SESSION['message'] ?>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <fieldset>
                                                <legend>Reset Password</legend>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" name="password" id="password" class="form-control <?= isset($error['password']) ? 'is-invalid' : '' ?>" placeholder="Password">
                                                    <?php if (isset($error['password'])) : ?>
                                                        <div class="invalid-feedback">
                                                            <?= $error['password'] ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <button type="button" id="togglePassword1" class="btn btn-quatenary btn-sm">Show Password</button>
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= isset($error['confirm_password']) ? 'is-invalid' : '' ?>" placeholder="Confirm Password">
                                                    <?php if (isset($error['confirm_password'])) : ?>
                                                        <div class="invalid-feedback">
                                                            <?= $error['confirm_password'] ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <button type="button" id="togglePassword2" class="btn btn-quatenary btn-sm">Show Password</button>
                                                </div>

                                                    
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>

    <script src="<?= base_url('assets/js/script.js') ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('password');
        var togglePasswordButton = document.getElementById('togglePassword1');

        togglePasswordButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.textContent = 'Show Password';
            }
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('confirm_password');
        var togglePasswordButton = document.getElementById('togglePassword2');

        togglePasswordButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.textContent = 'Show Password';
            }
        });
    });
    </script>
</body>

</html>