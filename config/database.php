
<?php

$host = '127.0.0.1';
$dbname = 'website_cua_hang_ban_sach_online';
$username = 'root';
$password = 'Thienhuong00';

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $conn->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Không thể kết nối database. Hãy kiểm tra cấu hình MySQL.');
}
