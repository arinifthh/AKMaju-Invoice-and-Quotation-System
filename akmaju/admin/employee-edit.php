<?php
$title = 'Edit Employee Details';
include 'layout/header.php';
require_once '../config/db.php';


if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $sql_employee = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
    $result_employee = $conn->query($sql_employee);

    if ($result_employee->num_rows > 0) {
        $employee = $result_employee->fetch_assoc();
        $employee_name = $employee['user_name'];
        $employee_email = $employee['user_email'];
        $employee_ic = $employee['user_ic'];
        $employee_role = $employee['user_role'];
        $employee_status = $employee['user_status'];
        // Add other employee fields as needed
    } else {
        $_SESSION['message'] = alert('Employee not found', 'danger');
        redirect('employee.php');
    }
} else {
    $_SESSION['message'] = alert('Employee not found', 'danger');
    redirect('employee.php');
}

$sql_user = "SELECT * FROM `users`";
$result_user = $conn->query($sql_user);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = [];

    // name

    if (empty($_POST['fullname'])) 
    {
        $error['fullname'] = 'Employee Name is required';
    } 
    else 
    {
        $employee_name = $_POST['fullname'];
        // Check for duplicate names if needed
    }

    // ic

    if (empty($_POST['ic'])) 
    {
        $error['ic'] = 'Identity Card Number is required';
    } 
    else if (strlen($_POST['ic']) != 12) 
    {
        $error['ic'] = 'Identity Card Number must be 12 digits';
    } 
    else if (!is_numeric($_POST['ic'])) 
    {
        $error['ic'] = 'Identity Card Number must be numeric';
    }
    else 
    {
        $employee_ic = $_POST['ic'];
    }

    //email


    if (empty($_POST['email'])) 
    {
        $error['email'] = 'Email is required';
    } 
    else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
    {
        $error['email'] = 'Invalid email format';
    }
    else 
    {
        $employee_email = $_POST['email'];
    }


    if (empty($_POST['roleString'])) 
    {
        $error['roleString'] = 'Employee Role is required';
    } 
    else 
    {
        $employee_role = $_POST['roleString'];
        $role = 0;
        if ($employee_role=="Admin")
        $role=1;
        else if ($employee_role=="Staff")
        $role=2;
    }


    // Add validations for other employee fields

    if (empty($error)) {
        $sql_employee = "UPDATE `users` SET `user_name`='$employee_name', `user_email`='$employee_email', `user_ic`='$employee_ic', `user_role`='$role' WHERE `user_id` = '$user_id'";

        if ($conn->query($sql_employee) === TRUE) {
            $_SESSION['message'] = alert('Employee has been updated successfully', 'success');
        } else {
            $_SESSION['message'] = alert('Something Went Wrong: ' . $conn->error, 'danger');
        }

        redirect('employee.php');
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
                        <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter Employee Full Name" value="<?=$employee_name?>">
                        <?php if (isset($error['fullname'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['fullname'] ?></small>
                        <?php endif ?>
                
                </div>
                
                <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label for="fullname" class="form-label">Identity Card Number</label>
                    <input type="text" name="ic" class="form-control" placeholder="Enter Your IC Number Without Dash (-)" value="<?=$employee_ic?>">
                        <?php if (isset($error['ic'])) : ?>
                            <span class="text-danger"><?= $error['ic'] ?></span>
                        <?php endif; ?>
                </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Enter Employee Email" value="<?=$employee_email?>">
                        <?php if (isset($error['email'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['email'] ?></small>
                        <?php endif ?>
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
            text: "You do not want to modify this employee's detail?",
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