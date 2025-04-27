<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');
include_once($filepath . '/../helpers/format.php');

class Wishlist
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function add_to_wishlist($customer_id, $product_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $product_id = $this->db->link->real_escape_string($product_id);

        $check = "SELECT * FROM wishlist WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
        if ($this->db->select($check)) {
            return "Sản phẩm đã có trong danh sách yêu thích!";
        }

        $query = "INSERT INTO wishlist (customer_id, product_id) VALUES ('$customer_id', '$product_id')";
        return $this->db->insert($query) ? true : "Lỗi khi thêm vào yêu thích!";
    }

    public function get_wishlist($customer_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "SELECT w.*, p.product_name, p.price, p.image 
                  FROM wishlist w 
                  JOIN product p ON w.product_id = p.product_id 
                  WHERE w.customer_id = '$customer_id'";
        return $this->db->select($query);
    }

    public function del_wishlist_product($customer_id, $product_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $product_id = $this->db->link->real_escape_string($product_id);
        $query = "DELETE FROM wishlist WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
        return $this->db->delete($query);
    }

    public function del_all_wishlist($customer_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "DELETE FROM wishlist WHERE customer_id = '$customer_id'";
        return $this->db->delete($query);
    }

    public function get_all_wishlist()
    {
        $query = "SELECT w.*, cu.name as customer_name, p.product_name 
                  FROM wishlist w 
                  JOIN customer cu ON w.customer_id = cu.customer_id 
                  JOIN product p ON w.product_id = p.product_id";
        return $this->db->select($query);
    }

    public function del_wishlist($wishlist_id)
    {
        $wishlist_id = $this->db->link->real_escape_string($wishlist_id);
        $query = "DELETE FROM wishlist WHERE wishlist_id = '$wishlist_id'";
        return $this->db->delete($query);
    }
}
?>