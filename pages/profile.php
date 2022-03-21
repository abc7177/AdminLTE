<?php
    include("../docs/_includes/connection.php");
    $page = "Profile";

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    if(isset($_FILES['account_photo'])){
			if($_FILES['account_photo']['name'] != ""){
				$filename = $_FILES['account_photo']['name'];
				$valid_ext = array('png','jpeg','jpg');
				

        if(is_uploaded_file($_FILES['account_photo']['tmp_name'])){
          $ext = pathinfo($_FILES['account_photo']['name'] , PATHINFO_EXTENSION);
          $filename = pathinfo($_FILES['account_photo']['name'] , PATHINFO_FILENAME);
          $filepath = time()."_".$_FILES['account_photo']['name'];
          $ext = strtolower($ext);
          if($ext == "jpg" || $ext == "jpeg" || $ext == "png"){	
            $location = "/dist/img/".$filepath;

        
            move_uploaded_file($_FILES['account_photo']['tmp_name'], "../dist/img/".$filepath);

            // compressImage($_FILES['account_photo']['tmp_name'], $location, 60);
            $q = "UPDATE account_detail SET ad_image_path = '".$location."', ad_image_name = '".$filename."' WHERE account_id = '".$_GET["id"]."'";
            $r = mysqli_query($con,$q);
          }
        }

				// // file extension
				// $file_extension = pathinfo($location, PATHINFO_EXTENSION);
				// $file_extension = strtolower($file_extension);

				// // Check extension
				// if(in_array($file_extension,$valid_ext)){
				// 	// Compress Image
				// 	compressImage($_FILES['account_photo']['tmp_name'], $location, 60);
				// 	$q = "UPDATE account_detail SET ad_image_path = '".$location."', ad_image_name = '".$filename."' WHERE account_id = '".$_GET["id"]."'";
				// 	$r = mysqli_query($con,$q);
        //   //echo $q;
				// }else{
				// 	echo "Invalid file type.";
				// }
			}

      if(isset($_GET["asStudent"])){
        $_SESSION["itac_ad_image_path2"] = $location;
        echo "<script>window.location='profile.php?asStudent=".$_GET["asStudent"]."&id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
      } else if(isset($_GET["asTutor"])){
        $_SESSION["itac_ad_image_path3"] = $location;
        echo "<script>window.location='profile.php?asTutor=".$_GET["asTutor"]."&id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
      } else {
        $_SESSION["itac_ad_image_path"] = $location;
        echo "<script>window.location='profile.php?id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
      }

      echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful updated flag.
      
      exit;
		}

    if(isset($_POST["submitPassword"])){ 
      if($_POST["account_password"] != ""){
        $hashedPassword = password_hash(mysqli_real_escape_string($con,$_POST["account_password"]), PASSWORD_DEFAULT);
        $passwordQuery = "account_password = '" . $hashedPassword . "'";
        $query = "UPDATE account SET 
                  $passwordQuery
                  WHERE account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
      } else {
          $hashedPassword = "";
          $passwordQuery = "";
          $query = "";
      }

      $result = mysqli_query($con, $query);
       //echo $query;
       echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful updated flag.

       if(isset($_GET["asStudent"])){
        echo "<script>window.location='profile.php?asStudent=".$_GET["asStudent"]."&id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
       } else {
        echo "<script>window.location='profile.php?id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
       }
       
       exit;
    }

    if (isset($_POST["studentName"])) {

      $query = "UPDATE account SET 
                  account_name = '" . mysqli_real_escape_string($con, $_POST["studentName"]) . "', 
                  account_ic = '" . mysqli_real_escape_string($con, $_POST["studentICNumber"]) . "', 
                  account_dob = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["studentDOB"]) . "', '%d/%m/%Y'), 
                  account_email = '" . mysqli_real_escape_string($con, $_POST["studentEmail"]) . "', 
                  account_phone_no = '" . mysqli_real_escape_string($con, $_POST["studentPhoneNumber"]) . "', 
                  account_e_name = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyName"]) . "', 
                  account_e_email = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyEmail"]) . "', 
                  account_e_phone_no = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyPhone"]) . "', 
                  account_e_name_2 = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyName2"]) . "', 
                  account_e_email_2 = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyEmail2"]) . "', 
                  account_e_phone_no_2 = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyPhone2"]) . "', 
                  account_address_line1 = '" . mysqli_real_escape_string($con, $_POST["studentAddressLine1"]) . "', 
                  account_address_line2 = '" . mysqli_real_escape_string($con, $_POST["studentAddressLine2"]) . "', 
                  account_postcode = '" . mysqli_real_escape_string($con, $_POST["studentPostcode"]) . "', 
                  account_state = '" . mysqli_real_escape_string($con, $_POST["studentState"]) . "', 
                  account_country = '" . mysqli_real_escape_string($con, $_POST["studentCountry"]) . "', 
                  account_enroll_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["studentEnrollDate"]) . "', '%d/%m/%Y') 
                  WHERE account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
      $result = mysqli_query($con, $query);
      
      //echo $query;
      echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful updated flag.

      if(isset($_GET["asStudent"])){
        echo "<script>window.location='profile.php?asStudent=".$_GET["asStudent"]."&id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
      } else {
        echo "<script>window.location='profile.php?id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
      }
      exit;
    }

    // Compress image
    function compressImage($source, $destination, $quality) {

      $info = getimagesize($source);
    
      if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);
      elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);
      elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);
    
      imagejpeg($image, $destination, $quality);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITAC | User Profile</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
  <!-- Favicon -->
	<link rel="shortcut icon" href="../dist/img/logo-200x200.png" />
  <style>
    textarea
    {
      width:100%;
    }

    img {
      image-orientation: from-image;
    }

    .textAreaDiv
    {
      margin:5px 0;
      padding:3px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Top Navbar Container -->
  <?php include("includes/navbar.php"); ?>

  <!-- Main Sidebar Container -->
  <?php include('includes/sidebar.php'); ?>
  <!-- /.Main Sidebar Container -->

  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="../dist/img/logo-200x200.png" alt="AdminLTELogo" height="100" width="100">
  </div> -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <?php
    $query = "SELECT * FROM account_detail
    WHERE account_id = '" . $_GET["id"] . "'";

    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0){

    } else {
      $query = "INSERT INTO account_detail SET 
      account_id = '" . $_GET["id"] . "'";
      mysqli_query($con, $query);
    }

    $query = "SELECT account.*, account_detail.ad_image_path, account_detail.ad_education, account_detail.ad_location, account_detail.ad_skills, account_detail.ad_notes, count(sc_id) as sc_id, attendance_rate, eva_rate FROM account
    LEFT JOIN account_detail ON account.account_id = account_detail.account_id 
    LEFT JOIN student_course ON account.account_id = student_course.account_id 
    LEFT JOIN 
    (SELECT FORMAT((
    (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_GET["id"]."' AND attendance_sub_status = 'Present') /
    (SELECT count(attendance_sub_id) as as_id from attendance_sub WHERE account_id ='".$_GET["id"]."') 
    )* 100, 0) AS attendance_rate) as attendance_sub ON account.account_id = '".$_GET["id"]."' 
    LEFT JOIN
    (SELECT FORMAT((
    (SELECT sum(eva_rate) as as_id from evaluation_sem_sub WHERE account_id ='".$_GET["id"]."' AND eva_rate > '0') /
    (SELECT count(eva_sub_id) as as_id from evaluation_sem_sub WHERE account_id ='".$_GET["id"]."')
    ), 2) AS eva_rate) as eva_sub ON account.account_id = '".$_GET["id"]."'
    WHERE account.account_id ='".$_GET["id"]."'";
    $result = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($result)

    ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <?php
                  if(isset($_GET["asStudent"])){
                    if($row["ad_image_path"] != ""){
                      echo '<img class="profile-user-img img-fluid img-circle" src="'.$_SESSION["itac_path_configuration2"].$row["ad_image_path"].'" alt="User profile picture" style="width:150px; height: 150px;">';
                    } else {
                      echo '<img class="profile-user-img img-fluid img-circle" src="../dist/img/avatar2.png" alt="User profile picture">';
                    }
                  } else if(isset($_GET["asTutor"])){
                    if($row["ad_image_path"] != ""){
                      echo '<img class="profile-user-img img-fluid img-circle" src="'.$_SESSION["itac_path_configuration3"].$row["ad_image_path"].'" alt="User profile picture" style="width:150px; height: 150px;">';
                    } else {
                      echo '<img class="profile-user-img img-fluid img-circle" src="../dist/img/avatar2.png" alt="User profile picture">';
                    }
                  } else {
                    if($row["ad_image_path"] != ""){
                      echo '<img class="profile-user-img img-fluid img-circle" src="'.$_SESSION["itac_path_configuration"].$row["ad_image_path"].'" alt="User profile picture" style="width:150px; height: 150px;">';
                    } else {
                      echo '<img class="profile-user-img img-fluid img-circle" src="../dist/img/avatar2.png" alt="User profile picture">';
                    }
                  }
                  ?>
                 
                  <br/>
                  <button class='btn btn-sm '>
                    <label for="account_photo">
                      <i class='fa fa-pen-alt'></i>
                    </label>
                  </button>
                  <form class="form-horizontal" name="update_photo" method="POST" enctype="multipart/form-data">
                    <input name="account_photo" id="account_photo" type="file" style="display: none;" />
                  </form>
                </div>

                <h3 class="profile-username text-center"><?php echo $row["account_name"]; ?></h3>

                <p class="text-muted text-center"><?php echo $row["account_type"]; ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Courses</b> <a class="float-right" href="course.php"><?php echo $row["sc_id"]==0?"No Course":$row["sc_id"];?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Attendance Rate</b> <a class="float-right" href="attendance.php"><?php echo $row["attendance_rate"]==''?"No Rate":$row["attendance_rate"]."%";?></a>
                  </li>
                  <!-- <li class="list-group-item">
                    <b>Average Score</b> <a class="float-right" href="evaluation.php?eva_type=semester"><?php //echo $row["eva_rate"]==''?"No Score":$row["eva_rate"];?></a>
                  </li> -->
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Education</strong>
                <button class='btn btn-sm' id='editEducation'>
                  <i class='fa fa-pen-alt'></i>
                </button>

                <button class='btn btn-sm' id='saveEducation' style="display:none;" data-account_id="<?php echo $row["account_id"]?>">
                  <i class='fa fa-check'></i>
                </button>

                <p class="text-muted" id="adEducationTxt" style='white-space:pre-wrap;'><?php echo $row["ad_education"]; ?></p>
                
                <div class="textAreaDiv" id="adEducationTxtBox" style="display:none;">
                  <textarea width="100%" id="adEducation"><?php echo $row["ad_education"]; ?></textarea>
                </div>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                <button class='btn btn-sm' id='editLocation'>
                  <i class='fa fa-pen-alt'></i>
                </button>

                <button class='btn btn-sm' id='saveLocation' style="display:none;" data-account_id="<?php echo $row["account_id"]?>">
                  <i class='fa fa-check'></i>
                </button>

                <p class="text-muted" id="adLocationTxt" style='white-space:pre-wrap;'><?php echo $row["ad_location"]; ?></p>

                <div class="textAreaDiv" id="adLocationTxtBox" style="display:none;">
                  <textarea width="100%" id="adLocation"><?php echo $row["ad_location"]; ?></textarea>
                </div>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>
                <button class='btn btn-sm' id='editSkills'>
                  <i class='fa fa-pen-alt'></i>
                </button>

                <button class='btn btn-sm' id='saveSkills' style="display:none;" data-account_id="<?php echo $row["account_id"]?>">
                  <i class='fa fa-check'></i>
                </button>

                <p class="text-muted" id="adSkillsTxt" style='white-space:pre-wrap;'><?php echo $row["ad_skills"]; ?></p>

                <div class="textAreaDiv" id="adSkillsTxtBox" style="display:none;">
                  <textarea width="100%" id="adSkills"><?php echo $row["ad_skills"]; ?></textarea>
                </div>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>
                <button class='btn btn-sm' id='editNotes'>
                  <i class='fa fa-pen-alt'></i>
                </button>

                <button class='btn btn-sm' id='saveNotes' style="display:none;" data-account_id="<?php echo $row["account_id"]?>">
                  <i class='fa fa-check'></i>
                </button>

                <p class="text-muted" id="adNotesTxt" style='white-space:pre-wrap;'><?php echo $row["ad_notes"]; ?></p>

                <div class="textAreaDiv" id="adNotesTxtBox" style="display:none;">
                  <textarea width="100%" id="adNotes"><?php echo $row["ad_notes"]; ?></textarea>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#personal" data-toggle="tab">Personal</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                     

                      <?php

                      if(isset($_GET["asStudent"])){
                        $accountId = $_GET["asStudent"];
                      } else {
                        $accountId = $_SESSION["itac_user_id"];
                      }

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

                      $result = mysqli_query($con,$query);
                      //echo $query;
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
                        
                        if($rowAttendance["4"] == "EVALUATION"){

                          if($rowAttendance["3"] == "Daily"){
                            echo "<div>";
                              echo '<i class="fas fas fa-percent bg-yellow"></i>';
                              echo '<div class="timeline-item">';
                                echo '<span class="time"><i class="far fa-clock"></i>&nbsp;'.date('H:i A', strtotime($rowAttendance["5"])).' </span>';
      
                                echo '<h3 class="timeline-header"><a href="#">You</a> attended a '.$rowAttendance["3"].' Evaluation</h3>';
      
                                echo '<div class="timeline-body">';
                                if(isset($_GET["asStudent"])){
                                  echo "More info of the result : <a href='evaluation_edit.php?asStudent=".$_GET["asStudent"]."&id=".$rowAttendance["1"]."&eva_type=daily&viewType=readonly'>Click Here</a>";
                                } else {
                                  echo "More info of the result : <a href='evaluation_edit.php?id=".$rowAttendance["1"]."&eva_type=daily&viewType=readonly'>Click Here</a>";
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
                                  echo "More info of the result : <a href='evaluation_sem_edit.php?asStudent=".$_GET["asStudent"]."&id=".$rowAttendance["1"]."&eva_type=semester&viewType=readonly'>Click Here</a>";
                                } else {
                                  echo "More info of the result : <a href='evaluation_sem_edit.php?id=".$rowAttendance["1"]."&eva_type=semester&viewType=readonly'>Click Here</a>";
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
                                  echo "More info of the result : <a href='evaluation_final_edit.php?asStudent=".$_GET["asStudent"]."&id=".$rowAttendance["1"]."&eva_type=final&viewType=readonly'>Click Here</a>";
                                } else {
                                  echo "More info of the result : <a href='evaluation_final_edit.php?id=".$rowAttendance["1"]."&eva_type=final&viewType=readonly'>Click Here</a>";
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
                      ?>
                      <!-- timeline item -->
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-success">
                          <?php 
                          
                          $datetime = '';
                          if($row["created"] != "0000-00-00" && $row["created"] != ""){
                            //$datetime = date('d/m/Y H:i A', strtotime($row["5"]));
                          }
                          echo date('d M. Y', strtotime($row["created"])); 
                          ?>
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-baby bg-pink"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i>&nbsp;<?php echo date('H:i A', strtotime($row["created"])) ?></span>

                          <h3 class="timeline-header border-0"><a href="#">You</a> created an account</h3>


                        </div>
                      </div>
                      <!-- END timeline item -->
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="personal">
                    <form class="form-horizontal" name="update_detail" id="update_detail" method="POST">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="hidden" name="studentId" id="studentId" class="form-control" value="<?php echo htmlspecialchars($row["account_id"]) ?>">
                          <input type="text" name="studentName" id="studentName" class="form-control"  value="<?php echo htmlspecialchars($row["account_name"]) ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">I/C Number</label>
                        <div class="col-sm-10">
                          <input type="text" name="studentICNumber" id="studentICNumber" class="form-control" value="<?php echo htmlspecialchars($row["account_ic"]) ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Date Of Birth</label>
                        <div class="col-sm-5">
                          <div class="input-group">
                          <?php
                          $datetime = '';
                          if($row["account_dob"] != "0000-00-00" && $row["account_dob"] != ""){
                            $datetime = date('d/m/Y', strtotime($row["account_dob"]));
                          }
                          ?>
                            <input type="text" name="studentDOB" id="studentDOB" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask value="<?php echo $datetime; ?>">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="text" name="studentEmail" id="studentEmail" class="form-control" value="<?php echo htmlspecialchars($row["account_email"]) ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Phone Number</label>
                        <div class="col-sm-10">
                          <input type="text" name="studentPhoneNumber" id="studentPhoneNumber" class="form-control" value="<?php echo htmlspecialchars($row["account_phone_no"]) ?>">
                        </div>
                      </div>
                      <div class="form-group row" style="margin-bottom:0px;">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Emergency Contact</label>
                        <div class="col-sm-10">
                          <div class="row">
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentEmergencyName" id="studentEmergencyName" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($row["account_e_name"]) ?>">
                              </div>
                            </div>
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentEmergencyEmail" id="studentEmergencyEmail" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($row["account_e_email"]) ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row" style="margin-bottom:0px;">
                        <label for="inputEmail" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                          <div class="row">
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentEmergencyPhone" id="studentEmergencyPhone" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($row["account_e_phone_no"]) ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row" style="margin-bottom:0px;">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Emergency Contact 2</label>
                        <div class="col-sm-10">
                          <div class="row">
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentEmergencyName2" id="studentEmergencyName2" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($row["account_e_name_2"]) ?>">
                              </div>
                            </div>
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentEmergencyEmail2" id="studentEmergencyEmail2" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($row["account_e_email_2"]) ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row" style="margin-bottom:0px;">
                        <label for="inputEmail" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                          <div class="row">
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentEmergencyPhone2" id="studentEmergencyPhone2" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($row["account_e_phone_no_2"]) ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                          <input type="text" name="studentAddressLine1" id="studentAddressLine1" class="form-control" placeholder="Line 1" value="<?php echo htmlspecialchars($row["account_address_line1"]) ?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                          <input type="text" name="studentAddressLine2" id="studentAddressLine2" class="form-control" placeholder="Line 2" value="<?php echo htmlspecialchars($row["account_address_line2"]) ?>">
                        </div>
                      </div>
                      <div class="form-group row" style="margin-bottom:0px;">
                        <label for="inputEmail" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                          <div class="row">
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <input type="text" name="studentPostcode" id="studentPostcode" class="form-control" placeholder="Postcode" value="<?php echo htmlspecialchars($row["account_postcode"]) ?>">
                              </div>
                            </div>
                          
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <select class="form-control" name="studentState" id="studentState">
                                  <?php
                                    echo '<option value="" selected readonly>----- State -----</option>';
                                    $query = "select * from state";
                                    $result = mysqli_query($con, $query);
                                    while($row1 = mysqli_fetch_assoc($result)){
                                      echo '<option value="'.$row1["state_id"].'" ';
                                      if($row["account_state"] == $row1["state_id"])
                                        echo 'selected ';
                                      echo '>'.$row1["state_name"].'</option>';
                                    }
                                  ?>
                                </select>													
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row" style="margin-bottom:0px;">
                        <label for="inputEmail" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                          <div class="row">
                            <div class="form-group col-lg-6">
                              <div class="input-group">
                                <select class="form-control" name="studentCountry" id="studentCountry">
                                <?php
                                    echo '<option value="" selected readonly>----- Country -----</option>';
                                    $query = "select * from country";
                                    $result = mysqli_query($con, $query);
                                    while($row1 = mysqli_fetch_assoc($result)){
                                      echo '<option value="'.$row1["country_id"].'" ';
                                      if($row["account_country"] == $row1["country_id"])
                                        echo 'selected ';
                                      echo '>'.$row1["country_name"].'</option>';
                                    }
                                ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Date of Enrollment</label>
                        <div class="col-sm-5">
                          <div class="input-group">
                          <?php
                          $datetime = '';
                          if($row["account_enroll_date"] != "0000-00-00" && $row["account_enroll_date"] != ""){
                            $datetime = date('d/m/Y', strtotime($row["account_enroll_date"]));
                          }
                          ?>
                            <input type="text" name="studentEnrollDate" id="studentEnrollDate" class="form-control" readonly value="<?php echo $datetime; ?>">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger" name="submitPersonalInfo">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal" name="passwordForm" id="passwordForm" method="POST">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-5">
                          <div class="input-group">
                            <input type="text" name="studentUsername" id="studentUsername" class="form-control" value="<?php echo htmlspecialchars($row["account_username"]) ?>" readonly>
                            <div class="input-group-append">
                              <div class="input-group-text">
                                <span class="fas fa-user"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-5">
                          <div class="input-group">
                            <input type="password" name="account_password" id="account_password" class="form-control">
                            <div class="input-group-append">
                              <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                              </div>
                            </div>	
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Retype Password</label>
                        <div class="col-sm-5">
                          <div class="input-group">
                            <input type="password" name="studentPasswordRetype" id="studentPasswordRetype" class="form-control">
                            <div class="input-group-append">
                              <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger" name="submitPassword">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer Container -->
  <?php include('includes/footer.php'); ?>
  <!-- /.Footer Container -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- jquery-validation -->
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<!-- Toastr -->
<script src="../plugins/toastr/toastr.min.js"></script>

<script>
  $(function () {
    
    if(localStorage.getItem("Updated")=="1"){
      toastr.success('Your information has been updated successfully.');
      localStorage.clear();
    }

    $("#editEducation").click(function() {
      if($("#adEducationTxt").is(":hidden")){
        $("#adEducationTxt").show();
        $("#adEducationTxtBox").hide();
        $("#editEducation").show();
        $("#saveEducation").hide();
      } else {
        $("#adEducationTxt").hide();
        $("#adEducationTxtBox").show();
        $("#editEducation").hide();
        $("#saveEducation").show();
      }
    });
			
    $("#account_photo").on("change", function() {
        var attachment_checking = "";
				$.map($('#account_photo').get(0).files, function(file) {
				var ext = file.name.split('.').pop().toLowerCase();
					if($.inArray(ext, [,'png','jpg','jpeg','PNG','JPG','JPEG']) == -1) {
						attachment_checking = "No";
					}
				});

				if(attachment_checking != "") {
					alert("Please select correct image format.");
				}
				else{
          document.forms["update_photo"].submit();	
				}
    });

    $('[data-mask]').inputmask()

    $("#saveEducation").click(function() {
      var adId = $(this).data("account_id");
      var adEducation = $("#adEducation").val();
      jQuery.ajax({
        type: "POST",
        url: "../pages/includes/update_account_detail.php",
        data: {
          adId: adId,
          adEducation
        }
      }).done(function(response){
        console.log(response);
        $("#adEducationTxt").text(adEducation);
        $("#adEducationTxt").show();
        $("#adEducationTxtBox").hide();
        $("#editEducation").show();
        $("#saveEducation").hide();
      });
    });

    $("#editLocation").click(function() {
      if($("#adLocationTxt").is(":hidden")){
        $("#adLocationTxt").show();
        $("#adLocationTxtBox").hide();
        $("#editLocation").show();
        $("#saveLocation").hide();
      } else {
        $("#adLocationTxt").hide();
        $("#adLocationTxtBox").show();
        $("#editLocation").hide();
        $("#saveLocation").show();
      }
    });

    jQuery.validator.addMethod("samePassword", function(value, element) {
        return this.optional(element) || $("#account_password").val() == $("#studentPasswordRetype").val();
    });

    $('#passwordForm').validate({ 
      rules: {
        account_password: {
        },
        studentPasswordRetype: {
          samePassword: true
        }
      },
      messages: {
        account_password: {
        },
        studentPasswordRetype: {
          samePassword: "Retype Password must be same as the Password."
        }
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });


    $("#saveLocation").click(function() {
      var adId = $(this).data("account_id");
      var adLocation = $("#adLocation").val();
      jQuery.ajax({
        type: "POST",
        url: "../pages/includes/update_account_detail.php",
        data: {
          adId: adId,
          adLocation
        }
      }).done(function(response){
        console.log(response);
        $("#adLocationTxt").text(adLocation);
        $("#adLocationTxt").show();
        $("#adLocationTxtBox").hide();
        $("#editLocation").show();
        $("#saveLocation").hide();
      });
    });

    $("#editSkills").click(function() {
      if($("#adSkillsTxt").is(":hidden")){
        $("#adSkillsTxt").show();
        $("#adSkillsTxtBox").hide();
        $("#editSkills").show();
        $("#saveSkills").hide();
      } else {
        $("#adSkillsTxt").hide();
        $("#adSkillsTxtBox").show();
        $("#editSkills").hide();
        $("#saveSkills").show();
      }
    });

    $("#saveSkills").click(function() {
      var adId = $(this).data("account_id");
      var adSkills = $("#adSkills").val();
      jQuery.ajax({
        type: "POST",
        url: "../pages/includes/update_account_detail.php",
        data: {
          adId: adId,
          adSkills
        }
      }).done(function(response){
        console.log(response);
        $("#adSkillsTxt").text(adSkills);
        $("#adSkillsTxt").show();
        $("#adSkillsTxtBox").hide();
        $("#editSkills").show();
        $("#saveSkills").hide();
      });
    });

    $("#editNotes").click(function() {
      if($("#adNotesTxt").is(":hidden")){
        $("#adNotesTxt").show();
        $("#adNotesTxtBox").hide();
        $("#editNotes").show();
        $("#saveNotes").hide();
      } else {
        $("#adNotesTxt").hide();
        $("#adNotesTxtBox").show();
        $("#editNotes").hide();
        $("#saveNotes").show();
      }
    });

    $("#saveNotes").click(function() {
      var adId = $(this).data("account_id");
      var adNotes = $("#adNotes").val();
      jQuery.ajax({
        type: "POST",
        url: "../pages/includes/update_account_detail.php",
        data: {
          adId: adId,
          adNotes
        }
      }).done(function(response){
        console.log(response);
        $("#adNotesTxt").text(adNotes);
        $("#adNotesTxt").show();
        $("#adNotesTxtBox").hide();
        $("#editNotes").show();
        $("#saveNotes").hide();
      });
    });

  });
</script>
</body>
</html>
