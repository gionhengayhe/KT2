# Hệ Thống Quản Lý Sinh Viên

Ứng dụng quản lý sinh viên đơn giản được phát triển bằng PHP và MySQL.

## Cấu trúc cơ sở dữ liệu

```sql
CREATE DATABASE Test1;
USE Test1;

CREATE TABLE NganhHoc (
    MaNganh VARCHAR(4) PRIMARY KEY,
    TenNganh VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL
);

CREATE TABLE SinhVien (
    MaSV VARCHAR(10) PRIMARY KEY,
    HoTen VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL,
    GioiTinh VARCHAR(5) CHARACTER SET utf8mb4,
    NgaySinh DATE,
    Hinh VARCHAR(50),
    MaNganh VARCHAR(4),
    FOREIGN KEY (MaNganh) REFERENCES NganhHoc(MaNganh) ON DELETE CASCADE ON UPDATE CASCADE
);
```

## Cài đặt

1. Cài đặt XAMPP, WAMP, hoặc bất kỳ máy chủ PHP và MySQL nào.
2. Clone hoặc tải xuống mã nguồn vào thư mục web server (htdocs, www, v.v.).
3. Tạo cơ sở dữ liệu mới với tên "Test1" và import cấu trúc từ file SQL.
4. Mở file `app/config/database.php` và cập nhật thông tin kết nối cơ sở dữ liệu (username, password) nếu cần.
5. Truy cập ứng dụng qua trình duyệt: `http://localhost/KT2/`

## Tính năng

- **Quản lý Sinh Viên**: Thêm, xem, sửa, xóa thông tin sinh viên.
- **Quản lý Ngành Học**: Thêm, xem, sửa, xóa thông tin ngành học.
- **Upload Hình Ảnh**: Hỗ trợ upload hình ảnh cho sinh viên.

## Cấu trúc thư mục

```
/
├── app/
│   ├── config/         # Cấu hình cơ sở dữ liệu
│   ├── controllers/    # Các controller xử lý logic
│   ├── models/         # Các model tương tác với cơ sở dữ liệu
│   └── views/          # Giao diện người dùng
├── uploads/            # Thư mục lưu trữ hình ảnh
├── index.php           # File chính để xử lý request
└── .htaccess           # Cấu hình rewrite URL
```

## Yêu cầu Hệ thống

- PHP 7.0 trở lên
- MySQL 5.6 trở lên
- PDO PHP Extension
- GD Library (để xử lý hình ảnh)

## Tác giả

Hệ thống được phát triển cho mục đích học tập.
