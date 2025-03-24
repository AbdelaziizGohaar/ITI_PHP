<?php
function appendDataTofile($filename, $data){
    if (!file_exists($filename)) {
        echo "<p>File does not exist.. Creating an empty file</p>";
        file_put_contents($filename, "");
        $data = "";
    } 
    $fileobject= fopen($filename, "a");
    if ($fileobject) {
        fwrite($fileobject, $data);
        fclose($fileobject);
        return true;
    }

    return false;

}