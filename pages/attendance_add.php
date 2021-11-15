<?php
include("../docs/_includes/connection.php");
$page = "Attendance";
$attendance_id = uniqid();

if (!(isset($_SESSION["itac_user_id"]))) {
	echo "<script>window.location='login.php'</script>";
	exit;
}

// if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
// 	echo "<script>alert('You have no permission to access this module.')</script>";
// 	echo "<script>window.location='index.php'</script>";	
// 	exit;
// }

if (isset($_POST["attendanceId"])) {
	$query = "SELECT * FROM attendance WHERE attendance_id = '" . mysqli_real_escape_string($con, $_POST["attendanceId"]) . "'";
	$result = mysqli_query($con, $query);
	if ($result = mysqli_fetch_assoc($result)) {
		echo '<script>localStorage.setItem("attendanceCodeUsed",1)</script>';	// Username used flag.
		echo "<script>window.history.go(-1)</script>";
	} else {

		$query = "INSERT INTO attendance SET attendance_id = '" . mysqli_real_escape_string($con, $_POST["attendanceId"]) . "', 
					attendance_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["attendanceDate"]) . "', '%d/%m/%Y'), 
					attendance_remark = '" . mysqli_real_escape_string($con, $_POST["attendanceDescription"]) . "', 
					account_id = '" . $_SESSION["itac_user_id"] . "', 
					batch_id = '" . mysqli_real_escape_string($con, $_POST["attendanceBatchId"]) . "', 
					course_id = '" . mysqli_real_escape_string($con, $_POST["attendanceCourseId"]) . "', 
					group_id = '" . mysqli_real_escape_string($con, $_POST["attendanceCourseGroup"]) . "'";
		$result = mysqli_query($con, $query);
		//echo $query;

		echo '<script>localStorage.setItem("Added",1)</script>';	// Successful added flag.
		echo "<script>window.location='attendance.php'</script>";
	}
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
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="attendance.php">Attendance</a></li>
								<li class="breadcrumb-item active">Attendance Marking</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_attendance" id="add_attendance" class="col-lg-12" method="POST">
					<div class="row">
						<div class="col-md-4">
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
									<div class="form-group">
										<label for="inputName">Course Code</label>
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
									<div class="form-group">
										<label for="inputName">Group Name</label>
										<select name='attendanceCourseGroup' id='attendanceCourseGroup' class='form-control form-control-sm' disabled></select>
									</div>
									
									<div class="form-group" style="margin-bottom:0px;">
										<label for="attendanceDate">Date</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="attendanceDate" id="attendanceDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="Date" value="<?php echo date('d/m/Y', strtotime("now")) ?>">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="attendanceDescription">Description / Remarks</label>
										<textarea name="attendanceDescription" id="attendanceDescription" class="form-control" rows="4"></textarea>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-12">
												<div class="input-group">
													<button type="button" class="btn btn-info btn-block" id="generateStudentList"><i class="fa fa-arrow-right"></i> NEXT</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<div class="col-md-8">
							
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
											<div class="card">
											<div class="card-header bg-success">
												Enrolled Student Information
											</div>
											<!-- ./card-header -->
											<div class="card-body p-0">
												<table id="enrolledStudentTable" class="table table-hover">
												<tbody>
													<tr>
														<td style='padding:10px;' colspan="3"><button type="button" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> No student list was generated</button></td>
													</tr>
													
												</tbody>
												</table>
											</div>
											<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
										<div class="col-12">
											<div class="form-group">
												<div class="row">
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="button" class="btn btn-danger btn-block" onclick="window.location='attendance.php'"><i class="fa fa-arrow-left"></i> BACK</button>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="submit" id="submitAttendanceForm" class="btn btn-primary btn-block">Add Attendance <i class="fa fa-check"></i></button>
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
		};

		function getCourseGroupInfo(thisObj){	
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

		function getEnrolledStudentInfo(batchId, courseId, courseGroupId){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_enrolled_student.php",
				data: {
					batchId: batchId,
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

			$(document).on('change','#attendanceCourseId',function(){
				valueCurrent = $(this).val();

				if(valueCurrent != 0){
					getCourseGroupInfo($(this));
				} else {
					$(this).parent().parent().find("select[name='attendanceCourseGroup']").val("0");
					$(this).parent().parent().find("select[name='attendanceCourseGroup']").prop('disabled', true);
				}
			});

			$(document).on('change','.studentAttendance',function(){
				var valueCurrent = $(this).val();
				var studentId = $(this).closest("tr").find("input[name='studentId']").val();
				var attendanceId = $("#attendanceId").val();
				$(this).closest("tr").find("label").removeClass("green-icon");
				$(this).closest("label").addClass("green-icon");

				setStudentAttendanceInfo(attendanceId, studentId, valueCurrent);
			});

			$("#add_attendance").submit(function(){
				if($(this).valid()) {
					$("#attendanceBatchId").prop('disabled', false);
					$("#attendanceCourseId").prop('disabled', false);
					$("#attendanceCourseGroup").prop('disabled', false);
				}
			});

			$("#generateStudentList").click(function(){
				var tbl = $("#enrolledStudentTable");
				var batchId = $("#attendanceBatchId").val();
				var courseId = $("#attendanceCourseId").val();
				var courseGroupId = $("#attendanceCourseGroup").val();

				if(!$("#add_attendance").valid()){
					return;
				}
				
				$("#attendanceBatchId").prop('disabled', true);
				$("#attendanceCourseId").prop('disabled', true);
				$("#attendanceCourseGroup").prop('disabled', true);
				$("#generateStudentList").prop('disabled', true);

				getEnrolledStudentInfo(batchId, courseId, courseGroupId);
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
					attendanceBatchId: {
						required: true
					},
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
					attendanceBatchId: {
						required: "Please select a batch"
					},
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