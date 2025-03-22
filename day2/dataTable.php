<?php
    require_once "utils.php";
    require_once "helpers.php";

    $lines = file("students.txt");
    $table  =[];
//    var_dump($lines);
    if ($lines) {
        #prepare data
        foreach ($lines as $line) {
            $line = trim($line);  # remove extra spaces and \n
            # split line to fields
            $line = explode(":", $line);  # split line to array 
//            print_r($line);
            $table[] = $line;  # append line in the array 
        }
    }


    echo '<h1 class="text-center mt-5 fw-bold text-primary">🏫 Students DB 🏫</h1>';
    $headers = ["ID", "FirstName", "LastName", "Address", "Country","Gender","Skills"];
    drawTable($headers, $table);
?>




