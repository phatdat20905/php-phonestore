<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/format.php');

class Brand
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
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

    public function get_brand_name($brand_id)
    {
        $brand_id = $this->db->link->real_escape_string($this->fm->validation($brand_id));
        $query = "SELECT brand_name FROM brand WHERE brand_id = '$brand_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['brand_name'] : 'Không xác định';
    }
}
