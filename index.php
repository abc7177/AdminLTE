<?php
include("docs/_includes/connection.php");
$page = "Dashboard";


if (!(isset($_SESSION["itac_user_id"]))) {
  //echo "<script>window.location='/demo/howard/itac/pages/login.php'</script>";
  //echo "<script>window.location='/pages/login.php'</script>";
  echo "<script>window.location='".$_SESSION['itac_path_configuration']."/itac/pages/login.php'</script>";
  exit;
}

if(isset($_POST["memo_edit_id"])){	// edit memo
  $query = "UPDATE memo SET memo_title = '" . mysqli_real_escape_string($con, $_POST["memo_title"]) . "', 
        memo_content = '" . mysqli_real_escape_string($con, $_POST["memo_content"]) . "', 
				batch_id = '" . mysqli_real_escape_string($con, $_POST["memo_batch"]) . "' 
				WHERE memo_id = '" . mysqli_real_escape_string($con, $_POST["memo_edit_id"]) . "'";
	$result = mysqli_query($con, $query);

	echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful updated flag.
	
  if(!isset($_GET["asTutor"]) ) { 
	  echo "<script>window.location='index.php'</script>";
  } else {
    echo "<script>window.location='index.php?asTutor=".$_GET["asTutor"]."'</script>";
  }
	
	exit;
}

if(isset($_POST["memo_title"])){	// add new memo
  $query = "INSERT INTO memo SET memo_title = '" . mysqli_real_escape_string($con, $_POST["memo_title"]) . "', 
        memo_content = '" . mysqli_real_escape_string($con, $_POST["memo_content"]) . "', 
				batch_id = '" . mysqli_real_escape_string($con, $_POST["memo_batch"]) . "', 
				account_id = '" . $_SESSION["itac_user_id"] . "'";
	$result = mysqli_query($con, $query);

	echo '<script>localStorage.setItem("Added",1)</script>';	// Successful added flag.

  if(!isset($_GET["asTutor"]) ) { 
	  echo "<script>window.location='index.php'</script>";
  } else {
    echo "<script>window.location='index.php?asTutor=".$_GET["asTutor"]."'</script>";
  }
	
	exit;
}

