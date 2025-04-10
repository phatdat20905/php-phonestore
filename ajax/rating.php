<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
$db = new Database();

if (isset($_POST['index'])) {
    $index = $_POST['index'];
    $product_id = $_POST['product_id'];
    $customer_id = $_POST['customer_id'];
    $query = "INSERT INTO rating(product_id, customer_id, rating) VALUES('$product_id', '$customer_id', '$index')";
    $result = $db->insert($query);
    if ($result) {
        echo "successfully";
    } else {
        echo "Failed";
    }
}
