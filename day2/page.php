<?php
require_once "./utils.php";
require_once "./helpers.php";


//================================== validate Post Data ==========================================

$formDataIssues = validatePostData($_POST);
$formErrors = $formDataIssues["errors"];
$oldData = $formDataIssues["valid_data"];

//print_r($formErrors);

if (count($formErrors)) {
    // I need to send errors to the form =====> display them in the form

    $errors = json_encode($formErrors);

    $queryString = "errors={$errors}";
    $old_data = json_encode($oldData);
    if ($old_data) {
        $queryString .= "&old={$old_data}";
    }


    header("location:register.php?{$queryString}");
} else {



    #var_dump($_POST);
    //=================== display content of data==========================================
    // access array content
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $address = $_POST["address"];
    $country = $_POST["country"];
    $gender = $_POST["gender"];
    //$Skills = $_POST["Skills"];
    $Skills = implode(",", $_POST["Skills"]); // Convert array to comma-separated string
    $id = generateID();


    if ($gender == "male") {
        $sirName = "Mr";
    } else {
        $sirName = "Mrs";
    }
    //================== save data to the file ===========================

    $info = "{$id}:{$firstName}:{$lastName}:{$address}:{$country}:{$gender}:{$Skills}\n";

    $saved = appendDataTofile("students.txt", $info);

    if ($saved) {
        echo '<h1 class="mt-5 fw-bold text-primary">🎉 Data Saved! Thanks 🎉</h1>';
        // redirect to the data Table ???
        //header("Location:dataTable.php");

        echo '<a class="btn btn-primary btn-lg shadow-lg rounded-pill px-4 py-2 fw-bold" href="dataTable.php">
                Display All Messages
                </a>';
    } else {
        echo '<h1 class="mt-5 fw-bold text-danger"> 🚨🚨 Error PLease Try Again Later  🚨🚨 </h1>';
    }
}


?>



<!--<h1> Hii --><?php //echo $sirName + $firstName + $lastName 
                ?><!-- </h1>-->
<h1> Hii <?php echo $sirName . " " . $firstName . " " . $lastName; ?> </h1>
<div id="resultCard" class="mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title">Submitted Data</h4>
            <p><strong>First Name:</strong> <span id="displayName">
                    <?php echo $firstName; ?>
                </span></p>
            <p><strong>Last Name :</strong> <span id="displayEmail">
                    <?php echo $lastName; ?>
                </span></p>
            <p><strong> Adress:</strong> <span id="displaySubject">
                    <?php echo $address; ?>
                </span></p>
            <p><strong>Country:</strong> <span id="displaySubject">
                    <?php echo $country; ?>
                </span></p>
            <p><strong>Gender:</strong> <span id="displaySubject">
                    <?php echo $gender; ?>
                </span></p>

            <p><strong>Skills:</strong> <span id="displaySubject">
                    <ul>
                        <?php
                        $skillsArray = explode(",", $Skills); // Convert string back to an array
                        foreach ($skillsArray as $skill) {
                            echo "<li>$skill</li>";
                        }
                        ?>
                    </ul>

                </span></p>

        </div>
    </div>
</div>