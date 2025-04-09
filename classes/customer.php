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
        $image = !empty($files['image']['name']) ? $this->upload_image($files['image']) : $data['current_image'];

        $query = "UPDATE customer SET name = '$name', email = '$email', phone = '$phone', address = '$address', 
                  city = '$city', country = '$country', zipcode = '$zipcode', image = '$image' 
                  WHERE customer_id = '$id'";
        $result = $this->db->update($query);
        if ($result) {
            return ['message' => 'Cập nhật khách hàng thành công'];
        } else {
            return ['message' => 'Cập nhật khách hàng thất bại'];
        }
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
        if (empty($file['name'])) return null;
        $target_dir = "../uploads/";
        $file_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($file["tmp_name"], $target_file);
        return $file_name;
    }
}
