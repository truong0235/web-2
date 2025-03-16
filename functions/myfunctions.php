<?php
include("../config/dbcon.php");
function getAll($table)
{
    global $conn;
    $query= "SELECT * FROM $table ORDER BY id DESC";
    return $query_run= mysqli_query($conn, $query);
}
function getByID($table,$id)
{
    global $conn;
    $query= "SELECT * FROM $table WHERE id='$id'";
    return $query_run= mysqli_query($conn, $query);
}

function totalValue($table){
    global $conn;
    $query= "SELECT COUNT(*) as `number` FROM $table";
    $totalValue = mysqli_query($conn, $query);
    $totalValue = mysqli_fetch_array($totalValue);
    return $totalValue['number'];
}
function getAllUsers(){
    global $conn;
    $query= "SELECT `users`.*, COUNT(`order_detail`.`id`) AS `total_buy` FROM `users`
            LEFT JOIN `order_detail` ON `users`.`id` = `order_detail`.`user_id`
            GROUP BY `users`.`id`
            ORDER BY `users`.`creat_at` DESC";
    return $query_run= mysqli_query($conn, $query);
}

// order
function getAllOrder($type = -1){
    global $conn;
    $getStatus = "1,2,3,4";
    if ($type != -1){
        $getStatus = $type . "";
    }
    $query =    "SELECT `orders`.*,COUNT(`order_detail`.`id`) as`quantity`,
                `users`.`name`,`users`.`email`,`users`.`phone`,`users`.`address` FROM`orders`
                JOIN `users` ON `orders`.`user_id` = `users`.`id`
                LEFT JOIN `order_detail` ON `order_detail`.`order_id` = `orders`.`id`
                WHERE`orders`.`status` IN($getStatus)
                GROUP BY `orders`.`id`
                ORDER BY `orders`.`id` DESC";
    return $query_run= mysqli_query($conn, $query);
}

function getOrderDetail($order_id){
    global $conn;
    $query =    "SELECT `users`.`name`,`users`.`email`,`users`.`phone`,`users`.`address`,
                `products`.`name` as `name_product`, `products`.`selling_price`,`products`.`image`,
                `order_detail`.*  FROM `order_detail` 
                JOIN `users` ON `order_detail`.`user_id` = `users`.`id`
                JOIN `products` ON `products`.`id` = `order_detail`.`product_id`
                WHERE `order_id` = '$order_id'";
    return mysqli_query($conn, $query);
}

function totalPriceGet(){
    global $conn;
    $query = "SELECT selling_price * quantity as price FROM `order_detail` WHERE `status` = 4";
    $prices= mysqli_query($conn, $query);
    $total_price = 0;
    foreach($prices as $price){
        $total_price += $price['price'];
    }
    return $total_price;
}

function redirect($url, $message)
{
    $_SESSION['message']= $message;
    header("Location:" . $url);
    exit();
}

function getFilteredProducts($minPrice, $maxPrice, $category, $brand, $rating, $sortBy)
{
    global $conn;
    $query = "SELECT products.* FROM products WHERE selling_price BETWEEN $minPrice AND $maxPrice";

    // Lọc theo danh mục
    if (!empty($category)) {
        $query .= " AND category_id = (SELECT id FROM categories WHERE slug = '$category')";
    }

    // Lọc theo thương hiệu
    if (!empty($brand)) {
        $query .= " AND brand = '$brand'";
    }

    // Lọc theo đánh giá trung bình
    if ($rating > 0) {
        $query .= " AND id IN (SELECT product_id FROM reviews GROUP BY product_id HAVING AVG(rating) >= $rating)";
    }

    // Sắp xếp kết quả
    switch ($sortBy) {
        case 'bestseller':
            $query .= " ORDER BY (SELECT COUNT(*) FROM order_detail WHERE product_id = products.id) DESC";
            break;
        case 'price_asc':
            $query .= " ORDER BY selling_price ASC";
            break;
        case 'price_desc':
            $query .= " ORDER BY selling_price DESC";
            break;
        default:
            $query .= " ORDER BY created_at DESC"; // Mặc định: Mới nhất
            break;
    }

    return mysqli_query($conn, $query);
}

function getBrands()
{
    global $conn;
    $query = "SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != ''";
    return mysqli_query($conn, $query);
}

function getAverageRatings()
{
    global $conn;
    $query = "SELECT product_id, AVG(rating) as avg_rating FROM reviews GROUP BY product_id";
    return mysqli_query($conn, $query);
}

?>