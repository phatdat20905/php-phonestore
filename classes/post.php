<?php

$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Post
{
    private $db;
    public $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }


    public function insert_categor_post($catName, $description, $catStatus)
    {
        $catName = $this->fm->validation($catName);
        $description = $this->fm->validation($description);
        $catStatus = $this->fm->validation($catStatus);

        $catName = mysqli_real_escape_string($this->db->link, $catName);
        $description = mysqli_real_escape_string($this->db->link, $description);
        $catStatus = mysqli_real_escape_string($this->db->link, $catStatus);

        if (empty($catName) || empty($description) || empty($catStatus)) {
            $alert = "<span class='error'>Category Post must be not empty</span>";
            return $alert;
        } else {
            $query = "INSERT INTO tbl_category_post(title, description, status) VALUES('$catName', '$description', '$catStatus')";
            $result = $this->db->insert($query);

            if ($result) {
                $alert = "<span class='success'>Category Post Inserted Successfully</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Category Post Insert Failed</span>";
                return $alert;
            }
        }
    }

    public function show_category_post()
    {
        $query = "SELECT * FROM tbl_category_post ORDER BY id_cate_post DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_category_post($catName, $description, $catStatus, $id)
    {
        $catName = $this->fm->validation($catName);
        $description = $this->fm->validation($description);
        $catStatus = $this->fm->validation($catStatus);

        $catName = mysqli_real_escape_string($this->db->link, $catName);
        $description = mysqli_real_escape_string($this->db->link, $description);
        $catStatus = mysqli_real_escape_string($this->db->link, $catStatus);

        $id = mysqli_real_escape_string($this->db->link, $id);
        if (empty($catName) || empty($description)) {
            $alert = "<span class='error'>Category Post must be not empty</span>";
            return $alert;
        } else {
            $query = "UPDATE tbl_category_post SET title='$catName', description = '$description', status = '$catStatus' WHERE id_cate_post='$id'";
            $result = $this->db->update($query);

            if ($result) {
                $alert = "<span class='success'>Category Post Updated Successfully</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Category Post Update Failed</span>";
                return $alert;
            }
        }
    }

    public function delete_category_post($id)
    {

        $query = "DELETE FROM tbl_category_post WHERE id_cate_post = '$id'";
        $result = $this->db->delete($query);

        if ($result) {
            $alert = "<span class='success'>Category Post Deleted Successfully</span>";
            return $alert;
        } else {
            $alert = "<span class='error'>Category Post Delete Failed</span>";
            return $alert;
        }
    }

    public function get_cat_by_id($id)
    {
        $query = "SELECT * FROM tbl_category_post WHERE id_cate_post = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_category_frontend()
    {
        $query = "SELECT * FROM tbl_category ORDER BY catId";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_product_by_cat($id)
    {
        $query = "SELECT * FROM tbl_product WHERE catId = '$id' ORDER BY catId DESC LIMIT 8";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_name_by_cat($id)
    {
        $query = "SELECT p.*, c.catName, c.catId FROM tbl_product as p, tbl_category as c WHERE p.catId = c.catId AND c.catId = '$id' LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }
}
?>