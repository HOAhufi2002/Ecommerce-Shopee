<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Lấy mã hàng từ URL
$mahang = $_GET['mahang'];

// Thực hiện xóa sản phẩm
$sql = "DELETE FROM hang WHERE mahang='$mahang'";
if (mysqli_query($conn, $sql)) {
    // Chuyển hướng về trang danh sách sau khi xóa
    header("Location: sanpham.php");
} else {
    echo "Lỗi khi xóa sản phẩm: " . mysqli_error($conn);
}

// Đóng kết nối
mysqli_close($conn);

