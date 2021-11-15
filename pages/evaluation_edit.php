<?php
include("../docs/_includes/connection.php");

if($_GET["eva_type"] == "daily"){
	$evaType = "daily";
	$page = "Daily Evaluation";
} else {
	$evaType = "semester";
	$page = "Semester Evaluation";
}

if (!(isset($_SESSION["itac_user_id"]))) {
	echo "<script>window.location='login.php'</script>";
	exit;
}

// if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
// 	echo "<script>alert('You have no permission to access this module.')</script>";
// 	echo "<script>window.location='index.php'</script>";	
// 	exit;
// }

$evaluation_id = $_GET["id"];

$query0 = "SELECT * FROM evaluation WHERE evaluation_id = '".$evaluation_id."'";
$result0 = mysqli_query($con,$query0);
if(!($row0 = mysqli_fetch_assoc($result0))){
	echo "<script>alert('Evaluation record was not found.')</script>";
	echo "<script>window.location= 'evaluation.php?eva_type=".$evaType."'</script>";
	exit;
}

if (isset($_POST["evaluationId"])) {

	$query = "UPDATE evaluation SET
				session_id = '" . mysqli_real_escape_string($con, $_POST["evaluationSessionId"]) . "', 
				evaluation_type = '" . mysqli_real_escape_string($con, $_POST["evaluationSessionType"]) . "', 
				evaluation_date = STR_TO_DATE('" . mysqli_real_escape_string($con, $_POST["evaluationDate"]) . "', '%d/%m/%Y'), 
				account_id = '" . $_SESSION["itac_user_id"] . "', 
				course_id = '" . mysqli_real_escape_string($con, $_POST["evaluationCourseId"]) . "', 
				course_module_id = '" . mysqli_real_escape_string($con, $_POST["evaluationCourseModule"]) . "', 
				group_id = '" . mysqli_real_escape_string($con, $_POST["evaluationCourseGroup"]) . "'
				WHERE evaluation_id = '" . $evaluation_id . "'";
	$result = mysqli_query($con, $query);
	//echo $query;

	echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful added flag.
	echo "<script>window.location='evaluation_edit.php?id=".$_GET["id"]."&eva_type=".$evaType."'</script>";
	
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
							<h1>Daily Evaluation</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="evaluation.php?eva_type=<?php echo $evaType;?>">Evaluation</a></li>
								<li class="breadcrumb-item active">Daily Evaluation</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="edit_evaluation" id="edit_evaluation" class="col-lg-12" method="POST">
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
										<input type="hidden" name="evaluationId" id="evaluationId" class="form-control" value="<?php echo $evaluation_id; ?>">
										<label for="evaluationDate">Date</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="evaluationDate" id="evaluationDate" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask placeholder="Date" value="<?php echo date('d/m/Y', strtotime($row0["evaluation_date"])) ?>" readonly>
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Assign Session</label>
										<input type="hidden" name="evaluationSessionId" value="<?php echo $row0["session_id"]; ?>">
										<select class="form-control" name="evaluationSessionId" id="evaluationSessionId" disabled>
											<?php
												echo '<option value="" selected disabled>-----  Session -----</option>';
												$query = "SELECT * FROM session WHERE session_type = 'Assignment'";
												$result = mysqli_query($con, $query);
												while($row = mysqli_fetch_assoc($result)){
													echo '<option value="'.$row["session_id"].'" ';
													if($row0["session_id"] == $row["session_id"])
														echo 'selected ';
													echo '>'.$row["session_name"].'</option>';
												}
											?>
										</select>		
									</div>
									<div class="form-group">
										<div class="form-group">
											<label for="inputName">Batch Code</label>
											<select class="form-control" name="evaluationBatchId" id="evaluationBatchId" readonly>
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
										<label for="inputName">Course Code</label>										
										<input type="hidden" name="evaluationCourseId" value="<?php echo $row0["course_id"]; ?>">
										<select class="form-control" name="evaluationCourseId" id="evaluationCourseId" disabled>
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
									<div class="form-group">
										<label for="inputName">Group Name</label>
										<input type="hidden" name="evaluationCourseGroup" value="<?php echo $row0["group_id"]; ?>">
										<select name='evaluationCourseGroup' id='evaluationCourseGroup' class='form-control form-control-sm' disabled>
										<?php
										$query = "SELECT group_id, group_name FROM course_group WHERE course_id = '".$row0["course_id"]."'";
										$result = mysqli_query($con, $query);
										while($row = mysqli_fetch_assoc($result)){
											echo '<option value="'.$row["group_id"].'" ';
											if($row0["group_id"] == $row["group_id"])
												echo "selected";
											echo '>'.$row["group_name"].'</option>';
										}		
										?>
										</select>
									</div>
									<div class="form-group">
										<label for="inputName">Module</label>
										<input type="hidden" name="evaluationCourseModule" value="<?php echo $row0["course_module_id"]; ?>">
										<select name='evaluationCourseModule' id='evaluationCourseModule' class='form-control form-control-sm' disabled>
										<?php
										$query = "SELECT course_module_id, course_module_code, course_module_name FROM course_module WHERE course_id = '".$row0["course_id"]."'";
										$result = mysqli_query($con, $query);
										while($row = mysqli_fetch_assoc($result)){
											echo '<option value="'.$row["course_module_id"].'" ';
											if($row0["course_module_id"] == $row["course_module_id"])
												echo "selected";
											echo '>'.$row["course_module_code"].' - '.$row["course_module_name"].'</option>';
										}		
										?>
										</select>
									</div>
									<div class="form-group">
										<label for="inputName">Session Type</label>
										<div>
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn bg-olive active">
													<input type="radio" name="evaluationSessionType" id="option_b1" value = 'Theory' 
													<?php if($row0['evaluation_type'] == 'Theory') echo 'checked'; ?>
													> Theory
												</label>
												<label class="btn bg-olive">
													<input type="radio" name="evaluationSessionType" id="option_b2" value = 'Practical' 
													<?php if($row0['evaluation_type'] == 'Practical') echo 'checked'; ?>
													> Practical
												</label>
											</div>
										</div>
									</div>
									
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
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
												Daily Evaluation Form
											</div>
											<!-- ./card-header -->
											<div class="card-body p-0">
												<table id="evaluationFormTable" class="table table-hover">
												<tbody>													
													<?php
													$counter = 0;
													$studentId = "";
													$query1 = "SELECT DISTINCT(course_module_criteria.cmc_id), course_module_criteria.* FROM course_module_criteria 
													LEFT JOIN evaluation_sub ON evaluation_sub.cmc_id = course_module_criteria.cmc_id
													WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
													$result1 = mysqli_query($con, $query1);

													$query2 = "SELECT evaluation_sub.*, account.account_name, course_module_criteria.* FROM evaluation_sub 
													LEFT JOIN account ON evaluation_sub.account_id = account.account_id 
													LEFT JOIN course_module_criteria ON evaluation_sub.cmc_id = course_module_criteria.cmc_id 
													WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
													$result2 = mysqli_query($con, $query2);

													echo "<tr id='evaluationFormHeader'>";
														echo "<td style='width:5px; text-align:center;'>No.</td><td style='width:80px;'>Student Name</td>";

														while($row1 = mysqli_fetch_assoc($result1)){																												
															echo "<td style='width:100px; text-align:center;'>".$row1["cmc_name"]."</td>";														
														}
													echo "</tr>";
													
													while($row2 = mysqli_fetch_assoc($result2)){														

														if($studentId != $row2["account_id"]){
															$counter++;
															$studentId = $row2["account_id"];
															echo "<tr>";
																echo "<td style='width:5px; text-align:center;'>".$counter."<input type='hidden' name='studentId' value='".$row2["account_id"]."'></td><td style='width:80px;'>".$row2["account_name"]."</td>";
														}																											
																echo "<td class='cmcColumn' style='width:100px; text-align:center;'>
																		<div><div>
																		<input type='number' name='evaRate' min='0' max='5' step='0.5' value='".$row2["eva_rate"]."'>
																		<input type='hidden' name='evaSubId' value='".$row2["eva_sub_id"]."'>
																		<input type='hidden' name='cmcId' value='".$row2["cmc_id"]."'>
																		<input type='hidden' name='eva_comment".$row2["account_id"]."&".$row2["cmc_id"]."' value='".$row2["eva_comment"]."'>
																		</div></div>
																		<button type='button' class='btn btn-sm commentBtn' style='background-color:transparent' ><i class='fas fa-plus'></i> Comment</button>
																		</td>";
														//echo "</tr>";
													}
													?>																									
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
															<button type="button" class="btn btn-danger btn-block" onclick="window.location='evaluation.php?eva_type=daily'"><i class="fa fa-arrow-left"></i> BACK</button>
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

			if(localStorage.getItem("Updated")=="1"){
				toastr.success('Evaluation has been updated successfully.');
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
								appendRow += "<td class='cmcColumn' style='width:100px; text-align:center;'><div><div><input name='evaRate' class='rating rating-loading' data-min='0' data-max='5' data-step='0.5' value=''><input type='hidden' name='cmcId' value='"+courseModuleCriteria[i].id+"'></div></div></td>";
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
			$(':radio:not(:checked)').attr('disabled', true);

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
			});

			$(document).on('click', ".commentBtn", function() {	// Use delegate function to register those dynamically added classes.
					var cmcId = $(this).closest("td").find("input[name='cmcId']").val();
					var studentId = $(this).closest("tr").find("input[name='studentId']").val();
					var evaComment = $(this).closest("tr").find('input[name="eva_comment'+studentId+'&'+cmcId+'"]').val();

					$("#cmcId").val( cmcId );
					$("#studentId").val( studentId );
					$("#evaComment").val( evaComment );
					$("#commentModal").modal();
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
						var evaSubId = $(this).find('input[name="evaSubId"]').val();
						var evaRate = $(this).find('input[name="evaRate"]').val();
						var evaComment = $(this).find('input[name^="eva_comment"]').val()

						jQuery.ajax({
							type: "POST",
							url: "../pages/includes/update_evaluation_sub.php",
							data: {
								id: evaSubId,
								evaRate: evaRate,
								evaType: evaType,
								evaComment: evaComment
							},
							success: function(data, textStatus, xhr) {
							}
						});
					});
				});
				$("#edit_evaluation")[0].submit();
			});

			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

		});
	</script>
</body>

</html>