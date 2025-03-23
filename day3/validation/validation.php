<?php
function validateData($data){
    $errors = [];
    $validData = [];

    foreach ($data as $key => $value) {
        $value = trim($value);
        if (!isset($value) || empty($value)) {
            $errors[$key] = ucfirst("{$key} is required.");
            continue;
        }

        switch ($key) {
            case "email":
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$key] = "Invalid email format.";
                } elseif(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $value)) {
                    $errors[$key] = "Invalid email format.";
                }else {
                    $validData[$key] = $value;
                }
                break;
            case "password":
                if (strlen($value) !== 8) {
                    $errors[$key] = "Password must be exactly 8 characters.";
                } elseif (!preg_match("/^[a-z0-9_]+$/", $value)) {
                    $errors[$key] = "Password can only contain lowercase letters, numbers, and underscores.";
                } elseif (preg_match("/[A-Z]/", $value)) {
                    $errors[$key] = "Password cannot contain uppercase letters.";
                } else {
                    $validData[$key] = $value;
                }
                break;

            case "confirmPassword":
                if ($value !== $data["password"]) {
                    $errors[$key] = "Passwords do not match.";
                }
                break;

            case "ext":
                if (!is_numeric($value)) {
                    $errors[$key] = "Extension must be a number.";
                } else {
                    $validData[$key] = $value;
                }
                break;

            default:
                $validData[$key] = $value;
                break;
        }
    }

    return ["errors" => $errors, "valid_data" => $validData];
}
