<?php
include("../../docs/_includes/connection.php");

$query = "INSERT INTO course_module_criteria SET
            cmc_name = '" . mysqli_real_escape_string($con, $_POST["moduleCriteriaName"]) . "', 
            course_module_id = '" . mysqli_real_escape_string($con, $_POST["moduleId"]) . "'";
$result = mysqli_query($con, $query);
// echo $query;
echo mysqli_insert_id($con);
?>