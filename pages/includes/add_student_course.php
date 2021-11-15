<?php
include("../../docs/_includes/connection.php");

$query = "INSERT INTO student_course SET
            course_id = '" . mysqli_real_escape_string($con, $_POST["courseCode"]) . "', 
            course_group_id = '" . mysqli_real_escape_string($con, $_POST["courseGroup"]) . "', 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";
$result = mysqli_query($con, $query);

$_SESSION['courseCode'] = $_POST["courseCode"];
$_SESSION['groupCode'] = $_POST["courseGroup"];
echo mysqli_insert_id($con);
?>