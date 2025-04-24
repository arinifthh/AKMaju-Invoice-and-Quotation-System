<?php require_once 'config/db.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ic = $_POST['ic'];
    $password = $_POST['password'];

    $error = [];

    if (empty($ic)) {
        $error['ic'] = 'IC Number is required';
    } else if (strlen($ic) != 12) {
        $error['ic'] = 'IC Number must be 12 digits';
    }

    if (empty($password)) {
        $error['password'] = 'Password is required';
    }
    if (empty($error)) {
        $sql = "SELECT * FROM users WHERE user_ic = '$ic'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['user_password'])) {
                // check role
                if ($user['user_role'] == '1') {
                    $_SESSION['admin'] = $user;
                    redirect('admin/index.php');
                } else if ($user['user_role'] == '2') {
                    $_SESSION['staff'] = $user;
                    redirect('staff/index.php');
                } else {
                    $_SESSION['message'] = alert('Invalid IC Number or Password', 'danger');
                }
            } else {
                $_SESSION['message'] = alert('Invalid IC Number or Password', 'danger');
            }
        } else {
            $_SESSION['message'] = alert('Invalid IC Number or Password', 'danger');
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

    <title>Login</title>
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
                                                <legend>Login</legend>
                                                <div class="form-group">
                                                    <label>Identity Card Number</label>
                                                    <input type="text" name="ic" class="form-control form-control-user" id="ic" placeholder="Enter your IC Number" autocomplete="off" value="<?= isset($ic) ? $ic : '' ?>">
                                                    <?php if (isset($error['ic'])) : ?>
                                                        <small class="text-danger font-weight-bold"><?= $error['ic'] ?></small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="form-group">
                                                    <label>Password</label>
                                                
                                                    <input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Enter your password" autocomplete="off">
                                                    <?php if (isset($error['password'])) : ?>
                                                        <small class="text-danger font-weight-bold"><?= $error['password'] ?></small>
                                                    <?php endif; ?>
                                                    
                                                    <button type="button" id="togglePassword" class="btn btn-quatenary btn-sm">Show Password</button>
                                                    
                                                
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Login</button>
                                                    <button type="reset" class="btn btn-dark">Reset</button>
                                                </div>
                                                <div class="form-group">
                                                    <a href="forgot-password.php">Forgot Password?</a>
                                                    
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
        var togglePasswordButton = document.getElementById('togglePassword');

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