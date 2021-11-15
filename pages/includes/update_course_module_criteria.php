<?php
include("../../docs/_includes/connection.php");

$query = "UPDATE course_module_criteria SET
            cmc_name = '" . mysqli_real_escape_string($con, $_POST["moduleCriteriaName"]) . "', 
            WHERE cmc_id = '" . mysqli_real_escape_string($con, $_POST["moduleCriteriaId"]) . "'";
$result = mysqli_query($con, $query);
echo $_POST["moduleCriteriaId"];
?>