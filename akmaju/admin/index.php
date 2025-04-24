<?php
$title = 'Dashboard';
include 'layout/header.php';

$totalSales = calculateTotalSales();
$costOfSales = calculateTotalCost();
$profit = $totalSales - $costOfSales;

$sql_customer = "SELECT * FROM `customers`";
$result_customer = $conn->query($sql_customer);

$sql_category = "SELECT * FROM `product_category`";
$result_category = $conn->query($sql_category);

$sql_product = "SELECT * FROM `products`";
$result_product = $conn->query($sql_product);

$sql_quotation = "SELECT * FROM `quotations` WHERE `quotation_status` = '1'";
$result_quotation = $conn->query($sql_quotation);

$sql_invoice = "SELECT * FROM `invoices` WHERE `invoice_status` = '1'";
$result_invoice = $conn->query($sql_invoice);

$sql_user = "SELECT * FROM `users`";
$result_user = $conn->query($sql_user);

$sql_admin = "SELECT * FROM `users`WHERE `user_role` = '1'";
$result_admin = $conn->query($sql_admin);

$sql_staff = "SELECT * FROM `users`WHERE `user_role` = '2'";
$result_staff = $conn->query($sql_staff);

$sql_delquo = "SELECT * FROM `quotations` WHERE `quotation_status` = '0'";
$result_delquo = $conn->query($sql_delquo);

$sql_delinvo = "SELECT * FROM `invoices` WHERE `invoice_status` = '0'";
$result_delinvo = $conn->query($sql_delinvo);

$sql_total_quo = "SELECT * FROM `quotations`";
$result_total_quo = $conn->query($sql_total_quo);

$sql_total_invo = "SELECT * FROM `invoices`";
$result_total_invo = $conn->query($sql_total_invo);

$sql_paid = "SELECT * FROM `invoices` WHERE `invoice_payment_pdf` = null";
$result_paid = $conn->query($sql_paid);

// Get the current date in the format of 'Y-m-d'
date_default_timezone_set('Asia/Kuala_Lumpur');

$currentDateTime = date('Y-m-d H:i:s');

// Prepare the SQL query
$sql_cust = "SELECT * FROM customers
             WHERE DATE(customer_created_at) = CURDATE()";

$result_cust = $conn->query($sql_cust);

$sql_order = "SELECT * FROM quotations
             WHERE DATE(quotation_created_at) = CURDATE() AND quotation_status=1";

$result_order = $conn->query($sql_order);

$sql_sales = "SELECT DATE(q.quotation_created_at) AS order_date, (SUM(qd.quotation_detail_total) + SUM(i.invoice_payment_delivery_fee)) AS total
               FROM quotations q
               LEFT JOIN invoices i ON q.quotation_id = i.invoice_quotation_id
               INNER JOIN quotation_details qd ON q.quotation_id = qd.quotation_detail_quotation_id
               WHERE i.invoice_payment_pdf IS NOT NULL
               GROUP BY DATE(q.quotation_created_at)";

$result_sales = $conn->query($sql_sales);

$dayData = array_fill(0, 7, 0); // Fill with 0 for each day of the week

// Process the query result and store the data in the array
while ($row = $result_sales->fetch_assoc()) {
    $day = (int) date('w', strtotime($row['order_date'])); // Get the numeric representation of the day (0-6)
    $totalSales = $row['total'];
    $dayData[$day] = $totalSales;
}

$sql_pending = "SELECT * FROM `quotations` LEFT JOIN `invoices` ON `quotations`.`quotation_id` = `invoices`.`invoice_quotation_id` WHERE `invoices`.`invoice_quotation_id` IS NULL AND `quotation_status` = '1'";
$result_pending = $conn->query($sql_pending);


$count = array(
    'customer' => $result_customer->num_rows,
    'category' => $result_category->num_rows,
    'product' => $result_product->num_rows,
    'quotation' => $result_quotation->num_rows,
    'invoice' => $result_invoice->num_rows,
    'user'=> $result_user->num_rows,
    'admin' => $result_admin->num_rows,
    'staff' => $result_staff->num_rows,
    'deletequo'=> $result_delquo->num_rows,
    'deleteinvo'=> $result_delinvo->num_rows,
    'totalquo' => $result_total_quo->num_rows,
    'totalinvo' => $result_total_invo->num_rows,
    'tcust'=>$result_cust->num_rows,
    'pending'=>$result_pending->num_rows,
    'torder'=>$result_order->num_rows,
    'paid'=>$result_paid->num_rows,
);

$countJson = json_encode($count);

