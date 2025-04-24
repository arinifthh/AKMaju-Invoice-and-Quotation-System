<?php
$title = 'Payment';
include 'layout/header.php';

$sql_quotation = "SELECT * FROM `quotations` JOIN `invoices` ON `quotations`.`quotation_id` = `invoices`.`invoice_quotation_id`";
$result_quotation = $conn->query($sql_quotation);

$sql_invoice = "SELECT * FROM `invoices`";
$result_invoice = $conn->query($sql_invoice);
$inv = $result_invoice->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <style>
  .upload-form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.upload-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 10px;
  border: 2px dashed #ccc;
  border-radius: 5px;
  cursor: pointer;
  transition: border-color 0.3s;
  width: 200px;
}

.upload-label:hover {
  border-color: #999;
}

.upload-input {
  display: none;
}

.upload-button {
  background-color: maroon;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 8px 16px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.upload-button:hover {
  background-color: #800000;
}

.button-text {
  font-size: 14px;
}
  </style>
</head>
<body>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if (isset($_SESSION['message'])) : ?>
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead class="thead-warning" style="background-color: maroon; color: white;">
                        <tr>
                            <th scope="col">Payment ID</th>
                            <th scope="col">Payment Date</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Customer Phone Number</th>
                            <th scope="col">Payment Type</th>
                            <th scope="col">Payment Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Proof of Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result_quotation && (is_array($result_quotation) || is_object($result_quotation))) {
                        foreach ($result_quotation as $quotation) : ?>
                            <?php
                            $sql_quotation_detail = "SELECT * FROM `quotation_details` 
                                JOIN `products` ON `quotation_details`.`quotation_detail_product_id` = `products`.`product_id`
                                WHERE `quotation_detail_quotation_id` = '" . $quotation['quotation_id'] . "'";
                            $result_quotation_detail = $conn->query($sql_quotation_detail);
                            $total = 0; // Initialize the total variable
                            foreach ($result_quotation_detail as $quotation_detail) :
                                $total += $quotation_detail['quotation_detail_quantity'] * $quotation_detail['product_selling_price'];
                                $sql_customer = "SELECT * FROM `customers`
                                    JOIN `states` ON `customers`.`customer_state_id` = `states`.`state_id`
                                    WHERE `customer_id` = " . $quotation['quotation_customer_id'];
                                $result_customer = $conn->query($sql_customer);
                                $customer = $result_customer->fetch_assoc();
                                ?>
                                <tr>
                                    <?php if($quotation['invoice_status']!=0): ?>
                                    <td><?= $quotation['quotation_id'] ?></td>
                                    <td><?= date('d/m/Y h:i:s A', strtotime($quotation['quotation_date'])) ?></td>
                                    <td><?= $customer['customer_name'] ?></td>
                                    <td><?= $customer['customer_phone'] ?></td>
                                    <td><?= $quotation['invoice_payment_method'] ?></td>
                                    <td id="total-price"><?= number_format($total + $quotation['invoice_payment_delivery_fee'], 2) ?></td>
                                    <td>
                                      <?php if ($quotation['invoice_payment_pdf'] !== null): ?>
                                    <span class="badge badge-success">Paid</span>
                                     <?php else: ?>
                                   <span class="badge badge-danger">Unpaid</span>
                                     <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($quotation['invoice_payment_pdf'] !== null): ?>
                                            <!-- Display filename if a file has been uploaded -->
                                            <button class="upload-button" onclick="viewPDF(<?= $quotation['quotation_id'] ?>)">
                                            <span class="button-text">View Payment Proof</span>
                                            </button>
                                            <br>
                                            <button class="upload-button" type="submit" onclick="confirmReset(<?= $quotation['quotation_id'] ?>)" style="margin-top: 10px;">
                                            <span class="button-text">Change Payment Proof</span>
                                            </button>
                                        <?php else: ?>
                                        <form class="upload-form" action="upload.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(<?= $quotation['quotation_id'] ?>)">
                                            <input type="hidden" name="quotation_id" value="<?= $quotation['quotation_id'] ?>">
                                            <label class="upload-label" for="pdfFile<?= $quotation['quotation_id'] ?>">
                                                <span class="button-text">Choose PDF File</span>
                                                <input class="upload-input" type="file" name="pdfFile[]" id="pdfFile<?= $quotation['quotation_id'] ?>" onchange="displayFileName('pdfFile<?= $quotation['quotation_id'] ?>')">
                                            </label>
                                            <button class="upload-button" type="submit">
                                                <span class="button-text">Upload</span>
                                            </button>
                                        </form>
                                        <?php endif; ?>               
                                    </td>
                                    <?php endif; ?> 
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>
function displayFileName(inputId) {
    const fileInput = document.getElementById(inputId);
    const fileName = fileInput.files[0].name;
    const buttonText = document.querySelector(`[for=${inputId}] .button-text`);

    buttonText.textContent = fileName;
}
</script>

<script>
function viewPDF(quotationId) {
    // Redirect to viewpdf.php with the quotation_id parameter
    window.open(`viewpdf.php?quotation_id=${quotationId}`, '_blank');
}
</script>

<script>
function confirmReset(quotationId) {
    Swal.fire({
        title: 'Confirmation',
        text: 'Are you sure you want to change the payment proof?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to resetpdf.php with the quotation_id parameter
            window.location.href = `resetpdf.php?quotation_id=${quotationId}`;
        }
    });
}
</script>

<script>
function validateForm(quotationId) {
    // Get the file input element
    var fileInput = document.getElementById('pdfFile' + quotationId);

    // Check if a file has been selected
    if (fileInput.files.length === 0) {
        // Display a SweetAlert confirmation dialog
        Swal.fire({
            title: 'Please choose a PDF file before uploading.',
            icon: 'warning',
            confirmButtonText: 'OK',
            showCancelButton: false,
            allowOutsideClick: false,
        });
        
        // Prevent form submission
        return false;
    }

    // Continue with form submission
    return true;
}
</script>