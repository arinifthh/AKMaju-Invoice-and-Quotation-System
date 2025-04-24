<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the file was uploaded without any errors
  if (isset($_FILES["pdfFile"]) && $_FILES["pdfFile"]["error"] == UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES["pdfFile"]["tmp_name"];
    $destination = 'C:/xampp/htdocs/akmaju/file/' . $_FILES["pdfFile"]["name"];

    // Validate file type (example: allow only PDF files)
    $allowedFileType = 'application/pdf';
    if ($_FILES["pdfFile"]["type"] != $allowedFileType) {
      echo "Invalid file type. Only PDF files are allowed.";
      exit;
    }

    // Move the uploaded file to the desired destination
    if (move_uploaded_file($uploadedFile, $destination)) {
      echo "File uploaded successfully.";
    } else {
      echo "Error uploading file.";
    }
  } else {
    echo "Invalid file upload.";
  }
}
?>