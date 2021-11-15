<?php
include("../../docs/_includes/connection.php");

$query = "INSERT INTO course_group SET
            group_name = '" . mysqli_real_escape_string($con, $_POST["groupName"]) . "', 
            group_capacity = '" . mysqli_real_escape_string($con, $_POST["groupCapacity"]) . "', 
            course_id = '" . mysqli_real_escape_string($con, $_POST["courseId"]) . "'";
$result = mysqli_query($con, $query);
echo mysqli_insert_id($con);
?>