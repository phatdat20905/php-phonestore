<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');
include_once($filepath . '/../helpers/format.php');


class Admin
{
    private $db;
    public $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function login_admin($username, $password)
    {
        $username = $this->db->link->real_escape_string($username);
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                Session::set('adminlogin', true);
                Session::set('adminId', $admin['admin_id']);
                Session::set('adminName', $admin['name']);
                return '';
            }
        }
        return 'Tên đăng nhập hoặc mật khẩu không đúng!';
    }

    public function insert_admin($data, $files)
    {
        $username = $this->db->link->real_escape_string($this->fm->validation($data['username']));
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $name = $this->db->link->real_escape_string($this->fm->validation($data['name']));
        $email = $this->db->link->real_escape_string($this->fm->validation($data['email']));
        $image = $this->upload_image($files['image']);

        $query = "INSERT INTO admin (username, password, name, email, image) 
                  VALUES ('$username', '$password', '$name', '$email', '$image')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm admin'];
    }

    public function show_admin()
    {
        $query = "SELECT * FROM admin ORDER BY admin_id DESC";
        return $this->db->select($query);
    }

    public function get_admin_by_id($id)
    {
        $id = $this->db->link->real_escape_string($this->fm->validation($id));
        $query = "SELECT * FROM admin WHERE admin_id = '$id'";
        return $this->db->select($query);
    }

    public function update_admin($data, $files, $id)
    {
        $username = $this->db->link->real_escape_string($this->fm->validation($data['username']));
        $name = $this->db->link->real_escape_string($this->fm->validation($data['name']));
        $email = $this->db->link->real_escape_string($this->fm->validation($data['email']));
        $id = $this->db->link->real_escape_string($this->fm->validation($id));
        $image = !empty($files['image']['name']) ? $this->upload_image($files['image']) : $data['current_image'];

        $query = "UPDATE admin SET username = '$username', name = '$name', email = '$email', image = '$image' 
                  WHERE admin_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_admin($id)
    {
        $id = $this->db->link->real_escape_string($this->fm->validation($id));
        $query = "DELETE FROM admin WHERE admin_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }

    public function change_password($admin_id, $old_password, $new_password, $confirm_password)
    {
        $admin_id = $this->db->link->real_escape_string($this->fm->validation($admin_id));
        $old_password = $this->fm->validation($old_password);
        $new_password = $this->fm->validation($new_password);
        $confirm_password = $this->fm->validation($confirm_password);

        // Kiểm tra mật khẩu mới và xác nhận
        if ($new_password !== $confirm_password) {
            return ['message' => 'Mật khẩu xác nhận không khớp'];
        }

        // Lấy mật khẩu đã mã hóa từ database
        $query = "SELECT password FROM admin WHERE admin_id = '$admin_id'";
        $result = $this->db->select($query);
        if (!$result || $result->num_rows == 0) {
            return ['message' => 'Không tìm thấy tài khoản'];
        }

        $admin = $result->fetch_assoc();
        // Kiểm tra mật khẩu cũ
        if (!password_verify($old_password, $admin['password'])) {
            return ['message' => 'Mật khẩu cũ không đúng'];
        }

        // Mã hóa mật khẩu mới
        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

        // Cập nhật mật khẩu mới
        $update_query = "UPDATE admin SET password = '$new_password_hashed' WHERE admin_id = '$admin_id'";
        $update_result = $this->db->update($update_query);
        if ($update_result) {
            return ['message' => 'Đổi mật khẩu thành công'];
        } else {
            return ['message' => 'Đổi mật khẩu thất bại'];
        }
    }

    private function upload_image($file)
    {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($file["tmp_name"], $target_file);
        return $file_name;
    }

    public function get_total_revenue()
    {
        $query = "SELECT SUM(total) as revenue FROM `order` WHERE status = 3"; // Chỉ tính status = 3 (Thành công)
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['revenue'] : 0;
    }

    public function get_revenue_by_day()
    {
        $query = "SELECT DATE(created_at) as day, SUM(total) as total 
                  FROM `order` 
                  WHERE status = 3 
                  GROUP BY DATE(created_at) 
                  ORDER BY day DESC";
        return $this->db->select($query);
    }

    public function get_revenue_by_month()
    {
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as total 
                  FROM `order` 
                  WHERE status = 3 
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                  ORDER BY month DESC";
        return $this->db->select($query);
    }

    public function get_revenue_by_year()
    {
        $query = "SELECT YEAR(created_at) as year, SUM(total) as total 
                  FROM `order` 
                  WHERE status = 3 
                  GROUP BY YEAR(created_at) 
                  ORDER BY year DESC";
        return $this->db->select($query);
    }
}
?>