<?php
include("../../docs/_includes/connection.php");

$query = "UPDATE student_course SET
            course_id = '" . mysqli_real_escape_string($con, $_POST["courseCode"]) . "', 
            course_group_id = '" . mysqli_real_escape_string($con, $_POST["courseGroup"]) . "', 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'
            WHERE sc_id = '" . mysqli_real_escape_string($con, $_POST["scId"]) . "'";
$result = mysqli_query($con, $query);

echo mysqli_insert_id($con);
?>