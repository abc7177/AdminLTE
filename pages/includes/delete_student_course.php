<?php
include("../../docs/_includes/connection.php");

$query = "DELETE FROM student_course WHERE
            sc_id = '" . mysqli_real_escape_string($con, $_POST["scId"]) . "'";
$result = mysqli_query($con, $query);
echo "Deleted";
?>