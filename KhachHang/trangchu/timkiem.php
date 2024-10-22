<?php
include "connect.php"; // Kết nối tới database

// Lấy từ khóa tìm kiếm từ form
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Truy vấn tìm kiếm sản phẩm dựa trên từ khóa
    $query = "SELECT * FROM hang WHERE tenhang LIKE '%$search%'";
    $result = mysqli_query($conn, $query);
    
    // Kiểm tra có sản phẩm nào không
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Kết quả tìm kiếm cho '$search':</h2>";
        echo "<div class='product-list'>";
        
        // Lặp qua các sản phẩm và hiển thị chúng
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product-item'>";
            echo "<img src='../img/" . $row['hinhanh'] . "' alt='" . $row['tenhang'] . "'>";
            echo "<h4>" . $row['tenhang'] . "</h4>";
            echo "<p>Giá: " . number_format($row['dongia'], 0, ',', '.') . " VND</p>";
            echo "</div>";
        }
        
        echo "</div>";
    } else {
        echo "<h2>Không tìm thấy kết quả nào cho '$search'</h2>";
    }
} else {
    echo "<h2>Vui lòng nhập từ khóa để tìm kiếm</h2>";
}
?>
