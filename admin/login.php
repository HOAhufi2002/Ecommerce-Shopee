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
            $_SESSION['username'] = $row['taikhoan'];
            $_SESSION['manv'] = $row['manv'];
            header('Location: sanpham.php'); // Chuyển hướng về trang quản lý
            exit;
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://source.unsplash.com/1600x900/?technology,abstract') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-custom {
            background-color: #007bff;
            color: #fff;
            border-radius: 25px;
            width: 100%;
            padding: 10px;
        }

        .form-control {
            border-radius: 25px;
        }

        .form-label {
            color: #333;
        }

        .alert {
            border-radius: 25px;
            text-align: center;
        }

        /* Hiệu ứng bay cho các con vật */
        .bird {
            position: absolute;
            width: 50px;
            height: 50px;
            background-image: url('https://png.pngtree.com/png-clipart/20200224/original/pngtree-horse-design-logo-icon-vector-png-image_5229804.jpg');
            background-size: cover;
            animation: fly 10s linear infinite;
        }

        .bird:nth-child(2) {
            animation-duration: 12s;
            width: 60px;
            height: 60px;
            top: 20%;
        }

        .bird:nth-child(3) {
            animation-duration: 15s;
            width: 45px;
            height: 45px;
            top: 60%;
        }

        @keyframes fly {
            0% {
                left: -100px;
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(15deg);
            }
            100% {
                left: 110%;
                transform: translateY(0) rotate(-5deg);
            }
        }
    </style>
</head>
<body>
    <!-- Các con vật bay trên màn hình -->
    <div class="bird" style="top: 10%; left: -50px;"></div>
    <div class="bird" style="top: 30%; left: -100px;"></div>
    <div class="bird" style="top: 50%; left: -150px;"></div>
    <div class="bird" style="top: 70%; left: -100px;"></div>
    <div class="bird" style="top: 90%; left: -150px;"></div>
    <div class="bird" style="top: 20%; left: -50px;"></div>
    <div class="bird" style="top: 40%; left: -100px;"></div>
    <div class="bird" style="top: 60%; left: -150px;"></div>
    <div class="bird" style="top: 80%; left: -100px;"></div>
    <div class="bird" style="top: 100%; left: -150px;"></div>
    
    <div class="login-container">
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
            <button type="submit" class="btn btn-custom">Đăng Nhập</button>
        </form>
    </div>

</body>
</html>
