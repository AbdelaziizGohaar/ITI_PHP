<?php
require_once "../includes/utils.php";
require_once "../validation/validation.php";
require_once "../validation/imageValidation.php";
require_once "../helpers/appendToFile.php";

$errors = [];
$oldData = [];
$allowedExtensions = ["jpg", "jpeg", "png", "gif"];

function handleGetRequest() {
    $errors = isset($_GET["errors"]) ? json_decode($_GET["errors"], true) : [];
    $oldData = isset($_GET["old"]) ? json_decode($_GET["old"], true) : [];
    return ["errors" => $errors, "oldData" => $oldData];
}

function handlePostRequest() {
    global $allowedExtensions;
    $registerData = validateData($_POST);
    $registerErrors = $registerData['errors'];
    $oldValidData = $registerData['valid_data'];

    $fileValidation = validateUploadedFile($_FILES["image"], $allowedExtensions);
    $fileErrors = $fileValidation["errors"];
    $fileValidData = $fileValidation["valid_data"];
    var_dump($_FILES); 

    $errors = array_merge($registerErrors, $fileErrors);

    if (!empty($errors)) {
        $errors = json_encode($errors);
        $oldData = json_encode($oldValidData);
        header("location: ../app/register.php?errors={$errors}&old={$oldData}");
        exit();
    }

    saveUserData($_POST, $fileValidData, $oldValidData);

    header("location: ../app/login.php");
    exit();
}

function saveUserData($data, $fileValidData, $oldValidData) {
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $imageFileName = $fileValidData["tmp_name"] . "." . $fileValidData["extension"];

    $destination = "../upload/" . $imageFileName;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
            echo "File uploaded successfully to: $destination";
    } else {
            echo "File upload failed. Check permissions or file data.";
            var_dump($_FILES["image"]);
    }

    $userData = "{$oldValidData['name']}:{$oldValidData['email']}:{$hashedPassword}:{$oldValidData['roomNo']}:{$oldValidData['ext']}:{$imageFileName}\n";
    appendDataTofile("../data/user.txt", $userData);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    extract(handleGetRequest());
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    handlePostRequest();
}
?>

