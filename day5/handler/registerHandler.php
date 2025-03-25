<?php
require_once "../includes/utils.php";
require_once "../validation/validation.php";
require_once "../validation/imageValidation.php";
require_once  "../DB/db_operation.php";  // Note: Your file is named db_operation.php (without 's')


$errors = [];
$oldData = [];
$allowedExtensions = ["jpg", "jpeg", "png", "gif"];

function handleGetRequest()
{
    $errors = isset($_GET["errors"]) ? json_decode($_GET["errors"], true) : [];
    $oldData = isset($_GET["old"]) ? json_decode($_GET["old"], true) : [];
    return ["errors" => $errors, "oldData" => $oldData];
}

function saveUserToDatabase($data, $imagePath)
{

    $userData = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        'room_no' => $data['roomNO'],
        'ext' => $data['ext'],
        'image_path' => $imagePath
    ];

    try {
        $userORM = new UserORM();
        $userId = $userORM->insertUser($userData);
        // $userId = insertUser($userData);
        return $userId !== false;
    } catch (Exception $e) {
        if ($e->getMessage() === "Email already exists") {
            $_SESSION["errors"]["email"] = "Email already exists";
        } else {
            $_SESSION["errors"]["database"] = "Registration failed: " . $e->getMessage();
        }
        return false;
    }
}

function handlePostRequest()
{
    global $allowedExtensions;
    $registerData = validateData($_POST);
    $registerErrors = $registerData['errors'];
    $oldValidData = $registerData['valid_data'];

    $fileValidation = validateUploadedFile($_FILES["image"], $allowedExtensions);
    $fileErrors = $fileValidation["errors"];
    $fileValidData = $fileValidation["valid_data"];

    $errors = array_merge($registerErrors, $fileErrors);

    if (!empty($errors)) {
        $errors = json_encode($errors);
        $oldData = json_encode($oldValidData);
        header("location: ../app/register.php?errors={$errors}&old={$oldData}");
        exit();
    }

    // Move uploaded file
    //    $imageFileName = uniqid() . '.' . $fileValidData["extension"];
    $imageFileName = $fileValidData["tmp_name"] . "." . $fileValidData["extension"];
    $destination = "../upload/" . $imageFileName;
    // $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);


    if (move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
        echo "File uploaded successfully to: $destination";
    } else {
        echo "File upload failed. Check permissions or file data.";
        var_dump($_FILES["image"]);
    }

    if (saveUserToDatabase($_POST, $imageFileName)) {
        header("location: ../app/login.php");
        exit();
    } else {
        // Remove the uploaded file if DB save failed
        //   unlink($destination);
        $errors["database"] = "Failed to save user data";
        // If we got here, something went wrong
        $errors = json_encode($errors);
        $oldData = json_encode($oldValidData);
        header("location: ../app/register.php?errors={$errors}&old={$oldData}");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    extract(handleGetRequest());
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    handlePostRequest();
}
?>

