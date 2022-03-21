<?php
include("../docs/_includes/connection.php");
$page = "Batch";
$batch_id = uniqid();
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

if (isset($_POST["batchCode"])) {
	$query = "SELECT * FROM batch WHERE batch_code = '" . mysqli_real_escape_string($con, $_POST["batchCode"]) . "'";
	$result = mysqli_query($con, $query);
	if ($result = mysqli_fetch_assoc($result)) {
		echo '<script>localStorage.setItem("batchCodeUsed",1)</script>';	// Username used flag.
		echo "<script>window.history.go(-1)</script>";
	} else {

		$batch_permission = "";
		if (isset($_POST["permission"])) {
			foreach ($_POST["permission"] as $selected_permission) {
				$batch_permission .= $selected_permission . ",";
			}
			if ($batch_permission != "") {
				$batch_permission = substr($staff_permission, 0, -1);
			}
		}

		if(isset($_POST["batchStatus"])){
			$batchStatus = 'Open';
		} else {
			$batchStatus = 'Closed';
		}

		$query = "INSERT INTO batch SET batch_code = '" . mysqli_real_escape_string($con, $_POST["batchCode"]) . "', 
					batch_name = '" . mysqli_real_escape_string($con, $_POST["batchName"]) . "', 
					batch_start_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["batchStartDate"]) . "', '%d/%m/%Y'), 
					batch_end_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["batchEndDate"]) . "', '%d/%m/%Y'),
					batch_status = '" . $batchStatus . "', 
					batch_description = '" . mysqli_real_escape_string($con, $_POST["batchDescription"]) . "', 
					batch_id = '" . mysqli_real_escape_string($con, $_POST["batchId"]) . "'";
		$result = mysqli_query($con, $query);
		//echo $query;
		echo '<script>localStorage.setItem("Added",1)</script>';	// Successful added flag.
		echo "<script>window.location='batch.php'</script>";
	}
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Batch Registration</title>

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
							<h1>Batch Registration</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="batch.php">Batch</a></li>
								<li class="breadcrumb-item active">Batch Registration</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<section class="content">
				<form name="add_batch" id="add_batch" class="col-lg-12" method="POST">
					<div class="row">
						<div class="col-md-5">
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
										<input type="hidden" name="batchId" id="batchId" class="form-control" value="<?php echo $batch_id; ?>">
										<input type="text" name="batchCode" id="batchCode" class="form-control">
									</div>
									<div class="form-group">
										<label for="inputName">Batch Name</label>
										<input type="text" name="batchName" id="batchName" class="form-control">
									</div>
									
									<div class="form-group" style="margin-bottom:0px;">
										<label for="batchStartDate">Batch Date</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="batchStartDate" id="batchStartDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="Start">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="batchEndDate" id="batchEndDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="End">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="batchStatus">Batch Status &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="batchStatus" id="batchStatus" checked data-bootstrap-switch data-off-text="Closed" data-off-color="danger" data-on-text="Open" data-on-color="success" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="batchDescription">Description / Remarks</label>
										<textarea name="batchDescription" id="batchDescription" class="form-control" rows="4"></textarea>
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
							</div>
						</div>
						<div class="col-md-7">
							<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">Batch Information</h3>
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
													Student Information
												</div>
												<div class="card-body p-0">
													<table id="enrolledStudentTable" class="table table-borderless">
														<tbody>
														<tr>
															<td style='padding:10px;' colspan="3"><button type="button" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> No student list was generated</button></td>
														</tr>													
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<div class="row">
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="button" class="btn btn-danger btn-block" onclick="window.location='batch.php'"><i class="fa fa-arrow-left"></i> BACK</button>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="submit" id="submitBatchForm" class="btn btn-primary btn-block">Add Batch <i class="fa fa-check"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
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
			if(localStorage.getItem("batchCodeUsed")=="1"){
				toastr.error('Batch code has been registered. Kindly change the Batch code.');
				localStorage.clear();
			}
		};
		
		$(function() {

			$(document.body).delegate(".addModuleCriteria", "click", function(){
				var tbl = $(this).closest('table');
				var moduleId = $(this).attr('data-moduleId');

				$("<tr><td style='padding:10px;'><div class='form-group row' style='padding:0px 20px;'><label>Evaluation Criteria</label>"+
				"<div class='col-sm-6'><input type='text' name='moduleCriteriaName' class='form-control form-control-sm moduleCriteriaName'></div>"+
				"</div></td><td style='text-align: right;background-color: transparent; padding:10px;'><button class='btn btn-sm moduleCriteriaDelBtn red-icon' data-moduleId='"+moduleId+"'>"+
				"<i class='fa fa-trash-alt'></i> Remove</button><button class='btn btn-sm moduleCriteriaSaveBtn green-icon' data-moduleId='"+moduleId+"'><i class='fas fa-check'></i> Save</button>"+
				"</td></tr>").appendTo(tbl);
			});

			$("#generateStudentList").click(function(){
				var tbl = $("#enrolledStudentTable");
				var batchId = $("#batchId").val();

				if(!$("#add_batch").valid()){
					return;
				}

				getEnrolledStudentInfo(batchId);
			});

			function getEnrolledStudentInfo(batchId){	
				jQuery.ajax({
					type: "POST",
					url: "../pages/includes/get_batch_student.php",
					data: {
						batchId: batchId
					},
					datatype: "json",
					success: function(data, textStatus, xhr) {
						console.log(xhr.responseText);
						data = JSON.parse(xhr.responseText);

						var tbl = $("#enrolledStudentTable");
						var tblHeader = "";
						var appendRow = "";

					
						
						if(data.length > 0){
							if($("#enrolledCourseHeader").length == 0){
								tblHeader = "<tr id='enrolledCourseHeader'><td style='width:20px; text-align:center;'>No.</td><td style='width:150px;'>Student Name</td>"+
								"<td style='width:50px;'>Enroll Date</td><td style='width:50px; text-align:center;'>&nbsp;</td>";
							}

							appendRow += tblHeader;
							
							for (var i = 0, len = data.length; i < len; i++) {
								var id = data[i].studentId;
								var desc = data[i].studentName;
								var enrollDate = data[i].studentEnrollDate;
								appendRow += "<tr><td style='width:20px; text-align:center;'>"+(i+1)+"<input type='hidden' name='studentId' class='form-control form-control-sm studentId' value='"+id+"'></div></td>";
								appendRow += "<td><div>"+desc+"</div></td>";
								appendRow += "<td><div>"+enrollDate+"</div></td>";
								appendRow += "<td style='text-align: center;'>";
								appendRow += "<div data-toggle='buttons'><label class='btn'>";
								appendRow += "<input type='radio' class='studentBatch' name='options' style='display:none;' value='Enroll'> <i class='fas fa-check'></i></label></div></td>";			
								appendRow += "</tr>";
							}
						} else {
							appendRow += "<tr>";
							appendRow += '<td style="padding:10px;" colspan="3"><button type="button" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> No available students to assign</button></td>';
							appendRow += "</tr>";
						}
						tbl.find("tr").remove();
						$(appendRow).appendTo(tbl);
					}
				});
			}

			$(document).on('click','.studentBatch',function(){
				var valueCurrent = $(this).val(); 
				var studentId = $(this).closest("tr").find("input[name='studentId']").val();
				var batchId = $("#batchId").val();
				if ($(this).closest("label").hasClass("green-icon")) {
					$(this).closest("label").removeClass("green-icon");
					$(this).prop('checked', false);
				} else { 				
					$(this).closest("label").addClass("green-icon");
					$(this).prop('checked', true);
				}

				setStudentBatchInfo(batchId, studentId, valueCurrent);
			});

			function setStudentBatchInfo(batchId, studentId, valueCurrent){	
				jQuery.ajax({
					type: "POST",
					url: "../pages/includes/set_student_batch.php",
					data: {
						studentId: studentId,
						valueCurrent: valueCurrent,
						batchId: batchId
					}
				}).done(function(response){
					console.log(response);
				});
			}

			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

			jQuery.validator.addMethod("samePassword", function(value, element) {
                return this.optional(element) || $("#account_password").val() == $("#batchPasswordRetype").val();
            });

			$('#add_batch').validate({
				rules: {
					batchCode: {
						required: true
					},
					batchName: {
						required: true
					},
					batchEmail: {
						required: true,
						email: true
					},
					batchStartDate: {
						required: true
					},
					batchEndDate: {
						required: true
					}
				},
				messages: {
					batchCode: {
						required: "Please enter a Batch code"
					},
					batchName: {
						required: "Please enter a Batch name"
					},
					batchStartDate: {
						required: "Please enter the start date"
					},
					batchEndDate: {
						required: "Please enter the end date"
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