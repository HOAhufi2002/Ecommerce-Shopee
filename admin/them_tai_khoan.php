<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $makhach = $_POST['makhach'];
    $tenkhach = $_POST['tenkhach'];
    $dienthoai = $_POST['dienthoai'];
    $diachi = $_POST['diachi'];
    $matkhau = password_hash($_POST['matkhau'], PASSWORD_DEFAULT); // Mã hóa mật khẩu

    // Thêm tài khoản vào bảng khach
    $sql = "INSERT INTO khach (makhach, tenkhach, dienthoai, diachi, matkhau) 
            VALUES ('$makhach', '$tenkhach', '$dienthoai', '$diachi', '$matkhau')";

    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang danh sách sau khi thêm thành công
        header("Location: quanlytaikhoan.php");
    } else {
        echo "Lỗi khi thêm tài khoản: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);

