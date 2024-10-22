<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Truy vấn danh sách hóa đơn từ bảng 'hoadon'
$sql = "SELECT mahoadon, makhach, ngaylap, tongtien, trangthai FROM hoadon";
$result = mysqli_query($conn, $sql);

// Nội dung của trang quản lý hóa đơn
$content = '<div class="container mt-4">';
$content .= '<h1 class="mb-4">Quản Lý Hóa Đơn</h1>';

$content .= '<table class="table table-hover table-bordered">';
$content .= '<thead class="table"><tr><th>Mã Hóa Đơn</th><th>Mã Khách</th><th>Ngày Lập</th><th>Tổng Tiền</th><th>Trạng Thái</th><th>Cập Nhật Trạng Thái</th><th>Chi Tiết Hóa Đơn</th></tr></thead>';
$content .= '<tbody>';

// Lặp qua kết quả truy vấn và tạo nội dung bảng
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $content .= '<tr>';
        $content .= '<td>' . $row['mahoadon'] . '</td>';
        $content .= '<td>' . $row['makhach'] . '</td>';
        $content .= '<td>' . $row['ngaylap'] . '</td>';
        $content .= '<td>' . $row['tongtien'] . '</td>';
        $content .= '<td>' . $row['trangthai'] . '</td>';
        
        // Thêm nút cập nhật trạng thái, khi bấm sẽ hiện modal popup
        $content .= '<td>';
        $content .= '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#capNhatTrangThaiModal" onclick="setHoaDonId(' . $row['mahoadon'] . ')">Cập Nhật</button>';
        $content .= '</td>';
        
        // Thêm nút xem chi tiết
        $content .= '<td><button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#chiTietHoaDonModal" onclick="loadChiTietHoaDon(' . $row['mahoadon'] . ')">Xem Chi Tiết</button></td>';
        $content .= '</tr>';
    }
} else {
    $content .= '<tr><td colspan="7">Không có hóa đơn nào</td></tr>';
}

$content .= '</tbody></table>';
$content .= '</div>';

// Bao gồm layout
include('layout.php');

// Đóng kết nối
mysqli_close($conn);
?>

<!-- Modal Cập Nhật Trạng Thái -->
<div class="modal fade" id="capNhatTrangThaiModal" tabindex="-1" aria-labelledby="capNhatTrangThaiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="capnhat_trangthai.php">
        <div class="modal-header">
          <h5 class="modal-title" id="capNhatTrangThaiModalLabel">Cập Nhật Trạng Thái Hóa Đơn</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="mahoadonInput" name="mahoadon">
          <div class="mb-3">
            <label for="trangthai" class="form-label">Chọn Trạng Thái Mới</label>
            <select name="trangthai" class="form-select" id="trangthai">
              <option value="chua_thanh_toan">Chưa Thanh Toán</option>
              <option value="da_thanh_toan">Đã Thanh Toán</option>
              <option value="huy">Hủy</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Hiển Thị Chi Tiết Hóa Đơn -->
<div class="modal fade" id="chiTietHoaDonModal" tabindex="-1" aria-labelledby="chiTietHoaDonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chiTietHoaDonModalLabel">Chi Tiết Hóa Đơn</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="chiTietHoaDonContent">
          <!-- Nội dung chi tiết hóa đơn sẽ được tải vào đây bằng AJAX -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript để tải chi tiết hóa đơn và cập nhật trạng thái -->
<script>
function setHoaDonId(mahoadon) {
    // Đặt giá trị mã hóa đơn vào input ẩn trong form của modal
    document.getElementById('mahoadonInput').value = mahoadon;
}

function loadChiTietHoaDon(mahoadon) {
    // Gọi AJAX để lấy chi tiết hóa đơn
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'lay_chi_tiet_hoa_don.php?mahoadon=' + mahoadon, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById('chiTietHoaDonContent').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
</script>
?>