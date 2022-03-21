<?php
include("../docs/_includes/connection.php");
$page = "Student";

if (!(isset($_SESSION["itac_user_id"]))) {
	echo "<script>window.location='login.php'</script>";
	exit;
}

// if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
// 	echo "<script>alert('You have no permission to access this module.')</script>";
// 	echo "<script>window.location='index.php'</script>";	
// 	exit;
// }

if (isset($_POST["studentName"])) {
	$query = "SELECT * FROM account WHERE account_username = '" . mysqli_real_escape_string($con, $_POST["studentUsername"]) . "'";
	$result = mysqli_query($con, $query);
	if ($result = mysqli_fetch_assoc($result)) {
		echo '<script>localStorage.setItem("usernameUsed",1)</script>';	// Username used flag.
		echo "<script>window.history.go(-1)</script>";
	} else {

		$student_permission = "";
		if (isset($_POST["permission"])) {
			foreach ($_POST["permission"] as $selected_permission) {
				$student_permission .= $selected_permission . ",";
			}
			if ($student_permission != "") {
				$student_permission = substr($staff_permission, 0, -1);
			}
		}

		if(isset($_POST["studentStatus"])){
			$studentStatus = 'Active';
		} else {
			$studentStatus = 'Inactive';
		}

		if(isset($_POST["studentFinancing"])){
			$studentFinancing = 'Loan';
		} else {
			$studentFinancing = 'Cash';
		}

		if(isset($_POST["studentHostel"])){
			$studentHostel = 'Yes';
		} else {
			$studentHostel = 'No';
		}

		$hashedPassword = password_hash(mysqli_real_escape_string($con,$_POST["account_password"]), PASSWORD_DEFAULT);
		$query = "INSERT INTO account SET account_username = '" . mysqli_real_escape_string($con, $_POST["studentUsername"]) . "', 
					account_name = '" . mysqli_real_escape_string($con, $_POST["studentName"]) . "', 
					account_ic = '" . mysqli_real_escape_string($con, $_POST["studentICNumber"]) . "', 
					account_dob = '" . mysqli_real_escape_string($con, $_POST["studentDOB"]) . "', 
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
					account_postcode = '" . mysqli_real_escape_string($con, $_POST["studentPostcode"]) . "', ";

		if(isset($_POST["studentState"]))	
			$query .= "account_state = '" . mysqli_real_escape_string($con, $_POST["studentState"]) . "', ";
			
		if(isset($_POST["studentCountry"]))		
			$query .= "account_country = '" . mysqli_real_escape_string($con, $_POST["studentCountry"]) . "', ";
			$query .= "account_enroll_date = '" . mysqli_real_escape_string($con, $_POST["studentEnrollDate"]) . "', 
					account_status = '" . $studentStatus . "', 
					account_hostel = '" . $studentHostel . "', 
					account_financing = '" . $studentFinancing . "', 
					account_remark = '" . mysqli_real_escape_string($con, $_POST["studentDescription"]) . "', 
					account_password = '" . $hashedPassword . "',
					account_level_id = '3',
					account_type = 'STUDENT'";
		$result = mysqli_query($con, $query);
		// echo $query;
		$last_id = mysqli_insert_id($con);

		$query = "INSERT INTO batch_sub SET 
					batch_id = '" . mysqli_real_escape_string($con, $_POST["studentBatchNumber"]) . "', 					
					account_id = '".$last_id."'";
		// echo $query;
		$result = mysqli_query($con, $query);

		echo '<script>localStorage.setItem("Added",1)</script>';	// Successful added flag.
		echo "<script>window.location='student_edit.php?id=".$last_id."'</script>";
	}
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Student Information</title>

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
							<h1>Student Information</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="student.php">Student</a></li>
								<li class="breadcrumb-item active">Student Registration</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_student" id="add_student" class="col-lg-12" method="POST">
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
									<div class="form-group">
										<label for="inputName">Name</label>
										<input type="text" name="studentName" id="studentName" class="form-control">
									</div>
									<div class="form-group">
										<label for="inputName">I/C Number</label>
										<input type="text" name="studentICNumber" id="studentICNumber" class="form-control">
									</div>
									<div class="form-group">
										<label for="inputName">Date Of Birth</label>
										<div class="input-group">
											<input type="date" name="studentDOB" id="studentDOB" class="form-control" >
											<div class="input-group-prepend">
												<!-- <span class="input-group-text"><i class="far fa-calendar-alt"></i></span> -->
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Email</label>
										<input type="text" name="studentEmail" id="studentEmail" class="form-control">
									</div>
									<div class="form-group">
										<label for="inputName">Phone Number</label>
										<input type="text" name="studentPhoneNumber" id="studentPhoneNumber" class="form-control">
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<label for="inputName">Parent / Emergency Contact 1</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyName" id="studentEmergencyName" class="form-control" placeholder="Name">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyEmail" id="studentEmergencyEmail" class="form-control" placeholder="Email">													
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyPhone" id="studentEmergencyPhone" class="form-control" placeholder="Phone">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<label for="inputName">Parent / Emergency Contact 2</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyName2" id="studentEmergencyName2" class="form-control" placeholder="Name">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyEmail2" id="studentEmergencyEmail2" class="form-control" placeholder="Email">													
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyPhone2" id="studentEmergencyPhone2" class="form-control" placeholder="Phone">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Address</label>
										<input type="text" name="studentAddressLine1" id="studentAddressLine1" class="form-control" placeholder="Line 1">
									</div>
									<div class="form-group">
										<input type="text" name="studentAddressLine2" id="studentAddressLine2" class="form-control" placeholder="Line 2">
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentPostcode" id="studentPostcode" class="form-control" placeholder="Postcode">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<select class="form-control" name="studentState" id="studentState">
                                                        <?php
                                                            echo '<option value="" selected disabled>----- State -----</option>';
                                                            $query = "select * from state";
                                                            $result = mysqli_query($con, $query);
                                                            while($row = mysqli_fetch_assoc($result)){
                                                                echo '<option value="'.$row["state_id"].'" ';
                                                                echo '>'.$row["state_name"].'</option>';
                                                            }
                                                        ?>
                                                    </select>		
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<select class="form-control" name="studentCountry" id="studentCountry">
														<?php
															echo '<option value="" selected disabled>----- Country -----</option>';
															$query = "select * from country";
															$result = mysqli_query($con, $query);
															while($row = mysqli_fetch_assoc($result)){
																echo '<option value="'.$row["country_id"].'" ';
																echo '>'.$row["country_name"].'</option>';
															}
														?>
                                                    </select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="inputName">Date Of Enrollment</label>
												<div class="input-group">
													<input type="date" name="studentEnrollDate" id="studentEnrollDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
													<div class="input-group-prepend">
														<!-- <span class="input-group-text"><i class="far fa-calendar-alt"></i></span> -->
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="inputName">Batch Number</label>
												<div class="input-group">
													<!-- <input type="text" name="studentBatchNumber" id="studentBatchNumber" class="form-control" readonly value="<?php echo $row0["batch_code"]; ?>">													 -->
											
                                                    <select class="form-control" name="studentBatchNumber" id="studentBatchNumber">
                                                    <?php
                                                        echo '<option value="" selected disabled>----- Batch -----</option>';
                                                        $query = "select * from batch";
                                                        $result = mysqli_query($con, $query);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo '<option value="'.$row["batch_id"].'" ';                                                        
                                                            echo '>'.$row["batch_code"].'</option>';
                                                        }
                                                    ?>
                                                    </select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="studentHostel">Hostel &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="studentHostel" id="studentHostel" checked data-bootstrap-switch data-off-text="No" data-off-color="danger" data-on-text="Yes" data-on-color="success" data-size="medium">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<label for="studentFinancing">Financing &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="studentFinancing" id="studentFinancing" checked data-bootstrap-switch data-off-text="Cash" data-off-color="yellow" data-on-text="Loan" data-on-color="blue" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="studentStatus">Status &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="studentStatus" id="studentStatus" checked data-bootstrap-switch data-off-text="Inactive" data-off-color="danger" data-on-text="Active" data-on-color="success" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="studentDescription">Description / Remarks</label>
										<textarea name="studentDescription" id="studentDescription" class="form-control" rows="4"></textarea>
									</div>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<div class="col-md-6">
							<div class="card card-info">
								<div class="card-header">
									<h3 class="card-title">Login Information</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label for="studentUsername">User Name</label>
										<div class="input-group">
											<input type="text" name="studentUsername" id="studentUsername" class="form-control">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-user"></span>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="password">Password</label>
										<div class="input-group">
											<input type="password" name="account_password" id="account_password" class="form-control">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-lock"></span>
												</div>
											</div>	
										</div>
										
									</div>
									<div class="form-group">
										<label for="passwordRetype">Retype Password</label>
										<div class="input-group">
											<input type="password" name="studentPasswordRetype" id="studentPasswordRetype" class="form-control">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-lock"></span>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<button type="button" class="btn btn-danger btn-block" onclick="window.location='student.php'"><i class="fa fa-arrow-left"></i> BACK</button>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<button type="submit" id="submitstudentForm" class="btn btn-primary btn-block">Save and Enroll <i class="fa fa-arrow-right"></i></button>
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
			if(localStorage.getItem("usernameUsed")=="1"){
				toastr.error('Username has been registered by another user. Kindly change the username.');
				localStorage.clear();
			}
		};
		
		$(function() {
			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

			jQuery.validator.addMethod("samePassword", function(value, element) {
                return this.optional(element) || $("#account_password").val() == $("#studentPasswordRetype").val();
            });

			$('#add_student').validate({
				rules: {
					studentName: {
						required: true
					},
					studentEmail: {
						required: true,
						email: true
					},
					studentEnrollDate: {
						required: true
					},
					studentUsername: {
						required: true
					},
					account_password: {
						required: true
					},
					studentPasswordRetype: {
						required: true,
						samePassword: true
					},
					studentBatchNumber: {
						required: true
					}
				},
				messages: {
					studentName: {
						required: "Please enter a student name"
					},
					studentEmail: {
						required: "Please enter an email address",
						email: "Please enter a vaild email address"
					},
					studentEnrollDate: {
						required: "Please enter the enroll date"
					},
					studentUsername: {
						required: "Please enter the username"
					},
					account_password: {
						required: "Please provide a password"
					},
					studentPasswordRetype: {
						required: "Please provide a password",
						samePassword: "Retype Password must be same as the Password."
					},
					studentBatchNumber: {
						required: "Please select a batch number"
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