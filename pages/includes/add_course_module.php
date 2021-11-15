<?php
include("../../docs/_includes/connection.php");

$query = "INSERT INTO course_module SET
            course_module_code = '" . mysqli_real_escape_string($con, $_POST["moduleCode"]) . "', 
            course_module_name = '" . mysqli_real_escape_string($con, $_POST["moduleName"]) . "', 
            course_id = '" . mysqli_real_escape_string($con, $_POST["courseId"]) . "'";
$result = mysqli_query($con, $query);

echo mysqli_insert_id($con);
?>