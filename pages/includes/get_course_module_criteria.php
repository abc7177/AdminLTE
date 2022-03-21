<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $id = null;
        var $name = null;
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to

    if(mysqli_real_escape_string($con,$_POST['sessionType']) == "Theory"){  // Theory only got one mark, no modules needed.
        $query = "SELECT '-1' as cms_id, 'Theory' as cms_name";
    } else {
        $query = "SELECT cms_id, cms_name FROM course_module_sub_criteria 
        LEFT JOIN course_module_criteria ON course_module_sub_criteria.cmc_id = course_module_criteria.cmc_id
        WHERE course_module_id = '".mysqli_real_escape_string($con,$_POST['id'])."'";
    }
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->id = $row['cms_id'];
        $data->name = $row['cms_name'];
        array_push($data_array, $data);
    }

    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>