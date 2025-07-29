<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $image;
    public $original_price;
    public $current_price;
    public $category;
    public $subcategory;
    public $is_sold_out;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all products
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY subcategory, name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get product by ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    // Create product
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, image=:image, original_price=:original_price, 
                      current_price=:current_price, category=:category, 
                      subcategory=:subcategory, is_sold_out=:is_sold_out";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->original_price = htmlspecialchars(strip_tags($this->original_price));
        $this->current_price = htmlspecialchars(strip_tags($this->current_price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->subcategory = htmlspecialchars(strip_tags($this->subcategory));

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":original_price", $this->original_price);
        $stmt->bindParam(":current_price", $this->current_price);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":subcategory", $this->subcategory);
        $stmt->bindParam(":is_sold_out", $this->is_sold_out, PDO::PARAM_BOOL);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update product
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, image=:image, original_price=:original_price, 
                      current_price=:current_price, category=:category, 
                      subcategory=:subcategory, is_sold_out=:is_sold_out
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->original_price = htmlspecialchars(strip_tags($this->original_price));
        $this->current_price = htmlspecialchars(strip_tags($this->current_price));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->subcategory = htmlspecialchars(strip_tags($this->subcategory));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":original_price", $this->original_price);
        $stmt->bindParam(":current_price", $this->current_price);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":subcategory", $this->subcategory);
        $stmt->bindParam(":is_sold_out", $this->is_sold_out, PDO::PARAM_BOOL);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete product
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
