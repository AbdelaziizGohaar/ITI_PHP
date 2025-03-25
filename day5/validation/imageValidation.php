<?php
function validateUploadedFile($file, $extensions) {
    $errors = [];
    $valid_data = [];

    if (empty($file['tmp_name'])) {
        $errors["image"] = "Image is required";
    } else {
        $valid_data['tmp_name'] = pathinfo($file['tmp_name'], PATHINFO_FILENAME);
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensions)) {
        $errors["image"] = "Invalid file extension. Allowed: " . implode(", ", $extensions);
    } else {
        $valid_data['extension'] = $extension;
    }

    return ["errors" => $errors, "valid_data" => $valid_data];
}
