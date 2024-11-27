<?php
$conn = mysqli_connect('localhost', 'root', '', 'mobiledb');
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');
?>