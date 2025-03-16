<?php
session_start();
include("../config/dbcon.php");
include("../functions/myfunctions.php");

if (isset($_POST['order'])) {
    $user_id    = $_SESSION['auth_user']['id'];
    $product_id = $_POST['product_id'];
    $quantity   = $_POST['quantity'];

    $product = getByID("products", $product_id);
    if (mysqli_num_rows($product) > 0) {
        $product = mysqli_fetch_array($product);
        $slug    = $product['slug'];
        if ($quantity != "" && $quantity <= $product['qty']) {
            $selling_price  = $product['selling_price'];
            $insert_query   = "INSERT INTO order_detail (`user_id`, `product_id`, `selling_price`, `quantity`) VALUES ('$user_id','$product_id','$selling_price','$quantity')";
            $insert_query_run = mysqli_query($conn, $insert_query);
            $_SESSION['giohang'] = "Đã Thêm Sản Phẩm Vào Giỏ Hàng";
            
            $previousPage = $_SERVER['HTTP_REFERER']; // Lấy trang trước đó
            header("Location: $previousPage");
        } else {
            $_SESSION['message'] = "Số lượng sản phẩm không phù hợp";
            header("Location: ../product-detail.php?slug=$slug");
        }
    } else {
        $_SESSION['message'] = "Đã xảy ra lỗi không đáng có";
        header("Location: ../products.php");
    }
}
unset($_SESSION['message']);
