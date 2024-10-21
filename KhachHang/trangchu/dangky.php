<?php 
session_start();
include_once "connect.php"; 

// Biến để lưu thông báo
$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mak = isset($_POST['mak']) ? $_POST['mak'] : '';
    $matkhau = isset($_POST['matkhau']) ? $_POST['matkhau'] : '';
    $tenk = isset($_POST['tenk']) ? $_POST['tenk'] : '';
    $diachi = isset($_POST['diachi']) ? $_POST['diachi'] : '';
    $dt = isset($_POST['dt']) ? $_POST['dt'] : '';

    // Kiểm tra xem khách hàng đã tồn tại hay chưa
    $sql = "SELECT * FROM khach WHERE tenkhach='$tenk' AND dienthoai='$dt'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        $sql1 = "SELECT * FROM khach WHERE makhach='$mak'";
        $result1 = mysqli_query($conn, $sql1);

        if (mysqli_num_rows($result1) == 0) {
            // Chèn dữ liệu mới vào bảng `khach`
            $kq = mysqli_query($conn, "INSERT INTO khach (makhach, matkhau, tenkhach, diachi, dienthoai) VALUES ('$mak', '$matkhau', '$tenk', '$diachi', '$dt')");
            
            if ($kq) {
                // Sau khi đăng ký thành công, lưu thông tin người dùng vào session
                $_SESSION['user'] = [
                    'makhach' => $mak,  // Mã khách hàng
                    'tenkhach' => $tenk  // Tên khách hàng
                ];

                // Đặt thông báo thành công
                $thongbao = "Đăng ký thành công! Vui lòng chờ chút...";
                
                // Gán session để thực hiện redirect
                $_SESSION['redirect'] = true;
            } else {
                $thongbao = "Có lỗi xảy ra trong quá trình đăng ký!";
            }
        } else {
            $thongbao = "Tên này đã có người đăng ký. Bạn lấy tên khác!";
        }
    } else {
        $thongbao = "Bạn đã đăng ký thông tin, vui lòng sử dụng thông tin đã đăng ký.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dangky.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Đăng Ký</title>
    <style>
        /* CSS cho thông báo */
        .notification {
            position: fixed;
            top: -50px;
            left: 0;
            right: 0;
            background-color: #4CAF50; /* Màu xanh */
            color: white;
            text-align: center;
            padding: 10px;
            transition: top 0.5s;
            z-index: 1000;
        }
        .notification.show {
            top: 0;
        }
    </style>
</head>
<body>

<header>
    <h2 class="logo">Logo</h2>
    <nav class="navigation">
        <a href="trangchu.php">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Contact</a>
        <button class="btnLogin-popup">Đăng Ký</button>
    </nav>
</header>

<!-- Thông báo -->
<?php if ($thongbao): ?>
    <div class="notification" id="notification"><?php echo $thongbao; ?></div>
<?php endif; ?>

<div class="wrapper">
    <div class="form-box login">
        <h2>Đăng Ký</h2>
        <form action="dangky.php" method="post">
            <!-- Tên tài khoản -->
            <div class="input-box">
                <span class="icon"><i class='bx bxs-user'></i></span>
                <input type="text" name="mak" placeholder="Tên Tài Khoản" required>
            </div>

            <!-- Mật khẩu -->
            <div class="input-box">
                <span class="icon">
                    <i class='bx bxs-hide' id="togglePassword" onclick="togglePassword()"></i>
                </span>
                <input type="password" id="password" name="matkhau" placeholder="Mật Khẩu" required>
            </div>

            <!-- Tên khách hàng -->
            <div class="input-box">
                <span class="icon"><i class='bx bxs-user-detail'></i></span>
                <input type="text" name="tenk" placeholder="Tên Khách Hàng" required>
            </div>

            <!-- Địa chỉ -->
            <div class="input-box">
                <span class="icon"><i class='bx bxs-map'></i></span>
                <input type="text" name="diachi" placeholder="Địa Chỉ" required>
            </div>

            <!-- Điện thoại -->
            <div class="input-box">
                <span class="icon"><i class='bx bxs-phone'></i></span>
                <input type="tel" name="dt" placeholder="Điện Thoại" required>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox"> Nhớ mật khẩu</label>
                <a href="login.php">Đăng Nhập</a>
            </div>

            <button type="submit" class="btn">Đăng Ký</button>
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
            toggleIcon.classList.add('bxs-show'); // Hiện mật khẩu
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bxs-show');
            toggleIcon.classList.add('bxs-hide'); // Ẩn mật khẩu
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
