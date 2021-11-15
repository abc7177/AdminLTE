<?php
include("../../docs/_includes/connection.php");

if($_POST["evaType"] == "daily") {
    $query = "INSERT INTO evaluation_sub SET
                eva_rate = '" . mysqli_real_escape_string($con, $_POST["evaRate"]) . "', 
                eva_comment = '" . mysqli_real_escape_string($con, $_POST["evaComment"]) . "', 
                evaluation_id = '" . mysqli_real_escape_string($con, $_POST["id"]) . "', 
                cmc_id = '" . mysqli_real_escape_string($con, $_POST["cmcId"]) . "', 
                account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";
} else {
    $query = "INSERT INTO evaluation_sem_sub SET
                eva_rate = '" . mysqli_real_escape_string($con, $_POST["evaRate"]) . "', 
                eva_comment = '" . mysqli_real_escape_string($con, $_POST["evaComment"]) . "', 
                evaluation_id = '" . mysqli_real_escape_string($con, $_POST["id"]) . "', 
                cmc_id = '" . mysqli_real_escape_string($con, $_POST["cmcId"]) . "', 
                account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";
}
echo $query;
$result = mysqli_query($con, $query);

echo mysqli_insert_id($con);
?>