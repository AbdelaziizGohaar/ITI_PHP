<?php

$errors=[];
$old_data=[];

if(isset($_GET["errors"])){
    $errors = $_GET["errors"];
    echo "<br>";

    $errors = json_decode($errors, true);
}

if(isset($_GET["old"])){
    $old_data=$_GET["old"];
    $old_data = json_decode($old_data, true);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Registration</h2>
    <form action="page.php" method="post" class="mt-4">
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" 
            value='<?php echo $old_data["firstName"] ? $old_data["firstName"]: ""  ?>'>
            <div class="text-danger  font-weight-bold">
                <?php  echo $errors["firstName"] ? "{$errors['firstName']}" : ""; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" 
            value='<?php echo $old_data["lastName"] ? $old_data["lastName"]: ""  ?>'>
            <div class="text-danger  font-weight-bold">
                <?php  echo $errors["lastName"] ? "{$errors['lastName']}" : ""; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" id="address" name="address" 
            value='<?php echo $old_data["address"] ? $old_data["address"]: ""  ?>'>
            <div class="text-danger  font-weight-bold">
                <?php  echo $errors["address"] ? "{$errors['address']}" : ""; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Country:</label>
            <input type="text" class="form-control" id="country" name="country">
            <div class="text-danger  font-weight-bold">
                <?php  echo $errors["country"] ? "{$errors['country']}" : ""; ?>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Gender:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="male" name="gender" value="male">
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="female" name="gender" value="female">
                <label class="form-check-label" for="female">Female</label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Skills:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="php" name="Skills[]" value="php">
                <label class="form-check-label" for="php">PHP</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="mysql" name="Skills[]" value="mysql">
                <label class="form-check-label" for="mysql">JS</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="java" name="Skills[]" value="java">
                <label class="form-check-label" for="java">Bash</label>
            </div>

        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Department:</label>
            <input type="text" class="form-control" id="department" name="department">
        </div>

        <div class="mb-3">
            <label for="verfCode" class="form-label">Please Enter This Code:</label>
            <label for="verfCode" class="form-label">123</label>
            <input type="text" class="form-control" id="verfCode" name="verfCode" required>
        </div>

        <button type="submit" class="btn btn-primary" disabled >Submit</button>
    </form>
</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript to Check Verification Code -->
<script>
    // Get references to the input field and the submit button
    const verfCodeInput = document.getElementById("verfCode");
    const submitButton = document.querySelector("button[type='submit']");

    // Disable the submit button by default
    submitButton.disabled = true;

    // Add an event listener to the input field
    verfCodeInput.addEventListener("input", function () {
        // Check if the entered code matches "123"
        if (verfCodeInput.value === "123") {
            // Enable the submit button
            submitButton.disabled = false;
        } else {
            // Disable the submit button
            submitButton.disabled = true;
        }
    });
</script>
</body>
</html>