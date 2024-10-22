<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahoadon = $_POST['mahoadon'];
    $trangthai = $_POST['trangthai'];

    // Cập nhật trạng thái hóa đơn
    $sql = "UPDATE hoadon SET trangthai='$trangthai' WHERE mahoadon='$mahoadon'";

    if (mysqli_query($conn, $sql)) {
        // Quay lại trang quản lý hóa đơn sau khi cập nhật thành công
        header("Location: quanlyhoadon.php");
    } else {
        echo "Lỗi khi cập nhật trạng thái: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);

?>