if(isset($_POST["memo_id"])){	// delete memo
  $query = "DELETE FROM memo WHERE memo_id = '" . mysqli_real_escape_string($con, $_POST["memo_id"]) . "'";
	$result = mysqli_query($con, $query);

	echo '<script>localStorage.setItem("Deleted",1)</script>';	// Successful deleted flag.
  
	if(!isset($_GET["asTutor"]) ) { 
	  echo "<script>window.location='index.php'</script>";
  } else {
    echo "<script>window.location='index.php?asTutor=".$_GET["asTutor"]."'</script>";
  }
	
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITAC Beauty | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <link rel="shortcut icon" href="dist/img/logo-200x200.png"/>
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/logo-200x200.png" alt="AdminLTELogo" height="100" width="100">
  </div>

  <!-- Top Navbar Container -->
  <?php include("pages/includes/navbar.php"); ?>

  <!-- Main Sidebar Container -->
  <?php include("pages/includes/sidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php
      $tutor = "";
      $student = "";
      $course = "";
      $evaluation = "";

      $query = "SELECT COUNT(*) AS tutor FROM account WHERE account_type = 'TUTOR'";
      $result = mysqli_query($con, $query);
      if($row = mysqli_fetch_assoc($result)){
        $tutor = $row["tutor"];
      }

      $query = "SELECT COUNT(*) AS student FROM account WHERE account_type = 'STUDENT'";
      $result = mysqli_query($con, $query);
      if($row = mysqli_fetch_assoc($result)){
        $student = $row["student"];
      }

      $query = "SELECT COUNT(*) AS course FROM course";
      $result = mysqli_query($con, $query);
      if($row = mysqli_fetch_assoc($result)){
        $course = $row["course"];
      }

      $query = "SELECT COUNT(*) AS evaluation FROM evaluation";
      $result = mysqli_query($con, $query);
      if($row = mysqli_fetch_assoc($result)){
        $evaluation = $row["evaluation"];
      }

      if(isset($_GET["asStudent"])){
        $accountId = $_GET["asStudent"];
      } else {
        $accountId = $_SESSION["itac_user_id"];
      }


    ?>

    <!-- Main content -->
    
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <?php if($_SESSION["itac_account_level"] <= 1 && !isset($_GET["asStudent"])) { // Tutor and Admin only?> 
        <div class="row">
          <?php if($_SESSION["itac_account_level"] < 1 && !isset($_GET["asTutor"]) ) { ?> 
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?php echo $tutor; ?></h3>

                  <p>Tutors</p>
                </div>
                <div class="icon">
                  <i class="far fas fa-chalkboard-teacher"></i>
                </div>
                <a href="pages/tutor.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $student; ?></h3>

                <p>Students</p>
              </div>
              <div class="icon">
                <i class="far fas fa-user-graduate"></i>
              </div>
              <a href="pages/student.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <?php } ?>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $course; ?></h3>

                <p>Courses</p>
              </div>
              <div class="icon">
                <i class="far fas fa-book-open"></i>
              </div>
              <a href="pages/course.php<?php if(isset($_GET['asTutor'])){ echo '?asTutor='.$_GET['asTutor']; }?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $evaluation; ?></h3>

                <p>Evaluations</p>
              </div>
              <div class="icon">
                <i class="fas fa-percent"></i>
              </div>
              <a href="pages/evaluation.php?eva_type=daily<?php if(isset($_GET['asTutor'])){ echo '&asTutor='.$_GET['asTutor']; }?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <?php } // Admin only?>

        <div class="row">
          <div class="col-lg-6 ">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Latest Memo / Announcement</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>                  
                </div>
              </div>
             
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <?php
                  if($_SESSION["itac_account_level"] < 1 && !isset($_GET["asStudent"])) {  // Tutor and Admin only
                    $query = "SELECT memo.*, account.account_name, batch.batch_code FROM memo 
                          LEFT JOIN account ON account.account_id = memo.account_id 
                          LEFT JOIN batch ON memo.batch_id = batch.batch_id 
                          ORDER BY created DESC";
                  } else {                  
                    $query = "SELECT memo.*, account.account_name, batch.batch_code FROM memo 
                            LEFT JOIN account ON account.account_id = memo.account_id 
                            LEFT JOIN batch ON memo.batch_id = batch.batch_id 
                            LEFT JOIN batch_sub ON memo.batch_id = batch_sub.batch_id
                            WHERE memo.batch_id = '0' OR batch_sub.account_id = '".$accountId."' 
                            ORDER BY created DESC";
                  }
                  $result = mysqli_query($con, $query);
                  if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                      echo '<li class="item">';
                        echo '<div class="product-img">';
                          echo '<img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-10">';
                        echo '</div>';
                        echo '<div class="product-info">';
                          echo '<input type="hidden" name="memo_title'.$row["memo_id"].'" value="'.$row["memo_title"].'">';
                          echo '<input type="hidden" name="memo_content'.$row["memo_id"].'" value="'.$row["memo_content"].'">';
                          echo '<input type="hidden" name="batch_id'.$row["memo_id"].'" value="'.$row["batch_id"].'">';
                          echo '<a href="javascript:void(0)" class="product-title">'.$row["memo_title"].'&nbsp;&nbsp;';                                           
                            echo '<span class="badge badge-info float-right"><i class="far fa-clock"></i>&nbsp;'.date('d/m/Y H:i A', strtotime($row["created"])).' </span></a>';
                          echo '<span class="product-description" style="white-space: pre-wrap;">';
                            echo 'From: '.$row["account_name"].'<br><br>';
                            echo $row["memo_content"];
                            
                            if($row["batch_id"] == '0')
                              echo '<br><br>To: Everyone';
                            else
                              echo '<br><br>To: '.$row["batch_code"];

                               
                          echo '</span>';
                          if($_SESSION["itac_account_level"] < 1 && !isset($_GET["asStudent"])) { // Tutor and Admin only
                            echo '<button class="btn btn-xs float-right editMemo" style="padding:0px 15px; color: #2190ff;" data-memo_id="'.$row["memo_id"].'" ><i class="fa fa-pen-alt"></i></button>';    
                            echo '<button class="btn btn-xs float-right deleteMemo" style="padding:0px 15px; color: red;" data-memo_id="'.$row["memo_id"].'" ><i class="fa fa-trash-alt"></i></button>';
                            
                          } 
                        echo '</div>';
                      echo '</li>';
                    }         
                  } else {
                    echo '<li class="item">';
                        echo '<span class="product-description" style="text-align: center;">';
                          echo 'No Memo / Announcement';
                        echo '</span>';
                      echo '</li>';
                  }     
                  ?>
                </ul>
              </div>
              <?php if($_SESSION["itac_account_level"] < 1 && !isset($_GET["asStudent"])) { // Tutor and Admin only?> 
                <div class="card-footer clearfix">
                  <button id="addNewMemo" class="btn btn-sm btn-info float-right">Add New Memo</button>
                </div>
              <?php
              }
              ?>
            </div>
        </div>
        
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-6 connectedSortable">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Timeline</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>                  
                  </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="timeline">
                      <!-- The timeline -->
                      <div class="timeline timeline-inverse">
                      

                        <?php
                        if(isset($_GET["asStudent"])){ 
                          $query0 = "SELECT account.*, account_detail.ad_image_path, account_detail.ad_education, account_detail.ad_location, account_detail.ad_skills, account_detail.ad_notes, count(sc_id) as sc_id, attendance_rate, eva_rate FROM account
                              LEFT JOIN account_detail ON account.account_id = account_detail.account_id 
                              LEFT JOIN student_course ON account.account_id = student_course.account_id 
                              LEFT JOIN 
                              (SELECT FORMAT((
                              (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_GET["asStudent"]."' AND attendance_sub_status = 'Present') /
                              (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_GET["asStudent"]."') 
                              )* 100, 0) AS attendance_rate) as attendance_sub ON account.account_id = '".$_GET["asStudent"]."' 
                              LEFT JOIN
                              (SELECT FORMAT((
                              (SELECT sum(eva_rate) as as_id from evaluation_sem_sub WHERE account_id ='".$_GET["asStudent"]."' AND eva_rate > '0') /
                              (SELECT count(eva_sub_id) as as_id from evaluation_sem_sub WHERE account_id ='".$_GET["asStudent"]."')
                              ), 2) AS eva_rate) as eva_sub ON account.account_id = '".$_GET["asStudent"]."'
                              WHERE account.account_id ='".$_GET["asStudent"]."'";
                              $result0 = mysqli_query($con,$query0);
                              $row0 = mysqli_fetch_assoc($result0);

                          $query = "SELECT evaluation_id AS '1', account_id AS '2', \"Daily\" as '3', 'EVALUATION' AS '4', created AS '5' FROM evaluation_sub WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT evaluation_id AS '1', account_id AS '2', \"Semester\" as '3', 'EVALUATION' AS '4', created AS '5' FROM evaluation_sem_sub WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT evaluation_id AS '1', account_id AS '2', \"Final\" as '3', 'EVALUATION' AS '4', created AS '5'FROM evaluation_final_sub WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT account.account_name AS '1', course_code AS '2', course_name AS '3', attendance_sub_status AS '4', attendance.created '5' FROM attendance 
                                    LEFT JOIN attendance_sub ON attendance.attendance_id = attendance_sub.attendance_id 
                                    LEFT JOIN course ON attendance.course_id = course.course_id 
                                    LEFT JOIN account ON attendance.account_id = account.account_id WHERE attendance_sub.account_id ='".$accountId."' ORDER BY 5 DESC";
                        } if($_SESSION["itac_account_level"] <= 1 && !isset($_GET["asStudent"])) { // Admin and tutor only.

                          if(isset($_GET["asTutor"])){
                            $accountId = $_GET["asTutor"];
                          }

                          $query0 = "SELECT account.*, account_detail.ad_image_path, account_detail.ad_education, account_detail.ad_location, account_detail.ad_skills, account_detail.ad_notes, count(sc_id) as sc_id, attendance_rate, eva_rate FROM account
                              LEFT JOIN account_detail ON account.account_id = account_detail.account_id 
                              LEFT JOIN student_course ON account.account_id = student_course.account_id 
                              LEFT JOIN 
                              (SELECT FORMAT((
                              (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_SESSION["itac_user_id"]."' AND attendance_sub_status = 'Present') /
                              (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_SESSION["itac_user_id"]."') 
                              )* 100, 0) AS attendance_rate) as attendance_sub ON account.account_id = '".$_SESSION["itac_user_id"]."' 
                              LEFT JOIN
                              (SELECT FORMAT((
                              (SELECT sum(eva_rate) as as_id from evaluation_sem_sub WHERE account_id ='".$_SESSION["itac_user_id"]."' AND eva_rate > '0') /
                              (SELECT count(eva_sub_id) as as_id from evaluation_sem_sub WHERE account_id ='".$_SESSION["itac_user_id"]."')
                              ), 2) AS eva_rate) as eva_sub ON account.account_id = '".$_SESSION["itac_user_id"]."'
                              WHERE account.account_id ='".$_SESSION["itac_user_id"]."'";
                              $result0 = mysqli_query($con,$query0);
                              $row0 = mysqli_fetch_assoc($result0);

                          $query = "SELECT evaluation_id AS '1', account_id AS '2', \"Daily\" as '3', 'EVALUATION' AS '4', created AS '5' FROM evaluation WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT evaluation_id AS '1', account_id AS '2', \"Semester\" as '3', 'EVALUATION' AS '4', created AS '5' FROM evaluation_sem WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT evaluation_id AS '1', account_id AS '2', \"Final\" as '3', 'EVALUATION' AS '4', created AS '5'FROM evaluation_final WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT attendance.attendance_id AS '1', course_code AS '2', course_name AS '3', 'ATTENDANCE' AS '4', attendance.created '5' FROM attendance                                 
                                    LEFT JOIN course ON attendance.course_id = course.course_id 
                                    LEFT JOIN account ON attendance.account_id = account.account_id WHERE attendance.account_id ='".$accountId."' ORDER BY 5 DESC";
                        
                        } else {
                          $query0 = "SELECT account.*, account_detail.ad_image_path, account_detail.ad_education, account_detail.ad_location, account_detail.ad_skills, account_detail.ad_notes, count(sc_id) as sc_id, attendance_rate, eva_rate FROM account
                              LEFT JOIN account_detail ON account.account_id = account_detail.account_id 
                              LEFT JOIN student_course ON account.account_id = student_course.account_id 
                              LEFT JOIN 
                              (SELECT FORMAT((
                              (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_SESSION["itac_user_id"]."' AND attendance_sub_status = 'Present') /
                              (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_SESSION["itac_user_id"]."') 
                              )* 100, 0) AS attendance_rate) as attendance_sub ON account.account_id = '".$_SESSION["itac_user_id"]."' 
                              LEFT JOIN
                              (SELECT FORMAT((
                              (SELECT sum(eva_rate) as as_id from evaluation_sem_sub WHERE account_id ='".$_SESSION["itac_user_id"]."' AND eva_rate > '0') /
                              (SELECT count(eva_sub_id) as as_id from evaluation_sem_sub WHERE account_id ='".$_SESSION["itac_user_id"]."')
                              ), 2) AS eva_rate) as eva_sub ON account.account_id = '".$_SESSION["itac_user_id"]."'
                              WHERE account.account_id ='".$_SESSION["itac_user_id"]."'";
                              $result0 = mysqli_query($con,$query0);
                              $row0 = mysqli_fetch_assoc($result0);

                          $query = "SELECT evaluation_id AS '1', account_id AS '2', \"Daily\" as '3', 'EVALUATION' AS '4', created AS '5' FROM evaluation_sub WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT evaluation_id AS '1', account_id AS '2', \"Semester\" as '3', 'EVALUATION' AS '4', created AS '5' FROM evaluation_sem_sub WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT evaluation_id AS '1', account_id AS '2', \"Final\" as '3', 'EVALUATION' AS '4', created AS '5'FROM evaluation_final_sub WHERE account_id='".$accountId."' group by evaluation_id
                                    union all
                                    SELECT account.account_name AS '1', course_code AS '2', course_name AS '3', attendance_sub_status AS '4', attendance.created '5' FROM attendance 
                                    LEFT JOIN attendance_sub ON attendance.attendance_id = attendance_sub.attendance_id 
                                    LEFT JOIN course ON attendance.course_id = course.course_id 
                                    LEFT JOIN account ON attendance.account_id = account.account_id WHERE attendance_sub.account_id ='".$accountId."' ORDER BY 5 DESC";
                        }

                        $result = mysqli_query($con,$query);
                        $datetime = '1';
                        while($rowAttendance = mysqli_fetch_assoc($result)) {
                          if($datetime != date('d/m/Y', strtotime($rowAttendance["5"]))){
                            echo '<div class="time-label">';
                              echo '<span class="bg-info">';                          
  
                              if($rowAttendance["5"] != "0000-00-00" && $rowAttendance["5"] != ""){
                                $datetime = date('d/m/Y', strtotime($rowAttendance["5"]));
                              }
                              echo date('d M. Y', strtotime($rowAttendance["5"])); 
                              echo '</span>';
                            echo '</div>';
                          }

                          if($_SESSION["itac_account_level"] <= 1 && !isset($_GET["asStudent"])) { // Admin and tutor only.
                            
                            if($rowAttendance["4"] == "EVALUATION"){
    
                              if($rowAttendance["3"] == "Daily"){
                                echo "<div>";
                                  echo '<i class="fas fas fa-percent bg-yellow"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> conducted a '.$rowAttendance["3"].' Evaluation</h3>';
          
                                    echo '<div class="timeline-body">';
                                    if(isset($_GET["asTutor"])){
                                      echo "More info of the result : <a href='pages/evaluation_edit.php?asTutor=".$_GET["asTutor"]."&id=".$rowAttendance["1"]."&eva_type=daily' target='_blank' >Click Here</a>";
                                    } else {
                                      echo "More info of the result : <a href='pages/evaluation_edit.php?id=".$rowAttendance["1"]."&eva_type=daily' target='_blank'>Click Here</a>";
                                    }
                                    echo '</div>';                
                                  echo '</div>';
                                echo '</div>';
                              } else if($rowAttendance["3"] == "Semester"){
                                echo "<div>";
                                  echo '<i class="fas fas fa-percent bg-orange"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> conducted a '.$rowAttendance["3"].' Evaluation</h3>';
          
                                    echo '<div class="timeline-body">';
                                    if(isset($_GET["asTutor"])){
                                      echo "More info of the result : <a href='pages/evaluation_sem_edit.php?asTutor=".$_GET["asTutor"]."&id=".$rowAttendance["1"]."&eva_type=semester' target='_blank'>Click Here</a>";
                                    } else {
                                      echo "More info of the result : <a href='pages/evaluation_sem_edit.php?id=".$rowAttendance["1"]."&eva_type=semester' target='_blank'>Click Here</a>";
                                    }
                                    echo '</div>';                
                                  echo '</div>';
                              echo '</div>';
                              } else if($rowAttendance["3"] == "Final"){
                                echo "<div>";
                                  echo '<i class="fas fas fa-percent bg-red"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> conducted a '.$rowAttendance["3"].' Evaluation</h3>';
          
                                    echo '<div class="timeline-body">';
                                    if(isset($_GET["asTutor"])){
                                      echo "More info of the result : <a href='pages/evaluation_final_edit.php?asTutor=".$_GET["asTutor"]."&id=".$rowAttendance["1"]."&eva_type=final' target='_blank'>Click Here</a>";
                                    } else {
                                      echo "More info of the result : <a href='pages/evaluation_final_edit.php?id=".$rowAttendance["1"]."&eva_type=final' target='_blank'>Click Here</a>";
                                    }
                                    echo '</div>';                
                                  echo '</div>';
                              echo '</div>';
                              }
                            
                            } else {
                             
                              echo "<div>";
                                echo '<i class="fas fa-user-check bg-success"></i>';
                                echo '<div class="timeline-item">';
                                  echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
        
                                  echo '<h3 class="timeline-header"><a href="#">You</a> took attendance of course: '.$rowAttendance["2"].'</h3>';
        
                                  echo '<div class="timeline-body">';
                                  if(isset($_GET["asTutor"])){
                                    echo "More info of the attendance : <a href='pages/attendance_edit.php?asTutor=".$_GET["asTutor"]."&viewType=readonly&id=".$rowAttendance["1"]."' target='_blank'>Click Here</a>";
                                  } else {
                                    echo "More info of the attendance : <a href='pages/attendance_edit.php?id=".$rowAttendance["1"]."' target='_blank'>Click Here</a>";
                                  }
                                  echo '</div>';                
                                echo '</div>';
                              echo '</div>';
                              
                            }

                          } else {
                          
                            if($rowAttendance["4"] == "EVALUATION"){
    
                              if($rowAttendance["3"] == "Daily"){
                                echo "<div>";
                                  echo '<i class="fas fas fa-percent bg-yellow"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> attended a '.$rowAttendance["3"].' Evaluation</h3>';
          
                                    echo '<div class="timeline-body">';
                                    if(isset($_GET["asStudent"])){
                                      echo "More info of the result : <a href='pages/evaluation_edit.php?asStudent=".$_GET["asStudent"]."&id=".$rowAttendance["1"]."&eva_type=daily&viewType=readonly' target='_blank' >Click Here</a>";
                                    } else {
                                      echo "More info of the result : <a href='pages/evaluation_edit.php?id=".$rowAttendance["1"]."&eva_type=daily&viewType=readonly' target='_blank'>Click Here</a>";
                                    }
                                    echo '</div>';                
                                  echo '</div>';
                                echo '</div>';
                              } else if($rowAttendance["3"] == "Semester"){
                                echo "<div>";
                                  echo '<i class="fas fas fa-percent bg-orange"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> attended a '.$rowAttendance["3"].' Evaluation</h3>';
          
                                    echo '<div class="timeline-body">';
                                    if(isset($_GET["asStudent"])){
                                      echo "More info of the result : <a href='pages/evaluation_sem_edit.php?asStudent=".$_GET["asStudent"]."&id=".$rowAttendance["1"]."&eva_type=semester&viewType=readonly' target='_blank'>Click Here</a>";
                                    } else {
                                      echo "More info of the result : <a href='pages/evaluation_sem_edit.php?id=".$rowAttendance["1"]."&eva_type=semester&viewType=readonly' target='_blank'>Click Here</a>";
                                    }
                                    echo '</div>';                
                                  echo '</div>';
                              echo '</div>';
                              } else if($rowAttendance["3"] == "Final"){
                                echo "<div>";
                                  echo '<i class="fas fas fa-percent bg-red"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> attended a '.$rowAttendance["3"].' Evaluation</h3>';
          
                                    echo '<div class="timeline-body">';
                                    if(isset($_GET["asStudent"])){
                                      echo "More info of the result : <a href='pages/evaluation_final_edit.php?asStudent=".$_GET["asStudent"]."&id=".$rowAttendance["1"]."&eva_type=final&viewType=readonly' target='_blank'>Click Here</a>";
                                    } else {
                                      echo "More info of the result : <a href='pages/evaluation_final_edit.php?id=".$rowAttendance["1"]."&eva_type=final&viewType=readonly' target='_blank'>Click Here</a>";
                                    }
                                    echo '</div>';                
                                  echo '</div>';
                              echo '</div>';
                              }
                            } else {
                              
                              if($rowAttendance["4"] == "Present"){
                                echo "<div>";
                                  echo '<i class="fas fa-user-check bg-success"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> were present at course: '.$rowAttendance["2"].'</h3>';
          
                                    echo '<div class="timeline-body">';
                                      echo "Attendance were taken by tutor: ".$rowAttendance["1"]."";
                                    echo '</div>';                
                                  echo '</div>';
                                echo '</div>';
                              } else if($rowAttendance["4"] == "Absent"){
                                echo "<div>";
                                  echo '<i class="fas fa-user-slash bg-danger"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> were absent at course: '.$rowAttendance["2"].'</h3>';
          
                                    echo '<div class="timeline-body">';
                                      echo "Attendance were taken by tutor: ".$rowAttendance["1"]."";
                                    echo '</div>';                
                                  echo '</div>';
                                echo '</div>';
                              } else if($rowAttendance["4"] == "Late"){
                                echo "<div>";
                                  echo '<i class="fas fa-user-clock bg-warning"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> were late at course: '.$rowAttendance["2"].'</h3>';
          
                                    echo '<div class="timeline-body">';
                                      echo "Attendance were taken by tutor: ".$rowAttendance["1"]."";
                                    echo '</div>';                
                                  echo '</div>';
                                echo '</div>';
                              } else if($rowAttendance["4"] == "MC / Leave"){
                                echo "<div>";
                                  echo '<i class="fas fa-hiking bg-info"></i>';
                                  echo '<div class="timeline-item">';
                                    echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
          
                                    echo '<h3 class="timeline-header"><a href="#">You</a> were on MC/leave at course: '.$rowAttendance["2"].'</h3>';
          
                                    echo '<div class="timeline-body">';
                                      echo "Attendance were taken by tutor: ".$rowAttendance["1"]."";
                                    echo '</div>';                
                                  echo '</div>';
                                echo '</div>';
                              }
                            }
                          }
                        }
                        ?>
                        <!-- timeline item -->
                        <!-- timeline time label -->
                        <div class="time-label">
                          <span class="bg-success">
                            <?php 
                            
                            $datetime = '';
                            if($row0["created"] != "0000-00-00" && $row0["created"] != ""){
                              //$datetime = date('d/m/Y H:i A', strtotime($row["created"]));
                            }
                            echo date('d M. Y', strtotime($row0["created"])); 
                            ?>
                          </span>
                        </div>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <div>
                          <i class="fas fa-baby bg-pink"></i>

                          <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i>&nbsp;<?php echo date('H:i A', strtotime($row0["created"])) ?></span>

                            <h3 class="timeline-header border-0"><a href="#">You</a> created an account</h3>


                          </div>
                        </div>
                        <!-- END timeline item -->
                        <div>
                          <i class="far fa-clock bg-gray"></i>
                        </div>
                      </div>
                    </div>
         
                  </div>
                  <!-- /.tab-content -->
                </div><!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </section>
          <!-- right col -->
        </div>
        <!-- TABLE: LATEST ORDERS -->
        
        <!-- /.card -->
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- Footer Container -->
  <?php include('pages/includes/footer.php'); ?>
	<!-- /.Footer Container -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<div class="modal fade" id="memoModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Add New Memo / Annoucement?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="memo_form" method="POST" enctype="multipart/form-data" >
              <div class="modal-body">
                <div class="form-group row">
                    <label for="memo_title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text"  class="form-control" name="memo_title" id="memo_title">     
                    </div>   
                </div>     
                <div class="form-group row">    
                  <label for="memo_content" class="col-sm-2 col-form-label">Content</label>
                  <div class="col-sm-10">              
                    <textarea name="memo_content" id="memo_content"  style="min-width: 100%;" rows="10"></textarea>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="memo_batch" class="col-sm-2 col-form-label">To</label>
                  <div class="col-sm-10">													
                    <select class="form-control" name="memo_batch" id="memo_batch">
                    <?php
                        echo '<option value="0" selected>Everyone</option>';
                        $query = "select * from batch";
                        $result = mysqli_query($con, $query);
                        while($row = mysqli_fetch_assoc($result)){
                            echo '<option value="'.$row["batch_id"].'" ';                                                        
                            echo '>'.$row["batch_code"].'</option>';
                        }
                    ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-center">
                  <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-outline-success" onclick="save_memo()">Save</button>
              </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="memoEditModal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-info">
          <h4 class="modal-title">Edit Memo / Annoucement?</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form name="memo_edit_form" method="POST" enctype="multipart/form-data" >
        <div class="modal-body">
          <div class="form-group row">
              <label for="memo_title" class="col-sm-2 col-form-label">Title</label>
              <div class="col-sm-10">
                <input type="hidden"  class="form-control" name="memo_edit_id" id="memo_edit_id">    
                <input type="text"  class="form-control memo_title" name="memo_title">     
              </div>   
          </div>     
          <div class="form-group row">    
            <label for="memo_content" class="col-sm-2 col-form-label">Content</label>
            <div class="col-sm-10">              
              <textarea class="memo_content" name="memo_content" style="min-width: 100%;" rows="10"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="memo_batch" class="col-sm-2 col-form-label">To</label>
            <div class="col-sm-10">													
              <select class="form-control memo_batch" name="memo_batch">
              <?php
                  echo '<option value="0" selected>Everyone</option>';
                  $query = "select * from batch";
                  $result = mysqli_query($con, $query);
                  while($row = mysqli_fetch_assoc($result)){
                      echo '<option value="'.$row["batch_id"].'" ';                                                        
                      echo '>'.$row["batch_code"].'</option>';
                  }
              ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-outline-success" onclick="edit_memo()">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Delete this memo?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="delete_form" method="POST" enctype="multipart/form-data" >
                <input type="hidden" name="memo_id" id="memo_id">
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-danger" onclick="delete_memo()">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
 <!-- Toastr -->
 <script src="plugins/toastr/toastr.min.js"></script>
<script>
$(function() {

  if(localStorage.getItem("Added")=="1"){
      toastr.success('Memo has been added successfully.');
      localStorage.clear();
  }

  if(localStorage.getItem("Updated")=="1"){
      toastr.success('Memo has been updated successfully.');
      localStorage.clear();
  }

  if(localStorage.getItem("Deleted")=="1"){
      toastr.success('Memo has been deleted successfully.');
      localStorage.clear();
  }

  $("#addEvaluation").click(function(){
			var url = "pages/evaluation_add.php?&eva_type=daily";
			window.location.href = url;
  });

  $(".editMemo").click(function(){
    var memoId = $(this).attr('data-memo_id');
    var memoTitle = $(this).closest(".product-info").find('input[name="memo_title'+memoId+'"]').val();
    var memoContent = $(this).closest(".product-info").find('input[name="memo_content'+memoId+'"]').val();
    var batchId = $(this).closest(".product-info").find('input[name="batch_id'+memoId+'"]').val();

    $("#memoEditModal #memo_edit_id").val(memoId);
    $("#memoEditModal input[name='memo_title']").val(memoTitle);
    $("#memoEditModal textarea[name='memo_content']").val(memoContent);
    $("#memoEditModal select").val(batchId).change(); 
    
    $("#memoEditModal").modal();
  });

  $(".deleteMemo").click(function(){
    var memoId = $(this).attr('data-memo_id');
    $("#memo_id").val(memoId);
    $("#deleteModal").modal();
  });

  $("#addNewMemo").click(function(){
    $("#memoModal").modal();
  });

  $('.editEvaluation').click(function() {
    var evaluation_id = $(this).data('evaluation_id');
    var url = "pages/evaluation_edit.php?id="+evaluation_id+"&eva_type=daily";		
    window.location.href = url;
  });

  $("#addAttendance").click(function(){
			var url = "pages/attendance_add.php";
			window.location.href = url;
  });

  $('.editAttendance').click(function() {
    var attendance_id = $(this).data('attendance_id');
    var url = "pages/attendance_edit.php?id="+attendance_id;		
    window.location.href = url;
  });
});

function save_memo(){
  document.forms["memo_form"].submit();	
}

function edit_memo(){
	document.forms["memo_edit_form"].submit();	
}

function delete_memo(){
	document.forms["delete_form"].submit();	
}
</script>
</body>
</html>
