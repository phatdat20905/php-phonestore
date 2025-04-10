<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');
include_once($filepath . '/../helpers/format.php');

class Compare
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function add_to_compare($customer_id, $product_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $product_id = $this->db->link->real_escape_string($product_id);

        $check = "SELECT * FROM compare WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
        if ($this->db->select($check)) {
            return "Sản phẩm đã có trong danh sách so sánh!";
        }

        $query = "INSERT INTO compare (customer_id, product_id) VALUES ('$customer_id', '$product_id')";
        return $this->db->insert($query) ? true : "Lỗi khi thêm vào so sánh!";
    }

    public function get_compare($customer_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "SELECT c.*, p.product_name, p.price, p.image 
                  FROM compare c 
                  JOIN product p ON c.product_id = p.product_id 
                  WHERE c.customer_id = '$customer_id'";
        return $this->db->select($query);
    }

    public function del_compare_product($customer_id, $product_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $product_id = $this->db->link->real_escape_string($product_id);
        $query = "DELETE FROM compare WHERE customer_id = '$customer_id' AND product_id = '$product_id'";
        return $this->db->delete($query);
    }

    public function del_all_compare($customer_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "DELETE FROM compare WHERE customer_id = '$customer_id'";
        return $this->db->delete($query);
    }

    public function get_all_compare()
    {
        $query = "SELECT c.*, cu.name as customer_name, p.product_name 
                  FROM compare c 
                  JOIN customer cu ON c.customer_id = cu.customer_id 
                  JOIN product p ON c.product_id = p.product_id";
        return $this->db->select($query);
    }

    public function del_compare($compare_id)
    {
        $compare_id = $this->db->link->real_escape_string($compare_id);
        $query = "DELETE FROM compare WHERE compare_id = '$compare_id'";
        return $this->db->delete($query);
    }
}
