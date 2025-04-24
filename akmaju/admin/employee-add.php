<?php
$title = 'Add Employee';
include 'layout/header.php';
require_once '../config/db.php';

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
            $_SESSION['message'] = alert('Employee added successfully', 'success');
            
        } else {
            $_SESSION['message'] = alert('Failed to add employee', 'danger');
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
        <div class="card-body">
            <form method="POST" action="">
                <?php if (isset($_SESSION['message'])) : ?>
                    <?= $_SESSION['message'] ?>
                    <?php unset($_SESSION['message']) ?>
                <?php endif ?>
                <div class="form-group">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter Employee Full Name" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>">
                        <?php if (isset($error['fullname'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['fullname'] ?></small>
                        <?php endif ?>
                
                </div>
                
                <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label for="fullname" class="form-label">Identity Card Number</label>
                    <input type="text" name="ic" class="form-control" placeholder="Enter Your IC Number Without Dash (-)" value="<?= isset($ic) ? $ic : '' ?>">
                        <?php if (isset($error['ic'])) : ?>
                            <span class="text-danger"><?= $error['ic'] ?></span>
                        <?php endif; ?>
                </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Enter Employee Email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                        <?php if (isset($error['email'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['email'] ?></small>
                        <?php endif ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                       <label for="password">Password</label>
                         <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            <?php if (isset($error['password'])) : ?>
                                <span class="text-danger"><?= $error['password'] ?></span>
                            <?php endif; ?>
                            <button type="button" id="togglePassword1" class="btn btn-quatenary btn-sm">Show Password</button>

                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                            <?php if (isset($error['confirm_password'])) : ?>
                                <span class="text-danger"><?= $error['confirm_password'] ?></span>
                            <?php endif; ?>
                            <button type="button" id="togglePassword2" class="btn btn-quatenary btn-sm">Show Confirm Password</button>

                    </div>

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

                <div class="form-group">
                    <a href="javascript:void(0);" onclick="goBack()" class="btn btn-secondary"> Back</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>


<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>


<script>
    function goBack(product_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You do not want to add this employee?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "employee.php";
            }
        })
    }
</script>

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
                togglePasswordButton.textContent = 'Show Confirm Password';
            }
        });
    });
</script>           