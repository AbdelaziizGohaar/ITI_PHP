<?php
 require_once  "../utils.php";
 require_once  "connect_db.php";


class UserORM {
    private $conn;

    public function __construct() {
        // $this->conn = $this->connect();
        // $this->createTables();
    }


    public function insertUser($userData) {
        try {
            $conn = connect_to_db_pdo();
            if($conn) {
                $inst_query = "INSERT INTO `users` 
                              (name, email, password, room_no, ext, image_path) 
                              VALUES (:name, :email, :password, :room_no, :ext, :image_path)";
                
                $stmt = $conn->prepare($inst_query);
                
                $stmt->bindParam(':name', $userData['name']);
                $stmt->bindParam(':email', $userData['email']);
                $stmt->bindParam(':password', $userData['password']);
                $stmt->bindParam(':room_no', $userData['room_no']);
                $stmt->bindParam(':ext', $userData['ext']);
                $stmt->bindParam(':image_path', $userData['image_path']);
                
                $res = $stmt->execute();
                
                if($res) {
                    $inserted_id = $conn->lastInsertId();
                    return $inserted_id;
                }
                
               // $conn = null;
                return false;
            }
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }

    //==================== =================================
    public function updateUser($id, $userData) {
        try {
            $conn = connect_to_db_pdo();
            $query = "UPDATE users SET 
                     name = :name,
                     email = :email,
                     room_no = :room_no,
                     ext = :ext,
                     image_path = :image_path
                     WHERE id = :id";
            
            $stmt = $conn->prepare($query);
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

        //==================================================================================================
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

        //==================================================================================================
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

            //==================================================================================================
    public function getUserById($id) {
        try {
            $conn = connect_to_db_pdo();
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch user: " . $e->getMessage());
        }
    }

            //==================================================================================================
    public function getUserByEmail($email) {
        try {
            $conn = connect_to_db_pdo();
            if ($conn) {
                $query = "SELECT id, name, email, password, room_no, ext, image_path 
                          FROM users WHERE email = :email";
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $conn = null;
                
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            displayError($e->getMessage());
            return false;
        }
    }
 
    public function __destruct() {
        // $this->conn = null;
    }
}

// Usage example:
// $userORM = new UserORM();
// $users = $userORM->getAllUsers();
?>