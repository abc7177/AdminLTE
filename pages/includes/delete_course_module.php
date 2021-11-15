<?php
include("../../docs/_includes/connection.php");

$query = "DELETE FROM course_module WHERE
            course_module_id = '" . mysqli_real_escape_string($con, $_POST["moduleId"]) . "'";
$result = mysqli_query($con, $query);
echo "Deleted";
?>