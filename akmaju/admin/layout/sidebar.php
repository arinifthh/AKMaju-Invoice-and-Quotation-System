<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-id-card"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= $title == 'Dashboard' ? 'active' : '' ?>">
        <a class="nav-link " href="<?= base_url('admin/index.php') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Employee -->
    <li class="nav-item <?= $title == 'Employee' ? 'active' : '' ?>">
        <a class="nav-link " href="<?= base_url('admin/employee.php') ?>">
            <i class="fas fa-fw fa-id-badge"></i>
            <span>Employee</span></a>
    </li>
    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= ($title == 'Category') || ($title == 'Add Category') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/category.php') ?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Category</span></a>
    </li>
    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= ($title == 'Product') || ($title == 'Add Product') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/product.php') ?>">
            <i class="fas fa-fw fa-box"></i>
            <span>Product</span></a>
    </li>

    <hr class="sidebar-divider my-0">
    <!-- customer info -->
    <li class="nav-item <?= ($title == 'Customer Info') || ($title == 'Add Customer') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/customer.php') ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Customer Info</span></a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Quotation -->
    <li class="nav-item <?= ($title == 'Quotation') || ($title == 'Add Quotation') || ($title == 'Quotation Detail') || ($title == 'Quotation Edit') || ($title == 'Generate Quotation') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/quotation.php') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Quotation</span></a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Invoice -->
    <li class="nav-item <?= ($title == 'Invoice') || ($title == 'Create Invoice' || $title == 'Invoice Detail') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/invoice.php') ?>">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Invoice</span></a>
    </li>
  <hr class="sidebar-divider d-none d-md-block">

    <!-- Invoice -->
    <li class="nav-item <?= ($title == 'Payment')  ? 'active' : '' ?>">
        <a class="nav-link" href="<?= base_url('admin/payment.php') ?>">
            <i class="fas fa-fw fa-dollar-sign"></i>
            <span>Payment</span></a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>