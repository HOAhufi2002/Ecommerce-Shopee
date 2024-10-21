<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maloai = $_POST['maloai'];
    $tenloai = $_POST['tenloai'];
    $nhasx = $_POST['nhasx'];

    // Thêm loại hàng mới vào bảng loaihang
    $sql = "INSERT INTO loaihang (maloai, tenloai, nhasx) VALUES ('$maloai', '$tenloai', '$nhasx')";

    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang quản lý sau khi thêm thành công
        header("Location: quanlyloaihang.php");
    } else {
        echo "Lỗi khi thêm loại hàng: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);

