<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $makhach = $_POST['makhach'];
    $tenkhach = $_POST['tenkhach'];
    $dienthoai = $_POST['dienthoai'];
    $diachi = $_POST['diachi'];
    
    // Kiểm tra xem có mật khẩu mới hay không
    if (!empty($_POST['matkhau'])) {
        $matkhau = password_hash($_POST['matkhau'], PASSWORD_DEFAULT); // Mã hóa mật khẩu mới
        $sql = "UPDATE khach SET tenkhach='$tenkhach', dienthoai='$dienthoai', diachi='$diachi', matkhau='$matkhau' WHERE makhach='$makhach'";
    } else {
        $sql = "UPDATE khach SET tenkhach='$tenkhach', dienthoai='$dienthoai', diachi='$diachi' WHERE makhach='$makhach'";
    }

    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang danh sách sau khi cập nhật thành công
        header("Location: quanlytaikhoan.php");
    } else {
        echo "Lỗi khi sửa tài khoản: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);