$totalSales = 0.00;

 $sqlQuotation = "SELECT SUM(qd.quotation_detail_total) AS quotation_total 
                FROM quotation_details qd
                INNER JOIN quotations q ON q.quotation_id = qd.quotation_detail_quotation_id
                INNER JOIN invoices i ON i.invoice_quotation_id = q.quotation_id
                WHERE i.invoice_payment_pdf IS NOT NULL";

    $resultQuotation = $conn->query($sqlQuotation);
    $rowQuotation = $resultQuotation->fetch_assoc();
    $quotationTotal = (double) $rowQuotation['quotation_total'];

    $sqlInvoice = "SELECT SUM(invoice_payment_delivery_fee) AS invoice_total FROM invoices WHERE invoice_payment_pdf IS NOT NULL";

    $resultInvoice = $conn->query($sqlInvoice);
    $rowInvoice = $resultInvoice->fetch_assoc();
    $invoiceTotal = $rowInvoice['invoice_total'];


// Calculate the total sales
$totalSales = $quotationTotal + $invoiceTotal;


function calculateTotalSales() {
    global $conn;
    
    $sqlQuotation = "SELECT SUM(qd.quotation_detail_total) AS quotation_total 
                FROM quotation_details qd
                INNER JOIN quotations q ON q.quotation_id = qd.quotation_detail_quotation_id
                INNER JOIN invoices i ON i.invoice_quotation_id = q.quotation_id
                WHERE i.invoice_payment_pdf IS NOT NULL";

    $resultQuotation = $conn->query($sqlQuotation);
    $rowQuotation = $resultQuotation->fetch_assoc();
    $quotationTotal = (double) $rowQuotation['quotation_total'];

    $sqlInvoice = "SELECT SUM(invoice_payment_delivery_fee) AS invoice_total FROM invoices WHERE invoice_payment_pdf IS NOT NULL";

    $resultInvoice = $conn->query($sqlInvoice);
    $rowInvoice = $resultInvoice->fetch_assoc();
    $invoiceTotal = $rowInvoice['invoice_total'];

    // Calculate the total sales
    return $quotationTotal + $invoiceTotal;
}

function calculateTotalCost() {
    global $conn;
    $sqlProduct = "SELECT SUM((product_cost_price)*(product_quantity)) AS product_cost FROM products WHERE product_status=1 ";

    $resultProduct = $conn->query($sqlProduct);
    $rowProduct = $resultProduct->fetch_assoc();
    $productTotal = (double) $rowProduct['product_cost'];
    return $productTotal;
}

?>


    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>

        <!-- Content Row -->
        <div class="row">

        
        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['user'] ?></div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Admin | Staff</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['admin'] ?> | <?= $count['staff'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Customers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['customer'] ?></div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Today's Customer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['tcust'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Order</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['quotation'] ?></div>
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Today's Order</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['torder'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Completed Order</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['paid'] ?></div>
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Order</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['quotation'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Category</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['category'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <a class="card-body" href="reportstock.php">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Stock Balance</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $count['product'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <a class="card-body" href="reportform.php">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">RM<?php echo $totalSales?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <a class="card-body" href="customer-print.php">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Transaction Listing</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count['invoice'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="container">
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Sales</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myArea"></canvas>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Data</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBar"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quotation Generated: <?= $count['totalquo'] ?></h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4">
                                <canvas id="myPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Invoice Generated: <?= $count['totalinvo'] ?></h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4">
                                <canvas id="myPie2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

    var count = <?php echo $countJson; ?>;
    var quotationCount = count['quotation'];
        var deleteQuoCount = count['deletequo'];
    var ctx = document.getElementById("myPie");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['Active Quotation', 'Deleted Quotation'],
    datasets: [{
      data: [quotationCount, deleteQuoCount],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});

var count = <?php echo $countJson; ?>;

        var quotationCount = count['invoice'];
        var deleteQuoCount = count['deleteinvo'];
    var ctx = document.getElementById("myPie2");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['Active Invoices', 'Deleted Invoices'],
    datasets: [{
      data: [quotationCount, deleteQuoCount],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});


        var dayData = <?php echo json_encode($dayData); ?>;
        var ctx = document.getElementById("myArea");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        datasets: [{
            label: "Earnings",
            lineTension: 0.3,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: dayData,}]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return '$' + number_format(value);
                    }
                },
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }],
        },
        legend: {
            display: false
        },
       
    }
});


var totalSales = <?php echo $totalSales; ?>;
        var costOfSales = <?php echo $costOfSales; ?>;
        var profit = <?php echo $profit; ?>;
var ctx = document.getElementById("myBar");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Total Sales', 'Cost of Sales', 'Profit / Loss'],
    datasets: [{
      label: 'Financial Data',
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#2e59d9",
      borderColor: "#4e73df",
      data: [totalSales, costOfSales, profit],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 6
        },
        maxBarThickness: 25,
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: 15000,
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return '$' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
        }
      }
    },
  }
});

    </script>
                 
</div></div>
<?php include 'layout/footer.php'; ?>
</html></div>