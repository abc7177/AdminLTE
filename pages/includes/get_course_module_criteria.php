<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $id = null;
        var $name = null;
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to

    $query = "SELECT cmc_id, cmc_name FROM course_module_criteria WHERE course_module_id = '".mysqli_real_escape_string($con,$_POST['id'])."'";
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->id = $row['cmc_id'];
        $data->name = $row['cmc_name'];
        array_push($data_array, $data);
    }

    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>