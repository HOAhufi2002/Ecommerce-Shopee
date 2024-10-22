<?php
session_start(); // Khởi động session trước khi bao gồm header hoặc bất kỳ mã HTML nào

include 'header.php'; // Bao gồm file header
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/giohang.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Giỏ Hàng</title>
</head>

<body>
    <div class="app">
        <div class="app">
        <?php include 'header.php'; ?>

            <?php

            // Kiểm tra xem giỏ hàng có tồn tại không
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = []; // Khởi tạo giỏ hàng nếu chưa tồn tại
            }

            // Thêm xử lý cho việc xóa sản phẩm khỏi giỏ hàng
            if (isset($_POST['remove'])) {
                $mahang = $_POST['mahang'];
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['mahang'] === $mahang) {
                        unset($_SESSION['cart'][$key]); // Xóa sản phẩm
                        break;
                    }
                }
                echo "<script>window.location.href='giohang.php';</script>";

                exit;
            }

            // Thêm xử lý cho việc cập nhật số lượng sản phẩm
            if (isset($_POST['update'])) {
                $mahang = $_POST['mahang'];
                $new_quantity = $_POST['quantity'];
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['mahang'] === $mahang) {
                        $_SESSION['cart'][$key]['so_luong'] = $new_quantity; // Cập nhật số lượng
                        break;
                    }
                }
                echo "<script>window.location.href='giohang.php';</script>";
                exit;
            }
            ?>

            <div class="container">
                <div class="cart-container">
                    <div class="cart-header">
                        <div class="cart-header-item">Sản Phẩm</div>
                        <div class="cart-header-item">Đơn Giá</div>
                        <div class="cart-header-item">Số Lượng</div>
                        <div class="cart-header-item">Số Tiền</div>
                        <div class="cart-header-item">Thao Tác</div>
                    </div>

                    <?php if (empty($_SESSION['cart'])): ?>
                        <p>Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng!</p>
                    <?php else: ?>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="cart-item">
                                <div class="item-details">
                                    <input type="checkbox" class="select-item">
                                    <div class="item-info">
                                        <img src="../img/<?php echo htmlspecialchars($item['hinh']); ?>" class="item-image" alt="Sản Phẩm">
                                        <div class="item-text">
                                            <p class="item-name" onclick="goToProductDetail()"><?php echo htmlspecialchars($item['tenhang']); ?></p>
                                            <p class="item-type">
                                                Phân Loại Hàng: 
                                                <?php echo isset($item['loai']) ? htmlspecialchars($item['loai']) : "Không xác định"; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="item-price">
                                    <span class="original-price">
                                        ₫<?php echo isset($item['gia_goc']) ? number_format($item['gia_goc'], 0, '.', '.') : '0'; ?>
                                    </span>
                                    <span class="sale-price">
                                        ₫<?php echo number_format($item['gia_giam'], 0, '.', '.'); ?>
                                    </span>
                                </div>
                                <div class="item-quantity">
                                    <form method="POST">
                                        <input type="hidden" name="mahang" value="<?php echo htmlspecialchars($item['mahang']); ?>">
                                        <button type="submit" name="update" class="quantity-btn minus-btn">-</button>
                                        <input type="number" name="quantity" class="quantity-input" value="<?php echo htmlspecialchars($item['so_luong']); ?>" min="1" onchange="this.form.submit()">
                                        <button type="submit" name="update" class="quantity-btn plus-btn">+</button>
                                    </form>
                                </div>
                                <div class="item-total">
                                    ₫<?php echo number_format($item['gia_giam'] * $item['so_luong'], 0, '.', '.'); ?>
                                </div>
                                <div class="item-delete">
                                    <form method="POST">
                                        <input type="hidden" name="mahang" value="<?php echo htmlspecialchars($item['mahang']); ?>">
                                        <button type="submit" name="remove" class="delete-btn">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="cart-footer">
                        <div class="cart-actions">
                            <button ></button>
                        </div>
                        <div class="cart-summary">
                            <p id="total-summary">
                                Tổng tiền (<?php echo count($_SESSION['cart']); ?> Sản phẩm):
                                ₫<?php
                                    $total = 0;
                                    foreach ($_SESSION['cart'] as $item) {
                                        $total += $item['gia_giam'] * $item['so_luong'];
                                    }
                                    echo number_format($total, 0, '.', '.');
                                    ?>
                            </p>
                            <form action="checkout.php" method="POST">
    <button type="submit" class="buy-now">Mua Hàng</button>
</form>                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Logic để điều hướng tới trang chi tiết sản phẩm
                function goToProductDetail() {
                    // Có thể sử dụng mã JavaScript để điều hướng đến trang chi tiết sản phẩm
                    alert("Đi tới trang chi tiết sản phẩm (chưa được cài đặt)");
                }
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const plusBtns = document.querySelectorAll(".plus-btn");
                    
                    const minusBtns = document.querySelectorAll(".minus-btn");
                    const selectAllCheckbox = document.getElementById("select-all");
                    const itemCheckboxes = document.querySelectorAll(".select-item");

                    // Tăng giảm số lượng sản phẩm
                    plusBtns.forEach((btn) => {
                        btn.addEventListener("click", function() {
                            const input = this.previousElementSibling;
                            let currentValue = parseInt(input.value);
                            if (currentValue < 99) {
                                input.value = currentValue + 1;
                                updateTotalPrice(input);
                            }
                            
                        });
                        
                    });

                    minusBtns.forEach((btn) => {
                        btn.addEventListener("click", function() {
                            const input = this.nextElementSibling;
                            let currentValue = parseInt(input.value);
                            if (currentValue > 1) {
                                input.value = currentValue - 1;
                                updateTotalPrice(input);
                            }
                        });
                    });

                    // Chuyển trang khi nhấn vào tên sản phẩm
                    const productNames = document.querySelectorAll(".item-name");
                    productNames.forEach((name) => {
                        name.addEventListener("click", goToProductDetail);
                    });

                    // Checkbox "Chọn tất cả"
                    selectAllCheckbox.addEventListener("change", function() {
                        itemCheckboxes.forEach((checkbox) => {
                            checkbox.checked = selectAllCheckbox.checked;
                        });
                    });
                });

                function updateTotalPrice(input) {
                    const priceElement = input.closest(".cart-item").querySelector(".sale-price");
                    const totalElement = input.closest(".cart-item").querySelector(".item-total");
                    const price = parseFloat(priceElement.textContent.replace(/[^0-9.-]+/g, ""));
                    const quantity = parseInt(input.value);
                    const totalPrice = price * quantity;

                    totalElement.textContent = `₫${totalPrice.toFixed(0)}`;
                }

                function goToProductDetail() {
                    window.location.href = "product-detail.html";
                }
            </script>
        </div>

        <?php include 'footer.php'; ?>

        <script src="../css/script.js"></script>
</body>

</html>
