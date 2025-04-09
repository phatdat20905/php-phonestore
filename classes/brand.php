<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');

class Brand
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_brand($brand_name)
    {
        $brand_name = $this->db->link->real_escape_string($brand_name);
        $query = "INSERT INTO brand (brand_name) VALUES ('$brand_name')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm thương hiệu'];
    }

    public function show_brand()
    {
        $query = "SELECT * FROM brand ORDER BY brand_id DESC";
        return $this->db->select($query);
    }

    public function get_brand_by_id($id)
    {
        $query = "SELECT * FROM brand WHERE brand_id = '$id'";
        return $this->db->select($query);
    }

    public function update_brand($brand_name, $id)
    {
        $brand_name = $this->db->link->real_escape_string($brand_name);
        $query = "UPDATE brand SET brand_name = '$brand_name' WHERE brand_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_brand($id)
    {
        $query = "DELETE FROM brand WHERE brand_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }
}
