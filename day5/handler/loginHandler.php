<?php
 if (session_status() === PHP_SESSION_NONE) {
    session_start();
 }
require_once "../DB/db_operation.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $errors = [];

    // Input validation
    if (empty($email)) {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    if (empty($password)) {
        $errors["password"] = "Password is required";
    }

    // If validation errors exist
    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        header("Location: ../app/login.php");
        exit();
    }

     $userORM = new UserORM();
     $user = $userORM->getUserByEmail($email);
      // $user = getUserByEmail($email);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Login successful - set session variables
            $_SESSION["user"] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'room_no' => $user['room_no'],
                'ext' => $user['ext'],
                'image_path' => $user['image_path']
            ];
            
            unset($_SESSION["errors"]);
            header("Location: ../app/User_Managment.php");
            exit();
        } else {
            $errors["password"] = "Incorrect password";
        }
    } else {
        $errors["email"] = "Email not found";
    }

    // If we get here, login failed
    $_SESSION["errors"] = $errors;
    header("Location: ../app/login.php");
    exit();
}
?>