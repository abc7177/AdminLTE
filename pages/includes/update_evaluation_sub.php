<?php
include("../../docs/_includes/connection.php");

if($_POST["evaType"] == "daily") {
    $query = "UPDATE evaluation_sub SET
                eva_rate = '" . mysqli_real_escape_string($con, $_POST["evaRate"]) . "', 
                eva_comment = '" . mysqli_real_escape_string($con, $_POST["evaComment"]) . "' 
                WHERE eva_sub_id = '" . mysqli_real_escape_string($con, $_POST["id"]) . "'";
} else {
    $query = "UPDATE evaluation_sem_sub SET
            eva_rate = '" . mysqli_real_escape_string($con, $_POST["evaRate"]) . "', 
            eva_comment = '" . mysqli_real_escape_string($con, $_POST["evaComment"]) . "' 
            WHERE eva_sub_id = '" . mysqli_real_escape_string($con, $_POST["id"]) . "'";
}
$result = mysqli_query($con, $query);
echo $query;
?>