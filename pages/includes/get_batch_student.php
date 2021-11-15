<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $studentId = null;
        var $studentName = null;
        var $studentEnrollDate = null;
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to

    $query = "SELECT account.* FROM account LEFT JOIN batch_sub ON batch_sub.account_id = account.account_id 
            WHERE (batch_sub.batch_id IS NULL) AND account_type = 'student'";
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->studentId = $row['account_id'];
        $data->studentName = $row['account_name'];
        $data->studentEnrollDate = $row['account_enroll_date'];
        array_push($data_array, $data);
    }
    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>