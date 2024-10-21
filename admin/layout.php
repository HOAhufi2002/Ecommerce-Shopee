<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng về trang đăng nhập
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Kết nối cơ sở dữ liệu
include('connect.php');

// Lấy thông tin người dùng đã đăng nhập
$taikhoan = $_SESSION['username'];
$sql = "SELECT * FROM nhanvien WHERE taikhoan='$taikhoan'";
$result = mysqli_query($conn, $sql);
$nhanvien = mysqli_fetch_assoc($result);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý</title>
    <link rel="shortcut icon" type="image/png" href="./assets/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="./assets/css/styles.min.css" />
    <style>
        .app-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000; /* Đảm bảo thanh header luôn ở trên cùng */
            background-color: #fff; /* Màu nền cho header */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Tạo hiệu ứng đổ bóng */
        }
        .body-wrapper {
            padding-top: 80px; /* Khoảng trống đủ để không bị che khuất nội dung bên dưới */
        }
    </style>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.html" class="text-nowrap logo-img">
                        <img src="./assets/images/logos/logo-light.svg" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                            <span class="hide-menu">Admin</span></li>   
                            <li class="sidebar-item">
                            <a class="sidebar-link" href="index.php" aria-expanded="false">
                                <span>
                                    <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                                </span>
                                <span class="hide-menu">Trang Chủ</span>
                            </a>
                        </li>

                             <li class="sidebar-item">
                            <a class="sidebar-link" href="quanlyloaihang.php" aria-expanded="false">
                                <span>
                                    <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                                </span>
                                <span class="hide-menu">Loại Sản Phẩm</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="sanpham.php" aria-expanded="false">
                                <span>
                                    <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                                </span>
                                <span class="hide-menu">Sản Phẩm</span>
                            </a>
                        </li>
             
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="quanlytaikhoan.php" aria-expanded="false">
                                <span>
                                    <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                                </span>
                                <span class="hide-menu">Tài Khoản Người Dùng</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="quanlyhoadon.php" aria-expanded="false">
                                <span>
                                    <iconify-icon icon="solar:danger-circle-bold-duotone" class="fs-6"></iconify-icon>
                                </span>
                                <span class="hide-menu">Quản Lý Hóa đơn</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="body-wrapper">
            <header style="position: fixed;" class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                <i class="ti ti-bell-ringing"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
            
                            <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- Hiển thị tên người dùng đã đăng nhập -->
                <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                <?php echo $_SESSION['username']; ?> <!-- Hiển thị tài khoản -->
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                <div class="message-body">
                    <!-- Link đến modal thông tin tài khoản -->
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="ti ti-user fs-6"></i>
                        <p class="mb-0 fs-3">Profile</p>
                    </a>
                    <a href="logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                </div>
            </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <div >
                <?php
                // Điểm chèn nội dung từ các trang con
                if (isset($content)) {
                    echo $content;
                }
                ?>
            </div>   
        </div>
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Thông Tin Tài Khoản</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><strong>Mã Nhân Viên:</strong> <?php echo $nhanvien['manv']; ?></li>
          <li class="list-group-item"><strong>Tên Nhân Viên:</strong> <?php echo $nhanvien['tennv']; ?></li>
          <li class="list-group-item"><strong>Điện Thoại:</strong> <?php echo $nhanvien['dienthoai']; ?></li>
          <li class="list-group-item"><strong>Địa Chỉ:</strong> <?php echo $nhanvien['diachi']; ?></li>
          <li class="list-group-item"><strong>CCCD:</strong> <?php echo $nhanvien['cccd']; ?></li>
          <li class="list-group-item"><strong>Tài Khoản:</strong> <?php echo $nhanvien['taikhoan']; ?></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
        <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/libs/apexcharts/dist/apexcharts.min.js"></script>
        <script src="./assets/libs/simplebar/dist/simplebar.js"></script>
        <script src="./assets/js/sidebarmenu.js"></script>
        <script src="./assets/js/app.min.js"></script>
        <script src="./assets/js/dashboard.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>