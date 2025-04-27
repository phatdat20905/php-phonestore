<?php
$filepath = realpath(dirname(__FILE__));
include_once($filepath . '/../lib/Database.php');
include_once($filepath . '/../lib/Session.php');
include_once($filepath . '/../helpers/format.php');

class Cart
{
    private $db;
    private $fm;

    public function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function check_cart()
    {
        $sId = session_id();
        $query = "SELECT * FROM cart WHERE sId = '$sId'";
        $result = $this->db->select($query);
        return $result && $result->num_rows > 0;
    }

    public function add_to_cart($quantity, $product_id)
    {
        $quantity = $this->fm->validation($quantity);
        $product_id = $this->db->link->real_escape_string($product_id);
        $sId = session_id();

        $query = "SELECT p.*, i.quantity as stock, i.inventory_id 
                  FROM product p 
                  LEFT JOIN inventory i ON p.product_id = i.product_id 
                  WHERE p.product_id = '$product_id'";
        $result = $this->db->select($query);
        if (!$result) {
            return "Sản phẩm không tồn tại!";
        }
        $product = $result->fetch_assoc();

        if ($quantity > $product['stock']) {
            return "Số lượng vượt quá tồn kho!";
        }

        $check_cart = $this->db->select("SELECT * FROM cart WHERE product_id = '$product_id' AND sId = '$sId'");
        if ($check_cart) {
            $current = $check_cart->fetch_assoc();
            $new_quantity = $current['quantity'] + $quantity;
            if ($new_quantity > $product['stock']) {
                return "Số lượng vượt quá tồn kho!";
            }
            $query_update = "UPDATE cart SET quantity = '$new_quantity' WHERE product_id = '$product_id' AND sId = '$sId'";
            $this->db->update($query_update);
        } else {
            $query_insert = "INSERT INTO cart (sId, product_id, product_name, price, quantity, image) 
                             VALUES ('$sId', '$product_id', '{$product['product_name']}', '{$product['price']}', '$quantity', '{$product['image']}')";
            $this->db->insert($query_insert);
        }

        $new_stock = $product['stock'] - $quantity;
        $update_inventory = "UPDATE inventory 
                             SET quantity = '$new_stock', 
                                 last_updated = NOW() 
                             WHERE inventory_id = '{$product['inventory_id']}'";
        $this->db->update($update_inventory);

        $quantity_change = -$quantity;
        $note = "Đã thêm $quantity sản phẩm vào giỏ hàng qua session ID: $sId";
        $history_query = "INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                          VALUES ('$product_id', '$quantity_change', '$note', NOW())";
        $this->db->insert($history_query);

        $this->update_session();
        return true;
    }

    public function get_all_cart()
    {
        $query = "SELECT c.*, p.product_name 
                  FROM cart c 
                  JOIN product p ON c.product_id = p.product_id";
        return $this->db->select($query);
    }

    public function get_cart_items()
    {
        $sId = session_id();
        $query = "SELECT c.*, i.quantity as stock 
                  FROM cart c 
                  LEFT JOIN inventory i ON c.product_id = i.product_id 
                  WHERE c.sId = '$sId'";
        return $this->db->select($query);
    }

    public function del_cart($cart_id)
    {
        $cart_id = $this->db->link->real_escape_string($cart_id);
        $query = "DELETE FROM cart WHERE cart_id = '$cart_id'";
        return $this->db->delete($query);
    }

