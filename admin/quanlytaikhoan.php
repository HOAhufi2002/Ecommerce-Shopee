<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Truy vấn danh sách tài khoản từ bảng 'khach'
$sql = "SELECT makhach, tenkhach, matkhau, diachi, dienthoai FROM khach";
$result = mysqli_query($conn, $sql);

// Nội dung của trang quản lý tài khoản
$content = '<div class="container mt-4">';
$content .= '<h1 class="mb-4">Quản Lý Tài Khoản Khách Hàng</h1>';
// Nút Thêm tài khoản (sử dụng modal)
$content .= '<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themTaiKhoanModal">Thêm Tài Khoản</button>';

$content .= '<table class="table table-hover table-bordered">';
$content .= '<thead class="table"><tr><th>Mã Khách</th><th>Tên Khách</th><th>Địa Chỉ</th><th>Điện Thoại</th><th>Hành Động</th></tr></thead>';
$content .= '<tbody>';

// Lặp qua kết quả truy vấn và tạo nội dung bảng
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $content .= '<tr>';
        $content .= '<td>' . $row['makhach'] . '</td>';
        $content .= '<td>' . $row['tenkhach'] . '</td>';
        $content .= '<td>' . $row['diachi'] . '</td>';
        $content .= '<td>' . $row['dienthoai'] . '</td>';
        // Thêm nút sửa và xóa
        $content .= '<td>';
        $content .= '<button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#suaTaiKhoanModal" onclick="fillEditForm(\'' . $row['makhach'] . '\', \'' . $row['tenkhach'] . '\', \'' . $row['matkhau'] . '\', \'' . $row['diachi'] . '\', ' . $row['dienthoai'] . ')">Sửa</button>';
        $content .= '<a href="xoa_tai_khoan.php?makhach=' . $row['makhach'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa tài khoản này không?\')">Xóa</a>';
        $content .= '</td>';
        $content .= '</tr>';
    }
} else {
    $content .= '<tr><td colspan="6">Không có tài khoản nào</td></tr>';
}

$content .= '</tbody></table>';
$content .= '</div>';

// Bao gồm layout
include('layout.php');

// Đóng kết nối
mysqli_close($conn);
?>

<!-- Modal Thêm Tài Khoản -->
<div class="modal fade" id="themTaiKhoanModal" tabindex="-1" aria-labelledby="themTaiKhoanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="them_tai_khoan.php">
        <div class="modal-header">
          <h5 class="modal-title" id="themTaiKhoanModalLabel">Thêm Tài Khoản</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="makhach" class="form-label">Mã Khách</label>
            <input type="text" class="form-control" id="makhach" name="makhach" required>
          </div>
          <div class="mb-3">
            <label for="tenkhach" class="form-label">Tên Khách</label>
            <input type="text" class="form-control" id="tenkhach" name="tenkhach" required>
          </div>
          <div class="mb-3">
            <label for="dienthoai" class="form-label">Điện Thoại</label>
            <input type="number" class="form-control" id="dienthoai" name="dienthoai" required>
          </div>
          <div class="mb-3">
            <label for="diachi" class="form-label">Địa Chỉ</label>
            <input type="text" class="form-control" id="diachi" name="diachi" required>
          </div>
          <div class="mb-3">
            <label for="matkhau" class="form-label">Mật Khẩu</label>
            <input type="password" class="form-control" id="matkhau" name="matkhau" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Thêm Tài Khoản</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Tài Khoản -->
<div class="modal fade" id="suaTaiKhoanModal" tabindex="-1" aria-labelledby="suaTaiKhoanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="sua_tai_khoan.php">
        <div class="modal-header">
          <h5 class="modal-title" id="suaTaiKhoanModalLabel">Sửa Tài Khoản</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_makhach" name="makhach">
          <div class="mb-3">
            <label for="edit_tenkhach" class="form-label">Tên Khách</label>
            <input type="text" class="form-control" id="edit_tenkhach" name="tenkhach" required>
          </div>
          <div class="mb-3">
            <label for="edit_dienthoai" class="form-label">Điện Thoại</label>
            <input type="number" class="form-control" id="edit_dienthoai" name="dienthoai" required>
          </div>
          <div class="mb-3">
            <label for="edit_diachi" class="form-label">Địa Chỉ</label>
            <input type="text" class="form-control" id="edit_diachi" name="diachi" required>
          </div>
          <div class="mb-3">
            <label for="edit_matkhau" class="form-label">Mật Khẩu</label>
            <input type="password" class="form-control" id="edit_matkhau" name="matkhau">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JavaScript để đổ dữ liệu vào form sửa -->
<script>
function fillEditForm(makhach, tenkhach, matkhau, diachi, dienthoai) {
    document.getElementById('edit_makhach').value = makhach;
    document.getElementById('edit_tenkhach').value = tenkhach;
    document.getElementById('edit_matkhau').value = matkhau;
    document.getElementById('edit_diachi').value = diachi;
    document.getElementById('edit_dienthoai').value = dienthoai;
}
</script>
