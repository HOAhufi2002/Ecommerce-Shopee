<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng về trang đăng nhập
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Truy vấn số lượng người mua hàng
$sql_khach = "SELECT COUNT(*) AS soLuongNguoiMua FROM khach";
$result_khach = mysqli_query($conn, $sql_khach);
$data_khach = mysqli_fetch_assoc($result_khach);
$soLuongNguoiMua = $data_khach['soLuongNguoiMua'];

// Truy vấn số lượng món hàng hiện có
$sql_hang = "SELECT COUNT(*) AS soLuongMonHang FROM hang";
$result_hang = mysqli_query($conn, $sql_hang);
$data_hang = mysqli_fetch_assoc($result_hang);
$soLuongMonHang = $data_hang['soLuongMonHang'];

// Truy vấn số lượng loại hàng
$sql_loaihang = "SELECT COUNT(*) AS soLuongLoai FROM loaihang";
$result_loaihang = mysqli_query($conn, $sql_loaihang);
$data_loaihang = mysqli_fetch_assoc($result_loaihang);
$soLuongLoai = $data_loaihang['soLuongLoai'];

// Nội dung thống kê sẽ được hiển thị trong phần layout
$content = '
    <div class="container">
        <h1 class="mb-12">Thống Kê Hệ Thống</h1>
<div class="row">
    <!-- Thẻ thống kê Người Mua Hàng -->
    <div class="col-md-6">
        <div class="card mb-3 shadow-sm" style="background: linear-gradient(135deg, #007bff 0%, #00c6ff 100%); color: white;">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <!-- Icon người dùng -->
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <div>
                    <h5 class="card-title">Người Mua Hàng</h5>
                    <h2 class="card-text"><?php echo $soLuongNguoiMua; ?></h2>
                    <p class="mb-0">Tổng số người mua hàng trong hệ thống</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thẻ thống kê Món Hàng -->
    <div class="col-md-6">
        <div class="card mb-3 shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #00ff85 100%); color: white;">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <!-- Icon sản phẩm -->
                    <i class="fas fa-box fa-3x"></i>
                </div>
                <div>
                    <h5 class="card-title">Món Hàng</h5>
                    <h2 class="card-text"><?php echo $soLuongMonHang; ?></h2>
                    <p class="mb-0">Tổng số món hàng hiện có trong kho</p>
                </div>
            </div>
        </div>
    </div>
</div>
        
<div class="mt-5">
            <canvas id="thongKeChart" width="400" height="150"></canvas>
 </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    var ctx = document.getElementById("thongKeChart").getContext("2d");
    var myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Người Mua Hàng", "Món Hàng", "Loại Hàng"],
            datasets: [{
                label: "Số Lượng",
                data: [' . $soLuongNguoiMua . ', ' . $soLuongMonHang . ', ' . $soLuongLoai . '],
                backgroundColor: [
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(255, 206, 86, 0.2)"
                ],
                borderColor: [
                    "rgba(54, 162, 235, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(255, 206, 86, 1)"
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
';

// Bao gồm layout
include('layout.php');

// Đóng kết nối
mysqli_close($conn);
