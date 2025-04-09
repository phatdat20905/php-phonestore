<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_order($data, $products)
    {
        $customer_id = $this->db->link->real_escape_string($data['customer_id']);
        $order_code = $this->generate_order_code();
        $total = $this->calculate_total($products);

        $query = "INSERT INTO `order` (order_code, customer_id, total) VALUES ('$order_code', '$customer_id', '$total')";
        $result = $this->db->insert($query);
        if ($result) {
            $order_id = $this->db->link->insert_id;
            foreach ($products as $product) {
                $product_id = $this->db->link->real_escape_string($product['product_id']);
                $quantity = $this->db->link->real_escape_string($product['quantity']);
                $price = $this->db->link->real_escape_string($product['price']);
                $this->db->insert("INSERT INTO order_details (order_id, product_id, quantity, price) 
                                   VALUES ('$order_id', '$product_id', '$quantity', '$price')");
                $this->db->update("UPDATE inventory SET quantity = quantity - $quantity WHERE product_id = '$product_id'");
            }
        }
        return ['success' => $result, 'message' => $result ? 'Đặt hàng thành công!' : 'Lỗi khi đặt hàng'];
    }

    public function show_orders()
    {
        $query = "SELECT o.*, c.name FROM `order` o JOIN customer c ON o.customer_id = c.customer_id ORDER BY o.created_at DESC";
        return $this->db->select($query);
    }

    public function get_order_details($order_id)
    {
        $query = "SELECT od.*, p.product_name, p.image 
                  FROM order_details od 
                  JOIN product p ON od.product_id = p.product_id 
                  WHERE od.order_id = '$order_id'";
        return $this->db->select($query);
    }

    public function update_status($order_id, $status)
    {
        $query = "UPDATE `order` SET status = '$status' WHERE order_id = '$order_id'";
        $result = $this->db->update($query);
        return ['success' => $result, 'message' => $result ? 'Cập nhật trạng thái thành công!' : 'Lỗi khi cập nhật'];
    }

    public function delete_order($order_id)
    {
        $query = "DELETE FROM `order` WHERE order_id = '$order_id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }

    public function get_pending_orders()
    {
        $query = "SELECT COUNT(*) as total FROM `order` WHERE status = 0";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function get_total_revenue()
    {
        $query = "SELECT SUM(total) as revenue FROM `order` WHERE status = 2";
        $result = $this->db->select($query);
        return $result ? $result->fetch_assoc()['revenue'] : 0;
    }

    private function generate_order_code()
    {
        return 'ORD' . time();
    }

    private function calculate_total($products)
    {
        $total = 0;
        foreach ($products as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }

    // Phương thức lấy doanh thu theo tháng
    public function get_revenue_by_month()
    {
        $query = "SELECT DATE_FORMAT(`created_at`, '%Y-%m') AS month, SUM(`total`) AS total 
              FROM `order` 
              WHERE `status` = 2 
              GROUP BY DATE_FORMAT(`created_at`, '%Y-%m') 
              ORDER BY month DESC";
        return $this->db->select($query);
    }
}
