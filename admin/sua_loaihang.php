<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maloai = $_POST['maloai'];
    $tenloai = $_POST['tenloai'];
    $nhasx = $_POST['nhasx'];

    // Cập nhật loại hàng trong bảng loaihang
    $sql = "UPDATE loaihang SET tenloai='$tenloai', nhasx='$nhasx' WHERE maloai='$maloai'";

    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang quản lý sau khi cập nhật thành công
        header("Location: quanlyloaihang.php");
    } else {
        echo "Lỗi khi sửa loại hàng: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);
