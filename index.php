<?php
include("docs/_includes/connection.php");
$page = "Dashboard";


if (!(isset($_SESSION["itac_user_id"]))) {
  //echo "<script>window.location='/demo/howard/itac/pages/login.php'</script>";
  echo "<script>window.location='/pages/login.php'</script>";
  echo "<script>window.location='".$_SESSION['itac_path_configuration']."'/itac/pages/login.php'</script>";
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


    ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
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
              <a href="pages/course.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            
            <!-- /.card -->

            <!-- TO DO List -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  Today's Evaluation
                </h3>

                <!-- <div class="card-tools">
                  <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                  </ul>
                </div> -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                  <?php
                    $query = "SELECT evaluation.*, course.*, course_group.*, batch.batch_code, batch.batch_name FROM evaluation 
                    LEFT JOIN batch ON evaluation.batch_id = batch.batch_id 
                    LEFT JOIN course ON evaluation.course_id = course.course_id
                    LEFT JOIN course_group ON evaluation.group_id = course_group.group_id 
                    WHERE CAST(evaluation_date AS DATE) = CAST(now() AS DATE)";

                    $result = mysqli_query($con,$query);
                    $counter = 1;
                    while($row = mysqli_fetch_assoc($result)){
                      echo "<li>";
                        // <!-- drag handle -->
                        echo "<span class='handle'>";
                          echo "<i class='fas fa-ellipsis-v'></i>";
                          echo "<i class='fas fa-ellipsis-v'></i>";
                        echo "</span>";
                        // <!-- checkbox -->
                        echo "<div  class='icheck-primary d-inline ml-2'>";
                        echo "</div>";
                        // <!-- todo text -->
                        echo "<span class='text'> Batch: ".$row["batch_name"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Course: ".$row["course_code"]." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Group: ".$row["group_name"]."</span>";
                        // <!-- Emphasis label -->
                        $query1 = "SELECT FORMAT((
                            (SELECT SUM(eva_rate) FROM evaluation_sub WHERE evaluation_id = '".$row["evaluation_id"]."')
                            /
                            (SELECT COUNT(*) FROM evaluation_sub WHERE evaluation_id = '".$row["evaluation_id"]."') 
                        ), 2) AS evaluationRate";

                        $result1 = mysqli_query($con,$query1);
                        if($row1 = mysqli_fetch_assoc($result1)){
                          echo "<small class='badge ";

                          if($row1["evaluationRate"] < 3)
                            echo "badge-danger";
                          else
                            echo "badge-success";
                          echo "'> ".$row1["evaluationRate"]." / 5.00 <i class='fas fa-percent'></i></small>";
                        }
                        
                        // <!-- General tools such as edit or delete-->
                        echo "<div class='tools'>";
                          echo "<i class='fas fa-edit editEvaluation' data-evaluation_id='".$row["evaluation_id"]."'></i>";
                          echo "<i class='as fa-trash-o'></i>";
                        echo"</div>";
                      echo "</li>";
                    }
                  ?>
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <button type="button" class="btn btn-primary float-right" id="addEvaluation"><i class="fas fa-plus"></i> Add Evaluations</button>
              </div>
            </div>
            <!-- /.card -->
            <!-- TO DO List -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  Today's Attendance
                </h3>

                <!-- <div class="card-tools">
                  <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                  </ul>
                </div> -->
              </div>
              <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                  <?php
                    $query = "SELECT attendance.*, course.*, course_group.*, batch.batch_code, batch.batch_name FROM attendance 
                    LEFT JOIN batch ON attendance.batch_id = batch.batch_id 
                    LEFT JOIN course ON attendance.course_id = course.course_id
                    LEFT JOIN course_group ON attendance.group_id = course_group.group_id 
                    WHERE CAST(attendance_date AS DATE) = CAST(now() AS DATE)";

                    $result = mysqli_query($con,$query);
                    $counter = 1;
                    while($row = mysqli_fetch_assoc($result)){
                      echo "<li>";
                        // <!-- drag handle -->
                        echo "<span class='handle'>";
                          echo "<i class='fas fa-ellipsis-v'></i>";
                          echo "<i class='fas fa-ellipsis-v'></i>";
                        echo "</span>";
                        // <!-- checkbox -->
                        echo "<div  class='icheck-primary d-inline ml-2'>";
                        echo "</div>";
                        // <!-- todo text -->
                        echo "<span class='text'> Batch: ".$row["batch_name"]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Course: ".$row["course_code"]." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Group: ".$row["group_name"]."</span>";
                        // <!-- Emphasis label -->
                        $query1 = "SELECT FORMAT((
                          (SELECT COUNT(*) FROM attendance_sub WHERE attendance_id = '".$row["attendance_id"]."' AND attendance_sub_status = 'Present')
                          /
                          (SELECT COUNT(*) FROM attendance_sub WHERE attendance_id = '".$row["attendance_id"]."') 
                      ) * 100, 0) AS attendanceRate";

                        $result1 = mysqli_query($con,$query1);
                        if($row1 = mysqli_fetch_assoc($result1)){
                          echo "<small class='badge ";

                          if($row1["attendanceRate"] < 3)
                            echo "badge-danger";
                          else
                            echo "badge-success";
                          echo "'> ".$row1["attendanceRate"]." / 100 <i class='fas fa-percent'></i></small>";
                        }
                        
                        // <!-- General tools such as edit or delete-->
                        echo "<div class='tools'>";
                          echo "<i class='fas fa-edit editAttendance' data-attendance_id='".$row["attendance_id"]."'></i>";
                          echo "<i class='as fa-trash-o'></i>";
                        echo"</div>";
                      echo "</li>";
                    }
                  ?>
                </ul>
              </div>
              <div class="card-footer clearfix">
                <button type="button" class="btn btn-primary float-right" id="addAttendance"><i class="fas fa-plus"></i> Add Attendance</button>
              </div>
            </div>
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">
          <!-- Calendar -->
          <div class="card bg-gradient-success">
              <div class="card-header border-0">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                  <!-- button with a dropdown -->
                  <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                      <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- Map card -->
            <div class="card bg-gradient-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-map-marker-alt mr-1"></i>
                  Daily Satistic
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                  <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                    <i class="far fa-calendar-alt"></i>
                  </button>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              
              <!-- /.card-body-->
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center">
                    <div id="sparkline-1"></div>
                    <div class="text-white">Visitors</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <div id="sparkline-2"></div>
                    <div class="text-white">Students</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <div id="sparkline-3"></div>
                    <div class="text-white">Tutors</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.card -->

   

            
          </section>
          <!-- right col -->
        </div>
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
<script>
$(function() {
  $("#addEvaluation").click(function(){
			var url = "pages/evaluation_add.php?&eva_type=daily";
			window.location.href = url;

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
</script>
</body>
</html>
