<?php
include("../../docs/_includes/connection.php");

$query = "SELECT * FROM batch_sub WHERE 
            batch_id = '" . mysqli_real_escape_string($con, $_POST["batchId"]) . "' AND 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

//echo $query;
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0){
    $query = "DELETE FROM batch_sub WHERE
            batch_id = '" . mysqli_real_escape_string($con, $_POST["batchId"]) . "' AND 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

    echo "Delete Batch";

} else {
    $query = "INSERT INTO batch_sub SET 
            batch_id = '" . mysqli_real_escape_string($con, $_POST["batchId"]) . "', 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

    echo "Add Batch";
}
echo $query;
$result = mysqli_query($con, $query);
?>