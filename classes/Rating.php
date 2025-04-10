<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');
include_once($filepath . '/../helpers/format.php');

class Rating
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    // Thêm đánh giá
    public function insert_rating($product_id, $customer_id, $rating)
    {
        $product_id = $this->db->link->real_escape_string($this->fm->validation($product_id));
        $customer_id = $this->db->link->real_escape_string($this->fm->validation($customer_id));
        $rating = $this->db->link->real_escape_string($this->fm->validation($rating));

        if (empty($product_id) || empty($customer_id) || empty($rating)) {
            return ['message' => 'Vui lòng điền đầy đủ thông tin bắt buộc'];
        }

        $query = "INSERT INTO rating (product_id, customer_id, rating) 
                  VALUES ('$product_id', '$customer_id', '$rating')";
        $result = $this->db->insert($query);
        if ($result) {
            return ['message' => 'Thêm đánh giá thành công'];
        } else {
            return ['message' => 'Thêm đánh giá thất bại'];
        }
    }

    // Hiển thị tất cả đánh giá
    public function show_ratings()
    {
        $query = "SELECT r.*, p.product_name, c.name AS customer_name 
                  FROM rating r 
                  INNER JOIN product p ON r.product_id = p.product_id 
                  INNER JOIN customer c ON r.customer_id = c.customer_id 
                  ORDER BY r.created_at DESC";
        return $this->db->select($query);
    }

    // Xóa đánh giá
    public function delete_rating($id)
    {
        $id = $this->db->link->real_escape_string($this->fm->validation($id));
        $query = "DELETE FROM rating WHERE rating_id = '$id'";
        $result = $this->db->delete($query);
        if ($result) {
            return ['message' => 'Xóa đánh giá thành công'];
        } else {
            return ['message' => 'Xóa đánh giá thất bại'];
        }
    }
    public function get_rating_customer($product_id, $customer_id)
    {
        $product_id = $this->db->link->real_escape_string($product_id);
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "SELECT rating FROM rating WHERE product_id = '$product_id'";
        return $this->db->select($query);
    }

    public function get_rating($product_id)
    {
        $product_id = $this->db->link->real_escape_string($product_id);
        $query = "SELECT rating FROM rating WHERE product_id = '$product_id'";
        return $this->db->select($query);
    }
}
