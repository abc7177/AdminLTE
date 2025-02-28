<?php
include("../docs/_includes/connection.php");

if($_GET["eva_type"] == "daily"){
	$evaType = "daily";
	$page = "Daily Evaluation";
} else {
	$evaType = "semester";
	$page = "Semester Evaluation";
}

if(isset($_GET["asStudent"])){
	$accountId = $_GET["asStudent"];
} else {
	$accountId = $_SESSION["itac_user_id"];
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
				account_id = '" . $_SESSION["itac_user_id"] . "' 
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
							<?php
                                if(isset($_GET["asStudent"])) {
									echo '<li class="breadcrumb-item"><a href="../index.php?asStudent='.$_GET["asStudent"].'">Home</a></li>';
								} if(isset($_GET["asTutor"])) { 
									echo '<li class="breadcrumb-item"><a href="../index.php?asTutor='.$_GET["asTutor"].'">Home</a></li>';
								} else {
									echo '<li class="breadcrumb-item"><a href="../index.php">Home</a></li>';
								}

								if(isset($_GET["viewType"]) == "readonly"){
									echo '<li class="breadcrumb-item">Evaluation</li>';
									echo '<li class="breadcrumb-item active">Daily Evaluation</li>';
								} if(isset($_GET["asTutor"])) { 
									echo '<li class="breadcrumb-item"><a href="evaluation.php?eva_type='.$evaType.'&asTutor='.$_GET["asTutor"].'">Evaluation</a></li>';
									echo '<li class="breadcrumb-item active">Daily Evaluation</li>';
								} else {
									echo '<li class="breadcrumb-item"><a href="evaluation.php?eva_type='.$evaType.'">Evaluation</a></li>';
									echo '<li class="breadcrumb-item active">Daily Evaluation</li>';
								}
							?>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="edit_evaluation" id="edit_evaluation" class="col-lg-12" method="POST">
					<div class="row">
						<div class="col-md-6">
							<div class="card card-primary collapsed-card">
								<div class="card-header">
									<h3 class="card-title">General</h3>
									<?php
									if(isset($_GET["viewType"])){
										echo '<input type="hidden" id="viewType" value="'.$_GET["viewType"].'">';
									} else {
										echo '<input type="hidden" id="viewType" value="">';
									}
									
									?>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-plus"></i>
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
									<!-- <div class="form-group">
										<label for="inputName">Group Name</label>
										<input type="hidden" name="evaluationCourseGroup" value="<?php //echo $row0["group_id"]; ?>">
										<select name='evaluationCourseGroup' id='evaluationCourseGroup' class='form-control form-control-sm' disabled>
										<?php
										// $query = "SELECT group_id, group_name FROM course_group WHERE course_id = '".$row0["course_id"]."'";
										// $result = mysqli_query($con, $query);
										// while($row = mysqli_fetch_assoc($result)){
										// 	echo '<option value="'.$row["group_id"].'" ';
										// 	if($row0["group_id"] == $row["group_id"])
										// 		echo "selected";
										// 	echo '>'.$row["group_name"].'</option>';
										// }		
										?>
										</select>
									</div> -->
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
										<?php
											$sessionType = $row0['evaluation_type'];
										?>
										<div>
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn bg-olive active">
													<input type="radio" name="evaluationSessionType" id="option_b1" value = 'Theory' 
													<?php if($sessionType == 'Theory') echo 'checked'; ?>
													> Theory
												</label>
												<label class="btn bg-olive">
													<input type="radio" name="evaluationSessionType" id="option_b2" value = 'Practical' 
													<?php if($sessionType == 'Practical') echo 'checked'; ?>
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
											<?php
												$query0 = "SELECT course_group.* FROM evaluation_sub LEFT JOIN course_group ON evaluation_sub.group_id = course_group.group_id 
														WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."' group by evaluation_sub.group_id";
												$result0 = mysqli_query($con, $query0);
												//echo $query0;
												while($row0 = mysqli_fetch_assoc($result0)){	
											?>
											<div class="card">
											<div class="card-header bg-success">
												Daily Evaluation Form: <?php echo $row0["group_name"];?>
											</div>
											<!-- ./card-header -->
											<div class="card-body p-0">
												<div style='overflow:auto; width:100%;position:relative;'>
													<table id="evaluationFormTable" class="table table-hover">
														<tbody>													
															<?php
															$counter = 0;
															$cmcId = "";
															if($sessionType == 'Theory'){
																$query1 = "SELECT distinct(account.account_id), account.account_name, cmc_id, 'Theory' as cmc_name, '-1' as course_module_id FROM evaluation_sub 
																LEFT JOIN account ON evaluation_sub.account_id = account.account_id 															
																WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."' AND evaluation_sub.group_id= '".$row0["group_id"]."' ";

																if(isset($_GET["viewType"]) == "readonly" || isset($_GET["asStudent"])) {
																	$query1 .= "AND account.account_id='".$accountId."' ";
																} 

																$query1 .= "group by account_id";
															} else {
																$query1 = "SELECT distinct(account.account_id), account.account_name, course_module_criteria.* FROM evaluation_sub 
																LEFT JOIN account ON evaluation_sub.account_id = account.account_id 
																LEFT JOIN course_module_criteria ON evaluation_sub.cmc_id = course_module_criteria.cmc_id 
																WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."' AND evaluation_sub.group_id= '".$row0["group_id"]."' ";

																if(isset($_GET["viewType"]) == "readonly" || isset($_GET["asStudent"])) {
																	$query1 .= "AND account.account_id='".$accountId."' ";
																} 

																$query1 .= "group by account_id";
															}
															
															$result1 = mysqli_query($con, $query1);	// Get Student Name
															//echo $query1;											

															echo "<tr id='evaluationFormHeader'>";
																echo "<td style='width:5px; text-align:center;'>No.</td><td style='width:100px;'>Criteria Name</td>";

																while($row1 = mysqli_fetch_assoc($result1)){																												
																	echo "<td style='width:100px; text-align:center;'>".$row1["account_name"]."</td>";														
																}
															echo "</tr>";
															
															if($sessionType == 'Theory'){
																$query2 = "SELECT '-1' as cms_id, 'Theory' as cms_name";
															} else {
																$query2 = "SELECT DISTINCT(course_module_sub_criteria.cms_id), course_module_sub_criteria.* FROM course_module_sub_criteria 
																LEFT JOIN evaluation_sub ON evaluation_sub.cmc_id = course_module_sub_criteria.cms_id
																WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
															}
															
															$result2 = mysqli_query($con, $query2);	// Get sub module name.
															//echo $query2;
															
															while($row2 = mysqli_fetch_assoc($result2)){		
																
																if($sessionType == 'Theory'){
																	$query3 = "SELECT evaluation_sub.*, evaluation_sub.cmc_id as cms_id, account.account_name FROM evaluation_sub 
																	LEFT JOIN account ON evaluation_sub.account_id = account.account_id 																	
																	WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."' AND evaluation_sub.group_id= '".$row0["group_id"]."' "; 
																	
																	if(isset($_GET["viewType"]) == "readonly" || isset($_GET["asStudent"])) {
																		$query3 .= "AND account.account_id='".$accountId."' ";
																	} 	
																	
																	$query3 .="AND cmc_id ='".$row2["cms_id"]."'";
																} else {
																	$query3 = "SELECT evaluation_sub.*, account.account_name, course_module_sub_criteria.* FROM evaluation_sub 
																	LEFT JOIN account ON evaluation_sub.account_id = account.account_id 
																	LEFT JOIN course_module_sub_criteria ON evaluation_sub.cmc_id = course_module_sub_criteria.cms_id 
																	WHERE evaluation_sub.evaluation_id = '".mysqli_real_escape_string($con,$_GET["id"])."' AND evaluation_sub.group_id= '".$row0["group_id"]."' ";

																	if(isset($_GET["viewType"]) == "readonly" || isset($_GET["asStudent"])) {
																		$query3 .= "AND account.account_id='".$accountId."' ";
																	} 

																	$query3 .="AND course_module_sub_criteria.cms_id ='".$row2["cms_id"]."'";
																}
																
																$result3 = mysqli_query($con, $query3);		// Get student mark.
																//echo $query3;	

																if($cmcId != $row2["cms_id"]){
																	$counter++;
																	$cmcId = $row2["cms_id"];
																	echo "<tr>";
																		echo "<td style='width:5px; text-align:center;'>".$counter."</td><td style='width:100px;'>".$row2["cms_name"]."</td>";
																}			
																while($row3 = mysqli_fetch_assoc($result3)){																								
																	echo "<td class='cmcColumn' style='width:1000px; text-align:center;'>
																			<div><div>
																			<input type='number' class='evaRate' name='evaRate' inputmode='decimal' min='0' max='100' step='0.5' value='".$row3["eva_rate"]."'>
																			<input type='hidden' name='evaSubId' value='".$row3["eva_sub_id"]."'>
																			<input type='hidden' name='evaImage' value='".$row3["eva_image_path"]."'>
																			<input type='hidden' name='cmcId' value='".$row3["cms_id"]."'>
																			<input type='hidden' name='studentId' value='".$row3["account_id"]."'>
																			<input type='hidden' name='eva_comment".$row3["account_id"]."&".$row3["cms_id"]."' value='".$row3["eva_comment"]."'>
																			</div></div>";

																			if($row3["eva_comment"] != "" || $row3["eva_image_path"] != "")
																				echo "<button type='button' class='btn btn-sm commentBtn' style='background-color:transparent;' ><i class='fas fa-plus-circle' style='color:red;'></i> Comment</button>";
																			else
																				echo "<button type='button' class='btn btn-sm commentBtn' style='background-color:transparent;' ><i class='fas fa-plus-circle' ></i> Comment</button>";
																			echo "</td>";
																}
																echo "</tr>";
															}
															?>																									
														</tbody>
													</table>
												</div>
											</div>
											<!-- /.card-body -->
											</div>
											<?php } ?>
											<!-- /.card -->
										</div>
										<div class="col-12">
											<div class="form-group">
												<div class="row">
													<div class="form-group col-lg-6">
														<div class="input-group">
															<?php if(isset($_GET["viewType"]) == "readonly" && !isset($_GET["asStudent"])){ ?>
																<button type="button" class="btn btn-danger btn-block" onclick="window.location='../index.php'"><i class="fa fa-arrow-left"></i> BACK</button>
															<?php } else if(isset($_GET["asStudent"])) { ?>
																<button type="button" class="btn btn-danger btn-block" onclick="window.location='../index.php?asStudent=<?php echo $_GET['asStudent'];?>'"><i class="fa fa-arrow-left"></i> BACK</button>
															<?php } else { ?>
																<button type="button" class="btn btn-danger btn-block" onclick="window.location='evaluation.php?<?php if(isset($_GET['asTutor'])) echo 'asTutor='.$_GET['asTutor'];?>&eva_type=daily'"><i class="fa fa-arrow-left"></i> BACK</button>
															<?php } ?>
														</div>
													</div>
													<div class="form-group col-lg-6">
														<div class="input-group">
															<button type="button" id="saveEvaluationForm" class="btn btn-primary btn-block" data-evatype='<?php echo $evaType;?>'
															<?php if(isset($_GET["viewType"]) == "readonly" || isset($_GET["asStudent"]) || isset($_GET["asTutor"])){ echo "disabled"; }?>
															>Save Evaluation Form <i class="fa fa-save"></i></button>
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
						<input type="hidden" name="evaSubId" id="evaSubId">
						<input type="hidden" name="studentId" id="studentId">
						<input type="hidden" name="evaImagePath" id="evaImagePath">
						<textarea name="evaComment" id="evaComment" style="min-width: 100%" rows="5"></textarea>
						<input type="file" class="form-control evaImage" name="evaImage" id="evaImage">
					</div>
					<div class="modal-footer justify-content-center">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-danger commentModalSave" 
						<?php if(isset($_GET["viewType"]) == "readonly"){ echo "disabled"; }?>
						>Save</button>
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
						tblHeader = "<tr id='enrolledCourseHeader'><td style='width:20px; text-align:center;'>No.</td><td >Student Name</td>"+
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
		
		$(function() {

			var currentUrl = window.location.href;
        	var url = new URL(currentUrl);

			if($('#viewType').val() == "readonly" || url.searchParams.has("asTutor") == true){
				$('input, textarea').attr('disabled', true);
			}

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
					var studentId = $(this).closest("td").find("input[name='studentId']").val();
					var evaComment = $(this).closest("td").find('input[name="eva_comment'+studentId+'&'+cmcId+'"]').val();
					var evaSubId = $(this).closest("td").find('input[name="evaSubId"]').val();
					var evaImage = $(this).closest("td").find('input[name="evaImage"]').val();

					$("#cmcId").val( cmcId );
					$("#evaSubId").val( evaSubId );
					$("#studentId").val( studentId );
					$("#evaComment").val( evaComment );
					$("#evaImagePath").val(evaImage);
					$("#evaImage").val("");
					$("#commentModal img, h5").remove();

					if(evaImage != ""){
						$("#commentModal .modal-body").prepend('<h5>Evaluation Image:</h5><img src="../evaPhoto/'+evaImage+'" style="padding: 10px; display: block; margin-left: auto; margin-right: auto; width: 50%;border-radius: 10%;"/>');
					} 

					$("#commentModal").modal();
			});

			$(document).on('change','.evaRate',function(){
				if(this.value>100){
					this.value='100';
				}else if(this.value<0){
					this.value='0';
				}
			});

			$('.evaImage').change(function() {

				if($(this).val() == ''){
					return;
				}

				var myFormData = new FormData();
				// myFormData.append('file', $('#cart_image')[0].files[0]);
				// myFormData.append('cartSubId', $('#cart_image').attr('data-field'));

				myFormData.append('evaImage', $(this)[0].files[0]);
				myFormData.append('evaSubId', $(this).parent().find('input[name="evaSubId"]').val());
				myFormData.append('evaType', "Daily");

				jQuery.ajax({
					url: 'includes/upload_eva_image.php',
					type: 'POST',
					processData: false, // important
					contentType: false, // important
					data: myFormData,
					success : function(data) {
						console.log(data);
						// location.reload();
						$("#commentModal img, h5").remove();	
						$("#commentModal .modal-body").prepend('<h5>Evaluation Image:</h5><img src="../evaPhoto/'+data+'" style="padding: 10px; display: block; margin-left: auto; margin-right: auto; width: 50%;border-radius: 10%;"/>');			
						$("#commentModal #evaImagePath").val(data);
						
					}
				});

			});

			$(".commentModalSave").click(function() {
				var evaComment = $("#evaComment").val();
				var evaImagePath = $("#evaImagePath").val();
				$('input[name="eva_comment'+$("#studentId").val()+'&'+$("#cmcId").val()+'"]').val( evaComment );
				$('input[name="eva_comment'+$("#studentId").val()+'&'+$("#cmcId").val()+'"]').parent().find('input[name="evaImage"]').val(evaImagePath)

				if(evaComment != "" || evaImagePath !="")
					$('input[name="eva_comment'+$("#studentId").val()+'&'+$("#cmcId").val()+'"]').parent().parent().parent().find(".fa-plus-circle").css("color","red");
				else
					$('input[name="eva_comment'+$("#studentId").val()+'&'+$("#cmcId").val()+'"]').parent().parent().parent().find(".fa-plus-circle").css("color","");

				$("#commentModal").modal('hide');
			});

			$("#saveEvaluationForm").click(function(){
				$(this).prop('disabled', true);
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
							async: false,
							url: "../pages/includes/update_evaluation_sub.php",
							data: {
								id: evaSubId,
								evaRate: evaRate,
								evaType: evaType,
								evaComment: evaComment
							},
							success: function(data, textStatus, xhr) {
								console.log(data);
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