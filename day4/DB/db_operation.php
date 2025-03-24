<?php

require_once  "../utils.php";
require_once  "connect_db.php";

function createUsersTable() {
    try {
        $conn = connect_to_db_pdo();
        
        $create_query = "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL,
            `email` VARCHAR(100) UNIQUE NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `room_no` VARCHAR(20),
            `ext` VARCHAR(20),
            `image_path` VARCHAR(255),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $stmt = $conn->prepare($create_query);
        $res = $stmt->execute();
        
        $conn = null;
        return $res;
    } catch (Exception $e) {
        displayError($e->getMessage());
        return false;
    }
}

// Call this once to create the table
createUsersTable();

//================ insert user  ===================================
function insertUser($userData) {
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
        // Handle duplicate email error specifically
        if ($e->getCode() == 23000) {
            throw new Exception("Email already exists");
        }
        displayError($e->getMessage());
        return false;
    }
}


// =================================== Update user
function updateUser($id, $userData) {
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



//==================== Delete user =================================
function deleteUser($id) {
    try {
        $conn = connect_to_db_pdo();
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Delete failed: " . $e->getMessage());
    }
}

//========================Get all users=======================================
function getAllUsers() {
    try {
        $conn = connect_to_db_pdo();
        $query = "SELECT id, name, email, room_no, ext, image_path, created_at 
                 FROM users ORDER BY created_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception("Failed to fetch users: " . $e->getMessage());
    }
}

//================ Get single user by ID ===================================
function getUserById($id) {
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


//================ Get single user by mail ===================================
function getUserByEmail($email) {
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


