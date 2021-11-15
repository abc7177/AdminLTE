<?php
include("../../docs/_includes/connection.php");

$query = "SELECT * FROM account_detail
            WHERE account_id = '" . mysqli_real_escape_string($con, $_POST["adId"]) . "'";

$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0){
    
} else {
    $query = "INSERT INTO account_detail SET 
    account_id = '" . mysqli_real_escape_string($con, $_POST["adId"]) . "'";
    mysqli_query($con, $query);
}

if(isset($_POST["adEducation"])){
    $query = "UPDATE account_detail SET
            ad_education = '" . mysqli_real_escape_string($con, $_POST["adEducation"]) . "'
            WHERE account_id = '" . mysqli_real_escape_string($con, $_POST["adId"]) . "'";
    $result = mysqli_query($con, $query);

}

if(isset($_POST["adLocation"])){
    $query = "UPDATE account_detail SET
            ad_location = '" . mysqli_real_escape_string($con, $_POST["adLocation"]) . "'
            WHERE account_id = '" . mysqli_real_escape_string($con, $_POST["adId"]) . "'";
    $result = mysqli_query($con, $query);

}

if(isset($_POST["adSkills"])){
    $query = "UPDATE account_detail SET
            ad_skills = '" . mysqli_real_escape_string($con, $_POST["adSkills"]) . "'
            WHERE account_id = '" . mysqli_real_escape_string($con, $_POST["adId"]) . "'";
    $result = mysqli_query($con, $query);

}

if(isset($_POST["adNotes"])){
    $query = "UPDATE account_detail SET
            ad_notes = '" . mysqli_real_escape_string($con, $_POST["adNotes"]) . "'
            WHERE account_id = '" . mysqli_real_escape_string($con, $_POST["adId"]) . "'";
    $result = mysqli_query($con, $query);

}

?>