<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
}

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
        $update_sql = "UPDATE products SET product_name = '$product_name', brand_id = $brand_id, description = '$description', price = $price, quantity = $quantity WHERE product_id = $product_id";
        if (mysqli_query($conn, $update_sql)) {
            // QUAY VỀ INDEX.PHP    sau khi sửa sp
            header('Location: index.php');
            exit();
        } else {
            echo "<script>alert('Bị lỗi, chưa sửa được thông tin sản phẩm'); </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="head_line"> Sửa sản phẩm  </div>
    <div class="form">
    <form method="POST">
        <label for="product_name">Tên sản phẩm:</label>
        <input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required><br><br>       
        <label for="brand_id">Loại sản phẩm:</label>
        <select name="brand_id">
            <?php
            $brand_sql = "SELECT * FROM brands";
            $brand_result = mysqli_query($conn, $brand_sql);
            while ($row = mysqli_fetch_assoc($brand_result)) {
                $selected = ($row['brand_id'] == $product['brand_id']) ? 'selected' : '';
                echo "<option value='{$row['brand_id']}' $selected>{$row['brand_name']}</option>";
            }            
            ?>
        </select><br><br>        
        <label for="description">Giới thiệu:</label>  
        <textarea name="description"><?php echo $product['description']; ?></textarea><br><br>      
        <label for="price">Đơn giá:</label> 
        <input type="number" name="price" value="<?php echo $product['price']; ?>" required><br><br>        
        <label for="quantity">Số lượng:</label> 
        <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required><br><br>        
        <button type="submit">Cập nhật</button> 
     </form>
    </div>
    <div class='bottom'><a href="index.php" > Về Trang chủ </a> </div>
</div>
</body>
</html>
