<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');

class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_comment($data)
    {
        $product_id = $this->db->link->real_escape_string($data['product_id']);
        $customer_id = $this->db->link->real_escape_string($data['customer_id']);
        $content = $this->db->link->real_escape_string($data['content']);

        $query = "INSERT INTO comment (product_id, customer_id, content) VALUES ('$product_id', '$customer_id', '$content')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm bình luận thành công!' : 'Lỗi khi thêm bình luận'];
    }

    public function show_comments()
    {
        $query = "SELECT c.*, p.product_name, cu.name as customer_name 
                  FROM comment c 
                  JOIN product p ON c.product_id = p.product_id 
                  JOIN customer cu ON c.customer_id = cu.customer_id 
                  ORDER BY c.created_at DESC";
        return $this->db->select($query);
    }

    public function show_approved_comments()
    {
        $query = "SELECT c.*, p.product_name, cu.name as customer_name 
                  FROM comment c 
                  JOIN product p ON c.product_id = p.product_id 
                  JOIN customer cu ON c.customer_id = cu.customer_id 
                  WHERE c.status = 1 
                  ORDER BY c.created_at DESC";
        return $this->db->select($query);
    }
    public function approve_comment($id)
    {
        $query = "UPDATE comment SET status = 0 WHERE comment_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Duyệt thành công!' : 'Lỗi khi duyệt'];
    }

    public function delete_comment($id)
    {
        $query = "DELETE FROM comment WHERE comment_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }
}
?>