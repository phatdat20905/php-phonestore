<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/format.php');
class Inventory
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function update_inventory($product_id, $quantity_change, $note)
    {
        $product_id = $this->db->link->real_escape_string($product_id);
        $quantity_change = $this->db->link->real_escape_string($quantity_change);
        $note = $this->db->link->real_escape_string($note);
        $query = "INSERT INTO inventory_history (product_id, quantity_change, note) VALUES ('$product_id', '$quantity_change', '$note')";
        $result = $this->db->insert($query);
        if ($result) {
            $this->db->update("UPDATE inventory SET quantity = quantity + $quantity_change WHERE product_id = '$product_id'");
        }
        return ['success' => $result, 'message' => $result ? 'Cập nhật kho thành công!' : 'Lỗi khi cập nhật kho'];
    }

    public function show_inventory()
    {
        $query = "SELECT i.*, p.product_name 
                  FROM inventory i 
                  JOIN product p ON i.product_id = p.product_id";
        return $this->db->select($query);
    }

    public function show_history($product_id = null)
    {
        $where = $product_id ? "WHERE h.product_id = '$product_id'" : "";
        $query = "SELECT h.*, p.product_name 
                  FROM inventory_history h 
                  JOIN product p ON h.product_id = p.product_id 
                  $where 
                  ORDER BY h.created_at DESC";
        return $this->db->select($query);
    }


    public function get_quantity_by_product($product_id)
    {
        $product_id = $this->db->link->real_escape_string($this->fm->validation($product_id));
        $query = "SELECT quantity FROM inventory WHERE product_id = '$product_id'";
        return $this->db->select($query);
    }
}
?>