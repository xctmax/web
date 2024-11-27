<?php
include 'connect.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $brand_id = $_POST['brand_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];    

    if (empty($product_name)) {
        $message = "Tên sản phẩm không được để trống.";
    } elseif ($price <= 0 || !is_numeric($price)) {
        $message = "Đơn giá phải là số dương.";
    } elseif ($quantity <= 0 || !is_numeric($quantity)) {
        $message = "Số lượng phải là số dương.";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $allowed_types = ['image/jpg', 'image/png', 'image/webp'];
            $file_type = $_FILES['image']['type'];
            $file_size = $_FILES['image']['size'];
            if (!in_array($file_type, $allowed_types) || $file_size > 2 * 1024 * 1024) {
                $message = "Ảnh không hợp lệ hoặc quá 2MB.";
            } else {
                $image_path = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }
        } else {  
            $image_path = null;
        }

        $check_sql = "SELECT * FROM products WHERE product_name = '$product_name'";
        $result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($result) > 0) {
            $update_sql = "UPDATE products SET quantity = quantity + $quantity WHERE product_name = '$product_name'";
            if (mysqli_query($conn, $update_sql)) {
                echo "<script>alert('Sản phẩm đã có trong database, số lượng đã được cập nhật'); window.location.href = 'index.php';</script>";
            }
        } else {
            $insert_sql = "INSERT INTO products (product_name, brand_id, image, description, price, quantity)
                            VALUES ('$product_name', $brand_id, '$image_path', '$description', $price, $quantity)";
            if (mysqli_query($conn, $insert_sql)) {
                // Quay về index.php sau khi thêm thành công
                header('Location: index.php');
                exit();
            } else {
                echo "<script>alert('Đã có lỗi, không nhập được sản phẩm vào database'); </script>";
            }
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="head_line"> <h1>Nhập sản phẩm</h1> </div>
    <div class="form">
    <form method="POST" enctype="multipart/form-data">
        <label for="product_name">Tên sản phẩm:</label>
        <input type="text" name="product_name" required><br><br>
        
        <label for="brand_id">Thương hiệu:</label>
        <select name="brand_id">
            <?php
            $brand_sql = "SELECT * FROM brands";
            $brand_result = mysqli_query($conn, $brand_sql);
            while ($row = mysqli_fetch_assoc($brand_result)) {
                echo "<option value='{$row['brand_id']}'>{$row['brand_name']}</option>";
            }
            ?>
        </select><br><br>        
        <label for="image">Hình ảnh:</label>  
        <input type="file" name="image"><br><br>        
        <label for="description">Giới thiệu:</label> 
        <textarea name="description"></textarea><br><br>       
        <label for="price">Đơn giá:</label>  
        <input type="number" name="price" required><br><br>        
        <label for="quantity">Số lượng:</label>  
        <input type="number" name="quantity" required><br><br>
        <button type="submit">Thêm sản phẩm</button>
    </form>
    </div>
    <div class='bottom'><a href="index.php"> Về Trang chủ </a> </div>
</div>
</body>
</html>
