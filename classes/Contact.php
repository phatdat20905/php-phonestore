<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/format.php');

class Contact
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_contact($name, $email, $phone, $message)
    {
        $name = $this->db->link->real_escape_string($this->fm->validation($name));
        $email = $this->db->link->real_escape_string($this->fm->validation($email));
        $phone = $this->db->link->real_escape_string($this->fm->validation($phone));
        $message = $this->db->link->real_escape_string($this->fm->validation($message));

        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!'];
        }

        $query = "INSERT INTO contact (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Gửi liên hệ thành công!' : 'Lỗi khi gửi liên hệ'];
    }

    public function show_contact()
    {
        $query = "SELECT * FROM contact ORDER BY created_at DESC";
        return $this->db->select($query);
    }

    public function update_status($contact_id, $status)
    {
        $contact_id = $this->db->link->real_escape_string($contact_id);
        $status = $this->db->link->real_escape_string($status);
        $query = "UPDATE contact SET status = '$status' WHERE contact_id = '$contact_id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật trạng thái thành công!' : 'Lỗi khi cập nhật trạng thái'];
    }

    public function delete_contact($contact_id)
    {
        $contact_id = $this->db->link->real_escape_string($contact_id);
        $query = "DELETE FROM contact WHERE contact_id = '$contact_id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa liên hệ thành công!' : 'Lỗi khi xóa liên hệ'];
    }
}
?>