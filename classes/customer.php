<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/format.php');

class Customer
{
    private $db;
    public $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_customer($data, $files)
    {
        $name = $this->db->link->real_escape_string($this->fm->validation($data['name']));
        $email = $this->db->link->real_escape_string($this->fm->validation($data['email']));
        $phone = $this->db->link->real_escape_string($this->fm->validation($data['phone']));
        $address = $this->db->link->real_escape_string($this->fm->validation($data['address']));
        $city = $this->db->link->real_escape_string($this->fm->validation($data['city']));
        $country = $this->db->link->real_escape_string($this->fm->validation($data['country']));
        $zipcode = $this->db->link->real_escape_string($this->fm->validation($data['zipcode']));
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $image = $this->upload_image($files['image']);

        $query = "INSERT INTO customer (name, email, phone, address, city, country, zipcode, password, image) 
                  VALUES ('$name', '$email', '$phone', '$address', '$city', '$country', '$zipcode', '$password', '$image')";
        $result = $this->db->insert($query);
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm khách hàng'];
    }

    public function login_customer($data)
    {
        $email = $this->db->link->real_escape_string($this->fm->validation($data['email']));
        $password = $this->fm->validation($data['password']);

        if (empty($email) || empty($password)) {
            return "Email và mật khẩu không được để trống!";
        }
        $query = "SELECT * FROM customer WHERE email = '$email' LIMIT 1";
        $result = $this->db->select($query);
        if ($result) {
            $value = $result->fetch_assoc();
            if (password_verify($password, $value['password'])) {
                Session::set('customer_login', true);
                Session::set('customer_id', $value['customer_id']);
                Session::set('customer_name', $value['name']);
                return "Đăng nhập thành công!";
            } else {
                return "Mật khẩu không đúng!";
            }
        } else {
            return "Email không tồn tại!";
        }
    }

    public function show_customers()
    {
        $query = "SELECT * FROM customer ORDER BY created_at DESC";
        return $this->db->select($query);
    }

    public function show_customer()
    {
        $query = "SELECT * FROM customer ORDER BY customer_id DESC";
        return $this->db->select($query);
    }

    public function get_customer_by_id($id)
    {
        $id = $this->db->link->real_escape_string($this->fm->validation($id));
        $query = "SELECT * FROM customer WHERE customer_id = '$id'";
        return $this->db->select($query);
    }


    public function get_total_customers()
    {
        $query = "SELECT COUNT(*) as total FROM customer";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_new_customers()
    {
        $query = "SELECT COUNT(*) as total FROM customer WHERE DATE(created_at) = CURDATE()";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function update_customer($data, $files, $id)
    {
        $name = $this->db->link->real_escape_string($this->fm->validation($data['name']));
        $email = $this->db->link->real_escape_string($this->fm->validation($data['email']));
        $phone = $this->db->link->real_escape_string($this->fm->validation($data['phone']));
        $address = $this->db->link->real_escape_string($this->fm->validation($data['address']));
        $city = $this->db->link->real_escape_string($this->fm->validation($data['city']));
        $country = $this->db->link->real_escape_string($this->fm->validation($data['country']));
        $zipcode = $this->db->link->real_escape_string($this->fm->validation($data['zipcode']));
        $id = $this->db->link->real_escape_string($this->fm->validation($id));

        // Lấy ảnh hiện tại từ database nếu không upload ảnh mới
        $current_image = '';
        $get_current = $this->get_customer_by_id($id);
        if ($get_current && $get_current->num_rows > 0) {
            $current_data = $get_current->fetch_assoc();
            $current_image = $current_data['image'];
        }

        // Xử lý upload ảnh mới nếu có
        $image = $current_image;
        if (!empty($files['image']['name'])) {
            $new_image = $this->upload_image($files['image']);
            if ($new_image) {
                $image = $new_image;
            } else {
                return ['message' => 'Lỗi khi upload ảnh!'];
            }
        }

        $query = "UPDATE customer SET 
                  name = '$name', 
                  email = '$email', 
                  phone = '$phone', 
                  address = '$address', 
                  city = '$city', 
                  country = '$country', 
                  zipcode = '$zipcode', 
                  image = '$image' 
                  WHERE customer_id = '$id'";
        $result = $this->db->update($query);
        return ['message' => $result ? 'Cập nhật khách hàng thành công' : 'Cập nhật khách hàng thất bại'];
    }

    public function delete_customer($id)
    {
        $id = $this->db->link->real_escape_string($this->fm->validation($id));
        $query = "DELETE FROM customer WHERE customer_id = '$id'";
        $result = $this->db->delete($query);
        return ['message' => $result ? 'Xóa khách hàng thành công' : 'Xóa khách hàng thất bại'];
    }

    private function upload_image($file)
    {
        $target_dir = "admin/uploads/"; // Đồng bộ với profile.php và editprofile.php
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
        }
        $file_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $file_name;
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Kiểm tra định dạng file
        if (!in_array($file_ext, $allowed_types)) {
            return false;
        }
        // Kiểm tra lỗi upload và di chuyển file
        if ($file['error'] === UPLOAD_ERR_OK && move_uploaded_file($file["tmp_name"], $target_file)) {
            return $file_name;
        }
        return false;
    }

    // Kiểm tra mật khẩu cũ
    public function check_old_password($customer_id, $old_password)
    {
        $customer_id = $this->db->link->real_escape_string($this->fm->validation($customer_id));
        $query = "SELECT password FROM customer WHERE customer_id = '$customer_id' LIMIT 1";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return password_verify($old_password, $row['password']);
        }
        return false;
    }

    // Cập nhật mật khẩu mới
    public function update_password($customer_id, $new_password)
    {
        $customer_id = $this->db->link->real_escape_string($this->fm->validation($customer_id));
        $new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $query = "UPDATE customer SET password = '$new_password' WHERE customer_id = '$customer_id'";
        $result = $this->db->update($query);
        return $result;
    }
}
