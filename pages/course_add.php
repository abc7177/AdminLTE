<?php
include("../docs/_includes/connection.php");
$page = "Course";
$course_id = uniqid();
$module_id = uniqid();

if (!(isset($_SESSION["itac_user_id"]))) {
	echo "<script>window.location='login.php'</script>";
	exit;
}

// if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
// 	echo "<script>alert('You have no permission to access this module.')</script>";
// 	echo "<script>window.location='index.php'</script>";	
// 	exit;
// }

if (isset($_POST["courseCode"])) {
	$query = "SELECT * FROM course WHERE course_code = '" . mysqli_real_escape_string($con, $_POST["courseCode"]) . "'";
	$result = mysqli_query($con, $query);
	if ($result = mysqli_fetch_assoc($result)) {
		echo '<script>localStorage.setItem("courseCodeUsed",1)</script>';	// Username used flag.
		echo "<script>window.history.go(-1)</script>";
	} else {

		$course_permission = "";
		if (isset($_POST["permission"])) {
			foreach ($_POST["permission"] as $selected_permission) {
				$course_permission .= $selected_permission . ",";
			}
			if ($course_permission != "") {
				$course_permission = substr($staff_permission, 0, -1);
			}
		}

		if(isset($_POST["courseStatus"])){
			$courseStatus = 'Open';
		} else {
			$courseStatus = 'Closed';
		}

		// $query = "INSERT INTO course SET course_code = '" . mysqli_real_escape_string($con, $_POST["courseCode"]) . "', 
		// 			course_name = '" . mysqli_real_escape_string($con, $_POST["courseName"]) . "', 
		// 			course_start_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["courseStartDate"]) . "', '%d/%m/%Y'), 
		// 			course_end_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["courseEndDate"]) . "', '%d/%m/%Y'),
		// 			course_status = '" . $courseStatus . "', 
		// 			course_description = '" . mysqli_real_escape_string($con, $_POST["courseDescription"]) . "', 
		// 			course_id = '" . mysqli_real_escape_string($con, $_POST["courseId"]) . "'";

		$query = "INSERT INTO course SET course_code = '" . mysqli_real_escape_string($con, $_POST["courseCode"]) . "', 
					course_name = '" . mysqli_real_escape_string($con, $_POST["courseName"]) . "', 
					course_status = '" . $courseStatus . "', 
					course_description = '" . mysqli_real_escape_string($con, $_POST["courseDescription"]) . "', 
					course_id = '" . mysqli_real_escape_string($con, $_POST["courseId"]) . "'";

		$result = mysqli_query($con, $query);

		echo '<script>localStorage.setItem("Added",1)</script>';	// Successful added flag.
		echo "<script>window.location='course.php'</script>";
	}
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Course Registration</title>

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
							<h1>Course Registration</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="course.php">Course</a></li>
								<li class="breadcrumb-item active">Course Registration</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_course" id="add_course" class="col-lg-12" method="POST">
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
										<label for="inputName">Course Code</label>
										<input type="hidden" name="courseId" id="courseId" class="form-control" value="<?php echo $course_id; ?>">
										<input type="text" name="courseCode" id="courseCode" class="form-control">
									</div>
									<div class="form-group">
										<label for="inputName">Course Name</label>
										<input type="text" name="courseName" id="courseName" class="form-control">
									</div>
									
									<!-- <div class="form-group" style="margin-bottom:0px;">
										<label for="courseStartDate">Course Date</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="courseStartDate" id="courseStartDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="Start">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="courseEndDate" id="courseEndDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="End">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div> -->
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="courseStatus">Course Status &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="courseStatus" id="courseStatus" checked data-bootstrap-switch data-off-text="Closed" data-off-color="danger" data-on-text="Open" data-on-color="success" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="courseDescription">Description / Remarks</label>
										<textarea name="courseDescription" id="courseDescription" class="form-control" rows="4"></textarea>
									</div>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<div class="col-md-12">
							
							<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">Course Information</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									 <!-- /.row -->
									<div class="row">
										<div class="col-12">
											<div class="card">
											<div class="card-header bg-success">
												Module & Criteria Information
											</div>
											<!-- ./card-header -->
											<div class="card-body p-0">
												<div style='overflow:auto; width:100%;position:relative;'>
													<table id="moduleTable" class="table table-borderless">
														<tbody>
															<tr>
																<td style='padding:10px;' colspan="3"><button type="button" id="addModule" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> Add New Module</button></td>
															</tr>
															
														</tbody>
													</table>
												</div>
											</div>
											<!-- /.card-body -->
											</div>
											<!-- /.card -->
										</div>
										<div class="col-12">
										<div class="card">
										<div class="card-header bg-success">
											Group Information
										</div>
										<!-- ./card-header -->
										<div class="card-body p-0">
											<div style='overflow:auto; width:100%;position:relative;'>
												<table id="groupTable" class="table">
												<tbody>
												<tr>
													<td style='padding:10px;'><button type="button" id="addGroup" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> Add New Group</button></td>
												</tr>
													
												</tbody>
												</table>
											</div>
										</div>
										<!-- /.card-body -->
										
										</div>
										<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<button type="button" class="btn btn-danger btn-block" onclick="window.location='course.php'"><i class="fa fa-arrow-left"></i> BACK</button>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<button type="submit" id="submitCourseForm" class="btn btn-primary btn-block">Add Course <i class="fa fa-arrow-right"></i></button>
												</div>
											</div>
										</div>
									</div>
										<!-- /.card -->
									</div>
									</div>
									<!-- /.row -->
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
			if(localStorage.getItem("courseCodeUsed")=="1"){
				toastr.error('Course code has been registered. Kindly change the course code.');
				localStorage.clear();
			}
		};
		
		$(function() {

			$('input[type="text"], button').click(function(e) {
				$(this).closest('tr').attr('aria-expanded', 'false');
			});

			$(document).keypress(function (e) {
				if(e.which == 13 || e.which == 13) 
					return false;
			});

			$("#addModule").click(function(){
				var tbl = $("#moduleTable");
				$("<tr class='' style='color:red;'><td style='padding:10px 24px;'><div style='min-width: 200px;'><div class='form-group row' ><label><i class='expandable-table-caret fas fa-plus fa-fw'></i> New Module Number</label>"+
				"<div class='col-sm-6'><input type='text' name='moduleCode' class='form-control form-control-sm moduleCode'></div>"+
				"</div></div></td><td style='padding:10px;'><div style='min-width: 200px;'><div class='form-group row' style='padding:0px 20px'><label>Module Name</label><div class='col-sm-6'>"+
				"<input type='text' name='moduleName' class='form-control form-control-sm moduleName'></div></div></div>"+
				"</td><td style='text-align: right;background-color: transparent; padding:10px'><div style='width: 120px;';><button type='button' class='btn btn-sm moduleDelBtn red-icon'>"+
				"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm moduleSaveBtn green-icon'><i class='fas fa-check'></i></button>"+
				"</div></td></tr>").appendTo(tbl);
			});

			$(document.body).delegate(".addModuleCriteria", "click", function(){
				var tbl = $(this).closest('table');
				var moduleId = $(this).attr('data-moduleId');

				$("<tr class=''><td style='padding:10px 20px; color:red;'><div style='min-width: 200px;'><div class='form-group row' style='padding:0px 15px;'><label><i class='fas fa-plus'></i> New Main Criteria</label>"+
				"<div class='col-sm-6'><input type='text' name='moduleCriteriaName' class='form-control form-control-sm moduleCriteriaName'></div>"+
				"</div></td><td style='text-align: right;background-color: transparent; padding:10px;'><div style='width: 120px;text-align: right;'><button type='button' class='btn btn-sm moduleCriteriaDelBtn red-icon' data-moduleId='"+moduleId+"'>"+
				"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm moduleCriteriaSaveBtn green-icon' data-moduleId='"+moduleId+"'><i class='fas fa-check'></i></button>"+
				"</div></td></tr>").appendTo(tbl);
			});

			$(document.body).delegate(".addModuleSubCriteria", "click", function(){
				var tbl = $(this).closest('table');
				var moduleCriteriaId = $(this).attr('data-moduleCriteriaId');

				$("<tr><td style='padding:10px 15px; color:red;'><div class='form-group row' style='padding:0px 20px;'><label><i class='fas fa-plus'></i> New Sub Criteria</label>"+
				"<div class='col-sm-6'><input type='text' name='moduleSubCriteriaName' class='form-control form-control-sm moduleSubCriteriaName'></div>"+
				"</div></td><td style='text-align: right;background-color: transparent;width: 120px;'><div style='width: 120px;text-align: right;'><button type='button' class='btn btn-sm moduleSubCriteriaDelBtn red-icon' data-moduleCriteriaId='"+moduleCriteriaId+"'>"+
				"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm moduleSubCriteriaSaveBtn green-icon' data-moduleCriteriaId='"+moduleCriteriaId+"'><i class='fas fa-check'></i></button>"+
				"</div></td></tr>").appendTo(tbl);
			});

			$("#addGroup").click(function(){
				var tbl = $("#groupTable");

				$("<tr class=''style='color:red;'><td><div class='form-group row'><label><i class='fas fa-plus'></i> New Name</label><div class='col-sm-6'>"+
				"<input type='text' name='groupName' class='form-control form-control-sm'></div></div><div class='form-group row'><label><i class=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i> Start Date</label><div class='col-sm-6'>"+
				"<div class='input-group'><input type='date' name='groupStartDate' class='form-control form-control-sm' ><div class='input-group-prepend'></div></div></div></div></div></td>"+
				"<td><div class='form-group row'><label>Capacity</label><div class='col-sm-6'>"+
				"<input type='number' name='groupCapacity' class='form-control form-control-sm groupCapacity'></div></div><div class='form-group row'><label>End Date</label><div class='col-sm-6'>"+
				"<div class='input-group'><input type='date' name='groupEndDate' class='form-control form-control-sm' ><div class='input-group-prepend'></div></div></div></div>"+
				"</td><td style='text-align: right;background-color: transparent;'><div style='width: 120px;text-align: right;'><button type='button' class='btn btn-sm groupDelBtn red-icon'>"+
				"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm groupSaveBtn green-icon'><i class='fas fa-check'></i></button>"+
				"</div></td></tr>").appendTo(tbl);
			});

			$(document.body).delegate(".moduleSaveBtn", "click", function(){
				var tbl = $("#moduleTable");
				var moduleCode = $(this).closest("tr").find("input[name='moduleCode']").val();
				var moduleName = $(this).closest("tr").find("input[name='moduleName']").val();
				var courseId = $("#courseId").val();
				var moduleId = $(this).attr('data-moduleId');
				

				if(moduleId != undefined){
					var flag = "update";
					var url = "../pages/includes/update_course_module.php";
				} else {
					var flag = "add";
					var url = "../pages/includes/add_course_module.php";
					$(this).closest("tr").remove();
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						courseId: courseId,
						moduleId: moduleId,
						moduleCode: moduleCode,
						moduleName: moduleName
					}
				}).done(function(response){
					if(flag == "add"){
						$("<tr data-widget='expandable-table' aria-expanded='true' class=''><td>"+
						"<div style='min-width: 200px;'><div class='form-group row'>"+
						"<label><i class='expandable-table-caret fas fa-caret-right fa-fw'></i>Module Number</label><div class='col-sm-6'><input type='text' name='moduleCode' class='form-control form-control-sm' value='"+moduleCode+"'></div></div></div></td>"+
						"<td><div style='min-width: 200px;'><div class='form-group row'><label>Module Name</label><div class='col-sm-6'><input type='text' name='moduleName' class='form-control form-control-sm moduleName' value='"+moduleName+"'></div></div></div></td>"+
						"<td style='text-align: right;background-color: transparent; padding:10px;'><div style='width: 120px;text-align: right;'><button type='button' class='btn btn-sm moduleDelBtn red-icon' data-moduleId='"+response+"'><i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm moduleSaveBtn  green-icon' data-moduleId='"+response+"'><i class='fas fa-check'></i></button></div></td></tr>"+
						"<tr bgcolor='#e0e0e0' class='expandable-body'><td colspan='3'><div class='p-0'><table class='table'><tbody>"+
						"<tr><td style='padding:10px;'><button type='button' class='btn btn-sm addModuleCriteria' style='background-color:transparent;' data-moduleId='"+response+"'><i class='fas fa-plus'></i> Add New Criteria</button></td></tr></tbody></table></div></td></tr>"
						).appendTo(tbl);
					}
					toastr.remove();
					toastr.success('Module has been updated successfully.');
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
						url: "../pages/includes/delete_course_module.php",
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
					var url = "../pages/includes/update_course_module_criteria.php";
				} else {
					var flag = "add";
					var url = "../pages/includes/add_course_module_criteria.php";
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

						$("<tr data-widget='expandable-table' aria-expanded='true' class=''><td style='padding:10px;'><div style='min-width: 200px;'><div class='form-group row' style='padding:0px 20px;'>"+
						"<label><i class='expandable-table-caret fas fa-caret-right fa-fw'></i>Main Criteria</label><div class='col-sm-6'>"+
						"<input type='text' name='moduleCriteriaName' class='form-control form-control-sm moduleCriteriaName'  value='"+moduleCriteriaName+"'></div></div>"+
						"</div></td><td></td><td style='text-align: right;background-color: transparent; width:120px;'><div style='width: 120px;text-align: right;'><button type='button' class='btn btn-sm moduleCriteriaDelBtn red-icon' data-moduleCriteriaId='"+response+"'>"+
						"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm moduleCriteriaSaveBtn green-icon' data-moduleCriteriaId='"+response+"'>"+
						"<i class='fas fa-check'></i></button></div></td></tr><tr class='expandable-body'><td colspan='3'><div class='p-0'><table class='table'><tbody>"+
						"<td style='padding:0px 20px;' colspan='3'><button type='button' class='btn btn-sm addModuleSubCriteria' style='background-color:transparent' data-moduleCriteriaId='"+response+"'><i class='fas fa-plus'></i> Add New Sub-Criteria</button></td></tr>").appendTo(tbl);
					}
					toastr.remove();
					toastr.success('Module criteria has been updated successfully.');
					console.log(response);
				});
			});
				
			$(document.body).delegate(".moduleCriteriaDelBtn", "click", function(){
				var moduleCriteriaId = $(this).attr('data-moduleCriteriaId');
				$(this).closest("tr").remove();

				if(moduleCriteriaId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_course_module_criteria.php",
						data: {
							moduleCriteriaId: moduleCriteriaId
						}
					}).done(function(response){
						console.log(response);
					});
						
				}
			});

			$(document.body).delegate(".moduleSubCriteriaSaveBtn", "click", function(){
				var tbl = $(this).closest('table');
				var moduleSubCriteriaName = $(this).closest("tr").find("input[name='moduleSubCriteriaName']").val();
				var moduleCriteriaId = $(this).attr('data-moduleCriteriaId');
				var moduleSubCriteriaId = $(this).attr('data-moduleSubCriteriaId');
				
				if(moduleSubCriteriaId != undefined){
					var flag = "update";
					var url = "../pages/includes/update_course_module_sub_criteria.php";
				} else {
					var flag = "add";
					var url = "../pages/includes/add_course_module_sub_criteria.php";
					$(this).closest("tr").remove();
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						moduleCriteriaId: moduleCriteriaId,
						moduleSubCriteriaId: moduleSubCriteriaId,						
						moduleSubCriteriaName: moduleSubCriteriaName
					}
				}).done(function(response){
					if(flag == "add"){

						$("<tr class=''><td style='padding:5px 20px;'><div style='min-width: 200px;'><div class='form-group row' style='padding:0px 20px;'>"+
						"<label>Sub Criteria</label><div class='col-sm-6'>"+
						"<input type='text' name='moduleSubCriteriaName' class='form-control form-control-sm moduleSubCriteriaName'  value='"+moduleSubCriteriaName+"'></div></div>"+
						"</div></td><td style='text-align: right;background-color: transparent;'><div style='width: 120px;text-align: right;'><button type='button' class='btn btn-sm moduleSubCriteriaDelBtn red-icon' data-moduleSubCriteriaId='"+response+"'>"+
						"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm moduleSubCriteriaSaveBtn green-icon' data-moduleSubCriteriaId='"+response+"'>"+
						"<i class='fas fa-check'></i></button></div></td></tr>").appendTo(tbl);
					}
					toastr.remove();
					toastr.success('Module Sub-criteria has been updated successfully.');
					console.log(response);
				});
			});
				
			$(document.body).delegate(".moduleSubCriteriaDelBtn", "click", function(){
				var moduleSubCriteriaId = $(this).attr('data-moduleSubCriteriaId');
				$(this).closest("tr").remove();

				if(moduleSubCriteriaId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_course_module_sub_criteria.php",
						data: {
							moduleSubCriteriaId: moduleSubCriteriaId
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
				var groupStartDate = $(this).closest("tr").find("input[name='groupStartDate']").val();
				var groupEndDate = $(this).closest("tr").find("input[name='groupEndDate']").val();
				var groupId = $(this).attr('data-groupId');
				var courseId = $("#courseId").val();
				$(this).closest("tr").remove();

				if(groupId != undefined){
					var url = "../pages/includes/update_course_group.php";
				} else {
					var url = "../pages/includes/add_course_group.php";
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						courseId: courseId,
						groupName: groupName,
						groupStartDate: groupStartDate,
						groupEndDate: groupEndDate,
						groupCapacity: groupCapacity,
						groupId: groupId
					}
				}).done(function(response){
					
					$("<tr class=''><td><div class='form-group row'><label>Name</label><div class='col-sm-6'>"+
					"<input type='text' name='groupName' class='form-control form-control-sm groupName' value='"+groupName+"'></div></div><div class='form-group row'><label><i class=''></i> Start Date</label><div class='col-sm-6'>"+
					"<div class='input-group'><input type='date' name='groupStartDate' class='form-control form-control-sm dateMask' value='"+groupStartDate+"'><div class='input-group-prepend'></div></div></div></div></div></td>"+
					"<td><div class='form-group row'><label>Capacity</label><div class='col-sm-6'>"+
					"<input type='number' name='groupCapacity' class='form-control form-control-sm groupCapacity'  value='"+groupCapacity+"'></div></div><div class='form-group row'><label>End Date</label><div class='col-sm-6'>"+
					"<div class='input-group'><input type='date' name='groupEndDate' class='form-control form-control-sm dateMask' value='"+groupEndDate+"'><div class='input-group-prepend'></div></div></div></div></td>"+
					"<td style='text-align: right;background-color: transparent;'><div style='width: 120px;';><button type='button' class='btn btn-sm groupDelBtn red-icon' data-groupId='"+response+"'>"+
					"<i class='fa fa-trash-alt'></i></button><button type='button' class='btn btn-sm groupSaveBtn green-icon' data-groupId='"+response+"'><i class='fas fa-check'></i></button>"+
					"</div></td></tr>").appendTo(tbl);

					toastr.remove();
					toastr.success('Group has been updated successfully.');
					console.log(response);
				});
			});
				
			$(document.body).delegate(".groupDelBtn", "click", function(){
				var groupId = $(this).attr('data-groupId');
				$(this).closest("tr").remove();

				if(groupId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_course_group.php",
						data: {
							groupId: groupId
						}
					}).done(function(response){
						console.log(response);
					});
						
				}
			});    
    

			$(document).on("focus", ".dateMask", function() {
				$(this).inputmask('dd/mm/yyyy', {
					'placeholder': 'dd/mm/yyyy'
				})
			});

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

			jQuery.validator.addMethod("samePassword", function(value, element) {
                return this.optional(element) || $("#account_password").val() == $("#coursePasswordRetype").val();
            });

			$('#add_course').validate({
				rules: {
					courseCode: {
						required: true
					},
					courseName: {
						required: true
					},
					courseEmail: {
						required: true,
						email: true
					}
					// ,
					// courseStartDate: {
					// 	required: true
					// },
					// courseEndDate: {
					// 	required: true
					// }
				},
				messages: {
					courseCode: {
						required: "Please enter a course code"
					},
					courseName: {
						required: "Please enter a course name"
					}
					// ,
					// courseStartDate: {
					// 	required: "Please enter the start date"
					// },
					// courseEndDate: {
					// 	required: "Please enter the end date"
					// }
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