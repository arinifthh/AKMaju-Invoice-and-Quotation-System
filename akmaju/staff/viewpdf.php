
<?php
// Include necessary database connection code
require_once '../config/db.php';

// Retrieve the quotation_id from the query string
$quotationId = $_GET['quotation_id'];

// Fetch the PDF content from the database
$sql = "SELECT invoice_payment_pdf FROM invoices WHERE invoice_quotation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $quotationId);
$stmt->execute();
$stmt->bind_result($pdfContent);
$stmt->fetch();
$stmt->close();

// Send appropriate headers for PDF
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="invoice.pdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');

// Output the PDF content
echo $pdfContent;
?>
<script>
// Set the document title to "Proof of Payment"
document.title = "Proof of Payment";
</script>