<?php
include 'connect.php';
$limit = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_page = ($page - 1) * $limit;

// Đếm tổng số sản phẩm
$sql = "SELECT COUNT(*) AS total FROM products";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}
$result_row = mysqli_fetch_assoc($result);
$totalProducts = $result_row['total'];
$totalPages = ceil($totalProducts / $limit);

// Lấy dữ liệu phân trang
$sql_page = "
    SELECT products.*, brands.brand_name 
    FROM products 
    JOIN brands ON products.brand_id = brands.brand_id 
    LIMIT $start_page, $limit";
$page_result = mysqli_query($conn, $sql_page);
if (!$page_result) {
    die("Lỗi truy vấn SQL (phân trang): " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mobile Web</title>
    <link rel="stylesheet" href="style.css">    
</head>
<body>
<div class="container">
    <div class="head_line"> QUẢN LÝ ĐIỆN THOẠI </div>
    <p class="total">Tổng số máy hiện có: <?php echo $totalProducts; ?></p>
    <table>
        <tr>
            <th>STT</th>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Thương hiệu</th>
            <th>Ảnh</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thao tác</th>
        </tr>
        <?php $stt = $start_page + 1;?>      
        <?php while ($product = mysqli_fetch_assoc($page_result)) { ?>
        <tr>
            <td class='center'><?php echo $stt; ?></td>
            <td><?php echo $product['product_id']; ?></td>
            <td><?php echo $product['product_name']; ?></td> 
            <td><?php echo $product['brand_name']; ?></td>       
            <td class='center'><img src= "<?php echo $product['image']; ?>"  ></td>
            <td class='right'><?php echo number_format( $product['price'], 0, ',', '.'); ?> đ </td>
            <td class='right'><?php echo $product['quantity']; ?></td>
            <td> 
            <div class='edit'> <a href="edit_product.php?id=<?php echo $product['product_id']; ?>"> Sửa</a></div><br><br>
            <div class='delete'> <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a> </div>
            </td>
        </tr>
        <?php $stt++;?>
    <?php }  ?>
    </table>
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo "<a href='index.php?page=$i' class='$activeClass'>$i</a>";
        }
        ?>
    </div> 
    <br>
    <div class='bottom'><a href="add_product.php" >Thêm sản phẩm</a> </div>
</div> 
</body>
</html>