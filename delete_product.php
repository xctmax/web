<?php
include 'connect.php';
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $deleteQuery = "DELETE FROM products WHERE product_id = $product_id";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>alert('Đã xóa thành công'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa sách'); window.location.href = 'index.php';</script>";
    }
}
?>