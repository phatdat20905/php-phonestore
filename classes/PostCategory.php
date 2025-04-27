<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');

class PostCategory
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_category($title, $description, $status)
    {
        $title = $this->db->link->real_escape_string($title);
        $description = $this->db->link->real_escape_string($description);
        $status = $this->db->link->real_escape_string($status);
        $query = "INSERT INTO post_category (title, description, status) VALUES ('$title', '$description', '$status')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm danh mục'];
    }

    public function show_category()
    {
        $query = "SELECT * FROM post_category ORDER BY post_cat_id DESC";
        return $this->db->select($query);
    }

    public function get_category_by_id($id)
    {
        $query = "SELECT * FROM post_category WHERE post_cat_id = '$id'";
        return $this->db->select($query);
    }

    public function get_category_name($post_cat_id)
    {
        $post_cat_id = $this->db->link->real_escape_string($post_cat_id);
        $query = "SELECT title FROM post_category WHERE post_cat_id = '$post_cat_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['title'] : 'Không xác định';
    }

    public function update_category($title, $description, $status, $id)
    {
        $title = $this->db->link->real_escape_string($title);
        $description = $this->db->link->real_escape_string($description);
        $status = $this->db->link->real_escape_string($status);
        $query = "UPDATE post_category SET title = '$title', description = '$description', status = '$status' WHERE post_cat_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_category($id)
    {
        $query = "DELETE FROM post_category WHERE post_cat_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }
}
?>