<?php
    include("../docs/_includes/connection.php");
    $page = "Tutor";

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    $query0 = "SELECT * FROM account WHERE account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
    $result0 = mysqli_query($con,$query0);
    if(!($row0 = mysqli_fetch_assoc($result0))){
        echo "<script>alert('Tutor was not found.')</script>";
        echo "<script>window.location= 'tutor.php'</script>";
        exit;
    }

    if (isset($_POST["tutorName"])) {
            $tutor_permission = "";
            if (isset($_POST["permission"])) {
                foreach ($_POST["permission"] as $selected_permission) {
                    $tutor_permission .= $selected_permission . ",";
                }
                if ($tutor_permission != "") {
                    $tutor_permission = substr($staff_permission, 0, -1);
                }
            }

            if(isset($_POST["tutorStatus"])){
                $tutorStatus = 'Active';
            } else {
                $tutorStatus = 'Inactive';
            }

            if($_POST["account_password"] != ""){
                $hashedPassword = password_hash(mysqli_real_escape_string($con,$_POST["account_password"]), PASSWORD_DEFAULT);
                $passwordQuery = "account_password = '" . $hashedPassword . "',";
            } else {
                $hashedPassword = "";
                $passwordQuery = "";
            }

            
            $query = "UPDATE account SET 
                        account_name = '" . mysqli_real_escape_string($con, $_POST["tutorName"]) . "', 
                        account_ic = '" . mysqli_real_escape_string($con, $_POST["tutorICNumber"]) . "', 
                        account_dob = '" . mysqli_real_escape_string($con, $_POST["tutorDOB"]) . "', 
                        account_email = '" . mysqli_real_escape_string($con, $_POST["tutorEmail"]) . "', 
                        account_phone_no = '" . mysqli_real_escape_string($con, $_POST["tutorPhoneNumber"]) . "', 
                        account_e_name = '" . mysqli_real_escape_string($con, $_POST["tutorEmergencyName"]) . "', 
                        account_e_phone_no = '" . mysqli_real_escape_string($con, $_POST["tutorEmergencyPhone"]) . "', 
                        account_address_line1 = '" . mysqli_real_escape_string($con, $_POST["tutorAddressLine1"]) . "', 
                        account_address_line2 = '" . mysqli_real_escape_string($con, $_POST["tutorAddressLine2"]) . "', 
                        account_postcode = '" . mysqli_real_escape_string($con, $_POST["tutorPostcode"]) . "',";

			if(isset($_POST["tutorState"]))
            	$query .= "account_state = '" . mysqli_real_escape_string($con, $_POST["tutorState"]) . "', ";
				
			if(isset($_POST["tutorCountry"]))	
                $query .= "account_country = '" . mysqli_real_escape_string($con, $_POST["tutorCountry"]) . "', ";
				$query .= "account_enroll_date = '" . mysqli_real_escape_string($con, $_POST["tutorEnrollDate"]) . "', 
						account_status = '" . $tutorStatus . "', 
                        account_remark = '" . mysqli_real_escape_string($con, $_POST["tutorDescription"]) . "', 
                        $passwordQuery
                        account_level_id = '2',
                        account_type = 'TUTOR' WHERE account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
            $result = mysqli_query($con, $query);

            echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful updated flag.
            echo "<script>window.location='tutor_edit.php?id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
            exit;
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Tutor Information</title>

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
							<h1>Tutor Information</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="tutor.php">Tutor</a></li>
								<li class="breadcrumb-item active">Tutor Registration</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_tutor" id="add_tutor" class="col-lg-12" method="POST">
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
										<input type="text" name="tutorName" id="tutorName" class="form-control"  value="<?php echo htmlspecialchars($row0["account_name"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">I/C Number</label>
										<input type="text" name="tutorICNumber" id="tutorICNumber" class="form-control" value="<?php echo htmlspecialchars($row0["account_ic"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">Date Of Birth</label>
										<div class="input-group">
											<input type="date" name="tutorDOB" id="tutorDOB" class="form-control" value="<?php echo date('Y-m-d', strtotime($row0["account_dob"])) ?>">
											<div class="input-group-prepend">
												<!-- <span class="input-group-text"><i class="far fa-calendar-alt"></i></span> -->
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Email</label>
										<input type="text" name="tutorEmail" id="tutorEmail" class="form-control" value="<?php echo htmlspecialchars($row0["account_email"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">Phone Number</label>
										<input type="text" name="tutorPhoneNumber" id="tutorPhoneNumber" class="form-control" value="<?php echo htmlspecialchars($row0["account_phone_no"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">Emergency Contact</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="tutorEmergencyName" id="tutorEmergencyName" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($row0["account_e_name"]) ?>">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="tutorEmergencyPhone" id="tutorEmergencyPhone" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($row0["account_e_phone_no"]) ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Address</label>
										<input type="text" name="tutorAddressLine1" id="tutorAddressLine1" class="form-control" placeholder="Line 1" value="<?php echo htmlspecialchars($row0["account_address_line1"]) ?>">
									</div>
									<div class="form-group">
										<input type="text" name="tutorAddressLine2" id="tutorAddressLine2" class="form-control" placeholder="Line 2" value="<?php echo htmlspecialchars($row0["account_address_line2"]) ?>">
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="tutorPostcode" id="tutorPostcode" class="form-control" placeholder="Postcode" value="<?php echo htmlspecialchars($row0["account_postcode"]) ?>">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
                                                    <select class="form-control" name="tutorState" id="tutorState">
                                                        <?php
                                                            echo '<option value="0" selected disabled>----- State -----</option>';
                                                            $query = "select * from state";
                                                            $result = mysqli_query($con, $query);
                                                            while($row = mysqli_fetch_assoc($result)){
                                                                echo '<option value="'.$row["state_id"].'" ';
                                                                if($row0["account_state"] == $row["state_id"])
																echo 'selected ';
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
                                                    <select class="form-control" name="tutorCountry" id="tutorCountry">
                                                    <?php
                                                        echo '<option value="0" selected disabled>----- Country -----</option>';
                                                        $query = "select * from country";
                                                        $result = mysqli_query($con, $query);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo '<option value="'.$row["country_id"].'" ';
                                                            if($row0["account_country"] == $row["country_id"])
																echo 'selected ';
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
													<input type="date" name="tutorEnrollDate" id="tutorEnrollDate" class="form-control" value="<?php echo date('Y-m-d', strtotime($row0["account_enroll_date"])) ?>">
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
												<label for="tutorStatus">Status &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="tutorStatus" id="tutorStatus" 
                                                    <?php 
                                                        if($row0["account_status"] == "Active")
                                                            echo "checked"; 
                                                    ?> 
                                                    data-bootstrap-switch data-off-text="Inactive" data-off-color="danger" data-on-text="Active" data-on-color="success" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="tutorDescription">Description / Remarks</label>
										<textarea name="tutorDescription" id="tutorDescription" class="form-control" rows="4" ><?php echo htmlspecialchars($row0["account_remark"]) ?></textarea>
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
										<label for="tutorUsername">User Name</label>
										<div class="input-group">
											<input type="text" name="tutorUsername" id="tutorUsername" class="form-control" value="<?php echo htmlspecialchars($row0["account_username"]) ?>" disabled>
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
											<input type="password" name="tutorPasswordRetype" id="tutorPasswordRetype" class="form-control">
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
													<button type="button" class="btn btn-danger btn-block" onclick="window.location='tutor.php'"><i class="fa fa-arrow-left"></i> BACK</button>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<button type="submit" id="submitTutorForm" class="btn btn-primary btn-block">UPDATE <i class="fa fa-arrow-right"></i></button>
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
        $(document).ready(function(){
            if(localStorage.getItem("Updated")=="1"){
                toastr.success('Tutor information has been updated successfully.');
                localStorage.clear();
            }
		});
        
		$(function() {
			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

            jQuery.validator.addMethod("samePassword", function(value, element) {
                return this.optional(element) || $("#account_password").val() == $("#tutorPasswordRetype").val();
            });

			$('#add_tutor').validate({
				rules: {
					tutorName: {
						required: true
					},
					tutorEmail: {
						required: true,
						email: true
					},
					tutorEnrollDate: {
						required: true
					},
					tutorUsername: {
						required: true
					},
					account_password: {
					},
					tutorPasswordRetype: {
                        samePassword: true
					}
				},
				messages: {
					tutorName: {
						required: "Please enter a tutor name"
					},
					tutorEmail: {
						required: "Please enter an email address",
						email: "Please enter a vaild email address"
					},
					tutorEnrollDate: {
						required: "Please enter the enroll date"
					},
					tutorUsername: {
						required: "Please enter the username"
					},
					account_password: {
					},
					tutorPasswordRetype: {
                        samePassword: "Retype Password must be same as the Password."
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