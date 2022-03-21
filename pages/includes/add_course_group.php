<?php
include("../../docs/_includes/connection.php");

$query = "INSERT INTO course_group SET
            group_name = '" . mysqli_real_escape_string($con, $_POST["groupName"]) . "', 
            group_capacity = '" . mysqli_real_escape_string($con, $_POST["groupCapacity"]) . "', 
            group_start_date = '" . mysqli_real_escape_string($con, $_POST["groupStartDate"]) . "', 
            group_end_date ='" . mysqli_real_escape_string($con, $_POST["groupEndDate"]) . "',
            course_id = '" . mysqli_real_escape_string($con, $_POST["courseId"]) . "'";
//echo $query;
$result = mysqli_query($con, $query);
echo mysqli_insert_id($con);
?>