<?php
require_once "helpers.php"; // Include helper functions

if (isset($_GET['id'])) {
    $idToDelete = $_GET['id'];
    $filename = "students.txt";

    // Read all lines and filter out the one to delete
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); 
    $newLines = array_filter($lines, function ($line) use ($idToDelete) {
        $fields = explode(":", $line);
        return $fields[0] != $idToDelete; // Keep lines that don't match the ID
    });

    // Rewrite file without the deleted entry
    file_put_contents($filename, implode("\n", $newLines) . "\n");

    // Redirect back to the table
    header("Location: delete_success.html");
    exit();
} else {
    echo "Invalid Request!";
}
?>
