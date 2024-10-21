<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Lấy mã loại hàng từ URL
$maloai = $_GET['maloai'];

// Xóa loại hàng khỏi bảng loaihang
$sql = "DELETE FROM loaihang WHERE maloai='$maloai'";

if (mysqli_query($conn, $sql)) {
    // Chuyển hướng về trang quản lý sau khi xóa thành công
    header("Location: quanlyloaihang.php");
} else {
    echo "Lỗi khi xóa loại hàng: " . mysqli_error($conn);
}

// Đóng kết nối
mysqli_close($conn);
