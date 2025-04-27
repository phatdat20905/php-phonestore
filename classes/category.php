<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/format.php');

class Category
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_category($cat_name)
    {
        $cat_name = $this->db->link->real_escape_string($cat_name);
        $query = "INSERT INTO category (cat_name) VALUES ('$cat_name')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm danh mục'];
    }

    public function show_category()
    {
        $query = "SELECT * FROM category ORDER BY cat_id DESC";
        return $this->db->select($query);
    }

    public function get_category_by_id($id)
    {
        $query = "SELECT * FROM category WHERE cat_id = '$id'";
        return $this->db->select($query);
    }

    public function update_category($cat_name, $id)
    {
        $cat_name = $this->db->link->real_escape_string($cat_name);
        $query = "UPDATE category SET cat_name = '$cat_name' WHERE cat_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_category($id)
    {
        $query = "DELETE FROM category WHERE cat_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }

    public function show_category_frontend()
    {
        $query = "SELECT * FROM category ORDER BY cat_id ASC";
        return $this->db->select($query);
    }


    public function get_category_name($cat_id)
    {
        $cat_id = $this->db->link->real_escape_string($this->fm->validation($cat_id));
        $query = "SELECT cat_name FROM category WHERE cat_id = '$cat_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['cat_name'] : 'Không xác định';
    }
}
?>