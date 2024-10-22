<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Lấy mã khách từ URL
$makhach = $_GET['makhach'];

// Xóa tài khoản khỏi bảng khach
$sql = "DELETE FROM khach WHERE makhach='$makhach'";

if (mysqli_query($conn, $sql)) {
    // Chuyển hướng về trang danh sách sau khi xóa
    header("Location: quanlytaikhoan.php");
} else {
    echo "Lỗi khi xóa tài khoản: " . mysqli_error($conn);
}

// Đóng kết nối
mysqli_close($conn);

?>