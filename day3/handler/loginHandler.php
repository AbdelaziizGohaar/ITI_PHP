<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $errors = [];

    $file = "../data/user.txt";

    if (!file_exists($file)) {
        echo "Users file not found.";
        header("Location: ../app/login.php");
        exit();
    }

    $users = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($users as $user) {
        $parts = explode(":", $user);
        if (count($parts) < 5) {
            continue;
        }

        list($name, $storedEmail, $hashedPassword, $roomNo, $ext) = $parts;

        echo "Stored Email: $storedEmail, Input Email: $email<br>";

        if ($email === $storedEmail) {
            if (password_verify($password, $hashedPassword)) {
                $_SESSION["user"] = $name;
                $_SESSION["email"] = $storedEmail;
                unset($_SESSION["errors"]);
                header("Location: ../app/home.php");
                exit();
            } else {
                $errors["password"] = "Incorrect password";
            }
        }
    }

    if (empty($errors)) {
        $errors["email"] = "Email not found";
    }

    $_SESSION["errors"] = $errors;
    header("Location: ../app/login.php?error=Invalid Email or Password");
    exit();
}
?>
