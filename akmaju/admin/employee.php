<?php
$title = 'Employee';
include 'layout/header.php';

$sql_employee = "SELECT * FROM `users`";
$result_employee = $conn->query($sql_employee);

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
                <form class="form-inline col-12 ">
                    <a class="btn btn-outline-dark" href="employee-add.php" role="button">
                        Add New Customer</a>
                </form>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['message'])) : ?>
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']) ?>
            <?php endif; ?>
            <div class='table-responsive'>

                <table class="table table-hover table-sm" id="dataTable">
                    <thead class="thead-warning" style="background-color: maroon; color: white;">
                        <tr>
                            <th scope="col">IC</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th class="text-center" scope="col" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_employee as $employee) : ?>
                            <tr>
                                <td><?= $employee['user_ic'] ?></td>
                                <td><?= $employee['user_name'] ?></td>
                                <td><?= $employee['user_email'] ?></td>
                                
                                    <?php if ($employee['user_role'] == 1) : ?>
                                        <td>Admin</td>
                                    <?php else : ?>
                                        <td>Staff</td>
                                    <?php endif; ?>
                                
                                <td>
                                    <?php if ($employee['user_status'] == 1) : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                <div class="dropdown mb-4">
                                        <a class="btn btn-outline-dark dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            Action
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="employee-edit.php?user_id=<?= $employee['user_id'] ?>" style="text-decoration: none;">Edit Employee Details</a>
                                            <br>
                                            <a href="javascript:void(0);" onclick="employeeDelete(<?= $employee['user_id'] ?>)" style="text-decoration:none;"> Delete Employee
                                            </a>
                                        </div>
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
    function employeeDelete(user_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this employee?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "employee-delete.php?user_id=" + user_id;
            }
        })
    }
</script>