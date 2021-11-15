<?php
    include("../docs/_includes/connection.php");
    $page = "Attendance";

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    if(isset($_POST["attendance_id"])){	// delete attendance
        $q = "DELETE FROM attendance WHERE attendance_id = '".mysqli_real_escape_string($con,$_POST["attendance_id"])."'";
        mysqli_query($con,$q);

        //echo $q;

        echo '<script>localStorage.setItem("Deleted",1)</script>';	// Successful deleted flag.
        echo "<script>window.location='attendance.php'</script>";	
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Attendance</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/adminlte.min.css">
	<!-- Favicon -->
	<link rel="shortcut icon" href="../dist/img/logo-200x200.png" />
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">
		  <!-- Top Navbar Container -->
          <?php include("includes/navbar.php"); ?>

		<!-- Main Sidebar Container -->
		<?php include('includes/sidebar.php'); ?>
		<!-- /.Main Sidebar Container -->

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/img/logo-200x200.png" alt="AdminLTELogo" height="100" width="100">
        </div>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Attendance</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item active">Attendance</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			 <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Datatable of Attendance</h3>
                                    <h3 class="card-title float-sm-right" ><button type="button" class="btn-sm btn-primary btn-block" id="addNewattendance" ><i class="fa fa-folder-plus"></i>&nbsp;&nbsp;&nbsp;Add New Attendance</button></h3>
                                </div>
                                <div class="card-body">
                                    <table id="attendanceTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Attendance Date</th>
                                                <th>Batch</th>
                                                <th>Course Info</th>
                                                <th>Group Name</th>
                                                <th>Attendance Rate</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $showFilterSelectionDialog = 1;
                                            $query = "SELECT attendance.*, course.*, course_group.*, batch.batch_code, batch.batch_name FROM attendance 
                                            LEFT JOIN batch ON attendance.batch_id = batch.batch_id 
                                            LEFT JOIN course ON attendance.course_id = course.course_id
                                            LEFT JOIN course_group ON attendance.group_id = course_group.group_id ";

                                            if(isset($_GET["batchNo"]) || isset($_GET["courseCode"]) || isset($_GET["groupNo"])){
                                                $showFilterSelectionDialog = 0;
                                                $query .= "WHERE ";

                                                if(isset($_GET["batchNo"])){
                                                    $query .= "batch.batch_id ='".$_GET["batchNo"]."' ";
                                                }

                                                if(isset($_GET["courseCode"])){
                                                    if(isset($_GET["batchNo"]))
                                                        $query .="AND ";
                                                    $query .= "course.course_id ='".$_GET["courseCode"]."' ";
                                                }

                                                if(isset($_GET["groupNo"]) && $_GET["groupNo"] !=""){
                                                    if(isset($_GET["batchNo"]) || isset($_GET["courseCode"]))
                                                        $query .="AND ";
                                                    $query .= "course_group.group_id ='".$_GET["groupNo"]."' ";
                                                }
                                            }
                                            
                                            //echo $query;
                                            $result = mysqli_query($con,$query);
                                            $counter = 1;
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo '<tr>';
                                                    $phpdate = strtotime( $row["attendance_date"] );
                                                    $mysqldate = date( 'd-M-Y', $phpdate );
                                                    echo '<td>'.$counter.'</td>';
                                                    echo '<td>'.$mysqldate.'</td>';
                                                    echo '<td>'.$row["batch_code"].'<br>'.$row["batch_name"].'</td>';
                                                    echo '<td>'.$row["course_code"].'<br>'.$row["course_name"].'</td>';
                                                    echo '<td>'.$row["group_name"].'</td>';

                                                    $query1 = "SELECT FORMAT((
                                                        (SELECT COUNT(*) FROM attendance_sub WHERE attendance_id = '".$row["attendance_id"]."' AND attendance_sub_status = 'Present')
                                                        /
                                                        (SELECT COUNT(*) FROM attendance_sub WHERE attendance_id = '".$row["attendance_id"]."') 
                                                    ) * 100, 0) AS attendanceRate";
                                                    $result1 = mysqli_query($con,$query1);
                                                    if($row1 = mysqli_fetch_assoc($result1)){
                                                        echo '<td>'.$row1["attendanceRate"].'%</td>';
                                                    }
                                                    echo '<td style="text-align: center;">';
                                                        echo '<button type="button" id="deleteattendance" style="margin:1px 3px;" class="btn btn-md btn-danger" data-attendance_id="'.$row["attendance_id"].'"><i class="fa fa-trash-alt"></i></button>';
                                                        echo '<button type="button" id="editattendance" style="margin:1px 3px;" class="btn btn-md btn-info" data-attendance_id="'.$row["attendance_id"].'"><i class="fa fa-edit"></i></button>';
                                                    echo '</td>';
                                                echo '</tr>';
                                                $counter++;
                                            }
                                        ?>
                                        <input type="hidden" name="showFilterSelectionDialog" id="showFilterSelectionDialog" value="<?php echo $showFilterSelectionDialog; ?>">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.Main content -->
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

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Delete attendance?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="delete_form" method="POST" enctype="multipart/form-data" >
                    <input type="hidden" name="attendance_id" id="attendance_id">
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-danger" onclick="delete_attendance()">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="filterModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Filter attendance?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="delete_form" method="POST" enctype="multipart/form-data" >
                    <div class="form-group">
                        <div class="row" style="margin:5px;">
                            <div class="col-md-12">
                                <select class="form-control" name="attendanceBatchId" id="attendanceBatchId">
                                    <?php
                                        echo '<option value="" selected disabled>----- Batch Code -----</option>';
                                        $query = "SELECT * FROM batch WHERE batch_status = 'Open'";
                                        $result = mysqli_query($con, $query);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo '<option value="'.$row["batch_id"].'" ';
                                            echo '>'.$row["batch_code"].' - '.$row["batch_name"].'</option>';
                                        }
                                    ?>
                                </select>		
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row" style="margin:5px;">
                            <div class="col-md-12">
                                <select class="form-control" name="attendanceCourseId" id="attendanceCourseId">
                                    <?php
                                        echo '<option value="" selected disabled>----- Course Code -----</option>';
                                        $query = "SELECT * FROM course WHERE course_status = 'Open'";
                                        $result = mysqli_query($con, $query);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo '<option value="'.$row["course_id"].'" ';
                                            echo '>'.$row["course_code"].' - '.$row["course_name"].'</option>';
                                        }
                                    ?>
                                </select>			
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row" style="margin:5px;">
                            <div class="col-md-12">
                                <select name='attendanceCourseGroup' id='attendanceCourseGroup' class='form-control form-control-sm' disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-info" onclick="filter_attendance()">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<!-- jQuery -->
	<script src="../plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<!-- AdminLTE App -->
	<script src="../dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../dist/js/demo.js"></script>
    <!-- Toastr -->
    <script src="../plugins/toastr/toastr.min.js"></script>
    <script>
        $(document).ready(function(){

            if(localStorage.getItem("Deleted")=="1"){
                toastr.success('Attendance has been deleted successfully.');
                localStorage.clear();
            }

            if(localStorage.getItem("Added")=="1"){
                toastr.success('Attendance has been added successfully.');
                localStorage.clear();
            }
            var showFilterSelectionDialog = $("#showFilterSelectionDialog").val();

            if(showFilterSelectionDialog == 1){	// No filter was selected then prompt filter selection modal.
                $("#filterModal").modal();
            }
		});

        $(function () {

            $(document).on('change','#attendanceCourseId',function(){
				valueCurrent = $(this).val();
				if(valueCurrent != 0){
					getCourseGroupInfo($(this));
				} else {
					$(this).parent().parent().find("select[name='attendanceCourseGroup']").val("0");
					$(this).parent().parent().find("select[name='attendanceCourseGroup']").prop('disabled', true);
				}
			});

            $("#attendanceTable").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#attendanceTable_wrapper .col-md-6:eq(0)');

            $('[id="deleteattendance"]').click(function() {
					var attendance_id = $(this).data('attendance_id');
                    $("#attendance_id").val( attendance_id );
					$("#deleteModal").modal();
			});

            $('[id="editattendance"]').click(function() {
					var attendance_id = $(this).data('attendance_id');
					var url = "../pages/attendance_edit.php?id="+attendance_id;
					window.location.href = url;
			});

            $('[id="addNewattendance"]').click(function() {
					var url = "../pages/attendance_add.php";
					window.location.href = url;
			});
                
        });

        function getCourseGroupInfo(thisObj){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_course_group.php",
				data: {id: thisObj.val()},
				datatype: "json",
				success: function(data, textStatus, xhr) {
					console.log(xhr.responseText);
					data = JSON.parse(xhr.responseText); 

					var select = thisObj.parent().parent().parent().siblings().find("select[name='attendanceCourseGroup']");
					var options = select.prop('options');
					$('option', select).remove();
					select.prop('disabled', false);
					options[options.length] = new Option("----------", "");

					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].id;
						var desc = data[i].name;
						options[options.length] = new Option(desc, id);
					}
				}
			});
		}

        function delete_attendance(){
				document.forms["delete_form"].submit();	
		}

        function filter_attendance(){ 
			var currentUrl = window.location.href;
			var url = new URL(currentUrl);
            var attendanceBatchId = "";
            var attendanceCourseId = "";
            var attendanceCourseGroup = "";

            if($("#attendanceBatchId").val() != undefined){
                attendanceBatchId = $("#attendanceBatchId").val();
                url.searchParams.set("batchNo", attendanceBatchId);
            }

            if($("#attendanceCourseId").val() != undefined){
                attendanceCourseId = $("#attendanceCourseId").val();
                url.searchParams.set("courseCode", attendanceCourseId);
            }

            if($("#attendanceCourseGroup").val() != undefined && $("#attendanceCourseGroup").val() != ""){
                attendanceCourseGroup = $("#attendanceCourseGroup").val();
                url.searchParams.set("groupNo", attendanceCourseGroup);
            }
            

            var newUrl = url.href; 
			window.location.href = newUrl; 
		}
    </script>
</body>

</html>