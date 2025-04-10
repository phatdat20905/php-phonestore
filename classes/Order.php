<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_order($customer_id, $products)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $order_code = $this->generate_order_code();
        $total = $this->calculate_total($products);

        foreach ($products as $product) {
            $product_id = $this->db->link->real_escape_string($product['product_id']);
            $quantity = $this->db->link->real_escape_string($product['quantity']);
            $stock_query = "SELECT quantity FROM inventory WHERE product_id = '$product_id'";
            $stock_result = $this->db->select($stock_query);
            if (!$stock_result || $stock_result->fetch_assoc()['quantity'] < $quantity) {
                return ['success' => false, 'message' => "Sản phẩm {$product['product_name']} không đủ tồn kho!"];
            }
        }

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

                $note = "Đặt hàng $quantity sản phẩm qua đơn hàng $order_code";
                $this->db->insert("INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                                   VALUES ('$product_id', 0, '$note', NOW())");
            }
            return ['success' => true, 'message' => 'Đặt hàng thành công!', 'order_id' => $order_id];
        }
        return ['success' => false, 'message' => 'Lỗi khi đặt hàng'];
    }

    public function show_orders()
    {
        $query = "SELECT o.*, c.name FROM `order` o JOIN customer c ON o.customer_id = c.customer_id ORDER BY o.created_at DESC";
        return $this->db->select($query);
    }

    public function get_order_details($order_id)
    {
        $order_id = $this->db->link->real_escape_string($order_id);
        $query = "SELECT od.*, p.product_name, p.image 
                  FROM order_details od 
                  JOIN product p ON od.product_id = p.product_id 
                  WHERE od.order_id = '$order_id'";
        return $this->db->select($query);
    }

    public function get_orders_by_customer($customer_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "SELECT o.*, od.product_id, od.quantity, od.price, p.product_name, p.image 
                  FROM `order` o 
                  JOIN order_details od ON o.order_id = od.order_id 
                  JOIN product p ON od.product_id = p.product_id 
                  WHERE o.customer_id = '$customer_id' 
                  ORDER BY o.created_at DESC";
        return $this->db->select($query);
    }

    public function update_status($order_id, $status)
    {
        $order_id = $this->db->link->real_escape_string($order_id);
        $status = $this->db->link->real_escape_string($status);
        $query = "UPDATE `order` SET status = '$status' WHERE order_id = '$order_id'";
        $result = $this->db->update($query);
        if ($result) {
            if ($status == 2 || $status == 3) { // Đã nhận hoặc Thành công
                $order_details = $this->get_order_details($order_id);
                while ($detail = $order_details->fetch_assoc()) {
                    $product_id = $detail['product_id'];
                    $quantity = $detail['quantity'];
                    $note = $status == 2 ? "Đã nhận đơn hàng $order_id: $quantity sản phẩm" : "Hoàn thành đơn hàng $order_id: $quantity sản phẩm";
                    $this->db->insert("INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                                       VALUES ('$product_id', 0, '$note', NOW())");
                }
            }
            return ['success' => true, 'message' => 'Cập nhật trạng thái thành công!'];
        }
        return ['success' => false, 'message' => 'Lỗi khi cập nhật trạng thái'];
    }

    public function delete_order($order_id)
    {
        $order_id = $this->db->link->real_escape_string($order_id);
        $order_details = $this->get_order_details($order_id);
        while ($detail = $order_details->fetch_assoc()) {
            $product_id = $detail['product_id'];
            $quantity = $detail['quantity'];
            // Không hoàn tồn kho, chỉ ghi lịch sử
            $note = "Xóa đơn hàng $order_id (admin): $quantity sản phẩm";
            $this->db->insert("INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                               VALUES ('$product_id', 0, '$note', NOW())");
        }
        $this->db->delete("DELETE FROM order_details WHERE order_id = '$order_id'");
        $query = "DELETE FROM `order` WHERE order_id = '$order_id'";
        $result = $this->db->delete($query);
        return ['success' => $result, 'message' => $result ? 'Xóa thành công!' : 'Lỗi khi xóa'];
    }

    public function cancel_order($order_id)
    {
        $order_id = $this->db->link->real_escape_string($order_id);
        $status_query = "SELECT status FROM `order` WHERE order_id = '$order_id'";
        $status_result = $this->db->select($status_query);
        if ($status_result && $status_result->fetch_assoc()['status'] == 0) {
            $order_details = $this->get_order_details($order_id);
            while ($detail = $order_details->fetch_assoc()) {
                $product_id = $detail['product_id'];
                $quantity = $detail['quantity'];
                // Hoàn lại tồn kho khi khách hủy
                $this->db->update("UPDATE inventory SET quantity = quantity + $quantity WHERE product_id = '$product_id'");
                $note = "Khách hủy đơn hàng $order_id: hoàn $quantity sản phẩm";
                $this->db->insert("INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                                   VALUES ('$product_id', '$quantity', '$note', NOW())");
            }
            $this->db->delete("DELETE FROM order_details WHERE order_id = '$order_id'");
            $query = "DELETE FROM `order` WHERE order_id = '$order_id'";
            $result = $this->db->delete($query);
            return ['success' => $result, 'message' => $result ? 'Hủy đơn hàng thành công!' : 'Lỗi khi hủy đơn hàng'];
        }
        return ['success' => false, 'message' => 'Chỉ có thể hủy đơn hàng khi đang xử lý!'];
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
        return $total + ($total * 0.1); // Cộng VAT 10%
    }

    public function get_revenue_by_month()
    {
        $query = "SELECT DATE_FORMAT(`created_at`, '%Y-%m') AS month, SUM(`total`) AS total 
                  FROM `order` 
                  WHERE `status` = 2 
                  GROUP BY DATE_FORMAT(`created_at`, '%Y-%m') 
                  ORDER BY month DESC";
        return $this->db->select($query);
    }

    public function check_order($customer_id)
    {
        $customer_id = $this->db->link->real_escape_string($customer_id);
        $query = "SELECT * FROM `order` WHERE customer_id = '$customer_id' LIMIT 1";
        $result = $this->db->select($query);
        return $result ? true : false;
    }
}
