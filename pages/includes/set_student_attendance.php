<?php
include("../../docs/_includes/connection.php");

$query = "SELECT * FROM attendance_sub WHERE 
            attendance_id = '" . mysqli_real_escape_string($con, $_POST["attendanceId"]) . "' AND 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

//echo $query;
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0){
    $query = "UPDATE attendance_sub SET
            attendance_sub_status = '" . mysqli_real_escape_string($con, $_POST["valueCurrent"]) . "' WHERE
            attendance_id = '" . mysqli_real_escape_string($con, $_POST["attendanceId"]) . "' AND 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

    echo "Update Attendace";

} else {
    $query = "INSERT INTO attendance_sub SET 
            attendance_sub_status = '" . mysqli_real_escape_string($con, $_POST["valueCurrent"]) . "', 
            attendance_id = '" . mysqli_real_escape_string($con, $_POST["attendanceId"]) . "', 
            group_id = '" . mysqli_real_escape_string($con, $_POST["groupId"]) . "', 
            account_id = '" . mysqli_real_escape_string($con, $_POST["studentId"]) . "'";

    echo "Add Attendance";
}
//echo $query;
$result = mysqli_query($con, $query);
?>