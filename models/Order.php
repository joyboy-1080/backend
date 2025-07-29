<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $customer_name;
    public $mobile_no;
    public $customer_address;
    public $product_list;
    public $amount;
    public $status;
    public $date_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all orders
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY date_time DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Order getAll error: " . $e->getMessage());
            return false;
        }
    }

    // Get order by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    // Search orders by mobile number
    public function searchByMobile($mobile) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE mobile_no = ? ORDER BY date_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $mobile);
        $stmt->execute();
        return $stmt;
    }

    // Create order
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET customer_name=:customer_name, mobile_no=:mobile_no, 
                      customer_address=:customer_address, product_list=:product_list, 
                      amount=:amount, status=:status";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->customer_name = htmlspecialchars(strip_tags($this->customer_name));
        $this->mobile_no = htmlspecialchars(strip_tags($this->mobile_no));
        $this->customer_address = htmlspecialchars(strip_tags($this->customer_address));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind values
        $stmt->bindParam(":customer_name", $this->customer_name);
        $stmt->bindParam(":mobile_no", $this->mobile_no);
        $stmt->bindParam(":customer_address", $this->customer_address);
        $stmt->bindParam(":product_list", $this->product_list);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Update order status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status=:status WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
