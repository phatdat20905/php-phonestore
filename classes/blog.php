<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');

class Blog
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_blog($data, $files)
    {
        $blog_title = $this->db->link->real_escape_string($data['blog_title']);
        $post_cat_id = $this->db->link->real_escape_string($data['post_cat_id']);
        $description = $this->db->link->real_escape_string($data['description']);
        $content = $this->db->link->real_escape_string($data['content']);
        $status = $this->db->link->real_escape_string($data['status']);
        $image = $this->upload_image($files['image']);

        $query = "INSERT INTO blog (blog_title, post_cat_id, description, content, image, status) 
                  VALUES ('$blog_title', '$post_cat_id', '$description', '$content', '$image', '$status')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm tin tức'];
    }

    public function show_blog()
    {
        $query = "SELECT b.*, pc.title 
                  FROM blog b 
                  JOIN post_category pc ON b.post_cat_id = pc.post_cat_id 
                  ORDER BY b.blog_id DESC";
        return $this->db->select($query);
    }

    public function get_blog_by_id($id)
    {
        $query = "SELECT * FROM blog WHERE blog_id = '$id'";
        return $this->db->select($query);
    }

    public function update_blog($data, $files, $id)
    {
        $blog_title = $this->db->link->real_escape_string($data['blog_title']);
        $post_cat_id = $this->db->link->real_escape_string($data['post_cat_id']);
        $description = $this->db->link->real_escape_string($data['description']);
        $content = $this->db->link->real_escape_string($data['content']);
        $status = $this->db->link->real_escape_string($data['status']);
        $image = !empty($files['image']['name']) ? $this->upload_image($files['image']) : $data['current_image'];

        $query = "UPDATE blog SET blog_title = '$blog_title', post_cat_id = '$post_cat_id', description = '$description', 
                  content = '$content', image = '$image', status = '$status' WHERE blog_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_blog($id)
    {
        $query = "DELETE FROM blog WHERE blog_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }

    private function upload_image($file)
    {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($file["tmp_name"], $target_file);
        return $file_name;
    }


    public function get_blogs_by_category($post_cat_id)
    {
        $post_cat_id = $this->db->link->real_escape_string($post_cat_id);
        $query = "SELECT * FROM blog WHERE post_cat_id = '$post_cat_id' ORDER BY blog_id DESC";
        return $this->db->select($query);
    }
}
