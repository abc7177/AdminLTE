<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $id = null;
        var $name = null;
    
        /* still unable to call the class's functions after initialization, do research in future.
        function setID($id) {
            $this->id = $id;
        }
        function setCode($code) {
            $this->code = $code;
        }
        function setDescription($desc) {
            $this->description = $desc;
        }
        */
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to

    $query = "SELECT group_id, group_name FROM course_group WHERE course_id = '".mysqli_real_escape_string($con,$_POST['id'])."'";
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->id = $row['group_id'];
        $data->name = $row['group_name'];
        //$data->description = $result['description'];
        array_push($data_array, $data);
    }

    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>