<?php

$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/database.php');
include_once($filepath . '/../helpers/format.php');
?>
<?php
class Category
{
    private $db;
    public $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }


    public function insert_category($catName)
    {
        $catName = $this->fm->validation($catName);

        $catName = mysqli_real_escape_string($this->db->link, $catName);

        if (empty($catName)) {
            $alert = "<span class='error'>Category must be not empty</span>";
            return $alert;
        } else {
            $query = "INSERT INTO tbl_category(catName) VALUES('$catName')";
            $result = $this->db->insert($query);

            if ($result) {
                $alert = "<span class='success'>Category Inserted Successfully</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Category Insert Failed</span>";
                return $alert;
            }
        }
    }

    public function show_category()
    {
        $query = "SELECT * FROM tbl_category ORDER BY catId";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_category($catName, $catId)
    {
        $catName = $this->fm->validation($catName);
        $catName = mysqli_real_escape_string($this->db->link, $catName);

        $catId = mysqli_real_escape_string($this->db->link, $catId);
        if (empty($catName)) {
            $alert = "<span class='error'>Category must be not empty</span>";
            return $alert;
        } else {
            $query = "UPDATE tbl_category SET catName='$catName' WHERE catId='$catId'";
            $result = $this->db->update($query);

            if ($result) {
                $alert = "<span class='success'>Category Updated Successfully</span>";
                return $alert;
            } else {
                $alert = "<span class='error'>Category Update Failed</span>";
                return $alert;
            }
        }
    }

    public function delete_category($catId)
    {

        $query = "DELETE FROM tbl_category WHERE catId = '$catId'";
        $result = $this->db->delete($query);

        if ($result) {
            $alert = "<span class='success'>Category Deleted Successfully</span>";
            return $alert;
        } else {
            $alert = "<span class='error'>Category Delete Failed</span>";
            return $alert;
        }
    }

    public function get_cat_by_id($catId)
    {
        $query = "SELECT * FROM tbl_category WHERE catId = '$catId'";
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