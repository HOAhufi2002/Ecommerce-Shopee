<?php
session_start();
include_once "connect.php";

// Biến lưu thông báo
$thongbao = "";

if (isset($_POST['xacnhan'])) {
    $makhach = $_POST['makhach'];
    $matkhau = $_POST['matkhau'];

    // Truy vấn kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM khach WHERE makhach = ? AND matkhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $makhach, $matkhau);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra kết quả
    if ($result->num_rows > 0) {
        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $user = $result->fetch_assoc();
    
        // Nếu thông tin đúng, lưu thông tin người dùng vào session
        $_SESSION['user'] = [
            'tenkhach' => $user['tenkhach'], 
            'makhach' => $user['makhach'],
            'diachi' => $user['diachi'],
            'dienthoai' => $user['dienthoai']        ];
    
        // Đặt cờ redirect để JavaScript xử lý chuyển hướng
        $_SESSION['redirect'] = true;
    
        // Đặt thông báo thành công
        $thongbao = "Đăng nhập thành công! Vui lòng chờ chút...";
    
        // Lưu tên người dùng vào localStorage bằng JavaScript
        echo "<script>
            localStorage.setItem('username', '" . $user['tenkhach'] . "');
        </script>";
    } else {
        // Nếu sai, hiển thị thông báo lỗi
        $thongbao = "Sai tài khoản hoặc mật khẩu. Vui lòng nhập lại.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Đăng Nhập</title>
    <style>
        /* CSS cho thông báo */
        .notification {
            position: fixed;
            top: -50px;
            left: 0;
            right: 0;
            background-color: #f44336; /* Màu đỏ */
            color: white;
            text-align: center;
            padding: 10px;
            transition: top 0.5s;
            z-index: 1000;
        }
        .notification.show {
            top: 0;
        }
        .notification.success {
            background-color: #4CAF50; /* Màu xanh lá cây cho thông báo thành công */
        }
    </style>
</head>
<body>
<header>
    <h2 class="logo">Logo</h2>
    <nav class="navigation">
        <a href="trangchu.php">Home</a>

        <button class="btnLogin-popup">Login</button>
    </nav>
</header>

<!-- Thông báo -->
<?php if ($thongbao): ?>
    <div class="notification <?php echo isset($_SESSION['redirect']) ? 'success' : ''; ?>" id="notification"><?php echo $thongbao; ?></div>
<?php endif; ?>

<!-- Form đăng nhập -->
<div class="wrapper">
    <div class="form-box login">
        <h2>Đăng Nhập</h2>
        <form action="" method="post">
            <div class="input-box">
                <span class="icon"><i class='bx bxs-user'></i></span>
                <input type="text" name="makhach" placeholder="Tài Khoản" required 
                value="<?php if (isset($_POST['makhach'])) echo $_POST['makhach']; ?>">
            </div>

            <div class="input-box">
                <span class="icon">
                    <i class='bx bxs-hide' id="togglePassword" onclick="togglePassword()"></i>
                </span>
                <input type="password" id="password" name="matkhau" placeholder="Mật Khẩu" required>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox"> Nhớ mật khẩu</label>
                <a href="#">Quên mật khẩu</a>
            </div>

            <button type="submit" name="xacnhan" class="btn">Đăng nhập</button>
            <div class="login-register">
                <p>Chưa có tài khoản? <a href="dangky.php" class="register-link">Đăng Ký</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePassword');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bxs-hide');
            toggleIcon.classList.add('bxs-show'); // Change icon to show
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bxs-show');
            toggleIcon.classList.add('bxs-hide'); // Change icon back to hide
        }
    }

    // Hiện thông báo nếu có
    window.onload = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.classList.add('show');
            setTimeout(() => {
                notification.classList.remove('show');
                <?php if (isset($_SESSION['redirect']) && $_SESSION['redirect'] == true): ?>
                    window.location.href = 'trangchu.php'; // Chuyển hướng sau 3 giây
                    <?php unset($_SESSION['redirect']); ?>
                <?php endif; ?>
            }, 3000); // 3 giây
        }
    }
</script>

</body>
</html>
