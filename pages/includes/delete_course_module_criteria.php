<?php
include("../../docs/_includes/connection.php");

$query = "DELETE FROM course_module_criteria WHERE
            cmc_id = '" . mysqli_real_escape_string($con, $_POST["moduleCriteriaId"]) . "'";
$result = mysqli_query($con, $query);
echo "Deleted";
?>