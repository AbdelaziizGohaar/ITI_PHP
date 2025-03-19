<?php
require_once "./utils.php";

#generateTitle("--- data received");
// I need here to display received data ---> later save in file , cookies, sessions, db
# you can access form data --->
# either sending get, post request , you can find data
# $_REQUEST
#generateTitle("Access form data", "blue", 1);
///var_dump($_REQUEST); # data of request regradless method --> found in $_REQUEST



#generateTitle("get Post Data", "green", 1);
#var_dump($_POST);
// display content of data
// access array content
$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$address = $_POST["address"];
$country = $_POST["country"];
$gender = $_POST["gender"];
$Skills = $_POST["Skills"];

//var_dump($firstName, $lastName, $address,  $country, $gender, $Skills);

//generateTitle("display GeT Data", "red", 1);
//var_dump($_GET);

 if ($gender == "male")
  {
      $sirName = "Mr";
  }else{
     $sirName = "Mrs";
 }

?>



<!--<h1> Hii --><?php //echo $sirName + $firstName + $lastName ?><!-- </h1>-->
<h1> Hii <?php echo $sirName . " " . $firstName . " " . $lastName; ?> </h1>
<div id="resultCard" class="mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title">Submitted Data</h4>
            <p><strong>First Name:</strong> <span id="displayName">
                    <?php echo $firstName;?>
                </span></p>
            <p><strong>Last Name :</strong> <span id="displayEmail">
                     <?php echo $lastName;?>
                </span></p>
            <p><strong> Adress:</strong> <span id="displaySubject">
                    <?php echo $address;?>
                </span></p>
            <p><strong>Country:</strong> <span id="displaySubject">
                    <?php echo $country;?>
            </span></p>
            <p><strong>Gender:</strong> <span id="displaySubject">
                    <?php echo $gender;?>
            </span></p>

            <p><strong>Skills:</strong> <span id="displaySubject">
                    <?php echo $Skills;?>
            </span></p>

        </div>
    </div>
</div>
