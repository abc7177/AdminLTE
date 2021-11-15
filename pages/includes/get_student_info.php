<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $studentId = null;
        var $studentName = null;
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to

    $query = "SELECT account_name FROM account
            WHERE account_id = '".mysqli_real_escape_string($con,$_POST['id'])."'";
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->studentName = $row['account_name'];
        array_push($data_array, $data);
    }
    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>