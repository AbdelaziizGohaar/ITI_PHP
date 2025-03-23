<?php
    require_once"../handler/loginHandler.php";
    session_start();
    $errors = isset($_SESSION["errors"]) ? $_SESSION["errors"] : [];
    unset($_SESSION["errors"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/login.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-container">
            <form class="login-form p-4" action="../handler/loginHandler.php" method="POST">
                <h2 class="text-center">Login</h2>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email">
                    <div class="text-danger font-weight-bold">
                        <?php echo isset($errors["email"]) ? $errors["email"] : ""; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                    <div class="text-danger font-weight-bold">
                        <?php echo isset($errors["password"]) ? $errors["password"] : ""; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom w-100">Login</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
