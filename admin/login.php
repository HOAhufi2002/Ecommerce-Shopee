<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taikhoan = $_POST['taikhoan'];
    $matkhau = $_POST['matkhau'];

    // Truy vấn kiểm tra tài khoản và mật khẩu
    $sql = "SELECT * FROM nhanvien WHERE taikhoan='$taikhoan'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Kiểm tra mật khẩu
        if (password_verify($matkhau, $row['matkhau'])) {
            // Đăng nhập thành công
            $_SESSION['username'] = $row['taikhoan']; // Lưu tài khoản vào session
            $_SESSION['manv'] = $row['manv']; // Lưu manv vào session
            header('Location: sanpham.php'); // Chuyển hướng về trang quản lý
            exit;
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}include('layout.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Đăng Nhập</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="taikhoan" class="form-label">Tài khoản</label>
                <input type="text" class="form-control" id="taikhoan" name="taikhoan" required>
            </div>
            <div class="mb-3">
                <label for="matkhau" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="matkhau" name="matkhau" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        </form>
    </div>
</body>
</html>
