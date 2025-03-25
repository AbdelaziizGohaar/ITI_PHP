<?php
require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/connect_db.php";

class UserORM {
    private $conn;

    public function __construct() {
        $this->conn = $this->connect();
        $this->createTables();
    }

    private function connect() {
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    private function createTables() {
        try {
            $query = "CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(50) NOT NULL,
                `email` VARCHAR(100) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `room_no` VARCHAR(20),
                `ext` VARCHAR(20),
                `image_path` VARCHAR(255),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $this->conn->exec($query);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Table creation failed: " . $e->getMessage());
        }
    }

    public function insertUser($userData) {
        try {
            $query = "INSERT INTO `users` 
                     (name, email, password, room_no, ext, image_path) 
                     VALUES (:name, :email, :password, :room_no, :ext, :image_path)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':name', $userData['name']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':password', $userData['password']);
            $stmt->bindParam(':room_no', $userData['room_no']);
            $stmt->bindParam(':ext', $userData['ext']);
            $stmt->bindParam(':image_path', $userData['image_path']);
            
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Email already exists");
            }
            throw new Exception("Insert failed: " . $e->getMessage());
        }
    }

    public function updateUser($id, $userData) {
        try {
            $query = "UPDATE users SET 
                     name = :name,
                     email = :email,
                     room_no = :room_no,
                     ext = :ext,
                     image_path = :image_path
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $userData['name']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':room_no', $userData['room_no']);
            $stmt->bindParam(':ext', $userData['ext']);
            $stmt->bindParam(':image_path', $userData['image_path']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }

    public function deleteUser($id) {
        try {
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Delete failed: " . $e->getMessage());
        }
    }

    public function getAllUsers() {
        try {
            $query = "SELECT id, name, email, room_no, ext, image_path, created_at 
                     FROM users ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fetch all failed: " . $e->getMessage());
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fetch by ID failed: " . $e->getMessage());
        }
    }

    public function getUserByEmail($email) {
        try {
            $query = "SELECT id, name, email, password, room_no, ext, image_path 
                      FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fetch by email failed: " . $e->getMessage());
        }
    }

    public function __destruct() {
        $this->conn = null;
    }
}