<?php
    include("../../docs/_includes/connection.php");

    class NewsDB {
        var $studentId = null;
        var $studentName = null;
    }

    // get all the info from the db
    $data_array = array(); // will store the array of the results
    $data = null; // temporary var to store info to
    $batchId = "";

    if(isset($_POST['batchId'])){
        $query = "SELECT * FROM student_course 
                LEFT JOIN account ON student_course.account_id = account.account_id 
                LEFT JOIN batch_sub ON batch_sub.account_id = account.account_id 
                WHERE student_course.course_id = '".mysqli_real_escape_string($con,$_POST['courseId'])."' AND student_course.course_group_id = '".mysqli_real_escape_string($con,$_POST['courseGroupId'])."'
                AND batch_id = '".mysqli_real_escape_string($con,$_POST['batchId'])."'";
    } else {
        $query = "SELECT * FROM student_course LEFT JOIN account ON student_course.account_id = account.account_id 
        WHERE student_course.course_id = '".mysqli_real_escape_string($con,$_POST['courseId'])."' AND student_course.course_group_id = '".mysqli_real_escape_string($con,$_POST['courseGroupId'])."'";
    }
    //echo $query;
    $result = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data = new NewsDB();
        $data->studentId = $row['account_id'];
        $data->studentName = $row['account_name'];
        array_push($data_array, $data);
    }
    // got all the data into an array
    // return this to the client (in ajax request)
    header('Content-type: application/json');
    echo json_encode($data_array);
?>