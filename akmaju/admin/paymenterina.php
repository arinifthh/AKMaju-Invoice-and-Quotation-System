<?php
$title = 'Payment';
include 'layout/header.php';

$sql_quotation = "SELECT * FROM `quotations` JOIN `invoices` ON `quotations`.`quotation_id` = `invoices`.`invoice_quotation_id`";
$result_quotation = $conn->query($sql_quotation);

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
                        <?php foreach ($result_quotation as $quotation) : ?>
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
                                    <td><?= $quotation['quotation_id'] ?></td>
                                    <td><?= date('d/m/Y h:i:s A', strtotime($quotation['quotation_date'])) ?></td>
                                    <td><?= $customer['customer_name'] ?></td>
                                    <td><?= $customer['customer_phone'] ?></td>
                                    <td><?= $quotation['invoice_payment_method'] ?></td>
                                    <td id="total-price"><?= number_format($total + $quotation['invoice_payment_delivery_fee'], 2) ?></td>
                                    <td>
                                      <?php if ('invoice_quotation_id' == 'quotation_id'): ?>
                                    <span class="badge badge-success">Paid</span>
                                     <?php else: ?>
                                   <span class="badge badge-success">Unpaid</span>
                                     <?php endif; ?>
                                    </td>
                                    <td>
        <form class="upload-form" action="uploaderina.php" method="POST" enctype="multipart/form-data">
  <label class="upload-label" for="pdfFile">
    <span class="button-text">Choose PDF File</span>
    <input class="upload-input" type="file" name="pdfFile" id="pdfFile" onchange="displayFileName()">
  </label>
  <button class="upload-button" type="submit">
    <span class="button-text">Upload</span>
  </button>
</form>                         </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<?php include 'layout/footer.php'; ?>

<script>function displayFileName() {
  const fileInput = document.getElementById('pdfFile');
  const fileName = fileInput.files[0].name;
  const buttonText = document.querySelectorAll('.button-text');

  for (let i = 0; i < buttonText.length; i++) {
    buttonText[i].textContent = fileName;
  }
} <