    public function clear_cart()
    {
        $sId = session_id();
        $cart_items = $this->get_cart_items();
        if ($cart_items) {
            while ($item = $cart_items->fetch_assoc()) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                // Không hoàn tồn kho, chỉ ghi lịch sử
                $note = "Xóa giỏ hàng sau khi đặt hàng thành công: $quantity sản phẩm qua session ID: $sId";
                $this->db->insert("INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                                   VALUES ('$product_id', 0, '$note', NOW())");
            }
        }
        $query = "DELETE FROM cart WHERE sId = '$sId'";
        $this->db->delete($query);
        Session::set('sum', 0);
        Session::set('qty', 0);
    }

    public function delete_cart_item($product_id)
    {
        $sId = session_id();
        $product_id = $this->db->link->real_escape_string($product_id);
        $query = "DELETE FROM cart WHERE sId = '$sId' AND product_id = '$product_id'";
        $this->db->delete($query);
        $this->update_session();
    }

    public function del_all_data_cart()
    {
        $sId = session_id();
        $cart_items = $this->get_cart_items();
        if ($cart_items) {
            while ($item = $cart_items->fetch_assoc()) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];
                $stock_query = "SELECT quantity FROM inventory WHERE product_id = '$product_id'";
                $stock = $this->db->select($stock_query)->fetch_assoc()['quantity'];
                $new_stock = $stock + $quantity;
                $update_inventory = "UPDATE inventory 
                                     SET quantity = '$new_stock', 
                                         last_updated = NOW() 
                                     WHERE product_id = '$product_id'";
                $this->db->update($update_inventory);

                $note = "Xóa toàn bộ giỏ hàng: $quantity sản phẩm qua session ID: $sId";
                $history_query = "INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                                  VALUES ('$product_id', '$quantity', '$note', NOW())";
                $this->db->insert($history_query);
            }
        }
        $query = "DELETE FROM cart WHERE sId = '$sId'";
        $this->db->delete($query);
        Session::set('sum', 0);
        Session::set('qty', 0);
    }

    public function update_quantity_cart($quantity, $cart_id, $stock)
    {
        $quantity = $this->fm->validation($quantity);
        $cart_id = $this->db->link->real_escape_string($cart_id);
        $stock = $this->db->link->real_escape_string($stock);

        $cart_query = "SELECT * FROM cart WHERE cart_id = '$cart_id'";
        $cart_result = $this->db->select($cart_query)->fetch_assoc();
        $product_id = $cart_result['product_id'];
        $old_quantity = $cart_result['quantity'];

        if ($quantity > $stock) {
            return "Số lượng vượt quá tồn kho!";
        }

        $query = "UPDATE cart SET quantity = '$quantity' WHERE cart_id = '$cart_id'";
        $result = $this->db->update($query);

        if ($result) {
            $quantity_change = $old_quantity - $quantity; // Tăng thì âm, giảm thì dương
            $new_stock = $stock + $quantity_change;
            $update_inventory = "UPDATE inventory 
                                 SET quantity = '$new_stock', 
                                     last_updated = NOW() 
                                 WHERE product_id = '$product_id'";
            $this->db->update($update_inventory);

            $note = "Cập nhật số lượng giỏ hàng từ $old_quantity thành $quantity qua session ID: " . session_id();
            $history_query = "INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                              VALUES ('$product_id', '$quantity_change', '$note', NOW())";
            $this->db->insert($history_query);

            $this->update_session();
            return "Cập nhật số lượng thành công!";
        }
        return "Cập nhật số lượng thất bại!";
    }

    public function delete_product_cart($cart_id)
    {
        $cart_id = $this->db->link->real_escape_string($cart_id);
        $cart_query = "SELECT * FROM cart WHERE cart_id = '$cart_id'";
        $cart_result = $this->db->select($cart_query)->fetch_assoc();
        $product_id = $cart_result['product_id'];
        $quantity = $cart_result['quantity'];

        $query = "DELETE FROM cart WHERE cart_id = '$cart_id'";
        $result = $this->db->delete($query);

        if ($result) {
            $stock_query = "SELECT quantity FROM inventory WHERE product_id = '$product_id'";
            $stock = $this->db->select($stock_query)->fetch_assoc()['quantity'];
            $new_stock = $stock + $quantity;
            $update_inventory = "UPDATE inventory 
                                 SET quantity = '$new_stock', 
                                     last_updated = NOW() 
                                 WHERE product_id = '$product_id'";
            $this->db->update($update_inventory);

            $note = "Xóa $quantity sản phẩm khỏi giỏ hàng qua session ID: " . session_id();
            $history_query = "INSERT INTO inventory_history (product_id, quantity_change, note, created_at) 
                              VALUES ('$product_id', '$quantity', '$note', NOW())";
            $this->db->insert($history_query);

            $this->update_session();
            return "Xóa sản phẩm thành công!";
        }
        return "Xóa sản phẩm thất bại!";
    }

    private function update_cart_total()
    {
        $sId = session_id();
        $query = "SELECT SUM(price * quantity) as sum, SUM(quantity) as qty FROM cart WHERE sId = '$sId'";
        $result = $this->db->select($query)->fetch_assoc();
        Session::set('sum', $result['sum'] ?? 0);
        Session::set('qty', $result['qty'] ?? 0);
    }

    private function update_session()
    {
        $sId = session_id();
        $query = "SELECT SUM(price * quantity) as sum, SUM(quantity) as qty FROM cart WHERE sId = '$sId'";
        $result = $this->db->select($query)->fetch_assoc();
        Session::set("sum", $result['sum'] ?? 0);
        Session::set("qty", $result['qty'] ?? 0);
    }
}
?>