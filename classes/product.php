<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../helpers/format.php');

class Product
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function insert_product($data, $files)
    {
        $product_name = $this->db->link->real_escape_string($data['product_name']);
        $cat_id = $this->db->link->real_escape_string($data['cat_id']);
        $brand_id = $this->db->link->real_escape_string($data['brand_id']);
        $product_desc = $this->db->link->real_escape_string($data['product_desc']);
        $price = $this->db->link->real_escape_string($data['price']);
        $type = $this->db->link->real_escape_string($data['type']);
        $image = $this->upload_image($files['image']);

        $query = "INSERT INTO product (product_name, cat_id, brand_id, product_desc, price, image, type) 
                  VALUES ('$product_name', '$cat_id', '$brand_id', '$product_desc', '$price', '$image', '$type')";
        $result = $this->db->insert($query);
        if ($result) {
            $product_id = $this->db->link->insert_id;
            $this->db->insert("INSERT INTO inventory (product_id, quantity) VALUES ('$product_id', 0)");
        }
        return ['success' => $result, 'message' => $result ? 'Thêm thành công!' : 'Lỗi khi thêm sản phẩm'];
    }

    public function show_product()
    {
        $query = "SELECT p.*, c.cat_name, b.brand_name 
                  FROM product p 
                  JOIN category c ON p.cat_id = c.cat_id 
                  JOIN brand b ON p.brand_id = b.brand_id 
                  ORDER BY p.product_id DESC";
        return $this->db->select($query);
    }

    public function filter_products($cat_id, $brand_id, $keyword)
    {
        $cat_id = $this->db->link->real_escape_string($cat_id);
        $brand_id = $this->db->link->real_escape_string($brand_id);
        $keyword = $this->db->link->real_escape_string($keyword);

        $query = "SELECT p.*, c.cat_name, b.brand_name 
                  FROM product p 
                  JOIN category c ON p.cat_id = c.cat_id 
                  JOIN brand b ON p.brand_id = b.brand_id 
                  WHERE 1=1";
        if ($cat_id) {
            $query .= " AND p.cat_id = '$cat_id'";
        }
        if ($brand_id) {
            $query .= " AND p.brand_id = '$brand_id'";
        }
        if ($keyword) {
            $query .= " AND (p.product_name LIKE '%$keyword%' OR p.product_desc LIKE '%$keyword%')";
        }
        $query .= " ORDER BY p.product_id DESC";
        return $this->db->select($query);
    }

    public function get_product_by_id($id)
    {
        $query = "SELECT * FROM product WHERE product_id = '$id'";
        return $this->db->select($query);
    }

    public function update_product($data, $files, $id)
    {
        $product_name = $this->db->link->real_escape_string($data['product_name']);
        $cat_id = $this->db->link->real_escape_string($data['cat_id']);
        $brand_id = $this->db->link->real_escape_string($data['brand_id']);
        $product_desc = $this->db->link->real_escape_string($data['product_desc']);
        $price = $this->db->link->real_escape_string($data['price']);
        $type = $this->db->link->real_escape_string($data['type']);
        $image = !empty($files['image']['name']) ? $this->upload_image($files['image']) : $data['current_image'];

        $query = "UPDATE product SET product_name = '$product_name', cat_id = '$cat_id', brand_id = '$brand_id', 
                  product_desc = '$product_desc', price = '$price', image = '$image', type = '$type' 
                  WHERE product_id = '$id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_product($id)
    {
        $query = "DELETE FROM product WHERE product_id = '$id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }

    private function upload_image($file)
    {
        $target_dir = "Uploads/";
        $file_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $file_name;
        move_uploaded_file($file["tmp_name"], $target_file);
        return $file_name;
    }

    public function get_total_products()
    {
        $query = "SELECT COUNT(*) as total FROM product";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_top_selling_products()
    {
        $query = "SELECT p.product_name, SUM(od.quantity) AS total_quantity, SUM(od.price * od.quantity) AS total_revenue 
                  FROM order_details od 
                  JOIN product p ON od.product_id = p.product_id 
                  JOIN `order` o ON od.order_id = o.order_id 
                  WHERE o.status = 3
                  GROUP BY p.product_id, p.product_name 
                  ORDER BY total_quantity DESC 
                  LIMIT 10";
        return $this->db->select($query);
    }

    public function get_featured_products()
    {
        $query = "SELECT p.*, i.quantity FROM product p 
                  LEFT JOIN inventory i ON p.product_id = i.product_id 
                  WHERE p.type = 1 LIMIT 4";
        return $this->db->select($query);
    }

    public function get_new_products()
    {
        $query = "SELECT p.*, i.quantity FROM product p 
                  LEFT JOIN inventory i ON p.product_id = i.product_id 
                  ORDER BY p.product_id DESC LIMIT 4";
        return $this->db->select($query);
    }

    public function get_all_product()
    {
        $query = "SELECT * FROM product";
        return $this->db->select($query);
    }

    public function getLastestByBrand($brand_id)
    {
        $brand_id = $this->db->link->real_escape_string($brand_id);
        $query = "SELECT p.*, i.quantity FROM product p 
                  LEFT JOIN inventory i ON p.product_id = i.product_id 
                  WHERE p.brand_id = '$brand_id' 
                  ORDER BY p.product_id DESC LIMIT 1";
        return $this->db->select($query);
    }

    public function get_product_details($id)
    {
        $id = $this->db->link->real_escape_string($id);
        $query = "SELECT p.*, c.cat_name, b.brand_name, i.quantity 
                  FROM product p 
                  LEFT JOIN category c ON p.cat_id = c.cat_id 
                  LEFT JOIN brand b ON p.brand_id = b.brand_id 
                  LEFT JOIN inventory i ON p.product_id = i.product_id 
                  WHERE p.product_id = '$id'";
        return $this->db->select($query);
    }

    public function get_product_by_cat($cat_id, $limit = null, $offset = null)
    {
        $cat_id = $this->db->link->real_escape_string($this->fm->validation($cat_id));
        $query = "SELECT * FROM product WHERE cat_id = '$cat_id'";
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT $offset, $limit";
        }
        return $this->db->select($query);
    }

    public function count_products_by_cat($cat_id)
    {
        $cat_id = $this->db->link->real_escape_string($this->fm->validation($cat_id));
        $query = "SELECT COUNT(*) as total FROM product WHERE cat_id = '$cat_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_product_by_brand($brand_id, $limit = null, $offset = null)
    {
        $brand_id = $this->db->link->real_escape_string($this->fm->validation($brand_id));
        $query = "SELECT * FROM product WHERE brand_id = '$brand_id'";
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT $offset, $limit";
        }
        return $this->db->select($query);
    }

    public function count_products_by_brand($brand_id)
    {
        $brand_id = $this->db->link->real_escape_string($this->fm->validation($brand_id));
        $query = "SELECT COUNT(*) as total FROM product WHERE brand_id = '$brand_id'";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function search_product($keyword, $limit, $offset)
    {
        $keyword = $this->db->link->real_escape_string($keyword);
        $query = "SELECT * FROM product 
                  WHERE product_name LIKE '%$keyword%' OR product_desc LIKE '%$keyword%' 
                  AND type = 1 
                  LIMIT $offset, $limit";
        return $this->db->select($query);
    }

    public function count_search_products($keyword)
    {
        $keyword = $this->db->link->real_escape_string($keyword);
        $query = "SELECT COUNT(*) as total FROM product 
                  WHERE product_name LIKE '%$keyword%' OR product_desc LIKE '%$keyword%' 
                  AND type = 1";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        }
        return 0;
    }
}
?>