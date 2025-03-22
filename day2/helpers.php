<?php

// function drawTable($header, $tableData) {

//     echo '<div class="table-responsive">
//         <table class="table table-hover table-bordered align-middle">
//             <thead class="table-dark">
//             <tr>';
//     foreach ($header as $value) {
//         echo "<th>$value</th>";
//     }
//     echo "</tr></thead><tbody>";

//     foreach ($tableData as $row) {
//         echo "<tr>";
//         foreach ($row as  $field) {
//             echo "<td>{$field}</td>";
//         }
//         echo "</tr>";
//     }

//     echo "</tbody></table></div> </div>";

// }

function drawTable($header, $tableData) {
    echo '<div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
            <tr>';
    
    foreach ($header as $value) {
        echo "<th>$value</th>";
    }
    echo "<th>Actions</th>"; 
    echo "</tr></thead><tbody>";

    foreach ($tableData as $row) {
        echo "<tr>";
        foreach ($row as  $field) {
            echo "<td>{$field}</td>";
        }
        $id = $row[0];
        echo "<td>
            <a href='delete.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
        </td>";
        echo "</tr>";
    }
    echo "</tbody></table></div>";
}




function generateID(){
    if(file_exists("ids.txt")){
        // read id in ids.txt
        $id=  file_get_contents("ids.txt");
        $id = (int)$id + 1;
        // increment +1
    }else{
        $id  =1 ;
    }
    // save incremented in the ids.txt
    file_put_contents("ids.txt", $id);
    // return with id
    return $id;
}


function appendDataTofile($filename, $data){
    $fileobject= fopen($filename, "a");
    if ($fileobject) {
        fwrite($fileobject, $data);
        fclose($fileobject);
        return true;
    }

    return false;

}


// function validatePostData($postData){
//     $errors = [];
//     $valid_data = [];
//     foreach ($postData as $key => $value) {
//         if(! isset($value) or empty($value)){
//             $errors[$key] = ucfirst("{$key} is required");
//         }else{
//             $valid_data[$key] = trim($value);
//         }
//     }
//     return ["errors" => $errors, "valid_data" => $valid_data];
// }


function validatePostData($postData) {
    $errors = [];
    $valid_data = [];
    
    foreach ($postData as $key => $value) {
        if (!isset($value) || empty($value)) {
            $errors[$key] = ucfirst("{$key} is required");
        } else {
            // Handle array fields separately
            if (is_array($value)) {
                $valid_data[$key] = array_map('trim', $value); // Trim each array element
            } else {
                $valid_data[$key] = trim($value);
            }
        }
    }
    
    return ["errors" => $errors, "valid_data" => $valid_data];
}
