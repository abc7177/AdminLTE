<?php
include("../docs/_includes/connection.php");

if($_GET["eva_type"] == "daily"){
    $evaType = "daily";
	$page = "Daily Evaluation";
} else {
    $evaType = "semester";
	$page = "Semester Evaluation";
}

$evaluation_id = uniqid();

if (!(isset($_SESSION["itac_user_id"]))) {
	echo "<script>window.location='login.php'</script>";
	exit;
}

// if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
// 	echo "<script>alert('You have no permission to access this module.')</script>";
// 	echo "<script>window.location='index.php'</script>";	
// 	exit;
// }

if (isset($_POST["evaluationId"])) {

	$query = "INSERT INTO evaluation_sem SET evaluation_id = '" . mysqli_real_escape_string($con, $_POST["evaluationId"]) . "', 
				session_id = '" . mysqli_real_escape_string($con, $_POST["evaluationSessionId"]) . "', 
				evaluation_type = '" . mysqli_real_escape_string($con, $_POST["evaluationSessionType"]) . "', 
				evaluation_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["evaluationDate"]) . "', '%d/%m/%Y'), 
				account_id = '" . $_SESSION["itac_user_id"] . "', 
				batch_id = '" . mysqli_real_escape_string($con, $_POST["evaluationBatchId"]) . "', 
				course_id = '" . mysqli_real_escape_string($con, $_POST["evaluationCourseId"]) . "', 
				course_module_id = '" . mysqli_real_escape_string($con, $_POST["evaluationCourseModule"]) . "', 
				group_id = '" . mysqli_real_escape_string($con, $_POST["evaluationCourseGroup"]) . "'";
	$result = mysqli_query($con, $query);

	echo '<script>localStorage.setItem("Added",1)</script>';	// Successful added flag.
	echo "<script>window.location='evaluation.php?eva_type=".$evaType."'</script>";
	
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Evaluation</title>

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
	<!-- Customize style -->
	<link rel="stylesheet" href="../dist/css/rating-stars.css">
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
							<h1>Semester Evaluation</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="evaluation.php?eva_type=<?php echo $evaType;?>">Evaluation</a></li>
								<li class="breadcrumb-item active">Semester Evaluation</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_evaluation" id="add_evaluation" class="col-lg-12" method="POST">
					<div class="row">
						<div class="col-md-6">
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
									<div class="form-group" style="margin-bottom:0px;">
										<label for="evaluationDate">Date</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="evaluationDate" id="evaluationDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="Date" value="<?php echo date('d/m/Y', strtotime("now")) ?>">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Assign Session</label>
										<select class="form-control" name="evaluationSessionId" id="evaluationSessionId">
											<?php
												echo '<option value="" selected disabled>-----  Session -----</option>';
												$query = "SELECT * FROM session WHERE session_type = 'Exam'";
												$result = mysqli_query($con, $query);
												while($row = mysqli_fetch_assoc($result)){
													echo '<option value="'.$row["session_id"].'" ';
													echo '>'.$row["session_name"].'</option>';
												}
											?>
										</select>		
									</div>
									<div class="form-group">
										<label for="inputName">Batch Code</label>
										<select class="form-control" name="evaluationBatchId" id="evaluationBatchId">
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
										<input type="hidden" name="evaluationId" id="evaluationId" class="form-control" value="<?php echo $evaluation_id; ?>">
										<select class="form-control" name="evaluationCourseId" id="evaluationCourseId">
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
										<select name='evaluationCourseGroup' id='evaluationCourseGroup' class='form-control form-control-sm' disabled></select>
									</div>
									<div class="form-group">
										<label for="inputName">Module</label>
										<select name='evaluationCourseModule' id='evaluationCourseModule' class='form-control form-control-sm' disabled></select>
									</div>
									<div class="form-group">
										<label for="inputName">Session Type</label>
										<div>
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn bg-olive active">
													<input type="radio" name="evaluationSessionType" id="option_b1" value="Theory"> Theory
												</label>
												<label class="btn bg-olive">
													<input type="radio" name="evaluationSessionType" id="option_b2" value="Practical"> Practical
												</label>
											</div>
										</div>
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
						<div class="col-md-6">
							<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">Information</h3>

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
													<div class="form-group col-lg-12">
														<div class="input-group">
															<button type="button" id="generateEvaluationForm" class="btn btn-primary btn-block">Generate Semester Evaluation Form <i class="fa fa-check"></i></button>
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
						<div class="col-md-12">
							<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">Information</h3>

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
												Semester Evaluation Form
											</div>
											<!-- ./card-header -->
											<div class="card-body p-0">
												<table id="evaluationFormTable" class="table table-hover">
												<tbody>
													<tr>
														<td style='padding:10px;' colspan="3"><button type="button" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> No evaluation form was generated</button></td>
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
															<button type="button" class="btn btn-danger btn-block" onclick="window.location='evaluation.php?eva_type=<?php echo $evaType;?>'"><i class="fa fa-arrow-left"></i> DISCARD</button>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="button" id="saveEvaluationForm" class="btn btn-primary btn-block" data-evatype='<?php echo $evaType;?>'>Save Evaluation Form <i class="fa fa-save"></i></button>
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

		<div class="modal fade" id="commentModal">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header bg-info">
						<h4 class="modal-title">Criteria Comment</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					
					<div class="modal-body">
						<input type="hidden" name="cmcId" id="cmcId">
						<input type="hidden" name="studentId" id="studentId">
						<textarea name="evaComment" id="evaComment"></textarea>
					</div>
					<div class="modal-footer justify-content-center">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-danger commentModalSave">Save</button>
					</div>
					
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
      	<!-- /.modal -->
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
	<!-- Toastr -->
	<script src="../plugins/rating-stars.js"></script>

	<script>

		window.onpageshow = function(event) {	// The document.ready function will not be called when running window.history, so use this instead.
			if(localStorage.getItem("evaluationCodeUsed")=="1"){
				toastr.error('evaluation code has been registered. Kindly change the evaluation code.');
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

					var select = thisObj.parent().parent().find("select[name='evaluationCourseGroup']");
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

		function getCourseModuleInfo(thisObj){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_course_module.php",
				data: {id: thisObj.val()},
				datatype: "json",
				success: function(data, textStatus, xhr) {
					console.log(xhr.responseText);
					data = JSON.parse(xhr.responseText);

					var select = thisObj.parent().parent().find("select[name='evaluationCourseModule']");
					var options = select.prop('options');
					$('option', select).remove();
					select.prop('disabled', false);
					options[options.length] = new Option("----------", "");

					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].id;
						var desc = data[i].code +" - "+ data[i].name;
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
						"<td style='width:100px; text-align:center;'>Hands On</td>"+
						"<td style='width:100px; text-align:center;'>Model</td></tr>";
					}

					appendRow += tblHeader;
					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].studentId;
						var desc = data[i].studentName;
						appendRow += "<tr><td style='width:20px; text-align:center;'>"+(i+1)+"<input type='hidden' name='studentId' class='form-control form-control-sm studentId' value='"+id+"'></div></td>";
						appendRow += "<td><div class='col-sm-6'>"+desc+"</div></td>";
						appendRow += "<td style='width:100px; text-align:center;'>";
						appendRow += "<div data-toggle='buttons'><label class='btn'>";
						appendRow += "<input type='radio' class='studentEvaluation' name='options_"+i+"' style='display:none;' value='Hands On'> <i class='fas fa-check'></i></label></div></td>";
						appendRow += "<td style='width:100px; text-align:center;'><div data-toggle='buttons'><label class='btn'>";
						appendRow += "<input type='radio' class='studentEvaluation' name='options_"+i+"' style='display:none;' value='Modal'> <i class='fas fa-check'></i></label></div></td>";
                		//appendRow += "</div></td>";
						appendRow += "</tr>";
					}
					tbl.find("tr").remove();
					$(appendRow).appendTo(tbl);
				}
			});
		}

		function getEvaluationForm(){	

			var courseModuleCriteria;
			var studentInfo;

			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_course_module_criteria.php",
				data: {id: $("#evaluationCourseModule").val()},
				datatype: "json",
				async: false,
				success: function(data, textStatus, xhr) {
					data = JSON.parse(xhr.responseText);
					courseModuleCriteria = JSON.parse(xhr.responseText);
				}
			});

			var tbl = $("#evaluationFormTable");
			var counter = 0;
			$(tbl).find('tr').remove();

			$('#enrolledStudentTable tr').each(function () {
				var studentId = $(this).find('input[name="studentId"]').val();
				var tblHeader = "";
				var appendRow = "";

				if($(this).find('td div input[type="radio"]:checked').val() == 'Hands On'){
					counter++;
					
					$(this).find('td div input[type="radio"]:checked').each(function () {
						
						jQuery.ajax({
							type: "POST",
							url: "../pages/includes/get_student_info.php",
							data: {id: studentId},
							datatype: "json",
							async: false,
							success: function(data, textStatus, xhr) {
								data = JSON.parse(xhr.responseText);
								studentInfo = JSON.parse(xhr.responseText);
							}
						});

						if($("#evaluationFormHeader").length == 0){
							
							tblHeader = "<tr id='evaluationFormHeader'><td style='width:5px; text-align:center;'>No.</td><td style='width:80px;'>Student Name</td>";

							for (var i = 0, len = courseModuleCriteria.length; i < len; i++) {
								var desc = courseModuleCriteria[i].name;
								tblHeader += "<td style='width:100px; text-align:center;'>"+desc+"</td>";
								
							}
							tblHeader += "</tr>";
							appendRow += tblHeader;
							$(appendRow).appendTo(tbl);
						}
						
						appendRow = "<tr><td style='width:5px; text-align:center;'>"+counter+"<input type='hidden' name='studentId' value='"+studentId+"'></td>";

						for (var i = 0, len = studentInfo.length; i < len; i++) {
							var desc = studentInfo[i].studentName;
							appendRow += "<td style='width:80px;'>"+desc+"</td>";
							for (var i = 0, len = courseModuleCriteria.length; i < len; i++) {
								appendRow += "<td class='cmcColumn' style='width:100px; text-align:center;'><div><div>"+
											"<input type='number' name='evaRate' min='0' max='5' step='0.5' value='0'>"+
											"<input type='hidden' name='cmcId' value='"+courseModuleCriteria[i].id+"'><input type='hidden' name='eva_comment"+studentId+"&"+courseModuleCriteria[i].id+"'></div></div>"+
											"<button type='button' class='btn btn-sm commentBtn' style='background-color:transparent' ><i class='fas fa-plus'></i> Comment</button></td>";
							}
						}
						appendRow += "</tr>";
						$(appendRow).appendTo(tbl);
						
					});
				}
			});

			$.getScript( "../plugins/rating-stars.js", function() {
				console.log( "Successfully Loaded." );
			});
		}
		
		$(function() {

			$(document).on('change','#evaluationCourseId',function(){
				valueCurrent = $(this).val();

				if(valueCurrent != 0){
					getCourseGroupInfo($(this));
					getCourseModuleInfo($(this));
				} else {
					$(this).parent().parent().find("select[name='evaluationCourseGroup']").val("0");
					$(this).parent().parent().find("select[name='evaluationCourseGroup']").prop('disabled', true);
				}
			});

			$(document).on('change','.studentEvaluation',function(){
				var valueCurrent = $(this).val();
				var studentId = $(this).closest("tr").find("input[name='studentId']").val();
				var evaluationId = $("#evaluationId").val();
				$(this).closest("tr").find("label").removeClass("green-icon");
				$(this).closest("label").addClass("green-icon");

				//setStudentEvaluationInfo(evaluationId, studentId, valueCurrent);
			});

			$(document).on('click', ".commentBtn", function() {	// Use delegate function to register those dynamically added classes.
					var cmcId = $(this).closest("td").find("input[name='cmcId']").val();
					var studentId = $(this).closest("tr").find("input[name='studentId']").val();
					var evaComment = $('input[name="eva_comment'+studentId+'&'+cmcId+'"]').val();

					if(evaComment == ""){
						$("#evaComment").val( "" );
					} else {
						$("#evaComment").val( evaComment );
					}
					$("#cmcId").val( cmcId );
					$("#studentId").val( studentId );
					$("#commentModal").modal();
			});

			$("#add_evaluation").submit(function(){
				if($(this).valid()) {
					$("#evaluationBatchId").prop('disabled', false);
					$("#evaluationCourseId").prop('disabled', false);
					$("#evaluationCourseGroup").prop('disabled', false);
					$("#evaluationSessionId").prop('disabled', false);
				}
			});

			$(".commentModalSave").click(function() {
				var evaComment = $("#evaComment").val();
				$('input[name="eva_comment'+$("#studentId").val()+'&'+$("#cmcId").val()+'"]').val( evaComment );
				$("#commentModal").modal('hide');
			});

			$("#saveEvaluationForm").click(function(){
                var evaType = $(this).data("evatype");

				$('#evaluationFormTable tr').each(function () {
					var studentId = $(this).find('input[name="studentId"]').val();                    

					if(studentId == undefined){
						return true;	// Skip those empty rows.
					}
					
					$(this).find('td.cmcColumn').each (function() {
						var cmcId = $(this).find('input[name="cmcId"]').val();
						var evaRate = $(this).find('input[name="evaRate"]').val();
						var evaComment = $(this).find('input[name^="eva_comment"]').val()

						jQuery.ajax({
							type: "POST",
							url: "../pages/includes/add_evaluation_sub.php",
							data: {
								id: $("#evaluationId").val(),
								studentId: studentId,
                                evaType: evaType,
								cmcId: cmcId,
								evaRate: evaRate,
								evaComment: evaComment
							},
							success: function(data, textStatus, xhr) {
							}
						});
					});
				});
				$("#add_evaluation").submit();
			});


			$("#generateStudentList").click(function(){
				var batchId = $("#evaluationBatchId").val();
				var courseId = $("#evaluationCourseId").val();
				var courseGroupId = $("#evaluationCourseGroup").val();

				if(!$("#add_evaluation").valid()){
					return;
				}
				
				$("#evaluationBatchId").prop('disabled', true);
				$("#evaluationCourseId").prop('disabled', true);
				$("#evaluationCourseGroup").prop('disabled', true);
				$("#generateStudentList").prop('disabled', true);
				$("#evaluationSessionId").prop('disabled', true);

				getEnrolledStudentInfo(batchId, courseId, courseGroupId);
			});

			$("#generateEvaluationForm").click(function(){
				getEvaluationForm();
			});

			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

			$('#add_evaluation').validate({
				rules: {
					evaluationSessionId: {
						required: true
					},
					evaluationBatchId: {
						required: true
					},
					evaluationCourseId: {
						required: true
					},
					evaluationCourseGroup: {
						required: true
					},
					evaluationCourseModule: {
						required: true
					},
					evaluationSessionType: {
						required: true
					},
					evaluationDate: {
						required: true
					}
				},
				messages: {
					evaluationSessionId: {
						required: "Please select a session"
					},
					evaluationBatchId: {
						required: "Please select a batch"
					},
					evaluationCourseId: {
						required: "Please select a course"
					},
					evaluationCourseGroup: {
						required: "Please select a course group"
					},
					evaluationCourseModule: {
						required: "Please select a course module"
					},
					evaluationSessionType: {
						required: "Please select a session type"
					},
					evaluationDate: {
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