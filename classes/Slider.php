<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');

class Slider
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_slider($data, $files)
    {
        $slider_name = $this->db->link->real_escape_string($data['slider_name']);
        $type = $this->db->link->real_escape_string($data['type']);
        $image = $this->upload_image($files['image']);

        $query = "INSERT INTO slider (slider_name, slider_image, type) VALUES ('$slider_name', '$image', '$type')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm slider'];
    }

    public function show_slider()
    {
        $query = "SELECT * FROM slider ORDER BY slider_id DESC";
        return $this->db->select($query);
    }

    public function update_slider($id, $type)
    {
        $type = $this->db->link->real_escape_string($type);
        $query = "UPDATE slider SET type = '$type' WHERE slider_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_slider($id)
    {
        $query = "DELETE FROM slider WHERE slider_id = '$id'";
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

    public function get_active_sliders()
    {
        $query = "SELECT * FROM slider WHERE type = 1 ORDER BY slider_id DESC";
        return $this->db->select($query);
    }
}
