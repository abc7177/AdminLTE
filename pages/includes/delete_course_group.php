<?php
include("../../docs/_includes/connection.php");

$query = "DELETE FROM course_group WHERE
            group_id = '" . mysqli_real_escape_string($con, $_POST["groupId"]) . "'";
$result = mysqli_query($con, $query);
echo "Deleted";
?>