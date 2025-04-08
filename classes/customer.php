<?php

$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Customer
{
    private $db;
    public $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }
    public function insert_comment()
    {
        $commentName = $_POST['commentName'];
        $comment = $_POST['comment'];
        $productId = $_POST['productId'];
        if ($commentName == '' || $comment == '' || $productId == '') {
            $alert = "<span class='error'>Fields must be not empty</span>";
            return $alert;
        } else {
            $query = "INSERT INTO tbl_comment(commentName,comment,productId) VALUES('$commentName','$comment','$productId')";
            $result = $this->db->insert($query);

            if ($result) {
                $alert = "<span class='success'>Comment Inserted Successfully</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Comment Insert Failed</span>";
                return $alert;
            }
        }
    }
    public function insert_customer($data)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $city = mysqli_real_escape_string($this->db->link, $data['city']);
        $zipcode = mysqli_real_escape_string($this->db->link, $data['zipcode']);
        $email = mysqli_real_escape_string($this->db->link, $data['email']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $country = mysqli_real_escape_string($this->db->link, $data['country']);
        $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
        $password = mysqli_real_escape_string($this->db->link, md5($data['password']));
        if ($name == '' || $city == '' || $zipcode == '' || $email == '' || $address == '' || $country == '' || $phone == '' || $password == '') {
            $alert = "<span class='error'>Fields must be not empty</span>";
            return $alert;
        } else {
            $check_email = "SELECT * FROM tbl_customer WHERE email = '$email' LIMIT 1";
            $result_check = $this->db->select($check_email);
            if ($result_check) {
                $alert = "<span class='error'>Email already exists</span>";
                return $alert;
            } else {
                $query = "INSERT INTO tbl_customer(name, city, zipcode, email, address, country, phone, password) VALUES('$name', '$city', '$zipcode', '$email', '$address', '$country', '$phone', '$password')";
                $result = $this->db->insert($query);
                if ($result) {
                    $alert = "<span class='success'>Customer registered successfully</span>";
                    return $alert;
                } else {
                    $alert = "<span class='error'>Failed to register customer</span>";
                    return $alert;
                }
            }
        }
    }
    public function login_customer($data)
    {
        $email = mysqli_real_escape_string($this->db->link, $data['email']);
        $password = mysqli_real_escape_string($this->db->link, md5($data['password']));
        if ($email == '' || $password == '') {
            $alert = "<span class='error'>Email and Password must be not empty</span>";
            return $alert;
        } else {
            $check_login = "SELECT * FROM tbl_customer WHERE email = '$email' AND password = '$password'";
            $result_check = $this->db->select($check_login);
            if ($result_check) {
                $value = $result_check->fetch_assoc();
                Session::set('customer_login', true);
                Session::set('customer_id', $value['id']);
                Session::set('customer_name', $value['name']);
                header("Location:index.php");
            } else {
                $alert = "<span class='error'>Email or Password don't match</span>";
                return $alert;
            }
        }
    }
    public function show_customers()
    {
        $query = "SELECT * FROM tbl_customer ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }
    public function show_order($order_code)
    {
        $query = "SELECT * FROM tbl_order WHERE order_code = $order_code";
        $result = $this->db->select($query);
        return $result;
    }
    public function getCustomerById($id)
    {
        $query = "SELECT * FROM tbl_customer WHERE id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }
    public function update_customer($data, $id)
    {
        $name = mysqli_real_escape_string($this->db->link, $data['name']);
        $city = mysqli_real_escape_string($this->db->link, $data['city']);
        $zipcode = mysqli_real_escape_string($this->db->link, $data['zipcode']);
        $email = mysqli_real_escape_string($this->db->link, $data['email']);
        $address = mysqli_real_escape_string($this->db->link, $data['address']);
        $country = mysqli_real_escape_string($this->db->link, $data['country']);
        $phone = mysqli_real_escape_string($this->db->link, $data['phone']);
        if ($name == '' || $city == '' || $zipcode == '' || $email == '' || $address == '' || $country == '' || $phone == '') {
            $alert = "<span class='error'>Fields must be not empty</span>";
            return $alert;
        } else {
            $query = "UPDATE tbl_customer SET name='$name', city='$city', zipcode='$zipcode', email='$email', address='$address', country='$country', phone='$phone' WHERE id='$id'";
            $result = $this->db->update($query);

            if ($result) {
                $alert = "<span class='success'>Customer updated successfully</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Failed to update customer</span>";
                return $alert;
            }
        }
    }
}
?>