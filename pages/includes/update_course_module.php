<?php
include("../../docs/_includes/connection.php");

$query = "UPDATE course_module SET
            course_module_code = '" . mysqli_real_escape_string($con, $_POST["moduleCode"]) . "', 
            course_module_name = '" . mysqli_real_escape_string($con, $_POST["moduleName"]) . "'
            WHERE course_module_id = '" . mysqli_real_escape_string($con, $_POST["moduleId"]) . "'";
$result = mysqli_query($con, $query);

echo $_POST["moduleId"];
?>