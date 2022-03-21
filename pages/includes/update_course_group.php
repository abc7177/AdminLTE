<?php
include("../../docs/_includes/connection.php");

$query = "UPDATE course_group SET
            group_name = '" . mysqli_real_escape_string($con, $_POST["groupName"]) . "', 
            group_start_date = '" . mysqli_real_escape_string($con, $_POST["groupStartDate"]) . "', 
            group_end_date = '" . mysqli_real_escape_string($con, $_POST["groupEndDate"]) . "',
            group_capacity = '" . mysqli_real_escape_string($con, $_POST["groupCapacity"]) . "'
            WHERE group_id = '" . mysqli_real_escape_string($con, $_POST["groupId"]) . "'";
$result = mysqli_query($con, $query);
echo $_POST["groupId"];
?>