<?php
$title = 'Change Password';
include 'layout/header.php';
$user_id = $_SESSION['admin']['user_id'];

$sql_user = "SELECT * FROM `users` WHERE `users`.`user_id` = '$user_id'"; // `users`.`user_id` = '$user_id'
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

$sql_states = "SELECT * FROM `states`";
$result_states = $conn->query($sql_states);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $error = [];

    if (empty($current_password)) {
        $error['current_password'] = 'Current Password is required';
    }

    if (empty($new_password)) {
        $error['new_password'] = 'New Password is required';
    } else if (strlen($new_password) < 6) {
        $error['new_password'] = 'New Password must be at least 6 characters';
    } else if (strlen($new_password) > 50) {
        $error['new_password'] = 'New Password must be less than 50 characters';
    } else if (!preg_match('/[A-Z]/', $new_password)) {
        $error['new_password'] = 'New Password must contain at least one uppercase letter';
    } else if (!preg_match('/[a-z]/', $new_password)) {
        $error['new_password'] = 'New Password must contain at least one lowercase letter';
    } else if (!preg_match('/[0-9]/', $new_password)) {
        $error['new_password'] = 'New Password must contain at least one number';
    } else if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $new_password)) {
        $error['new_password'] = 'New Password must contain at least one special character';
    }

    if (empty($confirm_password)) {
        $error['confirm_password'] = 'Confirm Password is required';
    }

    if ($new_password != $confirm_password) {
        $error['confirm_password'] = 'Confirm Password must be same as New Password';
    }

    if (empty($error)) {
        $sql_user = "SELECT * FROM `users` WHERE `users`.`user_id` = '$user_id'";
        $result_user = $conn->query($sql_user);
        $user = $result_user->fetch_assoc();

        if (password_verify($current_password, $user['user_password'])) {
            $new_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql_update = "UPDATE `users` SET `user_password` = '$new_password' WHERE `users`.`user_id` = '$user_id'";
            $conn->query($sql_update);

            $_SESSION['message'] = alert('Password has been changed successfully', 'success');
            redirect('change-password.php');
        } else {
            $error['current_password'] = 'Current Password is incorrect';
        }
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
                Change Password
            </h4>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <?php if (isset($_SESSION['message'])) : ?>
                    <?= $_SESSION['message'] ?>
                    <?php unset($_SESSION['message']) ?>
                <?php endif; ?>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control <?= isset($error['current_password']) ? 'is-invalid' : '' ?>" placeholder="Current Password" value="<?= isset($current_password) ? $current_password : '' ?>">
                    <?php if (isset($error['current_password'])) : ?>
                        <div class="invalid-feedback">
                            <?= $error['current_password'] ?>
                        </div>
                    <?php endif; ?>
                    <button type="button" id="togglePassword1" class="btn btn-quatenary btn-sm">Show Current Password</button>
 
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control <?= isset($error['new_password']) ? 'is-invalid' : '' ?>" placeholder="New Password" value="<?= isset($new_password) ? $new_password : '' ?>">
                    <?php if (isset($error['new_password'])) : ?>
                        <div class="invalid-feedback">
                            <?= $error['new_password'] ?>
                        </div>
                    <?php endif; ?>
                    <button type="button" id="togglePassword2" class="btn btn-quatenary btn-sm">Show New Password</button>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= isset($error['confirm_password']) ? 'is-invalid' : '' ?>" placeholder="Confirm Password">
                    <?php if (isset($error['confirm_password'])) : ?>
                        <div class="invalid-feedback">
                            <?= $error['confirm_password'] ?>
                        </div>
                    <?php endif; ?>
                    <button type="button" id="togglePassword3" class="btn btn-quatenary btn-sm">Show Confirm Password</button>
                </div>
                <div class="form-group">
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#current_password').focus();
    });

    // when type remove invalid class
    $('#current_password').keyup(function() {
        $(this).removeClass('is-invalid');
    });

    $('#new_password').keyup(function() {
        $(this).removeClass('is-invalid');
    });

    $('#confirm_password').keyup(function() {
        $(this).removeClass('is-invalid');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('current_password');
        var togglePasswordButton = document.getElementById('togglePassword1');

        togglePasswordButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.textContent = 'Show Current Password';
            }
        });
    });
    </script>   
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('new_password');
        var togglePasswordButton = document.getElementById('togglePassword2');

        togglePasswordButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.textContent = 'Show New Password';
            }
        });
    });
    </script> 
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.getElementById('confirm_password');
        var togglePasswordButton = document.getElementById('togglePassword3');

        togglePasswordButton.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePasswordButton.textContent = 'Hide Password';
            } else {
                passwordInput.type = 'password';
                togglePasswordButton.textContent = 'Show Confirm Password';
            }
        });
    });
    </script> 