<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $id = null;
        var $name = null;
        var $code = null;
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to

    $query = "SELECT course_module_id, course_module_code, course_module_name FROM course_module WHERE course_id = '".mysqli_real_escape_string($con,$_POST['id'])."'";
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->id = $row['course_module_id'];
        $data->name = $row['course_module_code'];
        $data->code = $row['course_module_name'];
        //$data->description = $result['description'];
        array_push($data_array, $data);
    }

    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>