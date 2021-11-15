<?php
include("../../docs/_includes/connection.php");

$query = "SELECT * FROM evaluation_sub WHERE 
            evaluation_id = '" . mysqli_real_escape_string($con, $_POST["evaluationId"]) . "' AND 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

//echo $query;
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0){
    $query = "UPDATE evaluation_sub SET
            evaluation_sub_status = '" . mysqli_real_escape_string($con, $_POST["valueCurrent"]) . "' WHERE
            evaluation_id = '" . mysqli_real_escape_string($con, $_POST["evaluationId"]) . "' AND 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

    echo "Update Attendace";

} else {
    $query = "INSERT INTO evaluation_sub SET 
            evaluation_sub_status = '" . mysqli_real_escape_string($con, $_POST["valueCurrent"]) . "', 
            evaluation_id = '" . mysqli_real_escape_string($con, $_POST["evaluationId"]) . "', 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

    echo "Add evaluation";
}
//echo $query;
$result = mysqli_query($con, $query);
?>