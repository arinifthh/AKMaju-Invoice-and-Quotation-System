<?php
require_once '../config/db.php';
header('Content-Type: application/json');
if (!isset($_SESSION['admin'])) {
    redirect('../login.php');
} else {
    $admin = $_SESSION['admin'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'add') {

        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        $sql_product = "SELECT * FROM products WHERE product_id = '$product_id'";
        $result_product = $conn->query($sql_product);

        if ($result_product->num_rows > 0) {
            $product = $result_product->fetch_assoc();

            // check if product already added
            if (isset($_SESSION['quotation'][$product_id])) {
                $quantity += $_SESSION['quotation'][$product_id];
            }
            // check stock
            if ($product['product_updated_quantity'] < $quantity) {
                $current_quantity = $product['product_quantity'] - $_SESSION['quotation'][$product_id];
                $message = "Product " . $product['product_name'] . " only have " . $current_quantity . " left";

                $status = false;
            } else {
                $_SESSION['quotation'][$product_id] = $quantity;
                $message = "Product " . $product['product_name'] . " added to quotation";
                $status = true;
            }

            echo json_encode([
                'status' => $status,
                'message' => $message
            ]);
        }
    } else if ($action == 'list') {


        if (isset($_SESSION['quotation']) && !empty($_SESSION['quotation'])) {
            $products = [];
            // list from quotation

            $sql_product = "SELECT * FROM products WHERE product_id IN (" . implode(',', array_keys($_SESSION['quotation'])) . ")";
            $result_product = $conn->query($sql_product);

            if ($result_product->num_rows > 0) {
                foreach ($result_product as $product) {
                    $products[] = [
                        'product_id' => $product['product_id'],
                        'product_name' => $product['product_name'],
                        'product_quantity' => $_SESSION['quotation'][$product['product_id']],
                        'product_selling_price' => $product['product_selling_price'],
                        'product_discount_percent' => $product['product_discount_percent'],
                        'product_discount_amount' => $product['product_discount_amount'],
                        'product_tax_code' => $product['product_tax_code'],
                        'product_total' => $_SESSION['quotation'][$product['product_id']] * $product['product_selling_price']
                    ];
                }
            } else {
                $products = [];
            }
            echo json_encode($products);
        } else {
            echo json_encode([]);
        }
    } else if ($action == 'remove') {
        $product_id = $_POST['product_id'];
        unset($_SESSION['quotation'][$product_id]);
    } else if ($action == 'generate') {
        $customer_id = $_POST['customer_id'];
        $sql_customer = "SELECT * FROM customers WHERE customer_id = '$customer_id'";
        $result_customer = $conn->query($sql_customer);



        // check  $_SESSION['quotation'] not empty
        if (empty($_SESSION['quotation'])) {
            echo json_encode([
                'status' => false,
                'message' => 'Quotation is empty'
            ]);
            return false;
        }

        if ($result_customer->num_rows > 0) {
            $customer = $result_customer->fetch_assoc();
            if (!isset($_POST['quotation_id'])) {
                $sql_quotation = "INSERT INTO quotations (quotation_id, quotation_customer_id, quotation_date, quotation_status) VALUES (NULL, '$customer_id', CURRENT_TIMESTAMP, '1')";
                $conn->query($sql_quotation);
                $quotation_id = $conn->insert_id;
            } else {
                $quotation_id = $_POST['quotation_id'];
                $sql_delete = "DELETE FROM quotation_details WHERE quotation_details.quotation_detail_quotation_id = '$quotation_id'";
                $conn->query($sql_delete);
            }

            foreach ($_SESSION['quotation'] as $product_id => $quantity) {
                $sql_product = "SELECT * FROM products WHERE product_id = '$product_id'";
                $result_product = $conn->query($sql_product);

                if ($result_product->num_rows > 0) {
                    $product = $result_product->fetch_assoc();

                    $sql_quotation_detail = "INSERT INTO quotation_details (quotation_detail_id, quotation_detail_quotation_id, quotation_detail_product_id, quotation_detail_quantity, quotation_detail_selling_price, quotation_detail_discount_percent, quotation_detail_discount_amount, quotation_detail_tax_code, quotation_detail_total) VALUES (NULL, '$quotation_id', '$product_id', '$quantity', '" . $product['product_selling_price'] . "', '" . $product['product_discount_percent'] . "', '" . $product['product_discount_amount'] . "', '" . $product['product_tax_code'] . "', '" . $quantity * $product['product_selling_price'] . "')";
                    $conn->query($sql_quotation_detail);
                }
            }

            foreach ($_SESSION['quotation'] as $product_id => $quantity) {
            // Check current inventory
            $sql_check_inventory = "SELECT product_quantity FROM products WHERE product_id = '$product_id'";
            $result = $conn->query($sql_check_inventory);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['product_quantity'] >= $quantity) {
                    // Enough inventory, proceed with update
                    $sql_update_inventory = "UPDATE products SET product_updated_quantity = product_quantity - $quantity WHERE product_id = '$product_id'";
                    $conn->query($sql_update_inventory);
                } else {
                    // Not enough inventory
                    // Handle this scenario, e.g., sending a notification or message
                    // You might also want to halt or rollback the quotation generation process
                }
            }
        }

            unset($_SESSION['quotation']);
            echo json_encode([
                'status' => true,
                'message' => 'Quotation generated'
            ]);
        }
    }
}