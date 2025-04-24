<?php
$title = 'Add Customer';
include 'layout/header.php';

$sql_states = "SELECT * FROM `states`";
$result_states = $conn->query($sql_states);

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];

    if (empty($fullname)) {
        $error['fullname'] = 'Full Name is required';
    }

    if (empty($email)) {
        $error['email'] = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Invalid email format';
    } else {
        $sql = "SELECT * FROM `customers` WHERE `customer_email` = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $error['email'] = 'Email already exist';
        }
    }

    if (empty($phone)) {
        $error['phone'] = 'Phone Number is required';
    }

    if (empty($type)) {
        $error['type'] = 'Customer Type is required';
    }

    $address_validation = [
        'address' => 'Address is required',
        'city' => 'City is required',
        'state' => 'State is required',
        'postcode' => 'Postcode is required'
    ];

    $size_address_validation = 0;
    foreach ($address_validation as $key => $value) 
    {
        if (!empty($_POST[$key])) 
        {
            $error[$key] = $value;
        } 
        else 
        {
            $size_address_validation++;
        }
    }

    if ($size_address_validation != 4) {
        foreach ($address_validation as $key => $value) {
            if (empty($_POST[$key])) {
                $error[$key] = $value;
            }
        }
    }

    if ($size_address_validation != 4) {
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $postcode = $_POST['postcode'];
        $sql_customer = "INSERT INTO `customers` (`customer_phone`, `customer_type`, `customer_address`, `customer_city`, `customer_state_id`, `customer_postcode`,`customer_name`,`customer_email`) 
        VALUES ('$phone', '$type', '$address', '$city', '$state', '$postcode','$fullname','$email')";
    } 

    $result_customer = $conn->query($sql_customer);
    if ($result_customer) {
        $_SESSION['message'] = alert('Customer added successfully', 'success');
    } else {
        $_SESSION['message'] = alert('Failed to add customer', 'danger');
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
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label for="fullname" class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter Customer Full Name" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>">
                        <?php if (isset($error['fullname'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['fullname'] ?></small>
                        <?php endif ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="Enter Customer Email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                        <?php if (isset($error['email'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['email'] ?></small>
                        <?php endif ?>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter Customer Phone Number" value="<?= isset($_POST['phone']) ? $_POST['phone'] : '' ?>">
                        <?php if (isset($error['phone'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['phone'] ?></small>
                        <?php endif ?>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                        <label for="type" class="form-label">Customer Type</label>
                        <select name="type" class="form-control" id="type">
                            <option value="">Select Customer Type</option>
                            <option value="Agency" <?= isset($_POST['type']) && $_POST['type'] == 'Agency' ? 'selected' : '' ?>>Agency</option>
                            <option value="Government" <?= isset($_POST['type']) && $_POST['type'] == 'Government' ? 'selected' : '' ?>>Government</option>
                            <option value="Personal" <?= isset($_POST['type']) && $_POST['type'] == 'Personal' ? 'selected' : '' ?>>Personal</option>
                        </select>
                        <?php if (isset($error['type'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['type'] ?></small>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" class="form-control" id="address" rows="3" placeholder="Enter Customer Address"><?= isset($_POST['address']) ? $_POST['address'] : '' ?></textarea>
                    <?php if (isset($error['address'])) : ?>
                        <small class="text-danger font-weight-bold"><?= $error['address'] ?></small>
                    <?php endif ?>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4 col-12">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="city" placeholder="Enter Customer City" value="<?= isset($_POST['city']) ? $_POST['city'] : '' ?>">
                        <?php if (isset($error['city'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['city'] ?></small>
                        <?php endif ?>
                    </div>
                    <div class="form-group col-lg-4 col-12">
                        <label for="state" class="form-label">State</label>
                        <select name="state" class="form-control" id="state">
                            <option value="">Select Customer State</option>
                            <?php while ($row_states = $result_states->fetch_assoc()) : ?>
                                <option value="<?= $row_states['state_id'] ?>" <?= isset($_POST['state']) && $_POST['state'] == $row_states['state_id'] ? 'selected' : '' ?>><?= $row_states['state_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                        <?php if (isset($error['state'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['state'] ?></small>
                        <?php endif ?>
                    </div>
                    <div class="form-group col-lg-4 col-12">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" name="postcode" class="form-control" id="postcode" placeholder="Enter Customer Postcode" value="<?= isset($_POST['postcode']) ? $_POST['postcode'] : '' ?>">
                        <?php if (isset($error['postcode'])) : ?>
                            <small class="text-danger font-weight-bold"><?= $error['postcode'] ?></small>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-dark">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>
