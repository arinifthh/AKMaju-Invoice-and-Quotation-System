<!DOCTYPE html>
<html>
<head>

  <title>File Upload</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Add your custom styles here */
    .swal2-popup {
      font-family: 'Puck Medium', sans-serif;
    }
  </style>
    </head>
<body>
<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if any files were uploaded
  if (!empty($_FILES["pdfFile"]["name"])) {
    foreach ($_FILES["pdfFile"]["name"] as $key => $value) {
      // Process each file individually
      $uploadedFile = $_FILES["pdfFile"]["tmp_name"][$key];
      $destination = 'C:/xampp/htdocs/akmaju/file/' . $_FILES["pdfFile"]["name"][$key];

      $maxFileSizeKB = 1000;
      $uploadedFileSizeKB = filesize($uploadedFile) / 1024; // Convert bytes to KB

      if ($uploadedFileSizeKB > $maxFileSizeKB) {
        // File size exceeds the limit, show an alert
        echo "<script>
          Swal.fire({
            icon: 'error',
            title: 'File Size Exceeded',
            text: 'The file size exceeds the limit of $maxFileSizeKB KB.',
            customClass: {
              popup: 'custom-swal-font'
            },
          }).then(function() {
            window.location = 'payment.php'; // Redirect to another page
          });
        </script>";
        continue; // Skip processing this file
      }

      // Check if the destination directory exists, create it if necessary
      if (!is_dir('C:/xampp/htdocs/akmaju/file/')) {
        mkdir('C:/xampp/htdocs/akmaju/file/', 0777, true);
      }

      $quotationId = $_POST['quotation_id'];
      $pdfContent = file_get_contents($uploadedFile);

      // Use proper SQL query to update the database
      $sqlUpdate = "UPDATE invoices SET invoice_payment_pdf = ? WHERE invoice_quotation_id = ?";
      $stmt = $conn->prepare($sqlUpdate);
      $stmt->bind_param('si', $pdfContent, $quotationId);
      $stmt->execute();
      $stmt->close();

      if (move_uploaded_file($uploadedFile, $destination)) {
        // File uploaded successfully
        echo "<script>
          Swal.fire({
            icon: 'success',
            title: 'File Uploaded',
            text: 'Your file has been successfully uploaded.',
            customClass: {
              popup: 'custom-swal-font'
            },
          }).then(function() {
            window.location = 'payment.php'; // Redirect to another page
          });
        </script>";
      } else {
        // Error uploading file
        echo "<script>
          Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: 'Failed to upload file.',
            customClass: {
              popup: 'custom-swal-font'
            },
          }).then(function() {
            window.location = 'payment.php'; // Redirect to another page
          });
        </script>";
      }
    }
  } else {
    // No files uploaded
    echo "<script>
      Swal.fire({
        icon: 'info',
        title: 'No Files',
        text: 'No files were uploaded.',
        customClass: {
          popup: 'custom-swal-font'
        },
      }).then(function() {
        window.location = 'index.html'; // Redirect to another page
      });
    </script>";
  }
}
?>

</body>
</html>