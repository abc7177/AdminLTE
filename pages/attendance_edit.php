<?php
include("../docs/_includes/connection.php");
$page = "Attendance";
$attendance_id = $_GET["id"];

if (!(isset($_SESSION["itac_user_id"]))) {
	echo "<script>window.location='login.php'</script>";
	exit;
}

// if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
// 	echo "<script>alert('You have no permission to access this module.')</script>";
// 	echo "<script>window.location='index.php'</script>";	
// 	exit;
// }
	$query0 = "SELECT * FROM attendance WHERE attendance_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
    $result0 = mysqli_query($con,$query0);
    if(!($row0 = mysqli_fetch_assoc($result0))){
        echo "<script>alert('Attendance record was not found.')</script>";
        echo "<script>window.location= 'attendance.php'</script>";
        exit;
    }

if (isset($_POST["attendanceId"])) {

	$query = "UPDATE attendance SET  
				attendance_date = '" . mysqli_real_escape_string($con, $_POST["attendanceDate"]) . "', 
				attendance_remark = '" . mysqli_real_escape_string($con, $_POST["attendanceDescription"]) . "'
				WHERE attendance_id = '" . mysqli_real_escape_string($con, $_POST["attendanceId"]) . "'";
	$result = mysqli_query($con, $query);
	//echo $query;

	echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful added flag.
	echo "<script>window.location='attendance_edit.php?id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
	
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Attendance Marking</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/adminlte.min.css">
	<!-- Favicon -->
	<link rel="shortcut icon" href="../dist/img/logo-200x200.png" />
	<!-- Toastr -->
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
	<!-- Customize style -->
	<link rel="stylesheet" href="../dist/css/customize.css">
</head>

<body class="hold-transition sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Top Navbar Container -->
		<?php include("includes/navbar.php"); ?>

		<!-- Main Sidebar Container -->
		<?php include('includes/sidebar.php'); ?>
		<!-- /.Main Sidebar Container -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Attendance Marking</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<?php
								if(isset($_GET["viewType"]) == "readonly"){
									echo '<li class="breadcrumb-item"><a href="../index.php?asTutor='.$_GET["asTutor"].'">Home</a></li>';
									echo '<li class="breadcrumb-item"><a href="attendance.php?asTutor='.$_GET["asTutor"].'">Attendance</a></li>';
									echo '<li class="breadcrumb-item active">Attendance Marking</li>';
								} else {
									echo '<li class="breadcrumb-item"><a href="../index.php">Home</a></li>';
									echo '<li class="breadcrumb-item"><a href="attendance.php">Attendance</a></li>';
									echo '<li class="breadcrumb-item active">Attendance Marking</li>';
								}
								?>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_attendance" id="add_attendance" class="col-lg-12" method="POST">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-primary">
								<div class="card-header">
									<h3 class="card-title">General</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label for="inputName">Batch Code</label>
										<input type="hidden" name="attendanceId" id="attendanceId" class="form-control" value="<?php echo $attendance_id; ?>">
										<select class="form-control" name="attendanceBatchId" id="attendanceBatchId" readonly>
											<?php
												echo '<option value="" selected disabled>----- Batch Code -----</option>';
												$query = "SELECT * FROM batch WHERE batch_status = 'Open'";
												$result = mysqli_query($con, $query);
												while($row = mysqli_fetch_assoc($result)){
													echo '<option value="'.$row["batch_id"].'" ';
													if($row0["batch_id"] == $row["batch_id"])
														echo 'selected ';
													echo '>'.$row["batch_code"].' - '.$row["batch_name"].'</option>';
												}
											?>
										</select>		
									</div>
									<div class="form-group">
										<label for="inputName">Course Code</label>
										<input type="hidden" name="attendanceId" id="attendanceId" class="form-control" value="<?php echo $row0["attendance_id"]; ?>">
										<input type="hidden" class="attendanceCourseGroup" class="form-control" value="<?php echo $row0["group_id"]; ?>">
										<select class="form-control" name="attendanceCourseId" id="attendanceCourseId" readonly>
											<?php
												echo '<option value="" selected disabled>----- Course Code -----</option>';
												$query = "SELECT * FROM course WHERE course_status = 'Open'";
												$result = mysqli_query($con, $query);
												while($row = mysqli_fetch_assoc($result)){
													echo '<option value="'.$row["course_id"].'" ';
													if($row0["course_id"] == $row["course_id"])
														echo 'selected ';
													echo '>'.$row["course_code"].' - '.$row["course_name"].'</option>';
												}
											?>
										</select>		
									</div>
									<!-- <div class="form-group">
										<label for="inputName">Group Name</label>
										<select name='attendanceCourseGroup' id='attendanceCourseGroup' class='form-control form-control-sm'></select>
									</div> -->
									
									<div class="form-group" style="margin-bottom:0px;">
										<label for="attendanceDate">Date</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="date" name="attendanceDate" id="attendanceDate" class="form-control" value="<?php echo date('Y-m-d', strtotime($row0["attendance_date"])) ?>">
													<div class="input-group-prepend">
														<!-- <span class="input-group-text"><i class="far fa-calendar-alt"></i></span> -->
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="attendanceDescription">Description / Remarks</label>
										<textarea name="attendanceDescription" id="attendanceDescription" class="form-control" rows="4"><?php echo $row0["attendance_remark"]; ?></textarea>
									</div>
									<!-- <div class="form-group">
										<div class="row">
											<div class="form-group col-lg-12">
												<div class="input-group">
													<button type="button" class="btn btn-info btn-block" id="generateStudentList" disabled><i class="fa fa-arrow-right"></i> NEXT</button>
												</div>
											</div>
										</div>
									</div> -->
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<div class="col-md-12">
							
							<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">Attendance Information</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-12">
											<?php
												$query0 = "SELECT course_group.* FROM attendance_sub LEFT JOIN course_group ON attendance_sub.group_id = course_group.group_id 
														WHERE attendance_sub.attendance_id = '".mysqli_real_escape_string($con,$_GET["id"])."' group by attendance_sub.group_id";
												$result0 = mysqli_query($con, $query0);
												//echo $query0;
												while($row0 = mysqli_fetch_assoc($result0)){	
											?>
											<div class="card">
											<div class="card-header bg-success">
												Student List: <?php echo $row0["group_name"];?>
											</div>
										
											<div class="card-body p-0">
												<div style='overflow:auto; width:100%;position:relative;'>
													<table id="enrolledStudentTable" class="table table-hover">
													<tbody>
														<tr id='enrolledCourseHeader'>
															<td style='width:20px; text-align:center;'>No.</td>
															<td>Student Name</td>
															<td style='width:100px; text-align:center;'>Present</td><td style='width:100px; text-align:center;'>Late</td>
															<td style='width:100px; text-align:center;'>MC / Leave</td><td style='width:100px; text-align:center;'>Absent</td>
														</tr>

														<?php
														$counter = 0;
														$query1 = "SELECT * FROM attendance_sub LEFT JOIN account ON attendance_sub.account_id = account.account_id 
														WHERE attendance_sub.attendance_id = '".mysqli_real_escape_string($con,$_GET["id"])."' AND attendance_sub.group_id= '".$row0["group_id"]."'";
														$result1 = mysqli_query($con, $query1);

														while($row1 = mysqli_fetch_assoc($result1)){
															$counter++;
															echo "<tr><td style='width:20px; text-align:center;'>".$counter."<input type='hidden' name='studentId' class='form-control form-control-sm studentId' value='".$row1["account_id"]."'></div></td>";
															echo "<td><div class='col-sm-6'>".$row1["account_name"]."</div></td>";
															echo "<td style='width:100px; text-align:center;'>";
															echo "<div data-toggle='buttons' class='btn'><label class=' ";
																if($row1["attendance_sub_status"] == 'Present'){echo 'green-icon';}
															echo "'>";
															echo "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='Present' ";
																if($row1["attendance_sub_status"] == 'Present'){echo 'checked';}
															echo "> <i class='fas fa-check'></i></label></div></td>";
															echo "<td style='width:100px; text-align:center;'><div data-toggle='buttons' class='btn'><label class=' ";
																if($row1["attendance_sub_status"] == 'Late'){echo 'green-icon';}
															echo "'>";
															echo "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='Late' ";
																if($row1["attendance_sub_status"] == 'Late'){echo 'checked';}
															echo "> <i class='fas fa-check'></i></label></div></td>";
															echo "<td style='width:100px; text-align:center;'><div data-toggle='buttons' class='btn'><label class=' ";
																if($row1["attendance_sub_status"] == 'MC / Leave'){echo 'green-icon';}
															echo "'>";
															echo "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='MC / Leave' ";
																if($row1["attendance_sub_status"] == 'MC / Leave'){echo 'checked';}
															echo "> <i class='fas fa-check'></i></label></div></td>";
															echo "<td style='width:100px; text-align:center;'><div data-toggle='buttons' class='btn'><label class=' ";
																if($row1["attendance_sub_status"] == 'Absent'){echo 'green-icon';}
															echo "'>";
															echo "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='Absent' ";
																if($row1["attendance_sub_status"] == 'Absent'){echo 'checked';}
															echo "> <i class='fas fa-check'></i></label></div></td>";
															echo "</tr>";
														}
														?>
														
													</tbody>
													</table>
												</div>
											</div>										
											</div>
											<?php } ?>										
										</div>
										<div class="col-12">
											<div class="form-group">
												<div class="row">
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="button" class="btn btn-danger btn-block" onclick="window.location='attendance.php<?php if(isset($_GET['asTutor'])){ echo '?asTutor='.$_GET['asTutor']; }?>'"><i class="fa fa-arrow-left"></i> BACK</button>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="submit" id="submitAttendanceForm" class="btn btn-primary btn-block" 
															<?php if(isset($_GET["viewType"]) == "readonly" || isset($_GET["asTutor"])){ echo "disabled"; }?>
															>Update Attendance <i class="fa fa-check"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
						</div>
					</div>
				</form>
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
	<!-- AdminLTE App -->
	<script src="../dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../dist/js/demo.js"></script>
	<!-- InputMask -->
	<script src="../plugins/moment/moment.min.js"></script>
	<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
	<!-- Bootstrap Switch -->
	<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<!-- jquery-validation -->
	<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
	<!-- Toastr -->
	<script src="../plugins/toastr/toastr.min.js"></script>
	<script>

		window.onpageshow = function(event) {	// The document.ready function will not be called when running window.history, so use this instead.
			if(localStorage.getItem("attendanceCodeUsed")=="1"){
				toastr.error('Attendance code has been registered. Kindly change the attendance code.');
				localStorage.clear();
			}

			if(localStorage.getItem("Updated")=="1"){
                toastr.success('Attendance information has been updated successfully.');
                localStorage.clear();
            }
		};

		function getCourseGroupInfo(thisObj, groupObj){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_course_group.php",
				data: {id: thisObj.val()},
				datatype: "json",
				success: function(data, textStatus, xhr) {
					console.log(xhr.responseText);
					data = JSON.parse(xhr.responseText);

					var select = thisObj.parent().parent().find("select[name='attendanceCourseGroup']");
					var options = select.prop('options');
					$('option', select).remove();
					select.prop('disabled', true);
					options[options.length] = new Option("----------", "");

					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].id;
						var desc = data[i].name;
						options[options.length] = new Option(desc, id);
					}
					select.val(groupObj.val()).change();
				}
			});
		}

		function getEnrolledStudentInfo(courseId, courseGroupId){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_enrolled_student.php",
				data: {
					courseId: courseId,
					courseGroupId: courseGroupId
				},
				datatype: "json",
				success: function(data, textStatus, xhr) {
					console.log(xhr.responseText);
					data = JSON.parse(xhr.responseText);

					var tbl = $("#enrolledStudentTable");
					var tblHeader = "";
					var appendRow = "";

					if($("#enrolledCourseHeader").length == 0){
						tblHeader = "<tr id='enrolledCourseHeader'><td style='width:20px; text-align:center;'>No.</td><td>Student Name</td>"+
						"<td style='width:100px; text-align:center;'>Present</td><td style='width:100px; text-align:center;'>Late</td>"+
						"<td style='width:100px; text-align:center;'>MC / Leave</td><td style='width:100px; text-align:center;'>Absent</td></tr>";
					}

					appendRow += tblHeader;
					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].studentId;
						var desc = data[i].studentName;
						appendRow += "<tr><td style='width:20px; text-align:center;'>"+(i+1)+"<input type='hidden' name='studentId' class='form-control form-control-sm studentId' value='"+id+"'></div></td>";
						appendRow += "<td><div class='col-sm-6'>"+desc+"</div></td>";
						appendRow += "<td style='width:100px; text-align:center;'>";
						appendRow += "<div data-toggle='buttons'><label class='btn'>";
						appendRow += "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='Present'> <i class='fas fa-check'></i></label></div></td>";
						appendRow += "<td style='width:100px; text-align:center;'><div data-toggle='buttons'><label class='btn'>";
						appendRow += "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='Late'> <i class='fas fa-check'></i></label></div></td>";
						appendRow += "<td style='width:100px; text-align:center;'><div data-toggle='buttons'><label class='btn'>";
						appendRow += "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='MC / Leave'> <i class='fas fa-check'></i></label></div></td>";
						appendRow += "<td style='width:100px; text-align:center;'><div data-toggle='buttons'><label class='btn'>";
						appendRow += "<input type='radio' class='studentAttendance' name='options' style='display:none;' value='Absent'> <i class='fas fa-check'></i></label></div></td>";
                		//appendRow += "</div></td>";
						appendRow += "</tr>";
					}
					tbl.find("tr").remove();
					$(appendRow).appendTo(tbl);
				}
			});
		}

		function setStudentAttendanceInfo(attendanceId, studentId, valueCurrent){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/set_student_attendance.php",
				data: {
					studentId: studentId,
					valueCurrent: valueCurrent,
					attendanceId: attendanceId
				}
			}).done(function(response){
				console.log(response);
			});
		}
		
		$(function() {

			var currentUrl = window.location.href;
        	var url = new URL(currentUrl);
					
			if(url.searchParams.has("asTutor") == true){
				$('input, textarea').attr('disabled', true);
			}
			
			$(document).on('change','#attendanceCourseId',function(){
				valueCurrent = $(this).val();

				if(valueCurrent != 0){
					getCourseGroupInfo($(this));
				} else {
					$(this).parent().parent().find("select[name='attendanceCourseGroup']").val("0");
					$(this).parent().parent().find("select[name='attendanceCourseGroup']").prop('disabled', true);
				}
			});

			$(document).on('change','.studentAttendance',function(){  //alert("YO");
				var valueCurrent = $(this).val();
				var studentId = $(this).closest("tr").find("input[name='studentId']").val();
				var attendanceId = $("#attendanceId").val();
				$(this).closest("tr").find("label").removeClass("green-icon");
				$(this).closest("label").addClass("green-icon");

				setStudentAttendanceInfo(attendanceId, studentId, valueCurrent);
			});

			$("#add_attendance").submit(function(){
				if($(this).valid()) {
					$("#attendanceCourseId").prop('disabled', false);
					$("#attendanceCourseGroup").prop('disabled', false);
				}
			});

			$("#generateStudentList").click(function(){
				var tbl = $("#enrolledStudentTable");
				var courseId = $("#attendanceCourseId").val();
				var courseGroupId = $("#attendanceCourseGroup").val();

				if(!$("#add_attendance").valid()){
					return;
				}
				
				//$("#attendanceCourseId").prop('disabled', true);
				//$("#attendanceCourseGroup").prop('disabled', true);
				//$("#generateStudentList").prop('disabled', true);

				getEnrolledStudentInfo(courseId, courseGroupId);
			});

			$(document.body).delegate(".addModuleCriteria", "click", function(){
				var tbl = $(this).closest('table');
				var moduleId = $(this).attr('data-moduleId');

				$("<tr><td style='padding:10px;'><div class='form-group row' style='padding:0px 20px;'><label>Evaluation Criteria</label>"+
				"<div class='col-sm-6'><input type='text' name='moduleCriteriaName' class='form-control form-control-sm moduleCriteriaName'></div>"+
				"</div></td><td style='text-align: right;background-color: transparent; padding:10px;'><button class='btn btn-sm moduleCriteriaDelBtn red-icon' data-moduleId='"+moduleId+"'>"+
				"<i class='fa fa-trash-alt'></i> Remove</button><button class='btn btn-sm moduleCriteriaSaveBtn green-icon' data-moduleId='"+moduleId+"'><i class='fas fa-check'></i> Save</button>"+
				"</td></tr>").appendTo(tbl);
			});

			$(document.body).delegate(".moduleSaveBtn", "click", function(){
				var tbl = $("#moduleTable");
				var moduleCode = $(this).closest("tr").find("input[name='moduleCode']").val();
				var moduleName = $(this).closest("tr").find("input[name='moduleName']").val();
				var attendanceId = $("#attendanceId").val();
				var moduleId = $(this).attr('data-moduleId');
				

				if(moduleId != undefined){
					var flag = "update";
					var url = "../pages/includes/update_attendance_module.php";
				} else {
					var flag = "add";
					var url = "../pages/includes/add_attendance_module.php";
					$(this).closest("tr").remove();
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						attendanceId: attendanceId,
						moduleId: moduleId,
						moduleCode: moduleCode,
						moduleName: moduleName
					}
				}).done(function(response){
					if(flag == "add"){
						$("<tr data-widget='expandable-table' aria-expanded='true' class='bg-olive'><td>"+
						"<div class='form-group row'>"+
						"<label><i class='expandable-table-caret fas fa-caret-right fa-fw'></i>Code</label><div class='col-sm-6'><input type='text' name='moduleCode' class='form-control form-control-sm' value='"+moduleCode+"'></div></div></td>"+
						"<td><div class='form-group row'><label>Name</label><div class='col-sm-6'><input type='text' name='moduleName' class='form-control form-control-sm moduleName' value='"+moduleName+"'></div></div></td>"+
						"<td style='text-align: right;background-color: transparent; padding:10px;'><button class='btn btn-sm moduleDelBtn white-icon' data-moduleId='"+response+"'><i class='fa fa-trash-alt'></i> Remove</button><button class='btn btn-sm moduleSaveBtn  white-icon' data-moduleId='"+response+"'><i class='fas fa-check'></i> Save</button></td></tr>"+
						"<tr class='expandable-body'><td colspan='3'><div class='p-0'><table class='table table-hover'><tbody>"+
						"<tr><td style='padding:10px;'><button type='button' class='btn btn-sm addModuleCriteria' style='background-color:transparent;' data-moduleId='"+response+"'><i class='fas fa-plus'></i> Add New Criteria</button></td></tr></tbody></table></div></td></tr>"
						).appendTo(tbl);
					}
					console.log(response);
				});
			});
				
			$(document.body).delegate(".moduleDelBtn", "click", function(){
				var moduleId = $(this).attr('data-moduleId');
				var $tr = $(this).closest('tr');

				if ($tr.attr('data-widget') == 'expandable-table') {
					$tr.nextUntil('tr[data-widget=expandable-table]').remove();
					$tr.remove();
				}
				else {
					$tr.remove();
				}

				if(moduleId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_attendance_module.php",
						data: {
							moduleId: moduleId
						}
					}).done(function(response){
						console.log(response);
					});
						
				}
			});    

			$(document.body).delegate(".moduleCriteriaSaveBtn", "click", function(){
				var tbl = $(this).closest('table');
				var moduleCriteriaName = $(this).closest("tr").find("input[name='moduleCriteriaName']").val();
				var moduleId = $(this).attr('data-moduleId');
				var moduleCriteriaId = $(this).attr('data-moduleCriteriaId');
				
				if(moduleCriteriaId != undefined){
					var flag = "update";
					var url = "../pages/includes/update_attendance_module_criteria.php";
				} else {
					var flag = "add";
					var url = "../pages/includes/add_attendance_module_criteria.php";
					$(this).closest("tr").remove();
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						moduleId: moduleId,
						moduleCriteriaId: moduleCriteriaId,
						moduleCriteriaName: moduleCriteriaName
					}
				}).done(function(response){
					if(flag == "add"){
						$("<tr><td style='padding:10px;'><div class='form-group row' style='padding:0px 20px;'><label>Evaluation Criteria</label><div class='col-sm-6'>"+
						"<input type='text' name='moduleCriteriaName' class='form-control form-control-sm moduleCriteriaName'  value='"+moduleCriteriaName+"'></div></div>"+
						"</td><td style='text-align: right;background-color: transparent; padding:10px;'><button class='btn btn-sm moduleCriteriaDelBtn red-icon' data-moduleCriteriaId='"+response+"'>"+
						"<i class='fa fa-trash-alt'></i> Remove</button><button class='btn btn-sm moduleCriteriaSaveBtn green-icon' data-moduleCriteriaId='"+response+"'>"+
						"<i class='fas fa-check'></i> Save</button>	</td></tr>").appendTo(tbl);
					}
					console.log(response);
				});
			});
				
			$(document.body).delegate(".moduleCriteriaDelBtn", "click", function(){
				var moduleCriteriaId = $(this).attr('data-moduleCriteriaId');
				$(this).closest("tr").remove();

				if(moduleCriteriaId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_attendance_module_criteria.php",
						data: {
							moduleCriteriaId: moduleCriteriaId
						}
					}).done(function(response){
						console.log(response);
					});
						
				}
			});

			$(document.body).delegate(".groupSaveBtn", "click", function(){
				var tbl = $("#groupTable");
				var groupName = $(this).closest("tr").find("input[name='groupName']").val();
				var groupCapacity = $(this).closest("tr").find("input[name='groupCapacity']").val();
				var groupId = $(this).attr('data-groupId');
				var attendanceId = $("#attendanceId").val();
				$(this).closest("tr").remove();

				if(groupId != undefined){
					var url = "../pages/includes/update_attendance_group.php";
				} else {
					var url = "../pages/includes/add_attendance_group.php";
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						attendanceId: attendanceId,
						groupName: groupName,
						groupCapacity: groupCapacity,
						groupId: groupId
					}
				}).done(function(response){
					
					$("<tr class='bg-olive'><td><div class='form-group row'><label>Name</label><div class='col-sm-6'>"+
					"<input type='text' name='groupName' class='form-control form-control-sm groupName' value='"+groupName+"'></div></div></td>"+
					"<td><div class='form-group row'><label>Capacity</label><div class='col-sm-6'>"+
					"<input type='text' name='groupCapacity' class='form-control form-control-sm groupCapacity'  value='"+groupCapacity+"'></div></div></td>"+
					"<td style='text-align: right;background-color: transparent;'><button class='btn btn-sm groupDelBtn white-icon' data-groupId='"+response+"'>"+
					"<i class='fa fa-trash-alt'></i> Remove</button><button class='btn btn-sm groupSaveBtn white-icon' data-groupId='"+response+"'><i class='fas fa-check'></i> Save</button>"+
					"</td></tr>").appendTo(tbl);
					console.log(response);
				});
			});
				
			$(document.body).delegate(".groupDelBtn", "click", function(){
				var groupId = $(this).attr('data-groupId');
				$(this).closest("tr").remove();

				if(groupId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_attendance_group.php",
						data: {
							groupId: groupId
						}
					}).done(function(response){
						console.log(response);
					});
						
				}
			});    
    

			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

			$('#add_attendance').validate({
				rules: {
					attendanceCourseId: {
						required: true
					},
					attendanceCourseGroup: {
						required: true
					},
					attendanceDate: {
						required: true
					}
				},
				messages: {
					attendanceCourseId: {
						required: "Please select a course"
					},
					attendanceCourseGroup: {
						required: "Please select a course group"
					},
					attendanceDate: {
						required: "Please enter the date"
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
		});
	</script>
</body>

</html>